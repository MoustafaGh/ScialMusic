<?php
$db_server = "localhost";
$db_user = "root";
$db_pas = "";
$db_name = "socialmedia";
$conn = new mysqli($db_server, $db_user, $db_pas, $db_name,);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
