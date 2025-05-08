<?php

session_start();
// Check if user is already loged in
if(!isset($_SESSION['email'])){
    header("location:../index.php");
    exit();
}

require_once '../config.php';
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed books</title>
    <link rel="stylesheet" href="user_style.css">
</head>
<body>
<div class="topnav">
        <a id='still'><?= $_SESSION['name']?></a>
        <a id='motionRight' href='../logout.php'>Logout</a>
        <a id='motionLeft' href='user_page.php'>Search</a>
        <a id='motionLeft' href='Borrowbooks.php'>My Dashboard</a>
    </div>

    <div class="header">
        <h1>My Borrowed Books</h1>
    </div>

    <?php 
    $email = $_SESSION['email'];
    $list = "SELECT TITLE, AUTHOR, CHECKDATE, EXPDATE,STATUS FROM BOOKS WHERE EMAIL = '$email'";
    $borrowedBooks = mysqli_query($conn, $list);
    if (!empty($borrowedBooks)) : 
    ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
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