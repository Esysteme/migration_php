<?php

/**
 * Index
 *
 * The Front Controller for handling every request
 *
 * PHP versions 5 required
 *
 * GLIALE(tm) : Rapid Development Framework (http://gliale.com)
 * Copyright 2007-2010, Esysteme Software Foundation, Inc. (http://www.esysteme.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2007-2010, Esysteme Software Foundation, Inc. (http://www.esysteme.com)
 * @link          http://www.gliale.com GLIALE(tm) Project
 * @package       gliale
 * @subpackage    gliale.app.webroot
 * @since         Gliale(tm) v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace glial;
date_default_timezone_set("Europe/Paris");

define("TIME_START", microtime(true));
error_reporting(-1);
ini_set('display_errors', 1);


//to know if we are in cli
define('IS_CLI', PHP_SAPI === 'cli');

define('EOL',(IS_CLI)? PHP_EOL: "<br />");



//Use the DS to separate the directories in other defines
define('DS', DIRECTORY_SEPARATOR);

/**
 * These defines should only be edited if you have gliale installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

if (IS_CLI)
{
	if (substr($_SERVER["SCRIPT_FILENAME"],0,1) === "/")
	{
		define('ROOT',dirname(dirname(dirname(htmlspecialchars($_SERVER["SCRIPT_FILENAME"], ENT_QUOTES, "utf-8")))));
		define('APP_DIR',dirname(dirname(htmlspecialchars($_SERVER["SCRIPT_FILENAME"], ENT_QUOTES, "utf-8"))));
	}
	else
	{
		define('ROOT',dirname(dirname(dirname($_SERVER["PWD"]."/".$_SERVER["SCRIPT_FILENAME"]))));
		define('APP_DIR',dirname(dirname($_SERVER["PWD"]."/".$_SERVER["SCRIPT_FILENAME"])));
	}
}
else
{
	define('ROOT',dirname(dirname(dirname(htmlspecialchars($_SERVER["SCRIPT_FILENAME"], ENT_QUOTES, "utf-8")))));
	define('APP_DIR',dirname(dirname(htmlspecialchars($_SERVER["SCRIPT_FILENAME"], ENT_QUOTES, "utf-8"))));
}


//echo "ROOT: ".ROOT."\n"; 
//echo "APP_DIR: ".APP_DIR."\n"; 

//temp directory
define("TMP", ROOT . DS . "tmp".DS);
define("DATA", ROOT . DS . "data".DS);

//The actual directory name for the "app".


//The actual directory name for the "config".
define('CONFIG', ROOT . DS . "configuration" . DS);


//The actual directory name for the extern "library".
define('LIBRARY', ROOT . DS . "library" . DS);



//The absolute path to the "gliale" directory.
define('CORE_PATH', ROOT . DS . "system" . DS);
define('LIB', CORE_PATH . "lib" . DS);

//echo "CORE_PATH: ".CORE_PATH."\n"; 
//The absolute path to the webroot directory.
//define('WEBROOT_DIR', basename(dirname(__FILE__)) . DS);


//echo "WEBROOT_DIR: ".WEBROOT_DIR."\n"; die();
/*
  $path = explode("=", $_SERVER['QUERY_STRING']);
  $www_root = str_replace($path[1], "", $_SERVER['REQUEST_URI']);
 * 
 * 
 */

require(CONFIG."webroot.config.php");



define('FARM1', "http://farm1.gdol.eu/");

/*
switch($_SERVER['SERVER_NAME'])
{

        case 'www.estrildidae.net': $taxo_tree = "/1/1/9/101/438"; break;
        case 'www.gdol.eu': $taxo_tree = "/"; break;
        default:  $taxo_tree = "/"; break;
}
define('TAXO_BRANCH', $taxo_tree);
*/

define('IMG', WWW_ROOT . "image" . DS);
define('CSS', WWW_ROOT . "css" . DS);
define('FILE', WWW_ROOT . "file" . DS);
define('VIDEO', WWW_ROOT . "video" . DS);
define('JS', WWW_ROOT . "js" . DS);




if (isset($_GET['url']) && $_GET['url'] === 'favicon.ico')
{
	exit;
} else
{
	if (!include(CORE_PATH . 'boot.php'))
	{
		trigger_error("Gliale core could not be found. Check the value of CORE_PATH in application/webroot/index.php.  It should point to the directory containing your " . DS . "gliale core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
	}
}


