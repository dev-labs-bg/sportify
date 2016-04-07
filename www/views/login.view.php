<h1 class="page-header">LOGIN</h1>

<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Login</div>
	<div class="panel-body">
		<form action="" method="POST">
			<input type="hidden" name="form_name" value="login">
			<div class="row">
				<div class="form-group col-sm-12">
					<label>Email</label>
					<input type="text" name="email" class="form-control" placeholder="email">
				</div>
				<div class="form-group col-sm-12">
					<label>Password</label>
					<br />
					<input type="password" name="password" class="form-control" placeholder="password">
				</div>

			</div>
			<div class="row">
				<div class="col-xs-12">
					<button type="submit" class="btn btn-primary center-block">Login</button>
					<br />
					<p class="text-center"><a href="index.php?page=password_reset">Forgotten password?</a> or <a href="index.php?page=register">Create an account</a></p>
				</div>
			</div>
		</form>
	</div>
</div>
