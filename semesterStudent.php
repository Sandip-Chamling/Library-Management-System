

<?php

// Check if the user is not logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
require_once('header.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once('dbConnection.php');

    $semester = $_POST['selectedSemester'];
    $selectedYear = $_POST['selectedyear'];
    $serialNumber = 1; // Initialize the serial number


    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM student WHERE semester = ? AND year = ? ORDER BY name ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    $stmt->bind_param("si", $semester, $selectedYear); // 's' for string, 'i' for integer

    if (!$stmt->execute()) {
        die("Error in executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();


// Display records in tabular form with inline editing
if ($result->num_rows > 0) {
    echo "<table class='tb1' border='1'  cellspacing='0'>
            <tr>
                <th>SN</th>
                <th>Student_Name</th>
                <th>Student_ID</th>          
                <th>Address</th>
                <th>Contact Number</th>
                <th>Semester</th>
                <th>Batch</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td id='d0d'>{$serialNumber}</td>
                <td id='d2d' class='editable' data-column='name' data-id='{$row['s_id']}'>{$row['name']}</td>
                <td id='d1d' class='editable' data-column='id' data-id='{$row['s_id']}'>{$row['s_id']}</td>
                <td id='d3d' class='editable' data-column='address' data-id='{$row['s_id']}'>{$row['address']}</td>
                <td id='d4d' class='editable' data-column='contact_number' data-id='{$row['s_id']}'>{$row['contact_number']}</td>
                <td id='d5d' class='editable' data-column='semester' data-id='{$row['s_id']}'>{$row['semester']}</td>
                <td id='d6d' class='editable' data-column='year' data-id='{$row['s_id']}'>{$row['year']}</td>
            </tr>";
            $serialNumber++;
    }

    echo "</table>";

    // Add the Update button
    echo "<button id='updateButton'>Update</button>";
} else {
    echo " <p class='pdof'> No records found for the $semester semester and $selectedYear </p>";
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
<style >
  .li2 a{
    color:#4b68e8;
  }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editableCells = document.querySelectorAll('.editable');
        let updatedData = {}; // Store updated data for all cells

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
                    // Store the updated data for this cell
                    updatedData[this.dataset.id] = updatedData[this.dataset.id] || {};
                    updatedData[this.dataset.id][this.dataset.column] = input.value;
                });

                input.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        this.innerText = input.value;
                        // Store the updated data for this cell
                        updatedData[this.dataset.id] = updatedData[this.dataset.id] || {};
                        updatedData[this.dataset.id][this.dataset.column] = input.value;
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
            fetch('updateStudent.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(updatedData),
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                // Refresh the page or handle success accordingly
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>

<?php require_once('footer.php'); ?>
</body>
</html>
