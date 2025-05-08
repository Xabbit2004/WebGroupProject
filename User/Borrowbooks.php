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
        <script type="text/javascript" src="../darkmode.js" defer></script>
    </head>
    <body>
        <button id="theme-switch"> 
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Z"/></svg>
    </button>
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