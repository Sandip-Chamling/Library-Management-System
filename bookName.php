<?php require_once('header.php'); ?>
<style >
  .li3 a{
    color:#4b68e8;
  }
</style>

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
    $serialNumber = 1;

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM book where title = ?";
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
        echo "<table class='tb1' border='1'  cellspacing='0'>
                <tr>
                    <th>SN</th>
                    <th>Title</th>
                    <th>Book_code:</th>
                    <th>Author</th>
                    <th>Publication</th>
                    <th>Price(in Rs.)</th>
                    <th>Semester</th>
                    <th>Status</th>
                </tr>";
    
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td >{$serialNumber}</td>
                    <td >{$row['title']}</td>
                    <td >{$row['book_code']}</td>
                    <td >{$row['author']}</td>
                    <td >{$row['publication']}</td>
                    <td >{$row['price']}</td>
                    <td >{$row['semester']}</td>
                    <td >{$row['status']}</td>
    
                </tr>";
                $serialNumber++;
        }
    
        echo "</table>";
    }else {
        echo " <p class='pdof'> No records found for the searched name.</p>";
       echo" <script>
        // Redirect to another page 
        setTimeout(function(){
            window.location.href = 'bookRecord.php';
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


