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
 * INDEX: SET UP
 *
 */
error_reporting (0);

ini_set('display_errors','Off');
ini_set('log_errors','On');

#error_reporting (E_ALL);

date_default_timezone_set("Europe/London");

// CONSTANTS:
define ('DIR', DIRECTORY_SEPARATOR);

$site_path = realpath(dirname(__FILE__) . DIR) . DIR; # GET SITE PATH

define ('SITE_PATH', $site_path); # DEFINE SITE PATH

define ('SALT', 'jmvc'); # DEFINE SALT

define ('SITEEMAIL','site@goodbaad.com');

define ('TOTAL', 100);

define ('RPXKEY', ''); # LIVE SITE
define ('RPXURL', ''); # LIVE SITE


#ini_set('gc_maxlifetime', 86400); # SET SESSION LENGTH TO ONE DAY (43200 FOR 1/2)
#ini_set('session.save_handler','mm'); # HIDE SESSION DATA

session_set_cookie_params(43200,'/'); # COOKIE LIFETIME: HALF DAY

session_start();

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

// BROWSER DETECT FUNCTION
function mobileBrowser()
{
    $mobile_browser = '0';

    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) $mobile_browser++;

    if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) $mobile_browser++;

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),' ppc;')>0) $mobile_browser++;
    
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'iemobile')>0) $mobile_browser++;

    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
    $mobile_agents = array(
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac', 'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno', 'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-', 'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-', 'newt','noki','oper','palm','pana','pant','phil','play','port','prox', 'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar', 'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-', 'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp', 'wapr','webc','winw','winw','xda','xda-');

    if(in_array($mobile_ua,$mobile_agents)) $mobile_browser++;
    
    if (isset($_SERVER['ALL_HTTP'])) if (strpos(strtolower($_SERVER['ALL_HTTP']),'operamini')>0) $mobile_browser++;
    
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) $mobile_browser=0;
    
    if($mobile_browser>0) return true; else return false; 
}

// GLOBAL DEBUG ARRAY HTML
function debug($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

$security = new Security;

require_once(SITE_PATH .  "router.php");

/*
 *
 * LOAD CONTROLLER
 *
 */

#debug($request);

if($request['Auth'] == true) # CHECK TO SEE IF PAGES REQUIRE AUTH, 
{
    $session = new BaseSession;
    
    $session->check(); # CHECK IF LOGGED IN, REDIRECT TO LOGIN IF NOT
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
