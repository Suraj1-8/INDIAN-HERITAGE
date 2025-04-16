<?php
session_start();
header('Content-Type: application/json');
$response = array();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['loggedin'] = true;
    $response['full_name'] = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : '';
} else {
    $response['loggedin'] = false;
}
echo json_encode($response);
exit;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Session</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Your user ID is: <?php echo $_SESSION['user_id']; ?></p>
    <p>Your role is: <?php echo $_SESSION['role']; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html> 