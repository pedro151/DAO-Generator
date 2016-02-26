<?php
/**
 * Created by PhpStorm.
 * User: PEDRO151
 * Date: 20/02/2016
 * Time: 02:35
 */

namespace TestClasses;


use Classes\AdaptersDriver\Pgsql;
use Classes\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testAdapterDriver ()
    {
        $config = new Config( array (
            'database' => 'dao_generator' ,
            'schema'   => 'teste_dao' ,
            'driver'   => 'pgsql'
        ) );
        $driver = $config->getAdapterDriver ();
        $table = $driver->getTables ();
        $this->assertTrue ( $driver instanceof Pgsql );
        $this->assertTrue ( is_array ( $table ) );
    }

    public function testAdapterConfig ()
    {
        $config = new Config( array (
            'database' => 'dao_generator' ,
            'schema'   => 'teste_dao' ,
            'driver'   => 'pgsql'
        ) );
        $config = $config->getAdapterConfig();
        $strAuthor = $config->author;
        $this->assertTrue ( $strAuthor == 'Pedro' );
        $this->assertTrue( $config->lol ==  null);
    }
}
