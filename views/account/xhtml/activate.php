<?php if (isset($data['active'])):?>

    <h2>Your account is now active, <a href="/login" title="login">login</a> and enjoy the site.</h2>
    
<?php else:?>
    <h2><?php echo $data['msg'];?></h2>
<?php endif;?>