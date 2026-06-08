<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotelName = trim($_POST['hotelName']);
    $hotelState = trim($_POST['hotelState']);
    $hotelEmail = trim($_POST['hotelEmail']);
    $hotelPerson = trim($_POST['hotelPerson']);
    $hotelDesignation = trim($_POST['hotelDesignation']);

    if (!empty($hotelName) && !empty($hotelState)) {
        $stmt = $conn->prepare("INSERT INTO hotel (name, state,email,picname,pic_main_desg) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $hotelName, $hotelState, $hotelEmail, $hotelPerson, $hotelDesignation);
        if ($stmt->execute()) {
            header("Location: ../hotel.php?added=1");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Hotel name and state are required.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>