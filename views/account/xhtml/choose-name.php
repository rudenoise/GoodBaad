<h2>This Username is in use</h2>

<p class="intro">Your prefered username <strong><?php echo $data['existinUserName']; ?></strong> is in use.</p>

<p>If this is your account please log-in using those details.</p>

<form id="UserNameForm" method="post" action="/account/newname" class="vform">
    
    <fieldset>
        
        <legend>Please enter another username:</legend>
        
        <?php if (isset($data['msg'])): ?><p class="intro"><em><?php echo $data['msg'];?></em></p><?php endif;?>
        
        <p>
            <label>Username:</label>
            <input name="data[Account][username]" type="text" maxlength="25" value="<?php if (isset($data['userArr']['username'])) echo $data['userArr']['username']; ?>" id="AccountAccountname" />
            <?php if (isset($data['validation']['username'])) if ($data['validation']['username'] != 1) echo '<em>'.$data['validation']['username'].'</em>'; ?>
            <?php  if (isset($data['validation']['uNameChck'])):?>Accountname in use<?php endif;?>
        </p>
        
        <p><input type="submit" name="submit" value="Go"></p>
    
    </fieldset>
    
</form>