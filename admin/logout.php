<?php
require_once '../backend/config/database.php';
require_once '../backend/functions/admin.php';

// Logout the admin
$adminManager = new AdminManager();
$adminManager->logout();

// Redirect to login page
header('Location: index.php');
exit();
?>