<?php
Class AccountModel extends BaseMdl
{
    function __construct()
    {
        $this->security = new Security;
    }
    
    function getAccount($email = null,$username = null)
    {
        if ($username == null)
        {
            $sql = "SELECT * FROM users WHERE email = '$email';";
        }
        else
        {
            $sql = "SELECT * FROM users WHERE username = '$username';";
        }
        
        return $this->query($sql);
    }
    
    function getMultipleAccounts($emails = array())
    {
        if (!empty($emails))
        {
            $sql = 'SELECT id, username, email FROM users WHERE ' . $this->chain($emails,'email') . ' ORDER BY username DESC';
            
            return $this->query($sql);
        }
    }
    
    function getInvites($emails = array())
    {
        if (!empty($emails))
        {
            $sql ='SELECT * FROM invites WHERE ' . $this->chain($emails,'email');
            
            return $this->query($sql);
        }
    }
    
    function addInvite($email,$date)
    {
        $sql = '
        INSERT INTO invites
            (email,last_send)
        VALUES
            (\''.$email.'\',\''.$date.'\')
        ';
        
        return $this->insert($sql);
    }
    
    function updateInvite($email,$date)
    {
        $updateAccount = '
        UPDATE invites 
            SET last_send = \'' . $date . '\'
        WHERE email = \'' . $email . '\';
        ';
        
        return $this->insert($updateAccount);
    }
    
    function deleteInvite($email)
    {
        $sql = "DELETE FROM invites WHERE email = '$email'";
        
        $this->delete($sql);
    }
    
    function getFullAccount($username)
    {
        $sql = "
        SELECT * FROM users AS user
          LEFT JOIN user_detail ON user.id = user_detail.user_id
        WHERE username = '$username';
        ";
        
        return $this->query($sql);
    }
    
    function createUserDetail($id,$gender = null,$yob = null)
    {
        if ($gender!=null && $yob!=null)
        {
            $sql = "
            INSERT INTO user_detail 
                (user_id, gender, yob)
            VALUES
                ($id, '$gender', '$yob' );
            ";
            
            return $this->insert($sql);
        }
    }
    
    function updateDetail($id,$gender = null,$yob = null)
    {
        $g = false;
        
        if ($gender != null)
        {
            $str = "gender = '$gender'";
            $g = true;
        }
        
        if ($yob != null)
        {
           if ($g == true)
           {
               $str .= ", yob = '$yob'"; 
           }
           else
           {
               $str = " yob = '$yob'"; 
           }
        }
        
        if (isset($str))
        {
            $sql = "
            UPDATE user_detail 
                SET $str
            WHERE id = '".$id."';
            ";
        }
        
        return $this->insert($sql);
    }
    
    function addAccount($username,$password,$password2,$email)
    {
        # CLEAN EXPECTED INPUT
        
        $allow = array('@','-','_','.','/','*','-','+','!','�','$','%','^','&','~','#','|');
        
        $username = strtolower($username);
        
        if ($this->security->matchParanoid($username,$allow) != true) $validation['username'] = $this->valid_length($username,4,20); else $validation['username'] = "Invalid characters";
        
        if ($this->security->matchParanoid($password,$allow) != true) $validation['password'] = $this->valid_length($password,6,20); else $validation['password'] = "Invalid characters";
        
        $validation['password2'] = $this->valid_match($password,$password2);
        
        $validation['email'] = $this->valid_email($email);
        
        foreach ($validation as $v)
        {
            if ($v != 1) $invalid = 1;
        }
        
        
        if (!isset($invalid))
        {
            $sql = "SELECT * FROM users WHERE (username = '$username') || (email = '$email');";
            
            $rslt = $this->query($sql);
            
            if (isset($rslt[0]))
            {
                foreach ($rslt as $rs)
                {
                    if (isset($rs['email'])) if ($rs['email'] == $email) $validation['emlChck'] = true;
                    if (isset($rs['username'])) if ($rs['username'] == $username) $validation['uNameChck'] = true;
                }
                return $validation;
            }
            else
            {
                $query = "
                INSERT INTO users 
                    (username, password, email, created)
                    VALUES
                    ('".strtolower($username)."','".sha1($password.SALT)."','$email', FROM_UNIXTIME(".time()."))";
                
                if ($this->insert($query))
                {
                    return true;
                }
            }
        }
        else
        {
            return $validation;
        }
    }
    
    function createActivationKey($userName)
    {
        $activationKey = sha1($userName.SALT.time());
        return $activationKey;
    }
    
    function addActivation($username,$key)
    {
        $sql = "SELECT * FROM users WHERE (username = '$username');";
        
        if ($rslt = $this->query($sql))
        {
            $query = "INSERT INTO activation (user_id, activation_key) VALUES (".$rslt[0]['id'].",'$key');";
        
            if ($this->insert($query))
            {
                return true;
            }
            
        }
    }
    
    function newPassword($username)
    {
        $newPass = substr(sha1(SALT.$username.time().SALT),5,6);
        $encrypted = sha1($newPass.SALT);
        
        $updateAccount = "
        UPDATE users 
            SET password = '$encrypted'
        WHERE username = '".$username."';
        ";
        
        if ($this->insert($updateAccount))
        {
            return $newPass;
        }
    }
    
    function validateActivation($username,$key)
    {
        $sql = "SELECT * FROM users WHERE (username = '$username');";
        
        if ($rslt = $this->query($sql))
        {
            $return = "Account Exists";
            
            $user_id = $rslt[0]['id'];
            
            $sql = "SELECT * FROM activation WHERE (user_id = $user_id) && (activation_key = '$key');";
            
            if ($rslt = $this->query($sql))
            {
                $return = "Account Exists, and not validated.";
                
                $updateAccount = "
                UPDATE users 
                    SET active = 'active', attempts = 0
                WHERE id = '".$user_id."';
                ";
                
                if ($this->insert($updateAccount))
                {
                    $return = "Account Exists, not validated, and Profile ACTIVE.";
                    
                    $sql = "DELETE FROM activation WHERE user_id = $user_id";
                    
                    if ($this->delete($sql))
                    {
                        $return = "Account Exists, not validated, Profile ACTIVE, and activation record DELETED.";
                    }
                }
            }
            else
            {
                $return = false;
            }
        }
        else
        {
            $return = false;
        }
        return $return;
    }
    
    function getActivation($username)
    {
        $sql = "
        SELECT * FROM
            activation LEFT JOIN users ON activation.user_id = users.id
        WHERE (users.username = '$username')";
        
        if ($rslt = $this->query($sql))
        {
            return $rslt;
        }
    }
    
    function updatePassoword($userName,$password)
    {
        
        $password = sha1($password.SALT);
        $updateAccount = "
        UPDATE users 
            SET password = '$password'
        WHERE username = '$userName';
        ";
        
        if ($this->insert($updateAccount))
        {
            return true;
        }
    }
    
    function lastLogin($userName,$ip)
    {
        $query = 'UPDATE users SET lastvisit = NOW(), attempts = 0, last_ip = \''.$ip.'\' WHERE username = \''.$userName.'\';';
        
        if ($this->insert($query))
        {
            return true;
        }
    }
    
    function badAttempt($userName,$attempts)
    {
        $attempts = $attempts+1;
        
        if ($attempts == 4)
        {
            $query = "UPDATE users SET active = 'dissabled' WHERE username = '$userName';";
        }
        else
        {
            $query = "UPDATE users SET attempts = $attempts WHERE username = '$userName';";
        }
        
        if ($this->insert($query))
        {
            return true;
        }
    }
    
    function checkThirdParty($identifier)
    {
        $query = '
        SELECT * FROM thirdparty
            LEFT JOIN users AS user on thirdparty.user_id = user.id
        WHERE identifier = \'' . $identifier . '\';
        ';
        
        return $this->query($query);
    }
    
    function createThirdPartyUser($newAC = array())
    {
        if (strlen($newAC['userName']) > 2)
        {
            $query = ' INSERT INTO users ';
            if (!isset($newAC['email']))
            {
                $query .= '(username, created, active) VALUES (\''.strtolower($newAC['userName']).'\', FROM_UNIXTIME('.time().'), \'active\')';
            }
            else
            {
                $query .= '(username, email, created, active) VALUES (\''.strtolower($newAC['userName']).'\', \''.$newAC['email'].'\', FROM_UNIXTIME('.time().'), \'active\')';
            }
            
            if ($this->insert($query))
            {
                return true;
            }
        }
    }
    
    function createThirdPartyDetail($userID,$idetifier)
    {
        $query = 'INSERT INTO thirdparty (user_id,identifier) VALUES ('.$userID.',\''.$idetifier.'\')';
        
        if ($this->insert($query))
        {
            return true;
        }
    }
}
?>