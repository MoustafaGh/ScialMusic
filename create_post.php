<?php
session_start();
header('Content-Type: application/json');  // Ensure it returns JSON

include('config.php');  // Ensure correct database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized access.']);
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

// Handle audio file upload
$audio_file = '';
if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/audio/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $fileName = preg_replace('/[^A-Za-z0-9.\-_]/', '', $_FILES['audio_file']['name']);
    $newFileName = uniqid() . '-' . $fileName;
    $audio_file = 'uploads/audio/' . $newFileName;
    move_uploaded_file($_FILES['audio_file']['tmp_name'], $audio_file);
}

// Handle image file upload
$image_file = '';
if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $fileName = preg_replace('/[^A-Za-z0-9.\-_]/', '', $_FILES['image_file']['name']);
    $newFileName = uniqid() . '-' . $fileName;
    $image_file = 'uploads/images/' . $newFileName;
    move_uploaded_file($_FILES['image_file']['tmp_name'], $image_file);
}

// Insert post data into the database
if ($audio_file && $image_file && isset($_POST['song_title'], $_POST['artist_name'])) {
    $song_title = $_POST['song_title'];
    $artist_name = $_POST['artist_name'];

    $query = "INSERT INTO posts (user_id, image, audio_file, music_title, artist_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $user_id, $image_file, $audio_file, $song_title, $artist_name);

    if ($stmt->execute()) {
        // Respond with a success message, but don't redirect or reload
        echo json_encode(['success' => 'Post created successfully']);
    } else {
        echo json_encode(['error' => 'Failed to save post in the database.']);
    }
} else {
    echo json_encode(['error' => 'Missing required data.']);
}
