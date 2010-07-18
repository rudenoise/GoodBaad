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
 * BASE SESSION : SESSION FEATURES
 *
 */
Class BaseSession
{
    /*function __construct()
    {
        
    }*/
    
    function check()
    {
        if (isset($_SESSION['ip']) && isset($_SESSION['userName']))
        {
            if (($_SESSION['userAgent'] == md5($_SERVER['HTTP_USER_AGENT'].SALT))&&($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) && ($_SESSION['userName'] != ''))
            {
                session_regenerate_id(true); # SETTING TRUE DELETES THE OLD SESSION FILE
                return true;
            }
            else
            {
                $this->end();
                header('Location:http://'. $_SERVER['HTTP_HOST'] .'/login');
                die();
            }
        }
        else
        {
            $_SESSION['last-location'] = $_GET['route'];
            header('Location:http://'. $_SERVER['HTTP_HOST'] .'/login');
            die();
            #return false;
        }
    }
    
    function set($args)
    {
        $_SESSION['ip'] = $args['ip'];
        $_SESSION['userName'] = $args['userName'];
        $_SESSION['userAgent'] = md5($args['userAgent'].SALT);
        
        if ($args['active'] == true) $_SESSION['userActive'] = true; else $_SESSION['userActive'] = false;
    }
    
    function end()
    {
        session_destroy();
    }
}
?>