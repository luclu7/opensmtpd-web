<?php

// configure crash on error
ini_set('display_errors', 1);

// connect to the database
$pdo = require('includes/connect.php'); // object of type PDO
// require HTTP basic auth
require_once('includes/auth.php');
