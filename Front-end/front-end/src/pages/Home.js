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
      <h1>Library of Babble</h1>
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
      <table className = "bookTable">
        <thead>
          <tr>
            {/* Example headers */}
            <th>Title</th>
            <th>Availability</th>
          </tr>
        </thead>
        <tbody>
          {tableData.map((item, index) => (
            <tr key={index}>
              <td>{item.TITLE}</td>
              <td>{item.STATUS}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default Home;
