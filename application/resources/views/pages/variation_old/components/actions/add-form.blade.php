<div class="form-group row">
    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Category</label>
    <div class="col-sm-12 col-lg-9">
        <select class="form-control form-control-sm" required id="project_cat" name="project_cat">
            <option value="">Please Select</option>
            <option value="{{ $project->project_cat ?? 'Project' }}">Project</option>
            <option value="{{ $project->project_cat ?? 'Maintenance' }}">Maintenance</option>
            <option value="{{ $project->project_cat ?? 'Manpower supply' }}">Manpower supply</option>
            <option value="{{ $project->project_cat ?? 'Spare parts, Equipment' }}">Spare parts, Equipment
            </option>
            <option value="{{ $project->project_cat ?? 'LSS (Life Safety System)' }}">LSS (Life Safety System)
            </option>
            <option value="{{ $project->project_cat ?? 'TGCM' }}">TGCM</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label " >{{ cleanLang(__('lang.project_title')) }}*</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" required class="form-control form-control-sm" required id="project_title" name="project_title"
            placeholder="" value="{{ $project->project_title ?? '' }}">
    </div>
</div>
<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.start_date')) }}*</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" required class="form-control form-control-sm pickadate" name="project_date_start"
            autocomplete="off" value="{{ runtimeDatepickerDate($project->project_date_start ?? '') }}">
        <input class="mysql-date" type="hidden" name="project_date_start" id="project_date_start"
            value="{{ $project->project_date_start ?? '' }}">
    </div>
</div>
<input type="hidden" name="project_clientid" value="{{ $estimate->bill_clientid }}">
<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.deadline')) }}</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" class="form-control form-control-sm pickadate" name="project_date_due" autocomplete="off"
            value="{{ runtimeDatepickerDate($project->project_date_due ?? '') }}">
        <input class="mysql-date" type="hidden" name="project_date_due" id="project_date_due"
            value="{{ $project->project_date_due ?? '' }}">
    </div>
</div>
