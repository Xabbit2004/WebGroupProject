<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <form method="post">
        <button name="add_book_form"> Add a book</button>
        <button name="delete_book_form"> Delete a book</button>
        <button name="update_book_form"> Update a book record</button>
    </form>
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
// ------------------------------------------------


    if (isset($_POST['add_book_form'])) {
    //Displays the form for Adding book
        echo "<h2> Add book to the database </h2>";
        echo '<form method="post">';
        echo "<label> Title: </label>";
        echo '<input type="text" name="title" required> <br>';
        echo "<label> Author: </label>";
        echo '<input type="text" name="author" required> <br>';
        echo "<label> ISBN #: </label>";
        echo '<input type="text" name="isbn" placeholder="xxx-x-xxx-xxxxx-x" required> <br>';
        echo "<label> Publication Date: </label>";
        echo '<input type="date" name="date" required> <br>';
        echo "<label> Genre: </label>";
        echo '<input type="text" name="genre" required> <br>';

        echo '<button type="submit" name="add_book"> Confirm Add </button>';
        echo '</form>';
    }


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

    if (isset($_POST['delete_book_form'])) {
    //Displays the form for Delete book
        echo "<h2> Delete book from the database </h2>";
        echo '<form method="post">';
        echo "<label> ISBN #: </label>";
        echo '<input type="text" name="isbn" placeholder="xxx-x-xxx-xxxxx-x" required> <br>';

        echo '<button type="submit" name="delete_book"> Delete </button>';
        echo '</form>';
    }


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
    if (isset($_POST['update_book_form'])) {
    //Displays the form for Delete book
        echo "<h2> Update Book Availability </h2>";
        echo '<form method="post">';
        echo "<label> Book(Title or ISBN): </label>";
        echo '<input type="text" name="search" required> <br>';
        echo "<label> User Email: </label>";
        echo '<input type="text" name="user" required> <br>';
        echo '<button  name="inButton"> Check IN </button>';
        echo '<button  name="outButton"> Check OUT </button>';
        echo '</form>';
    }


    if (isset($_POST['inButton'])) {
    // Gather input
        $Search = htmlspecialchars($_POST['search']);
        $Email = htmlspecialchars($_POST['user']);
        $CurDate = date("Y-m-d");
        echo "$CurDate";
    //Check if Student exist
        $check = "SELECT id FROM BOOKS WHERE ISBN = '$Search' OR TITLE = '$Search' AND
                                                    STATUS = 'Available' 
                                                    LIMIT 1";
        $result = mysqli_query($conn, $check);
        if ($result->num_rows > 0) {
            $pos = mysqli_fetch_assoc($result);
            $position1 = $pos["id"];
    //Delete the student
             $sql = "UPDATE BOOKS SET EMAIL = '$Email', CHECKDATE = '$CurDate', STATUS = 'Unavailable' 
                    WHERE id = '$position1' "; 
             mysqli_query($conn, $sql);
             echo "Book with ISBN deleted successfully";
              } 
              else { echo "Book with ISBN not found"; }
    }
    if (isset($_POST['outButton'])) {
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
</body>
</html>