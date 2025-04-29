// Checkout.js
import React, { useEffect, useState } from 'react';
import axios from 'axios';

function App() {
  const [books, setBooks] = useState([]);

  useEffect(() => {
    fetchBooks();
  }, []);

  const fetchBooks = () => {
    axios.get('http://localhost:3001/books')
      .then(response => setBooks(response.data))
      .catch(error => console.error(error));
  };

  const checkoutBook = (id) => {
    axios.post(`http://localhost:3001/checkout/${id}`)
      .then(() => fetchBooks())
      .catch(error => console.error(error));
  };

  const holdBook = (id) => {
    axios.post(`http://localhost:3001/hold/${id}`)
      .then(() => fetchBooks())
      .catch(error => console.error(error));
  };

  return (
    <div className="App" style={{ padding: '20px' }}>
      <h1>Library</h1>
      <table border="1" cellPadding="10">
        <thead>
          <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {books.map(book => (
            <tr key={book.id}>
              <td>{book.title}</td>
              <td>{book.status}</td>
              <td>
                <button onClick={() => checkoutBook(book.id)}>Checkout</button>
                <button onClick={() => holdBook(book.id)} style={{ marginLeft: '10px' }}>Place Hold</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default App;
