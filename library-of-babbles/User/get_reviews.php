<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Library";

try{
	$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$isbn = $_GET['isbn'];

	//Fetch reviews for the selected book
	$stmt = $conn->prepare("SELECT * FROM RATINGS WHERE ISBN = ? ORDER BY RATING DESC");
	$stmt->execute([$isbn]);
	$reviews = $stmt->fetchALL(PDO::FETCH_ASSOC);

	if(count($reviews) > 0){
		foreach($reviews as $review){
			echo '<div class = "review-item" style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #FFF;">';
			echo '<div class = "review-rating" style="color: #f39c12; margin-bottom: 5px;">' ;

			//Display star rating
			for($i = 1; $i <= 5; $i++){
				echo $i <= $review['RATING'] ? '★' : '☆';
			}

			echo ' (' .$review['RATING']. 'stars)';
			echo '</div>';
			echo '<div class = "review-text">' . htmlspecialchars($review['REVIEW']) . '</div>';
			echo '</div>';
		}
	} else {
		echo '<p>No reviews yet for this book!</p>';
	}
} catch(PDOException $e) {
	echo "Error:" . $e->getMessage();
}
?>