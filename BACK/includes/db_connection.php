<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once 'C:/xampp/htdocs/App-dev/BACK/config/db_config.php';


try {
    
    $conn = getConnection(); 
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
