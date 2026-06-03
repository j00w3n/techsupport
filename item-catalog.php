<?php
include 'db.php';
$hotelResult = $conn->query("SELECT * FROM hotel");
$itemResult = $conn->query("SELECT items.id, items.name, inventory.stock_quantity FROM inventory JOIN items ON inventory.item_id = items.id");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support - Inventory</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link class="hidden" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" crossorigin="anonymous"></script>
</head>

<body class="bg-slate-100 text-slate-800 font-sans antialiased overflow-x-hidden">

    <?php include 'navbar.php'; ?>

    <div id="addItemModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden transform transition-all">
            <div class="bg-slate-950 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <h5 class="text-md font-bold text-white uppercase tracking-wider">
                    <i class="fas fa-plus-circle border border-slate-700 p-1.5 rounded text-sky-500 mr-2"></i>Add New Item
                </h5>
                <button type="button" onclick="closeModal('addItemModal')" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form action="add-item.php" method="post" class="p-6 space-y-4">
                <div>
                    <label for="itemName" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Item Name</label>
                    <input type="text" id="itemName" name="itemName" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div>
                    <label for="itemQuantity" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Initial Stock Quantity</label>
                    <input type="number" id="itemQuantity" name="itemQuantity" min="0" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-md transition w-full justify-center">
                        <i class="fa-solid fa-plus"></i> Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editItemModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden transform transition-all">
            <div class="bg-slate-950 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <h5 class="text-md font-bold text-white uppercase tracking-wider">
                    <i class="fas fa-edit border border-slate-700 p-1.5 rounded text-amber-500 mr-2"></i>Edit Item Stock
                </h5>
                <button type="button" onclick="closeModal('editItemModal')" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="editItemForm" method="post" action="edit-item.php" class="p-6 space-y-4">
                <input type="hidden" id="editItemId" name="item_id">
                <div>
                    <label for="editItemName" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Item Name</label>
                    <input type="text" id="editItemName" name="item_name" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div>
                    <label for="editItemQuantity" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Stock Quantity</label>
                    <input type="number" id="editItemQuantity" name="stock_quantity" min="0" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-md transition w-full justify-center">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-4 rounded-xl border border-slate-200 shadow-sm gap-4">
            <nav class="text-sm font-medium text-slate-500">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="index.php" class="text-slate-600 hover:text-sky-600 transition"><i class="fas fa-home mr-2"></i>Home</a>
                    </li>
                    <li class="flex items-center text-slate-400">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-slate-400">Inventory</span>
                    </li>
                </ol>
            </nav>
            <a href="index.php" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-pen"></i> Jobsheet Form
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-boxes text-sky-500"></i> Hardware & Material Inventory
                </h4>
                <button type="button" onclick="openModal('addItemModal')" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-circle-plus"></i> Add Item
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table id="itemTable" class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-xs uppercase tracking-wider select-none">
                            <th class="p-3 border border-slate-800 text-center w-[8%] whitespace-nowrap overflow-hidden text-ellipsis">No</th>
                            <th class="p-3 border border-slate-800 whitespace-nowrap overflow-hidden text-ellipsis">Item Name</th>
                            <th class="p-3 border border-slate-800 w-[20%] whitespace-nowrap overflow-hidden text-ellipsis">Available Stock</th>
                            <th class="p-3 border border-slate-800 text-center w-[12%] whitespace-nowrap overflow-hidden text-ellipsis">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php $i = 1;
                        while ($row = $itemResult->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50 transition text-slate-700">
                                <td class="p-3 text-center font-medium text-slate-400 bg-slate-50/50"><?= $i++ ?></td>
                                <td class="p-3 font-semibold text-slate-900"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="p-3 font-mono font-bold <?= $row['stock_quantity'] <= 3 ? 'text-red-600' : 'text-slate-600' ?>">
                                    <?= htmlspecialchars($row['stock_quantity']) ?>
                                    <?php if ($row['stock_quantity'] <= 3): ?>
                                        <span class="ml-1.5 text-[10px] font-sans font-bold uppercase tracking-wider px-1.5 py-0.5 bg-red-50 border border-red-200 rounded text-red-500">Low</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 text-center">
                                    <button type="button" class="edit-item-btn p-2 bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-700 rounded-md transition inline-flex items-center justify-center"
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-quantity="<?= htmlspecialchars($row['stock_quantity']) ?>">
                                        <i class="fa-solid fa-edit text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // Modal State Controls
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        $(document).ready(function() {
            // Push values into the Edit Modal fields
            $('.edit-item-btn').on('click', function() {
                const itemId = $(this).data('id');
                const itemName = $(this).data('name');
                const itemQuantity = $(this).data('quantity');

                $('#editItemId').val(itemId);
                $('#editItemName').val(itemName);
                $('#editItemQuantity').val(itemQuantity);

                openModal('editItemModal');
            });

            // Parse URL parameters for status alerts
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