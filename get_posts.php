<?php
session_start();
header('Content-Type: application/json');

include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit();
}

$username = $_SESSION['username'];

// Fetch user details
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    echo json_encode(['error' => 'User not found.']);
    exit();
}

$user = $user_result->fetch_assoc();
$user_id = $user['id'];

// Fetch posts for the user
$query = "SELECT posts.id, posts.image, posts.audio_file, posts.music_title, posts.artist_name, users.username 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          WHERE posts.user_id = ? 
          ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $post_id = $row['id'];

    // Check if the post is liked by the current user
    $liked_query = "SELECT 1 FROM likes WHERE user_id = ? AND post_id = ?";
    $liked_stmt = $conn->prepare($liked_query);
    $liked_stmt->bind_param('ii', $user_id, $post_id);
    $liked_stmt->execute();
    $liked_result = $liked_stmt->get_result();
    $liked = $liked_result->num_rows > 0 ? true : false;

    // Check if the post is saved by the current user
    $saved_query = "SELECT 1 FROM saved WHERE user_id = ? AND post_id = ?";
    $saved_stmt = $conn->prepare($saved_query);
    $saved_stmt->bind_param('ii', $user_id, $post_id);
    $saved_stmt->execute();
    $saved_result = $saved_stmt->get_result();
    $saved = $saved_result->num_rows > 0 ? true : false;

    // Check if the post is downloaded by the current user
    $download_query = "SELECT 1 FROM download WHERE user_id = ? AND post_id = ?";
    $download_stmt = $conn->prepare($download_query);
    $download_stmt->bind_param('ii', $user_id, $post_id);
    $download_stmt->execute();
    $download_result = $download_stmt->get_result();
    $downloaded = $download_result->num_rows > 0 ? true : false;

    // Add the liked, saved, and downloaded flags to the post
    $row['liked'] = $liked;
    $row['saved'] = $saved;
    $row['downloaded'] = $downloaded;

    $posts[] = $row;
}

// Return posts as JSON
echo json_encode($posts);
