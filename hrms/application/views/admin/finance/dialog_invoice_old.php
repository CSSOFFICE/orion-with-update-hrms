<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['invoice_id']) && $_GET['data'] == 'invoice') {
    $system = $this->Xin_model->read_setting_info(1);
    $session = $this->session->userdata('username');
    $user_info = $this->Xin_model->read_user_info($session['user_id']);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_invoice_edit'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'edit_invoice', 'id' => 'edit_invoice', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['invoice_id'], 'ext_name' => $_GET['invoice_id']); ?>
    <?php echo form_open('admin/finance/update_invoice/' . $_GET["invoice_id"], $attributes, $hidden); ?>
    <div class="box-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" id="invoice_url1" value="<?php echo base_url('admin/Finance/related_data_dialog') ?>">
                        <input type="hidden" name="grandtotal1" id="grandtotal1" />
                        <input type="hidden" name="invoice_id1" id="invoice_id1" value="<?= $invoice_id ?>" />
                        <input type="hidden" name="quotation_no1" id="quotation_no1" value="<?= ($estimate_id[0]->bill_estimateid) ?? 0 ?>" />
                        <input type="hidden" name="estimate_id1" id="estimate_id1" value="<?= ($estimate_id[0]->bill_estimateid) ?? 0 ?>" />
                        <label for="quotation_no1"><?php echo "Quotation Number" ?><i class="hrsale-asterisk">*</i></label><br>
                        <label for="quotation_no1"><?php echo $quotation_no ?></label>

                    </div>
                    <div class="form-group">
                        <label for="client_id"><?php echo $this->lang->line('xin_customer'); ?><i class="hrsale-asterisk">*</i></label>
                        <select class="form-control" name="client_id1" id="client_id1" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                            <?php foreach ($all_customer as $crm) { ?>
                                <option value="<?php echo $crm->client_id; ?>">
                                    <?php echo ($crm->f_name) ?? $crm->client_company_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="project_id"><?php echo $this->lang->line('xin_project'); ?><i class="hrsale-asterisk">*</i></label>
                        <select class="form-control" name="project_id1" id="project_id1" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                            <?php foreach ($get_all_projects as $project) { ?>
                                <option value="<?php echo $project->project_id; ?>">
                                    <?php echo $project->project_title; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="terms">Invoice Date<i class="hrsale-asterisk">*</i></label>
                        <input type="text" id="invoice_date1" name="invoice_date1" class="form-control date" placeholder="Invoice Date" value="<?= date('d-m-Y', strtotime($invoice_date)) ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DO Number</label>
                                <input type="text" id="m_do_no1" name="m_do_no1" value="<?php echo $m_do_no ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Order Number</label>
                                <input type="text" id="m_order_no1" name="m_order_no1" value="<?php echo $m_order_no ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Billing Address</label>
                                <select class="form-control" placeholder="Billing Address" id="u_bill_address" name="u_bill_address">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Delivery Address</label>
                                <select class="form-control" placeholder="Delivery Address" id="u_delivery_address" name="u_delivery_address">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payment_term"><?php echo $this->lang->line('xin_payment_term'); ?></label>
                        <select class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_term'); ?>" name="payment_term1" id="terms1">
                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                            <?php foreach ($all_payment_terms->result() as $payment_terms) { ?>
                                <option value="<?php echo $payment_terms->payment_term_id; ?>"><?php echo $payment_terms->payment_term; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="terms">Due Date<i class="hrsale-asterisk">*</i></label>
                        <input type="text" name="due_date1" id="due_date1" class="form-control date" placeholder="Invoice Due Date" value="<?php echo date('d-m-Y', strtotime($invoice_due_date)); ?>">
                    </div>
                    <div class="form-group">
                        <label for="terms">Terms & Condition</label>
                        <textarea name="cterm6" class="form-control" id="editor3" cols="30" rows="10"><?php echo $cond_term ?></textarea>
                        <script>
                            ClassicEditor.create(document.querySelector('#editor3')).then(editor => {
                                console.log(editor);
                            }).catch(error => {
                                console.error(error);
                            });
                        </script>
                    </div>
                    <div class="p-20">
                        <div class="table-responsive my-3 purchaseTable">
                            <table class="table" id="data_table1" border="1">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <!-- <th>Item</th> -->
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <button id="add_product1" type="button" style="border-radius:50px; padding:5px;" class="btn btn-success">Add Product</button>
                                    <button id="blank_line1" type="button" style="border-radius:50px; padding:3px; margin:5px;" class="btn btn-info">Add Blank Line</button>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <?php $def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result() ?>

                                <label>Sub Total</label>
                                <input type="text" class="form-control" id="sub_t1" name="sub_t1" readonly>
                                <input type="checkbox" id="inclusive_gst2" name="inclusive_gst2" <?php if ($gst_inclusive == 'on') {
                                                                                                        echo "checked";
                                                                                                    } ?>>
                                <label for="inclusive_gst2">Inclusive GST</label><br>
                                <div id="gst_box2">
                                    <label>GST</label>

                                    <select class="form-control" id="order_gst3" name="order_gst3">
                                        <option>Select</option>
                                        <?php $all_gst = $this->db->get('xin_gst')->result();
                                        foreach ($all_gst as $gst1) { ?>
                                            <option value="<?php echo $gst1->gst ?>" <?php if ($gst == $gst1->gst) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $gst1->gst ?></option>
                                        <?php } ?>
                                    </select>

                                    <label>GST Value</label>
                                    <input type="text" class="form-control" id="g_val1" name="g_val1" readonly>
                                </div>
                                <div id="gst_box3">
                                    <label>Inclusive GST Value (<?php echo $def_gst[0]->d_gst ?> %)</label>
                                    <input type="text" class="form-control" id="d_gst_i1" name="d_gst_i1" readonly>
                                </div>
                                <!-- <label>Discount (%)</label>
                                                <input type="text" class="form-control" id="discount3" name="discount3"> -->

                                <label>Total</label>
                                <input type="text" class="form-control" id="t1" name="t1" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger abc" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        <button type="submit" class="btn btn-primary ">Save</button>
    </div>
    <?php echo form_close(); ?>
<?php } ?>
<script>
    var produt_new_arraay = [];

    function updateUnit(selectElement, rowIndex) {
        var selectedProductId = $(selectElement).val();

        var selectedProduct = produt_new_arraay.find(product => product.product_id == selectedProductId);
        if (selectedProduct) {
            $(`#unit1_${rowIndex}`).val(selectedProduct.std_uom);
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


        var discountedSubTotal = subTotal - discount;

        if (isInclusive) {
            var gstValue = discountedSubTotal * (gst1 / 100);

            $("#d_gst_i1").val(gstValue.toFixed(2));
            $("#t1").val(discountedSubTotal.toFixed(2));
            $("#grand_total1").val(discountedSubTotal.toFixed(2));
        } else {
            var gstValue = discountedSubTotal * (gst / 100);
            var total = discountedSubTotal + gstValue;
            $("#g_val1").val(gstValue.toFixed(2));
            $("#t1").val(total.toFixed(2));
            $("#grand_total1").val(total.toFixed(2));
        }
    }

    // Toggle GST input fields
    function toggleGSTInput2() {
        var isInclusive = $("#inclusive_gst2").is(":checked");
        if (isInclusive) {
            $("#gst_box2").hide();
            $("#gst_box3").show();
        } else {
            $("#gst_box2").show();
            $("#gst_box3").hide();
        }
    }
    $(document).ready(function() {

        // Initialize datepicker
        $('.date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '1900:' + new Date().getFullYear()
        });

        // Global variable to store the response
        var globalResponse = null;

        $("#terms1").on("change", function() {
            var invoice_date = $("#invoice_date1").val();
            var term_text = $("#terms1 option:selected").text();

            // Check if term_text matches the pattern for "XX days" where XX is a number
            var days = null;
            if (/^\d+\s*days$/i.test(term_text)) {
                // Extract number of days from term_text
                days = parseInt(term_text.replace(/\D/g, ""));


                if (!isNaN(days) && invoice_date) {
                    // If terms contain a valid number of days, calculate the due date
                    var date = new Date(invoice_date.split("-").reverse().join("-"));
                    date.setDate(date.getDate() + days);

                    // Format the due date as DD-MM-YYYY
                    var month = date.getMonth() + 1;
                    var day = date.getDate();
                    var output = (day < 10 ? '0' : '') + day + '-' + (month < 10 ? '0' : '') + month + '-' + date.getFullYear();
                    $('#due_date1').val(output);
                }
            } else {
                // Handle invalid terms by setting a default or clearing the due date
                $('#due_date1').val("<?php echo date('d-m-Y', strtotime($invoice_due_date)); ?>");
            }
        });





        $('#client_id1').on('change', function() {
            let clientId = $(this).val();
            let selectedBillingAddress = "<?php echo $billing_addresses; ?>"; // Pass the selected billing address from PHP
            let selectedShippingAddress = "<?php echo $shipping_addresses; ?>"; // Pass the selected shipping address from PHP

            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/get_client_address/'; ?>" + clientId,
                type: "POST",
                success: function(response) {
                    let data = JSON.parse(response); // Parse the JSON response
                    let billingOptions = '';
                    let deliveryOptions = '';

                    // Populate billing addresses
                    if (data.billing_address.length > 0) {
                        $.each(data.billing_address, function(index, address) {
                            let fullAddress = '';

                            if (address.street) fullAddress += `${address.street}\n`; // Add street if not null
                            if (address.state) fullAddress += `${address.state}\n`; // Add state if not null
                            if (address.city) fullAddress += `${address.city}\n`; // Add city if not null
                            if (address.zipcode) fullAddress += `${address.zipcode}`; // Add zipcode if not null

                            // Check if the current address is the selected one
                            let isSelected = (address.id == selectedBillingAddress) ? 'selected' : '';

                            billingOptions += `<option value="${address.id}" ${isSelected}>${fullAddress.trim()}</option>`;
                        });
                    } else {
                        billingOptions = '<option>No billing addresses available</option>';
                    }

                    // Populate delivery addresses
                    if (data.shipping_address.length > 0) {
                        $.each(data.shipping_address, function(index, address) {
                            let fullAddress = '';

                            if (address.street) fullAddress += `${address.street}\n`; // Add street if not null
                            if (address.state) fullAddress += `${address.state}\n`; // Add state if not null
                            if (address.city) fullAddress += `${address.city}\n`; // Add city if not null
                            if (address.zipcode) fullAddress += `${address.zipcode}`; // Add zipcode if not null

                            // Check if the current address is the selected one
                            let isSelected = (address.id == selectedShippingAddress) ? 'selected' : '';

                            deliveryOptions += `<option value="${address.id}" ${isSelected}>${fullAddress.trim()}</option>`;
                        });
                    } else {
                        deliveryOptions = '<option>No delivery addresses available</option>';
                    }

                    // Append options to the select elements
                    $('#u_bill_address').html(billingOptions);
                    $('#u_delivery_address').html(deliveryOptions);
                }
            });
        });

        // Update unit based on selected item

        // Calculate subtotal
        function calculateSubTotal1() {
            var subTotal = 0;
            $("input[name='total1[]']").each(function() {
                subTotal += parseFloat($(this).val()) || 0;
            });
            console.log(subTotal)
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

            var discountedSubTotal = subTotal - discount;

            if (isInclusive) {
                var gstValue = discountedSubTotal * (gst1 / 100);

                $("#d_gst_i1").val(gstValue.toFixed(2));
                $("#t1").val(discountedSubTotal.toFixed(2));
                $("#grand_total1").val(discountedSubTotal.toFixed(2));
            } else {
                var gstValue = discountedSubTotal * (gst / 100);
                var total = discountedSubTotal + gstValue;
                $("#g_val1").val(gstValue.toFixed(2));
                $("#t1").val(total.toFixed(2));
                $("#grand_total1").val(total.toFixed(2));
            }
        }

        // Toggle GST input fields
        function toggleGSTInput2() {
            var isInclusive = $("#inclusive_gst2").is(":checked");
            if (isInclusive) {
                $("#gst_box2").hide();
                $("#gst_box3").show();
            } else {
                $("#gst_box2").show();
                $("#gst_box3").hide();
            }
        }

        // Update row total


        // Load invoice data
        function loadInvoiceData(invoice_id) {
            $.ajax({
                url: $("#invoice_url1").val() + "/" + invoice_id,
                type: "POST",
                success: function(response) {
                    console.log(response.products);
                    produt_new_arraay = response.products;
                    globalResponse = response;
                    $("#client_id1").val(response.records[0].client_id).trigger("change");
                    $("#project_id1").val(response.records[0].project_id).trigger("change");
                    $("#terms1").val(response.records[0].terms).trigger("change");

                    $("#data_table1 tbody").empty();

                    response.records.forEach(function(record, index) {
                        $("#data_table1 tbody").append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>
                                ${(record.product_id !== null && !isNaN(record.product_id)) ? `
                                    <select name="u_item1[${index}]" class="form-control item_name1" id="u_item1_${index + 1}" onchange="updateUnit(this, ${index + 1})">
                                        <option value="">Select Item</option>
                                        ${response.products.map(product => `
                                            <option value="${product.product_id}" ${product.product_id == record.product_id ? "selected" : ""}>${product.product_name}</option>
                                        `).join('')}
                                    </select>
                                    <input type='hidden' class='form-control' id="type1_${index + 1}" name='type1[]' value='product'>
                                ` : `
                                    <input type='hidden' class='form-control' id="type1_${index + 1}" name='type1[]' value='plain'>
                                    <input type="text" name="item_description1[${index}]" id="item_description1_${index + 1}" class="form-control" value="${record.job_description}">
                                `}
                                </td>
                                <td><input type="text" name="quantity1[]" id="quantity1_${index + 1}" class="form-control" value="${record.item_qtn}" oninput="updateRowTotal1(this)"></td>
                                <td><input type="text" name="unit1[]" id="unit1_${index + 1}" class="form-control" value="${record.unit}" readonly></td>
                                <td><input type="text" name="rate1[]" id="rate1_${index + 1}" class="form-control" value="${record.rate}" oninput="updateRowTotal1(this)"></td>
                                <td><input type="text" name="total1[]" id="total1_${index + 1}" class="form-control" value="${record.total}" readonly></td>
                                <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                            </tr>
                        `);
                        updateRowTotal1($(`#quantity1_${index + 1}`)); // Ensure function is called
                    });

                    // Calculate subtotals and totals after rows are appended
                    calculateSubTotal1();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", error);
                    alert("An error occurred while fetching data. Please try again.");
                }
            });
        }

        // Add new product row
        $("#add_product1").click(function() {
            var rowCount = $("#data_table1 tbody tr").length + 1;
            var rowCount3 = $("#data_table1 tbody tr").length;
            $("#data_table1 tbody").append(`
                <tr>
                    <td>${rowCount}</td>
                    <td>
                        <select name="u_item1[${rowCount3}]" id="u_item1_${rowCount}" class="form-control item_name1" required onchange="updateUnit(this, ${rowCount})">
                            <option value="">Select Item</option>
                            <?php foreach ($all_items as $item) { ?>
                                <option value="<?php echo $item->product_id; ?>"><?php echo $item->product_name  ?></option>
                            <?php } ?>
                        </select>
                        <input type='hidden' class='form-control' id="type1_${rowCount}" name='type1[]' value='product'>
                    </td>                    
                    <td><input type="text" name="quantity1[]" id="quantity1_${rowCount}" class="form-control" oninput="updateRowTotal1(this)"></td>
                    <td><input type="text" name="unit1[]" id="unit1_${rowCount}"  class="form-control" readonly></td>
                    <td><input type="text" name="rate1[]" id="rate1_${rowCount}" class="form-control" oninput="updateRowTotal1(this)"></td>
                    <td><input type="text" name="total1[]" id="total1_${rowCount}" class="form-control" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                </tr>
            `);
            updateRowTotal1($(`#quantity1_${rowCount}`));
            // Calculate subtotals and totals after rows are appended
            calculateSubTotal1();
        });

        // Add blank line
        $("#blank_line1").click(function() {
            var rowCount = $("#data_table1 tbody tr").length + 1;
            var rowCount2 = $("#data_table1 tbody tr").length;
            $("#data_table1 tbody").append(`
                <tr>
                    <td>${rowCount}</td>                    
                    <td>
                        <input type='hidden' class='form-control' id="type1_${rowCount}" name='type1[]' value='plain'>
                        <input type="text" id="item_description1_${rowCount}" name="item_description1[${rowCount2}]" class="form-control">                                    
                    </td>
                    <td><input type="text" name="quantity1[]" id="quantity1_${rowCount}" class="form-control" oninput="updateRowTotal1(this)"></td>
                    <td><input type="text" name="unit1[]" id="unit1_${rowCount}" class="form-control"></td>
                    <td><input type="text" name="rate1[]" id="rate1_${rowCount}" class="form-control" oninput="updateRowTotal1(this)"></td>
                    <td><input type="text" name="total1[]" id="total1_${rowCount}" class="form-control" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                </tr>
            `);
            updateRowTotal1($(`#quantity1_${rowCount}`));
            // Calculate subtotals and totals after rows are appended
            calculateSubTotal1();
        });

        // Remove row
        $(document).on("click", ".remove-input-field", function() {
            $(this).closest('tr').remove();
            calculateSubTotal1();
        });

        // Event listeners
        $("#order_gst3").change(function() {
            calculateSubTotal1();
        });

        $("#inclusive_gst2").change(function() {
            toggleGSTInput2();
            calculateTotal1();
        });

        $("#order_gst3, #discount3").change(function() {
            calculateTotal1();
        });

        // Load data on page load
        var invoice_id = <?php echo $invoice_id ?>;
        loadInvoiceData(invoice_id);

        // Initial toggle for GST inputs
        toggleGSTInput2();


    });
    $("#edit_invoice").submit(function(e) {
        // var sub_total=$('#sub_total').text();
        // var total_gst1=$('#total_gst1').val();
        // var gst_amount=$('#gst_amount').text();
        // var total_amount1=$('#total_amount1').text();
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/update_invoice/" + <?php echo $_GET["invoice_id"] ?>,
            data: obj.serialize() +
                // "&sub_total="+sub_total+
                //                                         "&total_gst1="+total_gst1+
                //                                         "&gst_amount="+gst_amount+
                //                                         "&total_amount1="+total_amount1+

                "&is_ajax=1&add_type=invoice&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.abc').click();
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
</script>