<div id="completeJobModal" class="pl-modal-overlay">
    <div class="pl-modal-panel pl-modal-panel-md">
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Job Completion</span>
                <h3 class="pl-modal-title">Complete Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="completeJobModal" aria-label="Close">&times;</button>
        </div>

        <form id="completeJobForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="hidden" id="complete_job_id" name="job_id">

            <div class="pl-modal-body bg-slate-50">
                <div class="form-group-custom">
                    <label for="complete_note">Completion Notes / Summary <span class="text-red-500">*</span></label>
                    <textarea name="note" id="complete_note" rows="3" class="form-control-custom"
                        required placeholder="Detail the work done, findings, or final resolution..."></textarea>
                </div>

                <div class="form-group-custom mb-0">
                    <label for="complete_attachments">Final Evidence / Photos <span class="text-xs text-slate-400 font-normal">(Optional, max 3 files)</span></label>
                    <input type="file" name="attachments[]" id="complete_attachments" class="form-control-custom" multiple>
                </div>
            </div>

            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="completeJobModal">
                    Cancel
                </button>
                <button type="submit" class="pl-btn" style="background: #16a34a; color: #ffffff;">
                    <i class="fa-solid fa-check mr-2"></i> Mark as Completed
                </button>
            </div>
        </form>
    </div>
</div>
