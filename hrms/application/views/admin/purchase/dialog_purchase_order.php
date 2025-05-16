<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['purchase_order_id']) && $_GET['data'] == 'purchase_order') {

?>
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


        #ajax_modal {
            width: 1000px !important;
            margin-left: -150px;
        }
    </style>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data">Purchase Order</h4>
    </div>
    <?php $attributes = array('name' => 'edit_purchase_order', 'id' => 'edit_purchase_order', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['purchase_order_id'], 'ext_name' => $_GET['purchase_order_id']); ?>
    <?php echo form_open_multipart('admin/purchase/update_order/' . $_GET["purchase_order_id"], $attributes, $hidden); ?>
    <div class="modal-body">
        <input type="hidden" id="po_url1" value="<?php echo base_url('admin/purchase/get_edit_po_details/' . $_GET["purchase_order_id"]) ?>">
        <input type="hidden" name="purchase_order_id" value="<?php echo $_GET['purchase_order_id'] ?>">
        <input type="hidden" name="porder_id" value="<?php echo $porder_id ?>">

        <article>
            <header style="text-align: center;">
                <img src="<?php echo site_url('uploads/logo/' . $invoice_settings[0]->invoice_logo) ?>" class="img-fluid" width="100px">
                <p class="m-0" style="font-size: 12px; text-align:center;"><?php echo $settings[0]->address_1; ?> <?php echo $settings[0]->address_2; ?> <?php echo $settings[0]->state; ?> <?php echo $settings[0]->city; ?> <?php echo $settings[0]->zipcode; ?> <?php echo $settings[0]->phone; ?></p>
                <p class="mb-1" style="font-size: 12px; text-align:center;">REG.NO: <?php echo $invoice_settings[0]->invoice_reg_no; ?></p>
                <p class="mb-1" style="font-size: 12px; text-align:center; font-weight: 800; color: #000;">(GST REG.NO: <?php echo $invoice_settings[0]->invoice_gst_no; ?>)</p>
            </header>
            <h1 style="text-align: left; font-size: 18px; padding: 0.5em 0; margin: 0.5em 0;"><strong>
                    Purchase Order ID : <?php echo $porder_id ?>

                </strong></h1>
        </article>

        <div class="row">
            <div class="col-md-4">
                <label>Employee Name:</label>
                <?php $emp = $this->db->get('xin_employees')->result() ?>
                <select name="emp" class="form-control">
                    <option>Select Employee</option>
                    <?php foreach ($emp as $employe) { ?>
                        <option value="<?php echo $employe->user_id ?>" <?php if ($employe->user_id == $cust_id) {
                                                                            echo "selected";
                                                                        } ?>><?php echo $employe->first_name . " " . $employe->last_name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>PROJECT NAME:</label>
                <?php $project1 = $this->db->get('projects')->result(); ?>

                <input type="hidden" name="old_project" value="<?php echo $project ?>">
                <select class="form-control" data-plugin="select_hrm" data-placeholder="Select Project" name="project" id="project1" onchange="getProjectAddress1()">
                    <option>Select Project</option>
                    <?php foreach ($project1 as $pr) { ?>
                        <option value="<?php echo $pr->project_id ?>" <?php if ($pr->project_id == $project) {
                                                                            echo "selected";
                                                                        } ?>>
                            <?php echo  $pr->project_title ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">

                <label>Site Address:</label>
                <textarea class="form-control" name="s_add1" id="s_add1" rows="5"><?php echo $site_add ?></textarea>

            </div>
            <div class="col-md-4">
                <label>Milestone</label>
                <select name="milestone_id1" id="milestone_id1" class="form-control">
                    <option value="">Select Milestone</option>
                    <option value="1" <?php echo ($milestone == 1) ? 'selected' : '' ?>>Preliminaries</option>
                    <option value="2" <?php echo ($milestone == 2) ? 'selected' : '' ?>>Insurance</option>
                    <option value="3" <?php echo ($milestone == 3) ? 'selected' : '' ?>>Schedule Of Works</option>
                    <option value="4" <?php echo ($milestone == 4) ? 'selected' : '' ?>>Plumbing & Sanitary</option>
                    <option value="5" <?php echo ($milestone == 5) ? 'selected' : '' ?>>Elec & Acmv</option>
                    <option value="6" <?php echo ($milestone == 6) ? 'selected' : '' ?>>External Works</option>
                    <option value="7" <?php echo ($milestone == 7) ? 'selected' : '' ?>>Pc & Ps Sums</option>
                    <option value="7" <?php echo ($milestone == 8) ? 'selected' : '' ?>>Others</option>
                </select>

            </div>
            <div class="col-md-4">
                <label>Task</label>
                <select name="task_id1" id="task_id1" class="form-control" data-plugin="select_hrm">

                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">

            </div>
        </div>
        <div class="row">
            <div class="col-md-4" style="margin-top:15px">
                <label>Delivery Type</label>
                <select class="form-control" name="u_delivery_type" id="u_delivery_type">
                    <option value="">Select Delivery Type</option>
                    <option value="self_collection" <?php echo ($delivery_type == 'self_collection' ? 'selected' : ''); ?>>Self Collection</option>
                    <option value="delivery" <?php echo ($delivery_type == 'delivery' ? 'selected' : ''); ?>>Delivery</option>

                </select>

            </div>
            <div class="col-4" style="margin-top:15px">
                <label>Estimated Delivery Date</label>
                <input type="date" class="form-control" id="d_delivery_date" name="d_delivery_date" value="<?php echo ($delivery_date) ? date('Y-m-d', strtotime($delivery_date)) : ''; ?>">
            </div>
            <div class="col-md-4" id="u_delivery_time" style="margin-top:15px;">
                <label>Delivery Time</label>
                <input type="text" name="u_delivery_time" class="form-control" value="<?php echo $delivery_time ?>">
                <!-- <select class="form-control" name="u_delivery_time">
                    <option value="">Select Delivery Time</option>
                    <option value="morning" <?php //echo ($delivery_time == 'morning' ? 'selected' : ''); 
                                            ?>>Morning</option>
                    <option value="afternoon" <?php //echo ($delivery_time == 'afternoon' ? 'selected' : ''); 
                                                ?>>Afternoon</option>

                </select> -->

            </div>
        </div>
        <div class="row">
            <div class="col-md-4" style="margin-top:15px">
                <label>Send By</label>
                <select class="form-control" name="u_send_by">
                    <option value="">Select Send By</option>
                    <option value="Email" <?php echo $send_by == 'Email' ? 'selected' : ''; ?>>Email</option>
                    <option value="WhatsApp" <?php echo $send_by == 'WhatsApp' ? 'selected' : ''; ?>>WhatsApp</option>
                    <option value="WhatsApp / Email" <?php echo $send_by == 'WhatsApp / Email' ? 'selected' : ''; ?>>WhatsApp / Email</option>

                </select>

            </div>
            <div class="col-md-4" style="margin-top:15px">
                <label>Send Date</label>
                <input type="date" name="u_send_date" class="form-control date" value="<?php echo ($send_date) ? date('Y-m-d', strtotime($send_date)) : ''; ?>">

            </div>
            <div class="col-md-4" style="margin-top:15px">
                <label>PO Date</label>
                <input type="date" name="po_dates1" class="form-control date" value="<?php echo date('Y-m-d', strtotime($podates)) ?>">
                <input type="hidden" name="old_po_dates1" value="<?php echo date('Y-m-d', strtotime($podates)) ?>">

            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Supplier Name</label>
                <!-- <input type="hidden" name="sup_name" id="sup_name"> -->
                <?php $all_suppliers = $this->db->get('xin_suppliers')->result(); ?>
                <input type="hidden" name="old_supplier_id" value="<?php echo $supplier_id ?>">
                <select class="form-control" id="sup1" name="name_supplier1" class="form-control" data-plugin="select_hrm" data-placeholder="Select Supplier" onchange="getProductbySup1(this.value)">
                    <option>Select</option>
                    <?php foreach ($all_suppliers as $suppliers) { ?>
                        <option value="<?php echo $suppliers->supplier_id ?>" <?php if ($supplier_id == $suppliers->supplier_id) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $suppliers->supplier_name ?></option>
                    <?php } ?>
                </select>

            </div>
            <div class="col-md-3">
                <label>Supplier Billing Address</label>
                <select class="form-control" id="sup_billing1" name="supplier_billing1" class="form-control" data-plugin="select_hrm" data-placeholder="Select Billing Address">

                </select>

            </div>
            <div class="col-md-4">
                <label>Supplier Reference</label>
                <input type="text" class="form-control" id="sup_ref4" name="sup_ref4" value="<?php echo ($sup_ref) ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label for="terms">Payment Terms</label>
                <?php $term = $this->db->get('xin_payment_term')->result() ?>
                <select name="payment_term2" class="form-control" data-plugin="select_hrm" data-placeholder="Terms">
                    <option value="">Select</option>
                    <?php foreach ($term as $terms) { ?>
                        <option value="<?php echo $terms->payment_term ?>"><?php echo $terms->payment_term ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" style="margin-top:15px">
                <label for="terms">Note:-</label>
                <textarea name="u_note" id="u_note" class="form-control" cols="30" rows="10" style="height:fit-content;"><?php echo isset($note) ? htmlspecialchars($note) : ''; ?></textarea>
            </div>
        </div>

        <!-- CKEditor Script -->

        <script>
            ClassicEditor
                .create(document.querySelector('#u_note'))
                .catch(error => {
                    console.error(error);
                });
        </script>

        <div class="row">
            <div class="col-md-6" style="margin-top:15px">
                <textarea name="amendable1" class="form-control" cols="30" rows="3"><?php echo $amendable; ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="p-20">
                <label>Orderline for Purchase Department</label>
                <table class="table my-3" id="vendor_items_table1">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="AddItem1" style="overflow-y:scroll">

                        <button type="button" style="border-radius:50px; padding: 5px;" class="btn-sm btn-success" id="addProductButton1">Add Product</button>
                        <button type="button" style="border-radius:50px; padding: 5px;" class="btn-sm btn-info" id="addBlankLineButton1">Add Blank Line</button>
                        <button type="button" style="border-radius:50px; padding: 5px;" class="btn-sm btn-warning" id="addImageButton1">Add Image</button>
                    </tbody>

                </table>
            </div>

        </div>
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <label>Sub Total</label>
                <input type="text" class="form-control" id="sub_t1" name="sub_t1" readonly>
                <input type="checkbox" id="inclusive_gst2" name="inclusive_gst2" <?php if ($inclusive_gst == 'on') {
                                                                                        echo "checked";
                                                                                    } ?>>
                <label for="inclusive_gst2">Inclusive GST</label><br>
                <?php $def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result() ?>

                <div id="gst_box2">
                    <label>GST</label>
                    <select class="form-control" id="order_gst3" name="order_gst3">
                        <option>Select</option>
                        <?php $all_gst = $this->db->get('xin_gst')->result();
                        foreach ($all_gst as $gst1) { ?>
                            <option value="<?php echo $gst1->gst ?>" <?php if ($gst1->gst == $gst) {
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



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closee"><?php echo $this->lang->line('xin_close'); ?></button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
        <?php echo form_close(); ?>
        <script>
            // Handle product dropdown selection
            function handleProductChange(selectElement, rowId) {
                var selectedText = $(selectElement).find("option:selected").text();
                if (selectedText && selectedText !== "Select product") {
                    $(selectElement).hide();
                    $(selectElement).siblings('.product-text').text(selectedText).show();
                    getProductDetail($(selectElement).val(), rowId);
                }
            }

            // Show select dropdown when clicking on the product name span
            $(document).on('click', '.product-text', function() {
                var rowId = $(this).data('id');
                $(this).hide();
                $(this).siblings('.product-select').show().focus();
            });

            // Convert input fields into spans after losing focus
            $(document).on('focusout', '.editable-input', function() {
                var inputValue = $(this).val().trim();
                if (inputValue === "") {
                    inputValue = $(this).attr("placeholder"); // Default placeholder if empty
                }
                $(this).hide();
                $(this).siblings('.editable-span').text(inputValue).show();
            });

            // Show input field when clicking on span
            $(document).on('click', '.editable-span', function() {
                $(this).hide();
                $(this).siblings('.editable-input').show().focus();
            });

            // Hide select dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.product-text, .product-select, .editable-span, .editable-input').length) {
                    $(".product-select").each(function() {
                        if ($(this).is(":visible")) {
                            var selectedText = $(this).find("option:selected").text();
                            if (!selectedText || selectedText === "Select product") {
                                selectedText = "Select product";
                            }
                            $(this).hide();
                            $(this).siblings('.product-text').text(selectedText).show();
                        }
                    });

                    $(".editable-input").each(function() {
                        if ($(this).is(":visible")) {
                            var inputValue = $(this).val().trim();
                            if (inputValue === "") {
                                inputValue = $(this).attr("placeholder");
                            }
                            $(this).hide();
                            $(this).siblings('.editable-span').text(inputValue).show();
                        }
                    });
                }
            });
            $(document).ready(function() {
                loadPOData(<?php echo $sup_bill_id; ?>);
                // Trigger task loading on milestone change
                $("#milestone_id1").on('change', function() {
                    loadTasks($("#project1").val(), $(this).val());
                });

                // Initial load on page load to set pre-selected task
                loadTasks($("#project1").val(), $("#milestone_id1").val());
            });
            // Function to fetch and populate tasks based on the selected milestone
            function loadTasks(project_id, milestone_id) {
                var selectedTask = "<?php echo $description_name; ?>"; // Holds the pre-selected task from the database

                if (project_id && milestone_id) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('admin/purchase/get_tasks_by_milestone/'); ?>" + project_id + "/" + milestone_id,
                        dataType: "json",
                        success: function(taskData) {
                            if (taskData.length > 0) {
                                // Create options for tasks based on fetched data
                                var taskOptions = taskData.map(function(item) {
                                    var option = $("<option></option>")
                                        .attr("value", item.task_id) // Assuming 'task_id' is the ID field
                                        .text(item.task_title); // Assuming 'task_name' is the name field

                                    // Check if the task name matches selectedTask to set it as selected
                                    if (selectedTask == item.task_id) {
                                        option.attr("selected", "selected");
                                    }
                                    return option;
                                });

                                // Clear existing options and append new ones
                                $("#task_id1").empty().append('<option value="">Select Task</option>').append(taskOptions);
                            } else {
                                // Display a message if no tasks are available for the selected milestone
                                $("#task_id1").empty().append('<option value="">No tasks available</option>');
                            }
                        },
                        error: function() {
                            toastr.error("Error retrieving tasks for the selected milestone");
                        }
                    });
                } else {
                    // If no milestone is selected, reset the task dropdown
                    $("#task_id1").empty().append('<option value="">Select Task</option>');
                }
            }
        </script>
        <script>
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

            function loadPOData(supbill_id) {
                var img_url = '<?php echo base_url('uploads/purchase_order/') ?>';
                var base_url = '<?php echo base_url(); ?>';
                $.ajax({
                    url: "<?php echo base_url('admin/purchase/get_order_line/') ?>" + "<?php echo $supplier_id; ?>" + "/" + "<?php echo $_GET['purchase_order_id']; ?>",
                    type: "POST",
                    success: function(response) {                       
                        globalResponse = response;
                        $("#terms1").val(response.record[0].terms).trigger("change");
                        for (var i = 0; i < response.record.length; i++) {

                            // Auto-select payment term if supplier_terms is available
                            if (response.record[i].payment_term) {
                                var paymentTermSelect = $("select[name='payment_term2']");
                                // Set the value and trigger change
                                paymentTermSelect.val(response.record[i].payment_term).trigger('change');
                            }
                        }


                        $("#vendor_items_table1 tbody").empty();

                        response.record.forEach(function(record, index) {
                            let row = ""; // Initialize row variable to hold the generated HTML for each record

                            if (record.type == 'product') {
                                // Template for 'product' type
                                let selectedProduct = response.products.find(p => p.product_id == record.prd_id);
                                let selectedProductName = selectedProduct ? selectedProduct.product_name : 'Select Product';
                                row = `
                                        <tr>
                                            <td>${index + 1}</td>
                                           <td>
                                                <select name="u_item1[]" class="form-control item_name1 product-select" 
                                                        id="u_item1_${index + 1}" 
                                                        onchange="handleProductChange(this.value, ${index + 1});"
                                                        style="display:none;">
                                                        <option value="">Select Product</option>
                                                    ${response.products.map(product => `
                                                        <option value="${product.product_id}" ${product.product_id == record.prd_id ? "selected" : ""}>
                                                            ${product.product_name}
                                                        </option>
                                                    `).join('')}
                                                </select>
                                                <span class="product-text" 
                                                    id="product_display_${index + 1}"                                                     
                                                    style="cursor:pointer;" 
                                                    title="Click to edit">
                                                    ${selectedProductName}
                                                </span>
                                                <img id="prd_img2_${index + 1}" style="width:150px"/>                                            
                                                <input type="hidden" name="product_color1[]" id="product_color1_${index + 1}">
                                                <input type="hidden" name="u_color1[]" id="color_names1_${index + 1}">
                                                <input type="hidden" value="product" name="e_u_type[]" id="e_u_type_${index + 1}">
                                                <input type="hidden" value="1" name="image_status[]" id="image_status_${index + 1}">
                                                <input type="file" hidden name="e_u_b_img[]" id="e_u_b_img_${index + 1}" >
                                                <input type="hidden" name="u_img_description[]" id="u_img_description_${index + 1}">
                                                <input type="file" hidden name="e_u_a_img[]" id="e_u_a_img_${index + 1}" >                   
                                            </td>                                                  
                                            <td>
                                            <span class="editable-span" id="unit1_${index + 1}">${record.unit}</span>
                                            <input type="text" name="unit1[]" id="unit1_${index + 1}" class="form-control editable-input" value="${record.unit}" style="display:none;">
                                            </td>
                                            <td>
                                                <span class="editable-span">${record.prd_price}</span>
                                                <input type="text" name="rate1[]" id="rate1_${index + 1}" class="form-control editable-input" value="${record.prd_price}" oninput="debounceGetTotal1(${index + 1})" style="display:none;">
                                            </td>
                                            <td>
                                                <span class="editable-span">${record.prd_qtn}</span>
                                                <input type="text" name="quantity1[]" id="quantity1_${index + 1}" class="form-control editable-input" value="${record.prd_qtn}" oninput="debounceGetTotal1(${index + 1})" style="display:none;">
                                            </td>
                                            <td>
                                                <input type="text" name="total1[]" id="total1_${index + 1}" class="form-control" readonly>
                                            </td>
                                            <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                                        </tr>
                                    `;
                            }

                            if (record.type === 'blank') {
                                // Template for 'blank' type
                                row = `
                                                <tr>
                                                    <td>${index + 1}</td>
                                                    <td>

                                            <span class="editable-span">${record.description}</span>

                                                        <input type='text' class='form-control editable-input' id="u_item1_${index + 1}" name='u_item1[]' value='${record.description}' style="display:none;">
                                                        <input type="hidden" value="blank" name="e_u_type[]" id="e_u_type_${index + 1}">
                                    <input type="hidden" value="1" name="image_status[]" id="image_status_${index + 1}">

                                                        <input type="hidden" name="product_color1[]" id="product_color1_${index + 1}">
                                                        <input type="hidden" name="u_color1[]" id="color_names1_${index + 1}">
                                                        <input type="file" hidden name="e_u_b_img[]" id="e_u_b_img_${index + 1}" >
                                                        <input type="file" hidden name="e_u_a_img[]" id="e_u_a_img_${index + 1}" >                   
                                                        <input type="hidden" name="u_img_description[]" id="u_img_description_${index + 1}">
                                                    </td>
                                                     <td>
                                            <span class="editable-span">${record.unit}</span>
                                            <input type="text" name="unit1[]" id="unit1_${index + 1}" style="display:none;" class="form-control editable-input" value="${record.unit}">
                                            </td>
                                            <td>
                                                <span class="editable-span">${record.prd_price}</span>
                                                <input type="text" name="rate1[]" id="rate1_${index + 1}" style="display:none;" class="form-control editable-input" value="${record.prd_price}" oninput="debounceGetTotal1(${index + 1})">
                                            </td>
                                            <td>
                                                <span class="editable-span">${record.prd_qtn}</span>
                                                <input type="text" name="quantity1[]" id="quantity1_${index + 1}" style="display:none;" class="form-control editable-input" value="${record.prd_qtn}" oninput="debounceGetTotal1(${index + 1})">
                                            </td>
                                            <td>
                                                <input type="text" name="total1[]" id="total1_${index + 1}" class="form-control" readonly>
                                            </td>
                                                    <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                                                </tr>
                                            `;
                            }
                            if (record.type === 'image') {
                                // Template for 'image' type
                                row = `
                                                <tr>
                                                    <td>${index + 1}</td>
                                                    <td>                                                    
                                                        <img src="${img_url +'/'+ record.b_img}" style="width:100px; height:100px;" alt="Image B">
                                                        <input type="file" hidden  name="e_u_b_img[]" id="e_u_b_img_${index + 1}" value="1">
                                                        
                                                        <input type="hidden" value="image" name="e_u_type[]" id="e_u_type_${index + 1}">
                                                        <input type="hidden" value="1" name="image_status[]" id="image_status_${index + 1}">

                                                        <input type="hidden" name="u_item1[]" id="u_item1_${index + 1}">

                                                        
                                                        <input type="hidden" name="unit1[]" id="unit1_${index + 1}" >
                                                        <input type="hidden" name="rate1[]" id="rate1_${index + 1}"  oninput="debounceGetTotal1(${index + 1})">
                                                        <input type="hidden" name="quantity1[]" id="quantity1_${index + 1}"  oninput="debounceGetTotal1(${index + 1})">
                                                        <input type="hidden" name="total1[]" id="total1_${index + 1}"  readonly>
                                                        <input type="hidden" value="${record.b_img}" name="old_b_img[]" id="old_b_img_${index + 1}">

                                                    </td>
                                                    <td colspan="3">
                                                <span class="editable-span">${record.img_description}</span>

                                                        <textarea rows="5" class="form-control editable-input" name="u_img_description[]" id="u_img_description_${index + 1}" style="display:none;">${record.img_description}</textarea>
                                                    </td>
                                                    <td>
                                                        <img src="${img_url +'/'+ record.a_img}" style="width:100px; height:100px;" alt="Image A" >
                                                        <input type="hidden" value="${record.a_img}" name="old_a_img[]" id="old_a_img_${index + 1}">
                                                        <input type="file" hidden name="e_u_a_img[]" id="e_u_a_img_${index + 1}" >
                                                                                                                            
                                                    </td>
                                                    <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                                                    
                                                </tr>
                                            `;



                            }

                            // Append the generated row
                            $("#vendor_items_table1 tbody").append(row);

                            // Call the updateRowTotal1 function to calculate the total for the row
                            updateRowTotal1($(`#quantity1_${index + 1}`));
                        });
                        // Calculate subtotals and totals after rows are appended
                        calculateSubTotal1();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                        alert("An error occurred while fetching data. Please try again.");
                    }
                });
                // Second AJAX: Fetch supplier billing data
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() . 'admin/purchase/get_sup_product/'; ?>" + "<?php echo $supplier_id; ?>",
                    success: function(data) {
                        var billing_data = jQuery.parseJSON(data);
                        

                        var targetSelect1 = $("#sup_billing1"); // Refers to the billing dropdown

                        // Empty the dropdown before adding new options
                        targetSelect1.empty();

                        // Add a default option
                        targetSelect1.append(`<option value="">Select Billing Address</option>`);

                        // Pre-fetch the PHP variable for selected ID
                        var selectedBillId = supbill_id;

                        // Populate the dropdown with billing addresses
                        billing_data.billing.forEach((bill) => {
                            targetSelect1.append(
                                `<option value="${bill.bill_id}" ${bill.bill_id === selectedBillId ? 'selected' : ''}>${bill.address}</option>`
                            );
                        });
                    },
                    error: function() {
                        toastr.error("Failed to fetch supplier billing data.");
                    }
                });

            }



            // Add new Product row
            $("#addProductButton1").click(function() {

                var rowCount = $("#vendor_items_table1 tbody tr").length + 1;
                getProductbySup1(<?php echo $supplier_id; ?>, rowCount);
                $("#vendor_items_table1 tbody").append(`
                            <tr>
                                <td>${rowCount}</td>
                                <td>
                                    <select name="u_item1[]" id="u_item1_${rowCount}" class="form-control item_name1 product-select" required onchange="getProductQtn3(this.value, ${rowCount})" >                                        
                                      <option value="">Select product</option>
                                            <?php foreach ($all_items as $product) {
                                                echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                            } ?>
                                    </select>                                    
                                    <img id="prd_img1_${rowCount}" style="width:150px"/><br>
                                    <span class="product-text" data-id="${rowCount}" style="cursor:pointer;" title="Click to edit">Select product</span>                                    
                                    <input type="hidden" value="product" name="e_u_type[]" id="e_u_type_${rowCount}">
                                    <input type="hidden" value="1" name="image_status[]" id="image_status_${rowCount}">                                                                        
                                    <input type="hidden" name="e_u_a_img[]" id="e_u_a_img_${rowCount}">                   
                                    <input type="hidden" name="e_u_b_img[]" id="e_u_b_img_${rowCount}">
                                    <input type="hidden" name="u_img_description[]" id="u_img_description_${rowCount}">
                                </td>                                                  
                                <td><input type="text" name="unit1[]" id="unit1_${rowCount}" class="form-control"></td>
                                <td><input type="text" name="rate1[]" id="rate1_${rowCount}" class="form-control" oninput="debounceGetTotal1(${rowCount})"></td>
                                <td><input type="text" name="quantity1[]" id="quantity1_${rowCount}" class="form-control" oninput="debounceGetTotal1(${rowCount})"></td>
                                <td><input type="text" name="total1[]" id="total1_${rowCount}" class="form-control" readonly></td>
                                <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                            </tr>
                        `);
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() . 'admin/purchase/get_sup_product/'; ?>" + "<?php echo $supplier_id; ?>",
                    success: function(data) {
                        var billing_data = jQuery.parseJSON(data);
                        

                        var targetSelect1 = $("#sup_billing1"); // Refers to the billing dropdown

                        // Empty the dropdown before adding new options
                        targetSelect1.empty();

                        // Add a default option
                        targetSelect1.append(`<option value="">Select Billing Address</option>`);

                        // Pre-fetch the PHP variable for selected ID
                        var selectedBillId = <?php echo $sup_bill_id ?>;

                        // Populate the dropdown with billing addresses
                        billing_data.billing.forEach((bill) => {
                            targetSelect1.append(
                                `<option value="${bill.bill_id}" ${bill.bill_id === selectedBillId ? 'selected' : ''}>${bill.address}</option>`
                            );
                        });
                    },
                    error: function() {
                        toastr.error("Failed to fetch supplier billing data.");
                    }
                });
            });

            // Add new Blank row
            $("#addBlankLineButton1").click(function() {

                var rowCount = $("#vendor_items_table1 tbody tr").length + 1;

                $("#vendor_items_table1 tbody").append(`
                            <tr>
                                <td>${rowCount}</td>
                                <td>
                                    <textarea class='form-control' id="u_item1_${rowCount}" name='u_item1[]'></textarea>
                                    <input type="hidden" value="blank" name="e_u_type[]" id="e_u_type_${rowCount}">
                                    <input type="hidden" value="1" name="image_status[]" id="image_status_${rowCount}">
                                    <input type="hidden" name="e_u_a_img[]" id="e_u_a_img_${rowCount}">                   
                                    <input type="hidden" name="e_u_b_img[]" id="e_u_b_img_${rowCount}">
                                    <input type="hidden" name="u_img_description[]" id="u_img_description_${rowCount}">
                                </td>                                                  
                                <td><input type="text" name="unit1[]" id="unit1_${rowCount}" class="form-control"></td>
                                <td><input type="text" name="rate1[]" id="rate1_${rowCount}" class="form-control" oninput="debounceGetTotal1(${rowCount})"></td>
                                <td><input type="text" name="quantity1[]" id="quantity1_${rowCount}" class="form-control" oninput="debounceGetTotal1(${rowCount})"></td>
                                <td><input type="text" name="total1[]" id="total1_${rowCount}" class="form-control" readonly></td>
                                <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>
                            </tr>
                        `);
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() . 'admin/purchase/get_sup_product/'; ?>" + "<?php echo $supplier_id; ?>",
                    success: function(data) {
                        var billing_data = jQuery.parseJSON(data);
                        
                        var targetSelect1 = $("#sup_billing1"); // Refers to the billing dropdown
                        // Empty the dropdown before adding new options
                        targetSelect1.empty();
                        // Add a default option
                        targetSelect1.append(`<option value="">Select Billing Address</option>`);
                        // Pre-fetch the PHP variable for selected ID
                        var selectedBillId = <?php echo $sup_bill_id ?>;
                        // Populate the dropdown with billing addresses
                        billing_data.billing.forEach((bill) => {
                            targetSelect1.append(
                                `<option value="${bill.bill_id}" ${bill.bill_id === selectedBillId ? 'selected' : ''}>${bill.address}</option>`
                            );
                        });
                    },
                    error: function() {
                        toastr.error("Failed to fetch supplier billing data.");
                    }
                });
            });
            // Add new Image row
            $("#addImageButton1").click(function() {
                var rowCount = $("#vendor_items_table1 tbody tr").length + 1;
                // Template for 'image' type
                $("#vendor_items_table1 tbody").append(`
                    <tr>
                        <td>${rowCount}</td>
                        <td>                                                    
                            <input type="file" name="e_u_b_img[]" id="e_u_b_img_${rowCount}" style="width:100px; height:100px;">
                            <input type="hidden" value="image" name="e_u_type[]" id="e_u_type_${rowCount}">
                            <input type="hidden" value="0" name="image_status[]" id="image_status_${rowCount}">
                            <input type="hidden" name="u_item1[]" id="u_item1_${rowCount}">                                
                            <input type="hidden" name="unit1[]" id="unit1_${rowCount}" >
                            <input type="hidden" name="rate1[]" id="rate1_${rowCount}"  oninput="debounceGetTotal1(${rowCount})">
                            <input type="hidden" name="quantity1[]" id="quantity1_${rowCount}"  oninput="debounceGetTotal1(${rowCount})">
                            <input type="hidden" name="total1[]" id="total1_${rowCount}"  readonly>
                        </td>
                        <td colspan="3">
                            <textarea rows="5" class="form-control " name="u_img_description[]" id="u_img_description_${rowCount}"></textarea>
                        </td>
                        <td><input type="file" name="e_u_a_img[]" id="e_u_a_img_${rowCount}" style="width:100px; height:100px;"></td>
                        <td><button type="button" class="btn btn-danger remove-input-field">Remove</button></td>                        
                    </tr>
                `);
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() . 'admin/purchase/get_sup_product/'; ?>" + "<?php echo $supplier_id; ?>",
                    success: function(data) {
                        var billing_data = jQuery.parseJSON(data);
                        

                        var targetSelect1 = $("#sup_billing1"); // Refers to the billing dropdown

                        // Empty the dropdown before adding new options
                        targetSelect1.empty();

                        // Add a default option
                        targetSelect1.append(`<option value="">Select Billing Address</option>`);

                        // Pre-fetch the PHP variable for selected ID
                        var selectedBillId = <?php echo $sup_bill_id ?>;

                        // Populate the dropdown with billing addresses
                        billing_data.billing.forEach((bill) => {
                            targetSelect1.append(
                                `<option value="${bill.bill_id}" ${bill.bill_id === selectedBillId ? 'selected' : ''}>${bill.address}</option>`
                            );
                        });
                    },
                    error: function() {
                        toastr.error("Failed to fetch supplier billing data.");
                    }
                });

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



            // Initial toggle for GST inputs
            toggleGSTInput2();




            function debounceGetTotal1(number) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    getTotal1(number);
                }, 2000); // 300ms delay
            }

            function getTotal1(number) {
                var qtnty = parseFloat($('#quantity1_' + number).val());
                var price = parseFloat($('#rate1_' + number).val());
                var gt = 0;

                if (!isNaN(qtnty) && !isNaN(price)) {
                    gt = price * qtnty;
                }

                $('#total1_' + number).val(gt.toFixed(2));

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
                var gst = parseFloat($("#order_gst3").val()) || 0;
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
            // Update row total
            function updateRowTotal1(element) {
                var $row = $(element).closest('tr');
                var rate = parseFloat($row.find("input[name='rate1[]']").val()) || 0;
                var quantity = parseFloat($row.find("input[name='quantity1[]']").val()) || 0;
                var total = quantity * rate;
                $row.find("input[name='total1[]']").val(total.toFixed(2));
                calculateSubTotal1();
            }

            function getProjectAddress1() {
                var prj_id = $('#project1').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() . 'admin/purchase/get_project_details/'; ?>" + prj_id,
                    data: JSON,
                    success: function(data) {
                        var product_data = jQuery.parseJSON(data);
                        if (product_data.length > 0 && product_data[0].project_address !== null) {
                            $("#s_add1").text(product_data[0].project_address);
                        } else {
                            $("#s_add1").empty();
                            handleAddressNotFound();
                        }
                    },
                    error: function() {
                        $("#s_add1").empty();

                        handleAddressNotFound();
                    }
                });
            }

            function handleAddressNotFound() {
                $("#s_add").text('');

                toastr.error("Project Address Not Found");
            }
        </script>
        <script>
            $(document).ready(function() {
                $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
                $('[data-plugin="select_hrm"]').select2({
                    width: "100%"
                });

                <?php if ($status == "Rejected") { ?>
                    $("#reason_div").show();
                <?php } else {  ?>
                    $("#reason_div").hide();

                <?php } ?>

                $("#status").change(function() {
                    if ($(this).val() == "Rejected") {
                        $("#reason_div").show();
                    } else {
                        $("#reason_div").hide();
                    }
                });

                $('.e_date').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    format: 'dd-mm-yyyy',
                    yearRange: '1900:' + (new Date().getFullYear() + 10),
                });

            });
            $(document).on('click', '.remove-input-field', function() {
                $(this).parents('tr').remove();
            });


            function getProductQtn3(id, number) {
                var supplier_id = $('#sup1').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() . 'admin/purchase/get_product_qtn/'; ?>" + id + "/" + supplier_id,
                    data: JSON,
                    success: function(data) {
                        var product_data = jQuery.parseJSON(data);
                        if (product_data && product_data.length > 0) {
                            $("#unit1_" + number).val(product_data[0].std_uom || '');
                            $("#rate1_" + number).val(product_data[0].supplier_item_price);
                            $("#prd_img2_" + number).attr("src", "<?php echo base_url() . 'uploads/product/'; ?>" + product_data[0].prd_img);
                            $("#prd_img1_" + number).attr("src", "<?php echo base_url() . 'uploads/product/'; ?>" + product_data[0].prd_img);
                        } else {
                            $("#unit1_" + number).val('');
                            $("#rate1_" + number).val('');
                            $("#prd_img2_" + number).val('');
                            $("#prd_img1_" + number).val('');
                        }



                    },
                    error: function() {
                        toastr.error("Supplier/Price Not Found");
                    }
                });
            }

            function getProductbySup1(supid, num) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() . 'admin/purchase/get_sup_product/'; ?>" + supid,
                    data: {
                        supplier_id: supid
                    },
                    success: function(data) {
                        var product_data = jQuery.parseJSON(data);
                        // Populate Product Dropdown
                        var targetSelect = $("#u_item1_" + num);
                        targetSelect.empty(); // Clear existing options

                        targetSelect.append(`<option value="">Select Product</option>`);

                        // Add options for products
                        product_data.items.forEach((item) => {
                            targetSelect.append(`<option value="${item.product_id}">${item.product_name}</option>`);
                        });

                        // Auto-select Payment Term
                        if (product_data.items.length > 0 && product_data.items[0].supplier_terms) {
                            var paymentTermSelect = $("select[name='payment_term2']");
                            paymentTermSelect.val(product_data.items[0].supplier_terms).trigger('change');
                        }

                        // Populate Billing Address Dropdown
                        var targetSelect1 = $("#sup_billing1");
                        targetSelect1.empty(); // Clear existing options

                        // Add default option for Billing Address
                        targetSelect1.append(`<option value="">Select Billing Address</option>`);

                        // Add options for billing addresses
                        product_data.billing.forEach((bill) => {
                            targetSelect1.append(`<option value="${bill.bill_id}">${bill.address}</option>`);
                        });
                    },
                    error: function() {
                        toastr.error("Supplier Not Found");
                    }
                });
            }
        </script>

        <script>
            $("#edit_purchase_order").submit(function(e) {

                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append('is_ajax', '1');
                fd.append('edit_type', 'edit_purchase_order');

                e.preventDefault();
                $('.save').prop('disabled', true);
                $('.icon-spinner3').show();
                $.ajax({
                    type: "POST",
                    url: base_url + "/update_order",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(JSON) {
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                            $('.icon-spinner3').hide();
                        } else {
                            // Close the modal after successful form submission
                            $('#closee').click();

                            var xin_table = $('#xin_table').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: "<?php echo site_url("admin/purchase/purchase_order_list") ?>",
                                    type: 'GET'
                                },
                            });
                            xin_table.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                            }, true);

                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.add-form').removeClass('in');
                            $('.select2-selection__rendered').html('--Select--');
                            $('.icon-spinner3').hide();
                            $('#xin-form')[0].reset(); // To reset form fields
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });
        </script>
    <?php } ?>