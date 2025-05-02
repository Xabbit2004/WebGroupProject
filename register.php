<?php
    session_start();

    // db stuff
     $servername = "localhost";
     $username = "root";
     $password = "";
     $database = "Library";
    
     // Create connection
     $conn = new mysqli($servername, $username, $password, $database);
    
     // Check connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }


    echo "<h1> REGISTER </h1>";
    
    // Display the Register Form: Asks for (Name?), Email, and Password.
    echo "<form action ='register.php' method='POST'>";
    // echo "Name <input type='text' name='name' required> <br>";
    echo "Email <input type='email' name='email' required> <br>";
    echo "Password <input type='password' name='password' minlength='8' required>";
    // This then submits 'register' to $_POST[] superglobal array, which we can then access to process data
    echo "<input type='submit' name= 'register'>";
    echo "</form>";

     // if the form has been submitted, we process information the user submitted via the form.
     if(isset($_POST['register'])){

        // SQL code to search DB for email used to register
        $sql = "SELECT 1 FROM users WHERE email = '" . $_POST['email'] . "' LIMIT 1";
        //query result
        $result = mysqli_query($conn, $sql);
        // Checks to see the occurences of that email in the database, since user is registering it should be 0, else they are trying to register with an email already in use
        $searchResult = mysqli_num_rows($result);

        // If the user has already registered before --> send them to login page or just output msg saying email is already registered.
        if ($searchResult > 0){
            echo " Email is already Registered, try logging in. ";
            echo "<br>";
            // header('Location: login.php');
            // exit();
        }
        else{
            // User is registering with a fresh email
            
            // Get name and email used to register account
            $name = $_POST['name'];
            $email = $_POST['email'];

            // We do not want to store plain passwords so we hash it
            $hashpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            // THEN we store that hashed password into the database.
            $sql = "INSERT INTO users (name, email, hashed_password) VALUES ('$name', '$email', '$hashpass')";

            // result of insertion query (we can add if statements for when it fails)
            $result = mysqli_query($conn, $sql);

            // start a session with email user passed in
            $_SESSION['USER-EMAIL'] = $email;


            // header() sends user to location --> home.php (since as this point they are done with registration process) 
            header('Location: home.php');

            //stops php from running
            exit();
        }
    }
    echo "<br>";
    // this is added at the end so it wont intefer with the messages outputted, takes them to login page
    echo "<a href='login.php'> Have an account? Log in </a>";
?>