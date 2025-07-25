<?php
include 'db.php';

$sql = "SELECT 
            DATE_FORMAT(date, '%M %Y') AS month,
            COUNT(*) AS total
        FROM jobsheet
        GROUP BY month
        ORDER BY month";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>