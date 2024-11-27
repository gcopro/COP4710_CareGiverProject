<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Caregiver Community</title>
    <link rel="stylesheet" href="/COP4710_CareGiverProject/front/styling/style_sign_up.css"> <!-- Link to your CSS file -->
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
