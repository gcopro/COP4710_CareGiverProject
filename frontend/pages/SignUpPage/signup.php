<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// set name fields and POST data
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password  = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    //DEBUG
    print_r($_POST); 

    // make all everything is inputted
    if(empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) ){
        echo "Fields required";
        exit();
    }
    // make sure confirmed password matches
    if($password != $confirmPassword){ echo "Passwords do not match";  exit(); }
    // for secure password
    $hashPass = password_hash($password,PASSWORD_BCRYPT);
    
    // insert data with prepared statement
    $sql = "INSERT INTO member(firstName,lastName,email,password) VALUES(?,?,?,?)";

    //prepare statement
    $stmt = $conn->prepare($sql);
    //error check : connection
    if(!$stmt){ echo "Failed to prepare statement" . $conn->error; exit(); }
    // bind the parameters
    $stmt->bind_param("ssss",$firstName,$lastName,$email,$password);
    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../LoginPage/login_page.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
$conn->close();
?>
