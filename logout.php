<?php
session_start();
require_once 'auth/AuthFacade.php';

$auth = new AuthService();
$auth->logout();

header("Location: login.php");
exit;
