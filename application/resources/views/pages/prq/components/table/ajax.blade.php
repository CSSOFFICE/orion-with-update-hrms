@foreach($grn_data as $note)
<!--each row-->
<tr id="note_{{ $note->purchase_requistion_id }}">
    @if(config('visibility.notes_col_checkboxes'))
    <td class="notes_col_checkbox checkitem" id="notes_col_checkbox_{{ $note->purchase_requistion_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-notes-{{ $note->grn_id }}" name="ids[{{ $note->purchase_requistion_id }}]" class="listcheckbox listcheckbox-notes filled-in chk-col-light-blue" data-actions-container-class="notes-checkbox-actions-container">
            <label for="listcheckbox-notes-{{ $note->purchase_requistion_id }}"></label>
        </span>
    </td>
    @endif

    <td class="notes_col_title">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="{{ url('/') }}/prq/{{  $note->purchase_requistion_id }}" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            {{ $note->porder_id }}
        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="{{ url('/') }}/notes/{{  $note->purchase_requistion_id }}" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            {{-- {{ $note->required_date }} --}}
            {{ $note->created_datetime }}
        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="{{ url('/') }}/notes/{{  $note->purchase_requistion_id }}" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            {{ $note->created_datetime }}
        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="{{ url('/') }}/notes/{{  $note->purchase_requistion_id }}" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            {{ $note->status }}
        </a>
    </td>
    <!-- <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request">
            {{ $note->purchase }}
        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request">
            {{ $note->site_address }}
        </a>
    </td> -->
    <td class="notes_col_action  actions_column {{ $page[ 'visibility_col_action'] ?? '' }} ">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            @if (config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_note')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                data-ajax-type="DELETE" data-url="{{ url( '/') }}/prq/{{  $note->purchase_requistion_id }} ">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            @if (config('visibility.action_buttons_edit'))
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/prq/'.$note->purchase_requistion_id.'/edit') }}" data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.edit_note')) }}"
                data-action-url="{{ urlResource('/prq/'.$note->purchase_requistion_id.'?ref=list') }}" data-action-method="PUT"
                data-action-ajax-class="" data-action-ajax-loading-target="notes-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif


            <a href="javascript:void(0)" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm show-modal-button js-ajax-ux-request"
                data-toggle="modal" data-url="{{ url( '/') }}/prq/{{  $note->purchase_requistion_id }} " data-target="#plainModal"
                data-loading-target="plainModalBody" data-modal-title="">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <span>
            @if($note->engineer_status===111)
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" id="Project_Manager_id" data-id="{{$note->purchase_requistion_id}}" data-type="pending_engineer">Engineer Approval</button>
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" data-id="{{$note->purchase_requistion_id}}" data-type="pending_rejected">Reject</button>


            @elseif($note->project_status===199)
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" id="" data-id="{{$note->purchase_requistion_id}}" data-type="pending_project">Pending Project Manager Approval</button>
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" data-id="{{$note->purchase_requistion_id}}" data-type="pending_rejected">Reject</button>

            @elseif($note->managemant_status===992)
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" id="" data-id="{{$note->purchase_requistion_id}}" data-type="pending_approved">Approve</button> /
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" data-id="{{$note->purchase_requistion_id}}" data-type="pending_rejected">Reject</button>


            @endif
            <!--action button-->
        </span>

    </td>



</tr>
@endforeach
<!--each row-->
<script>
    $("body").on("click", '.Project_Manager_id', function() {
        const csrfToken = "{{ csrf_token() }}";
        let id = $(this).data('id');
        let type = $(this).data('type');
        $.ajax({
            url: "{{route('prq.change_status')}}",
            type: "post",
            data: {
                id: id,
                type: type,
                _token: csrfToken
            },
            success: function(re) {
                window.location.reload(1);
            }
        })

    })
</script>
