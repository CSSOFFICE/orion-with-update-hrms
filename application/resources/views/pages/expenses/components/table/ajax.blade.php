<?php $i=0;?>
@foreach($expenses_data as $expense)
<?php $i++;?>
<!--each row-->
<tr id="expense_{{ $expense->purchase_order_id }}">
    @if(config('visibility.expenses_col_checkboxes'))
    <td class="expenses_col_checkbox checkitem" id="expenses_col_checkbox_{{ $expense->payable_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-expenses-{{ $expense->payable_id }}"
                name="ids[{{ $expense->payable_id }}]"
                class="listcheckbox listcheckbox-expenses filled-in chk-col-light-blue expenses-checkbox"
                data-actions-container-class="expenses-checkbox-actions-container"
                data-expense-id="{{ $expense->payable_id }}" data-unit="{{ cleanLang(__('lang.item')) }}" data-quantity="1"
                data-description="{{ $expense->expense_description??'' }}" data-rate="{{ $expense->expense_amount??'' }}">
            <label for="listcheckbox-expenses-{{ $expense->payable_id }}"></label>
        </span>
    </td>
    @endif
    @if(config('visibility.expenses_col_date'))
    <td class="expenses_col_date">
        <?php echo $i;?>

    </td>
    @endif
    @if(config('visibility.expenses_col_description'))
    <td class="expenses_col_description">

        <span  title="{{ $expense->expense_description??'' }}">{{ $expense->invoice_no ?? '---'}}</span>
    </td>
    @endif
    <!--column visibility-->
    @if(config('visibility.expenses_col_project'))
    <td class="expenses_col_project">
        <a href="/projects/{{ $expense->project_id ??''}}">{{ str_limit($expense->purchase_order_no ?? '---', 12) }}</a>
    </td>
    @endif
    <!--column visibility-->
    @if(config('visibility.expenses_col_client'))
    <td class="expenses_col_client">
        <img src="{{ getUsersAvatar($expense->avatar_directory??'', $expense->avatar_filename??'') }}" alt="user"
            class="img-circle avatar-xsmall">

            {{$expense->supplier_name}}

    </td>
    @endif

    <!--column visibility-->
    <td class="expenses_col_amount">
        {{ runtimeMoneyFormat($expense->potal) }}
    </td>

    {{-- @if(config('visibility.expenses_col_client'))
    <td class="expenses_col_client">
        <a
            href="/clients/{{ $expense->expense_clientid }}">{{ date('d-m-Y', strtotime($r->created_datetime)) }}</a>
    </td>
    @endif --}}

    @if(config('visibility.expenses_col_status'))
    <td class="expenses_col_client">

        {{$expense->status}}
    </td>
    @endif

    <td>
       {{runtimeDate($expense->expense_created??'')}}

    </td>

    @if(config('visibility.expenses_col_action'))
    <td class="expenses_col_action actions_column" id="expenses_col_action_{{ $expense->payable_id }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
                {{-- <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                    class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                    data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-ajax-type="DELETE" data-url="{{ url('/') }}/expenses/{{ $expense->payable_id }}">
                    <i class="sl-icon-trash"></i>
                </button> --}}

                <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                    class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                    data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                    data-ajax-type="GET" data-url="{{ url('/') }}/expenses/expenses-delete/{{ $expense->purchase_order_id }}">
                    <i class="sl-icon-trash"></i>
                </button>
            @endif
            <!--edit-->

             @if(config('visibility.action_buttons_edit'))

            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/expenses/'.$expense->payable_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_invoice')) }}"
                data-action-url="{{ urlResource('/expenses/'.$expense->payable_id.'?ref=list') }}"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="expenses-td-container">
                <i class="sl-icon-note"></i>
            </button>

            @endif

            <button type="button" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-tooltip show-modal-button btn btn-outline-info btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#plainModal" data-loading-target="plainModalBody"
                data-modal-title="{{ cleanLang(__('lang.expense_records')) }}" data-url="{{ url('/expenses/'.$expense->payable_id) }}">
                <i class="ti-new-window"></i>
            </button>

            <!--more button (team)-->

            @if(config('visibility.action_buttons_edit') == 'show')
            {{-- <span class="list-table-action dropdown font-size-inherit">

                <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" title="{{ cleanLang(__('lang.more')) }}" class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
                    <i class="ti-more"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="listTableAction">
                    @if($expense->expense_billing_status == 'not_invoiced')

                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title=" {{ cleanLang(__('lang.attach_to_project')) }}"
                        data-url="{{ url('/expenses/' . $expense->payable_id .'/attach-dettach') }}"
                        data-action-url="{{ urlResource('/expenses/' . $expense->payable_id .'/attach-dettach') }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.attach_dettach')) }}</a>
                    @endif

                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
                        data-url="{{ url('/expenses/change-category') }}"
                        data-action-url="{{ urlResource('/expenses/change-category?id='.$expense->payable_id) }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.change_category')) }}</a>
                </div>

            </span> --}}
            @endif

            <!--more button-->
        </span>
        <!--action button-->

    </td>
    @endif
</tr>
@endforeach
<!--each row-->
