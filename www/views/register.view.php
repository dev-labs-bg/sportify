<p>REGISTER</p>

<form action="" method="POST">
	<ul>
        <li><input type="hidden" name="form_name" value="register"></li>
        <li><input type="text" name="email" placeholder="email" value="<?= form_prev_value('email'); ?>"></li>
		<li><input type="text" name="first_name" placeholder="First Name" value="<?= form_prev_value('first_name'); ?>"></li>
		<li><input type="text" name="last_name" placeholder="Last Name" value="<?= form_prev_value('last_name'); ?>"></li>
		<li><input type="password" name="password" placeholder="password"></li>
		<li><input type="password" name="password_confirm" placeholder="confirm password"></li>
		<li><button type="submit">Register</button></li>
	</ul>
</form>

<?php if (isset($reg_status)) : ?>
<p><?= $reg_status ?></p>
<?php endif; ?>
