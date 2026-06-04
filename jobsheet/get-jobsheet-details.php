<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $jobsheetId = intval($_POST['id']);

    // 🌟 QUERY BARU: Ambil pic_name terus dari jobsheet (j), buang JOIN hotel_person lama
    // Kita tambah sekali DATE_FORMAT dan TIME_FORMAT yang betul
    $stmt = $conn->prepare("
        SELECT 
            j.*,
            DATE_FORMAT(j.date, '%d %M %Y') AS formatted_date,
            TIME_FORMAT(j.time, '%H:%i %p') AS formatted_time,
            h.name AS hotel_name,
            GROUP_CONCAT(CONCAT(i.name, ' (x', ji.quantity, ')') SEPARATOR ', ') AS items_used
        FROM 
            jobsheet j
        JOIN 
            hotel h ON j.hotel_id = h.id
        LEFT JOIN 
            jobsheet_items ji ON j.id = ji.jobsheet_id
        LEFT JOIN 
            items i ON ji.item_id = i.id
        WHERE 
            j.id = ?
        GROUP BY 
            j.id
    ");
    $stmt->bind_param("i", $jobsheetId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Guna 'if' sebab kita cari 1 data spesifik sahaja, tak perlu 'while' loop berkali-kali
    if ($row = $result->fetch_assoc()) {
        
        // Atur isi kandungan barang (Jika tiada barang digunakan, kita letak '-' supaya tak kosong)
        $items_display = !empty($row['items_used']) ? $row['items_used'] : '<span class="text-slate-400 italic">No inventory items deployed.</span>';
        
        echo "
        <div class='space-y-4 text-sm text-slate-700'>
            <div class='info py-1.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center'>
                <span class='font-bold text-slate-500 w-32 uppercase tracking-wider text-[11px]'>Hotel Name</span>
                <span class='font-semibold text-slate-900'>" . htmlspecialchars($row['hotel_name']) . "</span>
            </div>
            
            <div class='info py-1.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center'>
                <span class='font-bold text-slate-500 w-32 uppercase tracking-wider text-[11px]'>Date & Time</span>
                <span class='text-slate-800'>" . htmlspecialchars($row['formatted_date']) . " @ " . htmlspecialchars($row['formatted_time']) . "</span>
            </div>
            
            <div class='info py-1.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center'>
                <span class='font-bold text-slate-500 w-32 uppercase tracking-wider text-[11px]'>Task Type</span>
                <span class='inline-block px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wide bg-sky-50 text-sky-700 border border-sky-200'>" . htmlspecialchars($row['task_type']) . "</span>
            </div>
            
            <div class='info py-1.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center'>
                <span class='font-bold text-slate-500 w-32 uppercase tracking-wider text-[11px]'>Person In Charge</span>
                <span class='text-slate-800 font-medium'>" . htmlspecialchars($row['pic_name'] ?? 'N/A') . "</span>
            </div>
            
            <div class='info py-1.5 border-b border-slate-100 flex flex-col'>
                <span class='font-bold text-slate-500 w-32 uppercase tracking-wider text-[11px] mb-1'>Log Description</span>
                <div class='bg-slate-50 border border-slate-100 rounded p-2.5 font-mono text-xs text-slate-600 leading-relaxed'>" . nl2br(htmlspecialchars($row['complaint'] ?? 'No issue logs submitted.')) . "</div>
            </div>
            
            <div class='info py-1.5 flex flex-col'>
                <span class='font-bold text-slate-500 w-32 uppercase tracking-wider text-[11px] mb-1'>Items Deployed</span>
                <span class='text-slate-800 font-medium'>" . $items_display . "</span>
            </div>
        </div>
        ";
    } else {
        echo "<div class='text-center py-4 text-xs text-red-500 font-medium'><i class='fas fa-exclamation-triangle mr-1'></i> Log details could not be retrieved.</div>";
    }
    
    $stmt->close();
    $conn->close();
}
?>