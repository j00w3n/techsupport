<?php
include 'db.php';
$hotelResult = $conn->query("SELECT * FROM hotel");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support - Hotels</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" crossorigin="anonymous"></script>
</head>

<body class="bg-slate-100 text-slate-800 font-sans antialiased overflow-x-hidden">

    <?php include 'navbar.php'; ?>

    <!-- general modal message -->
    <div id="general_msg_modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-200">
        <div class="bg-white rounded-xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-200" id="modalBox">

            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800" id="gen_modal_title">Notification</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-200/60 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-5 py-6">
                <p class="text-sm text-slate-600 leading-relaxed" id="gen_modal_message">Message goes here...</p>
            </div>

            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button onclick="closeModal('general_msg_modal')" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                    Okay
                </button>
            </div>
        </div>
    </div>

    <div id="addHotelModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden transform transition-all">
            <div class="bg-slate-950 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <h5 class="text-md font-bold text-white uppercase tracking-wider">
                    <i class="fas fa-plus border border-slate-700 p-1.5 rounded text-sky-500 mr-2"></i>Add New Hotel
                </h5>
                <button type="button" onclick="closeModal('addHotelModal')" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form action="hotel/add-hotel.php" method="post" class="p-6 space-y-4">
                <div>
                    <label for="hotelName" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Hotel Name</label>
                    <input type="text" id="hotelName" name="hotelName" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="mt-4">
                    <label for="hotelEmail" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Hotel Email</label>
                    <input type="email" id="hotelEmail" name="hotelEmail" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="mt-4">
                    <label for="hotelPerson" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Point of Contact</label>
                    <input type="text" id="hotelPerson" name="hotelPerson" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="mt-4">
                    <label for="hotelDesignation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">PIC Designation</label>
                    <input type="text" id="hotelDesignation" name="hotelDesignation" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div>
                    <label for="hotelState" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">State / Region</label>
                    <select id="hotelState" name="hotelState" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                        <option value="" disabled selected>Select a state</option>
                        <?php
                        $states = ["Johor", "Kedah", "Kelantan", "Malacca", "Negeri Sembilan", "Pahang", "Penang", "Perak", "Perlis", "Sabah", "Sarawak", "Selangor", "Terengganu", "Kuala Lumpur", "Labuan", "Putrajaya"];
                        foreach ($states as $state) echo "<option value='$state'>$state</option>";
                        ?>
                    </select>
                </div>
                <div class="pt-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-md transition w-full justify-center">
                        <i class="fa-solid fa-plus"></i> Save Hotel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editHotelModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden transform transition-all">
            <div class="bg-slate-950 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <h5 class="text-md font-bold text-white uppercase tracking-wider">
                    <i class="fas fa-edit border border-slate-700 p-1.5 rounded text-amber-500 mr-2"></i>Edit Hotel Details
                </h5>
                <button type="button" onclick="closeModal('editHotelModal')" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="editHotelForm" method="post" action="hotel/edit-hotel.php" class="p-6 space-y-4">
                <input type="hidden" id="editHotelId" name="id">
                <div>
                    <label for="editHotelName" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Hotel Name</label>
                    <input type="text" id="editHotelName" name="hotelName" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="mt-4">
                    <label for="editHotelEmail" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Hotel Email</label>
                    <input type="email" id="editHotelEmail" name="hotelEmail" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="mt-4">
                    <label for="editHotelPerson" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Point of Contact</label>
                    <input type="text" id="editHotelPerson" name="hotelPerson" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div class="mt-4">
                    <label for="editHotelDesignation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">PIC Designation</label>
                    <input type="text" id="editHotelDesignation" name="hotelDesignation" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                </div>
                <div>
                    <label for="edithotelState" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">State / Region</label>
                    <select id="edithotelState" name="hotelState" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                        <option value="" disabled>Select a state</option>
                        <?php
                        foreach ($states as $state) echo "<option value='$state'>$state</option>";
                        ?>
                    </select>
                </div>
                <div class="pt-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-md transition w-full justify-center">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="deleteHotelModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden transform transition-all">
            <div class="bg-slate-950 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <h5 class="text-md font-bold text-white uppercase tracking-wider">
                    <i class="fas fa-trash border border-slate-700 p-1.5 rounded text-red-500 mr-2"></i>Confirm Hotel Deletion
                </h5>
                <button type="button" onclick="closeModal('deleteHotelModal')" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-sm text-slate-700">Are you sure you want to delete this hotel? This action cannot be undone.</p>
                <form id="deleteHotelForm" method="post" action="hotel/delete-hotel.php" class="flex justify-end">
                    <input type="hidden" id="deleteHotelId" name="id">
                    <button type="submit" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-md transition">
                        <i class="fa-solid fa-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>
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
                        <span class="text-slate-400">Hotels</span>
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
                    <i class="fas fa-hotel text-sky-500"></i> Hotel Database Management
                </h4>
                <button type="button" onclick="openModal('addHotelModal')" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-circle-plus"></i> Add Hotel
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table id="hotelTable" class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-xs uppercase tracking-wider select-none">
                            <th class="p-3 border border-slate-800 text-center w-[5%]">No</th>
                            <th class="p-3 border border-slate-800">Hotel Name</th>
                            <th class="p-3 border border-slate-800">Hotel Email</th>
                            <th class="p-3 border border-slate-800">Person</th>
                            <th class="p-3 border border-slate-800">Designation</th>
                            <th class="p-3 border border-slate-800 w-[25%]">State / Region</th>
                            <th class="p-3 border border-slate-800 text-center w-[12%]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php $i = 1;
                        while ($row = $hotelResult->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50 transition text-slate-700">
                                <td class="p-3 text-center font-medium text-slate-400 bg-slate-50/50"><?= $i++ ?></td>
                                <td class="p-3 font-semibold uppercase text-slate-900"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="p-3 font-semibold text-slate-900"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="p-3 font-medium text-slate-600"><?= htmlspecialchars($row['picname']) ?></td>
                                <td class="p-3 font-medium text-slate-600"><?= htmlspecialchars($row['pic_main_desg']) ?></td>
                                <td class="p-3 font-medium text-slate-600"><?= htmlspecialchars($row['state']) ?></td>
                                <td class="p-3 text-center">
                                    <button type="button" class="edit-hotel-btn p-2 bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-700 rounded-md transition inline-flex items-center justify-center"
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-email="<?= htmlspecialchars($row['email']) ?>"
                                        data-picname="<?= htmlspecialchars($row['picname']) ?>"
                                        data-pic_main_desg="<?= htmlspecialchars($row['pic_main_desg']) ?>"
                                        data-state="<?= htmlspecialchars($row['state']) ?>">
                                        <i class="fa-solid fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" class="delete-hotel-btn p-2 bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-700 rounded-md transition inline-flex items-center justify-center"
                                        data-id="<?= $row['id'] ?>">
                                        <i class="fa-solid fa-trash text-red-500 hover:text-red-700 transition"></i>
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
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function showGeneralModal(title, message) {
            document.getElementById('gen_modal_title').innerText = title;
            document.getElementById('gen_modal_message').innerText = message;
            openModal('general_msg_modal');
        }

        $(document).ready(function() {
            // Trigger Data to Edit Modal
            $('.edit-hotel-btn').on('click', function() {
                const hotelid = $(this).data('id');
                const hotelName = $(this).data('name');
                const hotelState = $(this).data('state'); // Ditukar dari 'quantity' ke 'state'
                const hotelEmail = $(this).data('email');
                const hotelPerson = $(this).data('picname');
                const hotelDesignation = $(this).data('pic_main_desg');

                $('#editHotelId').val(hotelid);
                $('#editHotelName').val(hotelName);
                $('#edithotelState').val(hotelState);
                $('#editHotelEmail').val(hotelEmail);
                $('#editHotelPerson').val(hotelPerson);
                $('#editHotelDesignation').val(hotelDesignation);

                openModal('editHotelModal');
                
            });
            // Trigger Delete Modal
            $('.delete-hotel-btn').on('click', function() {
                const hotelid = $(this).data('id');
                $('#deleteHotelId').val(hotelid);
                openModal('deleteHotelModal');
                showGeneralModal("Success!", "Hotel deleted successfully!");
            });
        });

        $(document).ready(function() {

            // Handling Status Notifications from Response Query Params
            const url = new URL(window.location.href);
            const updated = url.searchParams.get('updated');
            const added = url.searchParams.get('added');

            if (updated === '1') {
                showGeneralModal("Success!", "Hotel updated successfully!");
                url.searchParams.delete('updated');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
            if (added === '1') {
                showGeneralModal("Success!", "Hotel added successfully!");
                url.searchParams.delete('added');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
        });
    </script>
</body>

</html>