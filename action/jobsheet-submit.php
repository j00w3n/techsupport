<?php
$date = $_POST["date"];
$time = $_POST["time"];
$hotelname = $_POST["hotelname"];
$complaint = $_POST["complaint"];
$fault = $_POST["fault"];
$repair = $_POST["repair"];
$partreplaced = $_POST["partreplaced"];
include '../db.php';

$stmt = $conn->prepare("INSERT INTO jobsheet (date, time, hotelname, complaint, fault, repair, partreplaced) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $date, $time, $hotelname, $complaint, $fault, $repair, $partreplaced);

if ($stmt->execute()) {
    echo "Record inserted successfully.";
    header("Location: ../dashboard.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>