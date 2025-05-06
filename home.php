<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin's Page</title>
    <link rel="stylesheet" type="text/css" href="styling.css">
</head>
<body>
    <div class="header">
        <h1> Library of Babble <h1>
    </div>


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
echo "<div class='topnav'>";
echo "<a id='still'>" . $_SESSION['USER-EMAIL'] . "<a>";
echo "<a id='motion' href='logout.php'>Logout</a>";
echo "</div>"
?>

<div class="row">
<div class="column left">
<!-- Include admin features -->
<?php include 'admin.php';?>
</div> 
<div class="column right">
<!-- Include Search features -->
<?php include 'search.php';?>
</div>
</div>


</body>
</html>


