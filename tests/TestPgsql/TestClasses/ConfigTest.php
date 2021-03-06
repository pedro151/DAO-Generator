<?php
/**
 * Created by PhpStorm.
 * User: PEDRO151
 * Date: 20/02/2016
 * Time: 02:35
 */

namespace TestPgsql\TestClasses;


use Classes\AdaptersDriver\Pgsql;
use Classes\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $basePath;

    protected function setUp ()
    {
        $this->basePath = __DIR__ . '/../../';
    }

    /**
     * @param $param1
     * @param $param2
     *
     * @return mixed
     */
    private function getReflectionMethodParseConfigEnv ( $configTemp, $argv )
    {
        $obj = $this->getMockBuilder ( 'Classes\Config' )
                    ->disableOriginalConstructor ()
                    ->getMock ();


        $reflectionMethod = new \ReflectionMethod( 'Classes\Config', 'parseConfigEnv' );
        $reflectionMethod->setAccessible ( true );

        return $reflectionMethod->invokeArgs (
            $obj,
            array (
                $configTemp,
                $argv
            )
        );
    }

    public function testAdapterDriver ()
    {
        $config = new Config(
            array (
                'framework' => 'none',
                'database'  => $GLOBALS[ 'dbname' ],
                'driver'    => 'pgsql'
            ), $this->basePath, 3
        );

        $driver = $config->getAdapterDriver ( $config->getAdapterConfig () );
        $table  = $driver->getTables ();
        $this->assertTrue ( $driver instanceof Pgsql );
        $this->assertTrue ( is_array ( $table ) );
    }

    public function testAdapterConfig ()
    {
        $config    = new Config(
            array (
                'database' => $GLOBALS[ 'dbname' ],
                'driver'   => 'pgsql'
            ), $this->basePath, 2
        );
        $config    = $config->getAdapterConfig ();
        $strAuthor = $config->author;
        $this->assertTrue ( $strAuthor == ucfirst ( get_current_user () ) );
    }

    public function testParseConfigEnvDefault ()
    {
        $param1 = array ( 'main' => 'configs' );
        $param2 = array ();
        $resp   = $this->getReflectionMethodParseConfigEnv ( $param1, $param2 );
        $this->assertEquals ( 'configs', $resp );
    }

    public function testParseConfigEnvArgs ()
    {
        $param1 = array (
            'main'    => array (
                "framework"  => "zf1",
                "database"   => "main",
                "config-env" => "config2"
            ),
            'config1' => array (
                "extends"  => "main",
                "database" => "config1"
            ),
            'config2' => array (
                "extends"  => "main",
                "database" => "config2"
            ),
        );
        $param2 = array ( 'config-env' => 'config1' );

        $expectedConfig1 = array (
            "extends"    => "main",
            "framework"  => "zf1",
            "config-env" => "config2",
            "database"   => "config1"
        );
        $resp1           = $this->getReflectionMethodParseConfigEnv ( $param1, $param2 );
        $this->assertEquals ( $expectedConfig1, $resp1 );

        $expectedConfig2 = array (
            "extends"    => "main",
            "framework"  => "zf1",
            "config-env" => "config2",
            "database"   => "config2"
        );
        $resp2           = $this->getReflectionMethodParseConfigEnv ( $param1, array () );
        $this->assertEquals ( $expectedConfig2, $resp2 );

    }
}
