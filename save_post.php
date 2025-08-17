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

    // Check if the post is already saved
    $query = "SELECT * FROM saved WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the post is already saved, remove it (unsave)
        $query = "DELETE FROM saved WHERE user_id = ? AND post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $post_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'saved' => false]); // Return saved status
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to unsave the post.']);
        }
    } else {
        // If the post is not saved, add it to the saved table
        $query = "INSERT INTO saved (user_id, post_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $post_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'saved' => true]); // Return saved status
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save the post.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Post ID is missing.']);
}
