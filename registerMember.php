<?php
//connection to DB
include 'db.php';

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
    // for secure password (interesting fucntion)
    $hashPass = password_hash($password,PASSWORD_BCRYPT);
    // insert data '?' use bind param for security (good practice. Project doesnt mention security so not neccessary)
    $sql = "
        INSERT INTO member(firstName,lastName,email,password)
        VALUES(?,?,?,?);
    ";

    // DEBUGG
    $sql = "INSERT INTO member (firstName, lastName, email, password) VALUES ('test', 'user', 'test@example.com', 'testpass')";
    if ($con->query($sql) === TRUE) {
        echo "Test data inserted successfully";
    } else {
        echo "Error: " . $con->error;
    }


    //prepare statment
    $stmt = $con->prepare($sql);
    //error check : connection
    if(!$stmt){ echo "Failed to prepare statment" . $con->error; exit(); }
    // use bind the place holders (?)
    $stmt->bind_param("ssss",$firstName,$lastName,$email,$hashPass);
    // Execute the statement
    if ($stmt->execute()) {
        header("Location: login_page.html");
    } else {
        echo "Error: " . $stmt->error;
    }
}
$stmt->close();
$con->close();
?>