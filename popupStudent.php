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
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();

    //Redirect to the main page after submission
    header('Location: issue.php');
    exit();
}
?>