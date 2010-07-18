<?php
Class FindModel extends BaseMdl
{
    function find($vars,$type = topic)
    {
        if ($type == 'topic')
        {
            if ($exactChain = $this->chain($vars['topic'],'topic.title','title'))
            {
                $result['topic']['exact'] = $this->findTopic($exactChain);
            }
            if ($likeChain = $this->chain($vars['topic'],'topic.title','title','like'))
            {
                $result['topic']['like'] = $this->findTopic($likeChain);
            }
        }
        elseif ($type == 'user')
        {
            if ($exactChain = $this->chain($vars['user'],'user.username','username'))
            {
                $result['user']['exact'] = $this->findUser($exactChain);
            }
            if ($likeChain = $this->chain($vars['user'],'user.username','username','like'))
            {
                $result['user']['like'] = $this->findUser($likeChain);
            }
        }
        
        if (isset($result)) return $result;
    }
    
    function findUser($str, $start = 0, $total = 5)
    {
        $query = "SELECT user.username FROM users AS user WHERE $str LIMIT $start,$total;";
        
        return $this->query($query);
    }
    
    function findTopic($str, $start = 0, $total = 5)
    {
        $query = "SELECT topic.title, topic.handle FROM topics AS topic WHERE $str LIMIT $start,$total;";
        
        return $this->query($query);
    }
}
?>