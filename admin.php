<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <form method="post">
        <button name="add_book_form"> Add a book</button>
        <button name="delete_book_form"> Delete a book</button>
        <button name="update_book"> Update a book record</button>
    </form>
    <?php
    //Connect to database
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

        $sql = "INSERT INTO BOOKS (ISBN, TITLE, AUTHOR, PUBDATE, GENRE, AVAILABILITY)
                VALUES('$ISBN', '$TITLE', '$AUTHOR', '$PUBDATE', '$GENRE', 'TRUE')";
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

    //Gather Columns name for Table Header
    $sql2 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA ='Library' AND TABLE_NAME = 'BOOKS'"; 
    $columns = mysqli_query($conn,$sql2);
    
    $sql1 = "SELECT * FROM BOOKS";
    $result = mysqli_query($conn, $sql1);

    //Call function to display table
    displayTable($result, $columns);

//Function: To display the result of the filtered database in a table format
function displayTable($result, $columns) {
       if ($result->num_rows > 0) {
              echo "<br>";
       //Use table to display the database table
              echo '<div class="center"><table>'; // table start
       // table heading
              echo "<thead><tr>";
              while($row = mysqli_fetch_assoc($columns)){
                     foreach($row as $value)
                     echo "<th>" . $value . "</th>";
              }
              echo "<tr></thead>";

       // table rows
              while ($row = mysqli_fetch_assoc($result)) { 
                     echo "<tbody><tr>";
                     foreach ($row as $value) { // get the value for each row
                            echo "<td>" . $value . "</td>"; 
                     }
                     echo "</tr></tbody>";
              }
              echo "</table></div>"; // table end
       }
       else { echo "<p>No records found.</p>";}
}
?>
</body>
</html>