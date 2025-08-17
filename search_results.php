<?php
session_start();
include 'config.php'; // Include your database connection

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle the search query and get results if available
$searchResults = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']); // Protect against SQL injection

    // Query to search for users by username
    $query = "SELECT * FROM users WHERE username LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $query);

    // If users are found, store the results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - SocialWaves</title>
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
                <a href="./downloads.php"><i class="bi bi-download"></i> Saved Songs</a>
                <a href="logout.php" class="btn bi bi-box-arrow-right"> Logout</a>
            </nav>
        </div>
        <div class="user_info">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>Search results for "<?php echo htmlspecialchars($_GET['search']); ?>"</p>
        </div>
    </header>

    <main>
        <section class="search-results">
            <?php if (!empty($searchResults)): ?>
                <h3>Users Found:</h3>
                <ul>
                    <?php foreach ($searchResults as $user): ?>
                        <!-- Link to searched_user.php with username parameter -->
                        <li><a href="searched_user.php?username=<?php echo urlencode($user['username']); ?>"><strong><?php echo htmlspecialchars($user['username']); ?></strong></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No users found for "<?php echo htmlspecialchars($_GET['search']); ?>"</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 All Rights Reserved.</p>
    </footer>
</body>
</html>
