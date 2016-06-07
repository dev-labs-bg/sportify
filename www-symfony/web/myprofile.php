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
 <div id="wrapper" class="wrapper toggled">
    <!-- Sidebar -->
    <nav class="navbar navbar-inverse navbar-fixed-top sidebar-wrapper" id="sidebar-wrapper" role="navigation">
        <ul class="nav sidebar-nav">
            <li class="sidebar-brand">
                <button class="hamburger is-open" data-toggle="offcanvas" ><?php include('img/menu.svg'); ?></button>
            </li>
            <li class="active my-profile">
                <a class="profile-picture" href="#">
                    <img src="img/devlabs_logo.png" alt="" />
                </a>
                <p class="user-name">Botzi Dimitrova</p>
            </li>
            <li>
                <a class="tournaments" href="#">Tournaments</a>
            </li>
            <li>
                <a class="matches" href="#">Matches</a>
            </li>
            <li>
                <a class="standings" href="#">Standings</a>
            </li>
            <li>
                <a class="history" href="#">History</a>
            </li>
            <li>
                <a class="rules" href="#">Rules</a>
            </li>
        </ul>
    </nav>
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper" class="page-content-wrapper">
        <nav id="navbar" class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="pull-left navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-menu" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <?php include('img/menu.svg'); ?>
              </button>
            </div>
            <h1 class="text-center">My Profile</h1>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse mobile-menu" id="mobile-menu">
              <ul class="nav navbar-nav">
                <li class="my-profile active">
                    <a href="#">My Profile</a>
                </li>
                <li>
                    <a class="tournaments" href="#">Tournaments</a>
                </li>
                <li>
                    <a class="matches" href="#">Matches</a>
                </li>
                <li>
                    <a class="standings" href="#">Standings</a>
                </li>
                <li>
                    <a class="history" href="#">History</a>
                </li>
                <li>
                    <a class="rules" href="#">Rules</a>
                </li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
        <div class="container-fluid content">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 text-center">
                    <form class="myprofile-form">
                        <label class="profile-picture btn-file">
                            <img src="img/devlabs_logo.png" alt="" />
                            <span>Add new profile picture</span>
                            <input type="file" style="display: none;">
                        </label>
                        <div class="form-group">
                            <input name="first-name" type="text" class="form-control" placeholder="First Name">
                        </div>
                        <div class="form-group">
                            <input name="last-name" type="text" class="form-control" placeholder="Last Name">
                        </div>
                        <div class="form-group">
                            <input name="new-password" type="password" class="form-control" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <input name="confirm-password" type="password" class="form-control" placeholder="Confirm New Password">
                        </div>
                        <button type="submit" class="btn btn-default green-btn">Submit changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="results-slider" class="results-slider">
        <div class="container-fluid">
            <div class="current-results-slider owl-carousel">
                <div class="slider-item-holder">
                    <div class="slider-item">
                        <div class="logo">
                            <img src="img/barclays-premier-league.png" alt="Barclays Premier League" />
                        </div>
                        <div class="result-info">
                            <div class="title">Barclays Premier League</div>
                            <div class="points">3p. <span class="position-up"></span></div>
                        </div>
                    </div>
                </div>
                <div class="slider-item-holder">
                <div class="slider-item">
                    <div class="logo">
                        <img src="img/la-liga-logo.png" alt="La Liga" />
                    </div>
                    <div class="result-info">
                        <div class="title">La Liga</div>
                        <div class="points">1p. <span class="position-down"></span></div>
                    </div>
                    </div>
                </div>
                <div class="slider-item-holder">
                <div class="slider-item">
                    <div class="logo">
                        <img src="img/UEFA-champions-league-logo.png" alt="UEFA Champions League" />
                    </div>
                    <div class="result-info">
                        <div class="title">UEFA Champions League</div>
                        <div class="points">3p. <span class="position-same"></span></div>
                    </div>
                    </div>
                </div>
                <div class="slider-item-holder">
                <div class="slider-item">
                    <div class="logo">
                        <img src="img/bundesliga.png" alt="bundesliga" />
                    </div>
                    <div class="result-info">
                        <div class="title">Bundesliga</div>
                        <div class="points">3p. <span class="position-up"></span></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- /#page-content-wrapper -->
<script src="../lib/jquery/dist/jquery.min.js"></script>
<script src="../lib/bootstrap-sass/assets/javascripts/bootstrap.min.js"></script>
<script src="../lib/owl.carousel/dist/owl.carousel.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>