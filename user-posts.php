<?php
session_start();
header('Content-Type: application/json');

// Include the database connection
include('config.php');

// Check if the username is provided in the GET request
if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo json_encode(['error' => 'Profile username not provided.']);
    exit();
}

$username = $_GET['username'];

// Fetch the user ID based on the provided username
$query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'User not found.']);
    exit();
}

$user = $result->fetch_assoc();
$user_id = $user['id'];

// Fetch posts by user ID
$query = "SELECT posts.id, posts.image, posts.audio_file, posts.music_title, posts.artist_name, posts.created_at, users.username 
          FROM posts 
          INNER JOIN users ON posts.user_id = users.id 
          WHERE posts.user_id = ? 
          ORDER BY posts.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    // You can customize these fields (e.g., add isLiked, isSaved logic) if needed
    $row['isLiked'] = false; // Placeholder logic
    $row['isSaved'] = false; // Placeholder logic
    $posts[] = $row;
}

// Return the posts as JSON
echo json_encode($posts);
