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

require_once('dbConnection.php');
// Function to open popups
function openPopup($popupId) {
    echo "<script>
            var popup = document.getElementById('$popupId');
            popup.style.display = 'block';
          </script>";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $studentInput = $_POST['student']; // Student input (name + ID)
    $bookCode = $_POST['book']; // Book Code
    $issueDate = $_POST['issueDate'];

    // Extract student name and ID
    $studentInfo = explode(' (', rtrim($studentInput, ')'));

    // Check if the primary key is present
    if (count($studentInfo) === 2) {
        list($studentName, $studentID) = $studentInfo;

        // Get Student ID
        $studentIDSql = "SELECT s_id FROM student WHERE name = ? AND s_id = ?";
        $studentIDStmt = $conn->prepare($studentIDSql);
        $studentIDStmt->bind_param("ss", $studentName, $studentID);
        $studentIDStmt->execute();
        $studentIDResult = $studentIDStmt->get_result();
        $studentIDRow = $studentIDResult->fetch_assoc();

        if ($studentIDRow !== null) {
            $studentID = $studentIDRow['s_id'];
    
        // Get Book ID and status
        $bookIDStatusSql = "SELECT book_id, `status` FROM book WHERE book_code = ?";
        $bookIDStatusStmt = $conn->prepare($bookIDStatusSql);
        $bookIDStatusStmt->bind_param("s", $bookCode);
        $bookIDStatusStmt->execute();
        $bookIDStatusResult = $bookIDStatusStmt->get_result();
        $bookIDStatusRow = $bookIDStatusResult->fetch_assoc();

        if ($bookIDStatusRow !== null) {
            $bookID = $bookIDStatusRow['book_id'];
            $bookStatus = $bookIDStatusRow['status'];

            if ($bookStatus === 'Available') {
                // Insert data into the borrow table
                $sql = "INSERT INTO borrow (s_id, book_id, borrow_date) 
                        VALUES ( ?, ?, ?)";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $studentID, $bookID, $issueDate);

                if ($stmt->execute()) {
                    // Update book status to 'Reserved'
                    $updateStatusSql = "UPDATE book SET `status` = 'Reserved' WHERE book_id = ?";
                    $updateStatusStmt = $conn->prepare($updateStatusSql);
                    $updateStatusStmt->bind_param("s", $bookID);
                    $updateStatusStmt->execute();
                    
                    setMessage ('Book issued successfully', 'success');
                } else {
                    setMessage("Error issuing book: " . $stmt->error, 'error');
                }

                $stmt->close();
                $updateStatusStmt->close();
                
            } else {
                setMessage ('Book is not available or already reserved', 'ststus');
            }
        } else {
        setMessage ('Book not found', 'bookFinding');
        }
        
        $bookIDStatusStmt->close();
        } else {
            setMessage('Student not found', 'studentFinding');
        }

        $studentIDStmt->close();
    } else {
        setMessage('Student not found', 'stdhndl');
    }
}

function setMessage($message, $messageType) {
    echo "<script>
        var message = '$message';
        var messageType = '$messageType';
    </script>";
}
?>
<?php require_once('header.php'); ?>

<style >
  .li5 a{
    color:#4b68e8;
  }
</style>
    <div id="messageContainer" class="popup" style="height: 50px; top: 35%;">
    <span class="close" onclick="closePopup('messageContainer')">&times;</span>
    <p id="messageText"></p>
</div>

    <!-- Popup for adding new student -->
    <div id="addStudentPopup" class="popup">
        <span class="close" onclick="closePopup('addStudentPopup')">&times;</span>
            <!-- Add your fields for adding a new student here -->
            <form class="form21" action="popupStudent.php" method="post" onsubmit="return validateSForm()" >

<label for="studentName">Student Name:</label>
<input type="text" id="studentName" name="studentName" required>

<label for="studentaddress">Address:</label>
<input type="text" id="studentaddress" name="studentaddress" required>

<label for="phoneNo">Phone Number:</label>
<input type="text" id="phoneNo" name="phoneNo">

<label for="semester">Semester:</label><br>
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
    <select name="year" id="year">
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
    
<br>
  <button class="addst" type="submit" >Add Student</button>
        </form>
    </div>

    <!-- Popup for adding new book -->
    <div id="addBookPopup" class="popup">
        <span class="close" onclick="closePopup('addBookPopup')">&times;</span>
        <form class="form21" action="popupBook.php" method="post" onsubmit="return validateBForm()">

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


            <button class="addst" type="submit" >Add Book</button>
        </form>
        
    </div>

            <div class="form1-container">
                <h2>Issue Book</h2>
                <hr size="3px" color="black">
                <form class="form3" action="issue.php" method="post">

                    <label for="student">Student Name:</label><br>
                    <input type="text" id="student" name="student" required list="students">
                    <button class="addst" type="button" onclick="openPopup('addStudentPopup')">Add Student</button><br><br>

                    <label for="book">Book Code:</label><br>
                    <input type="text" id="book" name="book" required list="books">
                    <button class="addst" type="button" onclick="openPopup('addBookPopup')">Add Book</button><br><br>

                    <label for="issueDate">Issue Date:</label><br>
                    <input type="date" id="issueDate" name="issueDate" required><br><br>

                
                    <datalist id="students">
                  <!-- Fetch and populate student names with primary key (s_id) dynamically from the database -->
                        <?php
                         $studentNamesSql = "SELECT name,s_id FROM student";
                         $studentNamesResult = $conn->query($studentNamesSql);
                          while ($studentNameRow = $studentNamesResult->fetch_assoc()) {
                          echo "<option value='{$studentNameRow['name']} ({$studentNameRow['s_id']})'>";
                           }
                          ?>
                    </datalist>

                    <datalist id="books">
                        <!-- Fetch and populate book codes dynamically from the database -->
                        <?php
                        $availableBooksSql = "SELECT book_code FROM book WHERE `status` = 'Available'";
                        $availableBooksResult = $conn->query($availableBooksSql);
                        while ($bookCodeRow = $availableBooksResult->fetch_assoc()) {
                            echo "<option value='{$bookCodeRow['book_code']}'>";
                        }
                        ?>
                    </datalist>

                    <button class="sub" type="submit">Submit</button>
                    
                    
                </form>
            </div>
        </div>
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
            function validateSForm() {
        const phn = document.getElementById('phoneNo').value;
        // Price validation
        var phoneRegex = /^(\+\d{1,2}\s?)?(\(\d{3}\)|\d{3})([\s.-]?)\d{3}([\s.-]?)\d{4}$/;
        if (!phoneRegex.test(phn)) {
            alert('Invalid contact_number format. Please enter a valid contact number.');
            return false;
        }
        return true;
    }
        function validateBForm() {
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

