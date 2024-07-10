<?php
session_start();
include_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $contactNumber = $_POST['contactNumber'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        die("Passwords do not match");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, middle_name, last_name, contact_number, email, username, password)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$firstName, $middleName, $lastName, $contactNumber, $email, $username, $hashedPassword]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['username'] = $username;
        echo "<script>
                alert('Signup successful! You will be redirected to the login page.');
                window.location.href = '../../FRONT/ACCOUNT/account.html';
              </script>";
    } else {
        echo "Error: Signup failed.";
    }
}
?>
