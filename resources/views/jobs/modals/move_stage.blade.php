<div id="moveStageModal" class="pl-modal-overlay">
    <div class="pl-modal-panel pl-modal-panel-md">
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Workflow Transition</span>
                <h3 id="moveStageTitle" class="pl-modal-title">Move Stage</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="moveStageModal" aria-label="Close">&times;</button>
        </div>

        <form id="moveStageForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="hidden" id="move_job_id" name="job_id">
            <input type="hidden" id="move_target_status" name="status">

            <div class="pl-modal-body bg-slate-50">
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-800 mb-4 flex items-start gap-2">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                    <span>Moving to the next stage resets the 3-day SLA timer. You can also reassign or forward this job to another department simultaneously.</span>
                </div>

                <div class="form-group-custom">
                    <label for="move_to_department_id">Assign to Department <span class="text-xs text-slate-400 font-normal">(Optional)</span></label>
                    <select name="to_department_id" id="move_to_department_id" class="form-control-custom">
                        <option value="" selected>— Keep in Current Department —</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Leave empty if the job stays in the current department.</p>
                </div>

                <div class="form-group-custom">
                    <label for="move_note">Note / Progress Report <span class="text-red-500">*</span></label>
                    <textarea name="note" id="move_note" rows="3" class="form-control-custom"
                        required placeholder="Describe work done or instructions for the next department..."></textarea>
                </div>

                <div class="form-group-custom mb-0">
                    <label for="move_attachments">Evidence / Attachment <span class="text-red-500">*</span></label>
                    <input type="file" name="attachments[]" id="move_attachments" class="form-control-custom" multiple required>
                </div>
            </div>

            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="moveStageModal">
                    Cancel
                </button>
                <button type="submit" class="pl-btn pl-btn-primary">
                    <i class="fa-solid fa-arrow-right mr-2"></i> Confirm Move
                </button>
            </div>
        </form>
    </div>
</div>