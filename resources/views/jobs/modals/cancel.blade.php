<div id="cancelJobModal" class="pl-modal-overlay">
    <div class="pl-modal-panel pl-modal-panel-md">
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Job Cancellation</span>
                <h3 class="pl-modal-title">Cancel Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="cancelJobModal" aria-label="Close">&times;</button>
        </div>

        <form id="cancelJobForm">
            @csrf
            @method('PATCH')
            <input type="hidden" id="cancel_job_id" name="job_id">

            <div class="pl-modal-body bg-slate-50">
                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800 mb-4 flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation text-red-600 mt-0.5"></i>
                    <span>Are you sure you want to cancel this job? This will terminate the workflow and notify the assigned department. Only the requester can cancel.</span>
                </div>

                <div class="form-group-custom mb-0">
                    <label for="cancel_reason">Reason for Cancellation <span class="text-red-500">*</span></label>
                    <textarea name="reason" id="cancel_reason" rows="3" class="form-control-custom"
                        required placeholder="Explain why this job is being cancelled..."></textarea>
                </div>
            </div>

            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="cancelJobModal">
                    Keep Job
                </button>
                <button type="submit" class="pl-btn" style="background: #dc2626; color: #ffffff;">
                    <i class="fa-solid fa-ban mr-2"></i> Yes, Cancel It
                </button>
            </div>
        </form>
    </div>
</div>