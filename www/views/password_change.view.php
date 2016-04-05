
<p>PASSWORD CHANGE</p>

Please fill in your new password:
<form action="" method="POST">
	<ul>
		<li><input type="hidden" name="form_name" value="password_change"></li>
        <li><input type="hidden" name="email" value="<?= $data['email'] ?>"></li>
		<li><input type="password" name="password" placeholder="password"></li>
		<li><input type="password" name="password_confirm" placeholder="confirm password"></li>
		<li><button type="submit">Submit</button></li>
	</ul>
</form>

<?php if (isset($password_reset_status)) : ?>
    <p><?= $password_reset_status ?></p>
<?php endif; ?>
