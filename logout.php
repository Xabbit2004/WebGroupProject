<?php
session_start();

// end the session for the user associated with current email.
unset($_SESSION['USER-EMAIL']);

// clear all session variables (extra)
session_unset();


// 4) Finally destroy the session
session_destroy();

// 5) Redirect back to your login page
header('Location: login.php');
exit();
?>
