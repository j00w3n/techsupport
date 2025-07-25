<?php
include 'db.php';
$hotelResult = $conn->query("SELECT * FROM hotel");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Modal add hotel -->
    <div class="modal fade" id="addHotelModal" tabindex="-1" aria-labelledby="addHotelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Hotel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="hotel/add-hotel.php" method="post">
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
                                class="ps-2">Add</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editHotelModal" tabindex="-1" aria-labelledby="editHotelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editHotelForm" method="post" action="hotel/edit-hotel.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Edit Hotel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editHotelId" name="id">

                        <div class="form-group">
                            <label for="editItemName">Hotel Name</label>
                            <input type="text" class="form-control" id="editHotelName" name="hotelName" required>
                        </div>
                        <div class="form-group">
                            <label for="hotelState">State</label>
                            <select class="form-control" id="edithotelState" name="hotelState" required>
                                <option value="" disabled>Select a state</option>
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
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'navbar.html'; ?>
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
        <div class="row mb-4">
            <div class="col-lg-12">
                <h4>Hotel List</h4>
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addHotelModal"><i class="fa-solid fa-circle-plus pe-2"></i>
                                Add Hotel
                            </button>
                        </div>
                    </div>
                </div>
                <table id="hotelTable" class="table table-light table-bordered table-striped mb-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>State</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        while ($row = $hotelResult->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center" style="width: 5%;"><?= $i++ ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['state'] ?></td>
                                <td class="text-center">
                                    <a class="text-dark edit-hotel-btn" data-bs-toggle="modal"
                                        data-bs-target="#editHotelModal" data-id="<?= $row['id'] ?>"
                                        data-name="<?= $row['name'] ?>" data-state="<?= $row['state'] ?>">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            $('.edit-hotel-btn').on('click', function () {
                const hotelid = $(this).data('id');
                const hotelName = $(this).data('name');
                const hotelState = $(this).data('quantity');

                $('#editHotelId').val(hotelid);
                $('#editHotelName').val(hotelName);
                $('#editHotelState').val(hotelState).prop('selected', true);
            });

            const url = new URL(window.location.href);
            const updated = url.searchParams.get('updated');
            const added = url.searchParams.get('added');
            if (updated === '1') {
                alert("Hotel updated successfully!");
                url.searchParams.delete('updated');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
            if (added === '1') {
                alert("Hotel added successfully!");
                url.searchParams.delete('added');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
        });
    </script>
</body>

</html>