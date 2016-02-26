<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 18/02/16
 * Time: 18:56
 */

namespace TestClasses\AdapterMakerFile;

class DbTableTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstace ()
    {
        $instance = \Classes\AdapterMakerFile\DbTable::getInstance ();
        $this->assertTrue ( $instance instanceof \Classes\AdapterMakerFile\DbTable );
        $this->assertTrue ( $instance->getPastName () == "DbTable" );
        $this->assertTrue ( $instance->getFileTpl () == "dbtable.tpl" );
        $this->assertTrue ( $instance->getParentClass () == "TableAbstract" );
        $this->assertTrue ( $instance->getParentFileTpl () == "dbtable_abstract.tpl" );
    }
}
