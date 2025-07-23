<?php
include 'db.php';

if (isset($_POST['hotel_id'])) {
    $hotel_id = $_POST['hotel_id'];

    $stmt = $conn->prepare("SELECT picid, picname,email FROM hotel_person WHERE hotel_id = ?");
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value='' selected>Select person</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['picid'] . "'data-email=".$row['email'].">" . htmlspecialchars($row['picname']) . "</option>";
    }
    $stmt->close();
    $conn->close();
}
?>