<?php if (isset($data['msg'])): ?>

    <h3><?php echo $data['msg']; ?></h3>
    
<?php endif;?>

<form id="AccountSignupForm" method="post" action="/signup" class="vform">
    
    <fieldset>
        
        <legend>Sign up to Good Baad:</legend>
        
        <?php if (isset($data['msg'])): ?><p class="intro"><em><?php echo $data['msg'];?></em></p><?php endif;?>
        
        <p>
            <label>Email:</label>
            <input name="data[Account][email]" type="text" maxlength="300" value="<?php if (isset($data['userArr']['email'])) echo $data['userArr']['email']; ?>" id="AccountEmail" />
            <?php if (isset($data['validation']['email'])) if ($data['validation']['email'] != 1) echo '<em>Invalid email</em>'; ?>
            <?php  if (isset($data['validation']['emlChck'])):?>Email address in use<?php endif;?>
        </p>
        <p>
            <label>Username:</label>
            <input name="data[Account][username]" type="text" maxlength="25" value="<?php if (isset($data['userArr']['username'])) echo $data['userArr']['username']; ?>" id="AccountAccountname" />
            <?php if (isset($data['validation']['username'])) if ($data['validation']['username'] != 1) echo '<em>'.$data['validation']['username'].'</em>'; ?>
            <?php  if (isset($data['validation']['uNameChck'])):?>Accountname in use<?php endif;?>
        </p>
        <p>
            <label>Password:</label>
            <input type="password" name="data[Account][password]" value="" id="AccountPassword" />
            <?php if (isset($data['validation']['password'])) if ($data['validation']['password'] != 1) echo '<em>'.$data['validation']['password'].'</em>'; ?>
        </p>
        <p>
            <label>Repeat Password:</label>
            <input type="password" name="data[Account][password2]" value="" id="AccountPassword2" />
            <?php if (isset($data['validation']['password2'])) if ($data['validation']['password2'] != 1) echo "<em>Passwords don't match.</em>"; ?>
        </p>
        <p class="clear">
            <label>Please read and agree to our <a href="/terms-and-conditions" target="_blank">terms and conditions</a>:</label>
            <input type="checkbox" name="data[Account][terms]" id="AccountTerms" <?php if (isset($data['validation'])) if ($data['validation']['terms']):?> checked<?php endif;?>>
            <?php if (isset($data['validation'])) if (!$data['validation']['terms']) echo "<em>You need to agree to the terms and condidions.</em>"; ?>
        </p>
        <p><input type="submit" name="submit" value="Go"></p>
    
    </fieldset>
    
</form>

<p class="intro">Or, if you have an account with any of the sites below, you can login with one of these:</p>

<iframe src="https://goodbaad.rpxnow.com/openid/embed?token_url=<?php echo RPXURL;?>"
    scrolling="no" frameBorder="no" style="width:400px;height:240px;">
</iframe>