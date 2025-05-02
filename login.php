<?php
session_start();

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "Library";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1>LOG IN</h1>";

// Display the login Form: Asks for Email and Password.
echo "<form action='login.php' method='POST'>";
echo "Email: <input type='email' name='email' required> <br>";
echo "Password: <input type='password' name='password' required> <br>";
// This then submits 'login' to $_POST[] superglobal array, which we can then access to process data
echo "<input type='submit' name='login' value='Log In'>";
echo "</form>";




// if the form has been submitted, we process information the user submitted via the form.
if (isset($_POST['login'])) {

    // get the email user passed in (password not saved yet, until we verify they have an account)
    $email = $_POST['email'];

    // SQL code to search for users email in the DB
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    //result of query search
    $result = mysqli_query($conn, $sql);

    // If there was no email found, send them to the register page or display msg saying no email was found.
    if (mysqli_num_rows($result) === 0) {
        // header('Location: register.php');
        // exit();
        echo " No account associated with that email, try again ";
        echo "<br>";
    } else {

        // Email was found so we will now check to see if the password they entered is correct
        $row = mysqli_fetch_assoc($result);
        $user_password = $_POST['password'];
        
        // compare between hashed user input and hashed password
        if (password_verify($user_password, $row['hashed_password'])) {
            // if true, start session with that email they entered
            $_SESSION['USER-EMAIL'] = $row['email'];

            // header() sends user to location --> home.php (replace with actual homepage)
            header('Location: home.php');
            exit();
        } else {
            // they entered the wrong password
            echo "<p>Incorrect password. Please try again.</p>";
            exit();
        }
    }
}


// placed at the bottom to not interfer with ouputted msg's, takes them to register page
echo "<a href='register.php'> Don't have an account? Register</a>";
?>