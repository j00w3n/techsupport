<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Get basic form values
    $date = $_POST['date'];
    $time = $_POST['time'];
    $hotel_id = $_POST['hotelname'];
    $typetask = $_POST['task'];
    $person_id = $_POST['pic'];
    $complaint = $_POST['complaint'];
    $fault = $_POST['fault'];
    $repair = $_POST['repair'];
    $picemail = $_POST['picemail'];
    $maint_note = $_POST['maintenance_note'];
    $install_note = $_POST['installation_note'];
    $dismantle_note = $_POST['dismantle_note'];
    $new_staff_name = $_POST['newstaff'];

    // ✅ Step 1.1: Check if the selected person's email needs update
    $stmtCheck = $conn->prepare("SELECT email FROM hotel_person WHERE picid = ?");
    $stmtCheck->bind_param("i", $person_id);
    $stmtCheck->execute();
    $stmtCheck->bind_result($current_email);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($current_email !== $picemail && !empty($picemail)) {
        $stmtUpdate = $conn->prepare("UPDATE hotel_person SET email = ? WHERE picid = ?");
        $stmtUpdate->bind_param("si", $picemail, $person_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    }

    // 1. Get values
    $new_staff_name = trim($_POST['newstaff']);
    $picemail = $_POST['picemail'];
    $person_id = $_POST['pic']; // Might be empty if new staff

    // 2. If new staff name is filled and person_id is empty, insert new person
    if (!empty($new_staff_name) && empty($person_id)) {
        $stmtNew = $conn->prepare("INSERT INTO hotel_person (picname, email, hotel_id) VALUES (?, ?, ?)");
        $stmtNew->bind_param("ssi", $new_staff_name, $picemail, $hotel_id);
        $stmtNew->execute();
        $person_id = $stmtNew->insert_id; // Now this is the person to use
        $stmtNew->close();
    }


    // Step 2: Get item arrays
    $items = $_POST['item'];        // [item_id1, item_id2, ...]
    $quantities = $_POST['quantity']; // [qty1, qty2, ...]

    // Step 3: Insert into jobsheet
    $stmt = $conn->prepare("INSERT INTO jobsheet (date, time, name,task, person_id, complaint, fault, repair)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiisss", $date, $time, $hotel_id,$typetask, $person_id, $complaint, $fault, $repair);

    if ($stmt->execute()) {
        $jobsheet_id = $stmt->insert_id;
        $stmt->close();

        // Step 4: Insert item rows and update inventory
        $itemStmt = $conn->prepare("INSERT INTO jobsheet_items (jobsheet_id, item_id, quantity) VALUES (?, ?, ?)");
        $stockStmt = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity - ? WHERE item_id = ?");

        foreach ($items as $index => $item_id) {
            $qty = intval($quantities[$index]);
            if (!empty($item_id) && $qty > 0) {
                // insert into jobsheet_items
                $itemStmt->bind_param("iii", $jobsheet_id, $item_id, $qty);
                $itemStmt->execute();

                // deduct stock
                $stockStmt->bind_param("ii", $qty, $item_id);
                $stockStmt->execute();
            }
        }

        $itemStmt->close();
        $stockStmt->close();
        $conn->close();

        // Success
        header("Location: ../dashboard.php?success=1");
        exit();
    } else {
        echo "❌ Failed to insert jobsheet: " . $stmt->error;
    }
}
?>
