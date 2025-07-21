<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Optional: Delete related items first if you have a jobsheet_items table
    // $conn->query("DELETE FROM jobsheet_items WHERE jobsheet_id = $id");

    $stmt = $conn->prepare("DELETE FROM jobsheet WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    exit();
}
?>
