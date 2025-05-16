@foreach($demos as $demo)
<!--each row-->
<tr id="demos_{{ $demos->demo_id }}">
    <td class="demos_col_id">{{ config('system.settings_invoices_prefix') }}{{ $demos->demo_id }}</td>
    <td class="demos_col_company">
        {{ str_limit($demos->demo_foo ?? '---', 12) }}
    </td>
    <td class="demos_col_project">
        {{ str_limit($demos->demo_foo ?? '---', 25) }}
    </td>
    <td class="demos_col_date">
        24-12-2018
    </td>
    <td class="demos_col_amount">$10,000</td>
    <td class="demos_col_demos">$4,000</td>
    <td class="demos_col_balance">
        $3,000
    </td>
    <td class="demos_col_status">
        <span class="label label-warning">New</span>
    </td>
    <td class="demos_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                data-ajax-type="DELETE" data-url="{{ url('/') }}/demos/{{ $demos->demo_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            <!--edit-->
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/demos/'.$demo->demo_id.'/edit') }}" data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.edit_item')) }}" data-action-url="{{ urlResource('/demos/'.$demo->demo_id) }}"
                data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request"
                data-action-ajax-loading-target="demos-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <!--view-->
            <a href="/demo/{{ $demos->demo_id }}" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <!--action button-->
        <!--more button (hidden)-->
        <span class="list-table-action dropdown hidden font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                class="btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <a class="dropdown-item" href="javascript:void(0)">
                    <i class="ti-new-window"></i> {{ cleanLang(__('lang.view_details')) }}</a>
            </div>
        </span>
        <!--more button-->
    </td>
</tr>
@endforeach
<!--each row-->