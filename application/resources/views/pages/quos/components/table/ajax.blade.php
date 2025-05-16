@foreach ($tasks as $task)

<!--each row-->
<tr id="task_{{ $task->task_id }}" class="task-{{ $task->task_status }}">
    <td class="tasks_col_title td-edge">
        <!--for polling timers-->
        <input type="hidden" name="tasks[{{ $task->task_id }}]" value="{{ $task->assigned_to_me }}">
        <!--checkbox-->
        <span
            class="task_border td-edge-border {{ runtimeTaskStatusColors($task->task_status, 'background') }}"></span>
        @if (config('visibility.tasks_checkbox'))
        <span class="list-checkboxes m-l-0">
            <input type="checkbox" id="toggle_task_status_{{ $task->task_id }}" name="toggle_task_status"
                class="toggle_task_status filled-in chk-col-light-blue js-ajax-ux-request-default"
                data-url="{{ urlResource('/tasks/' . $task->task_id . '/toggle-status') }}"
                data-ajax-type="post" data-type="form" data-form-id="task_{{ $task->task_id }}"
                data-notifications="disabled" data-container="task_{{ $task->task_id }}"
                data-progress-bar="hidden" {{ runtimePrechecked($task->task_status) }}>
            <label for="toggle_task_status_{{ $task->task_id }}"><a class=""
                    href="{{ url('/estimates/' . $task->view_id) }}"
                    data-loading-target="main-top-nav-bar"><span class="x-strike-through"
                        id="table_task_title_{{ $task->task_id }}">
                        {{ str_limit($task->quo_number ?? '---', 40) }}</span></a>
            </label>
        </span>
        @endif
        @if (config('visibility.tasks_nocheckbox'))
        <a class="show-modal-button reset-card-modal-form js-ajax-ux-request p-l-5" href="javascript:void(0)"
            data-toggle="modal" data-target="#cardModal"
            data-url="{{ urlResource('/tasks/' . $task->task_id) }}"
            data-loading-target="main-top-nav-bar"><span class="x-strike-through"
                id="table_task_title_{{ $task->task_id }}">{{ str_limit($task->task_title ?? '---', 45) }}</span>12</a>
        @endif
    </td>
    <td class="tasks_col_created">{{ $task->task_date_start }}</td>
    <td class="tasks_col_deadline">
        @php
        $user = \App\Models\User::find($task->task_creatorid);
        echo $user->first_name ?? '';
        @endphp
    </td>
    <td class="tasks_col_deadline">{{ runtimeDate($task->task_date_due) }}</td>

    @if (config('visibility.tasks_col_mytime'))
    <td class="tasks_col_my_time">
        @if ($task->assigned_to_me)
        <span class="x-timer-time timers {{ runtimeTimerRunningStatus($task->timer_current_status) }}"
            id="task_timer_table_{{ $task->task_id }}">{!! clean(runtimeSecondsHumanReadable($task->my_time, false)) !!}</span>
        @if ($task->task_status != 'completed')
        <!--start a timer-->
        <span
            class="x-timer-button js-timer-button js-ajax-request timer-start-button hidden {{ runtimeTimerVisibility($task->timer_current_status, 'stopped') }}"
            id="timer_button_start_table_{{ $task->task_id }}" data-task-id="{{ $task->task_id }}"
            data-location="table"
            data-url="{{ url('/') }}/tasks/timer/{{ $task->task_id }}/start?source=list"
            data-form-id="tasks-list-table" data-type="form" data-progress-bar='hidden'
            data-ajax-type="POST">
            <span><i class="mdi mdi-play-circle"></i></span>
        </span>
        <!--stop a timer-->
        <span
            class="x-timer-button js-timer-button js-ajax-request timer-stop-button hidden {{ runtimeTimerVisibility($task->timer_current_status, 'running') }}"
            id="timer_button_stop_table_{{ $task->task_id }}" data-task-id="{{ $task->task_id }}"
            data-location="table"
            data-url="{{ url('/') }}/tasks/timer/{{ $task->task_id }}/stop?source=list"
            data-form-id="tasks-list-table" data-type="form" data-progress-bar='hidden'
            data-ajax-type="POST">
            <span><i class="mdi mdi-stop-circle"></i></span>
        </span>
        <!--timer updating-->
        <input type="hidden" name="timers[{{ $task->task_id }}]" value="">
        @endif
        @else
        <span>{{ $task->AMO ?? '' }}</span>
        @endif
    </td>
    @endif

    @if (config('visibility.tasks_col_tags'))
    <td class="tasks_col_tags">
        <!--tag-->
        @if (count($task->tags) > 0)
        @foreach ($task->tags->take(2) as $tag)
        <span class="label label-outline-default">{{ str_limit($tag->tag_title, 15) }}</span>
        @endforeach
        @else
        <span>---</span>
        @endif
        <!--/#tag-->

        <!--more tags (greater than tags->take(x) number above -->
        @if (count($task->tags) > 1)
        @php $tags = $task->tags; @endphp
        @include('misc.more-tags')
        @endif
        <!--more tags-->
    </td>
    @endif
    @if ($task->task_status == 'accepted')
    <td class="tasks_col_status">
        <span
            class="label {{ runtimeEstimateStatusColors($task->task_status, 'label') }}">{{ 'Approval' }}</span>
    </td>
    @else
    <td class="tasks_col_status">
        <span
            class="label {{ runtimeEstimateStatusColors($task->task_status, 'label') }}">{{ $task->task_status == 'Approval_from_Management' ? 'Wating For Customer Approval' : runtimeLang($task->task_status) }}</span>
    </td>
    @endif
    <td class="tasks_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--[delete]-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/estimates/{{ $task->view_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif

            <!--view-->
            {{-- <button type="button" title="{{ cleanLang(__('lang.view')) }}"
            class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm show-modal-button reset-card-modal-form js-ajax-ux-request"
            data-toggle="modal" data-target="#cardModal" data-url="{{ urlResource('/tasks/'.$task->task_id) }}"
            data-loading-target="main-top-nav-bar">
            <i class="ti-new-window"></i>
            </button> --}}

            <a href="{{ url('/estimates/' . $task->view_id) }}" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        {{-- this is estimate code --}}
        <span class="list-table-action dropdown  font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" title="{{ cleanLang(__('lang.more')) }}"
                title="{{ cleanLang(__('lang.more')) }}"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">

                <!--actions button - email client -->
                <a class="dropdown-item confirm-action-info d-none" href="javascript:void(0)"
                    data-confirm-title="{{ cleanLang(__('lang.email_to_client')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ url('/estimates') }}/{{ $task->bill_estimateid }}/resend?ref=list">
                    {{ cleanLang(__('lang.email_to_client')) }}</a>
                <!--actions button - change category-->
                <a class="dropdown-item actions-modal-button  js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="{{ cleanLang(__('lang.change_status')) }}"
                    data-url="{{ urlResource('/estimates/' . $task->task_id . '/change-status') }}"
                    data-action-url="{{ urlResource('/estimates/' . $task->task_id . '/change-status') }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    {{ cleanLang(__('lang.change_status')) }}</a>
                <!--actions button - change category-->
                <a class="dropdown-item actions-modal-button d-none js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title=" {{ cleanLang(__('lang.change_status')) }}"
                    data-url="{{ url('/estimates/change-category') }}"
                    data-action-url="{{ urlResource('/estimates/change-category?id=' . $task->task_id) }}"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    {{ cleanLang(__('lang.change_status')) }}</a>
                <a class="dropdown-item confirm-action-info hidden" href="javascript:void(0)"
                    data-confirm-title="{{ cleanLang(__('lang.email_to_client')) }}"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ url('/estimates') }}/{{ $task->bill_estimateid }}/convert-to-invoice">
                    {{ cleanLang(__('lang.convert_to_invoice')) }}</a>

                @if($task->is_project_creates=="No")
                <a class="dropdown-item confirm-action-info" href="javascript:void(0)"
                    data-confirm-title="Convert To Project"
                    data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-url="{{ url('/estimates') }}/{{ $task->task_id }}/convert">
                    Convert To Project</a>
                @endif

            </div>
        </span>
        {{-- this is estimate code --}}
    </td>



    <!--more button (team)-->
    @if (auth()->user()->is_team && $task->permission_super_user)
    <span class="list-table-action dropdown  font-size-inherit">
        <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false" title="{{ cleanLang(__('lang.more')) }}"
            class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
            <i class="ti-more"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="listTableAction">
            <a class="dropdown-item confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.stop_all_timers')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="PUT"
                data-url="{{ url('/') }}/tasks/timer/{{ $task->task_id }}/stopall?source=list">
                {{ cleanLang(__('lang.stop_all_timers')) }}
            </a>
        </div>
    </span>
    @endif
    <!--more button-->
    <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->
