import React, {useState} from "react";
import Stars from "../components/Stars";
import "./Reviewpage.css";

export default function Reviewpage() {
	const [rating, setRating] = useState(0);
	const [review, setReview] = useState("");
	const [submit, setSubmit] = useState(false);

	const handleSubmit = (e) => {
		e.preventDefault();
		if(rating === 0 || review.trim() === "") {
			alert("Please provide a star rating and a review.");
			return;
		}
		setSubmit(true);
	};
 
 	return(
 		<div className = "review-container">
 		<h2>Leave a Review</h2>
 		{!submit ? (
 			<form onSubmit = {handleSubmit}>
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