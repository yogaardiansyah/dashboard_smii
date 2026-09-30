<div id="createJobModal" class="pl-modal-overlay">
    <div class="pl-modal-panel pl-modal-panel-lg">
        <div class="pl-modal-header">
            <div>
                <span class="pl-hero-kicker" style="margin-bottom: 2px; color: rgba(255,255,255,0.7); font-size: 11px;">Job Workflow</span>
                <h3 class="pl-modal-title">Create New Job</h3>
            </div>
            <button type="button" class="pl-modal-close" data-close-modal="createJobModal" aria-label="Close">&times;</button>
        </div>

        <form id="createJobForm" enctype="multipart/form-data">
            @csrf
            <div class="pl-modal-body bg-slate-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group-custom">
                        <label for="create_area_id">Area / Location <span class="text-red-500">*</span></label>
                        <select name="area_id" id="create_area_id" class="form-control-custom" required>
                            <option value="" disabled selected>-- Select Operational Area --</option>
                            @foreach($areas as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label for="create_to_department_id">Initial Assigned Department <span class="text-red-500">*</span></label>
                        <select name="to_department_id" id="create_to_department_id" class="form-control-custom" required>
                            <option value="" disabled selected>-- Select Initial Dept --</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group-custom">
                        <label for="create_start_date">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="create_start_date" class="form-control-custom"
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group-custom">
                        <label for="create_deadline">Deadline <span class="text-red-500">*</span></label>
                        <input type="date" name="deadline" id="create_deadline" class="form-control-custom" required>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="create_list_job">Job Description <span class="text-red-500">*</span></label>
                    <textarea name="list_job" id="create_list_job" rows="3" class="form-control-custom"
                        placeholder="Detail the work or instructions needed for this job..." required></textarea>
                </div>

                <div class="form-group-custom mb-0">
                    <label for="create_attachments">Attachments <span class="text-xs text-slate-400 font-normal">(Optional, max 3 files - jpg, png, pdf, doc)</span></label>
                    <input type="file" name="attachments[]" id="create_attachments" class="form-control-custom" multiple>
                </div>
            </div>

            <div class="pl-modal-footer">
                <button type="button" class="pl-btn pl-btn-neutral" data-close-modal="createJobModal">
                    Cancel
                </button>
                <button type="submit" class="pl-btn pl-btn-primary">
                    <i class="fa-solid fa-plus mr-2"></i> Create Job
                </button>
            </div>
        </form>
    </div>
</div>