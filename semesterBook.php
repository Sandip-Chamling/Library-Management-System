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
    
    require_once('dbConnection.php');

    $semester = $_POST['selectedSemester'];
    $serialNumber = 1; // Initialize the serial number

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM book WHERE semester = ? ORDER BY title ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $semester); // 's' for string

    if (!$stmt->execute()) {
        die("Error in executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();


// Display records in tabular form with inline editing
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
                <td id='d2d' class='editable' data-column='title' data-id='{$row['book_id']}'>{$row['title']}</td>
                <td id='d3d' class='editable' data-column='book_code' data-id='{$row['book_id']}'>{$row['book_code']}</td>
                <td id='d4d' class='editable' data-column='author' data-id='{$row['book_id']}'>{$row['author']}</td>
                <td id='d5d' class='editable' data-column='publication' data-id='{$row['book_id']}'>{$row['publication']}</td>
                <td id='d6d' class='editable' data-column='price' data-id='{$row['book_id']}'>{$row['price']}</td>
                <td id='d7d' class='editable' data-column='semester' data-id='{$row['book_id']}'>{$row['semester']}</td>
                <td id='d8d' class='editable' data-column='status' data-id='{$row['book_id']}'>{$row['status']}</td>

            </tr>";
            $serialNumber++;
    }

    echo "</table>";

    // Add the Update button
    echo "<button id='updateButton'>Update</button>";
} else {
    echo " <p class='pdof'> No records found for the $semester semester</p>";
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

<script>
   document.addEventListener('DOMContentLoaded', function () {
    const editableCells = document.querySelectorAll('.editable');
    const updatedData = {}; // Store updated data

    editableCells.forEach(cell => {
        cell.addEventListener('click', function () {
            // Check if an input field already exists
            const existingInput = this.querySelector('input');
            if (existingInput) {
                existingInput.focus();
                return;
            }

            const input = document.createElement('input');
            input.value = this.innerText;
            this.innerHTML = '';
            this.appendChild(input);
            input.focus();

            input.addEventListener('blur', () => {
                this.innerText = input.value;
                updatedData[`${this.dataset.id}-${this.dataset.column}`] = input.value;
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    this.innerText = input.value;
                    // Replace commas with line breaks before storing in updatedData
                    updatedData[`${this.dataset.id}-${this.dataset.column}`] = input.value.replace(/,/g, '\n');
                }
            });
        });
    });

    // Add click event listener to the Update button
    const updateButton = document.getElementById('updateButton');
    updateButton.addEventListener('click', function () {
        // Show confirmation dialog
        const isConfirmed = confirm("Are you sure you want to update the data?");
        if (isConfirmed) {
            updateData(updatedData);
        }
    });

    function updateData(updatedData) {
        fetch('updateBook.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Changed content type
            },
            body: JSON.stringify(updatedData), // Changed the data format
        })
            .then(response => response.text())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
    }
});

</script>
<?php require_once('footer.php'); ?>
</body>
</html>
