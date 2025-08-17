<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include('config.php');

$username = $_SESSION['username'];  // Get the current logged-in username

// Fetch the user's current data
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// If the form is submitted, process the update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $new_username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);

    // Check if the new username already exists (excluding the current user)
    $username_check_query = "SELECT * FROM users WHERE username = ? AND id != ?";
    $username_check_stmt = $conn->prepare($username_check_query);
    $username_check_stmt->bind_param("si", $new_username, $user['id']); // Exclude the current user's id
    $username_check_stmt->execute();
    $username_check_result = $username_check_stmt->get_result();

    if ($username_check_result->num_rows > 0) {
        // New username is already taken
        $error = "This username is already taken by another user.";
    } else {
        // Check if the email already exists (excluding the current user)
        $email_check_query = "SELECT * FROM users WHERE email = ? AND id != ?";
        $email_check_stmt = $conn->prepare($email_check_query);
        $email_check_stmt->bind_param("si", $email, $user['id']);
        $email_check_stmt->execute();
        $email_check_result = $email_check_stmt->get_result();

        if ($email_check_result->num_rows > 0) {
            // Email already exists
            $error = "This email is already taken by another user.";
        } else {
            // Handle profile picture upload
            if (!empty($_FILES['profile_picture']['name'])) {
                $profile_picture_name = basename($_FILES['profile_picture']['name']);
                $target_dir = "uploads/";
                $target_file = $target_dir . $profile_picture_name;

                // Validate file type and size
                $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                if (in_array($file_type, ['jpg', 'jpeg', 'png']) && $_FILES['profile_picture']['size'] <= 2000000) {
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                        // Insert the profile picture into the user_media table
                        $user_id = $user['id']; // Get the user ID from the current user
                        $media_type = 'profile_picture';
                        $created_at = date('Y-m-d H:i:s');

                        // Insert into user_media table
                        $insert_query = "INSERT INTO user_media (user_id, media_type, media_url, created_at) VALUES (?, ?, ?, ?)";
                        $insert_stmt = $conn->prepare($insert_query);
                        $insert_stmt->bind_param("isss", $user_id, $media_type, $target_file, $created_at);
                        if (!$insert_stmt->execute()) {
                            $error = "Failed to upload profile picture to the media table.";
                        } else {
                            $profile_picture = $target_file; // Set the new profile picture URL
                        }
                    } else {
                        $error = "Failed to upload profile picture.";
                    }
                } else {
                    $error = "Invalid file type or size. Only JPG, JPEG, and PNG files under 2MB are allowed.";
                }
            } else {
                // Use existing profile picture if not updated
                $profile_picture = $user['profile_picture'];
            }

            // Update user details in the database
            if (!isset($error)) {
                // Update user data in the users table
                $update_query = "UPDATE users SET fname = ?, lname = ?, username = ?, email = ?, phone = ?, profile_picture = ? WHERE username = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("sssssss", $fname, $lname, $new_username, $email, $phone, $profile_picture, $username);

                if ($update_stmt->execute()) {
                    // Success message
                    $success = "Profile updated successfully!";
                    // Update session with the new username
                    $_SESSION['username'] = $new_username;

                    // Refresh the user's data
                    $user['fname'] = $fname;
                    $user['lname'] = $lname;
                    $user['username'] = $new_username;
                    $user['email'] = $email;
                    $user['phone'] = $phone;
                    $user['profile_picture'] = $profile_picture;
                } else {
                    $error = "Failed to update profile. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>

        <?php if (isset($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php elseif (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($user['fname']) ?>" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($user['lname']) ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required pattern="\d{8}">
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                <?php if ($user['profile_picture']): ?>
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="profile-img-preview">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
        <a href="profile.php" class="back-button">Back to Profile</a>
    </div>
</body>
</html>
