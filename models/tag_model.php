<?php
Class TagModel extends BaseMdl
{
    function getStartsWith($letter)
    {
        $sql = 'SELECT * FROM tags AS tag WHERE title LIKE \''.$letter.'%\' ORDER BY title ASC;';
        
        return $this->query($sql);
    }
    
    function getTitle($name)
    {
        $sql = 'SELECT * FROM tags AS tag WHERE tag.title = \''.$name.'\' LIMIT 1;';
        
        return $this->query($sql);
    }
    
    function mostGood($title)
    {
        $sql = '
        SELECT topic.id, topic.title, topic.handle,
        SUM(CASE WHEN t_u1.opinion = \'good\' THEN 1 ELSE 0 END) good
        FROM
        (
          SELECT topic.id, topic.title, topic.handle
          FROM topics_tags AS t_t 
            JOIN tags AS tag ON t_t.tag_id = tag.id 
            JOIN topics AS topic ON t_t.topic_id = topic.id  
          WHERE tag.title = \''.$title.'\'
        ) AS topic 
          LEFT OUTER JOIN (SELECT * FROM topics_users ORDER BY id DESC) AS t_u1 ON topic.id = t_u1.topic_id
        WHERE
        (
          t_u1.modified = (SELECT MAX(t_u2.modified) FROM topics_users AS t_u2 WHERE t_u1.user_id = t_u2.user_id AND t_u1.topic_id = t_u2.topic_id LIMIT 0,1)
        )
        GROUP BY topic.title ORDER BY good DESC LIMIT 5;
        ';
        
        return $this->query($sql);
    }
    
    function mostBaad($title)
    {
        $sql = '
        SELECT topic.id, topic.title, topic.handle,
        SUM(CASE WHEN t_u1.opinion = \'baad\' THEN 1 ELSE 0 END) baad
        FROM
        (
          SELECT topic.id, topic.title, topic.handle
          FROM topics_tags AS t_t 
            JOIN tags AS tag ON t_t.tag_id = tag.id 
            JOIN topics AS topic ON t_t.topic_id = topic.id  
          WHERE tag.title = \''.$title.'\'
        ) AS topic 
          LEFT OUTER JOIN (SELECT * FROM topics_users ORDER BY id DESC) AS t_u1 ON topic.id = t_u1.topic_id
        WHERE
        (
          t_u1.modified = (SELECT MAX(t_u2.modified) FROM topics_users AS t_u2 WHERE t_u1.user_id = t_u2.user_id AND t_u1.topic_id = t_u2.topic_id LIMIT 0,1)
        )
        GROUP BY topic.title ORDER BY baad DESC LIMIT 5;
        ';
        
        return $this->query($sql);
    }
    
    function mostVoted($title)
    {
        $sql = '
        SELECT topic.id, topic.title, topic.handle,
        SUM(CASE WHEN t_u1.opinion THEN 1 ELSE 0 END) total
        FROM
        (
          SELECT topic.id, topic.title, topic.handle
          FROM topics_tags AS t_t 
            JOIN tags AS tag ON t_t.tag_id = tag.id 
            JOIN topics AS topic ON t_t.topic_id = topic.id  
          WHERE tag.title = \''.$title.'\'
        ) AS topic 
          LEFT OUTER JOIN (SELECT * FROM topics_users ORDER BY id DESC) AS t_u1 ON topic.id = t_u1.topic_id
        WHERE
        (
          t_u1.modified = (SELECT MAX(t_u2.modified) FROM topics_users AS t_u2 WHERE t_u1.user_id = t_u2.user_id AND t_u1.topic_id = t_u2.topic_id LIMIT 0,1)
        )
        GROUP BY topic.title ORDER BY total DESC LIMIT 5;
        ';
        
        return $this->query($sql);
    }
    
    function allTopics($tagTitle)
    {
        $sql = '
        SELECT topic.title, topic.handle FROM topics_tags AS t_t
          JOIN tags AS tag ON t_t.tag_id = tag.id
          JOIN topics AS topic ON t_t.topic_id = topic.id 
        WHERE tag.title = \''.$tagTitle.'\'
        ORDER BY topic.title ASC;';
        
        return $this->query($sql);
    }
}
?>