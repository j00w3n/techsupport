<?php
include 'db.php';
$hotelResult = $conn->query("SELECT * FROM hotel");
$itemResult = $conn->query(query: "SELECT * FROM items");

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <?php include 'navbar .html'; ?>
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHotelModalLabel">Add Hotel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="add-item.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="itemName">Item Name</label>
                            <input type="text" class="form-control" id="itemName" name="itemName" required>
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
    <div class="container pt-4">
    <div class="row mb-4">
            <div class="col-lg-12">
                <h4>Item List</h4>
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#addItemModal">
                                Add Item
                            </button>
                        </div>
                    </div>
                </div>
                <table id="itemTable" class="table table-bordered table-striped mb-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = $itemResult->fetch_assoc()): ?>
                            <tr>
                                <td style="width: 5%; white-space: nowrap;"><?= $i++ ?></td>
                                <td style="width: 95%; white-space: nowrap;"><?= $row['name'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>