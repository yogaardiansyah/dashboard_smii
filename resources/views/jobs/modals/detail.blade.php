<div id="jobDetailModal" class="pl-modal-overlay">
    <div class="pl-modal-panel pl-modal-panel-lg" style="max-width: 960px; max-height: 88vh;">
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Job Audit & Timeline</span>
                <h3 class="pl-modal-title">Job Timeline & Full Details</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="jobDetailModal" aria-label="Close">&times;</button>
        </div>

        <div id="jobDetailContent" class="pl-modal-body bg-slate-50 p-4" style="max-height: calc(88vh - 130px); overflow-y: auto;">
            <div class="flex flex-col items-center justify-center p-12 text-slate-500">
                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-3"></div>
                <span class="text-sm font-medium">Loading details & timeline...</span>
            </div>
        </div>

        <div class="pl-modal-footer">
            <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="jobDetailModal">
                Close
            </button>
        </div>
    </div>
</div>
