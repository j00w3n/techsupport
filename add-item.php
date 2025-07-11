<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = trim($_POST['itemName']);
    $itemQuantity = intval($_POST['itemQuantity']);

    if (!empty($itemName) && $itemQuantity >= 0) {
        // Step 1: Insert into items table
        $stmt = $conn->prepare("INSERT INTO items (name) VALUES (?)");
        $stmt->bind_param("s", $itemName);
        if ($stmt->execute()) {
            $itemId = $stmt->insert_id;
            $stmt->close();

            // Step 2: Insert into inventory table
            $invStmt = $conn->prepare("INSERT INTO inventory (item_id, stock_quantity) VALUES (?, ?)");
            $invStmt->bind_param("ii", $itemId, $itemQuantity);
            $invStmt->execute();
            $invStmt->close();

            header("Location: item-catalog.php?success=1");
            exit();
        } else {
            echo "❌ Failed to add item: " . $stmt->error;
        }
    } else {
        echo "❗ Item name and valid quantity are required.";
    }
}
?>
