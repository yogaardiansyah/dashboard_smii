<div id="jobDetailModal"
    class="pl-modal-overlay hidden" style="z-index: 1050;">
    <div class="pl-modal-panel pl-modal-panel-xl mx-auto my-auto h-[85vh] max-h-[85vh]">

        <!-- Header Modal (Manage Areas Theme) -->
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Enterprise Audit</span>
                <h3 class="pl-modal-title">Rincian Lengkap & Timeline Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="jobDetailModal" onclick="closeModal('jobDetailModal')" aria-label="Close">&times;</button>
        </div>

        <!-- Body Content -->
        <div id="jobDetailContent" class="flex-1 overflow-y-auto p-0 bg-slate-50/50 dark:bg-slate-950/40 custom-scrollbar text-slate-800 dark:text-slate-200">
            <div class="flex justify-center items-center h-64">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin kanban-modal-icon"></div>
                    <span class="text-slate-500 dark:text-slate-400 font-semibold text-xs">Memuat data rincian pekerjaan...</span>
                </div>
            </div>
        </div>
    </div>
</div>
