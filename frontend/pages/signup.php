<?php
// Include the connection to db
include '../backend/conn.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $firstName = trim(htmlspecialchars($_POST['firstName']));
    $lastName = trim(htmlspecialchars($_POST['lastName']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate inputs
    $errors = [];
    
    if(empty($firstName) || empty($lastName)) {
        $errors[] = "Name fields cannot be empty";
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if(strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    
    if($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Check if email already exists
    $checkEmail = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    $stmt->close();

    if(empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL query
        $sql = "INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Redirect to login page upon successful signup
            header("Location: /COP4710_CareGiverProject/frontend/pages/LoginPage/login.php");
            exit();
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }

    // If there are errors, display them
    if(!empty($errors)) {
        foreach($errors as $error) {
            echo "<div class='error-message'>$error</div>";
        }
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Caregiver Community</title>
    <link rel="stylesheet" href="../frontend/styling/style_sign_up.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <div class="container">
            <h1>Create an Account</h1>
        </div>
    </header>

    <!-- Sign Up Form Section -->
    <section class="signup-section">
        <div class="container">
            <form id="signupForm" action="backend/signup" method="POST"> <!-- Backend action will handle this -->
                <div class="input-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
                </div>
                <div class="input-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="input-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div>

                <!-- Error Message Section (placeholder for backend response) -->
                <div id="error-message" class="error-message">
                    <!-- This will display an error message from the backend, e.g. "Passwords do not match" -->
                </div>

                <div class="form-actions">
                    <button type="submit" class="cta-button">Sign Up</button>
                    <p>Already have an account? <a href="/COP4710_CareGiverProject/frontend/pages/LoginPage/login_page.html">Log In</a></p>
                </div>
            </form>
        </div>
    </section>


</body>
</html>
