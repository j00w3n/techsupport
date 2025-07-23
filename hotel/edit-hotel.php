<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotelName = $_POST['hotelName'];
    $hotelState = $_POST['hotelState'];
    $hotel_id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE hotel SET name = ?, state = ? WHERE id = ?");
    $stmt->bind_param("ssi", $hotelName, $hotelState, $_POST['id']);

    if ($stmt->execute()) {
        header("Location: ../hotel.php?updated=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

}
?>