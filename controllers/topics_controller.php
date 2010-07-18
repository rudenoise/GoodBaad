<?php
Class TopicsController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
    }
    
    function index()
    {
        /*
         * NOT LOGED IN
         * OVERALL TOP 5s (GOOD,BAAD,OVERALL)
         * MASTER FEED
         */
        $this->statModel = new StatModel;
        
        if ($total = $this->statModel->combo(null,'total',5))
        {
            $data['total'] = $total;
        }
        
        if ($goodest = $this->statModel->combo(null,'goodest',5))
        {
            $data['goodest'] = $goodest;
        }
        
        if ($baadest = $this->statModel->combo(null,'baadest',5))
        {
            $data['baadest'] = $baadest;
        }
        
        /*
         * LOGGED IN
         * FRIENDS TOP 5s (GOOD,BAAD,OVERALL)
         * COMBO FEED
         */
        if (isset($_SESSION['userName']))
        {
            $this->userModel = new UserModel;
            
            if ($following = $this->userModel->getFollowedBy($_SESSION['userName']))
            {
                $data['following'] = $following;
                
                $vars['userIDs'] = $following;
                
                if ($followingTotal = $this->statModel->combo($vars,'total',5))
                {
                    $data['followingTotal'] = $followingTotal;
                }
                
                if ($followingGoodest = $this->statModel->combo($vars,'goodest',5))
                {
                    $data['followingGoodest'] = $followingGoodest;
                }
                
                if ($followingBaadest = $this->statModel->combo($vars,'baadest',5))
                {
                    $data['followingBaadest'] = $followingBaadest;
                }
                
                unset($vars);
            }
        }
        
        $this->renderView($data,array(),'Topics voting activity');
    }
    
    function individual($input = null)
    {
        $override = array();
        
        if ($input == null)
        {
            $data['msg'] = "Topic Not Found";
            
            $title = 'Not Found';
        }
        else
        {
            $this->topicModel = new TopicModel;
            
            $handle = $input[0];
            
            if ($topic = $this->topicModel->getTopic($handle)) # GET TOPIC
            {
                if (count($topic)>1)
                {
                    foreach ($topic as $t)
                    {
                        if ($tags = $this->topicModel->getTopicTags($t['guid'])) # GET TAGS
                        {
                            $t['tags'] = $tags;
                        }
                        
                        $topics[] = $t;
                    }
                    
                    $data['topics'] = $topics;
                    
                    $title = 'Which \'' . $topic[0]['title'] . '\' Were You After?';
                    
                    $override = array('Controller'=>'topics','Type'=>'xhtml','Method'=>'multi');
                }
                else
                {
                    $data['topic'] = $topic;
                    
                    $data['topic'][0]['free_id_safe'] = strtolower(str_replace('/','.',$data['topic'][0]['freebase_id']));
                    
                    $title = $topic[0]['title']  . ': recent votes';
                    
                    if ($tags = $this->topicModel->getTopicTags($handle)) # GET TAGS
                    {
                        $data['tags'] = $tags;
                    }
                    
                    $this->statModel = new StatModel;
                    
                    $vars['topicID'] = $topic[0]['id'];
                    
                    if ($overAllOpinion = $this->statModel->combo($vars)) # GET ALL TIME TOPIC STATS
                    {
                        $data['overAllOpinion']['votes'] = $overAllOpinion[0];
                        $data['overAllOpinion']['percentages'] = $this->percentages($overAllOpinion[0]['good'],$overAllOpinion[0]['baad']);
                    }
                    
                    unset($vars); # RESET VARS
                    
                    if (isset($_SESSION['userName'])) # GET FRIEND'S STATS IF LOGGED IN
                    {
                        $this->userModel = new UserModel;
                        
                        if ($user = $this->userModel->getUser($_SESSION['userName'])) # GET USER DATA
                        {
                            $data['user'] = $user[0];
                            
                            if ($lastVote = $this->userModel->checkUserVote($user[0]['id'],$topic[0]['id'])) # CHECK LAST OPINION
                            {
                                $data['lastVote'] = $lastVote[0];
                            }
                            
                            if ($followedBy = $this->userModel->getFollowing($user[0]['username'])) # GET FOLLOWING
                            {
                                $data['followedBy'] = $followedBy;
                                
                                $vars['topicID'] = $topic[0]['id'];
                                $vars['userIDs'] = $followedBy;
                                
                                if ($followedByStats = $this->statModel->combo($vars))
                                {
                                    $data['followedByStats']['votes'] = $followedByStats[0];
                                    $data['followedByStats']['percentages'] = $this->percentages($followedByStats[0]['good'],$followedByStats[0]['baad']);
                                }
                                unset($vars); # RESET VARS
                            }
                            
                            if ($following = $this->userModel->getFollowedBy($user[0]['username'])) # GET FOLLOED BY
                            {
                                $data['following'] = $following;
                                
                                $vars['topicID'] = $topic[0]['id'];
                                $vars['userIDs'] = $following;
                                
                                if ($followingStats = $this->statModel->combo($vars))
                                {
                                    $data['followingStats']['votes'] = $followingStats[0];
                                    $data['followingStats']['percentages'] = $this->percentages($followingStats[0]['good'],$followingStats[0]['baad']);
                                }
                                unset($vars); # RESET VARS
                            }
                        }
                    }
                    
                    $this->feedModel = new FeedModel;
                    
                    if ($feed = $this->feedModel->topicFeed($handle))
                    {
                        $data['feed'] = $feed;
                    }
                }
            }
            else
            {
                $data['msg'] = "Topics Not Found";
            
                $title = 'Not Found';
            }
            
            $this->renderView($data,$override,$title);
        }
    }
    
}
?>