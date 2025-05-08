 <!DOCTYPE html>

 <html>
 <head>
 	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Book Reviews</title>
    <style>

    	body {
    		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    		line-height: 1.6;
    		color: #333;
    		max-width: 1200px;
    		margin: 0 auto;
    		padding: 20px;
    		background: #f5f5f5;
    	}

    	h1{
    		color: #2c3e50;
    		text-align: center;
    		margin-bottom: 30px;
    	}

    	table {
    		width: 100%;
    		border-collapse: collapse;
    		margin-bottom: 30px;
    		background-color: white;
    		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    	}

    	th, td {
    		padding: 12px, 15px;
    		text-align: left;
    		border-bottom: 1px solid #ddd;
    	}

    	th {
    		background-color: #3498db;
    		color: white;
    		font-weight: bold;
    	}

    	tr:nth-child(even) {
    		background-color: #f2f2f2;
    	}

    	tr:hover {
    		background-color: #e3f2f2;
    	}

    	.review-btn {
    		background-color: #2ecc71;
    		color: white;
    		border: none;
    		padding: 8px 12px;
    		border-radius: 4px;
    		cursor: pointer;
    		font-size: 14px;
    		transition: background-color 0.3s;
    	}

    	.review-btn:hover {
    		background-color: #27ae60;
    	}

    	.modal {
    		display: none;
    		position: fixed;
    		z-index: 1;
    		left: 0;
    		top: 0;
    		width: 100%;
    		height: 100%;
    		overflow: auto;
    		background-color: rgba(0, 0, 0, 0.4);
    	}

    	.modal-content {
    		background-color: #fefefe;
    		margin: 10% auto;
    		padding: 20px;
    		border: 1px solid #888;
    		width: 60%;
    		border-radius: 8px;
    		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    	}

    	.close {
    		color: #aaa;
    		float: right;
    		font-size: 28px;
    		font-weight: bold;
    		cursor: pointer;
    	}	

    	.close:hover{
    		color: black;
    	}

    	.star-rating {
    		color: #f39c12;
    		font-size: 18px;
    	}

    	.review-form {
    		margin-top: 20px;
    	}

    	.review-form label {
    		display: block;
    		margin-bottom: 5px;
    		font-weight: bold;
    	}

    	.review-form textarea {
    		width: 100%;
    		padding: 10px;
    		border: 1px solid #ddd;
    		border-radius: 4px;
    		min-height: 100px;
    		margin-bottom: 15px;
    	}

    	.rating-input {
    		margin-bottom: 15px;
    	}

    	.submit-review {
    		background-color: #3498db;
    		color: white;
    		border: none;
    		padding: 10px 15px;
    		border-radius: 4px;
    		cursor: pointer;
    		font-size: 16px;
    	}

    	.submit-review:hover{
    		background-color: #2980db;
    	}

    	.average-rating {
    		font-weight: bold;
    		color: #e67e22;
    	}
    </style>
</head>

<body>
    <h1>Library Book Reviews</h1>

    <?php
    // Initialize variables
    $books = [];
    $error = null;

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Verify database connection by selecting a simple query
        $conn->query("SELECT 1")->fetchColumn();

        // Fetch all books
        $stmt = $conn->prepare("SELECT b.*, AVG(r.RATING) as avg_rating 
                               FROM BOOKS b 
                               LEFT JOIN RATINGS r ON b.ISBN = r.ISBN 
                               GROUP BY b.id");
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-review'])) {
            $isbn = $_POST['isbn'] ?? '';
            $rating = $_POST['rating'] ?? '';
            $review = $_POST['review'] ?? '';
            
            if (!empty($isbn) && !empty($rating) && !empty($review)) {
                $insertStmt = $conn->prepare("INSERT INTO RATINGS (ISBN, RATING, REVIEW) VALUES (?, ?, ?)");
                $insertStmt->execute([$isbn, $rating, $review]);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
    ?>

    <?php if ($error): ?>
        <div class="error" style="color: red; padding: 10px; background: #ffeeee; margin-bottom: 20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publication Date</th>
                <th>Average Rating</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['TITLE']); ?></td>
                        <td><?php echo htmlspecialchars($book['AUTHOR']); ?></td>
                        <td><?php echo htmlspecialchars($book['GENRE']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($book['PUBDATE'])); ?></td>
                        <td>
                            <?php if (!empty($book['avg_rating'])): ?>
                                <span class="average-rating"><?php echo number_format($book['avg_rating'], 1); ?></span>
                                <span class="star-rating">
                                    <?php
                                        $fullStars = floor($book['avg_rating']);
                                        $halfStar = ($book['avg_rating'] - $fullStars) >= 0.5;

                                        for($i = 1; $i <= 5; $i++) {
                                            if ($i <= $fullStars) {
                                                echo '★';
                                            } elseif ($halfStar && $i == $fullStars + 1) {
                                                echo '½';
                                            } else {
                                                echo '☆';
                                            }
                                        }
                                    ?>
                                </span>
                            <?php else: ?>
                                No rating yet
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="review-btn" onclick="openModal('<?php echo htmlspecialchars($book['ISBN']); ?>', '<?php echo htmlspecialchars(addslashes($book['TITLE'])); ?>')">
                                Add Review
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No books found in the database</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

 <!--- Modal ---->
 
 <div id = "reviewModal" class = "modal">
 	<div class = "modal-content">
 		<span class = "close" onclick = "closeModal()">&times;</span>
 		<h2 id = "modalTitle"></h2>

 		<div class="review-form">
 			<form method="POST" action="">
 				<input type = "hidden" name="isbn" id="modalIsbn" value = "">

 				<div class = "rating-input">
 					<label for = "rating">Rating:</label>
 					<select name = "rating" id="rating" required>
 						<option value ="">Select a rating </option>
 						<option value ="1">1 Star </option>
 						<option value ="2">2 Stars </option>
 						<option value ="3">3 Stars</option>
 						<option value ="4">4 Stars</option>
 						<option value ="5">5 Stars</option>
 					</select>
 				</div>

 				<label for="review">Your Review:</label>
 				<textarea name="review" id="review" required></textarea>

 				<button type="submit" name = "submit-review" class="submit-review">Submit Review </button>

 			</form>
 		</div>

 		<div id="existingReviews">
 			<h3>Reviews</h3>
 			<?php
 			//Fetch existing reviews for the book
 			?>

 			<div id="reviewsList"></div>
 		</div>
 	</div>
 </div>

 <script>
 	//Open the Modal
 	function openModal(isbn, title) {
 		document.getElementById('reviewModal').style.display = 'block';
 		document.getElementById('modalTitle').textContent = 'Review: ' + title;
 		document.getElementById('modalIsbn').value = isbn;

 		fetchReviews(isbn)
 	}

 	//Close the Modal
 	function closeModal() {
 		document.getElementById('reviewModal').style.display = 'none';
 	}

 	//Fetch reviews for the book
 	function fetchReviews(isbn) {
 		fetch('get_reviews.php?isbn=' + isbn)
 		.then(response => response.text())
 		.then(data => {
 			document.getElementById('reviewsList').innerHTML = data;
 		});
 	}

 	//Close modal when clicking outside of it
 	window.onclick = function(event) {
 		const modal = document.getElementById('reviewModal');
 		if(event.target == modal) {
 			closeModal();
 		}
 	}
 </script>
</body>
</html>


                            








