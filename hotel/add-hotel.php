<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotelName = trim($_POST['hotelName']);
    $hotelState = trim($_POST['hotelState']);

    if (!empty($hotelName) && !empty($hotelState)) {
        $stmt = $conn->prepare("INSERT INTO hotel (name, state) VALUES (?, ?)");
        $stmt->bind_param("ss", $hotelName, $hotelState);

        if ($stmt->execute()) {
            header("Location: ../hotel.php?added=1");
            exit();
        } else {
            echo "Error: " . $stmt1->error;
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