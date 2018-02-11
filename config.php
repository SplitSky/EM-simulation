<?php
session_start();
define('dbServer', 'localhost');
define('dbDatabase', 'em-simulation');
define('dbUser', 'login_user');
define('dbPass', '1234');
define('userfile', 'user.php');
define('loginfile', 'login.php');
define('secretkey', 'Wombat');
define('welcomePage','simulation.php');
define('initialPage','index.php');
// FOR DEVELOPMENT ONLY
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
