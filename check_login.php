<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Debug information
$debug = array(
    'session_id' => session_id(),
    'session_status' => session_status(),
    'session_data' => $_SESSION
);

$response = array(
    'loggedin' => false,
    'username' => '',
    'debug' => $debug
);

// Check if user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $response['loggedin'] = true;
    $response['username'] = isset($_SESSION["full_name"]) ? $_SESSION["full_name"] : 'User';
}

// Log the response for debugging
error_log('Login check response: ' . json_encode($response));

echo json_encode($response);
?> 