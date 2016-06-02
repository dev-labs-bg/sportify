<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>Sportify</title>
    <link href='https://fonts.googleapis.com/css?family=Exo:400,600,700,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header>
    <nav class="navbar navbar-default">
        <h1 class="text-center sportify">Sportify</h1>
        </nav>
    </header>
    <div class="container content login-screen">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <h2>Log in to place your bets</h2>
                <form class="login-form">
                  <div class="form-group">
                    <input name="email" type="email" class="form-control" placeholder="Email address">
                  </div>
                  <div class="form-group">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                  </div>
                  <p class="forgotten-password"><a href="#">Forgotten password?</a></p>
                  <button type="submit" class="btn btn-default green-btn">Login</button>
                </form>
                <h2>Don't have an account?<br/>
                <a href="signup.php">Sign up here</a>
                </h2>
            </div>
        </div>
    </div>
<script src="../lib/jquery/dist/jquery.min.js"></script>
<script src="../lib/bootstrap-sass/assets/javascripts/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>