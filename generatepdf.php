<?php
require 'libs/fpdf/fpdf.php';
include 'db.php';

if (isset($_GET['id'])) {
    $jobsheet_id = intval($_GET['id']); // Letak intval untuk keselamatan daripada SQL injection

    // 1. QUERY: Ambil semua data asas termasuk signature_path terus dari table jobsheet
    $stmt = $conn->prepare("SELECT j.*, 
                                   DATE_FORMAT(j.date, '%d %M %Y') AS formatted_date, 
                                   TIME_FORMAT(j.time, '%H:%i %p') AS formatted_time, 
                                   h.name AS hotel_name
                            FROM jobsheet j
                            JOIN hotel h ON j.hotel_id = h.id
                            WHERE j.id = ?");
    $stmt->bind_param("i", $jobsheet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        die("❌ Error: Jobsheet record not found.");
    }

    // 2. Tarik senarai barang yang digunakan
    $items = [];
    $stmt2 = $conn->prepare("SELECT i.name, ji.quantity
                             FROM jobsheet_items ji
                             JOIN items i ON ji.item_id = i.id
                             WHERE ji.jobsheet_id = ?");
    $stmt2->bind_param("i", $jobsheet_id);
    $stmt2->execute();
    $itemsResult = $stmt2->get_result();
    while ($item = $itemsResult->fetch_assoc()) {
        $items[] = $item;
    }
    $stmt2->close();

    $bluecolor = array(0, 71, 171);

    // 3. Mula Generate PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);

    // Header Syarikat & Logo
    $pdf->Image('viv_logo.png', 10, 6, 30);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 0, 'Daytime Sdn Bhd (74432-U)', 0, 1, 'R');
    $pdf->SetXY(50, 14);
    $pdf->Cell(0, 0, '28, Jalan Liku, Bangsar,', 0, 1, 'R');
    $pdf->SetXY(50, 18);
    $pdf->Cell(0, 0, '59100 Kuala Lumpur', 0, 1, 'R');
    $pdf->SetXY(50, 22);
    $pdf->Cell(0, 0, 'Malaysia', 0, 1, 'R');

    // Tajuk Dokumen
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(0, 10, 'Jobsheet Report', 0, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 71, 171);

    // Maklumat Blok Kiri & Kanan (Hotel, Tarikh, Masa)
    $pdf->Cell(0, 10, 'Hotel:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(30, 47);
    $pdf->Cell(0, 10, $row['hotel_name'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->SetXY(150, 47);
    $pdf->Cell(0, 10, 'Date:', 0, 0);
    $pdf->SetXY(165, 47);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['formatted_date'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->SetXY(150, 57);
    $pdf->Cell(50, 10, 'Time:', 0, 0);
    $pdf->SetXY(165, 57);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['formatted_time'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    // Maklumat PIC, Email, Task
    $pdf->Ln(10);
    $pdf->Cell(50, 10, 'Person:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['pic_name'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->Cell(50, 10, 'Email:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['pic_email'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->Cell(50, 10, 'Task:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 10, $row['task_type']);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->Cell(50, 10, 'Description:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    // 🌟 FIX BUG: Tukar dari $row['description'] kepada $row['complaint'] ikut column DB yang betul
    $pdf->MultiCell(0, 10, !empty($row['description']) ? $row['description'] : 'No description provided.');
    $pdf->SetTextColor(0, 71, 171);

    // 4. Paparkan Senarai Barang Guna (Aktifkan balik bahagian yang kau komen tadi)
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Items Used:', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    if (!empty($items)) {
        foreach ($items as $item) {
            $pdf->Cell(0, 10, "- {$item['name']} (x{$item['quantity']})", 0, 1);
        }
    } else {
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell(0, 10, "No inventory items deployed for this task.", 0, 1);
        $pdf->SetTextColor(0, 0, 0);
    }

    // 5. 🌟 LOGIK BARU: Cetak Gambar Tanda Tangan dari Canvas Form
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(0, 10, 'Client Acknowledgement:', 0, 1);

    // Semak kalau fail signature wujud dalam folder signatures/
    if (!empty($row['signature_path']) && file_exists("signatures/" . $row['signature_path'])) {
        $current_x = $pdf->GetX();
        $current_y = $pdf->GetY();

        // Cetak gambar tanda tangan (.png) secara dinamik
        $pdf->Image("signatures/sig_6a21434a0ab62.png", $current_x, $current_y, 45, 22, 'PNG');
        $pdf->Ln(24); // Bagi ruang ke bawah supaya nama tak bertindih dengan imej
    } else {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 10, '(No digital signature captured)', 0, 1);
        $pdf->Ln(5);
    }

    // Garisan Nama Pengesiah Atas Tanda Tangan
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(60, 5, '__________________________', 0, 1);
    $pdf->Cell(60, 7, 'Name: ' . ($row['pic_name'] ?? 'N/A'), 0, 1);

    // Output PDF ke Browser
    $pdf->Output('I', 'js_' . str_replace(' ', '_', $row['hotel_name']) . '_' . $row['formatted_date'] . '.pdf');
}
