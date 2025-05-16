<table class="table table-bordered border-secondary">
    <thead style="text-align:center;">
        <tr style="border: 1px solid black">
            <th rowspan="3" style="border: 1px solid black">Item </th>
            <th rowspan="3" style="border: 1px solid black">Description</th>
            <th rowspan="3" style="border: 1px solid black">Unit</th>
            <th rowspan="3" style="border: 1px solid black">Qty</th>
            <th colspan="6" style="border: 1px solid black; text-align:center; ">Rate</th>
            <th rowspan="3" style="border: 1px solid black">Total($)</th>
            <th rowspan="3" style="border: 1px solid black">Amount</th>
        </tr>
        <tr style="border: 1px solid black">
            <th rowspan="2" style="border: 1px solid black">Labour</th>
            <th rowspan="2" style="border: 1px solid black">Material</th>
            <th rowspan="2" style="border: 1px solid black">Misc</th>
            <th colspan="2" style="border: 1px solid black">Wastage</th>
            <th rowspan="2" style="border: 1px solid black">S/C($)</th>
        </tr>
        <tr style="border: 1px solid black">
            <th style="border: 1px solid black">%</th>
            <th style="border: 1px solid black">$</th>
        </tr>
        <tr style="background-color:peachpuff">
            <th class="text-start"></th>
            <th class="text-start" style="text-align:left!important;"><b>BILL NO. 1 - PRELIMINARIES</b></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start" style="text-align:left!important;">$ <span id="grand-total" class="elec-gt"></span>
            </th>

        </tr>

    </thead>
    {{-- <tbody id="table-body">
                <tr class="fw-bold">
                    @if (config('visibility.bill_mode') == 'viewing')
                        <td>1</td>
                        <td>Visit to site and premises of work by Contractor</td>
                        <td>sum</td>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                        <td>6</td>
                        <td>7</td>
                        <td>8</td>
                        <td>9</td>
                    @elseif (config('visibility.bill_mode') == 'editing')
                        <td>1</td>
                        <td><input type="text" value="Visit to site and premises of work by Contractor" style="width: 100%;" min="1" class="form-control qty-input" /></td>
                        <td>sum</td>
                        <td><input type="text" value="1" style="width: 50px;" min="1" class="form-control qty-input" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control total-input" style="width: 70px;" readonly /></td>
                        <td><input type="text" value="1" class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
                    @endif
                </tr>
                <tr>
                    @if (config('visibility.bill_mode') == 'viewing')
                    <td>2</td>
                    <td>Setting out of works, profile and levels by qualified licensed land surveyor, including joint survey for
                        piling works</td>
                    <td>sum</td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    @elseif (config('visibility.bill_mode') == 'editing')
                    <td>1</td>
                        <td><input type="text" value="Setting out of works, profile and levels by qualified licensed land surveyor, including joint survey for
                            piling works" style="width: 100%;" min="1" class="form-control qty-input" /></td>
                        <td>sum</td>
                        <td><input type="text" value="1" style="width: 50px;" min="1" class="form-control qty-input" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" value="1" class="form-control total-input" style="width: 70px;" readonly /></td>
                        <td><input type="text" value="1" class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
                        @endif
                </tr>
                <tr id="subtotal-row" style="border-top: 2px solid black;">
                    <td></td>
                    <td>Sub-total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Add: Contractor's Profit</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="border-top: 3px solid black; font-weight: bold;">
                    <td></td>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody> --}}
    <tbody id="preliminaries-table-body">
        @if (config('visibility.bill_mode') == 'viewing')
        <?php
        $preliminariesTotal = DB::table('variation_templates')
            ->where('template_id', 1)
            ->where('estimates_id', $bill->vo_id)
            ->sum('total');

        $preliminariesAmount = DB::table('variation_templates')
            ->where('template_id', 1)
            ->where('estimates_id', $bill->vo_id)
            ->sum('amount');

        $preliminaries = [
            'total' => $preliminariesTotal,
            'amount' => $preliminariesAmount,
        ];
        ?>
        @php
        $i = 0; // Increment serial number
        @endphp
        @foreach ($quotation_templates as $data)
        @if ($data->template_id == 1 && $data->quotation_no== $page['crumbs'][2])
        @php
        $i++; // Increment serial number
        @endphp
        <tr class="fw-bold">
            <td>{{ $i }}</td> <!-- Serial No. -->
            <td class="wrap-text">{{ $data->description }}</td>
            <td>{{ $data->unit }}</td>
            <td>{{ $data->qty }}</td>
            <td>{{ $data->labour }}</td>
            <td>{{ $data->material }}</td>
            <td>{{ $data->misc }}</td>
            <td>{{ $data->wastage_percent }}</td>
            <td>{{ $data->wastage_amount }}</td>
            <td>{{ $data->sc }}</td>
            <td>{{ $data->total }}</td>
            <td>{{ $data->amount }}</td>
        </tr>
        @endif
        @endforeach
        @elseif (config('visibility.bill_mode') == 'editing')
        <?php
        $preliminariesTotal = DB::table('variation_templates')
            ->where('template_id', 1)
            ->where('estimates_id', $bill->vo_id)
            ->sum('total');

        $preliminariesAmount = DB::table('variation_templates')
            ->where('template_id', 1)
            ->where('estimates_id', $bill->vo_id)
            ->sum('amount');

        $preliminaries = [
            'total' => $preliminariesTotal,
            'amount' => $preliminariesAmount,
        ];
        ?>
        @foreach ($quotation_templates as $index => $data)
        @if ($data->template_id == 1 && $data->quotation_no== $page['crumbs'][2])
        <tr class="fw-bold">
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                    data-id="{{ $data->id }}"
                    data-url="{{ route('deletedata', ['id' => $data->id]) }}">
                    <i class="sl-icon-trash"></i>
                </button>
            </td>
            <td>
                <textarea name="description[]" rows="5" class="form-control description-input" autocomplete="off">{{ $data->description }}</textarea>

            </td>
            <td><select class="form-control" name="unit[]">
                    <option value="sum" {{ $data->unit == 'sum' ? 'selected' : '' }}>Sum</option>
                    <option value="mth" {{ $data->unit == 'mth' ? 'selected' : '' }}>mth</option>
                </select></td>
            <td><input type="text" name="qty[]" value="{{ $data->qty }}"
                    class="form-control qty-input" min="1" style="width: 70px;" /></td>
            <td><input type="text" name="labour[]" value="{{ $data->labour }}"
                    class="form-control rate-input humara-class" style="width: 70px;" /></td>
            <td><input type="text" name="material[]" value="{{ $data->material }}"
                    class="form-control rate-input humara-class" style="width: 70px;" /></td>
            <td><input type="text" name="misc[]" value="{{ $data->misc }}"
                    class="form-control rate-input humara-class" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_percent[]" value="{{ $data->wastage_percent }}"
                    class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_amount[]" value="{{ $data->wastage_amount }}"
                    class="form-control rate-input humara-class" style="width: 70px;" /></td>
            <td><input type="text" name="sc[]" value="{{ $data->sc }}"
                    class="form-control rate-input humara-class" style="width: 70px;" /></td>
            <td><input type="text" name="total[]" value="{{ $data->total }}"
                    class="form-control total-input" style="width: 70px;" readonly /></td>
            <td><input type="text" name="amount[]" value="{{ $data->amount }}"
                    class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
            <input type="hidden" name="quotation_no[]" value="{{ $page['crumbs'][2] }}" />
            <input type="hidden" name="template_id[]" value="1" />
            <input type="hidden" name="id[]" value="{{ $data->id }}" />
        </tr>
        @endif
        @endforeach
        @endif

    </tbody>
    <tfoot>
        <tr id="subtotal-row" style="border-top: 2px solid black;">
            <td></td>
            <td colspan="9" style="text-align:right;">Sub-total</td>

            <td id="total-subtotal">{{ $preliminaries['total'] }}</td>
            <td id="amount-subtotal">{{ $preliminaries['amount'] }}</td>
        </tr>
        <tr id="subtotal-row" style="border-top: 2px solid black;">
            <td></td>
            <td colspan="9" style="text-align:right;">Contractor Profit</td>

            <td id="total-subtotal"></td>
            <td id="amount-subtotal">{{ $preliminaries['amount']*0.1 }}</td>
        </tr>
        <tr id="subtotal-row" style="border-top: 2px solid black;">
            <td></td>
            <td colspan="9" style="text-align:right;">Total Amount</td>

            <td id="total-subtotal"></td>
            <td id="amount-subtotal">{{ $preliminaries['amount']*0.1+$preliminaries['amount']}}</td>
        </tr>
    </tfoot>
</table>


@if (config('visibility.bill_mode') == 'editing')
<button type="button" id="preliminaries-billing-time-actions-blank"
    class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
    <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
    <span>{{ cleanLang(__('lang.new_blank_line')) }}</span>
</button>
@endif



<script>
    $(document).ready(function() {
        // Append new row when button is clicked
        $('#preliminaries-billing-time-actions-blank').on('click', function() {
            $('#preliminaries-table-body').append(`
            <tr>
                <td>
                    <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                        <i class="sl-icon-trash"></i>
                    </button>
                </td>
                <td><textarea rows="5" name="description[]" style="width: 100%;" class="form-control description-input"></textarea><div id="" class="dropdown-list"></div></td>
                <td>
                    <input class="form-control unit-input" name="unit[]">

                    </>
                </td>
                <td><input type="text" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;" /></td>
                <td><input type="text" name="labour[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="text" name="material[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="text" name="misc[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="text" name="wastage_percent[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
                <td><input type="text" name="wastage_amount[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="text" name="sc[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="text" name="total[]" value="" class="form-control total-input" style="width: 70px;" readonly /></td>
                <td><input type="text" name="amount[]" value="" class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
                <input type="hidden" name="quotation_no[]" class="form-control" value="{{ $page['crumbs'][2] }}">
                <input type="hidden" name="template_id[]" class="form-control" value="1">
                <input type="hidden" name="id[]" value="">
            </tr>
        `);
        });

        // Event delegation for dynamically added delete buttons
        $('#preliminaries-table-body').on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
