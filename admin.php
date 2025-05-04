<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styling.css">
</head>
<body>
    <?php
//  Protection from users
    if(!defined('adminfeat')){
        session_start();
        if (!isset($_SESSION['USER-EMAIL'])) {
        // sends them back to the login page
        header('Location: login.php');
        exit();
        }
        else{
        // sends them back to the home page
            header('Location: home.php');
            exit();
        }
    }

// ------------- Connect to database --------------
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "Library";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
<!-- ------------------------------------------------ -->

    <!-- Displays the form for Adding book -->
    <div class="blocks left">       
        <h2> Add book to the database </h2>
        <form method="post">
            <label> Title: </label>
            <input type="text" name="title" required> <br>
            <label> Author: </label>
            <input type="text" name="author" required> <br>
            <label> ISBN #: </label>
            <input type="text" name="isbn" placeholder="xxx-x-xxx-xxxxx-x" required> <br>
            <label> Publication Date: </label>
            <input type="date" name="date" required> <br>
            <label> Genre: </label>
            <input type="text" name="genre" required> <br>
            <button type="submit" name="add_book"> Confirm Add </button>
        </form>
    </div>


<?php
    if (isset($_POST['add_book'])) {
        $ISBN = htmlspecialchars($_POST['isbn']);
        $TITLE = htmlspecialchars($_POST['title']);
        $AUTHOR = htmlspecialchars($_POST['author']);
        $PUBDATE = htmlspecialchars($_POST['date']);
        $GENRE = htmlspecialchars($_POST['genre']);

        $sql = "INSERT INTO BOOKS (ISBN, TITLE, AUTHOR, PUBDATE, GENRE, STATUS)
                VALUES('$ISBN', '$TITLE', '$AUTHOR', '$PUBDATE', '$GENRE', 'Available')";
        //Confirm if it was successful
              if (mysqli_query($conn, $sql)) {echo "New record created successfully";} 
              else { echo "Error: " . $sql . "<br>" . mysqli_error($conn); }
    }
    ?>
<!-- ------------------------------------------------ -->

    <!-- Displays the form for Delete book -->
    <div class="blocks left">     
        <h2> Delete book from the database </h2>
        <form method="post">
            <label> ISBN #: </label>
            <input type="text" name="isbn" placeholder="xxx-x-xxx-xxxxx-x" required> <br>
            <button type="submit" name="delete_book"> Delete </button>
        </form>
    </div>

<?php
    if (isset($_POST['delete_book'])) {
    // Gather input
        $ISBN = htmlspecialchars($_POST['isbn']);
    //Check if Student exist
        $check = "SELECT * FROM BOOKS WHERE ISBN = '$ISBN'";
        $result = mysqli_query($conn, $check);
        if ($result->num_rows > 0) {
    //Delete the student
             $sql = "DELETE FROM BOOKS WHERE ISBN = '$ISBN'"; 
             mysqli_query($conn, $sql);
             echo "Book with ISBN " . $ISBN . " deleted successfully";
              } 
              else { echo "Book with ISBN " . $ISBN . " not found"; }
    }
    ?>
<!-- ------------------------------------------------ -->

    <!-- Displays the form for Delete book -->
    <div class="blocks left">     
        <h2> Update Book Availability </h2>
        <form method="post">
            <label> Book(Title or ISBN): </label>
            <input type="text" name="search" required> <br>
            <label> User Email: </label>
            <input type="text" name="user" required> <br>
            <button  name="inButton"> Check IN </button>
            <button  name="outButton"> Check OUT </button>
        </form>
    </div>

<?php
    if (isset($_POST['outButton'])) {
    // Gather input
        $Search = htmlspecialchars($_POST['search']);
        $Email = htmlspecialchars($_POST['user']);

        //Check out Date
        $CurDate = date("Y-m-d");
        //Date of return
        $ExpDate = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+30, date("Y")));
        echo $ExpDate;

    //Check if is book is available
        $check = "SELECT id FROM BOOKS WHERE ISBN = '$Search' OR TITLE = '$Search' AND
                                                    STATUS = 'Available' 
                                                    LIMIT 1";
        $result = mysqli_query($conn, $check);
        if ($result->num_rows > 0) {
            $pos = mysqli_fetch_assoc($result);
            $position1 = $pos["id"];
    //Delete the student
             $sql = "UPDATE BOOKS SET EMAIL = '$Email', CHECKDATE = '$CurDate', EXPDATE = '$ExpDate', STATUS = 'Unavailable' 
                    WHERE id = '$position1' "; 
             mysqli_query($conn, $sql);
             $alert1 = $Search . " has been successfully checked out. Return by: " . $ExpDate;
             echo "<script type='text/javascript'>alert('{$alert1}');</script>";
              } 
        else { 
            echo "<script type='text/javascript'>alert('Book either not found or its already unavailable');</script>"; 
        }
    }
    if (isset($_POST['inButton'])) {
    // Gather input
        $Search = htmlspecialchars($_POST['search']);
        $Email = htmlspecialchars($_POST['user']);
    //Check if is book is taken
        $check = "SELECT id FROM BOOKS WHERE ISBN = '$Search' OR TITLE = '$Search' AND
                                                    EMAIL = '$Email' 
                                                    LIMIT 1";
        $result = mysqli_query($conn, $check);
        if ($result->num_rows > 0) {
            $pos = mysqli_fetch_assoc($result);
            $position1 = $pos["id"];
    //Delete the student
             $sql = "UPDATE BOOKS SET EMAIL = NULL, CHECKDATE = NULL, EXPDATE = NULL, STATUS = 'Available' 
                    WHERE id = '$position1' "; 
             mysqli_query($conn, $sql);
             $alert1 = $Search . " has been successfully checked in";
             echo "<script type='text/javascript'>alert('{$alert1}');</script>";
              } 
        else { echo "<script type='text/javascript'>alert('Book either not found or its already available');</script>"; }
    }
?>
</body>
</html>