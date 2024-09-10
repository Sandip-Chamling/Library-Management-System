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
$sql = "SELECT br.borrow_date, b.title, b.book_code, s.name,s.s_id, s.semester FROM borrow br
        JOIN book b ON br.book_id = b.book_id
        JOIN student s ON br.s_id = s.s_id ORDER BY borrow_date ASC";

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
                    <th>Borrowed_Date</th>
                    <th>Book_Title</th>
                    <th>Book_Code</th>
                    <th>Borrower_Name</th>
                    <th>Borrower_ID</th>
                    <th>Semester</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td> {$serialnumber}</td>
                    <td>{$row['borrow_date']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['book_code']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['s_id']}</td>
                    <td>{$row['semester']}</td>
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
