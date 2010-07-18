<?php
Class ConsoleModel extends BaseMdl
{
    function getTopics($guid)
    {
        $query = 'SELECT * FROM topics WHERE guid = \''.$guid.'\';';
        
        return $this->query($query);
    }
    
    function getDayStats($time)
    {
        $query = '
        SELECT 
          SUM(CASE WHEN t_u1.opinion = \'baad\' THEN 1 ELSE 0 END) baad,
          SUM(CASE WHEN t_u1.opinion = \'good\' THEN 1 ELSE 0 END) good
        FROM (SELECT * FROM topics_users WHERE topics_users.modified < \'' . $time . '\' ORDER BY topics_users.modified DESC LIMIT '.TOTAL.') AS t_u1
        WHERE 
          (t_u1.modified = (SELECT MAX(t_u2.modified) FROM topics_users AS t_u2 WHERE t_u1.user_id = t_u2.user_id AND t_u1.topic_id = t_u2.topic_id LIMIT 0,1))
        ORDER BY t_u1.modified DESC;
        ';
        
        return $this->query($query);
    }
    
    function getTopicsList()
    {
        return $this->query('SELECT handle FROM topics;');
    }
    
    function getUsers()
    {
        return $this->query('SELECT username FROM users;');
    }
    
    function getTags()
    {
        return $this->query('SELECT title FROM tags AS tag;');
    }
}
?>