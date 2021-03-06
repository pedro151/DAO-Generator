<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 26/02/16
 * Time: 17:45
 */

namespace TestPgsql\TestClasses\AdapterMakerFile\ZendFrameworkOne;


class EntityTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInstace ()
    {
        $instance = \Classes\AdapterMakerFile\ZendFrameworkOne\Entity::getInstance ();
        $this->assertTrue ( $instance instanceof \Classes\AdapterMakerFile\ZendFrameworkOne\Entity );
        $this->assertTrue ( $instance->getPastName () == 'Entity' );
        $this->assertTrue ( $instance->getFilesFixeds('parentClass')->getFileName() == "EntityAbstract" );
        $this->assertTrue ( $instance->getFilesFixeds('parentClass')->getTpl() == "entity_abstract.php" );
        $this->assertTrue ( $instance->getFileTpl () == "entity.php" );
    }
}
