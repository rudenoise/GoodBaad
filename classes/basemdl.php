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
 * V 1.0 © JOEL HUGHES 2008
 *
 * BASE MODEL : CORE/GENERIC FEATURES
 *
 */
Class BaseMdl
{
    
    private $host = 'localhost:3306';
    private $dbName = 'goodbaad';
    private $user = '';
    private $password = '';
    
    protected $connection = null;
    
    protected function query($query)
	{
		mysql_pconnect($this->host,$this->user,$this->password);

        mysql_select_db($this->dbName) or trigger_error(mysql_error(), E_USER_WARNING);
        
        $result = mysql_query($query);#DO QUERY
        
        if (is_resource($result))
        {
        
            while ($row = mysql_fetch_array($result, MYSQL_NUM))#GO THROUGH THE DB TO FIND ALL ROWS MATCHING THE QUERY
    		{
    			//BUILD UP AN ARRAY WITH NAMED FIELDS RATHER THAN NUMBERS
    			$i=0;
    			foreach ($row as $f)
    			{
    				$fileds[] = mysql_field_name($result, $i);
    				$field = mysql_field_name($result, $i);
    				$arr[$field] = $row[$i];
    				$i++;
    			}
                $resArr[] = $arr;
    		}
    		
    		mysql_free_result($result);
            
    		if (isset($resArr))
            {
                return($resArr);
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
	}
    
    protected function insert($query)
    {
        mysql_pconnect($this->host,$this->user,$this->password);
        
        mysql_select_db($this->dbName) or trigger_error(mysql_error(), E_USER_WARNING);
        
        if (mysql_query($query))#DO QUERY
        {
            return true;
        }
    }
    
    protected function delete($query)
    {
        mysql_pconnect($this->host,$this->user,$this->password);

        mysql_select_db($this->dbName) or trigger_error(mysql_error(), E_USER_WARNING);
        
        $result = mysql_query($query);#DO QUERY
        
        if (mysql_query($query))#DO QUERY
        {
            return true;
        }
    }
	
	function valid_length($str, $min = 1, $max = null)
    {
        $length = strlen($str);
        
        if ($length < $min)
        {
            $rsp = "Too short";
        }
        elseif (($max != null) && $length > $max)
        {
            $rsp = "Too long";
        }
        else
        {
            $rsp = true;
        }
        
        return $rsp;
    }
    
    function valid_email($email)
    {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
        {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
             if (!ereg("^(([A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
                return false;
            }
        }    
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                    return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }
    
    function valid_match($a,$b)
    {
        if ($a == $b)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	
    function getUserID($userName)
    {
        $sql = "SELECT id FROM users AS user WHERE user.username = '$userName'";
        
        if ($result = $this->query($sql))
        {
            return $result[0]['id'];
        }
    }
    
	protected function chain($values = array(),$colName,$index = null,$type = 'exact')
    {
        $total = count($values);
        $i = 1;
        
        $string = "(";
        
        if ($type == 'exact')
        { 
            foreach ($values as $value)
            {
                if ($index != null) $value = $value[$index];
                
                if ($i < $total)
                {
                    $string .= $colName . ' = \'' .  $value . '\' || ';
                }
                else
                {
                    $string .= $colName . ' = \'' .  $value . '\'';
                }
                $i++;
            }
        }
        elseif ($type == 'like')
        {
            foreach ($values as $value)
            {
                if ($i < $total)
                {
                    $string .= "$colName LIKE '%" .  $value[$index] . "%' || ";
                }
                else
                {
                    $string .= "$colName LIKE '%" .  $value[$index] . "%'";
                }
                $i++;
            }
        }
        $string .= ")";
        
        return $string;
    }
    
    function handle($title)
    {
        $unPretty = array('/�/', '/�/', '/�/', '/�/', '/�/', '/�/', '/�/', '/\s?-\s?/', '/\s?_\s?/', '/\s?\/\s?/', '/\s?\\\s?/', '/\s/', '/"/', '/\'/', '/&/','/[.]/','/amp;/','[!]','/ç/','/æ/','/œ/','/á/','/é/','/í/','/ó/','/ú/','/à/','/è/','/ì/','/ò/','/ù/','/ä/','/ë/','/ï/','/ö/','/ü/','/ÿ/','/â/','/ê/','/î/','/ô/','/û/','/å/','/e/','/i/','/ø/','/u/','/–/');
        $pretty   = array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss', '-', '-', '-', '-', '-', '', '','and','','','','c','ae','oe','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','y','a','e','i','o','u','a','e','i','o','u','-');
        
        $title = strtolower(preg_replace($unPretty, $pretty, $title));
        
        $title = str_replace('---','-',$title);
        return str_replace('--','-',$title);
    }
}
?>
