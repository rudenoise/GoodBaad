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
 * ROUTER
 * 
 */

$confArr = array( # THE CONF ARRAY DESCRIBES THE PERMITTED ROUTES THROUGH THE SITE (ALSO USE WITH NAV COMPONENT!)
    'home' => array(
        'Controller' => 'home',
        'Methods' => array(
            'index' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'about' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'contact' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'terms' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'worldometer' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            )
        ),
        'Model' => true
    ),
    'votes' =>  array(
        'Methods' => array(
            'index' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'search' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            ),
            'cast' => array(
                'Args' => array('guid','opinion'),
                'Auth' => true,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            )
        ),
        'Model' => true
    ),
    'account' =>  array(
        'Methods' => array(
            'index' => array(
                'Args' => array(),
                'Auth' => true,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            ),
            'login' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            ),
            'logout' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'signup' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            ),
            'activate' => array(
                'Args' => array('username','key'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'resendactivation' => array(
                'Args' => array('username'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'resetpassword' => array(
                'Args' => array(),
                'Auth' => true,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            ),
            'invite' => array(
                'Args' => array(),
                'Auth' => true,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            ),
            'rpx' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            ),
            'newname' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array('post')
            )
        ),
        'Model' => true
    ),
    'topics' => array(
        'Controller' => 'topics',
        'Methods' => array(
            'index' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'individual' => array(
                'Args' => array('handle'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            )
        ),
        'Model' => true
    ),
    'tags' => array(
        'Controller' => 'tags',
        'Methods' => array(
            'index' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'starting' => array(
                'Args' => array('letter'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'name' => array(
                'Args' => array('name'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            )
        ),
        'Model' => true
    ),
    'users' => array(
        'Controller' => 'users',
        'Methods' => array(
            'index' => array(
                'Args' => array(),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'individual' => array(
                'Args' => array('username'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'follow' => array(
                'Args' => array('username'),
                'Auth' => true,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            ),
            'history' => array(
                'Args' => array(),
                'Auth' => true,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            )
        ),
        'Model' => true
    ),
    'find' => array(
        'Controller' => 'find',
        'Methods' => array(
            'search' => array(
                'Args' => array('string'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'xhtml',
                'Expected_data' => array()
            )
        ),
        'Model' => true
    ),
    'api' => array(
        'Controller' => 'api',
        'Methods' => array(
            'vote' => array(
                'Args' => array('json'),
                'Auth' => false,
                'Components' => null,
                'Type' => 'json',
                'Expected_data' => array()
            )
        ),
        'Model' => true
    )
);

if (!empty($_GET))
{
    $siteRoot = strtolower($_GET['route']);
    $siteRoot = $security->paranoid($siteRoot,array('/','-','_','.','{','}',':','"',',')); # CLEANSE INPUT
    $siteRoot = trim($siteRoot, '/\\');
    
    $parts2 = explode('/', $siteRoot); # BREAK UP INPUT
    unset($route);
    #debug($parts2);
    
    if (($parts2[0] == 'logout')&&(!isset($parts2[1])))
    {
        $parts[0] = 'account';
        $parts[1] = 'logout';
    }
    elseif (($parts2[0] == 'login')&&(!isset($parts2[1])))
    {
        $parts[0] = 'account';
        $parts[1] = 'login';
    }
    elseif (($parts2[0] == 'about')&&(!isset($parts2[1])))
    {
        $parts[0] = 'home';
        $parts[1] = 'about';
    }
    elseif (($parts2[0] == 'contact')&&(!isset($parts2[1])))
    {
        $parts[0] = 'home';
        $parts[1] = 'contact';
    }
    elseif (($parts2[0] == 'terms-and-conditions')&&(!isset($parts2[1])))
    {
        $parts[0] = 'home';
        $parts[1] = 'terms';
    }
    elseif (($parts2[0] == 'worldometer')&&(!isset($parts2[1])))
    {
        $parts[0] = 'home';
        $parts[1] = 'worldometer';
    }
    elseif (($parts2[0] == 'signup')&&(!isset($parts2[1])))
    {
        $parts[0] = 'account';
        $parts[1] = 'signup';
    }
    elseif (($parts2[0] == 'activate')&&(isset($parts2[1]))&&(isset($parts2[2]))&&(!isset($parts2[3])))
    {
        $parts[0] = 'account';
        $parts[1] = 'activate';
        $parts[2] = $parts2[1];
        $parts[3] = $parts2[2];
    }
    elseif (($parts2[0] == 'topics')&&(isset($parts2[1]))&&(!isset($parts2[2])))
    {
        $parts[0] = 'topics';
        $parts[1] = 'individual';
        $parts[2] = $parts2[1];
    }
    elseif (($parts2[0] == 'users')&&(isset($parts2[1]))&&(!isset($parts2[2])))
    {
        if ($parts2[1] == 'history')
        {
            $parts[0] = 'users';
            $parts[1] = 'history';
        }
        else
        {
            $parts[0] = 'users';
            $parts[1] = 'individual';
            $parts[2] = $parts2[1];
        }
    }
    elseif ($parts2[0] == 'find')
    {
        $parts[0] = 'find';
        $parts[1] = 'search';
        $parts[2] = $_GET;
    }
    elseif ($parts2[0] == 'tags')
    {
        $parts[0] = 'tags';
        $parts[1] = 'starting';
        
        if (isset($parts2[1]))
        {
            $parts[2] = $parts2[1];
        }
    }
    elseif ($parts2[0] == 'tag')
    {
        
        $parts[0] = 'tags';
        $parts[1] = 'name';
        
        if (isset($parts2[1]))
        {
            $parts[2] = $parts2[1];
        }
    }
    else
    {
        $parts = $parts2;
    }
}
else
{
    $parts = array();
}

if (empty($parts)) # NO REQUEST: GOT TO HOME PAGE
{
    $request['Controller'] = "home";
    $request['Method'] = "index";
    $request['Components'] = $confArr['home']['Methods'][$request['Method']]['Components'];
    $request['Type'] = $confArr['home']['Methods'][$request['Method']]['Type'];
    $request['Expected_data'] = $confArr['home']['Methods'][$request['Method']]['Expected_data'];
    $request['Auth'] = false;
    $request['Model'] = $confArr['home']['Model'];
    
    $_POST = null; # EMPTY POST ARR
}
else #  DEAL WITH REQUEST
{
    #$parts = $parts3;
    
    if (array_key_exists($parts[0], $confArr)) # CHECK CONTROLLER EXISTS
    {
        $request['Controller'] = $parts[0];
        
        if (!empty($parts[1])) # CHECK IF ANY METHOD HAS BEEN REQUESTED
        {
            if (array_key_exists($parts[1], $confArr[$parts[0]]['Methods'])) # CHECK THERE SHOULD BE A METHOD
            {
                $request['Method'] = $parts[1];
                
                if (isset($parts[2])) # CHECK ARGS ARE BEING PASSED
                {
                    if (count($confArr[$parts[0]]['Methods'][$parts[1]]['Args']) > 0) # CHECK ARGS ARE EXPECTED
                    {
                        $args = array_splice($parts, 2, count($parts));
                        
                        if (count($args) <= count($confArr[$parts[0]]['Methods'][$parts[1]]['Args'])) # CHECK THERE ARE NOT TOO MANY ARGS
                        {
                            $request['Args'] = $args;
                        }
                        else # TOO MANY ARGS: PAGE NOT FOUND
                        {
                            $request['Controller'] = "PageNotFound";
                            $request['Method'] = "index";
                            $request['Auth'] = false;
                            $request['Expected_data'] = array();
                        }
                        
                        unset($args);
                    }
                    else # TOO MANY ARGS
                    {
                        $request['Controller'] = "PageNotFound";
                        $request['Method'] = "index";
                        $request['Auth'] = false;
                        $request['Expected_data'] = array();
                    }
                }
            }
            else # NO METHOD BY THAT NAME: PAGE NOT FOUND
            {
                $request['Controller'] = "PageNotFound";
                $request['Method'] = "index";
                $request['Auth'] = false;
                $request['Expected_data'] = array();
            }
        }
        else # NO: REVERT OT INDEX
        {
            $request['Method'] = 'index';
        }
        
        if ($request['Controller'] != "PageNotFound") # ADD DETAIL TO CONTROLLER
        {
            $request['Components'] = $confArr[$parts[0]]['Methods'][$request['Method']]['Components'];
            $request['Type'] = $confArr[$parts[0]]['Methods'][$request['Method']]['Type'];
            $request['Expected_data'] = $confArr[$parts[0]]['Methods'][$request['Method']]['Expected_data'];
            $request['Auth'] = $confArr[$parts[0]]['Methods'][$request['Method']]['Auth'];
            $request['Model'] = $confArr[$parts[0]]['Model'];
        }
        
        if (!in_array('post',$request['Expected_data'])) # IF POST DATA NOT EXPECTED: EMPTY POST ARR
        {
            $_POST = null;
        }
    }
    else # NO CONTROLLER: PAGE NOT FOUND
    {
        $request['Controller'] = "PageNotFound";
        $request['Method'] = "index";
        $request['Auth'] = false;
    }
    
    unset($parts);
    unset($confArr);
}
?>