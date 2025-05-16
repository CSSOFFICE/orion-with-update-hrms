<table class="table table-bordered border-secondary">
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
    <tbody id="external-table-body">
        <tr style="background-color:#FEF2CB">
            <th class="text-start"></th>
            <th class="text-start" style="text-align:left!important;"><b>BILL NO. 6 - EXTERNAL WORKS</b></th>
            <th class="text-start" colspan="12"></th>
            <th class="text-start" style="text-align:left!important;">$<span id="grand-total"class="external-gt"></span>
            </th>
            </th>

        </tr>
        @if (config('visibility.bill_mode') == 'viewing')
            <?php
            $externalTotal = DB::table('quotation_templates')->where('template_id', 6)->where('quotation_no', $bill->est_quotation_no)->sum('total');
            
            $externalAmount = DB::table('quotation_templates')->where('template_id', 6)->where('quotation_no', $bill->est_quotation_no)->sum('amount');
            
            $external = [
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
                @if ($data->template_id == 6 && $data->quotation_no == $page['crumbs'][2])
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
            $externalTotal = DB::table('quotation_templates')->where('template_id', 6)->where('quotation_no', $bill->est_quotation_no)->sum('total');
            
            $externalAmount = DB::table('quotation_templates')->where('template_id', 6)->where('quotation_no', $bill->est_quotation_no)->sum('amount');
            
            $external = [
                'total' => $externalTotal,
                'amount' => $externalAmount,
            ];
            ?>
            @foreach ($quotation_templates as $index => $data)
                @if ($data->template_id == 6 && $data->quotation_no == $page['crumbs'][2])
                    @if ($data->type == 'head')
                        <tr class="fw-bold" style="background-color:#E2EFD9">
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
                            <td>
                                <select class="form-control" name="unit[]">
                                    <option value="sum" {{ $data->unit == 'sum' ? 'sexternalted' : '' }}>Sum
                                    </option>
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
                            <input type="hidden" name="template_id[]" value="6" />
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
                            </td>
                            <td>
                                <textarea name="description[]" rows="5" class="form-control description-input" autocomplete="off">{{ $data->description }}</textarea>
                            </td>
                            <td>
                                <select class="form-control" name="unit[]">
                                    <option value="sum" {{ $data->unit == 'sum' ? 'selected' : '' }}>Sum
                                    </option>
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
                            <input type="hidden" name="template_id[]" value="6" />
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
            <td id="amount-subtotal">${{ number_format($external['total'], 2) }}</td>
            <script>
                $(document).ready(function() {
                    $('.external-gt').text('<?php echo number_format($external['total'], 2); ?>');
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
            <td>${{ number_format($external['total'], 2) }}</td>
        </tr>
    </tfoot>
</table>

@if (config('visibility.bill_mode') == 'editing')
    <button type="button" id="external_new_blank_line"
        class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
        <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
        <span>{{ cleanLang(__('lang.new_blank_line')) }}</span>
    </button>
    <button type="button" id="external_new_head_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
        <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
        <span>Heading</span>
    </button>
@endif
<script>
    $(document).ready(function() {
        // Append new row when button is clicked
        $('#external_new_blank_line').on('click', function() {
            $('#external-table-body').append(`
                    <tr>
                        <td>
                            <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                                <i class="sl-icon-trash"></i>
                            </button>
                        </td>
                        <td><textarea rows="5" name="description[]" style="width: 100%;" class="form-control description-input"></textarea>
                            <input type="hidden" name="type[]" value="row">
                        </td>
                        <td>
                            <select class="form-control unit-input" name="unit[]">
                                <option value="sum">Sum</option>
                                
                            </select>                                       
                        </td>
                        <td><input type="text" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;" /></td>
                        <td><input type="text" name="labour[]" value="" class="a form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="material[]" value="" class="b form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="misc[]" value="" class="c form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="wastage_percent[]" value="" class="d form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="wastage_amount[]" value="" class="e form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="sc[]" value="" class="f form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="net_rate[]" value="" class="g form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="contractor_percent[]" value="" class="h form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="contractor_amount[]" value="" class="i form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="rate[]" value="" class="j form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="text" name="total[]" value="" class="k form-control total-input" style="width: 70px;" readonly /></td>                    
                        <input type="hidden" name="quotation_no[]" class="form-control" value="{{ $page['crumbs'][2] }}">
                        <input type="hidden" name="template_id[]" class="form-control" value="6">
                        <input type="hidden" name="id[]" value="">                    
                    </tr>
        `);
        });
        $('#external_new_head_line').on('click', function() {
            $('#external-table-body').append(`
                    <tr class="fw-bold" style="background-color:#E2EFD9">
                        <td>
                            <button type="button" class="delete-row-btn btn btn-outline-danger btn-circle btn-sm">
                                <i class="sl-icon-trash"></i>
                            </button>
                        </td>
                        <td><textarea rows="5" name="description[]" style="width: 100%;" class="form-control description-input"></textarea>
                            <input type="hidden" name="type[]" value="head">
                        </td>
                        <td>
                            <select class="form-control unit-input" name="unit[]">
                                <option value="sum">Sum</option>                                
                            </select>                                       
                        </td>
                        <td><input type="hidden" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;" /></td>
                        <td><input type="hidden" name="labour[]" value="" class="a form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="material[]" value="" class="b form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="misc[]" value="" class="c form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="wastage_percent[]" value="" class="d form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="wastage_amount[]" value="" class="e form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="sc[]" value="" class="f form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="net_rate[]" value="" class="g form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="contractor_percent[]" value="" class="h form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="contractor_amount[]" value="" class="i form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="rate[]" value="" class="j form-control rate-input humara-class" style="width: 70px;" /></td>
                        <td><input type="hidden" name="total[]" value="" class="k form-control total-input" style="width: 70px;" readonly /></td>                    
                        <input type="hidden" name="quotation_no[]" class="form-control" value="{{ $page['crumbs'][2] }}">
                        <input type="hidden" name="template_id[]" class="form-control" value="6">
                        <input type="hidden" name="id[]" value="">                    
                    </tr>

                    `);

        });

        // Event delegation for dynamically added delete buttons
        $('#external-table-body').on('click', '.delete-row-btn', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
