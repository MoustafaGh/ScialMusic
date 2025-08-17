<?php
session_start();
header('Content-Type: application/json');

// Debug session data to ensure `user_id` is set
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'User not logged in.',
        'debug' => [
            'session_id' => session_id(),
            'session_data' => $_SESSION,
            'cookies' => $_COOKIE
        ]
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the POST request contains the post ID
if (!isset($_POST['post_id']) || empty($_POST['post_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Post ID is required.'
    ]);
    exit();
}

$post_id = $_POST['post_id'];

// Include the database connection
include('config.php');

// Fetch the audio file details for the given post ID
$query = "SELECT audio_file FROM posts WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'error' => 'Post not found.'
    ]);
    exit();
}

$post = $result->fetch_assoc();
$audio_file = $post['audio_file'];

// Insert a new record into the downloads table
$query = "INSERT INTO downloads (user_id, post_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $post_id);

if ($stmt->execute()) {
    // Return the audio file URL and suggested filename
    $audio_url = './' . $audio_file; // Adjust the path as necessary
    $audio_filename = basename($audio_file); // Extract the filename from the path

    echo json_encode([
        'success' => true,
        'audio_url' => $audio_url,
        'audio_filename' => $audio_filename,
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save download information.'
    ]);
}
