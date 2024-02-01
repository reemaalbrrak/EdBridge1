<?php
include_once 'DB3/DB.php';

session_start(); // Make sure to start the session

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming your Users table has fields: user_id, first_name, last_name, email, phone_number, bio

    // Extract user data from the form
    $user_id = $_SESSION['id']; // Assuming you have the user ID in the session
    $first_name = $_POST['Fname'];
    $last_name = $_POST['Lname'];
    $email = $_POST['email'];
    $phone_number = $_POST['num'];
    $bio = $_POST['bio'];

    // Save the user data to the database
    $sql = "UPDATE Users SET Fname=?, Lname=?, email=?, num=?, bio=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisi", $first_name, $last_name, $email, $phone_number, $bio, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        // Fetch the updated user data and return it as JSON
        $fetchSql = "SELECT * FROM Users WHERE id=?";
        $fetchStmt = $conn->prepare($fetchSql);
        $fetchStmt->bind_param("i", $user_id);
        $fetchStmt->execute();
        $result = $fetchStmt->get_result();
        $userData = $result->fetch_assoc();
        $fetchStmt->close();

        echo json_encode($userData);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ProfilePage.css">
    <title>User Profile</title>
</head>
<body>

<div id="container">

    <!-- Sidebar -->
    <div id="sidebar">
    <div id="sidebar-profile-container">
        <div id="sidebar-profile-icon">
            <img id="sidebar-profile-image" src="default-profile-image.jpg" alt="Profile Image">
        </div>
        <div id="sidebar-profile-details">
            <span id="sidebar-Fname"></span>
            <span id="sidebar-Lname"></span>
        </div>
    </div>
    <button onclick="window.location.href='TMainPage.html'">Main page</button>
    <button onclick="window.location.href='Help.html'">Help</button>
    <button onclick="window.location.href='login.php'">Logout</button>
</div>

    <!-- Content -->
    <div id="content">
        <div id="profile-container">
            <div id="top-profile-container">
                <label for="profile-image-input" id="profile-label">
                    <div id="profile-icon">
                        <div id="profile-icon-frame">
                            <img id="profile-image" src="default-profile-image.jpg" alt="Profile Image">
                        </div>
                    </div>
                </label>
                <button id="change-image">Change Image</button>
                <input type="file" id="profile-image-input" accept="image/*" style="display: none;">
            </div>
        </div>
        <form id="profile-form">
            <div class="form-group">
                <label for="first-name">First Name:</label>
                <input type="text" id="Fname" name="Fname">
            </div>
            <div class="form-group">
                <label for="last-name">Last Name:</label>
                <input type="text" id="Lname" name="Lname">
            </div>
            <div class="form-group">
                <label for="email-address">Email Address:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="phone-number">Phone Number:</label>
                <input type="tel" id="num" name="num">
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" rows="5"></textarea>
            </div>
            <button type="button" id="save-button">Save</button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Fetch user data when the page loads
    fetchUserData();

    // Function to handle file input change event for the profile page
    document.getElementById("profile-image-input").addEventListener("change", function (event) {
        handleImageChange(event, "profile-image");
    });

    // Function to handle "Change Image" button click event
    document.getElementById("change-image").addEventListener("click", function () {
        document.getElementById("profile-image-input").click();
    });

    // Function to handle save button click event
    document.getElementById("save-button").addEventListener("click", function () {
        saveUserData();
    });
});

// Fetch user data from the server
function fetchUserData() {
    // Fetch user data from the server using your PHP script
    fetch('ProfilePage.php')
        .then(response => response.json())
        .then(data => {
            // Populate the user data in the form
            // Populate the user data in the form
            document.getElementById("Fname").value = data.Fname || '';
            document.getElementById("Lname").value = data.Lname || '';
            document.getElementById("email").value = data.email || '';
            document.getElementById("num").value = data.num || '';
            document.getElementById("bio").value = data.bio || '';

            // Update the profile details in the sidebar
            document.getElementById("sidebar-Fname").innerText = data.Fname || '';
            document.getElementById("sidebar-Lname").innerText = data.Lname || '';


            // Update the profile image
            const profileImageElement = document.getElementById("profile-image");
            profileImageElement.src = data.profile_image || 'default-profile-image.jpg';
            updateSidebarProfileIcon("profile-image");
        })
        .catch(error => console.error('Error:', error));
}

// Function to handle image change and update the specified image element
function handleImageChange(event, imageElementId) {
    const fileInput = event.target;
    const imageElement = document.getElementById(imageElementId);

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imageElement.src = e.target.result;
            updateSidebarProfileIcon(imageElementId); // Update sidebar icon
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
}

// Function to update the sidebar profile icon with the selected image
function updateSidebarProfileIcon(profileImageId) {
    const profileImageElement = document.getElementById(profileImageId);
    const sidebarProfileImageElement = document.getElementById("sidebar-profile-image");

    // Copy the source of the profile image to the sidebar profile image
    sidebarProfileImageElement.src = profileImageElement.src;
}

// Function to save user data to the server
function saveUserData() {
    const formData = new FormData(document.getElementById('profile-form'));

    // Send user data to the server for saving
    // Replace 'profile_update.php' with the actual name of your PHP script to save data
    fetch('ProfilePage.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server (you can show a success message, etc.)
            console.log(data);
            // Optionally, you can fetch and update the user data after saving
            fetchUserData();
        })
        .catch(error => console.error('Error:', error));
}
</script>
</body>
</html>
