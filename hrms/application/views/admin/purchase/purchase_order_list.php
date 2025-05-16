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
        height: 100% !Important;
    }
</style>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('2902', $role_resources_ids)) { ?>

    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>
                    <?php echo $this->lang->line('xin_purchase_order'); ?></h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a>
                </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'purchase_order', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('user_id' => $session['user_id']); ?>
                    <?php echo form_open_multipart('admin/purchase/p_order_add', $attributes, $hidden);
                    ?>
                    <div class="form-body">

                        <div id="add_new">

                            <input type="hidden" name="type" value="new">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Employee</label>
                                    <select class="form-control" id="customer" name="name_customer" class="form-control" data-plugin="select_hrm" data-placeholder="Select Employee">
                                        <option>Select</option>
                                        <?php foreach ($all_customers as $users) { ?>
                                            <option value="<?php echo $users->user_id ?>"><?php echo $users->first_name . " " . $users->last_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Project Name</label>
                                    <select class="form-control" id="project" name="name_project" class="form-control" data-plugin="select_hrm" data-placeholder="Select Project" onchange="getProjectAddress()">
                                        <option>Select</option>
                                        <?php foreach ($all_projects as $project) { ?>
                                            <option value="<?php echo $project->project_id ?>"><?php echo $project->project_title ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Supplier Name</label>
                                    <input type="hidden" name="sup_name" id="sup_name">
                                    <select class="form-control" id="sup" name="name_supplier" class="form-control" data-plugin="select_hrm" data-placeholder="Select Supplier" onchange="getProductbySup(this.value,1)">
                                        <option>Select</option>
                                        <?php foreach ($all_suppliers as $suppliers) { ?>

                                            <option value="<?php echo $suppliers->supplier_id ?>"><?php echo $suppliers->supplier_name ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <label>Supplier Billing Address</label>
                                    <select class="form-control" id="sup_billing" name="sup_billing">
                                        <option value="">Select Billing Address</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Project Site Address</label>
                                    <select name="s_add" id="s_add" class="form-control">
                                        <option>Select Site Address</option>
                                    </select>
                                    <textarea class="form-control" rows="5" cols="80" name="other_s_add" id="other_s_add" class="form-control" style="display:none; margin-top:10px;"></textarea>

                                </div>
                                <div class="col-md-3">
                                    <label>Milestone</label>
                                    <select name="milestone_id" id="milestone_id" class="form-control">
                                        <option value="">Select Milestone</option>
                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <label>Task</label>
                                    <select name="task_id" id="task_id" class="form-control" data-plugin="select_hrm">

                                    </select>
                                </div>


                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <label>Supplier Reference</label>
                                    <input type="text" class="form-control" id="sup_ref23" name="sup_ref23">
                                </div>

                                <div class="col-md-3" style="margin-top:15px">
                                    <label>Delivery Type</label>
                                    <select class="form-control" name="delivery_type" id="delivery_type">
                                        <option value="">Select Delivery Type</option>
                                        <option value="self_collection">Self Collection</option>
                                        <option value="delivery">Delivery</option>

                                    </select>

                                </div>
                                <div class="col-md-3" style="margin-top:15px">
                                    <label>Estimated Delivery Date</label>
                                    <input type="date" name="delivery_date" class="form-control">

                                </div>
                                <div class="col-md-3" id="delivery_time" style="margin-top:15px">
                                    <label>Delivery Time</label>
                                    <input type="text" name="delivery_time" class="form-control">

                                    <!-- <select class="form-control" name="delivery_time">
                                        <option value="">Select Delivery Time</option>
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>

                                    </select> -->

                                </div>
                                <div class="col-md-3" style="margin-top:15px">
                                    <label>Send By</label>
                                    <select class="form-control" name="send_by">
                                        <option value="">Select Send By</option>
                                        <option value="Email">Email</option>
                                        <option value="WhatsApp">WhatsApp</option>
                                        <option value="WhatsApp / Email">WhatsApp / Email</option>
                                    </select>

                                </div>
                                <div class="col-md-3" style="margin-top:15px">
                                    <label>Send Date</label>
                                    <input type="date" name="send_date" class="form-control">

                                </div>
                                <div class="col-md-3" style="margin-top:15px">
                                    <label>PO Date</label>
                                    <input type="date" name="po_dates" class="form-control">

                                </div>
                                <div class="col-md-3" style="margin-top:15px">

                                    <label for="terms">Payment Terms</label>
                                    <?php $term = $this->db->get('xin_payment_term')->result() ?>
                                    <select name="payment_term" class="form-control" data-plugin="select_hrm" data-placeholder="Terms">
                                        <option value="">Select</option>
                                        <?php foreach ($term as $terms) { ?>
                                            <option value="<?php echo $terms->payment_term ?>"><?php echo $terms->payment_term ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

                            <div class="row">
                                <div class="col-md-6" style="margin-top:15px">
                                    <label for="terms">Note:-</label>
                                    <textarea name="important_note" id="important_note" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                            <script>
                                ClassicEditor
                                    .create(document.querySelector('#important_note'))
                                    .catch(error => {
                                        console.error(error);
                                    });
                            </script>




                            <div class="row">
                                <div class="col-md-6" style="margin-top:15px">
                                    <textarea name="amendable" class="form-control" cols="30" rows="3">To supply of the following items:-</textarea>
                                </div>
                            </div>

                            <div class="p-20">
                                <label>Orderline for Purchase Department</label>
                                <div class="table-responsive my-3 purchaseTable">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Unit of Measurement</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="AddItem" id="vendor_items_table1">
                                            <button type="button" style="border-radius:50px; padding: 5px;" class="btn-sm btn-success" id="addProductButton">Add Product</button>
                                            <button type="button" style="border-radius:50px; padding: 5px;" class="btn-sm btn-info" id="addBlankLineButton">Add Blank Line</button>
                                            <button type="button" style="border-radius:50px; padding: 5px;" class="btn-sm btn-warning" id="addImageButton">Add Image</button>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <label>Sub Total</label>
                                    <input type="text" class="form-control" id="sub_t" name="sub_t" readonly>
                                    <input type="checkbox" id="inclusive_gst" name="inclusive_gst">
                                    <label for="inclusive_gst">Inclusive GST</label><br>
                                    <?php $def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result() ?>

                                    <div id="gst_box">
                                        <label>GST</label>
                                        <select class="form-control" id="order_gst2" name="order_gst2">
                                            <option>Select</option>
                                            <?php $all_gst = $this->db->get('xin_gst')->result();
                                            foreach ($all_gst as $gst) { ?>
                                                <option value="<?php echo $gst->gst ?>" <?php if ($gst->gst == $def_gst[0]->d_gst) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $gst->gst ?></option>
                                            <?php } ?>
                                        </select>

                                        <label>GST Value</label>
                                        <input type="text" class="form-control" name="g_val" id="g_val">
                                    </div>
                                    <div id="gst_box1">
                                        <label>Inclusive GST Value (<?php echo $def_gst[0]->d_gst ?> %)</label>
                                        <input type="text" class="form-control" id="d_gst_i">
                                    </div>
                                    <!-- <label>Discount (%)</label>
                                    <input type="text" class="form-control" id="discount2" name="discount2"> -->

                                    <label>Total</label>
                                    <input type="text" class="form-control" id="t" name="t">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions box-footer" id="sub_btn">
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
            <?php echo $this->lang->line('xin_purchase_order'); ?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th><?php echo $this->lang->line('xin_action'); ?></th>
                        <th>MRF No.</th>
                        <th>MRF Date</th>
                        <th>PO No</th>
                        <th>PO Date</th>
                        <th>Vendor / Supplier Name</th>
                        <th>Description</th>
                        <th>Amount Before GST</th>
                        <th>GST</th>
                        <th>Amount Incl GST</th>
                        <th>Project Code</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    function getProjectAddress() {
        var prj_id = $('#project').val();
        $.ajax({
            url: "<?php echo base_url('admin/purchase/get_project_data_by_id/'); ?>" + prj_id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                projectData = response;

                const select = $('#s_add');
                select.empty();
                select.append('<option value="">Select Site Address</option>');

                response.forEach(function(item) {
                    select.append('<option value="' + item.project_site + '">' + item.project_site + '</option>');
                });

                select.append('<option value="others">Others</option>');

                // Optional: set default address and supervisor
                if (response.quotation_no && response.quotation_no.length > 0) {
                    $('#s_add').val(response.quotation_no[0].project_site);

                }


                $('#s_add').on('change', function() {
                    const selected = $(this).val();
                    if (selected == 'others') {
                        $('#other_s_add').show();
                    } else {
                        $('#other_s_add').hide();


                    }
                });

                // Trigger change manually to apply initial logic
                $('#s_add').trigger('change');
            },
            error: function() {
                alert('Failed to load site addresses.');
            }
        });
    }

    function handleAddressNotFound() {
        toastr.error("Address Not Found");
    }
</script>


<script>
    $(document).on('click', '#addProductButton', function() {
        var number = $('.AddItem tr').length;
        var item = number + 1;
        var supid = $('#sup').val();
        getProductbySup(supid);
        $('.AddItem').append(`
            <tr>
                <td>
                    <label>${item}</label>
                </td>
                <td>
                    <select class="form-control" name="product_id[]" id="product_${item}" onchange="getProductQtn2(this.value, ${item})">
                    </select>
                    <img id="prd_img_${item}" style="width:150px"/>
                    
                    <input type="hidden" value="product" name="u_type[]" id="u_type_${item}">                     
                    <input type="hidden"  name="u_des[]" id="u_des_${item}">
                    <input type="hidden" class="form-control" name="u_a_img[]" id="u_a_img_${item}">                   
                    <input type="hidden" class="form-control" name="u_b_img[]" id="u_b_img_${item}">
                </td>                
                <td>
                    <input class="form-control" type="text" name="u_unit[]" id="u_unit_${item}">
                </td>
                <td>
                    <input class="form-control" type="text" name="u_qty[]" id="u_2qty_${item}" oninput="debounceGetTotal(${item})">
                </td>
                <td>
                    <input type="text" name="u_price2[]" id="u_2price_${item}" class="form-control">
                </td>
                <td>
                    <input class="form-control" type="text"  name="u_gt[]" id="u_gt_${item}" readonly>
                </td>
                <td>
                    <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                </td>
            </tr>
        `);


    });

    $(document).on('click', '#addBlankLineButton', function() {
        var number = $('.AddItem tr').length;
        var item = number + 1;
        // var supid = $('#sup').val();
        // getProductbySup(supid, item);
        $('.AddItem').append(`
            <tr>
                <td>
                    <label>${item}</label>
                </td>
                <td>
                   <textarea class="form-control" rows="5" name="u_des[]" id="u_des_${item}"></textarea>
                    <input type="hidden" value="blank" name="u_type[]" id="u_type_${item}">
                  <input type="hidden" class="form-control" name="u_a_img[]" id="u_a_img_${item}">                   
                  <input type="hidden" class="form-control" name="u_b_img[]" id="u_b_img_${item}">
                
                    <input type="hidden" name="product_id[]" id="product_${item}">
                </td>                
                <td>
                    <input class="form-control" type="text" name="u_unit[]" id="u_unit_${item}">
                </td>
                <td>
                    <input type="text" name="u_price2[]" id="u_2price_${item}" class="form-control">
                </td>
                <td>
                    <input class="form-control" type="text" name="u_qty[]" id="u_2qty_${item}" oninput="debounceGetTotal(${item})">
                </td>
                <td>
                    <input class="form-control" type="text"  name="u_gt[]" id="u_gt_${item}" readonly>
                </td>
                <td>
                    <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                </td>
            </tr>
        `);
    });

    $(document).on('click', '#addImageButton', function() {
        var number = $('.AddItem tr').length;
        var item = number + 1;
        $('.AddItem').append(`
        <tr>
            <td>
                <label>${item}</label>
            </td>
            <td>
                <input type="hidden" value="image" name="u_type[]" id="u_type_${item}">                 
                <input type="hidden" name="u_unit[]" id="u_unit_${item}">
                <input type="hidden" name="u_price2[]" id="u_2price_${item}">
                <input type="hidden" name="u_qty[]" id="u_2qty_${item}">
                <input type="hidden" name="u_gt[]" id="u_gt_${item}" readonly>
                <input type="hidden" name="product_id[]" id="product_${item}">
                
                <input type="file" class="form-control img-input" name="u_b_img[]" id="u_b_img_${item}" data-preview="preview_b_${item}">
                <div class="img-preview" id="preview_b_${item}"></div>
            </td>                
            <td colspan="3">
                <textarea class="form-control" rows="5" name="u_des[]" id="u_des_${item}"></textarea>
            </td>
            <td>
                <input type="file" class="form-control img-input" name="u_a_img[]" id="u_a_img_${item}" data-preview="preview_a_${item}">
                <div class="img-preview" id="preview_a_${item}"></div>
            </td> 
            <td>
                <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
            </td>
        </tr>
    `);
    });


    $(document).on('change', '.img-input', function(event) {
        var input = event.target;
        var previewId = $(this).data('preview');
        var previewDiv = $('#' + previewId);
        previewDiv.html(''); // Clear previous preview

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.append(`
                <p><strong>File:</strong> ${input.files[0].name}</p>
                <img src="${e.target.result}" alt="Preview" style="max-width: 150px; margin-top: 5px; border:1px solid #ddd; padding:3px;">
            `);
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    function getProductbySup(supid, abc = 0) {
        // Get supplier ID and current row number

        var number = $('.AddItem tr').length;
        var num = number + 1;

        // Validate supplier ID
        if (!supid) {
            toastr.error("Please select a supplier.");
            return;
        }
        if (abc == 1) {
            // First AJAX: Fetch billing addresses
            $.ajax({
                type: "GET",
                url: "<?php echo base_url() . 'admin/purchase/get_sup_bill/'; ?>" + supid,
                success: function(data) {
                    var billing_data = jQuery.parseJSON(data);
                    console.log("Billing Data:", billing_data);

                    var targetSelect1 = $("#sup_billing");

                    // Empty and populate billing dropdown
                    targetSelect1.empty();
                    targetSelect1.append(`<option value="">Select Billing Address</option>`);
                    if (billing_data.billing && billing_data.billing.length > 0) {
                        billing_data.billing.forEach((bill) => {
                            targetSelect1.append(`<option value="${bill.bill_id}">${bill.address}</option>`);
                        });
                    } else {
                        toastr.warning("No billing addresses found for the supplier.");
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("Failed to fetch supplier billing data.");
                    console.error("Billing Error:", xhr, status, error);
                }
            });
        }


        // Second AJAX: Fetch products and payment terms
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/purchase/get_sup_product/'; ?>" + supid,
            data: {
                supplier_id: supid
            },
            success: function(data) {
                var product_data = jQuery.parseJSON(data);
                console.log("Product Data:", product_data);

                var targetSelect = $("#product_" + num);

                // Empty and populate product dropdown
                targetSelect.empty();
                targetSelect.append(`<option value="">Select Product</option>`);
                if (product_data.items && product_data.items.length > 0) {
                    product_data.items.forEach((item) => {
                        targetSelect.append(`<option value="${item.product_id}">${item.product_name}</option>`);
                    });
                } else {
                    toastr.warning("No products found for the supplier.");
                }


                // Auto-select payment term
                if (product_data.length > 0 && product_data[0].supplier_terms) {
                    $("select[name='payment_term']").val(product_data[0].supplier_terms).trigger('change');
                }
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch products or payment terms.");
                console.error("Product Error:", xhr, status, error);
            }
        });
    }
</script>

<script>
    let debounceTimer;

    function debounceGetTotal(number) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            getTotal(number);
        }, 2000); // 300ms delay
    }

    function getTotal(number) {
        var qtnty = parseFloat($('#u_2qty_' + number).val());
        var price = parseFloat($('#u_2price_' + number).val());
        var gt = 0;

        if (!isNaN(qtnty) && !isNaN(price)) {
            gt = price * qtnty;
        }

        $('#u_gt_' + number).val(gt.toFixed(2));

        calculateSubTotal();
    }

    function calculateSubTotal() {
        var subTotal = 0;
        $("input[name='u_gt[]']").each(function() {
            subTotal += parseFloat($(this).val()) || 0;
        });
        $("#sub_t").val(subTotal.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        var subTotal = parseFloat($("#sub_t").val()) || 0;
        var discountPercent = parseFloat($("#discount2").val()) || 0;
        var gst = parseFloat($("#order_gst2").val()) || 0;
        var gst1 = "<?php echo $def_gst[0]->d_gst ?>";
        var isInclusive = $("#inclusive_gst").is(":checked");

        // Convert discount percentage to actual discount amount
        var discount = (discountPercent / 100) * subTotal;

        // Calculate the discounted subtotal
        var discountedSubTotal = subTotal - discount;

        if (isInclusive) {
            console.log(gst1);
            console.log(100 + parseFloat(gst1));
            console.log(subTotal);
            gst1 = parseFloat(gst1);
            subTotal = parseFloat(subTotal);
            // Calculate GST value when GST is inclusive
            var gstValue = parseFloat((subTotal / (100 + gst1)) * gst1);

            $("#d_gst_i").val(gstValue.toFixed(2));
            // Update the total value to reflect the inclusive GST
            $("#t").val(discountedSubTotal.toFixed(2));
        } else {
            // Calculate GST value when GST is not inclusive
            var gstValue = discountedSubTotal * (gst / 100);
            var total = discountedSubTotal + gstValue;
            $("#g_val").val(gstValue.toFixed(2));
            $("#t").val(total.toFixed(2));
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

    $(document).on('click', '.remove-input-field', function() {
        $(this).closest('tr').remove();
        calculateSubTotal();
    });

    function getProductQtn2(id, number) {
        var supplier_id = $('#sup').val();


        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/purchase/get_product_qtn/'; ?>" + id + "/" + supplier_id,
            data: JSON,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);
                // console.log(product_data)
                $('#u_unit_' + number).val(product_data[0].std_uom);

                $("#u_2price_" + number).val(product_data[0].supplier_item_price);
                $("#prd_img_" + number).attr("src", "<?php echo base_url() . 'uploads/product/'; ?>" + product_data[0].prd_img);
            },
            error: function() {
                toastr.error("Price Not Found");
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        // $('#pr_div').hide();
        // $('#add_new').hide();
        // $('#sub_btn').hide();

        $('#add_new').show();
        $('#project').on('change', function() {
            var project_id = $('#project').val();
            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/get_quotation_from_project/'; ?>" + project_id,
                type: "POST",
                success: function(response) {
                    // Clear existing values
                    $('#qt_no').val('');
                    $('#qt_id').val('');
                    $('#milestone_id').empty(); // Clear the milestone dropdown
                    $("#client_id").val(response.quotation_no[0].bill_clientid).trigger('change');
                    let projectSum = parseFloat(response.quotation_no[0].project_sn);
                    $("#contract_sum").val(isNaN(projectSum) ? "0.00" : projectSum.toFixed(2));


                    // Check if quotation data exists
                    if (response.quotation_no && response.quotation_no.length > 0) {
                        $('#qt_no').val(response.quotation_no[0].quotation_no);
                        $('#qt_nos').val(response.quotation_no[0].quotation_no);
                        $('#qt_id').val(response.quotation_no[0].bill_estimateid);
                    } else {
                        toastr.error("No quotation found for this project.");
                    }

                    // Milestone ID-to-name mapping
                    const milestoneMapping = {
                        1: 'Preliminaries',
                        2: 'Insurances',
                        3: 'Schedule Of Works',
                        4: 'Plumbing & Sanitary',
                        5: 'Elec & Acmv',
                        6: 'External Works',
                        7: 'Pc & Ps Sums',
                        8: 'Others'
                    };

                    // Check if Milestone data exists
                    if (response.milestone_list && response.milestone_list.length > 0) {
                        let milestoneOptions = '<option value="">Select Milestone</option>';
                        $.each(response.milestone_list, function(index, milestone) {
                            const milestoneName = milestoneMapping[milestone.task_cat_id] || 'Unknown Milestone';
                            milestoneOptions += `<option value="${milestone.task_cat_id}">${milestoneName}</option>`;
                        });
                        $('#milestone_id').html(milestoneOptions);
                    } else {
                        toastr.error("No Milestone found for this project.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: ", error);
                    alert("An error occurred while fetching the quotation. Please try again later.");
                }
            });
        });

        $('#milestone_id').on('change', function() {
            var milestone_id = $('#milestone_id').val();
            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/get_task_from_milestone/'; ?>" + milestone_id + "/" + $('#project').val(),
                type: "POST",
                success: function(response) {
                    // Clear existing values                    
                    $('#task_id').empty(); // Clear the Task dropdown

                    // Check if Task data exists
                    if (response.task_list && response.task_list.length > 0) {
                        let taskOptions = '<option value="">Select Task</option>';
                        $.each(response.task_list, function(index, task) {
                            taskOptions += `<option value="${task.task_id}">${task.task_title}</option>`;
                        });
                        $('#task_id').html(taskOptions);
                    } else {
                        toastr.error("No Task found for this project.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: ", error);
                    alert("An error occurred while fetching the quotation. Please try again later.");
                }
            });
        });

        // $("#milestone_id").on('change', function() {
        //     // AJAX call to fetch tasks based on selected milestone
        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo base_url('admin/purchase/get_tasks_by_milestone/'); ?>" + $('#project').val() +"/"+ $('#milestone_id').val(),
        //         dataType: "json",
        //         success: function(taskData) {
        //             if (taskData.length > 0) {
        //                 // Clear existing options and add new ones based on the response
        //                 $("#task_id").empty();

        //                 // Populate task_id select box with options from taskData
        //                 $.each(taskData, function(index, item) {
        //                     var option = $("<option></option>")
        //                         .attr("value", item.id) // Assuming `task_id` is the identifier
        //                         .text(item.description)
        //                         .css({
        //                             "white-space": "normal",
        //                             "word-wrap": "break-word",
        //                             "width": "50%",
        //                             "display": "block",
        //                         }); // Assuming `task_name` is the display name
        //                     $("#task_id").append(option);

        //                 });
        //             } else {
        //                 toastr.error("No tasks found for this milestone");
        //                 $("#task_id").empty();

        //             }
        //         },
        //         error: function() {
        //             toastr.error("Error retrieving tasks for the selected milestone");
        //         }
        //     });
        // });

    });





    function getProductQtn(id, number) {
        var iid2 = $('#u_item_' + number).val();
        console.log(id, number)
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/purchase/get_product_qtn/'; ?>" + id + "/" + iid2,
            data: JSON,
            success: function(data) {
                console.log(data)
                var product_data = jQuery.parseJSON(data);

                $("#u_price_" + number).val(product_data[0].supplier_item_price);
                $("#prd_img_" + number).attr("src", "<?php echo base_url() . 'uploads/product/'; ?>" + product_data[0].prd_img);
            },
            error: function() {
                toastr.error("Price Not Found");
            }
        });
    }
</script>