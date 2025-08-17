<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo "No username provided.";
    exit();  // Optionally redirect to home or error page
}

$searched_username = $_GET['username'];  // Get the searched username from the URL

include('config.php');

// Query to fetch the user_id based on the session's username
$query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']); // Use the session's username to get user_id
$stmt->execute();
$user_result = $stmt->get_result();
$current_user = $user_result->fetch_assoc();

if (!$current_user) {
    echo "Current user not found.";
    exit();
}

$current_user_id = $current_user['id'];  // This is the logged-in user's ID

// Query to fetch user details based on the searched username
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $searched_username);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

$user_id = $user['id'];  // This is the searched user's ID

// Fetch posts count dynamically
$query = "SELECT COUNT(*) AS posts_count FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts_result = $stmt->get_result();
$posts_count = $posts_result->fetch_assoc()['posts_count'];

// Fetch followers and following count
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

// Check if the user has a profile picture
$query = "SELECT media_url FROM user_media WHERE user_id = ? AND media_type = 'profile_picture' ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile_picture_data = $result->fetch_assoc();

if ($profile_picture_data && isset($profile_picture_data['media_url'])) {
    $profile_picture = $profile_picture_data['media_url'];
} else {
    $profile_picture = '3.png';  // Default profile picture
}

// Check if the current user is following the searched user
$query = "SELECT COUNT(*) AS is_following FROM followers WHERE follower_id = ? AND following_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $current_user_id, $user_id);
$stmt->execute();
$follow_result = $stmt->get_result();
$is_following = $follow_result->fetch_assoc()['is_following'];

$follow_button_text = ($is_following > 0) ? 'Unfollow' : 'Follow'; // Change the button text based on follow status

// Handle follow/unfollow action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($is_following > 0) {
        // Unfollow the user
        $query = "DELETE FROM followers WHERE follower_id = ? AND following_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $current_user_id, $user_id);
        $stmt->execute();
        $follow_button_text = 'Follow';  // Change the button text
    } else {
        // Follow the user
        $query = "INSERT INTO followers (follower_id, following_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $current_user_id, $user_id);
        $stmt->execute();
        $follow_button_text = 'Unfollow';  // Change the button text
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $searched_username; ?>'s Profile</title> <!-- Title updated to show searched username -->
    <link rel="stylesheet" href="searched_user.css">
    <link rel="stylesheet" href="profile.css">
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
            <div class="user_information">
                <div class="prof_description">
                    <div class="user_description container">
                        <div class="item item-1 post post_nb">
                            <span><?php echo $posts_count; ?></span><br>
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
                <div class="prof_picture">
                    <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-img">
                </div>
                <form method="POST">
                    <div class="follow_button">
                        <button type="submit" class="follow-btn"><?php echo $follow_button_text; ?></button>
                    </div>
                </form>
                <div class="username_display">
                    <h2><?php echo $searched_username; ?>'s Profile</h2> <!-- Display searched username here -->
                </div>
            </div>


            <div class="posts">
                <div id="user-posts">

                </div>
            </div>

        </div>
    </header>

</body>
<script src="./user-posts.js"></script>
<script src="./likescript.js"></script>
<script src="./saved.js"></script>

</html>