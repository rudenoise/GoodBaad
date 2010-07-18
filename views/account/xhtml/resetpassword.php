<?php if (isset($data['msg'])): ?>

    <h3><?php echo $data['msg']; ?></h3>
    
<?php endif;?>

<form id="ChangePassForm" method="post" action="/account/resetpassword" class="hform">
    <fieldset>
        <legend>Change Password:</legend>
        <p>
            <label>Current</label>
            <input type="password" name="data[current]" value="" id="CurrentPassword" />
        </p>
        <p>
            <label>New</label>
            <input type="password" name="data[newPassword1]" value="" id="NewPassword1" />
        </p>
        <p>
            <label>Repeat New</label>
            <input type="password" name="data[newPassword2]" value="" id="NewPassword2" />
        </p>
        <p><input type="submit" name="submit" value="Go"></p>
    </fieldset>
</form>