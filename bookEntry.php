<?php

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    require_once('dbConnection.php');

    // Process and save data to the database
    $bookcode = $_POST['bookcode'];
    $title = $_POST['title']; 
    $author= $_POST['author'];
    $publication = $_POST['publication'];
    $price = $_POST['price'];
    $semester = $_POST['semester'];


    // SQL query for insertion with prepared statement
    $sql = "INSERT INTO book ( book_code, title,author,publication,price,semester) 
            VALUES ( ?, ?, ?, ?,?,?)";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $bookcode,$title,$author,$publication,$price,$semester);

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
  .li3 a{
    color:#4b68e8;
  }
</style>

<div id="messageContainer" class="popup" style="height: 50px; top: 35%;">
    <span class="close" onclick="closePopup('messageContainer')">&times;</span>
    <p id="messageText"></p>
</div>



    <div class="form-container" style="padding-top:20px; height: 357px;" >
        <form class="form2" action="bookEntry.php" method="post" onsubmit="return validateForm()">

            <label for="bookcode">Book Code:</label>
            <input type="text" id="bookcode" name="bookcode" required>

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author">

            <label for="publication">Publication:</label>
            <input type="text" id="publication" name="publication">

            <label for="price">Price:</label>
            <input type="text" id="price" name="price">

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
        const price = document.getElementById('price').value;
        const code = document.getElementById('bookcode').value;
        // Price validation
        var priceRegex = /^\d+(\.\d+)?$/;
        var codeRegex = /^[0-9]+$/;
        if (!(priceRegex.test(price)&&(codeRegex.test(code)))) {
            alert('Error: Either Invalid code or price  format. Please enter a valid format.');
            return false;
        }
        return true;
    }
        </script>
        <?php require_once('footer.php'); ?>
</body>
</html>
