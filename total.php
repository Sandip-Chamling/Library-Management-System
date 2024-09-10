<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

require_once('dbConnection.php');

function getTotalInfo($conn)
{
    $result = array();

    // Query to get the total number of books
    $totalBooksQuery = "SELECT COUNT(*) AS total_books FROM book";
    $totalBooksResult = $conn->query($totalBooksQuery);
    $result['totalBooks'] = $totalBooksResult->fetch_assoc()['total_books'];

    // Query to get the total number of available books
    $availableBooksQuery = "SELECT COUNT(*) AS available_books FROM book WHERE `status` = 'Available'";
    $availableBooksResult = $conn->query($availableBooksQuery);
    $result['availableBooks'] = $availableBooksResult->fetch_assoc()['available_books'];

    // Query to get the total number of reserved books
    $reservedBooksQuery = "SELECT COUNT(*) AS reserved_books FROM book WHERE `status` = 'Reserved'";
    $reservedBooksResult = $conn->query($reservedBooksQuery);
    $result['reservedBooks'] = $reservedBooksResult->fetch_assoc()['reserved_books'];

    // Query to get the total number of books in each semester
    $semesterQuery = "SELECT semester, COUNT(*) AS semester_books, 
                      SUM(CASE WHEN `status` = 'Available' THEN 1 ELSE 0 END) AS semester_available_books,
                      SUM(CASE WHEN `status` = 'Reserved' THEN 1 ELSE 0 END) AS semester_reserved_books
                      FROM book GROUP BY semester ORDER BY semester ASC";
    $semesterResult = $conn->query($semesterQuery);
    $semesterBooks = array();

    while ($row = $semesterResult->fetch_assoc()) {
        $semesterBooks[$row['semester']] = array(
            'total' => $row['semester_books'],
            'available' => $row['semester_available_books'],
            'reserved' => $row['semester_reserved_books']
        );
    }

    $result['semesterBooks'] = $semesterBooks;

    return $result;
}

// Get the total information
$totalInfo = getTotalInfo($conn);

// Close the database connection
$conn->close();
?>

<?php require_once('header.php'); ?>
<style >
  .li4 a{
    color:#4b68e8;
  }
</style>
    <div class="totaldiv">
    <h2><u>Total Information</u></h2>
    <ul class="totalul">
    <li><b>Total number of Books:</b> <?php echo $totalInfo['totalBooks']; ?></li>
    <li><b>Total number of Available books:</b> <?php echo $totalInfo['availableBooks']; ?></li>
    <li><b>Total number of Reserved books:</b> <?php echo $totalInfo['reservedBooks']; ?></li>
    </ul>

    <h2><u>Total Number of Books in Each Semester</u></h2>
    <ul class="totalul">
        <?php foreach ($totalInfo['semesterBooks'] as $semester => $count) : ?>
            <li>
                <?php echo "<b>$semester Semester : </b>$count[total]"?>
                <ul><li><?php echo "Available: $count[available]"?></li>
                    <li><?php echo "Reserved: $count[reserved]"?></li>
            </ul>   
               
            </li>
        <?php endforeach; ?>
    </ul>
    </div>
    <?php require_once('footer.php'); ?>
</body>

</html>
