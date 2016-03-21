#!/usr/bin/php

<?php

/**
 * @author Pedro Alarcao <phacl151@gmail.com>
 */

if ( ! ini_get ( 'short_open_tag' ) )
{
    die( "please enable short_open_tag directive in php.ini\n" );
}

if ( ! ini_get ( 'register_argc_argv' ) )
{
    die( "please enable register_argc_argv directive in php.ini\n" );
}


if (function_exists('ini_set')) {
    @ini_set('display_errors', 1);

    $memoryInBytes = function ($value) {
        $unit = strtolower(substr($value, -1, 1));
        $value = (int) $value;
        switch($unit) {
            case 'g':
                $value *= 1024;
            // no break (cumulative multiplier)
            case 'm':
                $value *= 1024;
            // no break (cumulative multiplier)
            case 'k':
                $value *= 1024;
        }

        return $value;
    };

    $memoryLimit = trim(ini_get('memory_limit'));
    // Increase memory_limit if it is lower than 1GB
    if ($memoryLimit != -1 && $memoryInBytes($memoryLimit) < 1024 * 1024 * 1024) {
        @ini_set('memory_limit', '1G');
    }
    unset($memoryInBytes, $memoryLimit);
}

set_include_path (
    implode (
        PATH_SEPARATOR ,
        array (
            realpath ( dirname ( __FILE__ ) . "/build/" ) ,
            get_include_path () ,
        )
    )
);

require_once 'Classes/MakerFile.php';
require_once 'Classes/Config.php';

try
{
    $arrValid = array (
        'help' ,
        'status' ,
        'config-ini:' ,
        'database:' ,
        'schema:' ,
        'driver:' ,
        'framework:' ,
        'path:'
    );

    $_path = realpath ( __FILE__ );

    $maker = new \Classes\MakerFile( new \Classes\Config( getopt ( null , $arrValid ) , $_path ) );
    $maker->run ();

} catch ( \Exception $e )
{
    die( $e->getMessage () );
}
