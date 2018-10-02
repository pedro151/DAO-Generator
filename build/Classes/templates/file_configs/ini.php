;=====================READ ME!================================
;
; Default settings if not specified in any Terminal.
;
; @copyright ORM Generator-Pedro151
; @license   The MIT License (MIT)
; @link      https://github.com/pedro151/orm-generator
; @version   <?= $version ?><?= "\n" ?>
;=============SETTING ENVIRONMENT OF DEFAULT==================

[main]

; name framework used, which has the contents of the database configurations
; and framework template (Ex.: zf1, phalcon)
framework = "<?= $framework ?>"<?= "\n" ?>
; configuration environment you want to generate
environment = <?= $environment ?><?= "\n" ?>
; database driver name (Ex.: pgsql, mysql)
driver = '<?= $driver ?>'<?= "\n" ?>
; database host
host = <?= $host ?><?= "\n" ?>
; encoding
charset = UTF8
; database name
database = "<?= isset( $database ) ? $database : '' ?>"<?= "\n" ?>
; database schema name (one or more than one)
<?= isset( $schema ) ? 'schema = ' . $schema : ';schema = public' ?><?= "\n" ?>
; database user
username = <?= isset( $username ) ? $username : '' ?><?= "\n" ?>
; database password
password = <?= isset( $password ) ? $password : '' ?><?= "\n" ?>
; show status of implementation carried out after completing the process
status = false
; table name (parameter can be used more then once)
;tables=""
; specify where to create the files (default is current directory)
path = ""
;folder with the database driver name
folder-database = 0
;folder with the name
folder-name=''
;.ini file the framework configuration
framework-ini = ""
;the path to the directory of the framework library
framework-path-library = ""
;delete all files that do not belong to your Database due.
;clean-trash=0

namespace = ''

;=====================READ ME!================================
;
; Configurations 'none'
;
;=============================================================
;[none : main]
;
;framework configuration
;framework = "none"

