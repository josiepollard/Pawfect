<?php
$conn = new mysqli("localhost", "root", "", "pawfect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>