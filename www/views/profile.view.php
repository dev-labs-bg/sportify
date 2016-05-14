<h1 class="page-header">PROFILE</h1>

<?php if (isset($status_message)) : ?>
    <p class="alert alert-info" role="alert"><?= $status_message; ?></p>
<?php endif; ?>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Profile</div>
    <div class="panel-body">
        <form action="" method="POST">
            <input type="hidden" name="form_name" value="profile_change">
            <div class="row">
                <div class="form-group col-sm-12">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?= $user->firstName; ?>">
                </div>
                <div class="form-group col-sm-12">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?= $user->lastName; ?>">
                </div>
                <div class="form-group col-sm-12">
                    <label>Change Password</label>
                    <br />
                    <input type="password" name="password" class="form-control" placeholder="password">
                </div>
                <div class="form-group col-sm-12">
                    <label>Confirm Password</label>
                    <br />
                    <input type="password" name="password_confirm" class="form-control" placeholder="confirm password">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary center-block">Submit changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
