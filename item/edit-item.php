<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['item_id'];
    $name = $_POST['item_name'];
    $qty = $_POST['stock_quantity'];
    // update item name

    $stmtupdatename = $conn->prepare("UPDATE items SET name = ? where id = ?");
    $stmtupdatename->bind_param("si", $name, $id);
    $stmtupdatename->execute();
    $stmtupdatename->close();
    
    //update inventory quantity
    $stmt = $conn->prepare("UPDATE inventory set stock_quantity = ? WHERE item_id = ?");
    $stmt->bind_param("ii", $qty, $id);

    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: item-catalog.php?updated=1");

        exit();
    } else {
        echo "❌ Update failed: " . $stmt->error.$stmtupdatename->error;
    }
}
?>