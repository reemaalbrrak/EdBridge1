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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data['email'];
    $password = $data['password'];

    // Validate and sanitize input data
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if email is valid
    if (!$email) {
        $response = ['success' => false, 'errors' => ['email' => 'Invalid email']];
    } else {
        $stmt = $conn->prepare("SELECT role FROM Users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $role = $row['role'];

            $response = ['success' => true, 'role' => $role];
        } else {
            $response = ['success' => false, 'errors' => ['login' => 'Invalid credentials']];
        }

        $stmt->close();
    }

    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduBridge Login / Sign Up form</title>
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="EduBridge Logo" class="center-image">
        <form class="form" id="login-form" method="post">
            <h1 class="form__title">Login</h1>
            <?php if (isset($loginError) || isset($roleError)): ?>
                <div class="form__message form__message--error"><?php echo isset($loginError) ? $loginError : $roleError; ?></div>
            <?php endif; ?>
            <div class="form__input-group">
                <input type="text" class="form__input" id="email" name="email" autofocus placeholder="Email">
                <div class="form__input-error-message" id="email-error"><?php echo isset($emailError) ? $emailError : ''; ?></div>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" id="password" name="password" autofocus placeholder="Password">
                <div class="form__input-error-message" id="password-error"></div>
            </div>
            <button class="form__button" type="submit">Submit</button>
            
            <p class="form__text">
                <a href="passwordReset.html" class="form__link">Forgot Password</a>
            </p>
            <p class="form__text">
                Don't have an account? <a href="signup.php">Sign up</a>
            </p>
        </form>
    </div>
    
    <script>
        document.getElementById("login-form").addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent the default form submission
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var emailError = document.getElementById("email-error");
            var passwordError = document.getElementById("password-error");

            // Reset error messages
            emailError.innerText = "";
            passwordError.innerText = "";

            // Perform basic client-side validation
            if (!email.trim() || !password.trim()) {
                emailError.innerText = "Email and password are required.";
                return;
            }

            // Continue with server-side validation and login
            fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Use 'application/json' for JSON data
                },
                body: JSON.stringify({ email, password }), // Convert data to JSON
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Successful login
                    if (data.role === 'teacher') {
                        window.location.href = "TMainPage.html";
                    } else if (data.role === 'student') {
                        window.location.href = "SMainPage.html";
                    } else {
                        // Handle other roles if needed
                        console.error('Invalid role:', data.role);
                    }
                } else {
                    // Display server-side error messages
                    emailError.innerText = data.errors.email || '';
                    passwordError.innerText = data.errors.password || '';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
