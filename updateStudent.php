<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    require_once('dbConnection.php');
    
    $updateData = json_decode(file_get_contents("php://input"), true);

    foreach ($updateData as $id => $fields) {
        // Construct SQL SET clause dynamically
        $setClauses = [];
        $values = [];
        foreach ($fields as $column => $value) {
            $setClauses[] = "$column = ?";
            $values[] = $value;
        }

        // Add student ID to the values array
        $values[] = $id;

        // Use prepared statement to prevent SQL injection
        $sql = "UPDATE student SET " . implode(", ", $setClauses) . " WHERE s_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error in preparing statement: " . $conn->error);
        }

        // Bind parameters
        $types = str_repeat('s', count($values)); // 's' for string
        $stmt->bind_param($types, ...$values);

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
        }

        $stmt->close();
    }

    $conn->close();

    echo "Data updated successfully!";
}
?>
