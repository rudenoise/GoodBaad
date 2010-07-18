<h2>Your Account Details</h2>

<ul class="links">
    <li><a href="/account/invite/" title="Invite Users to Good Baad">Invite Users to Good Baad</a></li>
    
    <?php if (!$_SESSION['userActive']):?>
    
        <li><a href="/account/resendactivation/" title="Resend Activation">Resend Your Activation Activation</a></li>
        
    <?php endif;?>
    
    <li><a href="/account/resetpassword/" title="Reset Password">Reset Password</a></li>
</ul>

<?php if (isset($data['msg'])):?>
    <p><strong><?php echo $data['msg'];?></strong></p>
<?php endif;?>

<form id="AccountDetail" method="post" action="/account" class="hform">
    <fieldset>
        <legend>Add/Alter Your Details Below:</legend>
        <p>
            <label>Gender:</label>
            <select name="data[Account][gender]">
                <option value="f" <?php if ($data['account']['gender'] == 'f') echo 'selected';?>>Female</option>
                <option value="m" <?php if ($data['account']['gender'] == 'm') echo 'selected';?>>Male</option>
            </select>
        </p>
        <p>
            <label>Year Of Birth:</label>
            <select name="data[Account][yob]">
                <?php $i = 1900; while ($i < 2000):?>
                    <option value="<?php echo $i;?>" <?php if ($data['account']['yob'] == $i) echo 'selected'; elseif (($data['account']['yob'] == null)&&($i == 1970)) echo 'selected';?>><?php echo $i; $i++;?></option>
                <?php endwhile;?>
            </select>
        </p>
        <p><input type="submit" value="Go" /></p>
    </fieldset>
</form>