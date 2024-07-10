<?php
session_start();


$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';


$data = [
    'isLoggedIn' => $isLoggedIn,
    'username' => $username
];


header('Content-Type: application/json');
echo json_encode($data);
?>
