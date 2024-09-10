<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BCA Book Management System</title>
  <link rel="stylesheet" type="text/css" href="style8.css">
  <link rel="icon" type="image/x-icon" href="favicon.jpeg">
  <style >
  .li2 a{
    color:#4b68e8;
  }
</style>
</head>

<body class="namebd5">
<header>
    <div class="div1">
      <div class="div1_1">
        <img height="130px" width="200px" src="Book.jpg">
      </div>
      <div class="div1_2">
        <h1 class="mainh1"> BCA BOOK MANAGEMENT SYSTEM</h1>
        <nav>
          <ul>
          <li><a href="dashboard.php">Home</a></li>
            <li class="li2"><a href="student.php">Student</a></li>
            <li><a href="book.php">Books</a></li>
            <li><a href="statistics.php">Statistics</a></li>
            <li><a href="issue.php">Issue_Book</a></li>
          </ul>
        </nav>
      </div>
    </div>
    </header>
<?php
// Check if the user is not logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
 
 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once('dbConnection.php');

    $search = $_POST['search'];
    $selectedYear = $_POST['year']; // Assuming you have added a 'year' field in your form

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM student WHERE name = ? AND year = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    $stmt->bind_param("si", $search, $selectedYear); // 's' for string, 'i' for integer

    if (!$stmt->execute()) {
        die("Error in executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    // Display records in tabular form
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Your existing code to display records
            echo "<div class='student-record-container'>";
            echo "<h2 class='h2d'>Detail of {$row['name']}<hr color='black' size='3px'> </h2>";
            echo "<p class='psim'><b>S_ID: </b> {$row['s_id']}</p>";
            echo "<p class='psim'><b>Name: </b> {$row['name']}</p>";
            echo "<p class='psim'><b>Address: </b> {$row['address']}</p>";
            echo "<p class='psim'><b>Phone Number: </b> {$row['contact_number']}</p>";
            echo "<p class='psim'><b>Semester: </b> {$row['semester']}</p>";
            echo "<p class='psim'><b>Batch: </b> {$row['year']}</p>";

           
            echo "<button class='bt25' onclick=\"deleteData({$row['s_id']})\">Delete</button>";
            echo "</div>";

            // Retrieve borrowed books information
         $borrowSql = "SELECT b.title,br.borrow_date,b.book_code
         FROM borrow br
         JOIN book b ON br.book_id = b.book_id
         WHERE br.s_id = ? AND br.status='Not-Returned'";
 $borrowStmt = $conn->prepare($borrowSql);
 
 if ($borrowStmt === false) {
 die("Error in preparing statement: " . $conn->error);
 }
 
 $borrowStmt->bind_param("i", $row['s_id']); // 'i' for integer
 
 if (!$borrowStmt->execute()) {
 die("Error in executing statement: " . $borrowStmt->error);
 }
 
 $borrowResult = $borrowStmt->get_result();
 
 // Display borrowed books information
 echo "<div class='book-list-container5'>";
 echo "<h2 class='books-list-label'>Borrowed Books<hr color='black' size='3px'></h2>";

// Check if there are any borrowed books
if ($borrowResult->num_rows > 0) {
    echo "<ol class='olist'>";
    while ($borrowRow = $borrowResult->fetch_assoc()) {
        echo "<li>{$borrowRow['title']}({$borrowRow['book_code']}) - Borrowed on: {$borrowRow['borrow_date']}</li>";
    }
    echo "</ol>";
} else {
    echo "<p>No borrowed books.</p>";
}
 echo "</div>";
 
 
        }

        echo "<script>
            function deleteData(studentId) {
                var confirmation = confirm('Are you sure you want to delete this record?');
                if (confirmation) {
                    window.location.href = 'deleteStudent.php?studentId=' + studentId;
                }
                return false;
            }
        </script>";
    } else {
        echo "<p class='pdif'> No records found for $search of $selectedYear .</p>";
        echo" <script>
    // Redirect to another page 
    setTimeout(function(){
        window.location.href = 'studentRecord.php';
    }, 1000); // 1000 milliseconds = 1 second
</script>";

    }

    $stmt->close();
    $conn->close();
}
?>
 <?php require_once('footer.php'); ?>
</body>
</html>


