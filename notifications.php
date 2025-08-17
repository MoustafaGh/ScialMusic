<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include('config.php');

$username = $_SESSION['username'];

// Fetch the logged-in user's ID
$query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// If the request is via AJAX (fetching notifications dynamically)
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $query = "
        SELECT 
            u.fname AS user_fname, 
            u.lname AS user_lname, 
            'like' AS type, 
            p.music_title AS post_title, 
            NULL AS created_at
        FROM likes l
        INNER JOIN posts p ON l.post_id = p.id
        INNER JOIN users u ON l.user_id = u.id
        WHERE p.user_id = ?
        
        UNION ALL
        
        SELECT 
            u.fname AS user_fname, 
            u.lname AS user_lname, 
            'follow' AS type, 
            NULL AS post_title, 
            f.created_at
        FROM followers f
        INNER JOIN users u ON f.follower_id = u.id
        WHERE f.following_id = ?
        ORDER BY created_at DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    // Send JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode($notifications);
    exit();
}

// For initial page load (HTML rendering)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="./notifications.css">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            </div>
        </div>

        <div class="notifications_side">
            <h2>Notifications</h2>
            <div id="notification_list" class="notification_list">
                <!-- Notifications will be dynamically loaded here -->
            </div>
        </div>
    </header>

    <script>
        // Fetch notifications via AJAX
        function fetchNotifications() {
            $.ajax({
                url: './notifications.php?ajax=1',
                method: 'GET',
                success: function(data) {
                    const notificationList = $('#notification_list');
                    notificationList.empty(); // Clear the current notifications

                    if (data.length === 0) {
                        notificationList.append('<p>No new notifications.</p>');
                    } else {
                        data.forEach(notification => {
                            let notificationItem = '';

                            if (notification.type === 'like') {
                                notificationItem = `
                                    <div class="notification_item">
                                        <i class="bi bi-heart-fill notification_icon"></i>
                                        <p>
                                            <strong>${notification.user_fname} ${notification.user_lname}</strong> liked your post "${notification.post_title}".
                                        </p>
                                        <span class="notification_time">Just now</span>
                                    </div>
                                `;
                            } else if (notification.type === 'follow') {
                                notificationItem = `
                                    <div class="notification_item">
                                        <i class="bi bi-person-plus-fill notification_icon"></i>
                                        <p>
                                            <strong>${notification.user_fname} ${notification.user_lname}</strong> followed you.
                                        </p>
                                        <span class="notification_time">${new Date(notification.created_at).toLocaleString()}</span>
                                    </div>
                                `;
                            }

                            notificationList.append(notificationItem);
                        });
                    }
                },
                error: function(err) {
                    console.error('Error fetching notifications:', err);
                }
            });
        }

        // Refresh notifications every 10 seconds
        setInterval(fetchNotifications, 10000);

        // Fetch notifications on page load
        fetchNotifications();
    </script>
</body>

</html>