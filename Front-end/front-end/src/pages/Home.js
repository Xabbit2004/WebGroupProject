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
      <h1>Home Page</h1>
      <table>
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
