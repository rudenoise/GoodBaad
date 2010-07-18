<?php
/*
 *    ___          ___                        ___     
 *   /  /\        /__/\          ___         /  /\    
 *  /  /:/       |  |::\        /__/\       /  /:/    
 * /__/::\       |  |:|:\       \  \:\     /  /:/     
 * \__\/\:\    __|__|:|\:\       \  \:\   /  /:/  ___ 
 *    \  \:\  /__/::::| \:\  ___  \__\:\ /__/:/  /  /\
 *     \__\:\ \  \:\~~\__\/ /__/\ |  |:| \  \:\ /  /:/
 *     /  /:/  \  \:\       \  \:\|  |:|  \  \:\  /:/ 
 *    /__/:/    \  \:\       \  \:\__|:|   \  \:\/:/  
 *    \__\/      \  \:\       \__\::::/     \  \::/   
 *                \__\/           ~~~~       \__\/  
 *
 * V 1.0 � JOEL HUGHES 2008
 *
 * CONSOLE: SET UP
 *
 */
#error_reporting (0);

#ini_set('display_errors','Off');
#ini_set('log_errors','On');

#error_reporting (E_ALL);

date_default_timezone_set("Europe/London");

// CONSTANTS:
define ('DIR', DIRECTORY_SEPARATOR);

$site_path = realpath(dirname(__FILE__) . DIR) . DIR; # GET SITE PATH

define ('SITE_PATH', $site_path); # DEFINE SITE PATH

define ('SALT', 'jmvc'); # DEFINE SALT

define ('SITEEMAIL','site@goodbaad.com');

define ('TOTAL', 100);

// AUTOLOAD CLASSES
function __autoload($class_name)
{
    if (preg_match('/Controller/i',$class_name)) # IF "CONTROLLER" IS USED IN CLASS NAME LOAD A CONTROLLER CLASS
    {
        $class_name = str_replace('Controller','',$class_name);
        $filename = strtolower($class_name) . '_controller.php';
        $path = $file = SITE_PATH . 'controllers' . DIR . $filename;
    }
    elseif (preg_match('/Model/i',$class_name)) # IF "MODEL" IS USED IN CLASS NAME LOAD A MODEL CLASS
    {
        $class_name = str_replace('Model','',$class_name);
        $filename = strtolower($class_name) . '_model.php';
        $path = $file = SITE_PATH . 'models' . DIR . $filename;
    }
    else # OTHERWISE LOAD A BASE CLASS
    {
        $filename = strtolower($class_name) . '.php';
        $file = SITE_PATH . 'classes' . DIR . $filename;
    }
   
    if (file_exists($file) == false) # CHECK FILE EXISTS
    {
        return false;
    }

    require_once ($file); # INCLUDE THE CLASS FILE
}

$security = new Security;

/*
 *
 * LOAD CONTROLLER
 *
 */

$request['Controller'] = 'console';

if (isset($argv[1]))
{
    $args = split('[,]',$argv[1]);
    
    if (count($args)>1)
    {
        $request['Method'] = array_shift($args);
        
        $request['Args'] = $args;
    }
    else
    {
        $request['Method'] = $args[0];
    }
}
else
{
    $request['Method'] = 'index';
}

$class = ucwords($request['Controller'])."Controller";

$ctrl = new $class($class,$request);

$action = $request['Method'];

if (isset($request['Args']))
{
    $ctrl->$action($request['Args']);
}
else
{
    $ctrl->$action();
}

?>