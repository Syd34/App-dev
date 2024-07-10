<?php
session_start();
include_once '../../BACK/includes/db_connection.php';


if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}


$input = json_decode(file_get_contents('php://input'), true);
$tableId = $input['tableId'];
$hours = intval($input['hours']);
$minutes = intval($input['minutes']);
$username = $_SESSION['username'];


if ($username && $tableId && $hours >= 0 && $minutes >= 0 && $minutes <= 59) {
    try {
        $conn = getConnection();

        
        $stmt = $conn->prepare("INSERT INTO reservations (username, table_id, hours, minutes) VALUES (:username, :tableId, :hours, :minutes)");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':tableId', $tableId, PDO::PARAM_INT);
        $stmt->bindParam(':hours', $hours, PDO::PARAM_INT);
        $stmt->bindParam(':minutes', $minutes, PDO::PARAM_INT);

        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }

        
        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>