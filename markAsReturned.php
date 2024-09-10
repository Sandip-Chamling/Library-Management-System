<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Check if the book ID is provided
if (!isset($_GET['bookId'])) {
    echo "Invalid request.";
    exit();
}

$bookId = $_GET['bookId'];

require_once('dbConnection.php');
// Update book status to 'Available'
$sqlUpdateBookStatus = "UPDATE book SET status = 'Available' WHERE book_id = ?";
$stmtUpdateBookStatus = $conn->prepare($sqlUpdateBookStatus);

if ($stmtUpdateBookStatus === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmtUpdateBookStatus->bind_param("i", $bookId);

if (!$stmtUpdateBookStatus->execute()) {
    die("Error in executing statement: " . $stmtUpdateBookStatus->error);
}

$stmtUpdateBookStatus->close();

// Update borrowed status to 'Returned' and set return date
$sqlUpdateBorrowedStatus = "UPDATE borrow SET status = 'Returned' WHERE book_id = ? AND status = 'Not-Returned'";
$stmtUpdateBorrowedStatus = $conn->prepare($sqlUpdateBorrowedStatus);

if ($stmtUpdateBorrowedStatus === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmtUpdateBorrowedStatus->bind_param("i", $bookId);

if (!$stmtUpdateBorrowedStatus->execute()) {
    die("Error in executing statement: " . $stmtUpdateBorrowedStatus->error);
}

$stmtUpdateBorrowedStatus->close();

// Delete the row from the borrow table
$sqlDeleteBorrowedRow = "DELETE FROM borrow WHERE book_id = ? AND status = 'Returned'";
$stmtDeleteBorrowedRow = $conn->prepare($sqlDeleteBorrowedRow);

if ($stmtDeleteBorrowedRow === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmtDeleteBorrowedRow->bind_param("i", $bookId);

if (!$stmtDeleteBorrowedRow->execute()) {
    die("Error in executing statement: " . $stmtDeleteBorrowedRow->error);
}

$stmtDeleteBorrowedRow->close();

$conn->close();

// Redirect to bookRecord.php after the update
header('Location: bookRecord.php');
exit();
?>
