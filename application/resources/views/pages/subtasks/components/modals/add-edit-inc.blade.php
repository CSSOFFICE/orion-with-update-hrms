<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.tasks')) }}</label>
    <div class="col-12">

        @if (isset($page['section']) && $page['section'] == 'edit')
            <select type="text" class="form-control  form-control-sm" autocomplete="off" id="tasks" name="tasks"
                value="{{ $subtask->task_title ?? '' }}">
                <option value="{{ $subtask->unit_rate ?? '' }}">Select Task</option>
                @foreach ($task as $key => $value)
                    <option value="{{ $value->task_id }}"
                        {{ $value->task_id == $subtask->subtask_taskid ? 'selected' : '' }}>{{ $value->task_title }}
                    </option>
                @endforeach
            </select>
        @else
            <select type="text" class="form-control  form-control-sm" autocomplete="off" id="tasks"
                name="tasks" value="{{ $subtask->task_title ?? '' }}">
                <option value="{{ $subtask->unit_rate ?? '' }}">Select Task</option>
                @foreach ($task as $key => $value)
                    <option value="{{ $value->task_id }}">{{ $value->task_title }}</option>
                @endforeach
            </select>

        @endif
    </div>
    <label
        class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.task_description')) }}</label>
    <div class="col-12">
        <textarea class="form-control  form-control-sm" autocomplete="off" id="subtask_description" name="subtask_description">{{ $subtask->subtask_description ?? '' }} </textarea>
        <input type="hidden" name="subtask_projectid" value="{{ request('project_id') }}">
    </div>
    <label
        class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.task_detail')) }}</label>
    <div class="col-12">
        <textarea class="form-control  form-control-sm" autocomplete="off" id="subtask_detail" name="subtask_detail">{{ $subtask->subtask_detail ?? '' }} </textarea>

    </div>
    <label class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.unit_rate')) }}</label>
    <div class="col-12">
        <input type="text" class="form-control  form-control-sm" autocomplete="off" id="unit_rate" name="unit_rate"
            value="{{ $subtask->unit_rate ?? '' }}">

    </div>
</div>
