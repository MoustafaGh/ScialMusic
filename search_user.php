<?php
session_start();
include 'config.php'; // Include your database connection

// Check if search is provided
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']); // Protect against SQL injection
    header("Location: search_results.php?search=" . urlencode($searchQuery)); // Redirect to search results
    exit();
}
?>
