<?php
require 'libs/fpdf/fpdf.php';
include 'db.php';

if (isset($_GET['id'])) {
    $jobsheet_id = $_GET['id'];

    // Get jobsheet details
    $stmt = $conn->prepare("SELECT j.*, h.name AS hotel_name, p.picname AS person_name,p.email AS person_email
                            FROM jobsheet j
                            JOIN hotel h ON j.hotel_id = h.id
                            LEFT JOIN hotel_person p ON j.person_id = p.picid
                            WHERE j.id = ?");
    $stmt->bind_param("i", $jobsheet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Get items
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
    // Generate PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);

    // header
    $pdf->Image('viv_logo.png', 10, 6, 30);
    $pdf->SetFont('Arial', '', 8);
    // $pdf->SetXY(50,6);
    $pdf->Cell(0, 0, 'Daytime Sdn Bhd (74432-U)', 0, 1, 'R');
    $pdf->SetXY(50, 14);
    $pdf->Cell(0, 0, '28, Jalan Liku, Bangsar,', 0, 1, 'R');
    $pdf->SetXY(50, 18);
    $pdf->Cell(0, 0, '59100 Kuala Lumpur', 0, 1, 'R');
    $pdf->SetXY(50, 22);
    $pdf->Cell(0, 0, 'Malaysia', 0, 1, 'R');

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(0, 10, 'Jobsheet Report', 0, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->Cell(0, 10, 'Hotel:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(30, 47);
    $pdf->Cell(0, 10, $row['hotel_name'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->SetXY(150, 47);
    $pdf->Cell(0, 10, 'Date:', 0, 0);
    $pdf->SetXY(165, 47);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['date'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->SetXY(150, 57);
    $pdf->Cell(50, 10, 'Time:', 0, 0);
    $pdf->SetXY(165, 57);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['time'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);

    $pdf->Ln(10);
    $pdf->Cell(50, 10, 'Person:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['person_name'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(50, 10, 'Email:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['person_email'], 0, 1);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(50, 10, 'Task:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 10, $row['task_type']);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(50, 10, 'Description:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 10, $row['complaint']);
    $pdf->SetTextColor(0, 71, 171);
    // $pdf->Cell(50, 10, 'Repair:', 0, 0);
    // $pdf->SetTextColor(0, 0, 0);
    // $pdf->MultiCell(0, 10, $row['repair']);
    // $pdf->SetTextColor(0, 71, 171);

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Items Used:', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    foreach ($items as $item) {
        $pdf->Cell(0, 10, "- {$item['name']} (x{$item['quantity']})", 0, 1);
    }
    $pdf->SetXY(10, 250);
    $pdf->Cell(50, 10, 'Description:', 0, 0);
    $pdf->Output('I', 'js_' . $row['hotel_name'] . '_' . $row['date'] . '.pdf');
}
?>