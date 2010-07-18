<?php
Class ApiController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
        
        $this->VoteModel = new VoteModel;
        
    }
    
    function index()
    {
        echo 'no request made';
    }
    
    function vote($args = null)
    {
        if ($args)
        {
            $request = json_decode($args[0]);
            
            if (isset($request->guid) && isset($request->opinion))
            {
                if ($request->opinion == 'good' || $request->opinion == 'baad')
                {
                    if ($topic = $this->VoteModel->checkTopic($request->guid))
                    {
                        if ($existing = $this->VoteModel->checkVote(joel,$topic[0]['id']))
                        {
                            if ($existing[0]['opinion'] == $request->opinion)
                            {
                                $data['msg'] =  "You haven't changed your mind and still think ". $topic[0]['title'] . " is " . $request->opinion .".";
                            }
                            else
                            {
                                if ($this->VoteModel->insertVote($topic[0]['id'],joel,$request->opinion))
                                {
                                    $data['msg'] =  "You have changed you mind and now think ". $topic[0]['title'] . " is " . $request->opinion .".";
                                }
                            }
                        }
                        else
                        {
                            if ($this->VoteModel->insertVote($topic[0]['id'],joel,$request->opinion))
                            {
                                $data['msg'] =  'You voted '. $topic[0]['title'] . ': $request->opinion.';
                            }
                        }
                    }
                    else
                    {
                        $str = 'http://www.freebase.com/api/service/mqlread?query='.urlencode('{"query":[{"guid":"#'.$request->guid.'","id":null,"name":null,"type":[]}]}');
                        
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
                                
                                if ($topic = $this->VoteModel->insertTopic($request->guid,$id,preg_replace("/'/","\'",$json->result[0]->name),$handle))
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
                                if ($this->VoteModel->insertVote($topicID,joel,$request->opinion))
                                {
                                    $data['msg'] =  "New vote added.";
                                }
                                else
                                {
                                    $data['msg'] = 'bad request';
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
                else
                {
                    $data['msg'] = 'bad request';
                }
            }
            else
            {
                $data['msg'] = 'bad request';
            }
        }
        else
        {
            $data['msg'] = 'bad request';
        }
        
        header('Content-type: application/x-javascript');
        
        echo $_GET['jsoncallback'] . '(' . json_encode($data) . ')';
    }
}
?>