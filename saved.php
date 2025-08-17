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

$user_id = $user['id'];

// Fetch the count of followers
$query = "SELECT COUNT(*) AS followers_count FROM followers WHERE following_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$followers_result = $stmt->get_result();
$followers = $followers_result->fetch_assoc()['followers_count'];

// Fetch the count of following
$query = "SELECT COUNT(*) AS following_count FROM followers WHERE follower_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$following_result = $stmt->get_result();
$following = $following_result->fetch_assoc()['following_count'];

// Fetch the count of posts
$query = "SELECT COUNT(*) AS posts_count FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts_result = $stmt->get_result();
$posts = $posts_result->fetch_assoc()['posts_count'];

// Fetch the profile picture with the maximum id
$query = "SELECT media_url FROM user_media WHERE user_id = ? AND media_type = 'profile_picture' ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile_picture_data = $result->fetch_assoc();

if ($profile_picture_data && isset($profile_picture_data['media_url'])) {
    $profile_picture = $profile_picture_data['media_url'];
} else {
    $profile_picture = '3.png'; // Default profile picture if no profile picture is set
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Posts</title>
    <link rel="stylesheet" href="profile.css?v=2">
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
                <a href="./saved.php"><i class="bi bi-save2-fill"></i> Saved Songs</a>
                <a href="./logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
        <div class="song_side">
            <div class="user_information">
                <div class="prof_description">
                    <div class="user_description container">
                        <div class="item item-1 post post_nb">
                            <span><?php echo $posts; ?></span><br>
                            <p>Posts</p>
                        </div>
                        <div class="item item-2 post followers">
                            <span><?php echo $followers; ?></span><br>
                            <p>Followers</p>
                        </div>
                        <div class="item item-3 post following">
                            <span><?php echo $following; ?></span><br>
                            <p>Following</p>
                        </div>
                    </div>
                </div>
                <?php echo $username; ?>
                <div class="prof_picture">

                    <img id="prof_pic" src="<?php echo $profile_picture . '?' . time(); ?>" alt="Profile Picture" class="profile-img">
                </div>
                <div class="edit_profile_button">
                    <a href="edit_profile.php" class="edit_button">Edit Profile</a>
                </div>
            </div>

            <div class="song" id="song">
                <div id="saved-posts"></div>
            </div>
        </div>
    </header>
</body>

<script src="./get-saved-posts.js"></script>
<script src="./likescript.js"></script>
<script src="./saved.js"></script>

</html>