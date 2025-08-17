<?php
session_start();
include 'config.php';

if (isset($_POST['edituser_btn'])) {
    $id = $_POST['edit_id'];

    $query = "SELECT * FROM users WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_array($result);
    } else {
        $_SESSION['message'] = "User Not Found";
        header('Location: user_admin.php');
        exit();
    }
}

if (isset($_POST['update_user_btn'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['username'];

    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', phone='$phone', address='$address', username='$username' WHERE id='$id'";
    mysqli_query($conn, $query);

    $_SESSION['message'] = "User Updated Successfully";
    header('Location: user_admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Edit User</h3>
    <form action="edit_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <div class="mb-3">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" name="fname" id="fname" class="form-control" value="<?= $user['fname'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="lname" class="form-label">Last Name</label>
            <input type="text" name="lname" id="lname" class="form-control" value="<?= $user['lname'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?= $user['phone'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" required><?= $user['address'] ?></textarea>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <textarea name="username" id="username" class="form-control" required><?= $user['username'] ?></textarea>
        </div>
        <button type="submit" name="update_user_btn" class="btn btn-primary">Update</button>
        <a href="user_admin.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
