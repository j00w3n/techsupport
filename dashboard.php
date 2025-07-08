<?php
include 'db.php';
$stmt = $conn->prepare("SELECT * FROM jobsheet");
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />

    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">VIVTech Support</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Form</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Dashboard Jobsheet</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <a href="index.php">form</a>

                <table id="jobsheetTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Hotel Name</th>
                            <th>Complaint</th>
                            <th>Fault</th>
                            <th>Repair</th>
                            <th>Part Replaced</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row["date"]; ?></td>
                                <td><?php echo $row["time"]; ?></td>
                                <td><?php echo $row["hotelname"]; ?></td>
                                <td><?php echo $row["complaint"]; ?></td>
                                <td><?php echo $row["fault"]; ?></td>
                                <td><?php echo $row["repair"]; ?></td>
                                <td><?php echo $row["partreplaced"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var table = $("#jobsheetTable").DataTable(
                {
                    columnDefs: [
                        {
                            targets: [0, 1, 2, 3, 4, 5, 6],
                            className: "dt-center"
                        }
                    ]
                }
            );
        });
    </script>
</body>

</html>