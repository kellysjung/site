<?php
include('config/init.php');
session_start();

session_destroy();
// $_SESSION = array();

header("Location:/index.php");
exit;