<?php
Class VotesController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
        
        $this->VoteModel = new VoteModel;
        
    }
    
    function index()
    {
        $overide = array();
        $this->renderView(null,$overide, 'Vote');
    }
    
    function search()
    {
        if (isset($_POST['data']['Topic']['Str']))
        {
            if (!empty($_POST['data']['Topic']['Str']))
            {
                $str = 'http://freebase.com/api/service/search?category=object&limit=15&prefix=' . urlencode($this->VoteModel->security->paranoid($_POST['data']['Topic']['Str'],array(' ')));
                
                if ($json = $this->jsonConnect($str))
                {
                    $i = 0;
                    foreach ($json->result as $result)
                    {
                        if ($result->name != null && (($result->type[0]->name != 'Domain') && ($result->type[0]->name != 'Content')))
                        {
                            $data[$i]['name'] = $result->name;
                            
                            $data[$i]['guid'] = preg_replace('[#]','',$result->guid);
                            
                            if (is_object($result->image)) $data[$i]['image'] = $result->image->id;
                            
                            foreach($result->type as $type)
                            {
                                $types[] = $type->name;
                            }
                            
                            $data[$i]['types'] = $types;
                            
                            unset($types);
                            
                            $i++;
                        }
                    }
                    
                    if (!isset($data)) $data['msg'] = "Sorry, no freebase topics matched your search, please check your spelling and retry. Or, maybe Freebase doesn't know about this topic yet, if so you shoud add it yourself at <a href=\"http://www.freebase.com/\" target=\"blank\">http://www.freebase.com/</a> or <a href=\"http://www.wikipedia.org/\" target=\"blank\">http://www.wikipedia.org/</a>";
                    
                    $data['string'] = $_POST['data']['Topic']['Str'];
                    
                    $title = 'Results';
                    $this->renderView($data,array(), $title . ': votes');
                }
                else
                {
                    $data['msg'] = "The Freebase site appears to be down, or the wrong url has been used. Please try again.";
                    $title = 'Cast';
                    $this->renderView($data,array('Controller'=>'votes','Type'=>'xhtml','Method'=>'index'), $title . ': votes');
                }
            }
            else
            {
                $title = 'Cast';
                $this->renderView(null,array('Controller'=>'votes','Type'=>'xhtml','Method'=>'index'), $title . ': votes');
            }
        }
        else
        {
            $title = 'Cast';
            $this->renderView(null,array('Controller'=>'votes','Type'=>'xhtml','Method'=>'index'), $title . ': votes');
        }
    }
    
    function cast($input = null)
    {
        if ($input !=null)
        {
	        if ((!isset($input[0])) || (!isset($input[1])))
	        {
	            $data['msg'] = "There was a problem with your vote, please try again.";
	        }
	        else
	        {
	            $guid = $input[0];
	            
	            $opinion = $input[1];
	            
	            if ($topic = $this->VoteModel->checkTopic($guid))
	            {
	                if ($existing = $this->VoteModel->checkVote($_SESSION['userName'],$topic[0]['id']))
	                {
	                    if ($existing[0]['opinion'] == $opinion)
	                    {
	                        $data['msg'] =  "You haven't changed your mind and still think <a href='/topics/".$topic[0]['handle']."' title='".$topic[0]['title']."'>". $topic[0]['title'] . "</a> is " . $opinion .".";
	                    }
	                    else
	                    {
	                        if ($this->VoteModel->insertVote($topic[0]['id'],$_SESSION['userName'],$opinion))
	                        {
	                            #$data['msg'] =  "You have changed you mind and now think <a href='/stats/topic/".$topic[0]['handle']."' title='".$topic[0]['title']."'>". $topic[0]['title'] . "</a> is " . $opinion .".";
	                            header('Location:http://'. $_SERVER['HTTP_HOST'] .'/topics/'.$topic[0]['handle']);
                                die();
	                        }
	                    }
	                }
	                else
	                {
    	                if ($this->VoteModel->insertVote($topic[0]['id'],$_SESSION['userName'],$opinion))
    	                {
    	                    #$data['msg'] =  "New vote added.";
    	                    header('Location:http://'. $_SERVER['HTTP_HOST'] .'/topics/'.$topic[0]['handle']);
                            die();
    	                }
	                }
	            }
	            else
	            {
	                $str = 'http://www.freebase.com/api/service/mqlread?query='.urlencode('{"query":[{"guid":"#'.$guid.'","id":null,"name":null,"type":[]}]}');
    	            
    	            if ($json = $this->jsonConnect($str))
    	            {
    	                #debug($json);
    	                #if(is_object($json->result[0]))
    	                if(isset($json->result[0]))
    	                {
    	                    foreach ($json->result[0]->type as $type) # GET TAGS
    	                    {
    	                        if (!preg_match('/user/i',$type))
    	                        {
    	                            $parts = explode("/",$type);
    	                            
    	                            foreach ($parts as $part)
    	                            {
    	                                if ((strlen($part) > 0)&&(strlen($part) <= 20)&&($part != 'guid')) $tags[] = $part;
    	                            }
    	                        }
    	                    }
    	                    
    	                    /*
                             * DEAL WITH ITEM:
                             */
                            
    	                    $id = $json->result[0]->id;
                            
                            $handle = $this->VoteModel->handle($json->result[0]->name);
                            
                            if ($topic = $this->VoteModel->insertTopic($guid,$id,preg_replace("/'/","\'",$json->result[0]->name),$handle))
                            {
                                $topicID = $topic[0]['id'];
                            }
                            /*
                             * END DEAL WITH ITEM;
                             */
    	                    
                            /*
    	                     * DEAL WITH TAGS
    	                     */
    	                    $tags = array_unique($tags);
    	                    #debug($tags);
    	                    foreach ($tags as $tagTitle) # CHECK THEN ADD NEW TAGS 
    	                    {
    	                        if($t2 = $this->VoteModel->checkTag($tagTitle))
    	                        {
    	                            $tagID = $t2[0]['id'];
    	                        }
    	                        else
    	                        {
    	                            if ($newTag = $this->VoteModel->insertTag($tagTitle))
    	                            {
    	                                $tagID = $newTag[0]['id'];
    	                            }
    	                        }
    	                        
    	                        if ($this->VoteModel->insertTopicTag($topicID,$tagID))
    	                        {
    	                            
    	                        }
    	                    }
    	                    
    	                    /*
                             * DEAL WITH VOTE:
                             */
        	                if ($this->VoteModel->insertVote($topicID,$_SESSION['userName'],$opinion))
                            {
                                #$data['msg'] =  "New vote added.";
                                header('Location:http://'. $_SERVER['HTTP_HOST'] .'/topics/'.$handle);
                                die();
                            }
    	                    /*
    	                     * END DEAL WITH VOTE
    	                     */
    	                }
    	                else
    	                {
    	                    $data['msg'] = "Freebase doesn't recognise this topic, please try again.";
    	                }
    	            }
    	            else
    	            {
    	                $data['msg'] = "The Freebase site appears to be down, or the wrong url has been used. Please try again.";
    	            }
	            }
	        }
        }
        else
        {
            $data['msg'] = "Freebase doesn't recognise this topic, please try again.";
        }
        
        if (isset($data['msg']))
        {
            $this->renderView($data,array(),'Cast Vote : Success');
        }
    }
}
?>