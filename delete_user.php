<?php
session_start();
include 'config.php';

if (isset($_POST['deleteuser_btn'])) {
    $id = $_POST['delete_id'];

    $query = "DELETE FROM users WHERE id='$id'";
    mysqli_query($conn, $query);

    $_SESSION['message'] = "User Deleted Successfully"; 
    header('Location: users.php'); 
    exit();
}
?>
