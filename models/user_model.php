<?php
Class UserModel extends BaseMdl
{
    function getUser($username)
    {
        $query = "SELECT id,username FROM users AS user WHERE user.username = '$username';";
        
        return $this->query($query);
    }
    
    function checkUserVote($userID,$topicID)
    {
        $query = "SELECT * FROM topics_users WHERE (user_id = $userID)&&(topic_id = $topicID) ORDER BY modified DESC;";
        
        return $this->query($query);
    }
    
    function getFollowing($username)
    {
        $query = "
		SELECT follower.id, follower.username FROM followers_users AS f_u
          LEFT JOIN users AS user ON f_u.user_id = user.id
          LEFT JOIN users AS follower ON f_u.follower_id = follower.id
        WHERE user.username = '$username';
		";
        
        return $this->query($query);
    }
    
    function getFollowedBy($username)
    {
        $query = "
		SELECT user.id, user.username FROM followers_users AS f_u
          LEFT JOIN users AS user ON f_u.user_id = user.id
          LEFT JOIN users AS follower ON f_u.follower_id = follower.id
        WHERE follower.username = '$username';
		";
        
        return $this->query($query);
    }
    
    function checkFollowing($userID, $followerID)
    {
        $sql = "
        SELECT count(*) AS total from followers_users WHERE followers_users.user_id = $userID AND followers_users.follower_id = $followerID;
        ";
        
        if ($rslt = $this->query($sql))
        {
            if ($rslt[0]['total'] > 0) return true;
        }
    }
    
    function follow($userID, $followerID)
    {
        $sql = "
        INSERT INTO followers_users 
            (user_id, follower_id, date)
            VALUES
            ($userID, $followerID, NOW());
        ";
        
        if ($this->insert($sql))
        {
            return true;
        }
    }
    
    function getUserEmails()
    {
        $query ="SELECT * FROM users WHERE users.email IS NOT NULL;";
        
        return $this->query($query);
    }
    
    function getInviteList()
    {
        $query ="SELECT * FROM invites WHERE email IS NOT NULL;";
        
        return $this->query($query);
    }
}
?>