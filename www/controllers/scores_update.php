<?php

$data = array();

$data = get_not_scored_predictions();
update_points($data);

header("Location: index.php");
