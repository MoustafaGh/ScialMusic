<?php
// Start session to get the logged-in user ID
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$loggedInUserId = $_SESSION['user_id'];

// SQL query to fetch posts from the logged-in user and users they are following
$sql = "
    SELECT 
        posts.id,
        posts.user_id,
        posts.image,
        posts.audio_file,
        posts.music_title,
        posts.artist_name,
        posts.created_at,
        users.username
    FROM posts
    INNER JOIN users ON posts.user_id = users.id
    LEFT JOIN followers ON followers.following_id = posts.user_id
    WHERE posts.user_id = ? OR followers.follower_id = ?
    ORDER BY posts.created_at DESC;
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to prepare the SQL statement']);
    exit();
}

// Bind the logged-in user's ID to the query
$stmt->bind_param('ii', $loggedInUserId, $loggedInUserId);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to store posts
$posts = [];

// Fetch results and prepare the response
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'image' => $row['image'],
            'audio_file' => $row['audio_file'],
            'music_title' => $row['music_title'],
            'artist_name' => $row['artist_name'],
            'username' => $row['username'],
            'created_at' => $row['created_at'],
            'liked' => false, // Placeholder for "liked" status
            'saved' => false, // Placeholder for "saved" status
            'downloaded' => false // Placeholder for "downloaded" status
        ];
    }
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the posts as a JSON object
echo json_encode($posts, JSON_UNESCAPED_UNICODE);

// Close the statement and database connection
$stmt->close();
$conn->close();
