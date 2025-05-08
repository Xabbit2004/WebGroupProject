<?php

$errorList = [];

session_start();
require_once 'config.php';

if(isset($_POST['register'])){
    $name = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $role = $_POST['role'];

    // Server-side password validation conditions
    $conditions = [
        'letter' => [
            'pattern' => '/[A-Za-z]/',
            'message' => 'Password must contain at least one letter.'
        ],
        'number' => [
            'pattern' => '/[0-9]/',
            'message' => 'Password must contain at least one number.'
        ],
        'special' => [
            'pattern' => '/[\W_]/',
            'message' => 'Password must contain at least one special character.'
        ]
    ];

    $valid = true;
    foreach ($conditions as $key => $rule) {
        if (!preg_match($rule['pattern'], $password)) {
            $errorList[$key] = false;
            $valid = false;
        } else {
            $errorList[$key] = true;
        }
    }

    if ($valid) {
        // Hash and store password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Check for no repeat of email
        $checkEmail = $conn->query("SELECT EMAIL FROM USERS WHERE EMAIL = '$email'");
        if($checkEmail->num_rows > 0){
            $_SESSION['register_error'] = 'Email is already registered!';
            $_SESSION['active_form'] = 'register';
        } else {
            $conn->query("INSERT INTO USERS (USERNAME,EMAIL,PASSWORD,ROLE) 
            VALUES ('$name', '$email','$hashed','$role')");
        }
    } else {
        $password_errors = "";
        ($errorList['letter'] && $errorList['letter']) ? $password_errors .= '✅' : $password_errors .= '❌';
        $password_errors .= 'Password must contain at least one letter.<br>';
        ($errorList['number'] && $errorList['number']) ? $password_errors .='✅' : $password_errors .= '❌';
        $password_errors .= 'Password must contain at least one number.<br>';
        ($errorList['special'] && $errorList['special']) ? $password_errors .='✅' : $password_errors .='❌';
        $password_errors .= 'Password must contain at least one special character.<br>';
        $_SESSION['register_password_error'] = $password_errors;
        $_SESSION['active_form'] = 'register';
    }



    header("Location: index.php");
    exit();
}

?>