<?php

session_start();
// Check if user is already loged in
if(!isset($_SESSION['email'])){
    header("location: ../index.php");
    exit();
}

require_once '../config.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="admin_style.css">
    <script type="text/javascript" src="../darkmode.js" defer></script>
</head>
<body>
    <button id="theme-switch"> 
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z"/></svg>
    </button>
    <div class="topnav">
        <a id='still'><?= $_SESSION['name']?></a>
        <a id='motionRight' href='../logout.php'>Logout</a>
    </div>

    <div class="header">
        <h1>Welcome, <span><?= $_SESSION['name']; ?></span>
        <p>This the <span>admin</span> page </p>
    </div>

    <div class="form-container">
        <div class="form-box">
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
                <button type="submit" name="add_book"> Confirm Add </button>
            </form>
        </div>
            <!-- Displays the form for Delete book -->
    <div class="form-box">     
        <h2> Delete book from the database </h2>
        <form method="post">
            <label> ISBN #: </label>
            <input type="text" name="isbn" placeholder="xxx-x-xxx-xxxxx-x" required> <br>
            <button type="submit" name="delete_book"> Delete </button>
        </form>
    </div>
    <!-- Displays the form for Delete book -->
    <div class="form-box">     
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

    </div>

    <?php

if (isset($_POST['add_book'])) {
    $ISBN = htmlspecialchars($_POST['isbn']);
    $TITLE = htmlspecialchars($_POST['title']);
    $AUTHOR = htmlspecialchars($_POST['author']);
    $PUBDATE = htmlspecialchars($_POST['date']);

    $sql = "INSERT INTO BOOKS (ISBN, TITLE, AUTHOR, PUBDATE, STATUS)
            VALUES('$ISBN', '$TITLE', '$AUTHOR', '$PUBDATE', 'Available')";
    //Confirm if it was successful
          if (mysqli_query($conn, $sql)) {
            echo "<script type='text/javascript'>alert('New record created successfully');</script>";
        } else { 
            $alert = "Error: " . $sql . "<br>" . mysqli_error($conn); 
             echo "<script type='text/javascript'>alert('{$alert}');</script>"; 
        }
}
if (isset($_POST['delete_book'])) {
    // Gather input
        $ISBN = htmlspecialchars($_POST['isbn']);
    //Check if Student exist
        $check = "SELECT * FROM BOOKS WHERE ISBN LIKE '$ISBN'";
        $result = mysqli_query($conn, $check);
        if ($result->num_rows > 0) {
    //Delete the student
             $sql = "DELETE FROM BOOKS WHERE ISBN = '$ISBN'"; 
             mysqli_query($conn, $sql);
             $alert = "Book with ISBN " . $ISBN . " deleted successfully";
             echo "<script type='text/javascript'>alert('{$alert}');</script>"; 
              } 
              else { 
                $alert = "Book with ISBN " . $ISBN . " not found"; 
                echo "<script type='text/javascript'>alert('{$alert}');</script>"; 
            }
    }
    if (isset($_POST['outButton'])) {
    // Gather input
        $Search = htmlspecialchars($_POST['search']);
        $Email = htmlspecialchars($_POST['user']);

        //Check out Date
        $CurDate = date("Y-m-d");
        //Date of return
        $ExpDate = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+30, date("Y")));
        //Check if user exist
        $result = $conn->query("SELECT * FROM users WHERE EMAIL = '$Email'");
        if($result->num_rows === 1){

    //Check if is book is available
        $check = "SELECT id FROM BOOKS WHERE ISBN = '$Search' OR TITLE = '$Search' AND
                                                    STATUS = 'Available' 
                                                    LIMIT 1";
        $result = mysqli_query($conn, $check);
        if ($result->num_rows > 0) {
            $pos = mysqli_fetch_assoc($result);
            $position1 = $pos["id"];
    //update book
             $sql = "UPDATE BOOKS SET EMAIL = '$Email', CHECKDATE = '$CurDate', EXPDATE = '$ExpDate', STATUS = 'Unavailable' 
                    WHERE id = '$position1' "; 
             mysqli_query($conn, $sql);
             $alert = $Search . " has been successfully checked out. Return by: " . $ExpDate;
             echo "<script type='text/javascript'>alert('{$alert}');</script>";
              } 
        else { 
            echo "<script type='text/javascript'>alert('Error: Wrong account or Book unavailable');</script>";  
        }}
        else{
            echo "<script type='text/javascript'>alert('Error: No account found');</script>";
        }
    }
    if (isset($_POST['inButton'])) {
    // Gather input
        $Search = htmlspecialchars($_POST['search']);
        $Email = htmlspecialchars($_POST['user']);
        $result = $conn->query("SELECT * FROM users WHERE EMAIL = '$Email'");
        if($result->num_rows === 1){
    //Check if is book is taken
        $check = "SELECT id FROM BOOKS WHERE ISBN = '$Search' OR TITLE = '$Search' AND
                                                    EMAIL = '$Email' 
                                                    LIMIT 1";
        $result = mysqli_query($conn, $check);
        if ($result->num_rows > 0) {
            $pos = mysqli_fetch_assoc($result);
            $position1 = $pos["id"];
    //Update student
             $sql = "UPDATE BOOKS SET EMAIL = NULL, CHECKDATE = NULL, EXPDATE = NULL, STATUS = 'Available' 
                    WHERE id = '$position1' "; 
             mysqli_query($conn, $sql);
             $alert = $Search . " has been successfully returned";
             echo "<script type='text/javascript'>alert('{$alert}');</script>";
              } 
        else { 
            echo "<script type='text/javascript'>alert('Error: Book already returned');</script>"; 
        }}
        else{
            echo "<script type='text/javascript'>alert('Error: No account found');</script>";
        }
    }
?>


    <div class="wrapper">
        <div class="table-container">
            <div class="search-form-box">
            <form method="post">
            <input type="text" name="searchText" 
            value="<?php if(isset($_POST['searchText'])) { echo $_POST['searchText']; } ?>">
            <select name="search_type" required>
                    <option value="byTitle"> By Title </option>
                    <option value="byAuthor"> By Author </option>
                    <option value="byISBN"> By ISBN </option>
                    <option value="byEmail">By Email</option>
                </select>
            <button type="submit" name="searchButton"> Search Book </button>
                </form>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>#ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publication Date</th>
                        <th>Status</th>
                        <th>Borrower</th>
                        <th>Date Borrowed</th>
                        <th>Date Due</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(isset($_POST['searchButton'])){
                        $searchText = htmlspecialchars($_POST['searchText']);
                        if(!empty($_POST['searchText'])){
                            $selectedValue = $_POST["search_type"];
                            if($selectedValue == 'byTitle'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS,EMAIL,CHECKDATE, EXPDATE FROM BOOKS
                                WHERE TITLE LIKE '%$searchText%'";
                            }
                            if($selectedValue == 'byAuthor'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS,EMAIL,CHECKDATE, EXPDATE FROM BOOKS
                                WHERE AUTHOR LIKE '%$searchText%'";
                            }
                            if($selectedValue == 'byISBN'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS,EMAIL,CHECKDATE, EXPDATE FROM BOOKS
                                WHERE ISBN LIKE '$searchText'";
                            }
                            if($selectedValue == 'byEmail'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS,EMAIL,CHECKDATE, EXPDATE FROM BOOKS
                                WHERE EMAIL LIKE '$searchText'";
                            }
                        }else{
                            $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS,EMAIL,CHECKDATE, EXPDATE FROM BOOKS";
    
                        }
                    }
                    else{
                        $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS,EMAIL,CHECKDATE, EXPDATE FROM BOOKS";

                    }
                    $result = mysqli_query($conn, $search);
                    if ($result->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { 
                            echo "<tr>";
                            foreach ($row as $value) { // get the value for each row
                                    echo "<td>" . $value . "</td>"; 
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

