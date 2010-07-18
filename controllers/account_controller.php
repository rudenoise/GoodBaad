<?php
Class AccountController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
        
        $this->accountModel = new AccountModel;
    }
    
    function index()
    {
        $account = $this->accountModel->getFullAccount($_SESSION['userName']);
        
        $data['account'] = $account[0];
        
        if (!isset($data['account']['gender']))
        {
            $user = $this->accountModel->getAccount(null,$_SESSION['userName']);
            
            $this->accountModel->createUserDetail($user[0]['id'],'f','1970');
        }
        
        if (isset($_POST['data']))
        {
            if ((($_POST['data']['Account']['gender'] == 'm')||($_POST['data']['Account']['gender'] == 'f'))&&(($_POST['data']['Account']['yob'] > 1900)&&($_POST['data']['Account']['yob'] < 2008)))
            {
                if ($this->accountModel->updateDetail($data['account']['id'],$_POST['data']['Account']['gender'],$_POST['data']['Account']['yob']))
                {
                    $data['account']['gender'] = $_POST['data']['Account']['gender'];
                    $data['account']['yob'] = $_POST['data']['Account']['yob'];
                    $data['msg'] = "Your details have been updated";
                }
                else
                {
                    $data['msg'] = "Somehow the data recieved from the from is invalid, please have another go.";
                }
            }
        }
        
        $this->renderView($data,array(),'Your Account');
    }
    
    function login()
    {
        if (isset($_POST['data']))
        {
            if ($_POST['data']['Account']['email'])
            {
                $user = $this->accountModel->getAccount(strtolower($_POST['data']['Account']['email']));
                
                if (isset($user[0]['id']))
                {
	                if ($user[0]['attempts'] < 3)
	                {
		                if ($user[0]['password'] == sha1($_POST['data']['Account']['password'].SALT))
		                {
		                    $this->loginNow($user[0]);
		                }
		                else
		                {
		                    if ($this->accountModel->badAttempt($user[0]['username'],$user[0]['attempts']))
		                    {
		                        $data['msg'] = "Incorrect details, check that you have typed your details correctly.";
		                        $this->renderView($data);
		                    }
		                }
	                }
	                elseif (($user[0]['attempts'] == 3)&&($user[0]['active']!='dissabled'))
	                {
	                    if ($this->accountModel->badAttempt($user[0]['username'],$user[0]['attempts']))
	                    {
	                        $activationKey = $this->accountModel->createActivationKey($user[0]['username']);
	                        
	                        if ($test = $this->accountModel->addActivation($user[0]['username'],$activationKey))
	                        {
	                            if ($newPass = $this->accountModel->newPassword($user[0]['username']))# CREATE+INSERT NEW PASSOWRD
	                            {
	                                $message = "
Hi ".$user[0]['username'].",

Your password has been reset due to multiple failed login attempts.

To re-activate your account click (or paste this link into you browser):

http://".$_SERVER['SERVER_NAME']."/activate/".$user[0]['username']."/".$activationKey."

You will need to use the following password: $newPass, you can then change it to something more memorable.

Thanks

The GoodBadd team";
                                    if ($this->email($user[0]['email'],'goodbaad.com re-activation',$message))
                                    {
                                        $data['msg'] = "Your account has been dissabled, a new username and activation-key have been sent to your email address";
                                    }
                                    else
                                    {
                                        $data['msg'] =  "Email failed.";
                                    }
	                            }
	                            else
	                            {
	                                $data['msg'] =  "New pass failed.";
	                            }
	                        }
	                        else
	                        {
	                            $data['msg'] =  "New activation failed";
	                        }
                            $this->renderView($data);
	                    }
	                }
	                else
	                {
	                    $data['msg'] = "Your account has been dissabled, a new username and activation-key have been sent to your email address. To resend the activation key <a href='/account/resendactivation/".$user[0]['username']."/' title='resend activation'>click here...</a>";
	                    $this->renderView($data);
	                }
                }
                else
                {
                    $data['msg'] = "There was a problem with your login, please try again.";
                    $this->renderView($data);
                }
            }
            else
            {
                $data['msg'] = "There was a problem with your login, please try again.";
                $this->renderView($data);
            }
        }
        else
        {
            $data['msg'] = "Please Log In.";
            $title = "Login";
            $this->renderView(null,array(),'Account > '.$title);
        }
    }
    
    function logout()
    {
        #session_start();
        session_destroy();
        header('Location: /');
    }
    
    function signup()
    {
        #session_start();
        if (isset($_SESSION['ip']) && isset($_SESSION['userName']))
        {
            header('Location:http://'. $_SERVER['HTTP_HOST'] .'/users');
            die();
        }
        
        #if (!filter_has_var(INPUT_POST, 'submit'))
        if (!isset($_POST['data']))
        {
            $this->renderView();
        }
        else
        {
            foreach ($_POST['data']['Account'] as $key => $value)
            {
                if (empty($value))
                {
                    $userArr[$key] = $value;
                    $blank = true;
                }
                else
                {
                    $userArr[$key] = $value;
                }
                
            }
            
            if (!isset($blank))
            {
                $userArr['username'] = $this->accountModel->handle($userArr['username']);
                
                $validation = $this->accountModel->addAccount($userArr['username'],$userArr['password'],$userArr['password2'],strtolower($userArr['email']));
                
                if (!isset($_POST['data']['Account']['terms']))
                {
                    if ($validation == 1) unset($validation);
                    $validation['terms'] = false;
                }
                    
                if ($validation != 'true')
                {
                    $data = array('msg' => 'Please correct the problems below.','userArr'=>$userArr,'validation' => $validation);
                    
                    $this->renderView($data);
                }
                else
                {
                    $data = array($userArr['username'],$userArr['password'],$userArr['email']);
                    
                    #$activationKey = sha1($userArr['username'].SALT.time());
                    $activationKey = $this->accountModel->createActivationKey($userArr['username']);
                    
                    
                    $message = "
Hi ".$userArr['username'].",

Thank you for joining GoodBaad.com!

To activate your account click (or paste this link into you browser):

http://".$_SERVER['SERVER_NAME']."/activate/".$userArr['username']."/".$activationKey."

Thanks

The Good Baad team
                    ";
                    
                    if ($this->email($userArr['email'],'goodbaad.com activation',$message)&&($this->accountModel->addActivation($userArr['username'],$activationKey)))
                    {
                        $this->accountModel->deleteInvite($userArr['email']);
                        
                        $overide['Controller'] = 'account';
                        $overide['Type'] = 'xhtml';
                        $overide['Method'] = 'user-success';
                        
                        $this->renderView($data,$overide);
                    }
                    else
                    {
                        $data = array('msg' => "There was a problem sending the activation email, please try again.");
                        
                        $this->renderView($data);
                    }
                }
                
            }
            else
            {
                $data = array('msg' => 'Please fill in all details:','userArr'=>$userArr);
                $title = "Sign Up";
                $this->renderView($data,array(),'Account > '.$title);
            }
        }
    }
    
    function activate($input = null)
    {
        if (isset($input[0]) && isset($input[1]))
        {
            $username = $input[0];
            $key = $input[1];
            
            if ($data['msg'] = $this->accountModel->validateActivation($username,$key))
            {
                $data['active'] = true;
                $this->renderView($data);
            }
            else
            {
                $data['msg'] = "Problem activating account";
                $title = "Activation";
                $this->renderView($data,array(),'Account > '.$title);
            }
        }
        else
        {
            header('Location:http://'. $_SERVER['HTTP_HOST'] .'/');
            die();
        }
    }
    
    function resendactivation($input = null)
    {
        if (isset($_SESSION['userName']))
        {
            $input[0] = $_SESSION['userName'];
        }
        
        if (isset($input[0]))
        {
            $userName = $input[0];
            
            if ($activation = $this->accountModel->getActivation($userName))
            {
                if ($activation[0]['active'] != 'active')
                {
                    $message = "
Hi ".$activation[0]['username'].",

Thank you for joining GoodBaad.com! Here is your activation reminder.

To activate your account click (or paste this link into you browser):

http://".$_SERVER['SERVER_NAME']."/activate/".$activation[0]['username']."/".$activation[0]['activation_key']."

Thanks


The GoodBadd team
                    ";
                    
                    if ($this->email($activation[0]['email'],'goodbaad.com activation',$message))
                    {
                        $msg = "You have been sent your activation details.";
                    }
                    else
                    {
                        $msg =  "There was a problem sending your activationh email, please try again.";
                    }
                }
                else
                {
                    $msg = "Your account is already active.";
                }
            }
            else
            {
                $msg = "Your account is already active.";
            }
        }
        else
        {
            $msg = "We can't send an activation email as the username doesn't exist.";
        }
        $data['msg'] = $msg;
        $title = "Resend Activation";
        $this->renderView($data,array(),'Account > '.$title);
    }
    
    function resetpassword()
    {
        $sucess = false;
        
        if (isset($_POST['data']['current']))
        {
            $username = $_SESSION['userName'];
            $user = $this->accountModel->getAccount(null,$username);
            
            if ($user[0]['password'] == (sha1($_POST['data']['current'].SALT)))
            {
                if ($this->accountModel->valid_match($_POST['data']['newPassword1'],$_POST['data']['newPassword2']))
                {
                    if ($this->accountModel->updatePassoword($username,$_POST['data']['newPassword1']))
                    {
                        $data['msg'] = "Your password has been changed.";
                    }
                    else
                    {
                        $data['msg'] = "Probem Updating Password";
                    }
                }
                else
                {
                    $data['msg'] = "Your new passwords don't match";
                }
            }
            else
            {
                $data['msg'] = "Your original password has been entered incorectly";
            }
        }
        else
        {
            $data['msg'] = "Change your password below.";
        }
        
        if ($sucess == false)
        {
            $title = "Error";
            $this->renderView($data,array(),'Account > Change Password');
        }
        else
        {
            $overide['Controller'] = 'account';
            $overide['Type'] = 'xhtml';
            $overide['Method'] = 'user-success';
            $title = 'Success';
            $this->renderView($data,$overide, 'Account > '.$title);
        }
    }
    
    function invite()
    {
        if (!isset($_POST['data']['emails']))
        {
            $overide['Controller'] = 'account';
            $overide['Type'] = 'xhtml';
            $overide['Method'] = 'invite-form';
            
            $this->renderView(null,$overide, 'Account > Invite');
        }
        else
        {
            $parts = split('[,]',$this->accountModel->security->paranoid($_POST['data']['emails'],array(',','.','_','-','@')));
            
            foreach($parts as $part)
            {
                if ($this->accountModel->valid_email($part))
                {
                    $emails[] = $part;
                }
            }
            
            if (!isset($emails))
            {
                $data['msg'] = 'You did not enter nay valid email addresses';
            }
            else
            {
                if ($usersExisting = $this->accountModel->getMultipleAccounts($emails))
                {
                    $this->userModel =  new UserModel;
                    
                    $follower = $this->userModel->getUser($_SESSION['userName']);
                    
                    foreach ($usersExisting as $userExisting)
                    {
                        unset($emails[array_search($userExisting['email'], $emails)]); # IF USER EXISTS REMOVE FROM EMAIL LIST
                        
                        if ($this->userModel->checkFollowing($userExisting['id'],$follower[0]['id']))
                        {
                            $data['alreadyFollowing'][] = $userExisting['username'];
                        }
                        else
                        {
                            if ($this->userModel->follow($userExisting['id'], $follower[0]['id']))
                            {
                                $data['nowFollowing'][] = $userExisting['username'];
                            }
                            else
                            {
                                $data['msg'] = "Following Error";
                            }
                        }
                    }
                }
                
                if ($invitesExisting = $this->accountModel->getInvites($emails)) # CHECK USER HASN'T RECIEVED AN INVITE BEFORE
                {
                    foreach ($invitesExisting as $inviteExisting)
                    {
                        unset($emails[array_search($inviteExisting['email'], $emails)]); # IF INVITE EXISTS REMOVE FROM EMAIL LIST
                        
                        $lastSend = strtotime($inviteExisting['last_send']);
                        
                        if ($lastSend < (time() - (30*24*60*60))) # CHECK THEY HAVEN'T BEEN HASSELED IN THE LAST 30 DAYS
                        {
                            if ($this->sendInvite($_SESSION['userName'],$inviteExisting['email']))
                            {
                                $emailSent[$inviteExisting['email']]['sent'] = true;
                                
                                if ($this->accountModel->updateInvite($email,date('Y-m-d H:i:s')))
                                {
                                    $emailSent[$inviteExisting['email']]['updated'] = true;
                                }
                            }
                            else
                            {
                                $emailSent[$inviteExisting['email']] = 'fail';
                            }
                            
                            $data['previousInvite'][] = $emailSent;
                        }
                        else
                        {
                            $data['previousInvite'][$inviteExisting['email']] = 'not now';
                        }
                    }
                }
                
                if (isset($emails))
                {
                    foreach ($emails as $email)
                    {
                        if ($this->sendInvite($_SESSION['userName'],$email))
                        {
                            $emailSent[$email]['sent'] = true;
                            
                            if ($this->accountModel->addInvite($email,date('Y-m-d H:i:s')))
                            {
                                $emailSent[$email]['added'] = true;
                            }
                        }
                        else
                        {
                            $emailSent[$email] = 'fail';
                        }
                    }
                    
                    if (isset($emailSent)) $data['emailSent'] = $emailSent;
                }
            }
            
            $overide['Controller'] = 'account';
            $overide['Type'] = 'xhtml';
            $overide['Method'] = 'invite-done';
            
            $this->renderView($data,$overide, 'Account > Invite');
        }
    }
    
    function rpx()
    {
        if (!isset($_GET['token']))
        {
            $this->renderView();
        }
        else
        {
            $partial_query["token"] = $this->accountModel->security->paranoid($_GET['token']);
            $partial_query["format"] = 'json';
            $partial_query["apiKey"] = RPXKEY;
            
            $query_str = "";
            foreach ($partial_query as $k => $v)
            {
                if (strlen($query_str) > 0)
                {
                    $query_str .= "&";
                }
                
                $query_str .= urlencode($k);
                $query_str .= "=";
                $query_str .= urlencode($v);
            }
            
            if ($result = $this->rpxConnect('https://goodbaad.rpxnow.com/api/v2/auth_info',$query_str))
            {
                
                if ($result->stat == 'ok')
                {
                    
                    if ($account = $this->accountModel->checkThirdParty($result->profile->identifier)) # IF THIS IDENTIFIER IS ALREADY ASSOCIATED WITH AN ACCOUNT: LOGIN
                    {
                        $this->loginNow($account[0]);
                    }
                    else # OTHERWISE CHECK SETUP OR MERGE ACCOUNTS
                    {
                        #debug($result);break;exit;
                        if (isset($result->profile->email))
                        {
                            $users['email'] = $this->accountModel->getAccount($result->profile->email);
                            
                            $users['username'] = $this->accountModel->getAccount(null,$this->accountModel->handle($result->profile->preferredUsername));
                        }
                        
                        
                        
                        if (isset($users['email'][0]) || isset($users['username'][0])) # DESIRED USERNAME: TAKEN
                        {
                            if (isset($users['email'][0]))
                            {
                                # merge
                                $data['msg'] = 'Your email address has already been used to set-up an account. You will be able to use multiple logins with your account soon, in the meantime you will need to log-in with your previous account.';
                                
                                $overide['Controller'] = 'account';
                                $overide['Type'] = 'xhtml';
                                $overide['Method'] = 'login';
                                
                                $this->renderView($data,$overide, 'Account > Login');
                            }
                            elseif (isset($users['username'][0]))
                            {
                                # GENERATE NEW/TEMPORARY USERNAME
                                # CREATE NEW ACCOUNT
                                # LOGIN AND SEND TO ACCOUNT/CHANGE-NAME PAGE
                                
                                $_SESSION['newAC']['identifier'] = $result->profile->identifier;
                                
                                if (isset($result->profile->email))
                                {
                                    $_SESSION['newAC']['email'] = $result->profile->email;
                                }
                                
                                $data['existinUserName'] = $users['username'][0]['username'];
                                
                                $overide['Controller'] = 'account';
                                $overide['Type'] = 'xhtml';
                                $overide['Method'] = 'choose-name';
                                
                                $this->renderView($data,$overide, 'Account > Choose Username');
                            }
                        }
                        else # USERNAME AVAILABLE: CREATE NEW ACCOUNT AND 3RD PARTY RECORD
                        {
                            if (isset($result->profile->preferredUsername))
                            {
                                $newAC['userName'] = $this->accountModel->handle($result->profile->preferredUsername);
                                
                                if (isset($result->profile->email))
                                {
                                    $newAC['email'] = $result->profile->email;
                                }
                                
                                $newAC['identifier'] = $result->profile->identifier;
                                
                                if ($user = $this->thirdPartyCreate($newAC))
                                {
                                    $this->loginNow($user[0]);
                                }
                                else
                                {
                                    $data['msg'] = 'There was a problem creating your account, sorry.';
                                    
                                    $this->renderView($data,$overide, 'Account > Login');
                                }
                            }
                            else
                            {
                                $_SESSION['newAC']['identifier'] = $result->profile->identifier;
                                
                                if (isset($result->profile->email))
                                {
                                    $_SESSION['newAC']['email'] = $result->profile->email;
                                }
                                
                                $data['existinUserName'] = rand  (10,1000);
                                
                                $overide['Controller'] = 'account';
                                $overide['Type'] = 'xhtml';
                                $overide['Method'] = 'choose-name';
                                
                                $this->renderView($data,$overide, 'Account > Choose Username');
                            }
                        }
                    }
                }
                else
                {
                    $data['msg'] = 'Sorry, the connection with your external account has expired, please try again.';
                                
                    $overide['Controller'] = 'account';
                    $overide['Type'] = 'xhtml';
                    $overide['Method'] = 'login';
                    
                    $this->renderView($data,$overide, 'Account > Login');
                }
            }
            else
            {
                $data['msg'] = 'Sorry, the connection with your external account is having problems, please try again.';
                                
                $overide['Controller'] = 'account';
                $overide['Type'] = 'xhtml';
                $overide['Method'] = 'login';
                
                $this->renderView($data,$overide, 'Account > Login');
            }
        }
    }
    
    function newname()
    {
        if (isset($_POST['data']) && isset($_SESSION['newAC']))
        {
            $username = $this->accountModel->handle($_POST['data']['Account']['username']);
            
            if ($account = $this->accountModel->getAccount(null,$username))
            {
                debug($account);
                
                $data['existinUserName'] = $account[0]['username'];
                
                $overide['Controller'] = 'account';
                $overide['Type'] = 'xhtml';
                $overide['Method'] = 'choose-name';
                
                $this->renderView($data,$overide, 'Account > Choose Username');
            }
            else
            {
                $newAC['userName'] = $username;
                            
                if (isset($_SESSION['newAC']['email']))
                {
                    $newAC['email'] = $_SESSION['newAC']['email'];
                }
                
                $newAC['identifier'] = $_SESSION['newAC']['identifier'];
                
                if ($user = $this->thirdPartyCreate($newAC))
                {
                    $this->loginNow($user[0]);
                }
                else
                {
                    $data['msg'] = 'There was a problem creating your account, sorry.';
                                
                    $this->renderView($data,$overide, 'Account > Login');
                }
            }
        }
        else
        {
            echo "error";
        }
    }
    
    private function thirdPartyCreate($newAC)
    {
        if ($this->accountModel->createThirdPartyUser($newAC)) # ACCOUNT CREATED
        {
            if ($user = $this->accountModel->getAccount(null,$newAC['userName']))
            {
                if ($this->accountModel->createThirdPartyDetail($user[0]['id'], $newAC['identifier']))
                {
                    if (isset($_SESSION['newAC'])) unset($_SESSION['newAC']);
                    # 3rd PARTY ACCOUNT CREATED
                    # GO TO ACCOUNT PAGE? OR FINISH VOTE?
                    return $user;
                }
                else
                {
                    # 3rd PARTY ACCOUNT NOT CREATED
                }
            }
            else
            {
                # CAN'T FIND ACCOUNT
            }
        }
        else
        {
            # PROBLEM CREATING ACCOUNT
        }
    }
    
    private function rpxConnect($url,$post_data)
    {
        $_POST = null;
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl_handle,CURLOPT_POST, true);
        curl_setopt($curl_handle,CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl_handle,CURLOPT_URL,$url);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
        
        $buffer = curl_exec($curl_handle);
        
        $_POST = null;
        
        curl_close($curl_handle);
        
        if (!empty($buffer))
        {
            return json_decode($buffer);
        }
    }
    
    private function sendInvite($username,$emailTo)
    {
        $message = '
'. ucfirst($username) . ' has invited you to join http://' . $_SERVER['HTTP_HOST'] . '

You can view '. ucfirst($username) . '\'s profile here: http://' . $_SERVER['HTTP_HOST'] . '/users/'. $username . '

http://' . $_SERVER['HTTP_HOST'] . ' aims to be the definitive barometer of public opinion. So, if you want to know what your friends, and the general population, think about absolutly anything: come and join in.
 
We hope to see you soon
 
The Good Baad Team';
        
        if ($this->email($emailTo,'GoodBaad.com Invitation From ' . $username ,$message))
        {
            return true;
        }
    }
    
    private function loginNow($account, $newAccount = false)
    {
        
        $session = new BaseSession;
		                    
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        if ($account['active'] == 'active') $active = true; else $active = false;
        
        $session->set(array('ip'=>$ip,'userName'=>strtolower($account['username']),'userAgent'=>$userAgent,'active'=>$active));
        
        if ($this->accountModel->lastLogin($account['username'],$ip))
        {
            if (isset($_SESSION['last-location']))
            {
                header('Location:http://'. $_SERVER['HTTP_HOST'] .'/' . $_SESSION['last-location']);
                die();
            }
            else
            {
                header('Location:http://'. $_SERVER['HTTP_HOST'] .'/');
                die();
            }
        }
        else
        {
            $session->end();
            header('Location:http://'. $_SERVER['HTTP_HOST'] .'/');
            die();
        }
    }
}

?>