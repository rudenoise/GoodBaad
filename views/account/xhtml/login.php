<?php if (isset($data['msg'])):?>
 	<p><?php echo $data['msg'];?></p>
<?php endif;?>

<form id="AccountLoginForm" method="post" action="/account/login" class="hform">
    
    <fieldset>
        
        <legend>Log In</legend>
        
        <p>
            <label>Email:</label>
            <input name="data[Account][email]" type="text" maxlength="320" value="" id="AccountEmail" />
        </p>
        <p>
            <label>Password:</label>
            <input type="password" name="data[Account][password]" value="" id="AccountPassword" />
        </p>
        <p>
            <input type="submit" value="Go" />
        </p>
    </fieldset>
    
    
    
</form>

<p class="intro">Or, if you have an account with any of the sites below, login with one of these:</p>
<iframe src="https://goodbaad.rpxnow.com/openid/embed?token_url=<?php echo RPXURL;?>"
    scrolling="no" frameBorder="no" style="width:400px;height:240px;">
</iframe>

<p class="intro">Or, <a href="/signup" title="Sign up to Good Baad">sign up here</a>.</p>