<style>
    [type="checkbox"]:checked+label:before {
        display: none;
    }

    [type="checkbox"]:checked+label {
        padding-left: 12px;

    }

    [type="checkbox"].filled-in:checked.chk-col-light-blue+label:after {
        display: none;
    }

    #add_form {
        height: 100% !important;
    }
</style>
<?php
/* Purchase view
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('3102', $role_resources_ids)) { ?>

    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>
                    <?php echo $this->lang->line('xin_invoices_title'); ?></h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a> </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_quotation', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('user_id' => $session['user_id']); ?>
                    <?php echo form_open_multipart('admin/finance/add_invoice', $attributes, $hidden); ?>
                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" name="" id="invoice_url" value="<?php echo base_url('admin/Finance/related_data') ?>">
                                        <input type="hidden" name="grandtotal" id="grand_total" />

                                        <label for="quotation_no"><?php echo "Quotation Number" ?>
                                            <i class="hrsale-asterisk">*</i>
                                        </label>
                                        <select class="form-control" name="quotation_no" id="quotation_no" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                            <?php foreach ($all_quotation as $estimate) { ?>
                                                <option value="<?php echo $estimate->bill_estimateid; ?>">
                                                    <?php echo $estimate->quotation_no; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="project_id"><?php echo $this->lang->line('xin_customer'); ?>
                                            <i class="hrsale-asterisk">*</i>
                                        </label>
                                        <select class="form-control" name="client_id" id="client_id" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                            <?php foreach ($all_customer as $crm) { ?>
                                                <option value="<?php echo $crm->client_id; ?>">
                                                    <?php echo ($crm->f_name) ?? $crm->client_company_name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="project_id"><?php echo $this->lang->line('xin_project'); ?>
                                            <i class="hrsale-asterisk">*</i>
                                        </label>
                                        <select class="form-control" name="project_id" id="project_id" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                            <?php foreach ($get_all_projects as $project) { ?>
                                                <option value="<?php echo $project->project_id; ?>">
                                                    <?php echo $project->project_title; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>DO Number</label>
                                                <input type="text" name="m_do_no" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Order Number</label>
                                                <input type="text" name="m_order_no" id="m_order_no" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Billing Address</label>
                                                <select class="form-control" placeholder="Billing Address" id="bill_address" name="bill_address">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Delivery Address</label>
                                                <select class="form-control" placeholder="Delivery Address" id="delivery_address" name="delivery_address">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="customer_address"></div>
                                    <div class="form-group">
                                        <label for="terms">Invoice Date<i class="hrsale-asterisk">*</i></label>
                                        <input class="form-control date" type="text" id="invoice_date" name="invoice_date" placeholder="Invoice Date">
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_term"><?php echo $this->lang->line('xin_payment_term'); ?></label>
                                        <select class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_term'); ?>" name="payment_term" id="terms">
                                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                            <?php foreach ($all_payment_terms->result() as $payment_terms) { ?>
                                                <option value="<?php echo $payment_terms->payment_term_id; ?>"><?php echo $payment_terms->payment_term; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="terms">Due Date<i class="hrsale-asterisk">*</i></label>
                                        <input type="text" name="due_date" id="due_date" class="form-control date" placeholder="Invoice Due Date">
                                    </div>
                                    <div class="p-20">
                                        <div class="table-responsive my-3 purchaseTable">
                                            <table class="table" id="data_table" border="1">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Item</th>
                                                        <!-- <th>Job Description</th> -->
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Rate</th>
                                                        <th>Amount</th>
                                                        <th></th>
                                                        <!-- Add more headers as needed -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <button id="add_product" type="button" style="border-radius:50px; padding:5px;" class="btn btn-success">Add Product</button>
                                                    <button id="add_blank" type="button" style="border-radius:50px; padding:3px; margin:5px;" class="btn btn-info">Add Blank Line</button>
                                                    <!-- Data will be populated here -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8"></div>
                                            <div class="col-md-4">
                                                <label>Sub Total</label>
                                                <input type="text" class="form-control" id="sub_t" name="sub_t" readonly>
                                                <input type="checkbox" id="inclusive_gst" name="inclusive_gst" checked>
                                                <label for="inclusive_gst">Inclusive GST</label><br>
                                                <div id="gst_box">
                                                    <label>GST</label>
                                                    <select class="form-control" id="order_gst2" name="order_gst2">
                                                        <option>Select</option>
                                                        <?php $all_gst = $this->db->get('xin_gst')->result();
                                                        foreach ($all_gst as $gst) { ?>
                                                            <option value="<?php echo $gst->gst ?>"><?php echo $gst->gst ?></option>
                                                        <?php } ?>
                                                    </select>

                                                    <label>GST Value</label>
                                                    <input type="text" class="form-control" id="g_val" name="g_val" readonly>
                                                </div>
                                                <div id="gst_box1">
                                                    <?php $def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result() ?>
                                                    <label>Inclusive GST Value (<?php echo $def_gst[0]->d_gst ?> %)</label>
                                                    <input type="text" class="form-control" id="d_gst_i" name="d_gst_i" readonly>
                                                </div>
                                                <!-- <label>Discount (%)</label>
                                                <input type="text" class="form-control" id="discount2" name="discount2"> -->

                                                <label>Total</label>
                                                <input type="text" class="form-control" id="t" name="t" readonly>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="form-group">
                                        <label for="terms">Terms & Condition</label>

                                        <script src="<?php echo base_url('assets/ckeditor.js') ?>"></script>
                                        <?php $query = $this->db->get('xin_system_setting')->result(); ?>
                                        <textarea name="cterm" class="form-control" id="editor1" cols="30" rows="10"><?php echo $query[0]->invoice_terms_condition ?></textarea>
                                        <script>
                                            ClassicEditor.create(document.querySelector('#editor1')).then(editor => {
                                                console.log(editor);
                                            }).catch(error => {
                                                console.error(error);
                                            });
                                        </script>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="form-actions box-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                                <?php echo $this->lang->line('xin_save'); ?> </button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php echo $this->lang->line('xin_invoices_title'); ?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('xin_action'); ?></th>
                        <th>Invoice No</th>
                        <th><?php echo $this->lang->line('xin_project'); ?></th>
                        <th><?php echo $this->lang->line('xin_customer'); ?></th>
                        <th><?php echo $this->lang->line('xin_created_date'); ?></th>
                        <!-- <th>Status</th> -->


                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({
            width: '100%'
        });
        $('.date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '1900:' + new Date().getFullYear()
        });


        $('#client_id').on('change', function() {
            let clientId = $(this).val();

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

                            billingOptions += `<option value="${address.id}">${fullAddress.trim()}</option>`;
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

                            deliveryOptions += `<option value="${address.id}">${fullAddress.trim()}</option>`;
                        });
                    } else {
                        deliveryOptions = '<option>No delivery addresses available</option>';
                    }

                    // Append options to the select elements
                    $('#bill_address').html(billingOptions);
                    $('#delivery_address').html(deliveryOptions);
                }
            });
        });


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

            var output = (day < 10 ? '0' : '') + day + '-' + (month < 10 ? '0' : '') + month + '-' + date.getFullYear();
            $('#due_date').val(output);


        });




        $(document).on("change", "#total_gst1", function() {
            totalGSTAmount();
        });

        // Add Product button click event
        $(document).on("click", "#add_product", function() {
            var rowCount = $("#data_table tbody tr").length + 1;
            var rowCount1 = $("#data_table tbody tr").length;
            // Initialize the select element with an opening <select> tag
            let selectElement = `<select name="u_item[${rowCount1}]" id="u_item_${rowCount}" class="form-control">`;
            selectElement += `<option value="">Select Product</option>`;

            $.ajax({
                url: '<?php echo base_url('admin/Finance/getProducts') ?>',
                type: "POST",
                dataType: "json",
                success: function(response) {
                    // Populate the select options dynamically
                    $.each(response, function(key, value) {
                        selectElement += `<option value="${value.product_id}">${value.product_name}</option>`;
                    });
                    selectElement += `</select>`; // Closing the <select> tag

                    // Append the new row to the table
                    $("#data_table tbody").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>${selectElement}
                                <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='product'>  
                                <input type='hidden' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount1}]'>
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
                }
            });
        });

        // Add Product button click event
        // $(document).on("click", "#add_product", function() {
        //     var rowCount = $("#data_table tbody tr").length + 1;
        //     var rowCount1 = $("#data_table tbody tr").length;

        //     var selectElement = `<select class="form-control" name="u_item[${rowCount1}]" id="u_item_${rowCount}" data-plugin="select_hrm" data-placeholder="Select Product" onchange="updateUnit(this, ${rowCount})"><option value="">Select Product</option>`;
        //     $.each(response.products, function(key, value) {
        //         selectElement += `<option value="${value.product_id}">${value.product_name} ${value.size} ${value.type} ${value.brand}</option>`;
        //     });
        //     selectElement += `</select>`;

        //     $("#data_table tbody").append(`
        //                 <tr>
        //                     <td>${rowCount}</td>
        //                     <td>${selectElement}
        //                         <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='product'>  
        //                         <input type='hidden' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount1}]'>
        //                     </td>
        //                     <td><input type='text' class='form-control' name='quantity[]' id='quantity_${rowCount}' oninput='updateRowTotal(this)'></td>
        //                     <td><input type='text' class='form-control' name='unit[]' id='unit_${rowCount}'></td>
        //                     <td><input type='text' class='form-control' name='rate[]' id='rate_${rowCount}' oninput='updateRowTotal(this)'></td>
        //                     <td><input type='text' class='form-control' name='total[]' id='total_${rowCount}' readonly></td>
        //                     <td>
        //                         <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
        //                     </td>
        //                 </tr>
        //             `);
        // });

        // Add Blank Line button click event
        $(document).on("click", "#add_blank", function() {
            var rowCount = $("#data_table tbody tr").length + 1;
            var rowCount5 = $("#data_table tbody tr").length;

            $("#data_table tbody").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>
                                <input type='text' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount5}]'>
                                <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='plain'>   
                                <input type='hidden' class='form-control' id="u_item_${rowCount}" name='u_item[${rowCount5}]'>
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

        $(document).on("change", "#quotation_no", function() {
            var quotation_id = $(this).val();



            // $("#data_table").show();
            $("#data_table tbody").empty(); // Clear the table body each time a new quotation is selected

            $.ajax({
                url: $("#invoice_url").val() + "/" + quotation_id,
                type: "POST",
                success: function(response) {
                    console.log(response);

                    // Populate client dropdown
                    $("#client_id").val(response.client_project[0].bill_clientid).trigger('change');
                    // Populate project dropdown
                    $("#project_id").val(response.client_project[0].bill_projectid).trigger('change');

                    $("#m_order_no").val(response.client_project[0].po_number).trigger('change');

                    var i = 1;

                    $.each(response.records, function(key, value) {
                        var abcd = response.products.map(function(value1) {
                            if (value1.product_id == value["item"]) {
                                return `<option value="${value1.product_id}" selected>${value1.product_name} ${value1.size} ${value1.type} ${value1.brand}</option>`;
                            } else {
                                return `<option value="${value1.product_id}">${value1.product_name} ${value1.size} ${value1.type} ${value1.brand}</option>`;
                            }
                        }).join(''); // Join array into a single string

                        $("#data_table tbody").append(`
                        <tr>
                            <td>${i}</td>
                            <td>
                                ${(value['item'] !== null && !isNaN(value['item'])) ? `
                                    <select class="form-control" name="u_item[]" id="u_item_${i}" data-plugin="select_hrm" data-placeholder="Select Product" onchange="updateUnit(this, ${i})">
                                        ${abcd}
                                    </select>
                                    <input type='hidden' class='form-control' id="type_${i}" name='type[]' value='product'>
                                    <input type='hidden' class='form-control' id="item_description_${i}" name='item_description[]'></td>
                                    ` : `
                                    <input type='text' class='form-control' id="item_description_${i}" name='item_description[]' value='${value['description']}'></td>
                                    <input type='hidden' class='form-control' id="type_${i}" name='type[]' value='plain'>
                                    <input type='hidden' class='form-control' id="u_item_${i}" name='u_item[]'>
                                `}
                            </td>
                            <td><input type='text' class='form-control' name='quantity[]' id='quantity_${i}' value='${value['qty']}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='unit[]' id='unit_${i}' value='${value['unit']}'></td>
                            <td><input type='text' class='form-control' name='rate[]' id='rate_${i}' value='${value['total']}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='total[]' id='total_${i}' readonly></td>
                            <td>
                                <button type='button' name='clear' class='btn btn-danger remove-input-field'><i class='ti-trash'></i></button>
                            </td>
                        </tr>
                    `);

                        // Calculate and set the total for each row after appending
                        updateRowTotal($(`#quantity_${i}, #rate_${i}`).first());
                        i++;
                    });

                    // Add Product button click event
                    $(document).on("click", "#add_product", function() {
                        var rowCount = $("#data_table tbody tr").length + 1;
                        var rowCount1 = $("#data_table tbody tr").length;

                        var selectElement = `<select class="form-control" name="u_item[${rowCount1}]" id="u_item_${rowCount}" data-plugin="select_hrm" data-placeholder="Select Product" onchange="updateUnit(this, ${rowCount})"><option value="">Select Product</option>`;
                        $.each(response.products, function(key, value) {
                            selectElement += `<option value="${value.product_id}">${value.product_name} ${value.size} ${value.type} ${value.brand}</option>`;
                        });
                        selectElement += `</select>`;

                        $("#data_table tbody").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>${selectElement}
                                <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='product'>  
                                <input type='hidden' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount1}]'>
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

                    // Add Blank Line button click event
                    $(document).on("click", "#add_blank", function() {
                        var rowCount = $("#data_table tbody tr").length + 1;
                        var rowCount5 = $("#data_table tbody tr").length;

                        $("#data_table tbody").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>
                                <input type='text' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount5}]'>
                                <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='plain'>   
                                <input type='hidden' class='form-control' id="u_item_${rowCount}" name='u_item[${rowCount5}]'>
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
                }
            });

        });



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


    function calculateSubTotal() {
        var subTotal = 0;
        $("input[name='total[]']").each(function() {
            subTotal += parseFloat($(this).val()) || 0;
        });
        $("#sub_t").val(subTotal.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        var subTotal = parseFloat($("#sub_t").val()) || 0;
        var discount = parseFloat($("#discount2").val()) || 0;
        var gst = parseFloat($("#order_gst2").val()) || 0;
        var gst1 = "<?php echo $def_gst[0]->d_gst ?>";
        var isInclusive = $("#inclusive_gst").is(":checked");

        var discountedSubTotal = subTotal - discount;

        if (isInclusive) {
            var gstValue = discountedSubTotal * (gst1 / 100);

            $("#d_gst_i").val(gstValue.toFixed(2));
            // $("#g_val").val(gstValue.toFixed(2));
            $("#t").val(discountedSubTotal.toFixed(2));
            $("#grand_total").val(discountedSubTotal.toFixed(2));
        } else {
            var gstValue = discountedSubTotal * (gst / 100);
            var total = discountedSubTotal + gstValue;
            $("#g_val").val(gstValue.toFixed(2));
            $("#t").val(total.toFixed(2));
            $("#grand_total").val(total.toFixed(2));

        }
    }

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

    $(document).ready(function() {
        $("#inclusive_gst").change(function() {
            toggleGSTInput();
            calculateTotal();
        });

        $("#order_gst2, #discount2").change(function() {
            calculateTotal();
        });

        toggleGSTInput();
    });




    function updateUnit(selectElement, item) {
        var productId = selectElement.value;
        $.ajax({
            url: "<?php echo base_url() . 'admin/Finance/get_prod_details/'; ?>" + productId,
            type: "POST",
            success: function(response) {
                // Assuming response contains the product details
                var selectedProduct = response.products.find(product => product.product_id == productId);
                // Update the unit field
                if (selectedProduct) {
                    $('#unit_' + item).val(selectedProduct.std_uom);
                    $('#rate_' + item).val(selectedProduct.sell_p);
                }
            }
        });
    }
    $(document).on('click', '.remove-input-field', function() {
        $(this).closest('tr').remove();
        calculateSubTotal();
    });
</script>