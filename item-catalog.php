<?php
include 'db.php';
$hotelResult = $conn->query("SELECT * FROM hotel");
$itemResult = $conn->query("SELECT items.id, items.name, inventory.stock_quantity FROM inventory JOIN items ON inventory.item_id = items.id
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.html'; ?>
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHotelModalLabel">Add Item</h5>
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
                        <div class="form-group">
                            <label for="itemQuantity">Initial Stock Quantity</label>
                            <input type="number" class="form-control" id="itemQuantity" name="itemQuantity" min="0"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Add<i class="fa-solid fa-plus pl-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editItemForm" method="post" action="edit-item.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editItemId" name="item_id">

                        <div class="form-group">
                            <label for="editItemName">Item Name</label>
                            <input type="text" class="form-control" id="editItemName" name="item_name" required>
                        </div>

                        <div class="form-group">
                            <label for="editItemQuantity">Stock Quantity</label>
                            <input type="number" class="form-control" id="editItemQuantity" name="stock_quantity"
                                min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
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
                            <li class="breadcrumb-item active" aria-current="page">Inventory</li>
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
                <h4>Inventory</h4>
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#addItemModal">
                                <i class="fa-solid fa-circle-plus pe-2"></i>Add Item
                            </button>
                        </div>
                    </div>
                </div>
                <table id="itemTable" class="table table-bordered table-striped mb-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        while ($row = $itemResult->fetch_assoc()): ?>
                            <tr>
                                <td style="width: 5%; white-space: nowrap;"><?= $i++ ?></td>
                                <td style="width: 70%; white-space: nowrap;"><?= $row['name'] ?></td>
                                <td style="width: 20%; white-space: nowrap;"><?= $row['stock_quantity'] ?></td>
                                <td class="text-center" style="width: 30%; white-space: nowrap;">
                                    <a class="text-dark edit-item-btn" data-toggle="modal"
                                        data-target="#editItemModal" data-id="<?= $row['id'] ?>"
                                        data-name="<?= $row['name'] ?>" data-quantity="<?= $row['stock_quantity'] ?>">
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
            // Handle edit item button click
            $('.edit-item-btn').on('click', function () {
                const itemId = $(this).data('id');
                const itemName = $(this).data('name');
                const itemQuantity = $(this).data('quantity');

                $('#editItemId').val(itemId);
                $('#editItemName').val(itemName);
                $('#editItemQuantity').val(itemQuantity);
            });
            const url = new URL(window.location.href);
            const updated = url.searchParams.get('updated');
            if (updated) {
                alert("Item updated successfully!");
                url.searchParams.delete('updated');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
        });
    </script>
</body>

</html>