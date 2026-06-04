<?php
include 'db.php';

// 1. Tarik senarai hotel untuk drop-down filter (Kekalkan)
$hotelsQuery = $conn->query("SELECT * FROM hotel ORDER BY name ASC");

// 2. Ambil data filter dari URL params (Kekalkan)
$filterHotel = isset($_GET['hotel_id']) ? $_GET['hotel_id'] : '';
$filterTask  = isset($_GET['task_type']) ? $_GET['task_type'] : '';
$filterDate  = isset($_GET['date']) ? $_GET['date'] : '';

// 3. 🌟 QUERY BARU: Buang JOIN hotel_person, ambil terus pic_name dari jobsheet
$sql = "SELECT j.*, h.name AS hotel_name 
        FROM jobsheet j
        JOIN hotel h ON j.hotel_id = h.id
        WHERE 1=1";

if ($filterHotel != '') {
    $sql .= " AND j.hotel_id = " . intval($filterHotel);
}
if ($filterTask != '') {
    $sql .= " AND j.task_type = '" . $conn->real_escape_string($filterTask) . "'";
}
if ($filterDate != '') {
    $sql .= " AND j.date = '" . $conn->real_escape_string($filterDate) . "'";
}

// Susun ikut tarikh dan masa terbaru
$sql .= " ORDER BY j.date DESC, j.time DESC";
$jobsheetResult = $conn->query($sql);

$jobsheetsArray = [];

// 4. Sedut data masuk ke dalam array untuk diagihkan ke Timeline & List bawah
if ($jobsheetResult && $jobsheetResult->num_rows > 0) {
    while ($row = $jobsheetResult->fetch_assoc()) {
        $jobsheetsArray[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support - Jobsheet Master & History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-slate-100 text-slate-800 font-sans antialiased overflow-x-hidden">

    <?php include 'navbar.php'; ?>

    <div id="detailsModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-2xl w-full overflow-hidden transform transition-all">
            <div class="bg-slate-950 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <h5 class="text-md font-bold text-white uppercase tracking-wider">
                    <i class="fas fa-info-circle text-sky-500 mr-2"></i>Job Logistics
                </h5>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto" id="modalBody"></div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-4 rounded-xl border border-slate-200 shadow-sm gap-4">
            <h1 class="text-lg font-black uppercase tracking-wider text-slate-700 flex items-center gap-2">
                <i class="fas fa-stream text-sky-500"></i> Operation Logs & Chronology
            </h1>
            <a href="index.php" class="bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus mr-1"></i> Form
            </a>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Filter Hotel</label>
                    <select name="hotel_id" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none">
                        <option value="">All Hotels</option>
                        <?php while ($h = $hotelsQuery->fetch_assoc()): ?>
                            <option value="<?= $h['id'] ?>" <?= $filterHotel == $h['id'] ? 'selected' : '' ?>><?= htmlspecialchars($h['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Task Type</label>
                    <select name="task_type" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none">
                        <option value="">All Tasks</option>
                        <option value="troubleshoot" <?= $filterTask == 'troubleshoot' ? 'selected' : '' ?>>Troubleshoot</option>
                        <option value="installation" <?= $filterTask == 'installation' ? 'selected' : '' ?>>Installation</option>
                        <option value="dismantle" <?= $filterTask == 'dismantle' ? 'selected' : '' ?>>Dismantle</option>
                        <option value="maintanance" <?= $filterTask == 'maintanance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Specific Date</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($filterDate) ?>" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-lg transition text-center">
                        <i class="fas fa-filter mr-1"></i> Apply
                    </button>
                    <?php if ($filterHotel || $filterTask || $filterDate): ?>
                        <a href="jobsheet-list.php" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs uppercase tracking-wider py-2.5 px-3 rounded-lg border border-red-200 transition text-center" title="Clear Filters">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-1">
                <div class="bg-slate-900 text-white p-4 rounded-xl border border-slate-800 shadow-md space-y-4">
                    <div class="border-b border-slate-800 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-history text-sky-400"></i> Operational Chronology
                            </h3>
                            <p class="text-[10px] text-slate-500 mt-1">
                                <?= ($filterHotel != '') ? 'Showing technical troubleshooting timeline for the selected hotel.' : 'Hotel-specific technical timeline.' ?>
                            </p>
                        </div>

                        <?php if ($filterHotel != ''): ?>
                            <div>
                                <a href="generate-chronology-pdf.php?hotel_id=<?= $filterHotel ?>" target="_blank" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] uppercase tracking-wider px-3 py-1.5 rounded-lg transition shadow-sm">
                                    <i class="fas fa-file-pdf"></i> Export Chronology
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($filterHotel != ''): ?>

                        <div class="relative border-l-2 border-slate-800 ml-2.5 pl-4 space-y-5">
                            <?php if (!empty($jobsheetsArray)): ?>
                                <?php foreach ($jobsheetsArray as $job):
                                    $task_name = isset($job['task']) ? $job['task'] : ($job['task_type'] ?? 'task');
                                    $is_trouble = (strtolower($task_name) == 'troubleshoot');
                                ?>
                                    <div class="relative text-xs">
                                        <div class="absolute -left-[23px] top-1 w-2.5 h-2.5 rounded-full border-2 border-slate-900 <?= $is_trouble ? 'bg-amber-400 ring-4 ring-amber-500/10' : 'bg-sky-400 ring-4 ring-sky-500/10' ?>"></div>

                                        <div class="space-y-1">
                                            <span class="text-[10px] font-mono font-bold text-slate-400"><?= htmlspecialchars($job['date']) ?> • <?= htmlspecialchars($job['time']) ?></span>
                                            <h4 class="font-bold text-slate-200 tracking-tight"><?= htmlspecialchars($job['hotel_name']) ?></h4>
                                            <p class="text-slate-400 text-[11px] leading-relaxed capitalize">
                                                <span class="<?= $is_trouble ? 'text-amber-400' : 'text-emerald-400' ?> font-semibold"><?= htmlspecialchars($task_name) ?></span>
                                                - <?= htmlspecialchars(!empty($job['complaint']) ? $job['complaint'] : 'Task closed with zero issues.') ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-xs text-slate-500 italic py-2">No maintenance records found for this hotel.</p>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>
                        <div class="text-center py-6 px-2 space-y-2">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center mx-auto text-slate-500">
                                <i class="fas fa-hotel text-sm"></i>
                            </div>
                            <p class="text-xs text-slate-400 font-medium leading-relaxed">
                                Please select a specific hotel from the filter options above to view its technical troubleshooting timeline chronology.
                            </p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex justify-between items-center">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-500">Filtered Results (<?= count($jobsheetsArray) ?> Found)</h3>
                </div>

                <div class="space-y-3">
                    <?php if (!empty($jobsheetsArray)): ?>
                        <?php foreach ($jobsheetsArray as $row):
                            $task_name = isset($row['task']) ? $row['task'] : ($row['task_type'] ?? 'task');
                            $is_trouble = (strtolower($task_name) == 'troubleshoot');
                        ?>
                            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between gap-4 items-start sm:items-center hover:border-slate-300 transition">
                                <div class="space-y-1.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded-md border <?= $is_trouble ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' ?>">
                                            <?= htmlspecialchars($task_name) ?>
                                        </span>
                                        <h2 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($row['hotel_name']) ?></h2>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium">
                                        <i class="far fa-calendar-alt mr-1"></i> <?= htmlspecialchars($row['date']) ?>
                                        <span class="mx-1.5 text-slate-300">•</span>
                                        <i class="far fa-clock mr-1"></i> <?= htmlspecialchars($row['time']) ?>
                                        <span class="mx-1.5 text-slate-300">•</span>
                                        <i class="far fa-user mr-1"></i> PIC: <?= htmlspecialchars($row['person_name'] ?? 'N/A') ?>
                                    </p>
                                    <?php if (!empty($row['complaint'])): ?>
                                        <p class="text-xs text-slate-600 line-clamp-1 italic bg-slate-50 p-2 rounded border border-slate-100 font-mono">
                                            <?= htmlspecialchars($row['complaint']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center gap-1.5 w-full sm:w-auto justify-end border-t sm:border-0 pt-2 sm:pt-0">
                                    <button class="view-btn p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition" data-id="<?= $row['id'] ?>" title="Quick View">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <a href="generatepdf.php?id=<?= $row['id'] ?>" target="_blank" class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg transition" title="Print PDF">
                                        <i class="fas fa-file-pdf text-xs"></i>
                                    </a>

                                    <button class="delete-jobsheet-btn p-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg transition" data-id="<?= $row['id'] ?>" title="Delete Jobsheet">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-white p-12 rounded-xl border border-slate-200 text-center text-slate-400 font-medium text-sm">
                            <i class="fas fa-inbox text-3xl mb-3 text-slate-300 block"></i> No records match your criteria.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        $(document).ready(function() {
            // 1. Skrip asal untuk View Details (Kekalkan)
            $('.view-btn').on('click', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'jobsheet/get-jobsheet-details.php',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('#modalBody').html(response);
                        document.getElementById('detailsModal').classList.remove('hidden');
                    }
                });
            });

            // 2. SKRIP BARU: Pengendali Butang Delete Jobsheet
            $('.delete-jobsheet-btn').on('click', function() {
                const id = $(this).data('id');

                // Letak confirmation prompt supaya tak tersilap tekan
                if (confirm('Are you sure you want to delete this jobsheet? This action cannot be undone.')) {
                    $.ajax({
                        url: 'jobsheet/jobsheet-delete.php',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            // Semak jika backend pulangkan text 'success'
                            if (response.trim() === 'success') {
                                alert('✅ Jobsheet deleted successfully.');
                                location.reload(); // Refresh page untuk update senarai terkini
                            } else {
                                alert('❌ Failed to delete jobsheet. System error.');
                                console.log('Response:', response);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('❌ Connection failed to process delete request.');
                            console.log('Error:', error);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>