<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    require_once('dbConnection.php');
    
    $updateData = json_decode(file_get_contents("php://input"), true);

    foreach ($updateData as $key => $value) {
        // Split the key into id and column
        list($id, $column) = explode('-', $key);

        // Use prepared statement to prevent SQL injection
        $sql = "UPDATE book SET $column = ? WHERE book_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error in preparing statement: " . $conn->error);
        }

        $stmt->bind_param("si", $value, $id);

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
        }

        $stmt->close();
    }

    $conn->close();

    echo "Data updated successfully!";
}
?>
