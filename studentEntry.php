<?php

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Initialize message and messageType
$message = '';
$messageType = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once('dbConnection.php');

    // Process and save data to the database
    $studentName = $_POST['studentName'];
    $studentAddress = $_POST['studentaddress']; 
    $phoneNo = $_POST['phoneNo'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];

    // SQL query for insertion with prepared statement
    $sql = "INSERT INTO student ( name, address, contact_number,semester,year) 
            VALUES ( ?, ?, ?, ?,?)";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $studentName, $studentAddress, $phoneNo,$semester,$year);

    // Execute the statement
    if ($stmt->execute()) {
        setMessage ('Record inserted successfully', 'success');
    } else {
        setMessage("Error: " . $stmt->error, 'error');
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();
    
    
}
function setMessage($message, $messageType) {
    echo "<script>
        var message = '$message';
        var messageType = '$messageType';
    </script>";
}
require_once('header.php');
?>
<style >
  .li2 a{
    color:#4b68e8;
  }
</style>
<div id="messageContainer" class="popup" style="height: 50px; top: 35%;">
    <span class="close" onclick="closePopup('messageContainer')">&times;</span>
    <p id="messageText"></p>
</div>


    <div class="form-container">
        <h2 class="h29">Enter Student's Data</h2>
        <hr size="3px" color="black">
        <form class="form2" action="studentEntry.php" method="post" onsubmit="return validateForm()">

            <label for="studentName">Student Name:</label>
            <input type="text" id="studentName" name="studentName" required>

            <label for="studentaddress">Address:</label>
            <input type="text" id="studentaddress" name="studentaddress" required>

            <label for="phoneNo">Phone Number:</label>
            <input type="text" id="phoneNo" name="phoneNo">

            <label for="semester">Semester:</label>
            <select id="semester" name="semester">
                <option value="1st">1st Semester</option>
                <option value="2nd">2nd Semester</option>
                <option value="3rd">3rd Semester</option>
                <option value="4th">4th Semester</option>
                <option value="5th">5th Semester</option>
                <option value="6th">6th Semester</option>
                <option value="7th">7th Semester</option>
                <option value="8th">8th Semester</option>
            </select>

            
            <label for="year">Batch:</label>
            <select name="year" id="year" style="width: 50%; margin-left: 120px;">
        <option value="2019">2019</option>
        <option value="2020">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
        <option value="2027">2027</option>
        <option value="2028">2028</option>
        <option value="2029">2029</option>
  </select>
    


            <button type="submit">Submit</button>
        </form>
    </div> 

    <script>
        // Function to open popups
        function openPopup(popupId) {
            var popup = document.getElementById(popupId);
            popup.style.display = 'block';
        }

        // Function to close popups
        function closePopup(popupId) {
            var popup = document.getElementById(popupId);
            popup.style.display = 'none';
        }

        var messageContainer = document.getElementById('messageContainer');
        var messageText = document.getElementById('messageText');

    // Check if message and messageType are set
    if (message && messageType) {
        messageText.innerHTML = message;
        messageContainer.classList.add(messageType);
        openPopup('messageContainer');
    }
    

    </script>

<script>
            function validateForm() {
        const phn = document.getElementById('phoneNo').value;
        // Price validation
        var phoneRegex = /^(\+\d{1,2}\s?)?(\(\d{3}\)|\d{3})([\s.-]?)\d{3}([\s.-]?)\d{4}$/;
        if (!phoneRegex.test(phn)) {
            alert('Invalid contact_number format. Please enter a valid contact number.');
            return false;
        }
        return true;
    }
        </script>
         <?php require_once('footer.php'); ?>
</body>
</html>
