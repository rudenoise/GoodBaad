<?php
Class UsersController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
    }
    
    function index()
    {
        /*
         * GENERAL USERS
         * MAIN FEED
         * GENERAL OPINION
         */
        
        $this->statModel = new StatModel;
        
        if ($grandTotal = $this->statModel->grandTotal())
        {
            $data['grandTotal']['votes'] = $grandTotal[0];
            $data['grandTotal']['percentages'] = $this->percentages($data['grandTotal']['votes']['good'],$data['grandTotal']['votes']['baad']);
        }
        
        $this->feedModel = new FeedModel;
        
        if ($masterFeed = $this->feedModel->masterFeed(0,20))
        {
            $data['masterFeed'] = $masterFeed;
        }
        
        /*
         * LOGGED IN STUFF
         * COMBO FEED
         * COMBO OPINION
         */
        
        if (isset($_SESSION['userName']))
        {
            $this->userModel = new UserModel;
            
            if ($followedBy = $this->userModel->getFollowedBy($_SESSION['userName']))
            {
                $data['followedBy'] = $followedBy;
                
                $vars['userIDs'] = $followedBy;
                
                if ($followedByGrandTotal = $this->statModel->grandTotal($vars))
                {
                    $data['followedByGrandTotal']['votes'] = $followedByGrandTotal[0];
                    
                    if ($data['followedByGrandTotal']['votes']['good'] != 0 || $data['followedByGrandTotal']['votes']['baad'] != 0)
                        $data['followedByGrandTotal']['percentages'] = $this->percentages($data['followedByGrandTotal']['votes']['good'],$data['followedByGrandTotal']['votes']['baad']);
                }
                
                unset($vars);
                
                $vars['users'] = $followedBy;
                
                if ($followedByFeed = $this->feedModel->comboFeed($vars))
                {
                    $data['followedByFeed'] = $followedByFeed;
                }
                
                unset($vars);
            }
        }
        
        $this->renderView($data, array(),'Users activity');
    }
    
    function individual($input = null)
    {
        if (isset($input[0]))
        {
            $username = $input[0];
            
            $this->userModel = new UserModel;
            
            if ($user = $this->userModel->getUser($username)) # GET USER
            {
                $data['user'] = $user[0];
                
                $title = $username;
                
                $this->statModel = new StatModel;
                
                $vars['userID'] = $user[0]['id'];
                
                if ($userTotal = $this->statModel->grandTotal($vars)) # GET USER'S OVERALL OPINION
                {
                    $data['user']['totals'] = $userTotal[0];
                    
                    if ($data['user']['totals']['good'] != 0 || $data['user']['totals']['baad'] != 0)
                    {
                        $data['user']['percentages'] = $this->percentages($data['user']['totals']['good'],$data['user']['totals']['baad']);
                    }
                }
                
                unset($vars);
                
                if ($following = $this->userModel->getFollowing($username)) # GET FOLLOWING
                {
                    $data['following']['users'] = $following;
                    $data['following']['total'] = count($following);
                }
                
                if ($followedBy = $this->userModel->getFollowedBy($username)) # GET FOLLOWED BY
                {
                    $data['followedBy']['users'] = $followedBy;
                    $data['followedBy']['total'] = count($followedBy);
                }
                
                $this->feedModel = new FeedModel;
                
                if ($userFeed = $this->feedModel->userFeed($username)) # GET USER FEED
                {
                    $data['userFeed'] = $userFeed;
                }
            }
            else
            {
                $data['msg'] = "User not found.";
                $title = 'Not Found';
            }
        }
        else
        {
            $data['msg'] = "User not found.";
            $title = 'Not Found';
        }
        
        $this->renderView($data,array(), $title .'\'s recent opinions');
    }
    
    function follow($input = null)
    {
        if (isset($input[0]))
        {
            $username = $input[0];
            
            if ($_SESSION['userName'] == strtolower($username))
            {
                $data['msg'] = 'Can\'t follow yourself.';
            }
            else
            {
                $this->userModel = new UserModel;
                
                if (($userToFollow = $this->userModel->getUser($username))&&($follower = $this->userModel->getUser($_SESSION['userName'])))
                {
                    if ($this->userModel->checkFollowing($userToFollow[0]['id'],$follower[0]['id']))
                    {
                        $data['msg'] =  'You are already following '.ucfirst($username).'.';
                    }
                    else
                    {
                        if ($this->userModel->follow($userToFollow[0]['id'], $follower[0]['id']))
                        {
                            #$data['msg'] = "You are now following $username";
                            header('Location:http://'. $_SERVER['HTTP_HOST'] .'/users/'.$username);
                            die();
                        }
                        else
                        {
                            $data['msg'] = "User not found, error.";
                        }
                    }
                }
                else
                {
                    $data['msg'] = "User not found, error.";
                }
            }
        }
        else
        {
            $data['msg'] = "User not found";
        }
        
        $this->renderView($data);
    }
    
    function history()
    {
        $this->feedModel = new FeedModel;
                
        if ($userFeed = $this->feedModel->userFeed($_SESSION['userName'],0,0)) # GET USER FEED
        {
            $data['feed'] = $userFeed;
        }
        else
        {
            $data['feed'] = array();
        }
        
        $this->renderView($data,array(),'Your voting history');
    }
}
?>