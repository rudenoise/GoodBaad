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
 * BASE CONTROLLER : CORE/GENERIC FEATURES
 *
 */
Class BaseCtrl
{
    function __construct($class,$request)
    {
        #CHECK GET AND POST DATA ARE EXPECTED AND SANITIZE ACCORDINGLY
    }
    
    protected function renderView($data = null, $overide = array(), $title = null)
    {
        if ($title == null)
        {
            $title = "Good Baad, the defitive record of public opinion.";
        }
        else
        {
            $title = $title . ' - Good Baad';
        }
        
        if (empty($overide))
        {
            $controller = $this->class;
            
            $type = $this->request['Type'];
            
            $method = $this->request['Method'];
        }
        else
        {
            # HANDLE OVERIDE
            
            $controller = $overide['Controller'];
            
            $type = $overide['Type'];
            
            $method = $overide['Method'];
        }
        
        if (isset($_SESSION['ip']) && isset($_SESSION['userName']))
        {
            $active = true;
        }
        $path = SITE_PATH . "views" . DIR . $controller . DIR . $type . DIR . $method . ".php";
        
        if ($type = $this->request['Type'] == 'xhtml')
        {
            include(SITE_PATH . "views" . DIR . 'wrappers' . DIR . "default.php");
        }
        else
        {
            include($path);
        }
    }
    
    protected function email($to, $subject, $message)
    {
        $headers = "From:Good Baad <".SITEEMAIL."> \r\n" .
        "Reply-To:".SITEEMAIL."\r\n" .
        'X-Mailer: PHP/' . phpversion();
        
        $headers .= '';
        
        //send message
        
        if(mail($to, $subject, $message, $headers))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    protected function percentages($good = false,$baad = false)
    {
        if (($good !== false)&&($baad !== false))
        {
            $percent['good'] = round(substr((($good / ($baad+$good)) * 100),0,5),1);
    		$percent['baad'] = round(substr((($baad / ($baad+$good)) * 100),0,5),1);
            return $percent;
        }
    }
    
    protected function jsonConnect($str, $json = true)
    {
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$str);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        
        if (!empty($buffer))
        {
            if ($json == true)
            {
                return json_decode($buffer);
            }
            else
            {
                return $buffer;
            }
        }
    }
}
?>