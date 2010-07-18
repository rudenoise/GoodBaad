<?php
Class HomeController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
    }
    
    function index()
    {
        $this->statModel = new StatModel;
        
        if ($mostVotes = $this->statModel->combo(null,'total',5))
        {
            $data['mostVotes'] = $mostVotes;
        }
        
        if (isset($_SESSION['userName']))
        {
            $this->feedModel = new FeedModel;
            
            $this->userModel = new UserModel;
            
            $user = $this->userModel->getUser($_SESSION['userName']);
            
            $vars['userID'] = $user[0]['id'];
            
            if ($userTotal = $this->statModel->grandTotal($vars)) # GET USER'S OVERALL OPINION
            {
                $data['userTotal']['votes'] = $userTotal[0];
                
                if ($data['userTotal']['votes']['good'] != 0 || $data['userTotal']['votes']['good'] != 0)
                    $data['userTotal']['percentages'] = $this->percentages($data['userTotal']['votes']['good'],$data['userTotal']['votes']['baad']);
            }
            
            unset($vars);
            
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
            
            if ($activeUsers = $this->statModel->topActiveUsers())
            {
                $data['activeUsers'] = $activeUsers;
            }
        }
        else
        {
            if ($grandTotal = $this->statModel->grandTotal())
            {
                $data['grandTotal']['votes'] = $grandTotal[0];
                if ($data['grandTotal']['votes']['good'] != 0 || $data['grandTotal']['votes']['baad'] != 0)
                    $data['grandTotal']['percentages'] = $this->percentages($data['grandTotal']['votes']['good'],$data['grandTotal']['votes']['baad']);
            }
            
            $this->feedModel = new FeedModel;
        
            if ($masterFeed = $this->feedModel->masterFeed())
            {
                $data['masterFeed'] = $masterFeed;
            }
        }
        
        $this->renderView($data,array(),null);
    }
    
    function about()
    {
        $this->renderView(null,array(),'About');
    }
    
    function contact()
    {
        $this->renderView(null,array(),'Contact');
    }
    
    function terms()
    {
        $this->renderView(null,array(),'Terms and Conditions');
    }
    
    function worldometer()
    {
        $this->statModel = new StatModel;
        
        if ($grandTotal = $this->statModel->grandTotal())
        {
            $data['grandTotal']['votes'] = $grandTotal[0];
            if ($data['grandTotal']['votes']['good'] != 0 || $data['grandTotal']['votes']['baad'] != 0)
                $data['grandTotal']['percentages'] = $this->percentages($data['grandTotal']['votes']['good'],$data['grandTotal']['votes']['baad']);
        }
        
        $this->renderView($data,array(),'Worldometer - the state of the world now');
    }
}
?>