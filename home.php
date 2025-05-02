<?php
session_start();
define('searchbar', TRUE);
define('adminfeat', TRUE);

// makes sure that if user does something like adding /home, they cannot enter without being signed in
if (!isset($_SESSION['USER-EMAIL'])) {
    // sends them back to the login page
    header('Location: login.php');
    exit();
}

// User is logged in
echo "Welcome, " . $_SESSION['USER-EMAIL'] . "!";

echo "<br><br>";

//Include admin features
include 'admin.php';
//Include Search enginge Features
include 'search.php';

// if user wants to sign out then this will take them to the logout page and sign them out
echo "<a href='logout.php'>Logout</a>";
?>
