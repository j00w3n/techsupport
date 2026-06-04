<?php
include 'db.php';
// Panggil library FPDF ikut struktur folder projek kau
require('libs/fpdf/fpdf.php');

// 1. Tangkap hotel_id dari URL (contoh: generate-chronology-pdf.php?hotel_id=5)
$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0;

if ($hotel_id == 0) {
    die("Error: Invalid or missing Hotel ID.");
}

// 2. Tarik nama hotel untuk tajuk report
$hotelStmt = $conn->prepare("SELECT name FROM hotel WHERE id = ?");
$hotelStmt->bind_param("i", $hotel_id);
$hotelStmt->execute();
$hotelName = $hotelStmt->get_result()->fetch_assoc()['name'] ?? 'Unknown Hotel';

// 3. 🌟 QUERY BARU: Ambil pic_name terus dari jobsheet, buang JOIN hotel_person
$sql = "SELECT j.* FROM jobsheet j
        WHERE j.hotel_id = ?
        ORDER BY STR_TO_DATE(j.date, '%Y-%m-%d') DESC, j.time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$result = $stmt->get_result();

// 4. Mula reka bentuk PDF guna FPDF
class ChronologyPDF extends FPDF {
    // Header Laporan
    function Header() {
        // Logo atau Nama Syarikat
        $this->SetFont('Arial', 'B', '14');
        $this->SetTextColor(15, 23, 42); // Warna Slate-900
        $this->Cell(0, 10, 'VIVTECH SUPPORT SYSTEM', 0, 1, 'L');
        
        $this->SetFont('Arial', '', '10');
        $this->SetTextColor(100, 116, 139); // Warna Grey
        $this->Cell(0, 5, 'Technical Troubleshooting History & Chronology Report', 0, 1, 'L');
        
        // Garis pemisah header
        $this->SetDrawColor(226, 232, 240);
        $this->Line(10, 27, 200, 27);
        $this->Ln(8);
    }

    // Footer Laporan
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', '8');
        $this->SetTextColor(148, 163, 184);
        // Menunjukkan nombor muka surat
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }
}

// Instantiate and build document
$pdf = new ChronologyPDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Info Hotel
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(2, 132, 199); // Sky blue color
$pdf->Cell(0, 7, 'HOTEL NAME: ' . strtoupper($hotelName), 0, 1, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(0, 5, 'Generated on: ' . date('d F Y (h:i A)'), 0, 1, 'L');
$pdf->Ln(5);

// ====== BINA TIMELINE / LOG DATA ======
if ($result->num_rows > 0) {
    $count = 1;
    while ($row = $result->fetch_assoc()) {
        
        // Kotak Utama Setiap Event Chronology
        $pdf->SetDrawColor(203, 213, 225); // Border grey soft
        $pdf->SetFillColor(248, 250, 252); // Background ala slate-50
        
        // Dapatkan kedudukan X & Y semasa sebelum melukis kotak
        $current_y = $pdf->GetY();
        
        // Header mini dalam log event (Tarikh & Jenis Tugas)
        $is_trouble = (strtolower($row['task_type']) == 'troubleshoot');
        
        $pdf->SetFont('Arial', 'B', 9);
        if ($is_trouble) {
            $pdf->SetTextColor(180, 83, 9); // Jingga/Amber untuk troubleshoot
            $task_badge = "[TROUBLESHOOT]";
        } else {
            $task_badge = "[" . strtoupper($row['task_type']) . "]";
            if (strtolower($row['task_type']) == 'installation') {
                $pdf->SetTextColor(4, 120, 87); // Hijau untuk installation
            } else {
                $pdf->SetTextColor(2, 132, 199); // Biru untuk dismantle/lain-lain
            }
        }
        
        // Cetak Tarikh & Waktu & Badge Tugas
        $pdf->Cell(45, 7, $row['date'] . ' @ ' . $row['time'], 0, 0, 'L');
        $pdf->Cell(50, 7, $task_badge, 0, 0, 'L');
        
        // 🌟 KEMAS KINI: Cetak Nama PIC menggunakan pic_name terus dari jobsheet
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->Cell(0, 7, 'PIC: ' . ($row['pic_name'] ?? 'N/A'), 0, 1, 'R');
        
        // Cetak Aduan / Detail Masalah (Guna MultiCell supaya tulisan panjang auto-drop ke bawah)
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(15, 23, 42);
        
        $complaint_text = !empty($row['complaint']) ? $row['complaint'] : 'Task executed and closed with zero core issues.';
        
        // Sediakan ruang padding sikit
        $pdf->SetX(12);
        $pdf->MultiCell(180, 5, "Log Detail: " . $complaint_text, 0, 'L');
        
        // Buat garisan pemisah bawah yang kemas antara event
        $pdf->Ln(3);
        $pdf->SetDrawColor(241, 245, 249);
        $pdf->Cell(0, 2, '', 'B', 1, 'C');
        $pdf->Ln(4);
        
        $count++;
    }
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->SetTextColor(148, 163, 184);
    $pdf->Cell(0, 10, 'No technical support or troubleshooting logs recorded for this hotel.', 0, 1, 'C');
}

// Output fail PDF terus ke browser
$pdf->Output('I', 'Chronology_' . str_replace(' ', '_', $hotelName) . '.pdf');
?>