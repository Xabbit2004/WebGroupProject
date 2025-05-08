<?php

session_start();
// Check if user is already loged in
if(!isset($_SESSION['email'])){
    header("location: index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome, <span><?= $_SESSION['name']; ?></span>
    <p>This the <span>user</span> page </p>
    <button onclick="window.location.href='logout.php'">Logout</button>

	<!-- Have a search bar -->
	<div class="container">
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
                <h3> Special Filter </h3>
                <label> Publication date: </label><br>
                <input type="date" name="fromDate"
                <label> - </label>
                <input type="date" name="toDate"
                <label> Genre: </label>
                <input type="text" name="Genre"
            </form>
        </div>
	</div>
</body>
</html>

<?php 

// Function: To display the result of the filtered 
// database in a table format
// ------------------------------------------------
function displayTable($result, $header) {
    $counthead = 0;
       if ($result->num_rows > 0) {
              echo "<br>";
       //Use table to display the database table
              echo '<table>'; // table start
       // table heading
              echo "<thead><tr>";
              while($row = mysqli_fetch_assoc($header)){
                     foreach($row as $value){
                     echo "<th>" . $value . "</th>";
                 }
                 $counthead++;
                 if($counthead == 9)
                     break;
              }
              echo "<tr></thead>";

       // table rows
             
              while ($row = mysqli_fetch_assoc($result)) { 
                    echo "<tbody><tr>";
                    $counthead=0;
                    foreach ($row as $value) { // get the value for each row
                            echo "<td>" . $value . "</td>"; 
                              $counthead++;
                         if($counthead == 9)
                         break;
                     }
                     echo "</tr></tbody>";
              }
              echo "</table>"; // table end
       }
       else { echo "<p>No records found.</p>";}
}

?>