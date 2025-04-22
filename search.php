<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to the user

// Log file setup
$logFile = 'search_debug.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Search request started\n", FILE_APPEND);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "heritage_culture";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $error = 'Database connection failed: ' . $conn->connect_error;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $error . "\n", FILE_APPEND);
    header('Content-Type: application/json');
    echo json_encode(['error' => $error]);
    exit();
}

// Get search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Search term: " . $search . "\n", FILE_APPEND);

// Prepare response array
$response = array(
    'heritage_places' => array(),
    'culture' => array(),
    'traditions' => array(),
    'error' => null
);

if (empty($search)) {
    $response['error'] = 'Please enter a search term';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

try {
    // Search across all tables simultaneously
    $search_param = "%$search%";
    
    // Search heritage places
    $heritage_query = "SELECT h.place_id, h.place_name, h.description, h.image_url, s.state_name 
                      FROM heritage_places h 
                      JOIN states s ON h.state_id = s.state_id 
                      WHERE LOWER(h.place_name) LIKE LOWER(?) 
                      OR LOWER(h.description) LIKE LOWER(?) 
                      OR LOWER(s.state_name) LIKE LOWER(?)";
    $stmt = $conn->prepare($heritage_query);
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['heritage_places'] = $result->fetch_all(MYSQLI_ASSOC);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Heritage places found: " . count($response['heritage_places']) . "\n", FILE_APPEND);
    
    // Search culture
    $culture_query = "SELECT c.culture_id, c.title, c.description, c.image_url, s.state_name 
                     FROM culture c 
                     JOIN states s ON c.state_id = s.state_id 
                     WHERE LOWER(c.title) LIKE LOWER(?) 
                     OR LOWER(c.description) LIKE LOWER(?) 
                     OR LOWER(s.state_name) LIKE LOWER(?)";
    $stmt = $conn->prepare($culture_query);
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['culture'] = $result->fetch_all(MYSQLI_ASSOC);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Culture items found: " . count($response['culture']) . "\n", FILE_APPEND);
    
    // Search traditions
    $traditions_query = "SELECT t.tradition_id, t.title, t.description, t.image_url, s.state_name 
                       FROM traditions t 
                       JOIN states s ON t.state_id = s.state_id 
                       WHERE LOWER(t.title) LIKE LOWER(?) 
                       OR LOWER(t.description) LIKE LOWER(?) 
                       OR LOWER(s.state_name) LIKE LOWER(?)";
    $stmt = $conn->prepare($traditions_query);
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['traditions'] = $result->fetch_all(MYSQLI_ASSOC);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Traditions found: " . count($response['traditions']) . "\n", FILE_APPEND);

} catch (Exception $e) {
    $error = 'An error occurred while searching: ' . $e->getMessage();
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $error . "\n", FILE_APPEND);
    $response['error'] = $error;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Response sent\n", FILE_APPEND);

$conn->close();
?> 