<?php
include_once 'DB3/DB.php';

$dbServername = "server184"; // servername
$dbUsername = "theeazqk_EduBridgeDB"; // username
$dbPassword = "TheEduBridge66"; // password
$dbName = "theeazqk_EduBridge"; // database name

// Create connection
$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Users (Fname, Lname, role, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $Fname, $Lname, $role, $email, $password);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['database' => $stmt->error]]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'errors' => ['method' => 'Invalid request method']]);
}

// Close the connection
$conn->close();
