<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = trim($_POST['itemName']);
    $quantity = trim($_POST['quantity']);

    if (!empty($itemName) && !empty($quantity)) {
        $stmt = $conn->prepare("INSERT INTO items (name, quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $itemName, $quantity);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Item name and quantity are required.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
