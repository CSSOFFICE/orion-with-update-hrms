<table class="table table-bordered border-secondary">
    <thead style="text-align:center;">
        <tr style="border: 1px solid black">
            <th rowspan="3" style="border: 1px solid black">Item</th>
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
            <th class="text-start" style="text-align:left!important;"><b>BILL NO. 4 - PROPOSED PLUMBING & SANITARY WORKS</b>
            </th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start"></th>
            <th class="text-start" style="text-align:left!important;">$<span id="grand-total"
                    class="plumbing-gt"></span></th>
        </tr>
    </thead>
    {{-- <tbody id="plumbing-table-body">
        <tr class="fw-bold">
            @if (config('visibility.bill_mode') == 'viewing')
            <td></td>
            <td>Design, supply and install complete the following electrical works inclusive of all electrical
                wiring conduits, connections, etc. as indicated on the Architect’s drawings:-</td>
            <td>mth</td>
            <td>1</td>
            @elseif (config('visibility.bill_mode') == 'editing')
            <td></td>
            <td><input type="text" value="Design, supply and install complete the following electrical works inclusive of all electrical wiring conduits, connections, etc. as indicated on the Architect’s drawings:-" style="width: 100%;" min="1" class="form-control qty-input" /></td>
            <td>mth</td>
            <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
            @endif
        </tr>
        <tr class="fw-bold">
            @if (config('visibility.bill_mode') == 'viewing')
            <td></td>
            <td>Complete supply, installation. testing and commissioning to the satisfaction and requirements of the
                Local Authorities, Architect and Engineer for the water supply system:-</td>
            <td>mth</td>
            <td>1</td>
            @elseif (config('visibility.bill_mode') == 'editing')
            <td></td>
            <td><input type="text" value="Complete supply, installation. testing and commissioning to the satisfaction and requirements of the
                Local Authorities, Architect and Engineer for the water supply system:-" style="width: 100%;" min="1" class="form-control qty-input" /></td>
            <td>mth</td>
            <td><input type="text" value="1" class="form-control rate-input" style="width: 70px;" /></td>
            @endif
        </tr>
        <tr id="plumbing-add-row" style="border-top: 2px solid black;">
            <td></td>
            <td>Sub-total</td>
            <td></td>
            <td>$589</td>
        </tr>
        <tr>
            <td></td>
            <td>Add: Contractor's Profit</td>
            <td></td>
            <td>$100</td>
        </tr>
        <tr style="border-top: 3px solid black; font-weight: bold;">
            <td></td>
            <td>Total</td>
            <td></td>
            <td>$689</td>
        </tr>

    </tbody> --}}
    <tbody id="plumbing-table-body">
        @if (config('visibility.bill_mode') == 'viewing')
            <?php
            $plumbingTotal = DB::table('quotation_templates')
                ->where('template_id', 5)
                ->where('quotation_no', $bill->est_quotation_no)
                ->sum('total');

            $plumbingAmount = DB::table('quotation_templates')
                ->where('template_id', 5)
                ->where('quotation_no', $bill->est_quotation_no)
                ->sum('amount');

            $plumbing = [
                'total' => $plumbingTotal,
                'amount' => $plumbingAmount,
            ];
            ?>

            @php
                $letterCounter = 'A'; // Start for head as 'B'
                $rowCounter = 0; // Start row counter from 1
            @endphp
            @foreach ($quotation_templates as $index => $data)
                @if ($data->template_id == 5 && $data->quotation_no == $page['crumbs'][2])
                    @if ($data->type == 'head')
                        @php
                            $rowCounter = 0; // Reset row counter when a new head is encountered
                            $subtotal = 0; // Initialize subtotal for the current head
                            $amountSubtotal = 0; // Initialize amount subtotal for the current head
                        @endphp
                        <tr class="fw-bold" style="background-color:#84eab3">
                            <td>{{ $letterCounter }}</td> <!-- Alphabet for head -->
                            <td class="wrap-text" colspan="9"><b><u>{{ $data->description }}</u></b></td>
                            <td>{{ $data->total }}</td>
                            <td>{{ $data->amount }}</td>

                        </tr>
                        @php
                            $letterCounter++; // Increment alphabet for next head
                        @endphp
                    @elseif($data->type == 'row')
                        @php
                            $rowCounter++; // Increment numeric counter for row
                            $subtotal += $data->total; // Add row total to subtotal
                            $amountSubtotal += $data->amount; // Add row amount to amountSubtotal
                        @endphp
                        <tr class="fw-bold">
                            <td>{{ $rowCounter }}</td> <!-- Numeric for row -->
                            <td class="wrap-text">{{ $data->description }}</td>
                            <td>{{ $data->unit }}</td>
                            <td>{{ $data->qty }}</td>
                            <td>{{ $data->labour }}</td>
                            <td>{{ $data->material }}</td>
                            <td>{{ $data->misc }}</td>
                            <td>{{ $data->wastage_percent }}</td>
                            <td>{{ $data->wastage_amount }}</td>
                            <td>{{ $data->sc }}</td>
                            <td>{{ number_format((float) $data->total, 2) }}</td>
                            <td>{{ number_format((float) $data->amount, 2) }}</td>

                        </tr>
                    @endif

                    <!-- At the end of each head's rows, show the subtotal -->
                    {{-- @if ($data->type == 'head' || ($loop->last && $rowCounter > 0))
          <tr class="fw-bold">
              <td colspan="9" class="text-right">Subtotal:</td>
              <td>{{ $subtotal }}</td>
              <td>{{ $amountSubtotal }}</td>
          </tr>
      @endif --}}
                @endif
            @endforeach
        @elseif (config('visibility.bill_mode') == 'editing')
            <?php
            $plumbingTotal = DB::table('quotation_templates')
                ->where('template_id', 5)
                ->where('quotation_no', $bill->est_quotation_no)
                ->sum('total');

            $plumbingAmount = DB::table('quotation_templates')
                ->where('template_id', 5)
                ->where('quotation_no', $bill->est_quotation_no)
                ->sum('amount');

            $plumbing = [
                'total' => $plumbingTotal,
                'amount' => $plumbingAmount,
            ];
            ?>
            @foreach ($quotation_templates as $index => $data)
                @if ($data->template_id == 5 && $data->quotation_no == $page['crumbs'][2])
                    @if ($data->type == 'head')
                        <tr class="fw-bold" style="background-color:#84eab3">
                            <td>
                                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                                    data-id="{{ $data->id }}"
                                    data-url="{{ route('deletedata', ['id' => $data->id]) }}">
                                    <i class="sl-icon-trash"></i>
                                </button>
                            </td>
                            <td colspan="9">
                                <textarea rows="5" name="description[]" class="form-control description-input">{{ $data->description }}</textarea>
                                <input type="hidden" name="type[]" value="head">
                            </td>
                            <input type="hidden" name="unit[]" value="{{ $data->unit }}" class="form-control" />
                            <input type="hidden" name="qty[]" value="{{ $data->qty }}"
                                class="form-control qty-input" min="1" style="width: 70px;" />
                            <input type="hidden" name="labour[]" value="{{ $data->labour }}"
                                class="form-control rate-input humara-class" style="width: 70px;" />
                            <input type="hidden" name="material[]" value="{{ $data->material }}"
                                class="form-control rate-input humara-class" style="width: 70px;" />
                            <input type="hidden" name="misc[]" value="{{ $data->misc }}"
                                class="form-control rate-input humara-class" style="width: 70px;" />
                            <input type="hidden" name="wastage_percent[]" value="{{ $data->wastage_percent }}"
                                class="form-control rate-input" style="width: 70px;" />
                            <input type="hidden" name="wastage_amount[]" value="{{ $data->wastage_amount }}"
                                class="form-control rate-input humara-class" style="width: 70px;" />
                            <input type="hidden" name="sc[]" value="{{ $data->sc }}"
                                class="form-control rate-input humara-class" style="width: 70px;" />
                            <td><input type="text" name="total[]" value="{{ $data->total }}"
                                    class="form-control total-input" style="width: 70px;" readonly /></td>
                            <td><input type="text" name="amount[]" value="{{ $data->amount }}"
                                    class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
                            <input type="hidden" name="quotation_no[]" value="{{ $page['crumbs'][2] }}" />
                            <input type="hidden" name="template_id[]" value="5" />
                            <input type="hidden" name="id[]" value="{{ $data->id }}" />
                        </tr>
                    @elseif($data->type == 'row')
                        <tr class="fw-bold">
                            <td>
                                <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                                    data-id="{{ $data->id }}"
                                    data-url="{{ route('deletedata', ['id' => $data->id]) }}">
                                    <i class="sl-icon-trash"></i>
                                </button>
                            </td>
                            <td>
                                <textarea rows="5" name="description[]" class="form-control description-input">{{ $data->description }}</textarea>
                                <input type="hidden" name="type[]" value="row">

                            </td>
                            <td>
                                <input type="text" name="unit[]" value="{{ $data->unit }}"
                                    class="form-control" />
                            </td>
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
                            <input type="hidden" name="template_id[]" value="5" />
                            <input type="hidden" name="id[]" value="{{ $data->id }}" />
                        </tr>
                    @endif
                @endif
            @endforeach
        @endif

    </tbody>
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
        <td id="total-subtotal"></td>
        <td id="amount-subtotal">{{ number_format((float) $plumbing['amount'], 2) }}</td>
        <script>
            $(document).ready(function() {
                $('.plumbing-gt').text('<?php echo number_format((float) $plumbing['amount'], 2); ?>');
            });
        </script>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:left;">Add: Contractor's Profit</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ number_format((float) 0.1 * $plumbing['amount'], 2) }}</td>
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
        <td>{{ number_format((float) 0.1 * $plumbing['amount'] + $plumbing['amount'], 2) }}</td>
    </tr>
</table>

@if (config('visibility.bill_mode') == 'editing')
    <button type="button" id="plumbing_new_blank_line"
        class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
        <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
        <span>{{ cleanLang(__('lang.new_blank_line')) }}</span>
    </button>
    <button type="button" id="plumbing_new_head_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
        <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
        <span>Heading</span>
    </button>
@endif
<script>
    /*Humara Code*/
    $(document).ready(function() {
        $('#plumbing_new_blank_line').on('click', function() {
            $('#plumbing-table-body').append(`
                    <tr>
                        <td>
                            <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                                <i class="sl-icon-trash"></i>
                            </button>
                        </td>
                        <td><textarea name="description[]" rows="5" style="width: 100%;" class="form-control description-input" ></textarea>
                        <div id="list" class="dropdown-list"></div>
                            <input type="hidden" name="type[]" value="row">
                            </td>
                        <td>
                               <input type="text" name="unit[]" class="form-control unit-input"  />
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
                        <input type="hidden" name="template_id[]" class="form-control" value="5">
                        <input type="hidden" name="id[]" value="">
                    </tr>
                `);
        });
        $('#plumbing_new_head_line').on('click', function() {
            $('#plumbing-table-body').append(
                `<tr style="background-color:#84eab3">
                        <td>
                            <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                                <i class="sl-icon-trash"></i>
                            </button>
                        </td>
                        <td colspan="9">
                            <textarea name="description[]" rows="5" style="width: 100%;" class="form-control description-input" ></textarea>
                            <input type="hidden" name="type[]" value="head">
                        </td>
                       <input type="hidden" name="unit[]" class="form-control"  />
                       <input type="hidden" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;"/>
                       <input type="hidden" name="labour[]" value="" class="form-control rate-input humara-class" style="width: 70px;" />
                       <input type="hidden" name="material[]" value="" class="form-control rate-input humara-class" style="width: 70px;" />
                       <input type="hidden" name="misc[]" value="" class="form-control rate-input humara-class" style="width: 70px;" />
                       <input type="hidden" name="wastage_percent[]" value="" class="form-control rate-input" style="width: 70px;" />
                       <input type="hidden" name="wastage_amount[]" value="" class="form-control rate-input humara-class" style="width: 70px;" />
                       <input type="hidden" name="sc[]" value="" class="form-control rate-input humara-class" style="width: 70px;" />
                       <td><input type="text" name="total[]" value="" class="form-control total-input" style="width: 70px;" readonly /></td>
                       <td><input type="text" name="amount[]" value="" class="form-control quotation-amount-input" style="width: 70px;" readonly /></td>
                        <input type="hidden" name="quotation_no[]" class="form-control" value="{{ $page['crumbs'][2] }}">
                        <input type="hidden" name="template_id[]" class="form-control" value="5">
                        <input type="hidden" name="id[]" value="">
                    </tr>

                    `);

        });
        $('#plumbing-table-body').on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
        });

    });
</script>
