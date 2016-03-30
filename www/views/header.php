<!DOCTYPE HTML>
<html lang="en">
<head>
	<title></title>
	<style>
		ul, li { margin: 0; padding: 0; }
		li { list-style: none; }
        div { border: 1px solid; width: 500px; }
        button { background-color: greenyellow; }
        .msg-error { font-size: 9pt; color: red; }
        .filter { border: none; }
	</style>
</head>
<body>
    <a href="index.php">Home</a>
    <?php if (is_user_logged_in()): ?>
        <a href="index.php?page=tournaments">Tournaments</a>
	    <a href="index.php?page=matches">Matches</a>
	    <a href="index.php?page=standings">Standings</a>
	    <a href="index.php?page=history">History</a>
        <a href="index.php?page=logout">Logout</a>
        <?= $_SESSION['email'] ?>
    <?php else: ?>
        <a href="index.php?page=login">Login</a>
        <a href="index.php?page=register">Register</a>
        <a href="index.php?page=standings">Standings</a>
    <?php endif; ?>
