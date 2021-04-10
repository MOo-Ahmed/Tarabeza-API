<?php
/**
 *  @file    index.php
 *  @date    05/10/2020
 *  @version 1.0.0
 *  @PHP_Version 7.4.14
 */

// For performance measurements, returns the current Unix timestamp with microseconds
// set to TRUE, microtime() will return a float instead of a string.
define('APP_START', microtime(true));

// Define root path
define('ROOT', realpath(__DIR__));

define('_DB_PREFIX_',  '');

// Define directory separator
define('DS', DIRECTORY_SEPARATOR); 

// Bootstrap the application and auto load all files.
require_once(ROOT . DS . "vendor" .DS. "autoload.php");

// Include all PHP files from the config dir
$configDir = ROOT . DS . "Config" . DS;
foreach(glob($configDir . "*.php") as $file)
{
    $pathInfo = pathinfo($file);
    $GLOBALS[$pathInfo["filename"]] = require($file);
}

// Run the application 
\Core\Application::run();

// APP_START
define('APP_END', microtime(true));

//echo "<br>" . (APP_END - APP_START);
/*
if($configs["debug"]) 
{
	echo "<br>" . (APP_END - APP_START);
}
*/