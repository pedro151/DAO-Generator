<?php

namespace Classes\AdaptersDriver;

use Classes\AdapterConfig\AbstractAdapter;
use Classes\Db\Column;
use Classes\Db\Constrant;
use Classes\Db\DbTable;

require_once 'Classes/AdaptersDriver/AbsractAdapter.php';
require_once 'Classes/Db/Column.php';
require_once 'Classes/Db/Constrant.php';
require_once 'Classes/Db/DbTable.php';

/**
 * @author Pedro Alarcao <phacl151@gmail.com>
 * @link   https://github.com/pedro151/orm-generator
 */
class Mssql extends AbsractAdapter
{

    /**
     * @var int
     */
    protected $port;

    protected $schema = array ( 'public' );

    public function __construct ( AbstractAdapter $adapterConfig )
    {
        parent::__construct ( $adapterConfig );
        if ( $adapterConfig->hasSchemas () ) {
            $this->schema = $adapterConfig->getSchemas ();
        }

    }


    /**
     * converts Mssql data types to Simple data types
     *
     * @param string $str
     *
     * @return string
     */
    protected function convertTypeToSimple ( $str )
    {
        $res = '';
        if ( preg_match ( '/(tinyint\(1\)|bit)/', $str ) ) {
            $res = 'boolean';
        }
        elseif ( preg_match ( '/(timestamp|blob|char|enum)/', $str ) ) {
            $res = 'string';
        }
        elseif ( preg_match ( '/(text)/', $str ) ) {
            $res = 'text';
        }
        elseif ( preg_match ( '/(decimal|numeric|float|double|money|smallmoney)/', $str ) ) {
            $res = 'float';
        }
        elseif ( preg_match ( '#^(?:tiny|small|medium|long|big|var)?(\w+)(?:\(\d+\))?(?:\s\w+)*$#', $str, $matches ) ) {
            $res =  $matches[ 1 ];
        }
        elseif ( preg_match ( '/(date)/', $str ) ) {
            $res = 'date';
        }
        elseif ( preg_match ( '/(datetime)/', $str ) ) {
            $res = 'datetime';
        }
        else {
            print "Can't convert column type to Simple - Unrecognized type: $str";
        }

        return $res;
    }


    protected function getHost(){
        $host = $this->host;
        if (!empty($this->port)) {
            $seperator = ':';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $seperator = ',';
            }
            $host .=  $seperator . $this->port;
            unset($this->port);
        }

        return $host;
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function getPDOString ()
    {
        return sprintf (
            "mssql:host=%s;dbname=%s" ,
            $this->getHost() ,
            $this->database
        );
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function getPDOSocketString ()
    {
        // TODO: implement here
        return "";
    }

    /**
     * @inheritDoc
     * @return string[]
     */
    public function getListNameTable ()
    {
        if ( empty( $this->tableList ) )
        {

            $sqlTables = ! empty( $this->tablesName )
                ? "AND table_name IN ( $this->tablesName )" : '';

            $strSchema = implode ( "', '" , $this->schema );

            $this->tableList = $this->getPDO ()
                                    ->query (
                                        "SELECT table_schema,
              table_name
             FROM {$this->database}.information_schema.tables
             WHERE
              table_type = 'BASE TABLE'
              AND table_schema IN ( '$strSchema' ) $sqlTables
              ORDER by
               table_schema,
               table_name
              ASC"
                                    )
                                    ->fetchAll ();
        }

        return $this->tableList;
    }

    /**
     * retorna multiplos arrays com dados da column em array
     *
     * @return array[]
     */

    /**
     * retorna multiplos arrays com dados da column em array
     *
     * @return array
     */
    public function getListColumns ()
    {
        $sqlTables = ! empty( $this->tablesName )
            ? "AND c.table_name IN ( $this->tablesName )" : '';
        $strSchema = implode ( "', '" , $this->schema );

        return $this->getPDO ()
                    ->query (
                        "SELECT distinct
	c.table_schema,
	c.table_name,
	c.column_name ,
	c.data_type,
	c.column_default,
	is_nullable,
	character_maximum_length AS max_length
		FROM
		{$this->database}.INFORMATION_SCHEMA.TABLES AS st
		INNER JOIN  {$this->database}.INFORMATION_SCHEMA.COLUMNS AS c
		ON st.table_name=c.table_name and st.table_type = 'BASE TABLE'
		 $sqlTables and  c.table_schema IN ('$strSchema')
		order by c.table_name asc"
                    )
                    ->fetchAll ( \PDO::FETCH_ASSOC );
    }

    /**
     * retorna o numero total de tabelas
     *
     * @return int
     */
    public function getTotalTables ()
    {
        if ( empty( $this->totalTables ) )
        {
            $sqlTables = ! empty( $this->tablesName )
                ? "AND table_name IN ( $this->tablesName )" : '';

            $strSchema = implode ( "', '" , $this->schema );

            $this->totalTables = $this->getPDO ()
                                      ->query (
                                          "SELECT COUNT(table_name)  AS total
             FROM {$this->database}.INFORMATION_SCHEMA.TABLES
             WHERE
              table_type = 'BASE TABLE'
              AND table_schema IN ( '" . $strSchema . "' ) $sqlTables"
                                      )
                                      ->fetchColumn ();
        }

        return (int) $this->totalTables;
    }

    public function getSequence ( $table , $column , $schema = 0 )
    {
        $return = $this->getPDO ()
                       ->query (
                           "SELECT is_identity FROM sys.columns WHERE object_id = object_id('{$schema}.{$table}')  AND name = '{$column}';"
                       )
                       ->fetchColumn();

        if ( ! $return )
        {
            return;
        }

        return "{$table}_{$column}_seq";
    }

    public function getListConstrant ()
    {
        $sqlTables = ! empty( $this->tablesName )
            ? "AND tc.table_name IN ( $this->tablesName )" : '';
        $strSchema = implode ( "', '" , $this->schema );

        return $this->getPDO ()
                    ->query (
                        "
SELECT DISTINCT
                tc.constraint_type,
                tc.constraint_name,
                tc.table_schema,
                tc.table_name,
                kcu.column_name,
		        ccu.table_schema AS foreign_schema,
                ccu.table_name AS foreign_table,
                ccu.column_name as foreign_column
            FROM
                {$this->database}.INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
            INNER JOIN {$this->database}.INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
                      ON tc.constraint_name = kcu.constraint_name
                       AND tc.table_schema IN ('$strSchema')
                       AND tc.constraint_type IN ('PRIMARY KEY')
                       $sqlTables
            INNER JOIN {$this->database}.INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE AS ccu
                      ON tc.constraint_name  = ccu.constraint_name
                      ORDER by tc.table_schema;"
                    )
                    ->fetchAll ( \PDO::FETCH_ASSOC );
    }


}
