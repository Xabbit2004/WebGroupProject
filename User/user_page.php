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
        <a id='motionLeft' href='user_page.php'>Search</a>
        <a id='motionLeft' href='Borrowbooks.php'>My Dashboard</a>
    </div>

    <div class="header">
        <h1>Welcome, <span><?= $_SESSION['name']; ?></span>
        <p>This the <span>User</span> page </p>
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
                    
                    $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS FROM BOOKS";

                    if(isset($_POST['searchButton'])){
                        $searchText = htmlspecialchars($_POST['searchText']);
                        if(!empty($_POST['searchText'])){
                            $selectedValue = $_POST["search_type"];
                            if($selectedValue == 'byTitle'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS FROM BOOKS
                                WHERE TITLE LIKE '%$searchText%'";
                            }
                            if($selectedValue == 'byAuthor'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS FROM BOOKS
                                WHERE AUTHOR LIKE '%$searchText%'";
                            }
                            if($selectedValue == 'byISBN'){
                                $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS FROM BOOKS
                                WHERE ISBN LIKE '$searchText'";
                            }
                        } else{
                            $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS FROM BOOKS";
    
                        }
                    } else{
                        $search = "SELECT ISBN,TITLE,AUTHOR,PUBDATE,STATUS FROM BOOKS";
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

