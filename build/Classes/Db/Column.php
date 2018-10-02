<?php

namespace Classes\Db;

use Classes\AdapterConfig\AbstractAdapter;

/**
 * Colunas dos bancos
 *
 * @author Pedro Alarcao <phacl151@gmail.com>
 * @link   https://github.com/pedro151/orm-generator
 */
class Column
{

    const TypeNone = 0;
    const TypePHP = 1;
    const TypeDefault = 2;

    /**
     * Colunas dos bancos
     *
     * @author Pedro Alarcao <phacl151@gmail.com>
     */
    final private function __construct ()
    {
    }

    /**
     * create instance
     *
     * @return \Classes\Db\Column
     */
    public static function getInstance ()
    {
        return new Column();
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Classes\Db\Constrant[]
     */
    private $primarykey;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var boolean
     */
    private $nullable;

    /**
     * @var int
     */
    private $max_length;

    /**
     * @var string
     */
    private $column_default;

    /**
     * @var \Classes\Db\Constrant[]
     */
    private $dependences;

    /**
     * @var \Classes\Db\Constrant
     */
    private $refForeingkey;

    /**
     * @type string
     */
    private $sequence;

    /**
     * @return string
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * popula o
     *
     * @param $array
     */
    public function populate ( $array )
    {
        $this->name           = $array[ 'name' ];
        $this->type           = $array[ 'type' ];
        $this->nullable       = $array[ 'nullable' ];
        $this->max_length     = $array[ 'max_length' ];
        $this->column_default = $array[ 'column_default' ];

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrimaryKey ()
    {
        return !empty( $this->primarykey );
    }

    /**
     * @return boolean
     */
    public function isForeingkey ()
    {
        return !empty( $this->refForeingkey );
    }

    /**
     * @return boolean
     */
    public function hasDependence ()
    {
        return !empty( $this->dependences );
    }

    /**
     * @return boolean
     */
    public function hasColumnDefault ()
    {
        return !empty( $this->column_default );
    }

    /**
     * @return string
     */
    public function getColumnDefault ()
    {
        return $this->column_default ;
    }

    /**
     * @return string
     */
    public function getType ( $type = self::TypePHP )
    {
        switch ( $type )
        {
            case self::TypePHP:
                return AbstractAdapter::convertTypeToPHP ( $this->type );
            case self::TypeDefault:
                return AbstractAdapter::convertTypeToDefault ( $this->type );
            case self::TypeNone:
                return $this->type;
        }
    }

    /**
     * @param      $type
     * @param string $inPHP
     *
     * @return bool
     */
    public function equalType ( $type, $inPHP = self::TypeDefault )
    {
        return $this->getType ( $inPHP ) === $type;
    }

    /**
     * @param AbstractAdapter $type
     *
     * @return mixed
     */
    public function getTypeByConfig ( AbstractAdapter $type )
    {
        return $type->convertTypeToTypeFramework ( $this->getType ( false ) );
    }

    /**
     * @return string
     */
    public function getComment ()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment ( $comment )
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @param \Classes\Db\Constrant $primarykey
     */
    public function setPrimaryKey ( Constrant $primarykey )
    {
        $this->primarykey = $primarykey;

        return $this;
    }

    /**
     * @param \Classes\Db\Constrant $dependece
     */
    public function addDependece ( Constrant $dependece )
    {
        $this->dependences[] = $dependece;

        return $this;
    }

    /**
     * @param $constraint_name
     * @param $table_name
     * @param $column_name
     *
     * @return $this
     */
    public function createDependece ( $constraint_name, $table_name, $column_name, $database, $schema = null )
    {
        $objConstrantDependence = Constrant::getInstance ()
                                           ->populate (
                                               array (
                                                   'constrant' => $constraint_name,
                                                   'schema'    => $schema,
                                                   'table'     => $table_name,
                                                   'column'    => $column_name,
                                                   'database'  => $database
                                               )
                                           );

        $this->addDependece ( $objConstrantDependence );

        return $this;
    }

    /**
     * @param \Classes\Db\Constrant $reference
     */
    public function addRefFk ( Constrant $reference )
    {
        $this->refForeingkey = $reference;

        return $this;
    }

    /**
     * retorna as foreingkeys
     *
     * @return \Classes\Db\Constrant
     */
    public function getFks ()
    {
        return $this->refForeingkey;
    }

    /**
     * Retorna as dependencias da tabela
     *
     * @return \Classes\Db\Constrant[]
     */
    public function getDependences ()
    {
        return $this->dependences;
    }

    /**
     * @return bool
     */
    public function hasDependences ()
    {
        return (bool) count ( $this->dependences );
    }

    /**
     * Retorna a constrant da primarykey da tabela
     *
     * @return \Classes\Db\Constrant[]
     */
    public function getPrimaryKey ()
    {
        return $this->primarykey;
    }

    /**
     *
     */
    public function getMaxLength ()
    {
        return $this->max_length;
    }

    /**
     * @return bool
     */
    public function hasSequence ()
    {
        return (bool) $this->sequence;
    }

    /**
     * @return string
     */
    public function getSequence ()
    {
        return $this->sequence;
    }

    /**
     * @param string $sequence
     */
    public function setSequence ( $sequence )
    {
        $this->sequence = $sequence;
    }

    /**
     * @return boolean
     */
    public function isNullable ()
    {
        return $this->nullable;
    }

}
