// server.js
const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const app = express();
const PORT = 3001;

app.use(cors());
app.use(express.json());

// MySQL connection
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: 'Abacab#2024',
  database: 'library'
});

// API endpoint to get data
app.get('/api/data', (req, res) => {
  db.query('SELECT * FROM books', (err, results) => {
    if (err) {
      res.status(500).json({ error: err.message });
    } else {
      console.log(results);
      res.json(results);
    }
  });
});

// List all books
app.get('/books', (req, res) => {
  db.query('SELECT TITLE, STATUS FROM books', (err, results) => {
    if (err) return res.status(500).send(err);
    res.json(results);
  });
});

// Checkout a book
app.post('/checkout/:id', (req, res) => {
  const { id } = req.params;
  db.query('UPDATE books SET status = "Checked Out" WHERE id = ?', [id], (err) => {
    if (err) return res.status(500).send(err);
    res.send('Book checked out!');
  });
});

// Place a hold
app.post('/hold/:id', (req, res) => {
  const { id } = req.params;
  db.query('UPDATE books SET status = "On Hold" WHERE id = ?', [id], (err) => {
    if (err) return res.status(500).send(err);
    res.send('Hold placed!');
  });
});

app.listen(PORT, () => {
  console.log(`Server running on http://localhost:${PORT}`);
});
