@php
$firtt_back = 'firtt_back';
$atz = 65;
$amount = 0;
$budget_amount = 0;
$petty_case_amount = 0;
$purchase_order_amount = 0;
$invoice = 0;
@endphp

<style>
    .clients_col_company span {
        display: inline-block;
        max-width: auto;
        /* Adjust as needed */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        word-wrap: break-word;
    }
</style>

@foreach ($templete_category as $T => $TM)
@php
$cat_id_exist = DB::table('tasks')->where('task_cat_id', $T)->exists();
$i = 0;
@endphp
@if ($cat_id_exist)
<tr>
    <td style="background-color: coral;">SECT {{ chr($atz) }}</td>
    <td style="background-color: coral;">{{ $TM }}</td>
    <td></td>
    <td></td>
    <td></td>
    <td style="background-color: coral;"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="background-color: greenyellow;"></td>
    <td></td>
</tr>
@endif
@foreach ($grn_data as $k => $client)
@if ($client->task_cat_id == $T)
@php
$budget_amount+=get_data_budget_data($client->task_cat_id, $client->task_id)

@endphp
<!-- Each row -->
<tr id="client_{{ $client->task_id }}">
    <td class="clients_col_id" id="clients_col_id_{{ $client->task_id }}">{{ $i + 1 }}</td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}" >
        <span style="width:250px;word-wrap:break-word;white-space:normal;">{{ $client->task_title ?? '---' }}</span>
    </td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}"></td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}">
        {{ str_limit($client->task_unit ?? '', 35) }}
    </td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}">
        {{ str_limit($client->task_qtn ?? '', 35) }}
    </td>
    <td class="clients_col_company " style="background-color: coral;"
        id="clients_col_id_{{ $client->task_id }}"><input type="number" data-t_id="{{$client->task_id}}" data-c_id="{{$client->task_cat_id}}" class="form-control budget"
            value="{{ get_data_budget_data($client->task_cat_id, $client->task_id) }}"></td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}"></td>
    <td class="clients_col_company contract" id="clients_col_id_{{ $client->task_id }}">
        {{ str_limit($client->task_total ?? 00, 35) }}
    </td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}">
        {{ $client->purchase_order_total_format }}
    </td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}">
        {{ get_petty_case_invoice($client->task_cat_id, $client->task_id, $client->task_projectid) }}
    </td>
    <td class="clients_col_company invoice" id="clients_col_id_{{ $client->task_id }}">
        {{ invoice_amount($client->task_cat_id, $client->task_id, $client->task_projectid) }}
    </td>
    @php
    $surplus_deficit =
    $client->task_total+
    $client->purchase_order_total +
    get_petty_case_invoice($client->task_cat_id, $client->task_id, $client->task_projectid) -
    invoice_amount($client->task_cat_id, $client->task_id, $client->task_projectid);
    $b_amount=get_data_budget_data($client->task_cat_id, $client->task_id)
    @endphp
    <td class="clients_col_company itemized surplus_deficit" style="background-color: greenyellow;"
        id="clients_col_id_{{ $client->task_id }}" data-surplus_deficit="{{$surplus_deficit}}">

        {{ number_format($surplus_deficit+$b_amount, 2) }}
    </td>
    <td class="clients_col_company" id="clients_col_id_{{ $client->task_id }}"></td>

    @if (config('visibility.action_column'))
    <td class="clients_col_action actions_column" id="clients_col_action_{{ $client->task_id }}">
        <span class="list-table-action dropdown font-size-inherit">
            @if (config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_client')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                data-ajax-type="DELETE" data-url="{{ url('/clients/' . $client->task_id) }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            @if (config('visibility.action_buttons_edit'))
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/clients/' . $client->task_id . '/edit') }}"
                data-loading-target="commonModalBody"
                data-modal-title="{{ cleanLang(__('lang.edit_client')) }}"
                data-action-url="{{ urlResource('/clients/' . $client->task_id . '?ref=list') }}"
                data-action-method="PUT" data-action-ajax-loading-target="clients-td-container">
                <i class="sl-icon-note"></i>
            </button>
            @endif
            <a href="{{ url('/clients/' . $client->task_id) ?? '' }}"
                class="btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
    </td>
    @endif
</tr>

@php
$amount += $client->task_total;
$purchase_order_amount += $client->purchase_order_total;
$invoice += invoice_amount($client->task_cat_id, $client->task_id, $client->task_projectid);
$petty_case_amount += get_petty_case_invoice(
$client->task_cat_id, $client->task_id,
$client->task_projectid,
);
$firtt_back = '';
$i+=1;
@endphp
@endif
@endforeach
@php
$atz += 1;

@endphp
@endforeach

<tr>
    <td></td>
    <td></td>
    <td>Total</td>
    <td></td>
    <td>0</td>
    <td id="budget">{{$budget_amount}}</td>
    <td>0</td>
    <td>{{ number_format($amount,2) }}</td>
    <td>{{ number_format($purchase_order_amount,2) }}</td>
    <td>{{ number_format($petty_case_amount,2) }}</td>
    <td>{{ number_format($invoice,2) }}</td>
    <td id="total_surplus_deficit">
        @php
        $total_surplus_deficit = $amount + $purchase_order_amount + $petty_case_amount - $invoice;
        @endphp
        {{ number_format($total_surplus_deficit, 2) }}
    </td>
    <td></td>
</tr>
