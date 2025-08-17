<?php
session_start();
header('Content-Type: application/json');

include('config.php'); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit();
}

$username = $_SESSION['username'];

// Fetch user ID
$query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$user_id = $user['id'];

// Check if the post_id is provided in the request
if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Check if the user has already liked this post
    $query = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If a like exists, delete it (unlike)
        $query = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $post_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'liked' => false]); // Return liked status
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to remove like.']);
        }
    } else {
        // If no like exists, insert a new like
        $query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $post_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'liked' => true]); // Return liked status
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add like.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Post ID is missing.']);
}
