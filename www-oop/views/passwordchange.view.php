<h1 class="page-header">PASSWORD CHANGE</h1>

<?php if (isset($status_message)) : ?>
	<p class="alert alert-info" role="alert"><?= $status_message; ?></p>
<?php endif; ?>


<p><em>Please fill in your new password.</em></p>
<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Password change</div>
	<div class="panel-body">
		<form action="" method="POST">
			<input type="hidden" name="form_name" value="password_change">
			<input type="hidden" name="email" value="<?= $user->email; ?>">
			<input type="hidden" name="token_purpose" value="<?= $token->purpose; ?>">
			<input type="hidden" name="token_value" value="<?= $token->value; ?>">

			<div class="row">
				<div class="form-group col-sm-12">
					<label>Password</label>
					<input type="password" name="password" class="form-control" placeholder="password">
				</div>
				<div class="form-group col-sm-12">
					<label>Password confirm</label>
					<input type="password" name="password_confirm" class="form-control" placeholder="password confirm">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<button type="submit" class="btn btn-primary center-block">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
