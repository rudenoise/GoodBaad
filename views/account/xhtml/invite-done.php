<h2>Invite More Good Baad Users</h2>

<?php if (isset($data['alreadyFollowing'])): $something = true;?>
    
    <h3>You Were Already Following</h3>
    
    <ul>
        <?php foreach ($data['alreadyFollowing'] as $user):?>
            
            <li><a href="/users/<?php echo $user;?>"><?php echo ucfirst($user);?></a></li>
            
        <?php endforeach;?>
    </ul>
    
<?php endif;?>

<?php if (isset($data['nowFollowing'])): $something = true;?>
    
    <h3>You Are Now Following These Users</h3>
    
    <ul>
        <?php foreach ($data['nowFollowing'] as $user):?>
            
            <li><a href="/users/<?php echo $user;?>"><?php echo ucfirst($user);?></a></li>
            
        <?php endforeach;?>
    </ul>
    
<?php endif;?>

<?php if (isset($data['previousInvite'])): $something = true;?>
    
    <h3>These Users Have Been Invited Already:</h3>
    
    <p>We don't like to hassle them too often, so, if you think they are missing out, send them a reminder from your own email account.</p>
    
    <ul>
        <?php foreach($data['previousInvite'] as $email => $status):?>
            
            <li><?php if ($status == 'not now'): echo $email; else: $data['emailSent'][$email] = $status; endif;?></li>
            
        <?php endforeach;?>
    </ul>
    
<?php endif;?>

<?php if (isset($data['emailSent'])): $something = true;?>
    
    <h3>You Have Sent Invites to the Following Email Addresses</h3>
    
    <ul>
        <?php foreach ($data['emailSent'] as $email => $status):?>
            
            <li><?php if ($email['sent']) echo $email;?></li>
            
        <?php endforeach;?>
    </ul>
    
<?php endif;?>