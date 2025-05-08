<?php

session_start();
// Check if user is already loged in
if(!isset($_SESSION['email'])){
    header("location:../index.php");
    exit();
}

require_once '../config.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="user_style.css">
</head>
<body>
    <div class="topnav">
        <a id='still'><?= $_SESSION['name']?></a>
        <a id='motionRight' href='../logout.php'>Logout</a>
        <a id='motionLeft' href='user_page.php'>Search</a>
        <a id='motionLeft' href='Borrowbooks.php'>My Dashboard</a>
    </div>

    <div class="header">
        <h1>Welcome, <span><?= $_SESSION['name']; ?></span>
        <p>This the <span>admin</span> page </p>
    </div>
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
                    </select>
                    <button type="submit" name="searchButton"> Search Book </button>
                    <button type="submit" name="CheckoutButton"> Borrow Book </button>
                </form>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>#ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publication Date</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                    if(isset($_POST['CheckoutButton'])){
                        $searchText = htmlspecialchars($_POST['searchText']);
                        $email = $_SESSION['email'];
                        
                        //Check out Date
                        $CurDate = date("Y-m-d");
                        //Date of return
                        $ExpDate = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+30, date("Y")));

                        if(!empty($_POST['searchText'])){
                            $selectedValue = $_POST["search_type"];
                            switch($selectedValue){  
                                case 'byAuthor':
                                echo "<script type='text/javascript'>alert('Error: One book must be chosen');</script>";
                                break;

                                case 'byTitle':
                                //Check if is book is available
                                $check = "SELECT id FROM BOOKS WHERE TITLE = '$searchText' AND
                                STATUS = 'Available' 
                                LIMIT 1";
                                $result = mysqli_query($conn, $check);
                                if ($result->num_rows > 0) {
                                $pos = mysqli_fetch_assoc($result);
                                $position1 = $pos["id"];
                                //update book
                                $sql = "UPDATE BOOKS SET EMAIL = '$email', CHECKDATE = '$CurDate', EXPDATE = '$ExpDate', STATUS = 'Unavailable' 
                                WHERE id = '$position1' "; 
                                mysqli_query($conn, $sql);

                                $alert = $searchText . " has been successfully checked out. Return by: " . $ExpDate;
                                echo "<script type='text/javascript'>alert('{$alert}');</script>";
                                }else{
                                    echo "<script type='text/javascript'>alert('Error: Wrong Book or Book unavailable');</script>"; 
                                }
                                break;

                                case 'byISBN':
                              //Check if is book is available
                                $check = "SELECT id FROM BOOKS WHERE ISBN = '$searchText' AND
                                STATUS = 'Available' 
                                LIMIT 1";
                                $result = mysqli_query($conn, $check);
                                if ($result->num_rows > 0) {
                                $pos = mysqli_fetch_assoc($result);
                                $position1 = $pos["id"];
                                //update book
                                $sql = "UPDATE BOOKS SET EMAIL = '$email', CHECKDATE = '$CurDate', EXPDATE = '$ExpDate', STATUS = 'Unavailable' 
                                WHERE id = '$position1' "; 
                                mysqli_query($conn, $sql);
                                
                                $alert = $searchText . " has been successfully checked out. Return by: " . $ExpDate;
                                echo "<script type='text/javascript'>alert('{$alert}');</script>";
                                }else{
                                    echo "<script type='text/javascript'>alert('Error: Wrong Book or Book unavailable');</script>"; 
                                }
                                break;

                            }
                        } else{
                            echo "<script type='text/javascript'>alert('Error: No book chosen');</script>";
                        }
                    }                            
                    
                    $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE, RATING,STATUS FROM BOOKS";

                    if(isset($_POST['searchButton'])){
                        $searchText = htmlspecialchars($_POST['searchText']);
                        if(!empty($_POST['searchText'])){
                            $selectedValue = $_POST["search_type"];
                            if($selectedValue == 'byTitle'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE, RATING,STATUS FROM BOOKS
                                WHERE TITLE LIKE '%$searchText%'";
                            }
                            if($selectedValue == 'byAuthor'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE, RATING,STATUS FROM BOOKS
                                WHERE AUTHOR LIKE '%$searchText%'";
                            }
                            if($selectedValue == 'byISBN'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE, RATING,STATUS FROM BOOKS
                                WHERE ISBN LIKE '$searchText'";
                            }
                        } else{
                            $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE, RATING,STATUS FROM BOOKS";
    
                        }
                    } else{
                        $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE, RATING,STATUS FROM BOOKS";
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

