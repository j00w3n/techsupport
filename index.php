<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support - Jobsheet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-slate-100 text-slate-800 font-sans antialiased">

    <?php include 'navbar.php'; ?>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">

            <div class="bg-slate-900 px-6 py-4 border-b border-slate-800">
                <h3 class="text-xl font-bold text-white tracking-wide uppercase flex items-center gap-2">
                    <i class="fas fa-file-invoice text-sky-500"></i> Tech Support Jobsheet
                </h3>
            </div>

            <form action="jobsheet/jobsheet-submit.php" method="post" class="p-6 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <div>
                        <label for="date" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Date</label>
                        <input type="date" id="date" name="date" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150">
                    </div>
                    <div>
                        <label for="time" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Time</label>
                        <input type="time" id="time" name="time" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="hotelname" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Hotel Name</label>
                        <select id="hotelname" name="hotelname" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150">
                            <option value="" disabled selected>Select a hotel</option>
                            <?php
                            include 'db.php';
                            $stmt = $conn->prepare("SELECT * FROM hotel");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "' data-email='" . $row['email'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-3">Type of Task</span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="task_type">
                            <?php
                            $tasks = ['troubleshoot' => 'Troubleshoot', 'installation' => 'Installation', 'dismantle' => 'Dismantle', 'maintanance' => 'Maintenance'];
                            foreach ($tasks as $value => $label): ?>
                                <label class="flex items-center justify-center p-3 border border-slate-200 rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition duration-150 [&:has(input:checked)]:bg-sky-50 [&:has(input:checked)]:border-sky-500 [&:has(input:checked)]:text-sky-700">
                                    <input type="radio" name="task" value="<?= $value ?>" class="sr-only" <?= $value == 'troubleshoot' ? 'checked' : '' ?>>
                                    <span class="text-sm font-medium"><?= $label ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div id="section-troubleshoot" class="space-y-4 border-t border-slate-200 pt-4">
                    <div>
                        <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">DESCRIPTION</label>
                        <textarea id="description" name="description" rows="10" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" placeholder="Describe client's issue..."></textarea>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-4 hidden" id="section-installation">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Items Used / Updated</label>
                    <div id="item-container" class="space-y-3">
                        <div class="flex gap-2 item-row items-center">
                            <div class="flex-1">
                                <select name="item[]" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150">
                                    <option value="" selected>Select an item</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM items");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="w-24">
                                <select name="quantity[]" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150">
                                    <?php for ($i = 1; $i <= 15; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="button" class="remove-item p-2 text-slate-400 hover:text-red-500 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="addItem" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-sky-600 hover:text-sky-700 transition duration-150">
                        <i class="fas fa-plus-circle"></i> Add Another Item
                    </button>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <div class="relative flex py-2 items-center mb-4">
                        <div class="flex-grow border-t border-slate-200"></div>
                        <span class="flex-shrink mx-4 text-xs font-bold uppercase tracking-widest text-slate-400">Client Sign-Off & PIC</span>
                        <div class="flex-grow border-t border-slate-200"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="newstaff" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Person In Charge (PIC)</label>
                            <input type="text" id="newstaff" name="newstaff" required
                                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150 text-slate-800"
                                placeholder="Enter PIC name (e.g. Haziq)">
                        </div>

                        <div>
                            <label for="picemail" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Email Address</label>
                            <input type="email" id="picemail" name="picemail" readonly
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md shadow-sm text-slate-500 focus:outline-none"
                                placeholder="hotel@email.com">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Client Signature</label>
                        <div class="border border-slate-300 rounded-md bg-white shadow-sm overflow-hidden">
                            <canvas id="signature-pad" class="w-full h-48 block bg-slate-50"></canvas>
                        </div>
                        <input type="hidden" id="signature_data" name="signature_image">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-150 uppercase tracking-wider text-sm flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Jobsheet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@5.1/dist/signature_pad.umd.min.js" integrity="sha256-DYq7w7p8ljuA7cpV0a7QQ4O2GU6atSHKl3qDL5sNxcQ=" crossorigin="anonymous"></script>
    <script>
        const canvas = document.getElementById('signature-pad');

        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)', 
            penColor: 'rgb(0, 0, 0)' 
        });
        $('form').on('submit', function(e) {

            if (signaturePad.isEmpty()) {
                alert("Please provide a client signature before submitting.");
                e.preventDefault();
                return false;
            }
            const dataURL = signaturePad.toDataURL('image/png');
            $('#signature_data').val(dataURL);
        });
        $(document).ready(function() {

            // 1. Define English templates (Maintaining 'maintanance' to match your PHP value)
            const descriptionTemplates = {
                'troubleshoot': 'Our team conducted a thorough inspection of the system reported to be experiencing downtime/disruption. Upon detailed investigation, the root cause was identified at [PLEASE SPECIFY ROOT CAUSE]. \nCorrective actions were taken by [PLEASE SPECIFY ACTIONS TAKEN]. Final testing confirms that the system is now fully operational and running stably. \n\n All Channel running okay.',

                'installation': 'The installation of new infrastructure was successfully executed at [PLEASE SPECIFY LOCATION/ROOM]. The components deployed involve [PLEASE SPECIFY EQUIPMENT]. All network configurations have been aligned according to standard specifications and are fully ready for operational use.',

                'dismantle': 'Dismantling and decommissioning works of the old equipment components were carried out at [PLEASE SPECIFY LOCATION]. The hardware includes [PLEASE SPECIFY EQUIPMENT]. \n\nAll uninstalled components have been cataloged and safely stored/disposed of in accordance with standard procedures.',

                'maintanance': 'Preventive maintenance was carried out on [VF28HD/VIVNEWS]. The scope of work included physical cleaning, system log reviews, and firmware updates. No critical errors were found, and the overall system performance is at its optimum level.'
            };

            // Common function to inject text into textarea
            function updateDescription(taskValue) {
                if (descriptionTemplates[taskValue]) {
                    $('#description').val(descriptionTemplates[taskValue]);
                } else {
                    $('#description').val('');
                }
            }

            // 2. AUTO-FILL ON FIRST LOAD: Fetch the default checked radio button (Troubleshoot)
            const initialTask = $('input[name="task"]:checked').val();
            if (initialTask) {
                updateDescription(initialTask);
            }

            // 3. TRIGGER ON RADIO BUTTON CHANGE
            $('input[name="task"]').on('change', function() {
                const selectedTask = $(this).val();
                updateDescription(selectedTask);
            });

        });
    </script>
    <script>
        // Add item row dynamic cloning
        document.getElementById('addItem').addEventListener('click', function() {
            const container = document.getElementById('item-container');
            const firstRow = container.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            container.appendChild(newRow);
        });

        // Delete row logic
        document.getElementById('item-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                }
            }
        });
        // Contoh jika kau guna SignaturePad library atau Javascript biasa masa hantar form:
        $(document).ready(function() {

            // Bila drop-down hotel ditukar (change event)
            $('#hotelname').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const hotelEmail = selectedOption.data('email');
                if (hotelEmail) {
                    $('#picemail').val(hotelEmail);
                } else {
                    $('#picemail').val('');
                }
            });

        });
    </script>
</body>

</html>