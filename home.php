<?php
session_start();
include 'config.php'; // Include your database connection

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SocialWaves</title>
    <link rel="stylesheet" href="./home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <div class="menu_side">
            <h1>SocialWaves</h1>
            <nav class="setting">
                <a href="./home.php"><i class="bi bi-house-fill"></i> Home</a>
                <a href="./posts.php"><i class="bi bi-house-fill"></i> Posts</a>
                <a href="./profile.php"><i class="bi bi-person-fill"></i> Profile</a>
                <a href="./notifications.php"><i class="bi bi-music-note-beamed"></i> Notifications</a>
                <a href="./saved.php"><i class="bi bi-save2-fill"></i> Saved Songs</a>
                <a href="logout.php" class="btn bi bi-box-arrow-right"> Logout</a>
            </nav>
        </div>
        <div class="user_info">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>Explore your favorite music, connect with friends, and share with them.</p>

            <div class="search-bar">
                <form action="search_user.php" method="GET">
                    <input type="text" name="search" placeholder="Search for a user..." class="search-input">
                    <button type="submit" class="search-btn"><i class="bi bi-search"></i></button> <br><br>
                </form>
            </div>

            <div class="actions">
                <a href="./posts.php" class="btn">See Your friends' posts</a>
                <a href="./last_listening.php" class="btn">Last Listening</a>
                <a href="./edit_profile.php" class="btn">Edit Profile</a>
            </div>
        </div>
    </header>

    <main>
        <section class="updates">
            <h3>Latest Updates</h3>
            <ul>
                <li><i class="bi bi-music-note"></i> New music releases available!</li>
                <li><i class="bi bi-people"></i> Connect with new friends.</li>
                <li><i class="bi bi-stars"></i> Customize your profile with new themes.</li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 All Rights Reserved.</p>
    </footer>
</body>

</html>