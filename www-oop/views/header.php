<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Sportify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo $GLOBALS['base_config']['img_path'] ?>favicon.png">

    <!-- Main CSS -->
    <link href="<?php echo $GLOBALS['base_config']['css_path'] ?>main.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $GLOBALS['base_config']['css_path'] ?>bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo $GLOBALS['base_config']['css_path'] ?>dashboard.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo get_home_url() ?>">Sportify</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php">Home</a></li>
                    <?php if (\devlabs\app\userAuth::loginStatus()): ?>
                        <li class="visible-xs"><a href="index.php?page=tournaments">Tournaments</a></li>
                        <li class="visible-xs"><a href="index.php?page=matches">Matches</a></li>
                        <li class="visible-xs"><a href="index.php?page=history">History</a></li>
                        <li><a href="index.php?page=profile"><?= $_SESSION['email'] ?></a></li>
                        <li><a href="index.php?page=logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=login">Login</a></li>
                        <li><a href="index.php?page=register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <?php if (\devlabs\app\userAuth::loginStatus()): ?>
                        <li><a href="index.php?page=tournaments">Tournaments</a></li>
                        <li><a href="index.php?page=matches">Matches</a></li>
                        <li><a href="index.php?page=history">History</a></li>
                    <?php endif; ?>
                    <li><a href="index.php?page=standings">Standings</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

