<?php
$servername = "localhost";
$server_username = "root";
$password = "";
$db_name = "suggestion_box";
$conn = new mysqli($servername, $server_username, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
