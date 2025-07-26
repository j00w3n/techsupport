<?php
include 'db.php';
$hotelperson = $conn->query("SELECT h.name AS hotel_name, p.picname AS person_name FROM hotel h JOIN hotel_person p ON h.id = p.hotel_id ORDER BY h.name, p.picname;");
$jobsheetResult = $conn->query("
SELECT 
    j.*,
    DATE_FORMAT(j.date, '%d %M %Y') AS date,
    TIME_FORMAT(j.time, '%H:%i %p') AS time,
    h.name AS hotel_name,
    p.picname AS person_name,
    p.email AS person_email,
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
    j.date DESC, j.time DESC
LIMIT 3;
");
$jobsheetResult1 = $conn->query("
SELECT 
j.*,
DATE_FORMAT(j.date, '%d %M %Y') AS date,
TIME_FORMAT(j.time, '%H:%i %p') AS time,
h.name AS hotel_name,
p.picname AS person_name,
p.email AS person_email,
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .count-box {
            background: #fff;
            transition: all 0.3s ease-in-out;
            padding: 0px 10px;
            border-radius: 10px;
        }

        .count-box:hover {
            background: #007bff;
        }

        .count-box:hover .count-header .link-box {
            border: 1px solid #fff;
        }

        .count-box:hover .count-header .link-box i {
            color: #fff;
        }

        .count-box:hover .count-header .count-title {
            color: #fff;
        }

        .count-box:hover .count-body p {
            color: #fff;
        }

        .count-box:hover .count-body span {
            color: #fff;
        }

        .count-header {
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .count-title {
            font-size: 16px;
            font-weight: bold;
            /* color: #007bff; */
            color: #000;
            padding: 0px 10px;
        }

        .count-body p {
            font-size: 30px;
            font-weight: bold;
            color: #000;
            padding: 10px;
        }

        .count-body span {
            font-size: 14px;
            font-weight: lighter !important;
            color: darkgrey;
        }

        .link-box {
            border: 1px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .link-box i {
            color: #007bff;
            transform: rotate(45deg);
            transition: all 0.3s ease;
        }

        .link-box:hover {
            background-color: #fff;
        }

        .link-box:hover i {
            color: #007bff !important;
            transform: rotate(90deg);
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="jobsheetDetailsBody">

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row my-3">
            <div class="col-lg-12">
                <div class="breadcrumb-container d-flex flex-row bg-white justify-content-between align-items-center">
                    <nav aria-label="breadcrumb" class="">
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
            <div class="col-lg-6 col-12">
                <div>
                    <div class="row">
                        <div class="col-lg-6 col-12 mb-sm-3 mb-lg-0">
                            <div class="border count-box box">
                                <div class="count-header">
                                    <p class="mb-0 count-title">Total Jobsheet</p>
                                    <div class="link-box ">
                                        <a href="#"><i class="fa-solid fa-arrow-up"></i></a>
                                    </div>
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
                                <div class="count-header">
                                    <p class="mb-0 count-title">Total PIC</p>
                                    <div class="link-box ">
                                        <a href="#"><i class="fa-solid fa-arrow-up"></i></a>
                                    </div>
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
                        <div class="col-lg-6 col-12 mb-sm-3 mb-lg-0 mb-3">
                            <div class="border count-box box">
                                <div class="count-header">
                                    <p class="mb-0 count-title">Total Hotels</p>
                                    <div class="link-box ">
                                        <a href="#"><i class="fa-solid fa-arrow-up"></i></a>
                                    </div>
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
                            <div class="border count-box box mb-sm-3 mb-lg-0 mb-3">
                                <div class="count-header">
                                    <p class="mb-0 count-title">Total Items</p>
                                    <div class="link-box ">
                                        <a href="#"><i class="fa-solid fa-arrow-up"></i></a>
                                    </div>
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
                <div class="border bg-white rounded-3 p-3 h-100 fw-bold">
                    <div class="job-list-header d-flex justify-content-between">
                        <h5 class="mb-3"><i class="fa-regular text-primary fa-bell pe-3"></i>Jobs Updates</h5>
                        <div data-bs-toggle="tooltip" data-bs-placement="top" title="View All">
                            <a target="_blank" href="jobsheet-list.php" class="text-grey px-3">
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 13px;"></i>
                            </a>
                        </div>
                    </div>

                    <?php while ($row = $jobsheetResult->fetch_assoc()): ?>
                        <?php
                        $datetime1 = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));

                        $datetimeString = $row['date'] . ' ' . $row['time']; // e.g., "24 July 2025 02:00 PM"
                        $datetime2 = DateTime::createFromFormat('d F Y h:i A', $datetimeString, new DateTimeZone('Asia/Kuala_Lumpur'));

                        if (!$datetime2) {
                            echo "Failed to parse datetime";
                        } else {
                            $interval = $datetime1->diff($datetime2);

                            if ($interval->d > 0) {
                                $time = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
                            } else if ($interval->h > 0) {
                                $time = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
                            } else if ($interval->i > 0) {
                                $time = $interval->i . ' min ago';
                            } else {
                                $time = 'just now';
                            }

                        }


                        $currDate = $row['date'];
                        $task_type = $row['task_type'];
                        $id = $row['id'];

                        $dotcolor = ($task_type == "installation") ? "text-success" : (($task_type == "troubleshoot") ? "text-warning" : "text-warning");
                        $icon = ($task_type == "installation") ? "fa-wrench" : (($task_type == "troubleshoot") ? "fa-gear" : "fa-wrench");
                        ?>

                        <div class="job-list-item border position-relative p-2 rounded-3 d-flex flex-row mb-2">
                            <div class="d-flex align-items-center ps-2 pe-3">
                                <div class="job-list-icon">
                                    <i class="fa-solid fa-circle <?php echo $dotcolor; ?>" style="font-size: 8px;"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0 job-list-title text-capitalize"><i
                                        class="fa-solid <?php echo $icon; ?> pe-2"></i><?php echo $task_type; ?> at
                                    <?php echo $row["hotel_name"]; ?>
                                </p>
                                <small id="jobTime" class="text-muted fw-lighter"><?php echo $time; ?></small>
                            </div>
                            <div class="position-absolute top-50 end-0 translate-middle">
                                <!-- <a class="view-details-btn" style="cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#jobsheetDetailsModal" data-id="<?php echo $row['id']; ?>">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                                </a> -->
                                <a href="generatepdf.php?id=<?= $row['id'] ?>" class="text-primary" target="_blank">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-8">
                <div class="border bg-white rounded-3 p-3 fw-bold">
                    <canvas id="jobsheetChart" width="100%" height=""></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="border bg-white rounded-3 h-100 p-3 fw-bold">

                </div>
            </div>
        </div>
        <div class="row mt-4 d-none">
            <div class="col-lg-12">
                <h4 class="py-2">Jobsheet List</h4>
                <div class="table-responsive">
                    <table id="jobsheetTable" class="table table-bordered table-striped mb-5">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Hotel Name</th>
                                <th>Task</th>
                                <th>PIC</th>
                                <th>Description</th>
                                <th>Items Used</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $jobsheetResult1->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row["date"]; ?></td>
                                    <td><?php echo $row["time"]; ?></td>
                                    <td><?php echo $row["hotel_name"]; ?></td>
                                    <td><?php echo $row["task_type"]; ?></td>
                                    <td><?php echo $row["person_name"]; ?></td>
                                    <td><?php echo $row["description"]; ?></td>
                                    <td><?php echo $row["items_used"]; ?></td>
                                    <td>
                                        <div class="d-flex flex-row" style="gap: 5px;">
                                            <button class="btn btn-sm btn-secondary view-details-btn" data-bs-toggle="modal"
                                                data-bs-target="#jobsheetDetailsModal" data-id="<?php echo $row['id']; ?>">
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
    </div>
    <script>
        fetch('jobsheet-data-chart.php')
            .then(res => res.json())
            .then(data => {
                const labels = data.map(row => row.month);
                const values = data.map(row => row.total);

                new Chart(document.getElementById('jobsheetChart'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jobsheets per Month',
                            data: values,
                            borderColor: 'rgb(75, 192, 192)'
                        }]
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            });
    </script>
    <script>
        //view modal details on jobsheet
        $(document).ready(function () {
            $('.view-details-btn').on('click', function () {
                const jobsheetId = $(this).data('id');

                $.ajax({
                    url: 'jobsheet/get-jobsheet-details.php',
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
                        url: 'jobsheet/jobsheet-delete.php',
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