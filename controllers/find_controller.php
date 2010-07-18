<?php
Class FindController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
        
        $this->security = new Security;
    }
    
    function search($input = null)
    {
        if (isset($input[0]['string'])&&isset($input[0]['option']))
        {
            $data['option'] = $input[0]['option']; 
            
            $string1 = $this->security->paranoid($input[0]['string'],array('"',' ','+','-',"'"));
            
            if (strlen($string1) > 2)
            {
            
                $strings = split("[ ]",$string1);
                
                $i = 0;
                $n = 0;
                
                $returnStr = '';
                
                foreach ($strings as $string)
                {
                    if (preg_match("[\"]",$string)||preg_match("[\']",$string))
                    {
                        if ($i == 0)
                        {
                            $returnStr .= $string . ' ';
                            
                            if ($input[0]['option'] == 'topic')
                            {
                                if (preg_match("[\"]",$string)) $searchArr['topic'][$n]['title'] = preg_replace("[\"]","",$string) . ' ';
                                if (preg_match("[\']",$string)) $searchArr['topic'][$n]['title'] = preg_replace("[\']","",$string) . ' ';
                            }
                            elseif ($input[0]['option'] = 'user')
                            {
                                if (preg_match("[\"]",$string)) $searchArr['user'][$n]['username'] = preg_replace("[\"]","",$string) . ' ';
                                if (preg_match("[\']",$string)) $searchArr['user'][$n]['username'] = preg_replace("[\']","",$string) . ' ';
                            }
                            
                            $i++;
                        }
                        elseif($i == 1)
                        {
                            $returnStr .= $string. ' ';
                            
                            if ($input[0]['option'] == 'topic')
                            {
                                if (preg_match("[\"]",$string)) $searchArr['topic'][$n]['title'] .= preg_replace("[\"]","",$string);
                                if (preg_match("[\']",$string)) $searchArr['topic'][$n]['title'] .= preg_replace("[\']","",$string);
                                
                                $searchArr['topic'][$n]['title'] = trim($searchArr['topic'][$n]['title']);
                            }
                            elseif ($input[0]['option'] = 'user')
                            {
                                if (preg_match("[\"]",$string)) $searchArr['user'][$n]['username'] .= preg_replace("[\"]","",$string);
                                if (preg_match("[\']",$string)) $searchArr['user'][$n]['username'] .= preg_replace("[\']","",$string);
                                
                                $searchArr['user'][$n]['username'] = trim($searchArr['user'][$n]['username']);
                            }
                            
                            $i = 0;
                            
                            $n++;
                        }
                    }
                    else
                    {
                        if ($input[0]['option'] == 'topic')
                        {
                            if ($i==1)
                            {
                                $searchArr['topic'][$n]['title'] .= ' ' . trim($string);
                            }
                            else
                            {
                                $searchArr['topic'][$n]['title'] = trim($string);
                                $n++;
                            }
                            
                            $returnStr .= $string . " ";
                        }
                        elseif ($input[0]['option'] == 'user')
                        {
                            if ($i==1)
                            {
                                $searchArr['user'][$n]['username'] .= ' ' . trim($string);
                            }
                            else
                            {
                                $searchArr['user'][$n]['username'] = trim($string);
                                $n++;
                            }
                            
                            $returnStr .= $string . ' ';
                        }
                        
                    }
                }
                
                if (isset($searchArr))
                {
                    $this->findModel = new FindModel;
                    
                    if ($results = $this->findModel->find($searchArr,$input[0]['option'],'like'))
                    {
                        $data['results'] = $results;
                    }
                }
                
                $data['string'] = trim(htmlentities($returnStr));
                
                $this->renderView($data,array(),'Find');
            }
            else
            {
                $data['string'] = trim(htmlentities($string1));
                $data['msg'] = 'Search must be longer than two characters.';
                $this->renderView($data,array(),'Find');
            }
        }
        else
        {
            $this->renderView(null,array(),'Find');
        }
    }
}
?>