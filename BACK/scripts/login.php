<?php
session_start();
include_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare(getConnection(), $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
      
        if (password_verify($password, $row['password'])) {
            
            $_SESSION['username'] = $username;
            echo "<script>
                    alert('Login successful! Redirecting to the home page.');
                    window.location.href = '../../FRONT/HOME/home.html';
                  </script>";
            exit();
        } else {
            
            echo "<script>
                    alert('Incorrect password. Please try again.');
                    window.location.href = '../../FRONT/ACCOUNT/account.html';
                  </script>";
        }
    } else {
        
        echo "<script>
                alert('No user found with this username. Please try again.');
                window.location.href = '../../FRONT/ACCOUNT/account.html';
              </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close(getConnection());
}
?>
