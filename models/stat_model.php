<?php
Class StatModel extends BaseMdl
{
    var $recentTotal = TOTAL;
    
    function topActiveUsers($vars = array())
    {
        $sql = '
            SELECT user.username, COUNT(t_u1.id) AS total
                FROM (SELECT * FROM topics_users';
        
        if (isset($vars['userIDs'])) $sql .= 'WHERE ' . $this->chain($vars['userIDs'],'user.id','id'); # MULTIPLE USERS
        
        $sql .= '
                ORDER BY topics_users.id DESC LIMIT '.$this->recentTotal.') AS t_u1
            INNER JOIN users AS user ON t_u1.user_id = user.id
            GROUP BY user.username
            ORDER BY total DESC
        ';
        
        if (isset($vars['limit'])) $limit = $vars['limit']; else $limit = 5;
        
        $sql .= '
            LIMIT ' . $limit;
        
        return $this->query($sql);
    }
    
    function grandTotal($vars = array())
    {
        $subquery = 'SELECT * FROM topics_users ';
        
        if (count($vars) > 0) $subquery .= ' WHERE id IS NOT NUll ';
        
        if (isset($vars['userIDs'])) $subquery .= '  AND ' . $this->chain($vars['userIDs'],'topics_users.user_id','id'); # MULTIPLE USERS
        
        if (isset($vars['userID'])) $subquery .= '  AND topics_users.user_id = ' . $vars['userID']; # SINGLE USER
        
        if (isset($vars['topicIDs'])) $subquery .= ' AND ' . $this->chain($vars['topicIDs'],'topics_users.topic_id','id'); # MULTIPLE TOPICS
        
        if (isset($vars['topicID'])) $subquery .= '  AND topics_users.topic_id = ' . $vars['topicID']; # SINGLE TOPIC
        
        $subquery .= ' ORDER BY id DESC LIMIT ' . $this->recentTotal;
        
        
        $sql = '
        SELECT 
          SUM(CASE WHEN t_u1.opinion = \'baad\' THEN 1 ELSE 0 END) baad,
          SUM(CASE WHEN t_u1.opinion = \'good\' THEN 1 ELSE 0 END) good
        FROM ('.$subquery.') AS t_u1
        WHERE 
          (t_u1.modified = (SELECT MAX(t_u2.modified) FROM topics_users AS t_u2 WHERE t_u1.user_id = t_u2.user_id AND t_u1.topic_id = t_u2.topic_id LIMIT 0,1))';
        
        $sql .= '
        ORDER BY t_u1.modified DESC;
        ';
        
        return $this->query($sql);
    }
    
    function combo($vars = array(), $type = null, $limit = 10)
    {
        $subquery = 'SELECT * FROM topics_users ';
        
        if (count($vars) > 0) $subquery .= ' WHERE id IS NOT NUll ';
        
        if (isset($vars['userIDs'])) $subquery .= '  AND ' . $this->chain($vars['userIDs'],'topics_users.user_id','id'); # MULTIPLE USERS
        
        if (isset($vars['userID'])) $subquery .= '  AND topics_users.user_id = ' . $vars['userID']; # SINGLE USER
        
        if (isset($vars['topicIDs'])) $subquery .= ' AND ' . $this->chain($vars['topicIDs'],'topics_users.topic_id','id'); # MULTIPLE TOPICS
        
        if (isset($vars['topicID'])) $subquery .= '  AND topics_users.topic_id = ' . $vars['topicID']; # SINGLE TOPIC
        
        if (isset($vars['date'])) $subquery .= ' AND topics_users.modified < \'' . $vars['date'] .'\'';
        
        $subquery .= ' ORDER BY id DESC LIMIT ' . $this->recentTotal;
        
        
        /* SET UP "TOTALS" QUERY */
        $sql = '
        SELECT topic.id, topic.title, topic.handle, ';
        
        /* TOTAL TYPE (GOODEST, BADDEST, MOST VOTED) */
        switch ($type)
        {
            case 'goodest':
                $sql .= ' SUM(CASE WHEN t_u1.opinion = \'good\' THEN 1 ELSE 0 END) good';
                $orderby = 'good';
                break;
            
            case 'baadest':
                $sql .= ' SUM(CASE WHEN t_u1.opinion = \'baad\' THEN 1 ELSE 0 END) baad';
                $orderby = 'baad';
                break;
            
            case 'total':
                $sql .= ' SUM(CASE WHEN t_u1.opinion THEN 1 ELSE 0 END) total';
                $orderby = 'total';
                break;
            
            default:
                $sql .= '
                  SUM(CASE WHEN t_u1.opinion = \'baad\' THEN 1 ELSE 0 END) baad,
                  SUM(CASE WHEN t_u1.opinion = \'good\' THEN 1 ELSE 0 END) good';
                  $orderby = 't_u1.modified';
                break;
        }
        
        $sql .= '
        FROM topics AS topic 
          LEFT OUTER JOIN ('.$subquery.') AS t_u1 ON topic.id = t_u1.topic_id';
        
        $sql .= '
        WHERE 
          (t_u1.modified = (SELECT MAX(t_u2.modified) FROM topics_users AS t_u2 WHERE t_u1.user_id = t_u2.user_id AND t_u1.topic_id = t_u2.topic_id LIMIT 0,1))';
        
        /* SEPARATE RESULTS BY TOPIC AND ORDER BY DATE */
        $sql.='
        GROUP BY topic.title ';
        
        $sql .= 'ORDER BY '.$orderby.' DESC LIMIT '.$limit.';';
        
        return $this->query($sql);
    }

}
?>