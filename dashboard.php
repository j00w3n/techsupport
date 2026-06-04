<?php
include 'db.php';

// 1. Tarik 3 jobsheet terbaru (Untuk paparan Recent Activity / Quick View)
$jobsheetResult = $conn->query("
SELECT 
    j.*,
    DATE_FORMAT(j.date, '%d %M %Y') AS date,
    TIME_FORMAT(j.time, '%H:%i %p') AS time,
    h.name AS hotel_name,
    j.pic_name AS person_name,   -- 🌟 TUKAR SINI: Ambil terus dari jobsheet
    j.pic_email AS person_email, -- 🌟 TUKAR SINI: Ambil terus dari jobsheet
    GROUP_CONCAT(CONCAT(i.name, ' (x', ji.quantity, ')') SEPARATOR ', ') AS items_used
FROM 
    jobsheet j
JOIN 
    hotel h ON j.hotel_id = h.id
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

// 2. Tarik semua jobsheet (Untuk paparan Master List penuh)
$jobsheetResult1 = $conn->query("
SELECT 
    j.*,
    DATE_FORMAT(j.date, '%d %M %Y') AS date,
    TIME_FORMAT(j.time, '%H:%i %p') AS time,
    h.name AS hotel_name,
    j.pic_name AS person_name,   -- 🌟 TUKAR SINI: Ambil terus dari jobsheet
    j.pic_email AS person_email, -- 🌟 TUKAR SINI: Ambil terus dari jobsheet
    GROUP_CONCAT(CONCAT(i.name, ' (x', ji.quantity, ')') SEPARATOR ', ') AS items_used
FROM 
    jobsheet j
JOIN 
    hotel h ON j.hotel_id = h.id
LEFT JOIN 
    jobsheet_items ji ON j.id = ji.jobsheet_id
LEFT JOIN 
    items i ON ji.item_id = i.id
GROUP BY 
    j.id
ORDER BY 
    j.date DESC, j.time DESC; -- Ditambah susunan masa sekali supaya tersusun rapat
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support - Dashboard</title>

    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- JQuery, DataTables & Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-slate-100 text-slate-800 font-sans antialiased overflow-x-hidden">

    <?php include 'navbar.php'; ?>

    <!-- Modal for Jobsheet Details -->
    <div id="jobsheetDetailsModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-2xl w-full overflow-hidden transform transition-all">
            <div class="bg-slate-950 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <h5 class="text-md font-bold text-white uppercase tracking-wider" id="jobsheetDetailsModalLabel">
                    <i class="fas fa-info-circle text-sky-500 mr-2"></i>Jobsheet Details
                </h5>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto text-slate-700" id="jobsheetDetailsBody">
                <!-- Content injected via AJAX -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Breadcrumb & Top Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-4 rounded-xl border border-slate-200 shadow-sm gap-4">
            <nav class="text-sm font-medium text-slate-500">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="index.php" class="text-slate-600 hover:text-sky-600 transition"><i class="fas fa-home mr-2"></i>Home</a>
                    </li>
                    <li class="flex items-center text-slate-400">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-slate-400">Dashboard</span>
                    </li>
                </ol>
            </nav>
            <a href="index.php" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus"></i> New Jobsheet
            </a>
        </div>

        <!-- Main Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Counter Cards -->
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4 content-start">

                <!-- Card 1: Total Jobsheet -->
                <a href="jobsheet-list.php" class="group block bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:bg-slate-900 hover:border-slate-800 transition duration-300 flex flex-col justify-between h-36 cursor-pointer decoration-none">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 group-hover:text-slate-400">Total Jobsheet</span>
                        <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-slate-700 group-hover:text-sky-400 transition duration-300">
                            <i class="fas fa-file-alt text-sm"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black tracking-tight text-slate-900 group-hover:text-white transition duration-300">
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) AS total_jobsheet FROM jobsheet");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            echo $row['total_jobsheet'];
                            $stmt->close(); // Tutup stmt untuk amalan coding yang baik
                            ?>
                        </span>
                        <span class="text-xs font-normal text-slate-400 group-hover:text-slate-500">sheets</span>
                    </div>
                </a>

                <a href="hotel.php" class="group block bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:bg-slate-900 hover:border-slate-800 transition duration-300 flex flex-col justify-between h-36 cursor-pointer decoration-none">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 group-hover:text-slate-400">Total Hotels</span>
                        <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-slate-700 group-hover:text-sky-400 transition duration-300">
                            <i class="fas fa-hotel text-sm"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black tracking-tight text-slate-900 group-hover:text-white transition duration-300">
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) AS total_hotel FROM hotel");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            echo $row['total_hotel'];
                            $stmt->close(); // Tutup stmt
                            ?>
                        </span>
                        <span class="text-xs font-normal text-slate-400 group-hover:text-slate-500">hotels</span>
                    </div>
                </a>

                <!-- Card 4: Total Items -->
                <div class="group bg-white hidden p-5 rounded-xl border border-slate-200 shadow-sm hover:bg-slate-900 hover:border-slate-800 transition duration-300 flex flex-col justify-between h-36">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 group-hover:text-slate-400">Total Items</span>
                        <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-slate-700 group-hover:text-sky-400 transition duration-300">
                            <i class="fas fa-boxes text-sm"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black tracking-tight text-slate-900 group-hover:text-white transition duration-300">
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) AS total_items FROM items");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            echo $row['total_items'];
                            ?>
                        </span>
                        <span class="text-xs font-normal text-slate-400 group-hover:text-slate-500">items</span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Jobs Updates Log -->
            <div class="lg:col-span-5 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
                        <h5 class="text-sm font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <i class="far fa-bell text-sky-500"></i> Jobs Updates
                        </h5>
                        <a target="_blank" href="jobsheet-list.php" class="text-slate-400 hover:text-sky-600 transition">
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>

                    <div class="space-y-3 max-h-[260px] overflow-y-auto pr-1">
                        <?php while ($row = $jobsheetResult->fetch_assoc()):
                            $datetime1 = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
                            $datetimeString = $row['date'] . ' ' . $row['time'];
                            $datetime2 = DateTime::createFromFormat('d F Y h:i A', $datetimeString, new DateTimeZone('Asia/Kuala_Lumpur'));

                            $time = 'just now';
                            if ($datetime2) {
                                $interval = $datetime1->diff($datetime2);
                                if ($interval->d > 0) $time = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
                                else if ($interval->h > 0) $time = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
                                else if ($interval->i > 0) $time = $interval->i . ' min ago';
                            }

                            $task_type = strtolower($row['task_type']);
                            $is_install = ($task_type == "installation");

                            $icon_class = $is_install ? "fa-wrench text-emerald-500" : "fa-cog text-amber-500";
                        ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition duration-150">
                                <div class="flex items-center gap-3">
                                    <div class="w-2.5 h-2.5 rounded-full flex items-center justify-center">
                                        <i class="fas fa-circle text-[8px] <?= $is_install ? 'text-emerald-500' : 'text-amber-500' ?>"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 capitalize flex items-center gap-1.5">
                                            <i class="fas <?= $icon_class ?> text-xs"></i>
                                            <?= htmlspecialchars($task_type) ?> at <span class="font-bold text-slate-900"><?= htmlspecialchars($row["hotel_name"]) ?></span>
                                        </p>
                                        <span class="text-xs text-slate-400 font-medium"><?= $time ?></span>
                                    </div>
                                </div>
                                <a href="generatepdf.php?id=<?= $row['id'] ?>" class="text-slate-400 hover:text-sky-600 p-1 transition" target="_blank">
                                    <i class="fas fa-file-pdf text-sm"></i>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Section: Charts & Operations -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Line Chart container -->
            <div class="lg:col-span-8 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Analytics Overview</h5>
                <div class="w-full">
                    <canvas id="jobsheetChart" class="max-h-[320px]"></canvas>
                </div>
            </div>

            <!-- System Status Sidebar -->
            <div class="lg:col-span-4 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">System Status</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-xs font-semibold p-2.5 bg-emerald-50 text-emerald-800 rounded-lg border border-emerald-100">
                            <span>Database Core</span>
                            <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Online</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-semibold p-2.5 bg-slate-50 text-slate-700 rounded-lg border border-slate-200">
                            <span>Port Binding</span>
                            <span class="text-slate-500 font-mono">3307 TCP</span>
                        </div>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400 font-medium pt-4 border-t border-slate-100">
                    VIVTech Operation Suite • Active Session
                </div>
            </div>
        </div>

        <!-- Section: Data Table Area (Hidden with 'hidden' Tailwind class) -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hidden">
            <h4 class="text-md font-bold text-slate-800 mb-4 uppercase tracking-wider">Jobsheet Master List</h4>
            <div class="overflow-x-auto">
                <table id="jobsheetTable" class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-xs uppercase tracking-wider">
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">Date</th>
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">Time</th>
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">Hotel Name</th>
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">Task</th>
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">PIC</th>
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">Description</th>
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">Items Used</th>
                            <th class="p-3 border border-slate-800 text-center whitespace-nowrap overflow-hidden text-ellipsis">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php while ($row = $jobsheetResult1->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50 transition text-slate-700">
                                <td class="p-3 font-medium text-slate-900"><?= htmlspecialchars($row["date"]) ?></td>
                                <td class="p-3"><?= htmlspecialchars($row["time"]) ?></td>
                                <td class="p-3 font-semibold"><?= htmlspecialchars($row["hotel_name"]) ?></td>
                                <td class="p-3"><span class="px-2 py-0.5 text-xs font-bold rounded-full border bg-slate-100 text-slate-800 uppercase"><?= htmlspecialchars($row["task_type"]) ?></span></td>
                                <td class="p-3"><?= htmlspecialchars($row["person_name"]) ?></td>
                                <td class="p-3 truncate max-w-[150px]"><?= htmlspecialchars($row["description"]) ?></td>
                                <td class="p-3"><?= htmlspecialchars($row["items_used"]) ?></td>
                                <td class="p-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button class="view-details-btn p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition" data-id="<?= $row['id'] ?>"><i class="fas fa-eye text-xs"></i></button>
                                        <button class="edit-jobsheet-btn p-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded transition" data-id="<?= $row['id'] ?>"><i class="fas fa-edit text-xs"></i></button>
                                        <button class="delete-jobsheet-btn p-1.5 bg-red-600 hover:bg-red-700 text-white rounded transition" data-id="<?= $row['id'] ?>"><i class="fas fa-trash text-xs"></i></button>
                                        <a href="mailto:<?= $row['person_email'] ?>?subject=Support%20Job%20Update" class="p-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded transition"><i class="fas fa-envelope text-xs"></i></a>
                                        <a href="generatepdf.php?id=<?= $row['id'] ?>" class="p-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded transition" target="_blank"><i class="fas fa-file-pdf text-xs"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Scripts Logic -->
    <script>
        function closeModal() {
            document.getElementById('jobsheetDetailsModal').classList.add('hidden');
        }

        $(document).ready(function() {
            $('.view-details-btn').on('click', function() {
                const jobsheetId = $(this).data('id');
                $.ajax({
                    url: 'jobsheet/get-jobsheet-details.php',
                    type: 'POST',
                    data: {
                        id: jobsheetId
                    },
                    success: function(response) {
                        $('#jobsheetDetailsBody').html(response);
                        document.getElementById('jobsheetDetailsModal').classList.remove('hidden');
                    }
                });
            });

            $('.delete-jobsheet-btn').on('click', function() {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this jobsheet?')) {
                    $.ajax({
                        url: 'jobsheet/jobsheet-delete.php',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                location.reload();
                            } else {
                                alert('❌ Failed to delete jobsheet.');
                            }
                        }
                    });
                }
            });
        });

        // Chart.js Configuration
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
                            label: 'Jobsheets Filled',
                            data: values,
                            borderColor: '#0284c7',
                            backgroundColor: 'rgba(2, 132, 199, 0.05)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#0284c7'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                grid: {
                                    color: '#f1f5f9'
                                },
                                beginAtZero: true
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            });
    </script>
</body>

</html>