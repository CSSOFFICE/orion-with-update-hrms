@foreach($subtasks as $subtask)
<tr id="subtask_{{$subtask->sub_task_id }}" class="subtask-{{ $subtask->sub_task_id }}">
   <td class="tasks_col_project">
        <span class="x-strike-through">{{$subtask->task_title}}</span>
   </td>
    <td class="tasks_col_project">
        <span class="x-strike-through">{{ str_limit($subtask->subtask_description ?? '---', 18) }}</span>
    </td>

    <td class="tasks_col_milestone">
        <span class="x-strike-through">{{ str_limit($subtask->subtask_detail ?? '---', 12) }}</span>
    </td>

    <td class="tasks_col_project hidden">
        <span class="x-strike-through">{{ str_limit($subtask->unit ?? '---', 18) }}</span>
    </td>

    <td class="tasks_col_milestone">
        <span class="x-strike-through">{{ str_limit($subtask->unit_rate ?? '---', 12) }}</span>
    </td>
    <td class="milestones_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">

            <!---delete milestone with confirm checkbox-->
            <span id="subtask_form_{{ $subtask->sub_task_id }}">
                <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                    class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                    id="foobar" data-confirm-title="{{ cleanLang(__('lang.delete_subtask')) }}"
                    data-confirm-text="
                            <input type='checkbox' id='confirm_action_{{ $subtask->sub_task_id }}'
                                   class='filled-in chk-col-light-blue confirm_action_checkbox'
                                   data-field-id='delete_subtasks_{{ $subtask->sub_task_id }}'>
                            <label for='confirm_action_{{ $subtask->sub_task_id }}'>{{ cleanLang(__('lang.delete_all_subtasks')) }}</label>" data-ajax-type="DELETE" data-type="form"
                    data-form-id="subtask_form_{{ $subtask->sub_task_id }}"
                    data-url="{{ url('/') }}/subtasks/{{ $subtask->sub_task_id }}?sub_task_id={{ $subtask->sub_task_id }}">
                    <i class="sl-icon-trash"></i>
                </button>
                <input type="hidden" class="confirm_hidden_fields" name="delete_subtasks"
                    id="delete_subtasks_{{ $subtask->sub_task_id }}">
            </span>
            <!---/#delete milestone with confirm checkbox-->
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/subtasks/'.$subtask->sub_task_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_subtasks')) }}"
                data-action-url="{{ urlResource('/subtasks/'.$subtask->sub_task_id.'?ref=list') }}"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="subtasks-td-container">
                <i class="sl-icon-note"></i>
            </button>


        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
