<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'libs/fpdf/fpdf.php';
include 'db.php';

if (isset($_GET['id'])) {
    $jobsheet_id = intval($_GET['id']);

    // 🌟 KEMAS KINI MUKTAMAD: Beri nama Alias 'jobsheet_real_id' supaya tak gaduh dengan ID Hotel
    $stmt = $conn->prepare("SELECT j.id AS jobsheet_real_id,
                                   j.hotel_id,
                                   j.task_type,
                                   j.pic_name,
                                      j.pic_assist_desg,
                                   j.pic_email,
                                   j.description,
                                   j.signature_path, -- 🌟 Paksa ambil column path tanda tangan
                                   DATE_FORMAT(j.date, '%d %M %Y') AS formatted_date, 
                                   TIME_FORMAT(j.time, '%H:%i %p') AS formatted_time, 
                                   h.name AS hotel_name,
                                   h.pic_main_desg AS hotel_pic_desg
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

    // Mula buat PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);

    // Header Syarikat
    $pdf->Image('viv_logo.png', 10, 6, 30);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 0, 'VISION FOUR MULTIMEDIA SDN BHD (199501037071)', 0, 1, 'R');
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

    $pdf->SetFont('Arial', '', 11);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 71, 171);
    $real_id = $row['jobsheet_real_id'] ?? $jobsheet_id;
    $twodigityear = date('y');
    $running_number = str_pad($real_id, 3, '0', STR_PAD_LEFT);
    $jobsheet_ref = $twodigityear . $running_number;
    // Grid Hotel & Tarikh
    $pdf->Cell(0, 10, 'Hotel:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(30, 47);
    $pdf->Cell(0, 10, $row['hotel_name'], 0, 1);
    $pdf->SetTextColor(220, 38, 38);

    $pdf->SetXY(150, 37);
    $pdf->Cell(0, 10, 'ID:', 0, 0);
    $pdf->SetXY(165, 37);
    $pdf->SetTextColor(220, 38, 38);
    $pdf->Cell(0, 10, $jobsheet_ref, 0, 1);
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


    $pdf->Ln(10);

    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(50, 10, 'Person In Charge:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['pic_name'] . ' ( ' . $row['pic_assist_desg'] . ' )', 0, 1);

    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(50, 10, 'Email:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, $row['pic_email'] .' ( '. $row['hotel_pic_desg'] .' )', 0, 1);

    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(50, 10, 'Task:', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 10, $row['task_type']);

    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(50, 6, 'Description:', 0, 0); // Tukar tinggi cell tajuk jadi 6 juga biar sebaris
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 6, !empty($row['description']) ? $row['description'] : 'No description provided.');

    // =================================================================
    // 🌟 KOD MUKTAMAD: LOCK 2 KOLUM DI BAWAH (KIRI: CLIENT, KANAN: TECHNICIAN)
    // =================================================================

    // 1. ANCHOR: Paksa kursor melompat ke 60mm sebelum kertas habis
    $pdf->SetY(220);
    $current_y_anchor = $pdf->GetY(); // Simpan titik Y ini untuk rujukan kedua-dua kolum

    // -----------------------------------------------------------------
    // KOLUM KIRI: CLIENT ACKNOWLEDGEMENT (Kekalkan paksi-X asal = 15)
    // -----------------------------------------------------------------
    $pdf->SetX(15);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(95, 8, 'Client Acknowledgement:', 0, 0); // Lebar 95mm (Separuh kertas)

    // Ambil koordinat untuk imej signature pelanggan
    $client_x = 15;
    $client_y = $current_y_anchor + 8;

    $sig_file = $row['signature_path'] ?? '';
    $absolute_path = __DIR__ . '/signatures/' . $sig_file;

    if (!empty($sig_file) && file_exists($absolute_path)) {
        // Tembak imej signature di sebelah kiri
        $pdf->Image($absolute_path, $client_x, $client_y, 45, 22, 'PNG');
    } else {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(150, 150, 150);
        // Letak Textbox amaran sementara di koordinat kiri
        $pdf->SetXY($client_x, $client_y);
        $pdf->Cell(95, 22, '(No signature captured)', 0, 0);
    }

    // Gerakkan kursor ke bawah imej untuk cetak Garisan & Nama Client
    $pdf->SetXY(15, $current_y_anchor + 32);
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(95, 5, '__________________________', 0, 1);
    $pdf->SetX(15);
    $pdf->Cell(95, 15, 'Name: ' . ($row['pic_name'] ?? 'N/A') . ' (' . ($row['pic_assist_desg'] ?? 'N/A') . ')', 0, 0);


    // -----------------------------------------------------------------
    // KOLUM KANAN: ATTENDED TECHNICIAN (Kita anjak paksi-X ke = 115)
    // -----------------------------------------------------------------
    $pdf->SetXY(135, $current_y_anchor); // Lompat balik ke Y atas, tapi X di kanan
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 71, 171);
    $pdf->Cell(80, 8, 'Attended VIVTechnician:', 0, 1);

    // Memandangkan tak perlu signature, kita terus buat kotak info / jarak kosong yang kemas
    $pdf->SetX(135);
    $pdf->Ln(2); // Bagi gap sikit ke bawah

    // Cetak Nama Juruteknik / Staff yang login / handle task tersebut
    // (Nota: Sila ganti $row['staff_name'] ikut nama column staff/technician kau yang sebenar)
    $technician_name = $row['technician_name'] ?? 'Duty Technician';
    $pdf->Image('signatures/sig_vivtech.png', 135, $current_y_anchor + 8, 45, 22, 'PNG'); // Logo teknisyen di kanan
    $pdf->SetXY(135, $current_y_anchor + 32); // Sebariskan ketinggian teks nama dengan sebelah kiri
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(80, 5, '__________________________', 0, 1);
    $pdf->SetX(135);
    $pdf->Cell(95, 15, 'Name: ' . $technician_name, 0, 0);

    // =================================================================
    // END OF PDF OUTPUT
    // =================================================================
    if (ob_get_contents()) ob_end_clean();
    $pdf->Output('I', 'js_' . str_replace(' ', '_', $row['hotel_name']) . '_' . $row['formatted_date'] . '.pdf');
}
