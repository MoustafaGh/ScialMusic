<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if the username exists in the admin table
    $sqlAdmin = "SELECT * FROM admin WHERE username = ?";
    $stmtAdmin = $conn->prepare($sqlAdmin);

    if ($stmtAdmin) {
        $stmtAdmin->bind_param('s', $username);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();

        if ($resultAdmin->num_rows > 0) {
            // User found in admin table
            $rowAdmin = $resultAdmin->fetch_assoc();
            if ($password === $rowAdmin['password']) {
                // Plain text password matches for admin
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'admin';
                $_SESSION['user_id'] = $rowAdmin['id']; // Correctly set the user_id for admin
                header("Location: user_admin.php");
                exit();
            } else {
                echo "<script>alert('Invalid username or password'); location.href='login.php';</script>";
                exit();
            }
        }
    }

    // Check if the username exists in the users table
    $sqlUser = "SELECT * FROM users WHERE username = ?";
    $stmtUser = $conn->prepare($sqlUser);

    if ($stmtUser) {
        $stmtUser->bind_param('s', $username);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();

        if ($resultUser->num_rows > 0) {
            // User found in users table
            $rowUser = $resultUser->fetch_assoc();
            $hashedPassword = md5($password); // MD5 hash the entered password

            if ($hashedPassword === $rowUser['password']) {
                // MD5 hashed password matches for users
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user';
                $_SESSION['user_id'] = $rowUser['id']; // Correctly set the user_id for user
                header("Location: home.php");
                exit();
            } else {
                echo "<script>alert('Invalid username or password'); location.href='login.php';</script>";
                exit();
            }
        }
    }

    // If no match is found in either table
    echo "<script>alert('Invalid username or password'); location.href='login.php';</script>";
    exit();
}

// Close the database connection
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - socialmedia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="./login.css">
</head>

<body>
    <form action="login.php" method="POST">
        <div class="form-container">
            <h2>Login to Rami's Social</h2>

            <input type="text" name="username" placeholder="Username" class="input-field" required>
            <input type="password" name="password" placeholder="Password" class="input-field" required>

            <button type="submit" class="submit-btn">Login</button>

            <p class="register-link">Don't have an account? <a href="register.php">Register Now</a></p>
        </div>
    </form>
</body>

</html>