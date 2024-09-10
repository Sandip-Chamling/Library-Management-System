<?php require_once('header.php'); ?>
<style >
  .li3 a{
    color:#4b68e8;
  }
</style>
<?php
session_start();

// Check if the user is logged in
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

// Check if the book is reserved or borrowed
$sqlCheckBookStatus = "SELECT status FROM book WHERE book_id = ?";
$stmtCheckBookStatus = $conn->prepare($sqlCheckBookStatus);

if ($stmtCheckBookStatus === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmtCheckBookStatus->bind_param("i", $bookId);

if (!$stmtCheckBookStatus->execute()) {
    die("Error in executing statement: " . $stmtCheckBookStatus->error);
}

$stmtCheckBookStatus->bind_result($bookStatus);

if ($stmtCheckBookStatus->fetch()) {
    // Check if the book is reserved or borrowed
    if ($bookStatus == 'Reserved') {
        echo "<p class='del-msg' >Cannot delete. The book is reserved or borrowed.</p>";
    } else {
        // Close the statement before preparing a new one
        $stmtCheckBookStatus->close();

        // Delete the record from the book table
        $sqlDeleteFromBookTable = "DELETE FROM book WHERE book_id = ?";
        $stmtDeleteFromBookTable = $conn->prepare($sqlDeleteFromBookTable);

        if ($stmtDeleteFromBookTable === false) {
            die("Error in preparing delete statement: " . $conn->error);
        }

        $stmtDeleteFromBookTable->bind_param("i", $bookId);

        if (!$stmtDeleteFromBookTable->execute()) {
            die("Error in executing delete statement: " . $stmtDeleteFromBookTable->error);
        }

        echo "<p class='success-msg'>Record deleted Successfully!</p>";
        // Check if the statement exists before attempting to close it
        if ($stmtDeleteFromBookTable) {
            $stmtDeleteFromBookTable->close();
        }
    }
} else {
    echo "Error fetching book status.";
}

$conn->close();
?>
<script>
    // Redirect to another page 
    setTimeout(function () {
        window.location.href = 'bookRecord.php';
    }, 2000); // 2000 milliseconds = 1 second
</script>
<?php require_once('footer.php'); ?><?php
