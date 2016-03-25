<?php

login_unset();
session_destroy();
header("Location: index.php");
