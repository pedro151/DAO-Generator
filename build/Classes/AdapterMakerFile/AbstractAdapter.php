<?php

namespace Classes\AdapterMakerFile;

/**
 * @author Pedro Alarcao <phacl151@gmail.com>
 * @link   https://github.com/pedro151/orm-generator
 */
abstract class AbstractAdapter
{
    /**
     * @type AbstractAdapter[]
     */
    private static $_instance = array ();

    /**
     *
     */
    final private function __construct ()
    {
    }

    /**
     * @return \Classes\AdapterMakerFile\AbstractAdapter
     */
    public static function getInstance ()
    {
        $class = get_called_class ();
        if ( !isset( self::$_instance[ $class ] ) )
        {
            self::$_instance[ $class ] = new $class();
        }

        return self::$_instance[ $class ];
    }

    /**
     * @param \Classes\MakerFile $makerFile
     * @param \Classes\Db\DbTable $dbTable
     *
     * @return array
     */
    abstract public function parseRelation ( \Classes\MakerFile $makerFile, \Classes\Db\DbTable $dbTable );

    /**
     * @type string
     */
    protected $parentClass;

    /**
     * @type string nome do arquivo template
     */
    protected $fileTpl;

    /**
     * @type string nome do arquivo template
     */
    protected $parentFileTpl;

    /**
     * @type string
     */
    protected $pastName;

    /**
     * @var bool
     */
    protected $overwrite = false;

    /**
     * verifica se existe diretorio nesta makeFile
     *
     * @return bool
     */
    public function hasDiretory ()
    {
        return !empty( $this->pastName );
    }

    /**
     * @return string
     */
    public function getParentClass ()
    {
        return $this->parentClass;
    }


    /**
     * @return string
     */
    public function getFileTpl ()
    {
        return $this->fileTpl;
    }

    /**
     * @return string
     */
    public function getParentFileTpl ()
    {
        return $this->parentFileTpl;
    }

    /**
     * @return string
     */
    public function getPastName ()
    {
        return $this->pastName;
    }

    /**
     * @return bool
     */
    public function isOverwrite ()
    {
       return $this->overwrite;
    }

}
