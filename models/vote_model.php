<?php
Class VoteModel extends BaseMdl
{
    function __construct()
    {
        $this->security = new Security;
    }
    
    function checkTopic($guid)
    {
        $getTopic = "SELECT * FROM topics WHERE guid = '$guid';";
        if ($result = $this->query($getTopic))
        {
            return $result;
        }
    }
    
    function insertTopic($guid,$topicID,$name,$handle)
    {
        $insertTopic ="
        INSERT INTO topics 
            (guid,freebase_id, title, handle, added)
        VALUES
            ('".$guid."','".$topicID."', '$name', '$handle', NOW());";
            
        if ($this->insert($insertTopic))
        {
            if ($topic = $this->checkTopic($guid))
            {
                return $topic;
            }
        }
    }
    
    function checkTag($title)
    {
        $getTag = "SELECT * FROM tags as tag WHERE tag.title = '$title';";
        
        if ($tag = $this->query($getTag))
        {
            return $tag;
        }
    }
    
    function insertTag($title)
    {
        $insertTag ="
        INSERT INTO tags 
            (title)
        VALUES
            ('$title');";
        
        if ($this->insert($insertTag))
        {
            return $this->checkTag($title);
        }
    }
    
    function insertTopicTag($topicID,$tagID)
    {
        $insertTopicTag = "
        INSERT INTO topics_tags 
            (topic_id, tag_id)
        VALUES
            ($topicID, $tagID);
        ";
        
        if ($this->insert($insertTopicTag))
        {
            return true;
        }
    }
    
    function checkVote($userName,$topicID)
    {
        $checkVote = "
        SELECT * FROM topics_users
          LEFT JOIN users AS user ON topics_users.user_id = user.id
        WHERE (user.username = '$userName')&&(topic_id = $topicID) ORDER BY modified DESC;
        ";
        
        if ($vote = $this->query($checkVote))
        {
            return $vote;
        }
    }
    
    function insertVote($topicID,$userName,$opinion)
    {
        if ($userID = $this->getUserID($userName))
        {
            $insertVote = "INSERT INTO topics_users 
                (topic_id, user_id, opinion, modified)
                VALUES
                ($topicID, $userID, '$opinion', NOW());
            ";
                
            if ($this->insert($insertVote))
            {
                return true;
            }
        }
    }
}
?>