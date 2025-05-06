<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Search Book</title>
	    <link rel="stylesheet" type="text/css" href="styling.css">

</head>
<body>
	<!-- Have a search bar -->
	<div class="blocks">
	<form method="post">
		<input type="text" name="searchText" 
		value="<?php if(isset($_POST['searchText'])) { echo $_POST['searchText']; } ?>">
		<button type="submit" name="searchButton"> Search Book </button>
		<h3> Special Filter </h3>
        <label> Publication date: </label><br>
        <input type="date" name="fromDate"
        value="<?php if(isset($_POST['fromDate'])) { echo $_POST['fromDate']; } ?>">
        <label> - </label>
        <input type="date" name="toDate"
        value="<?php if(isset($_POST['toDate'])) { echo $_POST['toDate']; } ?>"> <br>
        <label> Genre: </label>
        <input type="text" name="Genre"
        value="<?php if(isset($_POST['Genre'])) { echo $_POST['Genre']; } ?>">
	</form>
	</div>

	<?php
//	Protection from user
	if(!defined('searchbar')){
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

// Gather Columns name for Table Header
    $columns = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA ='Library' AND 
    																	 TABLE_NAME = 'BOOKS'"; 
    $header = mysqli_query($conn,$columns);

// ---------------- Search Button -----------------
// ------------------------------------------------
    if(isset($_POST['searchButton']))
    {
//------ IF given A Tittle, ISBN, or Author
    	if(!empty($_POST['searchText'])){
    	$search = htmlspecialchars($_POST['searchText']);
   	//-- Between Given dates
	    	if(!empty($_POST['fromDate']) && !empty($_POST['toDate'])){
	    		$fromDate = $_POST['fromDate'];
	    		$toDate = $_POST['toDate'];
	    		$Filter = "SELECT * FROM BOOKS WHERE TITLE LIKE '%$search%' OR 
	    														ISBN = '$search' OR 
	    														AUTHOR LIKE '%$search%' AND 
	    														PUBDATE BETWEEN '$fromDate' AND '$toDate'";
	    	}
    //-- From Given date and below
	    	elseif (!empty($_POST['toDate'])) {
	    		$toDate = $_POST['toDate'];
	    		$Filter = "SELECT * FROM BOOKS WHERE TITLE LIKE '%$search%' OR 
	    														ISBN = '$search' OR 
	    														AUTHOR LIKE '%$search%' AND
	    														PUBDATE <= '$toDate' ";
	    	}
	//-- From Given date and above
	    	elseif (!empty($_POST['fromDate'])) {
	    		$fromDate = $_POST['fromDate'];
	    		$Filter = "SELECT * FROM BOOKS WHERE TITLE LIKE '%$search%' OR 
	    														ISBN = '$search' OR 
	    														AUTHOR LIKE '%$search%' AND
	    														PUBDATE >= '$fromDate'";
	    	}
    //-- From Given genre
	    	elseif (isset($_POST['Genre'])) {
	    		$genre = htmlspecialchars($_POST['Genre']);
	    		$Filter = "SELECT * FROM BOOKS WHERE TITLE LIKE '%$search%' OR 
	    														ISBN = '$search' OR 
	    														AUTHOR LIKE '%$search%' AND
	    														GENRE = '$genre' ";
	    	}
    //-- Wihtout specific Filters
	    	else
	    	{
	    		$Filter = "SELECT * FROM BOOKS WHERE TITLE LIKE '%$search%' OR 
	    														ISBN = '$search' OR 
	    														AUTHOR LIKE '%$search%'";
	    	}
	//-- Gather Database results
		$result = mysqli_query($conn, $Filter);
    	} else{
//------ If there is no text input
	    //-- Between Given dates
	    	if(!empty($_POST['fromDate']) && !empty($_POST['toDate'])){
	    		$fromDate = $_POST['fromDate'];
	    		$toDate = $_POST['toDate'];
	    		$Filter = "SELECT * FROM BOOKS WHERE PUBDATE BETWEEN '$fromDate' AND '$toDate'";
	    	}
	    //-- From Given date and below
	    	elseif (!empty($_POST['toDate'])) {
	    		$toDate = $_POST['toDate'];
	    		$Filter = "SELECT * FROM BOOKS WHERE PUBDATE <= '$toDate' ";
	    	}
		//-- From Given date and above
	    	elseif (!empty($_POST['fromDate'])) {
	    		$fromDate = $_POST['fromDate'];
	    		$Filter = "SELECT * FROM BOOKS WHERE PUBDATE >= '$fromDate'";
	    	}
	   	//-- From Given genre
	    	elseif (!empty($_POST['Genre'])) {
	    		$genre = htmlspecialchars($_POST['Genre']);
	    		$Filter = "SELECT * FROM BOOKS WHERE GENRE = '$genre' ";
	    	}
	    //-- Default
	    	else
	    	{
	    		$Filter = "SELECT * FROM BOOKS";
	    	}
    	//-- Gather Database results
		$result = mysqli_query($conn, $Filter);
    	} 
    }
//-- Default
    else
    {
    	$all = "SELECT * FROM BOOKS";
    	$result = mysqli_query($conn, $all);
    }
   	
//-- Call function to display table
	displayTable($result, $header);	

// ------------------------------------------------
// ------------------------------------------------

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
</body>
</html>