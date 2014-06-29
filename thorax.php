<?php
/*
Plugin Name: thorax
Version: 0.1-alpha
Description: The new version of our record label management software.
Author: Alex Andrews <alex@recordsonribs.com>
Author URI: http://recordsonribs.com
Plugin URI: http://recordsonribs.com/thorax
Text Domain: thorax
Domain Path: /languages
*/

function ThoraxAutoloader($className)
{
    // An array of paths, relative to the current directory, with trailing slashes,
    // to search for autoload classes within.
    $paths = array(
        'src/'
    );

    if (stripos($className, "RecordsOnRibs") === false) {
        return;
    }

    foreach ($paths as $path) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $fileName .= dirname(__FILE__) . DIRECTORY_SEPARATOR . $path . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($fileName)) {
            require $fileName;
        }
    }
}

spl_autoload_register('ThoraxAutoloader');

define('RecordsOnRibs_Thorax_DIR', dirname(__FILE__));
define('RecordsOnRibs_Thorax_URL', plugin_dir_url(__FILE__));

$plugin = new RecordsOnRibs\Thorax\Thorax();;

$Thorax->initialize();