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
    p.email AS person_email,
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
    j.date DESC
LIMIT 6;

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script> <!-- script type="text/javascript" src="js/date_time.js"></script -->
    <!-- script type="text/javascript" src="engine1/jquery.js"></script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            overflow-x: hidden;
        }

        table thead tr th {
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .count-title {
            font-size: 16px;
            font-weight: bold;
            color: #fff;
        }

        .count-body p {
            font-size: 30px;
            font-weight: bold;
            color: #000;
            padding: 10px;
        }

        .count-body span {
            font-size: 14px;
            font-weight: lighter;
            color: #808080;
        }
    </style>
</head>


<body>
    <?php include 'navbar.html'; ?>
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
                <div class="d-flex justify-content-between">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-decoration-none text-dark"
                                    href="index.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                    <div>
                        <a href="index.php" class="btn btn-sm btn-primary px-3"><i
                                class="fa-solid fa-pen pe-2"></i>Jobsheet
                            Form</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-end">
                <div class="mb-3">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-12">
                <div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="border count-box box">
                                <div class="bg-danger p-1 pl-2">
                                    <p class="mb-0 count-title">Total Jobsheet</p>
                                </div>
                                <div class="count-body">
                                    <p>
                                        <?php
                                        $stmt = $conn->prepare("SELECT COUNT(*) AS total_jobsheet FROM jobsheet");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();
                                        echo $row['total_jobsheet'];
                                        ?>
                                        <span>sheets</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="border count-box box">
                                <div class="bg-success p-1 pl-2">
                                    <p class="mb-0 count-title">Total PIC</p>
                                </div>
                                <div class="count-body">
                                    <p>
                                        <?php
                                        $stmt = $conn->prepare("SELECT COUNT(*) AS total_pic FROM hotel_person");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();
                                        echo $row['total_pic'];
                                        ?>
                                        <span>persons</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 col-12">
                            <div class="border count-box box">
                                <div class="bg-info p-1 pl-2">
                                    <p class="mb-0 count-title">Total Hotels</p>
                                </div>
                                <div class="count-body">
                                    <p>
                                        <?php
                                        $stmt = $conn->prepare("SELECT COUNT(*) AS total_hotel FROM hotel");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();
                                        echo $row['total_hotel'];
                                        ?>
                                        <span>hotels</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="border count-box box">
                                <div class="bg-dark p-1 pl-2">
                                    <p class="mb-0 count-title">Total Items</p>
                                </div>
                                <div class="count-body">
                                    <p>
                                        <?php
                                        $stmt = $conn->prepare("SELECT COUNT(*) AS total_items FROM items");
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();
                                        echo $row['total_items'];
                                        ?>
                                        <span>items</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="border p-3 h-100 fw-bold d-flex align-items-center justify-content-center">
                    <h3 class="text-muted">Nothing to show for now</h3>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <h4 class="py-2">Jobsheet List</h4>
                <div class="table-responsive">
                    <table id="jobsheetTable" class="table table-bordered table-striped mb-5">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Hotel Name</th>
                                <th>PIC</th>
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
                                        <div class="d-flex flex-row" style="gap: 5px;">
                                            <button class="btn btn-sm btn-secondary view-details-btn" data-toggle="modal"
                                                data-target="#jobsheetDetailsModal" data-id="<?php echo $row['id']; ?>">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning edit-jobsheet-btn"
                                                data-id="<?= $row['id'] ?>">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-jobsheet-btn"
                                                data-id="<?= $row['id'] ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <a href="mailto:<?= $row['person_email'] ?>?subject=Support%20Job%20Update&body=Dear%20<?= urlencode($row['person_name']) ?>,"
                                                class="btn btn-sm btn-info">
                                                <i class="fa-solid fa-envelope"></i>
                                            </a>
                                            <a href="generatepdf.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success"
                                                target="_blank">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>

                                        </div>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h4 class="py-2">Person List</h4>
                <table id="personTable" class="table table-bordered table-striped">
                    <thead class="thead-dark">
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
        //view modal details on jobsheet
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
        //delete jobsheet
        $(document).ready(function () {
            $('.delete-jobsheet-btn').on('click', function () {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this jobsheet?')) {
                    $.ajax({
                        url: 'action/jobsheet-delete.php',
                        type: 'POST',
                        data: { id: id },
                        success: function (response) {
                            if (response.trim() === 'success') {
                                location.reload();
                            } else {
                                alert('❌ Failed to delete jobsheet.');
                                console.log('Response:', response);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('❌ Failed to delete jobsheet.');
                            console.log('Error:', error);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>