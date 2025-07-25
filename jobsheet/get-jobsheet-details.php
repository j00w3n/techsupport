<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $jobsheetId = intval($_POST['id']);

    $stmt = $conn->prepare("
        SELECT 
            j.*,
            DATE_FORMAT(j.date, '%d %M %Y') AS date,
            h.name AS hotel_name,
            p.picname AS person_name,
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
        <div class='info'><span class=''>Hotel Name: </span>" . $row['hotel_name'] . "</div>
        <div class='info'><span class=''>Date: </span>" . $row['date'] . " " . $row['time'] . "</div>
        <div class='info'><span class=''>Task: </span>" . $row['task_type'] . "</div>
        <div class='info'><span class=''>Person: </span>" . $row['person_name'] . "</div>
        <div class='info'><span class=''>Complaint: </span>" . $row['description'] . "</div>
        <div class='info'><span class=''>Items Used: </span>" . $row['items_used'] . "</div>
        ";
    }
    $stmt->close();
    $conn->close();
}
