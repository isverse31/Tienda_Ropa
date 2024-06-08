<?php
session_start();
header('Content-Type: application/json');

$response = array(
    'loggedIn' => false,
    'email' => ''
);

if (isset($_SESSION['email'])) {
    $response['loggedIn'] = true;
    $response['email'] = $_SESSION['email'];
}

echo json_encode($response);
?>
