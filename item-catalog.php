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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                    <input type="number" class="form-control" id="editItemQuantity" name="stock_quantity" min="0" required>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="container pt-4">
        <div class="row mb-4">
            <div class="col-lg-12">
                <h4>Inventory</h4>
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#addItemModal">
                                <i class="fa-solid fa-circle-plus pr-2"></i>Add Item
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        while ($row = $itemResult->fetch_assoc()): ?>
                            <tr>
                                <td style="width: 5%; white-space: nowrap;"><?= $i++ ?></td>
                                <td style="width: 95%; white-space: nowrap;"><?= $row['name'] ?></td>
                                <td style="width: 95%; white-space: nowrap;"><?= $row['stock_quantity'] ?></td>
                                <td style="width: 5%; white-space: nowrap;">
                                    <button class="btn btn-sm btn-warning edit-item-btn" data-toggle="modal"
                                        data-target="#editItemModal" data-id="<?= $row['id'] ?>"
                                        data-name="<?= $row['name'] ?>" data-quantity="<?= $row['stock_quantity'] ?>">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
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