<?php
session_start();
include_once '../../BACK/includes/db_connection.php';

$isLoggedIn = isset($_SESSION['username']) ? true : false;
$username = $isLoggedIn ? $_SESSION['username'] : '';


$reservations = [];


$sql = "SELECT table_id, remaining_time FROM reservations WHERE status = 'active'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    
    while ($row = mysqli_fetch_assoc($result)) {
        $reservation = [
            'table_id' => $row['table_id'],
            'remaining_time' => $row['remaining_time']
            
        ];
        $reservations[] = $reservation;
    }
}


mysqli_close($conn);


header('Content-Type: application/json');
echo json_encode($reservations);
?>
