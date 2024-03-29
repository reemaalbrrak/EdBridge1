
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduBridge Login / Sign Up form</title>
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="styleSignup.css">
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="EduBridge Logo" class="center-image">

        <form class="form" id="SignUp" method="post" action="DB.php">
            
            <h1 class="form__title">Sign Up</h1>
            <div class="form__message form__message--error"></div>

            <div class="form__input-group">
                <input type="text" class="form__input" id="Fname" autofocus placeholder="First Name" name="Fname">
            </div>
            <div class="form__input-group">
                <input type="text" class="form__input" id="Lname" autofocus placeholder="Last Name" name="Lname">
            </div>

            <div class="form__input-group">
        <select id="role" class="form__input" name="role">
            <option value="role">Role</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>
                <div class="form__input-error-message" id="role-error"></div>
            </div>
            <div class="form__input-group">
                <input type="text" class="form__input" id="email" autofocus placeholder="Email" name="email">
                <div class="form__input-error-message" id="email-error"></div>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" id="password" placeholder="Password" name="password">
                <div class="form__input-error-message" id="password-error"></div>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" id="confirm-password" placeholder="Confirm Password">
                <div class="form__input-error-message" id="confirm-password-error"></div>
            </div>
            <button class="form__button" type="submit">Submit</button>
       </form>     
            <p class="form__text">
                Already have an account? <a href="login.php">Login</a>
            </p>
        
    </div>
    
    <script src="./src/main.js"></script>
    <script>document.getElementById("SignUp").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this);
    console.log("Form Data:", formData); // Log form data for debugging

    var role = document.getElementById("role").value;
    var roleError = document.getElementById("role-error");
    var email = document.getElementById("email").value;
    var emailError = document.getElementById("email-error");
    var password = document.getElementById("password").value;
    var passwordError = document.getElementById("password-error");
    var confirmPassword = document.getElementById("confirm-password").value;
    var confirmPasswordError = document.getElementById("confirm-password-error");

    // Perform form validation
    if (role === "role") {
        roleError.innerText = "Please choose a valid role.";
    } else {
        roleError.innerText = "";
    }

    // Email validation (basic pattern)
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!email.match(emailPattern)) {
        emailError.innerText = "Please enter a valid email address.";
    } else {
        emailError.innerText = "";
    }

    // Password validation (at least 10 characters, with uppercase, lowercase, number, and special character)
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/;
    if (!password.match(passwordPattern)) {
        passwordError.innerText = "Password must be at least 10 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        passwordError.innerText = "";
    }

    // Confirm password
    if (password !== confirmPassword) {
        confirmPasswordError.innerText = "Passwords do not match.";
    } else {
        confirmPasswordError.innerText = "";
    }

    // Check if all input fields are valid before proceeding with fetch
    if (!roleError.innerText && !emailError.innerText && !passwordError.innerText && !confirmPasswordError.innerText) {
        // Perform the fetch request
        fetch('DB.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Assuming the server responds with JSON
        .then(data => {
            // Handle the response from the server
            if (data.success) {
                // Successful submission, you may redirect or perform other actions
                window.location.href = "login.php";
            } else {
                // Display any error messages from the server
                roleError.innerText = data.errors.role || '';
                emailError.innerText = data.errors.email || '';
                passwordError.innerText = data.errors.password || '';
                confirmPasswordError.innerText = data.errors.confirmPassword || '';
            }
        })
        .catch(error => console.error('Error:', error));
} else {
    e.preventDefault(); // Prevent the default form submission if there are validation errors
}});

    </script>
    
</body>
</html>

