<table class="table table-bordered border-secondary">
    <thead>
        <tr>
            <th rowspan="3">Item</th>
            <th rowspan="3">Description</th>
            <th rowspan="3">Unit</th>
            <th rowspan="3">Qty</th>
            <th colspan="6">Rate</th>
            <th rowspan="3">Total($)</th>
            <th rowspan="3">AMOUNT</th>
        </tr>
        <tr>
            <th rowspan="2">Labour</th>
            <th rowspan="2">Material</th>
            <th rowspan="2">Misc</th>
            <th colspan="2">Wastage</th>
            <th rowspan="2">S/C($)</th>
        </tr>
        <tr>
            <th>%</th>
            <th>$</th>
        </tr>
        <tr>
            <th class="text-start"></th>
            <th class="text-start">BILL NO. 2 - INSURANCES FOR THE WORKS</th>
            <th class="text-start">mth</th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start">$</th>
            <th class="text-start"></th>
        </tr>
    </thead>
    {{-- <tbody id="insurance-table-body">
        <tr>
            @if (config('visibility.bill_mode') == 'viewing')
            <td>1</td>
            <td>Insurance against Injury to Person & Property</td>
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
            <td><input type="text" value="Insurance against Injury to Person & Property" style="width: 100%;" min="1" class="form-control qty-input" /></td>
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
            <td>The Contractor is reminded that he must take out and maintain Public Liability insurance policy in
                the joint names of Employer including all Consultants and the Contractor (including all
                sub-contractors) for their respective rights and interests up to a limit of S$2 Millions for any one
                period of insurance to cover the liability stated in the said Clause 18 & 19.
                In addition, the Contractor shall ensure that the following endorsement is inserted into the said
                Public Liability insurance policy.</td>
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
            <td>2</td>
            <td><input type="text" value="The Contractor is reminded that he must take out and maintain Public Liability insurance policy in
                the joint names of Employer including all Consultants and the Contractor (including all
                sub-contractors) for their respective rights and interests up to a limit of S$2 Millions for any one
                period of insurance to cover the liability stated in the said Clause 18 & 19.
                In addition, the Contractor shall ensure that the following endorsement is inserted into the said
                Public Liability insurance policy." style="width: 100%;" min="1" class="form-control qty-input" /></td>
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
        <tr id="insurance-add-row" style="border-top: 2px solid black;">
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
    <tbody id="insurance-table-body">
        @if (config('visibility.bill_mode') == 'viewing')
        <?php
        $insuranceTotal = DB::table('variation_templates')
            ->where('template_id', 2)
            ->where('quotation_no', $bill->est_quotation_no)
            ->sum('total');

        $insuranceAmount = DB::table('variation_templates')
            ->where('template_id', 2)
            ->where('quotation_no', $bill->est_quotation_no)
            ->sum('amount');

        $insurance = [
            'total' => $insuranceTotal,
            'amount' => $insuranceAmount,
        ];
        ?>
        @php
        $i = 0; // Increment serial number
        @endphp
        @foreach ($quotation_templates as $index => $data)
        @if ($data->template_id == 2 && $data->quotation_no == $page['crumbs'][2])
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
        $insuranceTotal = DB::table('quotation_templates')
            ->where('template_id', 2)
            ->where('quotation_no', $bill->est_quotation_no)
            ->sum('total');

        $insuranceAmount = DB::table('quotation_templates')
            ->where('template_id', 2)
            ->where('quotation_no', $bill->est_quotation_no)
            ->sum('amount');

        $insurance = [
            'total' => $insuranceTotal,
            'amount' => $insuranceAmount,
        ];
        ?>
        @foreach ($quotation_templates as $index => $data)
        @if ($data->template_id == 2 && $data->quotation_no == $page['crumbs'][2])
        <tr class="fw-bold">
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                    data-id="{{ $data->id }}"
                    data-url="{{ route('deletedata', ['id' => $data->id]) }}">
                    <i class="sl-icon-trash"></i>
                </button>
            </td>
            <td>
                <textarea name="description[]" rows="5" class="form-control description-input">{{ $data->description }}</textarea>
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
                    class="form-control rate-input humara-class" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_amount[]" value="{{ $data->wastage_amount }}"
                    class="form-control rate-input" style="width: 70px;" /></td>
            <td><input type="text" name="sc[]" value="{{ $data->sc }}"
                    class="form-control rate-input humara-class" style="width: 70px;" /></td>
            <td><input type="text" name="total[]" value="{{ $data->total }}"
                    class="form-control total-input" style="width: 70px;" readonly /></td>
            <td><input type="text" name="amount[]" value="{{ $data->amount }}"
                    class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
            <input type="hidden" name="quotation_no[]" value="{{ $page['crumbs'][2] }}" />
            <input type="hidden" name="template_id[]" value="2" />
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

            <td id="total-subtotal">{{ $insurance['total'] }}</td>
            <td id="amount-subtotal">{{ $insurance['amount'] }}</td>
        </tr>
        <tr id="subtotal-row" style="border-top: 2px solid black;">
            <td></td>
            <td colspan="9" style="text-align:right;">Contractor Profit</td>

            <td id="total-subtotal"></td>
            <td id="amount-subtotal">{{ $insurance['amount']*0.1 }}</td>
        </tr>
        <tr id="subtotal-row" style="border-top: 2px solid black;">
            <td></td>
            <td colspan="9" style="text-align:right;">Total Amount</td>

            <td id="total-subtotal"></td>
            <td id="amount-subtotal">{{ $insurance['amount']*0.1+$insurance['amount']}}</td>
        </tr>
    </tfoot>
</table>
@if (config('visibility.bill_mode') == 'editing')
<button type="button" id="insurance_new_blank_line"
    class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
    <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
    <span>{{ cleanLang(__('lang.new_blank_line')) }}</span>
</button>

@endif
<script>
    /*Humara Code*/
    $(document).ready(function() {
        $('#insurance_new_blank_line').on('click', function() {
            $('#insurance-table-body').append(`
                    <tr>
                        <td>
                            <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                                <i class="sl-icon-trash"></i>
                            </button>
                        </td>
                        <td><textarea name="description[]" rows="5" style="width: 100%;" class="form-control description-input" ></textarea>
<div id="t" class="dropdown-list"></div>
                        </td>
                        <td>
                            <input class="form-control unit-input" name="unit[]" >

                            </>
                        </td>
                        <td><input type="text" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;"/></td>
                        <td><input type="text" name="labour[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="material[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="misc[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="wastage_percent[]" value="" class="form-control rate-input" style="width: 70px;" /></td>
                        <td><input type="text" name="wastage_amount[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="sc[]" value="" class="form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="total[]" value="" class="form-control total-input" style="width: 70px;" readonly /></td>
                        <td><input type="text" name="amount[]" value="" class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
                        <input type="hidden" name="quotation_no[]" class="form-control" value="{{ $page['crumbs'][2] }}">
                        <input type="hidden" name="template_id[]" class="form-control" value="2">
                        <input type="hidden" name="id[]" value="">
                    </tr>
                `);
        });

        $('#insurance-table-body').on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
