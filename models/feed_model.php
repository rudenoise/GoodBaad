<?php
Class FeedModel extends BaseMdl
{
    function masterFeed($offset = 0, $rowCount = 10)
    {
        $query ="
        SELECT user.username, topic.title, topic.guid, topic.handle, topics_users.opinion, topic.freebase_id, topic.handle, topics_users.modified FROM users as user
            LEFT JOIN
                (topics_users LEFT JOIN topics AS topic ON topics_users.topic_id = topic.id)
            ON user.id = topics_users.user_id
        WHERE /*user.active = 'active' AND*/ topics_users.opinion IS NOT NULL
        ORDER BY topics_users.modified DESC
        LIMIT $offset,$rowCount;
        ";
        
        if ($masterFeed = $this->query($query)) return $masterFeed;
    }
    
    function userFeed($user, $offset = 0, $rowCount = 10)
    {
        if (is_numeric($user))
        {
            $searchStr = "user.id = " . $user;
        }
        else
        {
            $searchStr = "user.username = '$user'";
        }
        $query ='
        SELECT user.username, topic.title, topic.guid, topic.handle, topics_users.opinion, topic.freebase_id, topics_users.modified FROM users as user
          LEFT JOIN
            (topics_users LEFT JOIN topics AS topic ON topics_users.topic_id = topic.id)
          ON user.id = topics_users.user_id
        WHERE '.$searchStr.' AND /*user.active = \'active\' AND*/ topics_users.opinion IS NOT NULL
        ORDER BY topics_users.modified DESC
        ';
        
        if ($rowCount != 0) $query .= 'LIMIT ' . $offset . ','.$rowCount.';'; else $query .= ';';
        
        if ($userFeed = $this->query($query)) return $userFeed;
    }
    
    function topicFeed($handle, $offset = 0, $rowCount = 10)
    {
        $query = "
        SELECT user.username, topic.title, topic.guid, topic.handle, topics_users.opinion, topic.freebase_id, topics_users.modified FROM users as user
            LEFT JOIN
            (topics_users LEFT JOIN topics AS topic ON topics_users.topic_id = topic.id)
            ON user.id = topics_users.user_id
        WHERE /*user.active = 'active' AND*/ topics_users.opinion IS NOT NULL AND (topic.handle = '$handle' || topic.guid = '$handle')
        ORDER BY topics_users.modified DESC
        LIMIT 0,10;
        ";
        
        if ($topicFeed = $this->query($query)) return $topicFeed;
    }
    
    function comboFeed($vars,$offset = 0,$rowCount = 10)
    {
        $string = $this->chain($vars['users'],'user.id','id');
        
        $query = "
        SELECT user.username, topic.title, topic.guid, topic.handle, topics_users.opinion, topic.freebase_id, topics_users.modified FROM users AS user
            LEFT JOIN
            (topics_users LEFT JOIN topics AS topic ON topics_users.topic_id = topic.id)
            ON user.id = topics_users.user_id
        WHERE /*user.active = 'active' AND*/ topics_users.opinion IS NOT NULL
          AND $string
        ORDER BY topics_users.modified DESC
        LIMIT $offset,$rowCount;
        ";
        
        if ($feed = $this->query($query))
        {
            return $feed;
        }
    }
}
?>