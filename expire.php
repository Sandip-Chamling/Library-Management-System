<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

require_once('dbConnection.php');
$serialNUmber=1;
// Fetch records from the borrow table where borrow date is more than 6 months ago
$sql = "SELECT b.title, b.book_code, br.borrow_date, s.name, s.s_id, s.contact_number, s.semester,
            f.overdue_days,
            f.amount AS fine
        FROM Fine f 
        JOIN borrow br ON f.borrow_id = br.borrow_id
        JOIN book b ON br.book_id = b.book_id 
        JOIN student s ON br.s_id = s.s_id
        WHERE f.overdue_days > 0
        ORDER BY f.amount DESC"; 

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
    echo "<table class='tb12' border='1'>
            <tr>
                <th>SN</th>
                <th>Book_Title</th>
                <th>Book_Code</th>
                <th>Borrow_Date</th>
                <th>Borrower_Name</th>
                <th>Borrower_ID</th>
                <th>Contact_Number</th>
                <th>Semester</th>
                <th>Overdue_Days</th>
                <th>Fine(Rs.)</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$serialNUmber}</td>
            <td>{$row['title']}</td>
            <td>{$row['book_code']}</td>
            <td>{$row['borrow_date']}</td>
            <td>{$row['name']}</td>
            <td>{$row['s_id']}</td>
            <td>{$row['contact_number']}</td>
            <td>{$row['semester']}</td>
            <td>{$row['overdue_days']}</td>
            <td>{$row['fine']}</td>
        </tr>";
        $serialNUmber++;
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
