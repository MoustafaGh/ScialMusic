<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $country = htmlspecialchars(trim($_POST['country']));
    $bod = htmlspecialchars(trim($_POST['bod']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $address = htmlspecialchars(trim($_POST['address']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!$%^&*])[A-Za-z\d!$%^&*]{8,}$/", $password)) {
        echo "<script>alert('Password must be at least 8 characters, contain a letter, a number, and a special character.'); location.href='register.php';</script>";
        exit();
    }

    $sqlCheck = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($sqlCheck);
    if ($result->num_rows > 0) {
        echo "<script>alert('Username or email already exists. Please choose another.'); location.href='register.php';</script>";
        exit();
    }

    $hashedPassword = md5($password);
    $sql = "INSERT INTO users (fname, lname, country, bod, email, phone, gender, address, username, password) 
            VALUES ('$fname', '$lname', '$country', '$bod', '$email', '$phone', '$gender', '$address', '$username', '$hashedPassword')";

    if ($conn->query($sql)) {
        echo "<script>location.href='login.php'</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.'); location.href='register.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./register.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

</head>
<body>
    <form action="register.php" method="POST"> <br><br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h2>Register Here</h2>

        <input type="text" name="fname" placeholder="First Name" required>
        <input type="text" name="lname" placeholder="Last Name" required>
        <input type="text" name="country" placeholder="Country" required>
        <input type="date" name="bod" placeholder="Date of Birth" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>
        <input type="text" name="gender" placeholder="Gender (Male/Female/Other)" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <label>
            <input type="checkbox" name="terms" required> I agree to the Terms and Privacy Policy
        </label>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</body>
</html>
