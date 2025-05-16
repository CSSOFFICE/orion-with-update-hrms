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
        <tbody id="elec-table-body">
            <tr style="background-color:#FEF2CB">
                <th class="text-start"></th>
                <th class="text-start" style="text-align:left!important;"><b>BILL NO. 5 - PROPOSED ELECTRICAL & ACMV
                        INSTALLATION</b></th>
                <th class="text-start" colspan="12"></th>
                <th class="text-start" style="text-align:left!important;">$<span id="grand-total"
                        class="elec-gt"></span>
                </th>
            </tr>
            <?php if(config('visibility.bill_mode') == 'viewing'): ?>
                <?php
                $elecTotal = DB::table('quotation_templates')->where('template_id', 5)->where('quotation_no', $bill->est_quotation_no)->sum('total');
                
                $elecAmount = DB::table('quotation_templates')->where('template_id', 5)->where('quotation_no', $bill->est_quotation_no)->sum('amount');
                
                $elec = [
                    'total' => $elecTotal,
                    'amount' => $elecAmount,
                ];
                ?>
                <?php
                    $parentCounter = 1; // Fixed parent value
                    $headCounter = 1; // Will increment only for 'head'
                    $subtotal = 0;
                    $amountSubtotal = 0;
                ?>
                <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($data->template_id == 5 && $data->quotation_no == $page['crumbs'][2]): ?>
                        <?php if($data->type == 'head'): ?>
                            <?php
                                $serial = $parentCounter . '.' . $headCounter;
                                $headCounter++; // Increment only for heads
                            ?>
                            <tr class="fw-bold" style="background-color:#E2EFD9">
                                <td style="text-align:center;"><?php echo e($serial); ?></td> <!-- Numbering only for head -->
                                <td class="wrap-text" width="150px" style="word-wrap: break-word;">
                                    <b><?php echo clean($data->description); ?></b>
                                </td>
                                <td><b><?php echo e($data->unit); ?></b></td>
                                <td colspan="12"><b><?php echo e($data->qty); ?></b></td>
                            </tr>
                        <?php elseif($data->type == 'row'): ?>
                             <?php
                                $subtotal += $data->total;
                                $amountSubtotal += $data->amount;
                            ?>
                            <tr class="fw-bold">
                                <td style="color:rgb(39, 97, 255);text-align:center;">-</td> <!-- Show -- for rows -->
                                <td class="wrap-text"><?php echo e($data->description); ?></td>
                                <td><?php echo e($data->unit); ?></td>
                                <td><?php echo e($data->qty); ?></td>
                                <td><?php echo e(number_format($data->labour, 2)); ?></td>
                                <td><?php echo e(number_format($data->material, 2)); ?></td>
                                <td><?php echo e(number_format($data->misc, 2)); ?></td>
                                <td><?php echo e(number_format($data->wastage_percent, 2)); ?></td>
                                <td><?php echo e(number_format($data->wastage_amount, 2)); ?></td>
                                <td><?php echo e(number_format($data->sc, 2)); ?></td>
                                <td><?php echo e(number_format($data->net_rate, 2)); ?></td>
                                <td><?php echo e(number_format($data->contractor_percent, 2)); ?></td>
                                <td><?php echo e(number_format($data->contractor_amount, 2)); ?></td>
                                <td><?php echo e(number_format($data->rate, 2)); ?></td>
                                <td><?php echo e(number_format($data->total, 2)); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php elseif(config('visibility.bill_mode') == 'editing'): ?>
                <?php
                $elecTotal = DB::table('quotation_templates')->where('template_id', 5)->where('quotation_no', $bill->est_quotation_no)->sum('total');
                
                $elecAmount = DB::table('quotation_templates')->where('template_id', 5)->where('quotation_no', $bill->est_quotation_no)->sum('amount');
                
                $elec = [
                    'total' => $elecTotal,
                    'amount' => $elecAmount,
                ];
                ?>
                <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($data->template_id == 5 && $data->quotation_no == $page['crumbs'][2]): ?>
                        <?php if($data->type == 'head'): ?>
                            <tr class="fw-bold" style="background-color:#E2EFD9">
                                <td>
                                    <button type="button"
                                        class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                                        data-id="<?php echo e($data->id); ?>"
                                        data-url="<?php echo e(route('deletedata', ['id' => $data->id])); ?>">
                                        <i class="sl-icon-trash"></i>
                                    </button>
                                    <!-- Add Row and Head Buttons -->
                                    <?php if(config('visibility.bill_mode') == 'editing'): ?>
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
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <textarea name="description[]" rows="5" class="form-control description-input" autocomplete="off"><?php echo e($data->description); ?></textarea>
                                </td>
                                <td>
                                    <select class="form-control unit-input" name="unit[]">
                                        <option value="">Select Unit</option>
                                        <?php $__currentLoopData = $alluoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($uom->unit); ?>"
                                                <?php echo e($data->unit == $uom->unit ? 'selected' : ''); ?>><?php echo e($uom->unit); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td><input type="text" name="qty[]" value="<?php echo e($data->qty); ?>"
                                        class="form-control qty-input" min="1" style="width: 70px;" /></td>
                                <td><input type="hidden" name="labour[]" value="<?php echo e($data->labour); ?>"
                                        class="form-control rate-input humara-class a" style="width: 70px;" /></td>
                                <td><input type="hidden" name="material[]" value="<?php echo e($data->material); ?>"
                                        class="form-control rate-input humara-class b" style="width: 70px;" /></td>
                                <td><input type="hidden" name="misc[]" value="<?php echo e($data->misc); ?>"
                                        class="form-control rate-input humara-class c" style="width: 70px;" /></td>
                                <td><input type="hidden" name="wastage_percent[]"
                                        value="<?php echo e($data->wastage_percent); ?>" class="form-control rate-input d"
                                        style="width: 70px;" /></td>
                                <td><input type="hidden" name="wastage_amount[]"
                                        value="<?php echo e($data->wastage_amount); ?>"
                                        class="form-control rate-input humara-class e" style="width: 70px;" /></td>
                                <td><input type="hidden" name="sc[]" value="<?php echo e($data->sc); ?>"
                                        class="form-control rate-input humara-class f" style="width: 70px;" /></td>
                                <td><input type="hidden" name="net_rate[]" value="<?php echo e($data->net_rate); ?>"
                                        class="form-control rate-input humara-class g" style="width: 70px;" /></td>
                                <td><input type="hidden" name="contractor_percent[]"
                                        value="<?php echo e($data->contractor_percent); ?>" class="form-control rate-input h"
                                        style="width: 70px;" /></td>
                                <td><input type="hidden" name="contractor_amount[]"
                                        value="<?php echo e($data->contractor_amount); ?>"
                                        class="form-control rate-input humara-class i" style="width: 70px;" /></td>
                                <td><input type="hidden" name="rate[]" value="<?php echo e($data->rate); ?>"
                                        class="form-control rate-input humara-class j" style="width: 70px;" /></td>
                                <td><input type="hidden" name="total[]" value="<?php echo e($data->total); ?>"
                                        class="form-control total-input k" style="width: 70px;" readonly /></td>
                                <input type="hidden" name="quotation_no[]" value="<?php echo e($page['crumbs'][2]); ?>" />
                                <input type="hidden" name="template_id[]" value="5" />
                                <input type="hidden" name="id[]" value="<?php echo e($data->id); ?>" />
                                <input type="hidden" name="type[]" value="head">
                            </tr>
                        <?php elseif($data->type == 'row'): ?>
                            <tr class="fw-bold">
                                <td>
                                    <button type="button"
                                        class="delete-row-btn btn btn-outline-danger btn-circle btn-sm"
                                        data-id="<?php echo e($data->id); ?>"
                                        data-url="<?php echo e(route('deletedata', ['id' => $data->id])); ?>">
                                        <i class="sl-icon-trash"></i>
                                    </button>

                                    <!-- Add Row and Head Buttons -->
                                    <?php if(config('visibility.bill_mode') == 'editing'): ?>
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
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <textarea name="description[]" rows="5" class="form-control description-input" autocomplete="off"><?php echo e($data->description); ?></textarea>
                                    <br>

                                    <div class="form-control dropdown-list" style="height:auto;">

                                    </div>
                                </td>
                                <td>
                                    <select class="form-control unit-input" name="unit[]">
                                        <option value="">Select Unit</option>
                                        <?php $__currentLoopData = $alluoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($uom->unit); ?>"
                                                <?php echo e($data->unit == $uom->unit ? 'selected' : ''); ?>><?php echo e($uom->unit); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                </td>
                                <td><input type="text" name="qty[]" value="<?php echo e($data->qty); ?>"
                                        class="form-control qty-input" min="1" style="width: 70px;" /></td>
                                <td><input type="text" name="labour[]" value="<?php echo e($data->labour); ?>"
                                        class="form-control rate-input humara-class a" style="width: 70px;" /></td>
                                <td><input type="text" name="material[]" value="<?php echo e($data->material); ?>"
                                        class="form-control rate-input humara-class b" style="width: 70px;" /></td>
                                <td><input type="text" name="misc[]" value="<?php echo e($data->misc); ?>"
                                        class="form-control rate-input humara-class c" style="width: 70px;" /></td>
                                <td><input type="text" name="wastage_percent[]"
                                        value="<?php echo e($data->wastage_percent); ?>" class="form-control rate-input d"
                                        style="width: 70px;" /></td>
                                <td><input type="text" name="wastage_amount[]"
                                        value="<?php echo e($data->wastage_amount); ?>"
                                        class="form-control rate-input humara-class e" style="width: 70px;" /></td>
                                <td><input type="text" name="sc[]" value="<?php echo e($data->sc); ?>"
                                        class="form-control rate-input humara-class f" style="width: 70px;" /></td>
                                <td><input type="text" name="net_rate[]" value="<?php echo e($data->net_rate); ?>"
                                        class="form-control rate-input humara-class g" style="width: 70px;" /></td>
                                <td><input type="text" name="contractor_percent[]"
                                        value="<?php echo e($data->contractor_percent); ?>" class="form-control rate-input h"
                                        style="width: 70px;" /></td>
                                <td><input type="text" name="contractor_amount[]"
                                        value="<?php echo e($data->contractor_amount); ?>"
                                        class="form-control rate-input humara-class i" style="width: 70px;" /></td>
                                <td><input type="text" name="rate[]" value="<?php echo e($data->rate); ?>"
                                        class="form-control rate-input humara-class j" style="width: 70px;" /></td>
                                <td><input type="text" name="total[]" value="<?php echo e($data->total); ?>"
                                        class="form-control total-input k" style="width: 70px;" readonly /></td>
                                <input type="hidden" name="quotation_no[]" value="<?php echo e($page['crumbs'][2]); ?>" />
                                <input type="hidden" name="template_id[]" value="5" />
                                <input type="hidden" name="id[]" value="<?php echo e($data->id); ?>" />
                                <input type="hidden" name="type[]" value="row">
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

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
                
                <td id="total-subtotal"></td>
                <td id="amount-subtotal">$<?php echo e(number_format($elec['total'], 2)); ?></td>
                <script>
                    $(document).ready(function() {
                        $('.elec-gt').text('<?php echo number_format($elec['total'], 2); ?>');
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
                
                <td>$<?php echo e(number_format($elec['total'], 2)); ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php if(config('visibility.bill_mode') == 'editing'): ?>
    <button type="button" id="elec_new_blank_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
        <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
        <span><?php echo e(cleanLang(__('lang.new_blank_line'))); ?></span>
    </button>
    <button type="button" id="elec_new_head_line" class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon">
        <i class="mdi mdi-plus-circle-outline text-themecontrast"></i>
        <span>Heading</span>
    </button>
<?php endif; ?>

<script>
    /*Humara Code*/
    $(document).ready(function() {
        // Generate UOM options for reuse
        let uomOptions = '';
        <?php $__currentLoopData = $alluoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            uomOptions += `<option value="<?php echo e($uom->unit); ?>"><?php echo e($uom->unit); ?></option>`;
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        // Add new blank line (appends to end)
        $('#elec_new_blank_line').on('click', function() {
            $('#elec-table-body').append(getRowTemplate('row', uomOptions));
        });

        // Add new head line (appends to end)
        $('#elec_new_head_line').on('click', function() {
            $('#elec-table-body').append(getRowTemplate('head', uomOptions));
        });

        // Add row after specific row/head
        $('#elec-table-body').on('click', '.add-row-after', function() {
            $(this).closest('tr').after(getRowTemplate('row', uomOptions));
        });

        // Add head after specific row/head
        $('#elec-table-body').on('click', '.add-head-after', function() {
            $(this).closest('tr').after(getRowTemplate('head', uomOptions));
        });

        // Delete row
        $('#elec-table-body').on('click', '.delete-row-btn', function() {
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
                <td><input type="text" name="qty[]" value="" class="form-control qty-input" min="1" style="width: 70px;" /></td>
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
                <input type="hidden" name="quotation_no[]" class="form-control" value="<?php echo e($page['crumbs'][2]); ?>">
                <input type="hidden" name="template_id[]" class="form-control" value="5">
                <input type="hidden" name="id[]" value="">
            </tr>
        `;
        }

    });
</script>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/bill/components/elements/templates/elec_acme.blade.php ENDPATH**/ ?>