<table class="table table-bordered border-secondary">
    <thead style="text-align:center;">
        <tr style="border: 1px solid black">
            <th style="border: 1px solid black">Item</th>
            <th style="border: 1px solid black">Description</th>
            <th style="border: 1px solid black">Amount</th>
        </tr>

        <tr style="background-color:peachpuff">
            <th class="text-start"></th>
            <th class="text-start" style="text-align:left!important;">Provide the following Prime Cost & Provisional Sums for works to be executed by Nominated Suppliers & Sub-Contractors or expended by Architect as directed. The sums shall be adjusted in due course.</th>
            <th class="text-start" style="text-align:left!important;">$ <span id="grand-total" class="elec-gt"></span></th>

        </tr>

    </thead>
    <tbody id="pcps-table-body">
        @if (config('visibility.bill_mode') == 'viewing')
        <?php
        // $pcpsTotal = DB::table('quotation_templates')
        //     ->where('template_id', 7)
        //     ->where('quotation_no', $bill->est_quotation_no)
        //     ->sum('total');

        $pcpsAmount = DB::table('quotation_templates')
            ->where('template_id', 7)
            ->where('quotation_no', $bill->est_quotation_no)
            ->sum('amount');

        $pcps = [
            // 'total' => $pcpsTotal,
            'amount' => $pcpsAmount,
        ];
        ?>
        @php $i = 0; @endphp
        @foreach ($quotation_templates as $index => $data)
        @if ($data->template_id == 7 && $data->quotation_no== $page['crumbs'][2])
        @php $i++; @endphp
        <tr class="fw-bold">
            <td>{{ $i }}</td>
            <td class="wrap-text">{{ $data->description }}</td>
            <td>{{ $data->amount }}</td>
        </tr>
        @endif
        @endforeach
        @elseif (config('visibility.bill_mode') == 'editing')
        <?php
        // $pcpsTotal = DB::table('quotation_templates')
        //     ->where('template_id', 7)
        //     ->where('quotation_no', $bill->est_quotation_no)
        //     ->sum('total');

        $pcpsAmount = DB::table('quotation_templates')
            ->where('template_id', 7)
            ->where('quotation_no', $bill->est_quotation_no)
            ->sum('amount');

        $pcps = [
            // 'total' => $pcpsTotal,
            'amount' => $pcpsAmount,
        ];
        ?>
        @foreach ($quotation_templates as $index => $data)
        @if ($data->template_id == 7 && $data->quotation_no== $page['crumbs'][2])
        <tr class="fw-bold">
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm" data-id="{{ $data->id }}" data-url="{{ route('deletedata', ['id' => $data->id]) }}">
                    <i class="sl-icon-trash"></i>
                </button>
            </td>
            <td><textarea rows="5" name="description[]" class="form-control description-input">{{ $data->description }}</textarea></td>
            <td><input type="text" name="amount[]" value="{{ $data->amount }}" class="form-control quotation-amount-input" style="width: 100px;" /></td>
            <input type="hidden" name="quotation_no[]" value="{{ $page['crumbs'][2] }}" />
            <input type="hidden" name="template_id[]" value="7" />
            <input type="hidden" name="id[]" value="{{ $data->id }}" />
        </tr>
        @endif
        @endforeach
        @endif
    </tbody>
    <tr id="subtotal-row" style="border-top: 2px solid black;">
        <td></td>
        <td style="text-align:left;"><b>TOTAL PROVISIONAL SUMS CARRIED TO GENERAL SUMMARY OF TENDER</b></td>

        {{-- <td id="total-subtotal">{{$pcps['total']}}</td> --}}
        <td id="amount-subtotal">{{number_format($pcps['amount'],2)}}</td>
    </tr>
    <tr>
        <td></td>
        <td><b><u>PROFIT & ATTENDANCE ALLOWANCE</u></b></td>
        <td id="amount-subtotal"></td>
    </tr>
    <tr>
        <td></td>
        <td>Allow for Main Contractor’s profit on P.C. Items</td>
        <td id="amount-subtotal">{{ number_format(0.05*$pcps['amount'],2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Allow for Main Contractor’s attendance on P.C. Items.</td>
        <td id="amount-subtotal">{{ number_format(0.05*$pcps['amount'],2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Sub-total</td>
        <td id="amount-subtotal">{{number_format($pcps['amount'],2)}}</td>
    </tr>
    <tr>
        <td></td>
        <td>Add: Contractor's Profit</td>
        <td id="amount-subtotal">{{ number_format(0.015*$pcps['amount'],2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td><b>TOTAL</b></td>
        <td id="amount-subtotal">{{ number_format(0.015*$pcps['amount']+$pcps['amount'],2) }}</td>
    </tr>
</table>

@if (config('visibility.bill_mode') == 'editing')
<button type="button" id="pcps_new_blank_line" class="btn btn-secondary btn-rounded btn-sm">
    <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
    <span>{{ cleanLang(__('lang.new_blank_line')) }}</span>
</button>
@endif
<script>
    /*Humara Code*/
    $(document).ready(function() {
        $('#pcps_new_blank_line').on('click', function() {
            $('#pcps-table-body').append(`
        <tr>
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                    <i class="sl-icon-trash"></i>
                </button>
            </td>
            <td><textarea name="description[]" rows="5" style="width: 100%;" class="form-control description-input" ></textarea></td>
            <td><input type="text" name="amount[]" value="" class="form-control quotation-amount-input" style="width: 70px;" /></td>
            <input type="hidden" name="quotation_no[]" class="form-control" value="{{ $page['crumbs'][2] }}">
            <input type="hidden" name="template_id[]" class="form-control" value="7">
            <input type="hidden" name="id[]" value="">
        </tr>
                `);
        });
        $('#pcps-table-body').on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
        });

    });
</script>
