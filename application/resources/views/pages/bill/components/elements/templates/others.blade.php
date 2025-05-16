<div class="table-responsive">
<table class="table table-bordered border-secondary nowrap w-100">
    <thead style="text-align:center;">
        <tr style="border: 1px solid black">
            <th rowspan="3" style="border: 1px solid black">Item</th>
            <th rowspan="3" style="border: 1px solid black">Description</th>
            <th rowspan="3" style="border: 1px solid black">Unit</th>
            <th rowspan="3" style="border: 1px solid black">Qty</th>
        </tr>
        <tr style="border: 1px solid black">
            <th rowspan="2" style="border: 1px solid black">Labour</th>
            <th rowspan="2" style="border: 1px solid black">Material</th>
            <th rowspan="2" style="border: 1px solid black">Misc</th>
            <th colspan="2" style="border: 1px solid black">Wastage</th>
            <th rowspan="2" style="border: 1px solid black">S/C($)</th>
            <th rowspan="2" style="border: 1px solid black">Net Rate</th>
            <th colspan="2" style="border: 1px solid black">Contractor Profit</th>
            <th rowspan="2" style="border: 1px solid black">Rate</th>
            <th rowspan="2" style="border: 1px solid black">Total Amount($)</th>
        </tr>
        <tr style="border: 1px solid black">
            <th style="border: 1px solid black">%</th>
            <th style="border: 1px solid black">$</th>
            <th style="border: 1px solid black">%</th>
            <th style="border: 1px solid black">$</th>
        </tr>
    </thead>
    <tbody id="others-table-body">
        <tr style="background-color:#FEF2CB">
            <th class="text-start"></th>
            <th class="text-start" style="text-align:left!important;"><b>BILL NO. 8 - Others</b></th>
            <th class="text-start" colspan="12"></th>
            <th class="text-start" style="text-align:left!important;">$<span id="grand-total" class="others-gt"></span>
            </th>
            </th>

        </tr>
        @if (config('visibility.bill_mode') == 'viewing')
        <?php
        $externalTotal = DB::table('quotation_templates')->where('template_id', 8)->where('quotation_no', $bill->est_quotation_no)->sum('total');

        $externalAmount = DB::table('quotation_templates')->where('template_id', 8)->where('quotation_no', $bill->est_quotation_no)->sum('amount');

        $others = [
            'total' => $externalTotal,
            'amount' => $externalAmount,
        ];
        ?>
        @php
        $letterCounter = 'A'; // Start for head as 'B'
        $rowCounter = 0; // Start row counter from 1
        $subtotal = 0; // Initialize subtotal for the current head
        $amountSubtotal = 0;
        @endphp
        @foreach ($quotation_templates as $index => $data)
        @if ($data->template_id == 8 && $data->quotation_no == $page['crumbs'][2])
        @if ($data->type == 'head')
        <tr class="fw-bold" style="background-color:#E2EFD9">
            <td>{{ $letterCounter }}</td> <!-- Alphabet for head -->
            <td class="wrap-text" width="width: 150px;word-wrap: break-word;">
                <b>{!! clean($data->description) !!}</b>
            </td>
            <td colspan="12"><b>{{ $data->unit }}</b></td>
            <td></td>
        </tr>
        @php
        $letterCounter++; // Increment alphabet for next head
        $rowCounter = 0;
        @endphp
        @elseif($data->type == 'row')
        @php
        $rowCounter++; // Increment numeric counter for row
        $subtotal += $data->total; // Add row total to subtotal
        $amountSubtotal += $data->amount; // Add row amount to amountSubtotal
        @endphp
        <tr class="fw-bold">
            <td>{{ $rowCounter }}</td> <!-- Serial No. -->
            <td class="wrap-text">{{ $data->description }}</td>
            <td>{{ $data->unit }}</td>
            <td>{{ $data->qty }}</td>
            <td>{{ number_format($data->labour, 2) }}</td>
            <td>{{ number_format($data->material, 2) }}</td>
            <td>{{ number_format($data->misc, 2) }}</td>
            <td>{{ number_format($data->wastage_percent, 2) }}</td>
            <td>{{ number_format($data->wastage_amount, 2) }}</td>
            <td>{{ number_format($data->sc, 2) }}</td>
            <td>{{ number_format($data->net_rate, 2) }}</td>
            <td>{{ number_format($data->contractor_percent, 2) }}</td>
            <td>{{ number_format($data->contractor_amount, 2) }}</td>
            <td>{{ number_format($data->rate, 2) }}</td>
            <td>{{ number_format($data->total, 2) }}</td>
        </tr>
        @endif
        @endif
        @endforeach
        @elseif (config('visibility.bill_mode') == 'editing')
        <?php
        $externalTotal = DB::table('quotation_templates')->where('template_id', 8)->where('quotation_no', $bill->est_quotation_no)->sum('total');

        $externalAmount = DB::table('quotation_templates')->where('template_id', 8)->where('quotation_no', $bill->est_quotation_no)->sum('amount');

        $others = [
            'total' => $externalTotal,
            'amount' => $externalAmount,
        ];
        ?>
        @foreach ($quotation_templates as $index => $data)
        @if ($data->template_id == 8 && $data->quotation_no == $page['crumbs'][2])
        @if ($data->type == 'head')
        <tr class="fw-bold" style="background-color:#E2EFD9">
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                    data-id="{{ $data->id }}"
                    data-url="{{ route('deletedata', ['id' => $data->id]) }}">
                    <i class="sl-icon-trash"></i>
                </button>

                 <!-- Add Row and Head Buttons -->
                 @if (config('visibility.bill_mode') == 'editing')
                 <button type="button"
                     class="add-row-after btn btn-outline-success btn-circle btn-sm"
                     title="Add Row After">
                     <i class="mdi mdi-plus-circle-outline"></i>
                 </button>
                 <button type="button"
                     class="add-head-after btn btn-outline-primary btn-circle btn-sm"
                     title="Add Head After">
                     <i class="mdi mdi-plus-circle"></i>
                 </button>
             @endif
            </td>
            <td>
                <textarea name="description[]" rows="5" class="form-control description-input" autocomplete="off">{{ $data->description }}</textarea>
            </td>
            <td>
                <select class="form-control unit-input" name="unit[]">
                    <option value="">Select Unit</option>
                    @foreach ($alluoms as $uom)
                    <option value="{{ $uom->unit }}"
                        {{ $data->unit == $uom->unit ? 'selected' : '' }}>{{ $uom->unit }}
                    </option>
                    @endforeach
                </select>
            </td>
            <td><input type="hidden" name="qty[]" value="{{ $data->qty }}"
                    class="form-control qty-input" min="1" style="width: 70px;" /></td>
            <td><input type="hidden" name="labour[]" value="{{ $data->labour }}"
                    class="form-control rate-input humara-class a" style="width: 70px;" /></td>
            <td><input type="hidden" name="material[]" value="{{ $data->material }}"
                    class="form-control rate-input humara-class b" style="width: 70px;" /></td>
            <td><input type="hidden" name="misc[]" value="{{ $data->misc }}"
                    class="form-control rate-input humara-class c" style="width: 70px;" /></td>
            <td><input type="hidden" name="wastage_percent[]" value="{{ $data->wastage_percent }}"
                    class="form-control rate-input d" style="width: 70px;" /></td>
            <td><input type="hidden" name="wastage_amount[]" value="{{ $data->wastage_amount }}"
                    class="form-control rate-input humara-class e" style="width: 70px;" /></td>
            <td><input type="hidden" name="sc[]" value="{{ $data->sc }}"
                    class="form-control rate-input humara-class f" style="width: 70px;" /></td>
            <td><input type="hidden" name="net_rate[]" value="{{ $data->net_rate }}"
                    class="form-control rate-input humara-class g" style="width: 70px;" /></td>
            <td><input type="hidden" name="contractor_percent[]"
                    value="{{ $data->contractor_percent }}" class="form-control rate-input h"
                    style="width: 70px;" /></td>
            <td><input type="hidden" name="contractor_amount[]"
                    value="{{ $data->contractor_amount }}"
                    class="form-control rate-input humara-class i" style="width: 70px;" /></td>
            <td><input type="hidden" name="rate[]" value="{{ $data->rate }}"
                    class="form-control rate-input humara-class j" style="width: 70px;" /></td>
            <td><input type="hidden" name="total[]" value="{{ $data->total }}"
                    class="form-control total-input k" style="width: 70px;" readonly /></td>
            <input type="hidden" name="quotation_no[]" value="{{ $page['crumbs'][2] }}" />
            <input type="hidden" name="template_id[]" value="8" />
            <input type="hidden" name="id[]" value="{{ $data->id }}" />
            <input type="hidden" name="type[]" value="head">
        </tr>
        @elseif($data->type == 'row')
        <tr class="fw-bold">
            <td>
                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                    data-id="{{ $data->id }}"
                    data-url="{{ route('deletedata', ['id' => $data->id]) }}">
                    <i class="sl-icon-trash"></i>
                </button>
                 <!-- Add Row and Head Buttons -->
                 @if (config('visibility.bill_mode') == 'editing')
                 <button type="button"
                     class="add-row-after btn btn-outline-success btn-circle btn-sm"
                     title="Add Row After">
                     <i class="mdi mdi-plus-circle-outline"></i>
                 </button>
                 <button type="button"
                     class="add-head-after btn btn-outline-primary btn-circle btn-sm"
                     title="Add Head After">
                     <i class="mdi mdi-plus-circle"></i>
                 </button>
             @endif
            </td>
            <td>
                <textarea name="description[]" rows="5" class="form-control description-input" autocomplete="off">{{ $data->description }}</textarea>
                <br>

                <div class="form-control dropdown-list" style="height:auto;">

                </div>
            </td>
            <td>
                <select class="form-control unit-input" name="unit[]">
                    <option value="">Select Unit</option>
                    @foreach ($alluoms as $uom)
                    <option value="{{ $uom->unit }}"
                        {{ $data->unit == $uom->unit ? 'selected' : '' }}>{{ $uom->unit }}
                    </option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="qty[]" value="{{ $data->qty }}"
                    class="form-control qty-input" min="1" style="width: 70px;" /></td>
            <td><input type="text" name="labour[]" value="{{ $data->labour }}"
                    class="form-control rate-input humara-class a" style="width: 70px;" /></td>
            <td><input type="text" name="material[]" value="{{ $data->material }}"
                    class="form-control rate-input humara-class b" style="width: 70px;" /></td>
            <td><input type="text" name="misc[]" value="{{ $data->misc }}"
                    class="form-control rate-input humara-class c" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_percent[]" value="{{ $data->wastage_percent }}"
                    class="form-control rate-input d" style="width: 70px;" /></td>
            <td><input type="text" name="wastage_amount[]" value="{{ $data->wastage_amount }}"
                    class="form-control rate-input humara-class e" style="width: 70px;" /></td>
            <td><input type="text" name="sc[]" value="{{ $data->sc }}"
                    class="form-control rate-input humara-class f" style="width: 70px;" /></td>
            <td><input type="text" name="net_rate[]" value="{{ $data->net_rate }}"
                    class="form-control rate-input humara-class g" style="width: 70px;" /></td>
            <td><input type="text" name="contractor_percent[]"
                    value="{{ $data->contractor_percent }}" class="form-control rate-input h"
                    style="width: 70px;" /></td>
            <td><input type="text" name="contractor_amount[]"
                    value="{{ $data->contractor_amount }}"
                    class="form-control rate-input humara-class i" style="width: 70px;" /></td>
            <td><input type="text" name="rate[]" value="{{ $data->rate }}"
                    class="form-control rate-input humara-class j" style="width: 70px;" /></td>
            <td><input type="text" name="total[]" value="{{ $data->total }}"
                    class="form-control total-input k" style="width: 70px;" readonly /></td>
            <input type="hidden" name="quotation_no[]" value="{{ $page['crumbs'][2] }}" />
            <input type="hidden" name="template_id[]" value="8" />
            <input type="hidden" name="id[]" value="{{ $data->id }}" />
            <input type="hidden" name="type[]" value="row">
        </tr>
        @endif
        @endif
        @endforeach
        @endif

    </tbody>
    <tfoot>
        <tr id="subtotal-row" style="border-bottom: 1px solid black;border-top: 2px solid black;">
            <td></td>
            <td style="text-align:left;">Sub-total</td>
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
            <td></td>
            {{-- <td></td> --}}
            <td id="total-subtotal"></td>
            <td id="amount-subtotal">${{ number_format($others['total'], 2) }}</td>
            <script>
                $(document).ready(function() {
                    $('.others-gt').text('<?php echo number_format($others['total'], 2); ?>');
                });
            </script>
        </tr>
        <tr style="border-bottom: 2px solid black;border-top: 1px solid black;">
            <td></td>
            <td style="text-align:left;"><b>TOTAL</b></td>
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
            <td></td>
            <td></td>
            {{-- <td></td> --}}
            <td>${{ number_format($others['total'], 2) }}</td>
        </tr>
    </tfoot>
</table>
</div>

@if (config('visibility.bill_mode') == 'editing')
<button type="button" id="others_new_blank_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
    <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
    <span>{{ cleanLang(__('lang.new_blank_line')) }}</span>
</button>
<button type="button" id="others_new_head_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
    <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
    <span>Heading</span>
</button>
@endif
<script>
    /*Humara Code*/
    $(document).ready(function() {
        // Generate UOM options for reuse
        let uomOptions = '';
        @foreach ($alluoms as $uom)
            uomOptions += `<option value="{{ $uom->unit }}">{{ $uom->unit }}</option>`;
        @endforeach

        // Add new blank line (appends to end)
        $('#others_new_blank_line').on('click', function() {
            $('#others-table-body').append(getRowTemplate('row', uomOptions));
        });

        // Add new head line (appends to end)
        $('#others_new_head_line').on('click', function() {
            $('#others-table-body').append(getRowTemplate('head', uomOptions));
        });

        // Add row after specific row/head
        $('#others-table-body').on('click', '.add-row-after', function() {
            $(this).closest('tr').after(getRowTemplate('row', uomOptions));
        });

        // Add head after specific row/head
        $('#others-table-body').on('click', '.add-head-after', function() {
            $(this).closest('tr').after(getRowTemplate('head', uomOptions));
        });

        // Delete row
        $('#others-table-body').on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
        });

        // Function to generate row/head template
        function getRowTemplate(type, uomOptions) {
            const isHead = type === 'head';
            const bgColor = isHead ? 'background-color:#E2EFD9' : '';
            const inputType = isHead ? 'hidden' : 'text';
            return `
            <tr class="fw-bold" style="${bgColor}">
                <td>
                    <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                        <i class="sl-icon-trash"></i>
                    </button>
                    <button type="button" class="add-row-after btn btn-outline-success btn-circle btn-sm" title="Add Row After">
                        <i class="mdi mdi-plus-circle-outline"></i>
                    </button>
                    <button type="button" class="add-head-after btn btn-outline-primary btn-circle btn-sm" title="Add Head After">
                        <i class="mdi mdi-plus-circle"></i>
                    </button>
                </td>
                <td>
                    <textarea rows="5" name="description[]" style="width: 100%;" class="form-control description-input"></textarea>
                    <input type="hidden" name="type[]" value="${type}">
                    ${!isHead ? '<br><div class="form-control dropdown-list" style="height:auto;"></div>' : ''}
                </td>
                <td>
                    <select class="form-control unit-input" name="unit[]">
                        <option value="">Select Unit</option>
                        ${uomOptions}
                    </select>
                </td>
                <td><input type="${inputType}" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="labour[]" value="" class="a form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="material[]" value="" class="b form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="misc[]" value="" class="c form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="wastage_percent[]" value="" class="d form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="wastage_amount[]" value="" class="e form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="sc[]" value="" class="f form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="net_rate[]" value="" class="g form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="contractor_percent[]" value="" class="h form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="contractor_amount[]" value="" class="i form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="rate[]" value="" class="j form-control rate-input humara-class" style="width: 70px;" /></td>
                <td><input type="${inputType}" name="total[]" value="" class="k form-control total-input" style="width: 70px;" readonly /></td>
                <input type="hidden" name="quotation_no[]" class="form-control" value="{{ $page['crumbs'][2] }}">
                <input type="hidden" name="template_id[]" class="form-control" value="8">
                <input type="hidden" name="id[]" value="">
            </tr>
        `;
        }
    });
</script>
