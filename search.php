<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "heritage_culture";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare response array
$response = array(
    'heritage_places' => array(),
    'culture' => array(),
    'traditions' => array()
);

if (!empty($search)) {
    // Search for state
    $state_query = "SELECT state_id, state_name FROM states WHERE state_name LIKE ?";
    $stmt = $conn->prepare($state_query);
    $search_param = "%$search%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $state = $result->fetch_assoc();
        $state_id = $state['state_id'];
        
        // Get heritage places
        $heritage_query = "SELECT * FROM heritage_places WHERE state_id = ?";
        $stmt = $conn->prepare($heritage_query);
        $stmt->bind_param("i", $state_id);
        $stmt->execute();
        $response['heritage_places'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Get culture
        $culture_query = "SELECT * FROM culture WHERE state_id = ?";
        $stmt = $conn->prepare($culture_query);
        $stmt->bind_param("i", $state_id);
        $stmt->execute();
        $response['culture'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Get traditions
        $traditions_query = "SELECT * FROM traditions WHERE state_id = ?";
        $stmt = $conn->prepare($traditions_query);
        $stmt->bind_param("i", $state_id);
        $stmt->execute();
        $response['traditions'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?> 