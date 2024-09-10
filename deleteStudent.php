
<?php require_once('header.php'); ?>
<style >
  .li2 a{
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

// Check if the student ID is provided
if (!isset($_GET['studentId'])) {
    echo "Invalid request.";
    exit();
}

$studentId = $_GET['studentId'];

require_once('dbConnection.php');

// Check if the student has any borrowed books
$sqlCheckStudentBooks = "SELECT COUNT(*) FROM borrow WHERE s_id = ? AND status='Not-Returned'";
$stmtCheckStudentBooks = $conn->prepare($sqlCheckStudentBooks);

if ($stmtCheckStudentBooks === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmtCheckStudentBooks->bind_param("i", $studentId);

if (!$stmtCheckStudentBooks->execute()) {
    die("Error in executing statement: " . $stmtCheckStudentBooks->error);
}

$stmtCheckStudentBooks->bind_result($borrowedBooksCount);
$stmtCheckStudentBooks->fetch();

if ($borrowedBooksCount > 0) {
   echo "<p class='del-msg' > Cannot delete. The student has borrowed books.</p>";
 
} else {
    // Close the statement before preparing a new one
    $stmtCheckStudentBooks->close();

    // Delete the record from the student table
    $sqlDeleteFromStudentTable = "DELETE FROM student WHERE s_id = ?";
    $stmtDeleteFromStudentTable = $conn->prepare($sqlDeleteFromStudentTable);

    if ($stmtDeleteFromStudentTable === false) {
        die("Error in preparing delete statement: " . $conn->error);
    }

    $stmtDeleteFromStudentTable->bind_param("i", $studentId);

    if (!$stmtDeleteFromStudentTable->execute()) {
        die("Error in executing delete statement: " . $stmtDeleteFromStudentTable->error);
    }

    echo "<p class='success-msg' >Record deleted Successfully!</p>";
   

    // Check if the statement exists before attempting to close it
    if ($stmtDeleteFromStudentTable) {
        $stmtDeleteFromStudentTable->close();
    }
}

$conn->close();
?>
<script>
    // Redirect to another page 
    setTimeout(function () {
        window.location.href = 'studentRecord.php';
    },2000); // 2000 milliseconds = 1 second
</script>
<?php require_once('footer.php'); ?>
