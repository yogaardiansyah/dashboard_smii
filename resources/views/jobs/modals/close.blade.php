<div id="closeJobModal" class="pl-modal-overlay">
    <div class="pl-modal-panel pl-modal-panel-md">
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Job Archive</span>
                <h3 class="pl-modal-title">Confirm Close Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="closeJobModal" aria-label="Close">&times;</button>
        </div>

        <form id="closeJobForm">
            @csrf
            <input type="hidden" id="close_job_id" name="job_id">

            <div class="pl-modal-body bg-slate-50">
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-sm flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5"></i>
                    <div>
                        <p class="font-semibold mb-1">Archive Job Ticket</p>
                        <p class="text-xs text-amber-800 leading-relaxed">
                            Are you sure you want to close this job? This will archive the job ticket and notify all involved team members. No further stage moves or notes can be submitted.
                        </p>
                    </div>
                </div>
            </div>

            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="closeJobModal">
                    Cancel
                </button>
                <button type="submit" class="pl-btn pl-btn-primary">
                    <i class="fa-solid fa-box-archive mr-2"></i> Yes, Close Job
                </button>
            </div>
        </form>
    </div>
</div>