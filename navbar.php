<?php
    // Dapatkan nama fail semasa (contoh: index.php, dashboard.php)
    $current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="bg-slate-900 border-b border-slate-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <div class="flex items-center">
                <a class="text-white font-bold text-lg tracking-wider uppercase flex items-center gap-2" href="index.php">
                    <span class="text-sky-500"><i class="fas fa-tools"></i></span> VIVTech Support
                </a>
            </div>

            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    
                    <a href="index.php" class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 <?= ($current_page == 'index.php' || $current_page == '') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-edit mr-1 text-xs"></i> Form
                    </a>
                    
                    <a href="dashboard.php" class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 <?= ($current_page == 'dashboard.php') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-chart-pie mr-1 text-xs"></i> Dashboard
                    </a>

                    <a href="jobsheet-list.php" class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 <?= ($current_page == 'jobsheet-list.php') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-stream mr-1 text-xs"></i> Jobsheet
                    </a>
                    
                    <a href="hotel.php" class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 <?= ($current_page == 'hotel.php') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-hotel mr-1 text-xs"></i> Hotels
                    </a>

                    <!-- <a href="item-catalog.php" class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 <?= ($current_page == 'item-catalog.php') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-boxes mr-1 text-xs"></i> Item
                    </a> -->

                </div>
            </div>

            <div class="-mr-2 flex md:hidden">
                <button type="button" onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none">
                    <i class="fas fa-bars text-xl" id="menu-icon"></i>
                </button>
            </div>
            
        </div>
    </div>

    <div class="hidden md:hidden bg-slate-900 border-t border-slate-800 px-2 pt-2 pb-3 space-y-1" id="mobile-menu">
        <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'index.php' || $current_page == '') ? 'bg-sky-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-edit mr-2 text-sm"></i> Form
        </a>
        <a href="dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'dashboard.php') ? 'bg-sky-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-chart-pie mr-2 text-sm"></i> Dashboard
        </a>
        <a href="jobsheet-list.php" class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'jobsheet-list.php') ? 'bg-sky-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-stream mr-2 text-sm"></i> Jobsheet
        </a>
        <a href="hotel.php" class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'hotel.php') ? 'bg-sky-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-hotel mr-2 text-sm"></i> Hotels
        </a>
        <!-- <a href="item-catalog.php" class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'item-catalog.php') ? 'bg-sky-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
            <i class="fas fa-boxes mr-2 text-sm"></i> Item
        </a> -->
    </div>
</nav>

<script>
    // Logik ringkas untuk buka/tutup menu bila buka pakai phone
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('menu-icon');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times'); // Tukar ikon jadi 'X' bila menu buka
        } else {
            menu.classList.add('hidden');
            icon.classList.remove('fa-times');
            icon.xlastList.add('fa-bars');
        }
    }
</script>