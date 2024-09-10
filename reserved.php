<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

require_once('dbConnection.php');

$serialnumber=1;
// Fetch records from the borrow table
$sql = "SELECT * FROM book WHERE status = 'reserved' ORDER BY title ASC";
$result = $conn->query($sql);

?>

<?php require_once('header.php'); ?>
<style >
  .li4 a{
    color:#4b68e8;
  }
</style>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table class='tb11' border='1'>
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
                    <td> {$serialnumber}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['book_code']}</td>
                    <td>{$row['author']}</td>
                    <td>{$row['publication']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['semester']}</td>
                    <td>{$row['status']}</td>
                </tr>";
                $serialnumber++;
        }

        echo "</table>";
    } else {
        echo "<h1 class='p5'> No records found</h1>";
    }

    $conn->close();
    ?>
    <?php require_once('footer.php'); ?>
</body>
</html>
