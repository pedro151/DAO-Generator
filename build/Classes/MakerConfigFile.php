<?php
namespace Classes;


use Classes\Maker\AbstractMaker;

require_once 'Maker/AbstractMaker.php';

/**
 * @author Pedro Alarcao <phacl151@gmail.com>
 * @link   https://github.com/pedro151/DAO-Generator
 */
class MakerConfigFile extends AbstractMaker
{

    /**
     * caminho de pastas Base
     *
     * @type string
     */
    private $baseLocation = '';

    private $template = 'Classes\templates\file_configs\ini.php';

    private $configs = array (
        'name'        => 'config',
        'framework'   => 'none',
        'driver'      => 'pgsql',
        'environment' => 'dev',
        'host'        => 'localhost',
        'database'    => null,
        //'schema'     => null,
        'username'    => null,
        'password'    => null
    );

    public function __construct ( $argv, $basePath )
    {
        $this->argv = $this->parseConfig ( $basePath, $argv );
    }

    /**
     * Analisa e estrutura a Configuracao do generate
     *
     * @param string $_path
     * @param array $argv
     *
     * @return array
     * @throws \Exception
     */
    private function parseConfig ( $basePath, $argv )
    {
        $this->baseLocation = dirname ( $basePath );

        $arrayIO = array_diff_key ( $this->configs, $argv );
        foreach ( $arrayIO as $index => $config )
        {
            echo "Please enter the value for {$index} [{$config}]: ";
            $line = trim ( fgets ( STDIN ) );
            if ( !empty( $line ) )
            {
                $this->configs[ $index ] = strtolower ( $line );
            }
        }

        return $argv + array_filter ( $this->configs );
    }

    public function run ()
    {
        $path = $this->baseLocation . DIRECTORY_SEPARATOR . "configs";
        self::makeDir ( $path );
        self::makeSourcer (
            $path . DIRECTORY_SEPARATOR . $this->argv[ 'name' ] . '.ini',
            $this->getParsedTplContents ( $this->template, $this->argv )
        );

    }
}