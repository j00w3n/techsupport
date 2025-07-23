<?php
include 'db.php';

$id = $_GET['id']; // get id from url

$stmt = $conn->prepare("DELETE FROM hotel WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
