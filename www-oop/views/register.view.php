<h1 class="page-header">REGISTER</h1>

<?php if (isset($status_message)) : ?>
	<p class="alert alert-info" role="alert"><?= $status_message ?></p>
<?php endif; ?>

<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Register</div>
	<div class="panel-body">
		<form action="" method="POST">
			<input type="hidden" name="form_name" value="register">
			<div class="row">
				<div class="form-group col-sm-12">
					<label>Email</label>
					<input type="text" name="email" class="form-control" placeholder="email" value="<?= form_prev_value('email'); ?>" >
				</div>
				<div class="form-group col-sm-12">
					<label>First name</label>
					<br />
					<input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?= form_prev_value('first_name'); ?>">
				</div>
				<div class="form-group col-sm-12">
					<label>Last name</label>
					<br />
					<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?= form_prev_value('last_name'); ?>">
				</div>
				<div class="form-group col-sm-12">
					<label>Password</label>
					<br />
					<input type="password" class="form-control" name="password" placeholder="password">
				</div>
				<div class="form-group col-sm-12">
					<label>Password</label>
					<br />
					<input type="password" class="form-control" name="password_confirm" placeholder="confirm password">
				</div>

			</div>
			<div class="row">
				<div class="col-xs-12">
					<button type="submit" class="btn btn-primary center-block">Register</button>
				</div>
			</div>
		</form>
	</div>
</div>
