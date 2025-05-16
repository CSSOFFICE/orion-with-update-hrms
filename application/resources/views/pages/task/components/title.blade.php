<div class="card-title m-b-0" id="{{ runtimePermissions('task-edit-title', $task->permission_edit_task) }}">
    {{ $task->task_title }}
</div>
<!--buttons: edit-->
@if($task->permission_edit_task)
<div id="card-title-edit" class="card-title-edit hidden">
    <input type="text" class="form-control form-control-sm card-title-input" id="task_title" name="task_title">
    <!--button: subit & cancel-->
    <div id="card-title-submit" class="p-t-10 text-right">
        <button type="button" class="btn waves-effect waves-light btn-xs btn-default"
            id="card-title-button-cancel">{{ cleanLang(__('lang.cancel')) }}</button>
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
            data-url="{{ urlResource('/tasks/'.$task->task_id.'/update-title') }}" data-progress-bar='hidden' data-type="form"
            data-form-id="card-title-edit" data-ajax-type="post" id="card-title-button-save">{{ cleanLang(__('lang.save')) }}</button>
    </div>
</div>
@endif
<div class="m-b-15"><small><strong>Milestone: </strong></small><small id="card-task-milestone-title">{{get_mile($task->task_cat_id)}}</small></div>
