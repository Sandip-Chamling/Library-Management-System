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
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();

    //Redirect to the main page after submission
    header('Location:issue.php');
    exit();
}
?>