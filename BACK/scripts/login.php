<?php
session_start();
include_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                echo "<script>
                        alert('Login successful! Redirecting to the home page.');
                        window.location.href = '../../FRONT/HOME/home.php';
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
    } catch (PDOException $e) {
        echo "<script>
                alert('Database error: " . $e->getMessage() . "');
                window.location.href = '../../FRONT/ACCOUNT/account.html';
              </script>";
    }
}
?>
