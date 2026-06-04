<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $hotel_id = intval($_POST['id']);

    // Disebabkan database dah set ON DELETE CASCADE, kita terus 
    // padam baris hotel sahaja. Anak-anak data lain auto terpadam sekali!
    $stmt = $conn->prepare("DELETE FROM hotel WHERE id = ?");
    $stmt->bind_param("i", $hotel_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        
        // Selesai dan hantar balik ke page utama hotel
        header("Location: ../hotel.php?deleted=1");
        exit();
    } else {
        echo "❌ Error deleting hotel: " . $stmt->error;
        $stmt->close();
        $conn->close();
    }
} else {
    header("Location: ../hotel.php");
    exit();
}
?>