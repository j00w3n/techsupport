<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotelName = $_POST['hotelName'];
    $hotelState = $_POST['hotelState'];
    $hotelEmail = $_POST['hotelEmail'];
    $hotel_id = $_POST['id'];
    $hotelPerson = $_POST['hotelPerson'];
    $hotelDesignation = $_POST['hotelDesignation'];


    $stmt = $conn->prepare("UPDATE hotel SET name = ?, state = ?, email = ?, picname = ?, pic_main_desg = ? WHERE id = ?");

    $stmt->bind_param(
        "sssssi",
        $hotelName,   // 1. name
        $hotelState,  // 2. state
        $hotelEmail,  // 3. email
        $hotelPerson, // 4. picname (Nama orang dulu)
        $hotelDesignation, // 5. pic_desg (Jawatan orang)
        $hotel_id     // 6. WHERE id (ID di hujung sekali sebagai integer)
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
