<?php
include 'db.php';
$hotelperson = $conn->query("SELECT h.name AS hotel_name, p.picname AS person_name FROM hotel h JOIN hotel_person p ON h.id = p.hotel_id ORDER BY h.name, p.picname;");
$jobsheetResult = $conn->query("
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
GROUP BY 
    j.id
ORDER BY 
    j.date DESC;

");
$jobsheetResult1 = $conn->query("
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
GROUP BY 
    j.id
ORDER BY 
    j.date DESC;

");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
</head>

<body>
    <?php include 'navbar.html'; ?>

    <!-- Modal -->
    <div class="modal fade" id="addHotelModal" tabindex="-1" aria-labelledby="addHotelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHotelModalLabel">Add Hotel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="add-hotel.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="hotelName">Hotel Name</label>
                            <input type="text" class="form-control" id="hotelName" name="hotelName" required>
                        </div>
                        <div class="form-group">
                            <label for="hotelState">State</label>
                            <select class="form-control" id="hotelState" name="hotelState" required>
                                <option value="" disabled selected>Select a state</option>
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Malacca">Malacca</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Penang">Penang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Labuan">Labuan</option>
                                <option value="Putrajaya">Putrajaya</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus pe-3"></i><span
                                class="ps-2">Add</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal for jobsheet details -->
    <div class="modal fade" id="jobsheetDetailsModal" tabindex="-1" aria-labelledby="jobsheetDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobsheetDetailsModalLabel">Jobsheet Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="jobsheetDetailsBody">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-3">
            <div class="col-lg-12">
                <h3>Dashboard Jobsheet</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-end">
                <div class="mb-3">
                    <a href="index.php" class="btn btn-sm btn-primary">form</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h4 class="py-2">Jobsheet List</h4>
                <table id="jobsheetTable" class="table table-responsive table-bordered table-striped mb-5">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Hotel Name</th>
                            <th>Person in charge</th>
                            <th>Complaint</th>
                            <th>Fault</th>
                            <th>Repair</th>
                            <th>Items Used</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $jobsheetResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row["date"]; ?></td>
                                <td><?php echo $row["time"]; ?></td>
                                <td><?php echo $row["hotel_name"]; ?></td>
                                <td><?php echo $row["person_name"]; ?></td>
                                <td><?php echo $row["complaint"]; ?></td>
                                <td><?php echo $row["fault"]; ?></td>
                                <td><?php echo $row["repair"]; ?></td>
                                <td><?php echo $row["items_used"]; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info view-details-btn" data-toggle="modal"
                                        data-target="#jobsheetDetailsModal" data-id="<?php echo $row['id']; ?>">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h4 class="py-2">Person List</h4>
                <table id="personTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Hotel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $hotelperson->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['person_name'] ?></td>
                                <td><?= $row['hotel_name'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
$(document).ready(function () {
    $('.view-details-btn').on('click', function () {
        const jobsheetId = $(this).data('id');

        $.ajax({
            url: 'get-jobsheet-details.php',
            type: 'POST',
            data: { id: jobsheetId },
            success: function (response) {
                $('#jobsheetDetailsBody').html(response);
                $('#viewJobsheetModal').modal('show'); // Show the modal
            }
        });
    });
});
    </script>
</body>

</html>