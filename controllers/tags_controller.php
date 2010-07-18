<?php
Class TagsController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request;
    }
    
    function index()
    {
        echo 'index';
    }
    
    function starting($input = null)
    {
        if ($input == null)
        {
            $letter = 'a';
        }
        else
        {
            $letter = $input[0];
        }
        
        if (strlen($letter) == 1)
        {
            $data['letter'] = $letter;
            
            $this->tagModel = new TagModel;
            
            $tags = $this->tagModel->getStartsWith($letter);
            
            $data['total'] = count($tags);
            
            $data['split'] = round(count($tags) / 2);
            
            $data['tags'] = $tags;
            
            if (!$data['tags'])
            {
                unset($data['tags']);
                
                $data['msg'] = 'No tags starting with: ' . ucfirst($letter);
            }
        }
        else
        {
            $data['msg'] = 'Not found!';
        }
        
        #debug($data);
        $this->renderView($data,array(), 'Tags list');
    }
    
    function name($input = null)
    {
        
        if ($input == null)
        {
            $data['msg'] = 'Not found!';
            
            $data['title'] = 'Not found!';
        }
        else
        {
            $title = $input[0];
            
            $this->tagModel = new TagModel;
            
            if ($tag = $this->tagModel->getTitle($title))
            {
                $data['title'] = 'Topics tagged: ' . $tag[0]['title'];
                
                $data['tag'] = $tag[0]['title'];
                
                $data['mostVoted'] = $this->tagModel->mostVoted($title);
                
                $data['mostGood'] = $this->tagModel->mostGood($title);
                
                $data['mostBaad'] =$this->tagModel->mostBaad($title);
                
                $data['allTopics'] = $this->tagModel->allTopics($title);
            }
            else
            {
                $data['title'] = 'Not found!';
                
                $data['msg'] = 'Not found!';
            }
            
            #
            #debug($data);
        }
        $this->renderView($data,array(), $data['title']);
    }
}
?>