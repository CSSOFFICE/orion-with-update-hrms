
@foreach ($stock_move_data as $key => $note)
@if($note->total_quantity>0)
    <!--each row-->
    <tr id="note_{{ $key }}">
        @if (config('visibility.notes_col_checkboxes'))
            <td class="notes_col_checkbox checkitem" id="notes_col_checkbox_{{ $key }}">
                <!--list checkbox-->
                <span class="list-checkboxes display-inline-block w-px-20">
                    <input type="checkbox" id="listcheckbox-notes-{{ $key }}" name="ids[{{ $key }}]"
                        class="listcheckbox listcheckbox-notes filled-in chk-col-light-blue"
                        data-actions-container-class="notes-checkbox-actions-container">
                    <label for="listcheckbox-notes-{{ $key }}"></label>
                </span>
            </td>
        @endif

        <td class="notes_col_title">
            <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal"
                data-url="{{ url('/') }}/notes/{{ $key }}" data-target="#plainModal"
                data-loading-target="plainModalBody" data-modal-title=" ">
                {{ $note->product_name }}
            </a>
        </td>
        <td class="notes_col_tags">
            <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal"
                data-url="{{ url('/') }}/notes/{{ $key }}" data-target="#plainModal"
                data-loading-target="plainModalBody" data-modal-title=" ">
                {{ $note->total_quantity }}
            </a>
        </td>
        <td class="projects_col_action actions_column">
            <!--action button-->
            @if (config('visibility.action_buttons_edit'))
            <span class="list-table-action dropdown font-size-inherit">
                <!--[inventory-return]-->
                <button type="button" title="Inventory Return"
                    class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    data-toggle="modal" data-target="#commonModal"
                    data-url="{{ urlResource('/projectinventory/inventory-return/?project_id='.$project_id.'&product_id='.$note->product_id.'&total_qty='.$note->quantity.'&old_ware='.$note->warehouse_id) }}"
                    data-loading-target="commonModalBody" data-modal-title="Inventory Return"
                    data-action-url="{{urlResource('projectinventory/inventory-return-submit')}}" data-action-method="POST">
                    <i class="sl-icon-note"></i>
                </button>
            </span>
            @endif
            <!--action button-->
        </td>
    </tr>
    @endif
@endforeach


<!--each row-->
