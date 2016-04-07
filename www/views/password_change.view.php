<h1 class="page-header">PASSWORD CHANGE</h1>

Please fill in your new password:
<form action="" method="POST">
	<ul>
		<li><input type="hidden" name="form_name" value="password_change"></li>
        <li><input type="hidden" name="email" value="<?= $userdata['email'] ?>"></li>
        <li><input type="hidden" name="token_purpose" value="<?= $userdata['token_purpose'] ?>"></li>
		<li><input type="password" name="password" placeholder="password"></li>
		<li><input type="password" name="password_confirm" placeholder="confirm password"></li>
		<li><button type="submit">Submit</button></li>
	</ul>
</form>

<?php if (isset($status_message)) : ?>
    <p><?= $status_message ?></p>
<?php endif; ?>