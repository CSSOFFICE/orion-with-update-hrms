<?php
$billing=DB::table('billing_addresses')->where('client_id',$project->project_clientid)->get();
$Shipping=DB::table('shipping_addresses')->where('client_id',$project->project_clientid)->get();
$product=DB::table('product')->get();
$def_gst=DB::table('xin_system_setting')->get();
$all_gst=DB::table('xin_gst')->get();

?>


<div class="row" id="js-trigger-invoices-modal-add-edit" data-payload="<?php echo e($page['section'] ?? ''); ?>">
    <div class="col-lg-12">

        <!--meta data - creatd by-->
        <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
        <div class="modal-meta-data">
            <small><strong><?php echo e(cleanLang(__('lang.created_by'))); ?>:</strong> <?php echo e($invoice->first_name); ?> <?php echo e($invoice->last_name); ?> |
                <?php echo e(runtimeDate($invoice->bill_created)); ?></small>
        </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-6">
                <div class="form-group row">
                    <label>Project Name : <?php echo e($project->project_title??''); ?></label>

                </div>
            </div>
            <div class="col-6">
                <div class="form-group row">
                    <label>Client Name : <?php echo e($project->f_name??$project->client_company_name); ?></label>

                </div>
            </div>
        </div>


        <div class="form-group row">
            <label>Billing Address </label>
            <select class="form-control" name="Billing_Address" id="Billing_Address">
                <option>Choose Address</option>
                <?php foreach ($billing as $k => $billing) { ?>
                    <option value="<?php echo $billing->id; ?>" <?php echo e(($invoice->Billing_Address==$billing->id)?'selected':''); ?>><?php echo e($billing->street); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group row">
            <label>Shipping Address </label>
            <select class="form-control" name="Shipping_Address" id="Shipping_Address">
                <option>Choose Address</option>
                <?php foreach ($Shipping as $k => $Shipping) { ?>
                    <option value="<?php echo $Shipping->id; ?>" <?php echo e(($invoice->Shipping_Address==$Shipping->id)?'selected':''); ?>><?php echo e($Shipping->street); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group row">
            <label>Category </label>
            <select class="form-control" name="category_id_prili" id="category_id_prili">
                <option>Choose Category</option>
                <?php foreach ($templete_category as $k => $purchase) { ?>
                    <option value="<?php echo $purchase->milestonecategory_id; ?>" <?php echo e(($invoice->mile_id==$purchase->milestonecategory_id)?'selected':''); ?>><?php echo $purchase->milestonecategory_title; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group row">
            <label>Task</label>
            <select class="form-control" name="pp" id="Task_id_quotation">
                <?php $__currentLoopData = $grn_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($tas->task_cat_id==$invoice->mile_id): ?>
                <option value="<?php echo $tas->task_id; ?>" <?php echo e(($tas->task_id==$invoice->task)?'selected':''); ?>><?php echo $tas->task_title; ?></option>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>
        </div>
        <!--invoice date-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.invoice_date'))); ?>*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm pickadate" id="invoice_date" name="bill_date_add_edit" autocomplete="off"
                    value="<?php echo e(runtimeDatepickerDate($invoice->invoice_date ?? '')); ?>">
                <input class="mysql-date" type="hidden" name="bill_date" id="bill_date_add_edit"
                    value="<?php echo e($invoice->invoice_date ?? ''); ?>">
            </div>
        </div>


        <input type="hidden" name="client_id" value="<?php echo e($project->project_clientid); ?>">
        <input type="hidden" name="project_id" value="<?php echo e($project->project_id); ?>">
        <input type="hidden" name="total_invoice_amount" value="<?php echo e($total_invoice_amount); ?>">






        <div class="form-group row">
            <label for="payment_terms" class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Payment Terms
            </label>
            <div class="col-sm-12 col-lg-9">
                <select name="payment_terms" class="form-control" placeholder="Terms" id="terms">
                    <option value="">Select Payment Term</option>
                    <?php $__currentLoopData = $payment_terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $terms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>:
                    <option value="<?php echo e($terms->payment_term_id); ?>" <?php echo e(($invoice->terms==$terms->payment_term_id)?'selected':''); ?>><?php echo e($terms->payment_term); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <!--due date-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.due_date'))); ?>*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" id="due_date" class="form-control form-control-sm pickadate" name="bill_due_date_add_edit"
                    autocomplete="off" value="<?php echo e(runtimeDatepickerDate($invoice->invoice_due_date ?? '')); ?>">
                <input class="mysql-date" type="hidden" name="bill_due_date" id="bill_due_date_add_edit"
                    value="<?php echo e($invoice->invoice_due_date ?? ''); ?>">
            </div>
        </div>

        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
            </div>
        </div>
        <div class="col-12">

            <div class="table-responsive my-3 purchaseTable">
                <a href="javascript:void(0)" class="btn-sm btn-success"
                    id="addButton1">Add Blank Line</a>
                <a href="javascript:void(0)" class="btn-sm btn-success"
                    id="add_product">Add Product</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Rate</th>

                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="AddItem" id="vendor_items_table1">

                        <?php $__currentLoopData = $invoice_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($invoice->item_type=="product"): ?>
                        <tr>
                            <td><?php echo e($i+1); ?></td>
                            <td style="min-width:200px">
                                <select name="u_item[<?php echo e($i); ?>]" id="product" class="form-control">
                                    <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productT): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <option value="<?php echo e($productT->product_id); ?>" <?php echo e(($invoice->item==$productT->product_id??'')?'selected':''); ?>><?php echo e($productT->product_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <input type='hidden' class='form-control' name='type[<?php echo e($i); ?>]' id='type_<?php echo e($i); ?>' value='product'>
                            </td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->item_qtn); ?>" name='quantity[<?php echo e($i); ?>]' id='quantity_<?php echo e($i); ?>' oninput='updateRowTotal(this)'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->unit); ?>" name='unit[<?php echo e($i); ?>]' id='unit_<?php echo e($i); ?>'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->rate); ?>" name='rate[<?php echo e($i); ?>]' id='rate_<?php echo e($i); ?>' oninput='updateRowTotal(this)'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->total); ?>" name='total[]' id='total_<?php echo e($i); ?>' readonly></td>
                            <td>
                                <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                            </td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <td><?php echo e($i+1); ?></td>
                            <td style="min-width:200px">
                                <input type='text' class='form-control' id="item_description_<?php echo e($i); ?>" name='item_description[<?php echo e($i); ?>]' value="<?php echo e($invoice->job_description); ?>">

                                <input type='hidden' class='form-control' name='type[<?php echo e($i); ?>]' id='type_<?php echo e($i); ?>' value='plain'>

                            </td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->item_qtn); ?>" name='quantity[<?php echo e($i); ?>]' id='quantity_<?php echo e($i); ?>' oninput='updateRowTotal(this)'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->unit); ?>" name='unit[<?php echo e($i); ?>]' id='unit_<?php echo e($i); ?>'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->rate); ?>" name='rate[<?php echo e($i); ?>]' id='rate_<?php echo e($i); ?>' oninput='updateRowTotal(this)'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' value="<?php echo e($invoice->total); ?>" name='total[]' id='total_<?php echo e($i); ?>' readonly></td>
                            <td>
                                <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                            </td>
                        </tr>

                        <?php endif; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>

                </table>
            </div>
            <div class="row">
                <div class="col-md-8"></div>

                <div class="col-md-4">
                    <label>Sub Total </label>
                    <input type="text" class="form-control" id="sub_t" name="sub_t" readonly>
                    <input type="checkbox" id="inclusive_gst" name="inclusive_gst" <?php echo e(($tags=='on')?'checked':''); ?>>
                    <label for="inclusive_gst">Inclusive GST</label><br>
                    <div id="gst_box">
                        <label>GST</label>
                        <select class="form-control" id="order_gst2" name="order_gst2">
                            <option>Select</option>
                            <?php
                            foreach ($all_gst as $gst) { ?>
                                <option value="<?php echo $gst->gst ?>" <?php echo e(($gst12 == $gst->gst)?'selected':''); ?>><?php echo $gst->gst ?></option>
                            <?php } ?>
                        </select>

                        <label>GST Value</label>
                        <input type="text" class="form-control" id="g_val" name="g_val" readonly>
                    </div>
                    <div id="gst_box1">

                        <label>Inclusive GST Value (<?php echo $def_gst[0]->d_gst ?> %)</label>
                        <input type="text" class="form-control" id="d_gst_i" name="d_gst_i" readonly>
                    </div>

                    <label>Total</label>
                    <input type="text" class="form-control" id="t" name="t" readonly>
                </div>
            </div>
        </div>

    </div>


    <script>
        $('#add_product').on("click", function() {



            var rowCount = $(".AddItem tr").length + 1;
            var rowCount1 = $(".AddItem tr").length;
            // Initialize the select element with an opening <select> tag
            let selectElement = `<select name="u_item[${rowCount1}]" id="u_item_${rowCount}" class="form-control">`;
            selectElement += `<option value="0">Select Product</option>`;

            let product = <?php echo json_encode($product, 15, 512) ?>;


            // Populate the select options dynamically
            $.each(product, function(key, value) {
                selectElement += `<option value="${value.product_id}">${value.product_name}</option>`;
            });
            selectElement += `</select>`; // Closing the <select> tag

            // Append the new row to the table
            let div = (`
                        <tr>
                            <td>${rowCount}</td>
                            <td style="min-width:200px">${selectElement}
                                <input type='hidden' class='form-control' name='type[${rowCount1}]' id='type_${rowCount}' value='product'>
                            </td>
                            <td style="min-width:100px"><input type='text' class='form-control' name='quantity[]' id='quantity_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' name='unit[]' id='unit_${rowCount}'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' name='rate[]' id='rate_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td style="min-width:100px"><input type='text' class='form-control' name='total[]' id='total_${rowCount}' readonly></td>
                            <td>
                                <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                            </td>
                        </tr>
                    `);
            $('.AddItem').append(div)



        });

        function updateRowTotal(element) {
            var $row = $(element).closest('tr');
            var rate = parseFloat($row.find("input[name='rate[]']").val());

            if ($row.find("input[name='timers[]']").val() == 'time') {
                var hrs = parseFloat($row.find("input[name='hours[]']").val());
                var mins = parseFloat($row.find("input[name='mins[]']").val());
                var totalHours = parseFloat(hrs + "." + (mins < 10 ? "0" + mins : mins)); // Combine hours and minutes into float
                var total = totalHours * rate;
                $row.find("input[name='total[]']").val(total.toFixed(2));
            } else {
                var quantity = parseFloat($row.find("input[name='quantity[]']").val());
                var total = quantity * rate;
                $row.find("input[name='total[]']").val(total.toFixed(2));
            }

            calculateSubTotal();


        }
        $(document).ready(function() {
            $("#inclusive_gst").change(function() {
                toggleGSTInput();
                calculateTotal();
            });

            $("#order_gst2, #discount2").change(function() {
                calculateTotal();
            });
            calculateSubTotal()
            toggleGSTInput();
        });

        function toggleGSTInput() {
            var isInclusive = $("#inclusive_gst").is(":checked");
            if (isInclusive) {
                $("#gst_box").hide();
                $("#gst_box1").show();
            } else {
                $("#gst_box").show();
                $("#gst_box1").hide();

            }
        }

        function calculateSubTotal() {
            var subTotal = 0;
            $("input[name='total[]']").each(function() {
                subTotal += parseFloat($(this).val()) || 0;
            });
            $("#sub_t").val(subTotal.toFixed(2));
            console.log(subTotal);

            calculateTotal();
        }

        function calculateTotal() {
            var subTotal = parseFloat($("#sub_t").val()) || 0;
            var discount = parseFloat($("#discount2").val()) || 0;
            var gst = parseFloat($("#order_gst2").val()) || 0;
            var gst1 = parseFloat("<?php echo $def_gst[0]->d_gst ?>");
            var isInclusive = $("#inclusive_gst").is(":checked");

            var discountedSubTotal = subTotal - discount;
            let contractSum = parseFloat($("#contract_sum").val()) || 0;

            // Clear existing alert
            $("#sum_over").empty();

            if (isInclusive) {
                var gstValue = discountedSubTotal * (gst1 / 100);

                $("#d_gst_i").val(gstValue.toFixed(2));
                $("#t").val(discountedSubTotal.toFixed(2));
                $("#grand_total").val(discountedSubTotal.toFixed(2));

                // Check if the total exceeds the contract sum
                if (discountedSubTotal > contractSum) {
                    let alertHtml = `<div class="alert alert-warning" role="alert">Project Total Exceded</div>`;
                    $("#sum_over").append(alertHtml);
                }
            } else {
                var gstValue = discountedSubTotal * (gst / 100);
                var total = discountedSubTotal + gstValue;

                $("#g_val").val(gstValue.toFixed(2));
                $("#t").val(total.toFixed(2));
                $("#grand_total").val(total.toFixed(2));

                // Check if the total exceeds the contract sum
                if (total > contractSum) {
                    let alertHtml = `<div class="alert alert-warning" role="alert">Project Total Exceded</div>`;
                    $("#sum_over").append(alertHtml);
                }
            }
        }

        function updateRowTotal1(element) {
            var $row = $(element).closest('tr');
            var rate = parseFloat($row.find("input[name='rate1[]']").val()) || 0;
            var quantity = parseFloat($row.find("input[name='quantity1[]']").val()) || 0;
            var total = quantity * rate;
            $row.find("input[name='total1[]']").val(total.toFixed(2));
            calculateSubTotal1();
        }
        // Calculate subtotal
        function calculateSubTotal1() {
            var subTotal = 0;
            $("input[name='total1[]']").each(function() {
                subTotal += parseFloat($(this).val()) || 0;
            });
            $("#sub_t1").val(subTotal.toFixed(2));
            calculateTotal1();
        }

        // Calculate total including GST
        function calculateTotal1() {
            var subTotal = parseFloat($("#sub_t1").val()) || 0;
            var discount = parseFloat($("#discount3").val()) || 0;
            var gst = parseFloat($("#order_gst3").val());

            var gst1 = "<?php echo $def_gst[0]->d_gst ?>";
            var isInclusive = $("#inclusive_gst2").is(":checked");

            let contractSum = parseFloat($("#contract_sum1").val()) || 0;

            var discountedSubTotal = subTotal - discount;
            $("#sum_over1").empty();

            if (isInclusive) {
                var gstValue = discountedSubTotal * (gst1 / 100);

                $("#d_gst_i1").val(gstValue.toFixed(2));
                $("#t1").val(discountedSubTotal.toFixed(2));
                $("#grand_total1").val(discountedSubTotal.toFixed(2));
                // Check if the total exceeds the contract sum
                if (discountedSubTotal > contractSum) {
                    let alertHtml = `<div class="alert alert-warning" role="alert">Project Total Exceded</div>`;
                    $("#sum_over1").append(alertHtml);
                }

            } else {
                var gstValue = discountedSubTotal * (gst / 100);
                var total = discountedSubTotal + gstValue;
                $("#g_val1").val(gstValue.toFixed(2));
                $("#t1").val(total.toFixed(2));
                $("#grand_total1").val(total.toFixed(2));
                // Check if the total exceeds the contract sum
                if (total > contractSum) {
                    let alertHtml = `<div class="alert alert-warning" role="alert">Project Total Exceded</div>`;
                    $("#sum_over1").append(alertHtml);
                }
            }
        }

        $("#category_id_prili").on("change", function() {
            let id = $(this).val();

            let jsArray = <?php echo json_encode($grn_data, 15, 512) ?>;


            jsArray = jsArray.data.filter((re) => re.task_cat_id == id);
            let op = `<option value="" selected>Task</option>`;
            op += jsArray.map(re => `<option value="${re.task_id}">${re.task_title}</option>`).join('');

            $("#Task_id_quotation").html(op);



        })

        $(document).ready(function() {
            $("#is_gst").change(function() {
                if (this.checked) {
                    $("#gst_div").hide();
                } else {
                    $("#gst_div").show();


                }
            });
            var counter = 0;
            $("#task_div").hide();

            $("#terms").on("change", function() {
                var invoice_date = $("#invoice_date").val();

                var term_text = $("#terms option:selected").text();
                term_text = parseInt(term_text.replace("days", ""));

                var date = new Date(invoice_date.split("-").reverse().join("-"));
                //alert(date);return false;
                date.setDate(date.getDate() + term_text);
                // alert(date);return false;
                var month = date.getMonth() + 1;
                var day = date.getDate();

                var output = (day < 10 ? '0' : '') + day + '-' + (month < 10 ? '0' : '') + month + '-' +
                    date.getFullYear();
                $('#due_date').val(output);
                $('#bill_due_date_add_edit').val(output);

            });
            $('#addButton1').on('click', function() {
                var rowCount = $(".AddItem tr").length + 1;
                var rowCount5 = $(".AddItem tr").length;
                $(".AddItem").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>
                                <input type='text' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount5}]'>
                                <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='plain'>
                                <input type='hidden' class='form-control' id="u_item_${rowCount}" name='u_item[]'>
                            </td>
                            <td><input type='text' class='form-control' name='quantity[]' id='quantity_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='unit[]' id='unit_${rowCount}'></td>
                            <td><input type='text' class='form-control' name='rate[]' id='rate_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='total[]' id='total_${rowCount}' readonly></td>
                            <td>
                                <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                            </td>
                        </tr>
                    `);

            });

        });

        $("#commonModalSubmitButton").unbind().click(function(e) {
            e.preventDefault();
            let project_product_details = [];
            $('#vendor_items_table1 > tr').each(function(e) {
                let description = $(this).find('.description').val();
                let cost = $(this).find('.cost').val();
                let amount = $(this).find('.amount').val();

                if (description != '' && cost != '' && amount != '') {

                    let arr = {
                        description: description,
                        cost: cost,
                        amount: amount,
                    }

                    project_product_details.push(arr);
                } else {
                    noty({
                        text: 'Please Enter At least one detail',
                        layout: 'bottomLeft',
                        type: 'warning',
                        timeout: '3000',
                        progressBar: false,
                        closeWith: ['click', 'button', 'backdrop'],
                    });
                }

            });

            let url = "<?php echo e(urlResource('/invoices/'.$invoice->invoice_id.'/update')); ?>";

            let form = $('#commonModalForm')[0];
            let data = new FormData(form);
            data.append('project_product_details', JSON.stringify(project_product_details));
            data.append("_token", "<?php echo e(csrf_token()); ?>")
            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                data: data,
                processData: false,
                contentType: false,
                success: function(payload) {
                    console.log(payload);
                    //if (payload.notification.type == 'success') {
                    noty({
                        text: payload.notification.value,
                        layout: 'bottomLeft',
                        type: 'success',
                        timeout: '3000',
                        progressBar: false,
                        closeWith: ['click', 'button', 'backdrop'],
                    });

                    window.location.reload()
                    // }

                },
                error: function(error) {
                    console.log(error)
                    if (error.responseJSON.notification.type == 'error') {
                        noty({
                            text: error.responseJSON.notification.value,
                            layout: 'bottomLeft',
                            type: 'warning',
                            timeout: '3000',
                            progressBar: false,
                            closeWith: ['click', 'button', 'backdrop'],
                        });
                    }
                }
            });
        });
        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();

            updateCalculationPQ();
        });

        function updateCalculationPQ() {

            var total = 0;
            var total_tax = 0;
            var untaxed = 0;
            var total_amount = 0;
            var tax = parseFloat($('#tax').val());

            $('#vendor_items_table1 > tr').each(function() {

                total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

            });

            if ($('#tax_inclusive1').prop('checked') == true) {
                total_tax = (parseFloat(total_amount) * tax) / 100;
                total = total_amount + total_tax;
            } else {
                total = total_amount;
            }

            $('#sub_total').val(total_amount.toFixed(2));

            $('#total_gst1').val(total_tax.toFixed(2));

            $('#total_amount1').val(total.toFixed(2));

        }

        function calculation(id) {
            var total = 0;
            var total_tax = 0;
            var final_total = 0;
            var total_amount = 0;

            var unit_price = $("#cost" + id).val();
            var type = $("#type_id_" + id + " option:selected").text();
            var cost = $("#cost" + id).val();
            if (type == "Select Type" || type == "Addition") {
                $("#amount" + id).val("" + cost)
            } else {
                $("#amount" + id).val("" + cost)
            }

            // var total = parseFloat(unit_price) * parseFloat(quantity);
            // if (total > 0) {
            //     $("#amount" + id).val(total);
            // } else {
            //     $("#amount" + id).val('0');
            // }





            $('#vendor_items_table1 > tr').each(function() {

                total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

            });

            // if($('#tax_inclusive1').prop('checked') == true){
            //     total_tax = (parseFloat(total_amount)*tax)/100;
            //     total = total_amount+total_tax;
            // }else{
            //     total = total_amount;
            // }
            //var tax = parseFloat($('#total_gst1').val());
            var tax = parseFloat($("#total_gst1 option:selected").text());
            if (tax > 0) {
                var final_total = total_amount + total_amount * (tax / 100);

            } else {
                var final_total = total_amount;
            }
            $('#sub_total').val(total_amount.toFixed(2));

            $('#total_gst1').val(total_tax.toFixed(2));

            $('#total_amount1').val(final_total.toFixed(2));
        }

        function totalGSTAmount() {
            var total_amount = parseFloat($('#sub_total').val());
            var tax = parseFloat($("#total_gst1 option:selected").text());
            var total = total_amount + total_amount * (tax / 100);
            $('#total_amount1').val(total);
        }
        jQuery("#project_id").change(function() {

            jQuery.get(base_url + "/get_customer_address/" + jQuery(this).val(), function(data, status) {
                jQuery('#customer_address').html(data);
            });
        });

        function get_type(id) {



            var total = 0;
            var total_tax = 0;
            var final_total = 0;
            var total_amount = 0;

            var type = $("#type_id_" + id).val();
            type = type ? parseFloat(type) : 0;


            var cost = $("#cost" + id).val();
            // if (type) {
            $("#amount" + id).val(parseFloat(type) * parseFloat(cost))
            // }

            $('#vendor_items_table1 > tr').each(function() {

                total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

            });

            var tax = parseFloat($("#total_gst1 option:selected").text());
            if (tax > 0) {
                var final_total = total_amount + total_amount * (tax / 100);

            } else {
                var final_total = total_amount;
            }
            $('#sub_total').val(total_amount.toFixed(2));

            $('#total_gst1').val(total_tax.toFixed(2));

            $('#total_amount1').val(final_total.toFixed(2));
        }
    </script>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/invoices/components/modals/edit.blade.php ENDPATH**/ ?>