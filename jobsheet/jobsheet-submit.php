<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Dapatkan nilai asas dari form input
    $date      = $_POST['date'];
    $time      = $_POST['time'];
    $hotel_id  = intval($_POST['hotelname']);
    $typetask  = $_POST['task'];
    $pic_email = $_POST['picemail'] ?? '';

    // Nama PIC diambil terus dari input 'newstaff'
    $pic_name  = $_POST['newstaff'] ?? '';
    $description = $_POST['description'] ?? '';

    // 🌟 LOGIK BARU: Tarik hotelEmail secara automatik dari table hotel berdasarkan hotel_id
    $pic_email = "";
    if ($hotel_id > 0) {
        $stmtHotel = $conn->prepare("SELECT email FROM hotel WHERE id = ?");
        $stmtHotel->bind_param("i", $hotel_id);
        $stmtHotel->execute();
        $stmtHotel->bind_result($fetched_email);
        if ($stmtHotel->fetch()) {
            $pic_email = $fetched_email; // Email hotel kini menjadi pic_email secara automatik
        }
        $stmtHotel->close();
    }
    // 🌟 LOGIK BARU: Proses Tanda Tangan Canvas
    $signature_filename = NULL;
    if (!empty($_POST['signature_image'])) {
        $img_data = $_POST['signature_image'];

        // Potong header Base64 ("data:image/png;base64,") untuk ambil data mentah gambar
        $filteredData = explode(',', $img_data);
        if (isset($filteredData[1])) {
            $unencodedData = base64_decode($filteredData[1]);

            // Reka nama fail unik (contoh: sig_67f8a9bc.png)
            $signature_filename = "sig_" . uniqid() . ".png";

            // Simpan fail fizikal ke dalam folder 'signatures/'
            // Menggunakan ../signatures/ jika fail submit ini berada di dalam subfolder (cth: hotel/)
            file_put_contents("../signatures/" . $signature_filename, $unencodedData);
        }
    }
    // Step 2: Dapatkan array barang yang digunakan
    $items      = isset($_POST['item']) ? $_POST['item'] : [];
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];

    // Step 3: Insert rekod ke dalam table jobsheet
    $stmt = $conn->prepare("INSERT INTO jobsheet 
        (date, time, description,hotel_id, task_type, pic_name, pic_email, signature_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // 8 parameter diikat dengan tepat ke dalam table jobsheet
    $stmt->bind_param("sssissss", $date, $time, $description, $hotel_id, $typetask, $pic_name, $pic_email, $signature_filename);
    if ($stmt->execute()) {
        $jobsheet_id = $stmt->insert_id;
        $stmt->close();

        // Step 4: Uruskan kemasukan barang (jobsheet_items) & Kemas kini Inventori
        if (!empty($items)) {
            $itemStmt = $conn->prepare("INSERT INTO jobsheet_items (jobsheet_id, item_id, quantity) VALUES (?, ?, ?)");

            if (strtolower($typetask) === 'installation') {
                $stockStmt = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity - ? WHERE item_id = ?");
            } else {
                $stockStmt = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity + ? WHERE item_id = ?");
            }

            foreach ($items as $index => $item_id) {
                if (isset($quantities[$index])) {
                    $qty = intval($quantities[$index]);

                    if (!empty($item_id) && $qty > 0) {
                        $itemStmt->bind_param("iii", $jobsheet_id, $item_id, $qty);
                        $itemStmt->execute();

                        $stockStmt->bind_param("ii", $qty, $item_id);
                        $stockStmt->execute();
                    }
                }
            }
            $itemStmt->close();
            $stockStmt->close();
        }

        $conn->close();

        // Sukses & Hantar balik ke Dashboard
        header("Location: ../dashboard.php?success=1");
        exit();
    } else {
        echo "❌ Failed to insert jobsheet: " . $stmt->error;
    }
}
