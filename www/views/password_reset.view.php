
<p>PASSWORD RESET</p>

Reset password by sending a new random-generated password via e-mail:
<form action="" method="POST">
	<ul>
		<li><input type="hidden" name="form_name" value="password_reset"></li>
		<li><input type="text" name="email" placeholder="email" size="30"></li>
		<li><button type="submit">Sumbit</button></li>
	</ul>
</form>

<?php if (isset($status_message)) : ?>
    <p><?= $status_message ?></p>
<?php endif; ?>
