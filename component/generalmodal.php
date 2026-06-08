<div id="generalModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-200">
    <div class="bg-white rounded-xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-200" id="modalBox">
        
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Notification</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-200/60 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="px-5 py-6">
            <p class="text-sm text-slate-600 leading-relaxed" id="modalMessage">Message goes here...</p>
        </div>
        
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                Okay
            </button>
        </div>
    </div>
</div>