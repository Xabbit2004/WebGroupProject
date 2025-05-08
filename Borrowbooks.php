<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed books</title>
    <style>
        /* Your CSS styles remain unchanged */
    </style>
</head>
<body>
    <h1>My Borrowed Books</h1>

    <?php
    session_start();

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Library";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get user's email from session - MAKE SURE THIS MATCHES YOUR LOGIN SYSTEM
        $userEmail = $_SESSION['email'] ?? null;

        if ($userEmail) {
            // Fetch borrowed books for current user
            $stmt = $conn->prepare("SELECT b.TITLE, b.AUTHOR, b.GENRE, b.CHECKDATE, b.EXPDATE 
                                  FROM BOOKS b 
                                  WHERE b.EMAIL = :email AND b.STATUS = 'Borrowed' 
                                  ORDER BY b.EXPDATE");
            $stmt->bindParam(':email', $userEmail);
            $stmt->execute();
            $borrowedBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $borrowedBooks = [];
            echo "<p>Please log in to view your borrowed books.</p>";
        }

    } catch(PDOException $e) {
        echo "<p>Database Error: " . $e->getMessage() . "</p>";
        $borrowedBooks = [];
    }
    ?>

    <?php if (!empty($borrowedBooks)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Checkout Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($borrowedBooks as $book): 
                    $dueDate = new DateTime($book['EXPDATE']);
                    $today = new DateTime();
                    $interval = $today->diff($dueDate);
                    $daysRemaining = (int)$interval->format('%r%a');
                ?>
                <tr>
                    <td><?= htmlspecialchars($book['TITLE']) ?></td>
                    <td><?= htmlspecialchars($book['AUTHOR']) ?></td>
                    <td><?= htmlspecialchars($book['GENRE']) ?></td>
                    <td><?= date('M j, Y', strtotime($book['CHECKDATE'])) ?></td>
                    <td class="<?= ($daysRemaining < 0) ? 'overdue' : (($daysRemaining <= 3) ? 'due-soon' : '') ?>">
                        <?= date('M j, Y', strtotime($book['EXPDATE'])) ?>
                        <?php if ($daysRemaining < 0): ?>
                            <br><small>(Overdue by <?= abs($daysRemaining) ?> days)</small>
                        <?php elseif ($daysRemaining <= 3): ?>
                            <br><small>(Due in <?= $daysRemaining ?> days)</small>
                        <?php endif; ?>
                    </td>
                    <td class="status-borrowed">Borrowed</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You currently have no borrowed books.</p>
    <?php endif; ?>
</body>
</html>