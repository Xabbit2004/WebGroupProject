import React, {useState, useEffect} from "react";
import Stars from "../components/Stars";
import "./Reviewpage.css";

export default function Reviewpage() {
	const [rating, setRating] = useState(0);
	const [review, setReview] = useState("");
	const [submit, setSubmit] = useState(false);
	const [books, setBooks] = useState([]);
	const [selectedBook, setSelectedBook] = useState("");
	const [searchTerm, setSearchTerm] = useState("");
	const [filteredBooks, setFilteredBooks] = useState([]);


	useEffect(() => {
		fetch("http://localhost:3001/books")
			.then((res) => res.json())
			.then((data) => {
				setBooks(data)
				setFilteredBooks(data); //initialize filtered books with all books
		})

			.catch((err) => console.error("Failed to fetch books:", err));
	}, []);

	useEffect(() => {
		if (searchTerm.trim() === "") {
			setFilteredBooks(books);
		}else {
			const filtered = books.filter(book =>
				book.title.toLowerCase().includes(searchTerm.toLowerCase())
				);
			setFilteredBooks(filtered);
		}
	}, [searchTerm, books]);

	const handleSubmit = async (e) => {
		e.preventDefault();
		if(rating === 0 || review.trim() === "") {
			alert("Please provide a star rating and a review.");
			return;
		}
		try {
			const response = await fetch("http://localhost:3001/submit-review", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify({rating, review}),

			});
			if(response.ok){
				setSubmit(true);
			} else {
				alert("Error submitting review.");
			} 
		}catch (error) {
				console.error("Error: " , error);
				alert("Failed to connect to the server.");
			}


   			
  
	};
 
 	return(
 		<div className = "review-container">
 		<h2>Leave a Review</h2>
 		{!submit ? (
 			<form onSubmit = {handleSubmit}>
 			<div className = "form-group">
 			<label>Select Book:</label>
 			<select
 				value={selectedBook}
 				onChange = {(e) => setSelectedBook(e.target.value)}
 				required
 			>
 			<option value=""> Choose Book </option>
 			{books.map((book) => ( 
 				<option key={book.id} value={book.id}>
 					{book.title}
 				</option>
 				))}
 			</select>
 			</div>
 			 <div className="book-results">
            {filteredBooks.length > 0 ? (
              filteredBooks.map((book) => (
                <div 
                  key={book.id} 
                  className={`book-option ${selectedBook === book.id ? 'selected' : ''}`}
                  onClick={() => setSelectedBook(book.id)}
                >
                  {book.title}
                </div>
              ))
            ) : (
              <p>No books found matching your search.</p>
            )}
          </div>
 			<div className = "form-group">
 			<label> Rate Book: </label>
 			 <div className = "star-row">
 			 <Stars rating={rating} onRate={setRating} />
 			 </div>
 			</div>
 			<div className = "form-group">
 			 <label> Your Review </label>
 			 <textarea
 			 	value = {review}
 			 	onChange = {(e) => setReview(e.target.value)}
 			 	rows="4"
 			 	placeholder = "Write Something..."
 			 	required
 			 />
 			</div>
 			<button type = "submit">Submit Review</button>
 			</form>
 			) : (
 			<div>
 				<h3>Thank you for your review!</h3>
 				<p>
 				 <strong>Rating:</strong>{" "}
 				 <span className="star-row">
 				 <Stars rating={rating} onRate= {() => {}} />
 				 </span>
 				</p>
 				<p>
 				 <strong>Review:</strong> {review}
 				</p>
 			</div>
 			)}
 		</div>
 	);
}
