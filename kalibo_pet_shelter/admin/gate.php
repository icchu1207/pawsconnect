<?php
session_start();

$_SESSION['admin_gate'] = true;

header("Location: login.php");
exit;
