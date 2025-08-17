<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include('config.php');

$username = $_SESSION['username'];

$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// $user_id = $user['id'];

$query = "SELECT COUNT(*) AS followers_count FROM followers WHERE following_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$followers_result = $stmt->get_result();
$followers = $followers_result->fetch_assoc()['followers_count'];

$query = "SELECT COUNT(*) AS following_count FROM followers WHERE follower_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$following_result = $stmt->get_result();
$following = $following_result->fetch_assoc()['following_count'];

$query = "SELECT COUNT(*) AS posts_count FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts_result = $stmt->get_result();
$posts = $posts_result->fetch_assoc()['posts_count'];

$query = "SELECT media_url FROM user_media WHERE user_id = ? AND media_type = 'profile_picture' LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile_picture_data = $result->fetch_assoc();

if ($profile_picture_data && isset($profile_picture_data['media_url'])) {
    $profile_picture = $profile_picture_data['media_url'];
} else {
    $profile_picture = '5.png';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>last listening</title>
    <link rel="stylesheet" href="./last_listening.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body>
    <header>
        <div class="menu_side">
            <h1>SocialWaves</h1>
            <div class="setting">
                <a href="./home.php"><i class="bi bi-house-fill"></i> Home</a>
                <a href="./posts.php"><i class="bi bi-house-fill"></i> Posts</a>
                <a href="./profile.php"><i class="bi bi-person-fill"></i> Profile</a>
                <a href="./notifications.php"><i class="bi bi-music-note-beamed"></i> Notifications</a>
                <a href="./downloads.php"><i class="bi bi-download"></i> Saved Songs</a>
                <a href="./logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
        <div class="song_side">
            <div class="music-post">
                <img src="image_url" alt="Daily Mix Cover">
                <div class="music-details">
                    <div class="music-title">
                        <div>
                            <h3>Username</h3>
                        </div>
                    </div>
                    <div class="track-list">
                        <div class="track-item">
                            <span>artist name</span>
                            <span>song title</span>
                        </div>
                    </div>
                    <div class="controls">
                        <div>
                            <button>&#171;</button>
                            <button>&#9654;</button>
                            <button>&#187;</button>
                        </div>
                        <div>
                            <button><i class="bi bi-heart"></i></button>
                            <!-- <button><i class="bi bi-heart-fill"></i></button> -->
                             <button><i class="bi bi-save2-fill"></i></button>
                             <button><i class="bi bi-download"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>

</html>