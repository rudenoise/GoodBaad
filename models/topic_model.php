<?php
Class TopicModel extends BaseMdl
{
    function getTopic($handle)
    {
        $query = "SELECT * FROM topics AS topic WHERE topic.handle = '$handle'  || topic.guid = '$handle';";
        
        return $this->query($query);
    }
    
    function getTopicTags($handle)
    {
        $query = "
        SELECT tag.title FROM topics AS topic
          LEFT OUTER JOIN topics_tags AS i_t ON topic.id = i_t.topic_id
          LEFT OUTER JOIN tags AS tag ON i_t.tag_id = tag.id
        WHERE topic.handle = '$handle'  || topic.guid = '$handle';
        ";
        
        return $this->query($query);
    }
}
?>