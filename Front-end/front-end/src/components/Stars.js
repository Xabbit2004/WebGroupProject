import React, { useState } from "react";
import { FaStar } from "react-icons/fa";

export default function Star() {
  const [rating, setRating] = useState(null);

  return (
    <>
      {[...Array(5)].map((_, index) => {
        const currentRate = index + 1;
        return (
          <span key={index} onClick={() => setRating(currentRate)} style={{ cursor: "pointer" }}>
            <FaStar color={currentRate <= rating ? "yellow" : "grey"} size={24} />
          </span>
        );
      })}
    </>
  );
}
