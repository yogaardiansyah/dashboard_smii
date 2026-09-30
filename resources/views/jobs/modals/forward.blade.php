<div id="forwardJobModal" class="pl-modal-overlay">
    <div class="pl-modal-panel pl-modal-panel-md">
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Workflow Routing</span>
                <h3 class="pl-modal-title">Forward Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="forwardJobModal" aria-label="Close">&times;</button>
        </div>

        <form id="forwardJobForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="forward_job_id" name="job_id">

            <div class="pl-modal-body bg-slate-50">
                <div class="form-group-custom">
                    <label for="forward_to_department_id">Forward to Department <span class="text-red-500">*</span></label>
                    <select name="to_department_id" id="forward_to_department_id" class="form-control-custom" required>
                        <option value="" disabled selected>-- Select Destination Department --</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-custom">
                    <label for="forward_note">Routing Notes <span class="text-red-500">*</span></label>
                    <textarea name="note" id="forward_note" rows="3" class="form-control-custom"
                        required placeholder="Detail reason for forwarding and any specific instructions..."></textarea>
                </div>

                <div class="form-group-custom mb-0">
                    <label for="forward_attachments">Attachments <span class="text-xs text-slate-400 font-normal">(Optional, max 3 files)</span></label>
                    <input type="file" name="attachments[]" id="forward_attachments" class="form-control-custom" multiple>
                </div>
            </div>

            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="forwardJobModal">
                    Cancel
                </button>
                <button type="submit" class="pl-btn" style="background: #d97706; color: #ffffff;">
                    <i class="fa-solid fa-share mr-2"></i> Forward Job
                </button>
            </div>
        </form>
    </div>
</div>