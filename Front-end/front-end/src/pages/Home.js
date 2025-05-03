// Home.js
import "./Home.css";
import React, { useEffect, useState } from 'react';

function Home() {
  const [tableData, setTableData] = useState([]);

  const fetchData = async () => {
    try {
      // Replace this with your actual API or data source
      const response = await fetch('http://localhost:3001/api/data');
      const data = await response.json();
      setTableData(data);
    } catch (error) {
      console.error('Error fetching table data:', error);
    }
  };

  useEffect(() => {
    fetchData();
  }, []);

  return (
    <div>
      <div className="background-image"></div>

      {/* Main container for the page layout */}
      <div className="container">
        <header>
          <h1>Library of Babble</h1>
        </header>
        <br></br>
          <p className = "intro-blurb">
            Welcome to our Library of Babble! This is our Web Technologies final group project.
            Our website is a library management website, you can check out the books we have available, and check one out.
            If one is not available then you may place it on hold to be checked out later.
            Books may only be checked out if you are a registered user of this website, and the same 
            goes for holds!
          </p>
          <br></br>
          <br></br>
          <br></br>
          <form id="filterForm" className="filters">
            <input type="text" id="titleFilter" placeholder="Title"></input>
            <input type="text" id="authorFilter" placeholder="Author"></input>
            <input type="text" id="isbnFilter" placeholder="ISBN"></input>
            <select id="genreFilter">
              <option value="">All Genres</option>
              <option value="Fiction">Fiction</option>
              <option value="Non-fiction">Non-fiction</option>
            </select>
            <input type="date" id="pubDateFrom" placeholder="Published After"></input>
            <input type="date" id="pubDateTo" placeholder="Published Before"></input>
            <button type="submit">Filter</button>
          </form>

          <table className = "bookTable">
            <thead>
              <tr>
                <th>Title</th>
                <th>Availability</th>
              </tr>
            </thead>
            <tbody>
              {tableData.map((item, index) => (
                <tr key={index}>
                  <td>{item.TITLE}</td>
                  <td>{item.STATUS}</td>
                  <td><button>Checkout</button></td>
                </tr>
              ))}
            </tbody>
          </table>
          <br></br>
          <br></br>
      </div>
    </div>
    
  );
}

export default Home;