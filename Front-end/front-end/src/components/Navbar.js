import { Link } from "react-router-dom";
import {Provider} from "./ui/provider";
import React, { useState } from 'react';
import DarkModeButton from "./DarkModeButton";
import "./style.css";

export default function Navbar() {
	const [query, setQuery] = useState('');

	const handleSubmit = (e) => {
		e.preventDefault();
		alert(`Searching for: ${query}`);
	};
	return (
		<Provider>
		<nav className = "navbar">
		<div className = "site-title">
			<Link to ="/">Library Babel</Link>
		</div>

		<form onSubmit={handleSubmit} className = "search-form">
			<input
				type = "text"
				value = {query}
				onChange = {(e) => setQuery(e.target.value)}
				placeholder = "Search..."
				className = "search-input"
			/>
			<button type =  "submit" className="search-button">Go</button>
		</form>


		<ul className = "navbar-links">
			<li className = "nav-item">
				<Link to = "/Reviewpage">Review Page </Link>
			</li>
			<li className = "nav-item">
				<Link to = "/Loginpage">Login</Link>
			</li>
			<li className = "nav-item">
				<DarkModeButton />
			</li>

		</ul>
		</nav>
		</Provider>
	);
}
