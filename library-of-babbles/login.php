<?php

session_start();
require_once 'config.php';


if(isset($_POST['login'])){
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE EMAIL = '$email'");
    if($result->num_rows === 1){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['PASSWORD'])){
            $_SESSION['name'] = $user['USERNAME'];
            $_SESSION['email'] = $user['EMAIL'];

            if($user['ROLE'] === 'admin'){
                header("Location: Admin/admin_page.php");
            } else {
                header("Location: User/user_page.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password!';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

?>