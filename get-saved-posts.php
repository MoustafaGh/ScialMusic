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

// Fetch saved posts for the user
$query = "SELECT posts.id, posts.image, posts.audio_file, posts.music_title, posts.artist_name, users.username 
          FROM saved 
          JOIN posts ON saved.post_id = posts.id 
          JOIN users ON posts.user_id = users.id 
          WHERE saved.user_id = ? 
          ORDER BY saved.id DESC";

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

    // Check if the post is downloaded by the current user
    $download_query = "SELECT 1 FROM download WHERE user_id = ? AND post_id = ?";
    $download_stmt = $conn->prepare($download_query);
    $download_stmt->bind_param('ii', $user_id, $post_id);
    $download_stmt->execute();
    $download_result = $download_stmt->get_result();
    $downloaded = $download_result->num_rows > 0 ? true : false;

    // Add the liked and downloaded flags to the post
    $row['liked'] = $liked;
    $row['downloaded'] = $downloaded;

    $posts[] = $row;
}

// Return saved posts as JSON
echo json_encode($posts);
