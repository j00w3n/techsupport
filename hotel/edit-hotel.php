<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotelName = $_POST['hotelName'];
    $hotelState = $_POST['hotelState'];
    $hotelEmail = $_POST['hotelEmail'];
    $hotel_id = $_POST['id'];
    $hotelPerson = $_POST['hotelPerson'];

    $stmt = $conn->prepare("UPDATE hotel SET name = ?, state = ?, email = ?, picname = ? WHERE id = ?");

    $stmt->bind_param(
        "ssssi",
        $hotelName,   // 1. name
        $hotelState,  // 2. state
        $hotelEmail,  // 3. email
        $hotelPerson, // 4. picname (Nama orang dulu)
        $hotel_id     // 5. WHERE id (ID di hujung sekali sebagai integer)
    );

    if ($stmt->execute()) {
        header("Location: ../hotel.php?updated=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
