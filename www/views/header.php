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
        .filter { background-color: lightgrey; 500px; }
        .filter-field { border: none; float: left; width: auto; }
        .history-0 { }
        .history-1 { background: #fffc90; }
        .history-3 { background: #c2ffad; }
        .history-field-1 {}
        .history-field-2 { margin-left: 100px;}
        .table-field-left { float: left; width: 20%; }
        .table-field-center { float: left; width: 60%; }
        .table-field-right { float: left; width: 20%; }
	</style>
</head>
<body>
    <a href="index.php">Home</a>
    <?php if (is_user_logged_in()): ?>
        <a href="index.php?page=tournaments">Tournaments</a>
	    <a href="index.php?page=matches">Matches</a>
	    <a href="index.php?page=standings">Standings</a>
	    <a href="index.php?page=history">History</a>
        <a href="index.php?page=profile">Profile (<?= $_SESSION['email'] ?>)</a>
        <a href="index.php?page=logout">Logout</a>
        <br />
        <a href="index.php?page=scores_update">Update scores</a>
    <?php else: ?>
        <a href="index.php?page=login">Login</a>
        <a href="index.php?page=register">Register</a>
        <a href="index.php?page=standings">Standings</a>
    <?php endif; ?>
