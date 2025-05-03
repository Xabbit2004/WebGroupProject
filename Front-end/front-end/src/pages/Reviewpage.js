import React, {useState} from "react";
import Stars from "../components/Stars";
import "./Reviewpage.css";

const ReviewPage = () => {
  const [reviewText, setReviewText] = useState('');
  const [rating, setRating] = useState(null);

  const handleReviewSubmit = (e) => {
    e.preventDefault();
    // Handle the form submission logic (e.g., send review data to a backend or display confirmation)
    alert(`Review: ${reviewText}, Rating: ${rating}`);
  };

  return (
    <div className="review-container">
      <h2 className="review-heading">Submit Your Review</h2>
      <form className="review-form" onSubmit={handleReviewSubmit}>
        <div className="form-group">
          <label htmlFor="review-text">Review:</label>
          <textarea
            id="review-text"
            rows="4"
            value={reviewText}
            onChange={(e) => setReviewText(e.target.value)}
            placeholder="Enter your review here"
          ></textarea>
        </div>
        <div className="form-group star-row">
          <label>Rating:</label>
          {[1, 2, 3, 4, 5].map((star) => (
            <React.Fragment key={star}>
              <input
                type="radio"
                name="rating"
                value={star}
                onChange={() => setRating(star)}
                checked={rating === star}
              />
              {star}
            </React.Fragment>
          ))}
        </div>
        <button type="submit" className="submit-btn">Submit</button>
      </form>
      <div className="thank-you-message">
        <h3>Thank you for your review!</h3>
      </div>
    </div>
  );
};

export default ReviewPage;
