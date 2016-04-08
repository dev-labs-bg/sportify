<h1 class="page-header">PASSWORD RESET</h1>

<?php if (isset($status_message)) : ?>
	<p class="alert alert-info" role="alert"><?= $status_message ?></p>
<?php endif; ?>

<p><em>Reset password by sending a new random-generated password via e-mail.</em></p>
<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Password reset</div>
	<div class="panel-body">
		<form action="" method="POST">
			<input type="hidden" name="form_name" value="password_reset">
			<div class="row">
				<div class="form-group col-sm-12">
					<label>Email</label>
					<input type="text" name="email" class="form-control" placeholder="email">
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

