<!DOCTYPE HTML>
<html lang="en">
<head>
	<title></title>
	<style>
		ul, li { margin: 0; padding: 0; }
		li { list-style: none; }
	</style>
</head>
<body>

    <a href="index.php">Home</a>
    <?php if (is_user_logged_in()) : ?>
        <a href="index.php?page=tournaments">Tournaments</a>
	    <a href="index.php?page=matches">Matches</a>
	    <a href="index.php?page=standings">Standings</a>
	    <a href="index.php?page=history">History</a>
    <?php endif; ?>
