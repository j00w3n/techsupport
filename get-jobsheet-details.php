<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $jobsheetId = intval($_POST['id']);

    $stmt = $conn->prepare("
        SELECT 
            j.id,
            j.date,
            j.time,
            h.name AS hotel_name,
            p.picname AS person_name,
            j.task_type,
            j.complaint,
            j.fault,
            j.repair,
            GROUP_CONCAT(CONCAT(i.name, ' (x', ji.quantity, ')') SEPARATOR ', ') AS items_used
        FROM 
            jobsheet j
        JOIN 
            hotel h ON j.hotel_id = h.id
        LEFT JOIN 
            hotel_person p ON j.person_id = p.picid
        LEFT JOIN 
            jobsheet_items ji ON j.id = ji.jobsheet_id
        LEFT JOIN 
            items i ON ji.item_id = i.id
        WHERE 
            j.id = ?
        GROUP BY 
            j.id
        ORDER BY 
            j.date DESC
    ");
    $stmt->bind_param("i", $jobsheetId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='info'><span class=''>info: </span>".$row['hotel_name']."</div>
        <div class='info'><span class=''>info: </span>".$row['date']." ".$row['time']."</div>
        <div class='info'><span class=''>info: </span>".$row['task_type']."</div>
        <div class='info'><span class=''>info: </span>".$row['person_name']."</div>
        <div class='info'><span class=''>info: </span>".$row['complaint']."</div>
        <div class='info'><span class=''>info: </span>".$row['fault']."</div>
        <div class='info'><span class=''>info: </span>".$row['repair']."</div>
        <div class='info'><span class=''>info: </span>".$row['items_used']."</div>
        ";
    }

    $stmt->close();
    $conn->close();
}
