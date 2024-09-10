<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BCA Book Management System</title>
  <link rel="stylesheet" type="text/css" href="style8.css">
  <link rel="icon" type="image/x-icon" href="favicon.jpeg">
  <style >
  .li3 a{
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
            <li><a href="student.php">Student</a></li>
            <li class="li3"><a href="book.php">Books</a></li>
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
    // Database connection
    require_once('dbConnection.php');

    $search = $_POST['search'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM book where book_code = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $search); // 's' for string, 'i' for integer

    if (!$stmt->execute()) {
        die("Error in executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    // Display records in tabular form
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Your existing code to display records
            echo "<div class='book-record-container'>";
            echo "<h2 class='h2d'>Detail of book:<hr color='black' size='3px'> </h2>";
            echo "<p class='psim'><b>Book_ID:</b> {$row['book_id']}</p>";
            echo "<p class='psim'><b>Title:</b> {$row['title']}</p>";
            echo "<p class='psim'><b>Book_code:</b> {$row['book_code']}</p>";
            echo "<p class='psim'><b>Author:</b> {$row['author']}</p>";
            echo "<p class='psim'><b>Publication:</b> {$row['publication']}</p>";
            echo "<p class='psim'><b>Price:</b> Rs.{$row['price']}</p>";
            echo "<p class='psim'><b>Semester:</b> {$row['semester']}</p>";
            echo "<p class='psim'><b>Status:</b> {$row['status']}</p>";

           
            echo "<button class='bt25' onclick=\"deleteData({$row['book_id']})\">Delete</button>";
            echo "</div>";

    // Retrieve borrowed Status information
    $bookId = $row['book_id'];
    $borrowInfoSql = "SELECT b.*, s.name as borrower_name, f.overdue_days, f.remaining_days, f.amount 
                      FROM borrow b 
                      JOIN student s ON b.s_id = s.s_id 
                      LEFT JOIN Fine f ON b.borrow_id = f.borrow_id
                      WHERE b.book_id = ? 
                      AND b.status = 'Not-Returned'";
    $borrowInfoStmt = $conn->prepare($borrowInfoSql);

    if ($borrowInfoStmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    $borrowInfoStmt->bind_param("i", $bookId);

    if (!$borrowInfoStmt->execute()) {
        die("Error in executing statement: " . $borrowInfoStmt->error);
    }

    $borrowResult = $borrowInfoStmt->get_result();

    echo "<div class='borrow-status-container5'>";
    echo "<h2 class='books-list-label'>Borrowed Status:<hr color='black' size='3px'></h2>";

    if ($borrowResult->num_rows > 0) {
        while ($borrowRow = $borrowResult->fetch_assoc()) {
            echo "<ul class='olist'>";
            echo "<li>Borrower Name: {$borrowRow['borrower_name']}</li>";
            echo "<li>Borrowed Date: {$borrowRow['borrow_date']}</li>";
            echo "<li>Remaining Days: {$borrowRow['remaining_days']}</li>";
            echo "<li>Overdue Days: {$borrowRow['overdue_days']}</li>";
            echo "<li>Fine: Rs. {$borrowRow['amount']}</li>";
            echo "</ul>";
        }
            echo "<button class='bt1' onclick=\"returnBookConfirmation({$row['book_id']})\">Mark as Returned</button>";
        } else {
            echo "<p>No borrow status.</p>";
        }
        echo "</div>";

        $borrowInfoStmt->close();

 echo "</div>";
 
 
        }

        echo "<script>
            function deleteData(bookId) {
                var confirmation = confirm('Are you sure you want to delete this record?');
                if (confirmation) {
                    window.location.href = 'deleteBook.php?bookId=' + bookId;
                }
                return false;
            }
        </script>";

    } else {
        echo "<p class='pdif'> No records found for book($search).</p>";
        echo" <script>
    // Redirect to another page 
    setTimeout(function(){
        window.location.href = 'bookRecord.php';
    }, 1000); // 1000 milliseconds = 1 second
</script>";

    }
   echo" <script>
        function returnBookConfirmation(bookId) {
            var confirmation = confirm('Are you sure you want to mark this book as returned?');
            if (confirmation) {
                // User clicked OK, perform the update
                window.location.href = 'markAsReturned.php?bookId=' + bookId;
            }
            return false;
        }
    </script>";


    $stmt->close();
    $conn->close();
}
?>
<?php require_once('footer.php'); ?>

</body>
</html>



