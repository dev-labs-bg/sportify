<h2>USER PROFILE</h2>

<p> - Picture - </p>

<form action="" method="POST">
    <ul>
        <li><input type="hidden" name="form_name" value="profile_change"></li>
        <li><input type="text" name="first_name" placeholder="First Name" value="<?= $data['userdata']['first_name']; ?>"></li>
        <li><input type="text" name="last_name" placeholder="Last Name" value="<?= $data['userdata']['last_name']; ?>"></li>
        Password change:
        <li><input type="password" name="password" placeholder="password"></li>
        <li><input type="password" name="password_confirm" placeholder="confirm password"></li>
        <li><button type="submit">Submit changes</button></li>
    </ul>
</form>

<?php if (isset($status_message)) : ?>
    <p><?= $status_message ?></p>
<?php endif; ?>