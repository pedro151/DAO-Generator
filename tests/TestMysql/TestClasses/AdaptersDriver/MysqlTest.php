<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 16/02/16
 * Time: 11:33
 */

namespace TestMysql\TestClasses\AdaptersDriver;

use Classes\AdapterConfig\None;
use Classes\AdaptersDriver\Mysql;

/**
 * Class PgsqlTest
 *
 * @package TestClasses\AdaptersDriver
 */
class MysqlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @type Pgsql
     */
    private $objDriver;

    /**
     * http://framework.zend.com/manual/1.12/en/zend.db.adapter.html#zend.db.adapter.example-database
     */
    protected function setUp ()
    {
        $this->pdo = new \PDO( $GLOBALS[ 'db_dsn' ], $GLOBALS[ 'db_username' ], $GLOBALS[ 'db_password' ] );
        $this->pdo->setAttribute ( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        $this->tearDown ();

        $this->pdo->exec (
            "CREATE TABLE accounts (
       account_name      VARCHAR(100) NOT NULL PRIMARY KEY
);

CREATE TABLE products (
      product_id        INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
      product_name      VARCHAR(100)
);

CREATE TABLE bugs (
    bug_id            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    bug_description   VARCHAR(100),
    bug_status        VARCHAR(20),
    reported_by       VARCHAR(100),
    assigned_to       VARCHAR(100),
    verified_by       VARCHAR(100),
  FOREIGN KEY (reported_by)
	REFERENCES accounts(account_name),
  FOREIGN KEY (assigned_to)
	REFERENCES accounts(account_name),
  FOREIGN KEY (verified_by)
	REFERENCES accounts(account_name)
);

CREATE TABLE bugs_products (
    bug_id            INTEGER NOT NULL ,
    product_id        INTEGER NOT NULL,
    PRIMARY KEY       (bug_id, product_id),
    FOREIGN KEY (bug_id)
		REFERENCES bugs(bug_id),
	FOREIGN KEY (product_id)
		REFERENCES products(product_id)
);");
    }

    /**
     *
     */
    protected function tearDown ()
    {
        $this->pdo->exec (
            "DROP TABLE IF EXISTS bugs_products;
             DROP TABLE IF EXISTS  bugs;
             DROP TABLE IF EXISTS  products;
             DROP TABLE IF EXISTS  accounts;"
        );
    }

    /**
     * @return \Classes\AdaptersDriver\Mysql
     */
    protected function getDataBaseDrive ()
    {
        if ( !$this->objDriver )
        {
            $arrConfig = array (
                'driver'    => 'pdo_mysql',
                'host'      => $GLOBALS[ 'host' ],
                'database'  => $GLOBALS[ 'dbname' ],
                'username'  => $GLOBALS[ 'db_username' ],
                'socket'    => null,
                'password'  => $GLOBALS[ 'db_password' ],
                'namespace' => ''
            );

            $this->objDriver = new Mysql( new None( $arrConfig ) );
            $this->objDriver->runDatabase ();
        }

        return $this->objDriver;
    }

    /**
     *
     */
    public function testPDO ()
    {
        $this->assertTrue ( $this->getDataBaseDrive ()->getPDO () instanceof \PDO );
    }

    public function testRunDatabase ()
    {
        $daoClone = clone $this->getDataBaseDrive ();
        $this->getDataBaseDrive ()->runDatabase ();
        $this->assertEquals ( $daoClone, $this->getDataBaseDrive () );
    }

    /**
     *
     */
    public function testSQLConstrants ()
    {
        $arrConstrants = $this->getDataBaseDrive ()->getListConstrant ();


        foreach ( $arrConstrants as $index => $contrstrant )
        {
            if ( $contrstrant[ 'table_name' ] == 'bugs' )
            {
                switch ( $contrstrant[ 'constraint_type' ] )
                {
                    case "FOREIGN KEY":
                    {
                        $this->assertEquals ( 'accounts', $contrstrant[ "foreign_table" ] );
                        $this->assertEquals ( 'account_name', $contrstrant[ "foreign_column" ] );
                        break;
                    }
                    case "PRIMARY KEY":
                    {
                        $this->assertEquals ( 'bug_id', $contrstrant[ "column_name" ] );
                    }

                };
            }
        }
    }

    public function testParseConstrant ()
    {
        $objAdapterDriver = $this->getDataBaseDrive();
    }

    /**
     *
     */
    public function testGetListNameTable ()
    {
        $this->assertTrue (
            is_array (
                $this->getDataBaseDrive ()
                    ->getListNameTable ()
            )
        );
        $this->assertTrue (
            count ( $this->getDataBaseDrive ()->getListNameTable () )
            > 0
        );
    }

    /**
     *
     */
    public function testGetListColumns ()
    {
        $this->assertTrue ( is_array ( $this->getDataBaseDrive ()->getListColumns () ) );
    }


    public function testSQLSequence ()
    {
        $this->assertEquals (
            'bugs_bug_id_seq', $this->getDataBaseDrive ()
            ->getSequence ( 'bugs', 'bug_id' )
        );
        $this->assertEquals (
            'products_product_id_seq', $this->getDataBaseDrive ()
            ->getSequence ( 'products', 'product_id' )
        );
    }

    /**
     *
     */
    public function testGetTables ()
    {
        $this->assertTrue (
            $this->getDataBaseDrive ()->getTable ( "accounts" )
            instanceof
            \Classes\Db\DbTable
        );
        $arrTables = $this->getDataBaseDrive ()->getTables ();
        $this->assertTrue (
            $arrTables[ "accounts" ] instanceof
            \Classes\Db\DbTable
        );
    }

    /**
     *
     */
    public function testTotalTables ()
    {
        $this->assertTrue ( is_int ( $this->getDataBaseDrive ()->getTotalTables () ) );
    }
}
