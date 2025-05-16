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
<!-- <style>
    /* CSS to allow text wrapping within the select dropdown */
    #task_id, #milestone_id {
        white-space: normal; /* Allows text to wrap */
        width: 100%; /* Adjust width as needed */
        max-width: 300px; /* Set a max-width to control dropdown width */
        word-wrap: break-word; /* Break long words to the next line */
    }
</style> -->

<style>
    .invoice table th {
        border: 1px solid #000;
        text-align: center;
    }

    * {
        font-family: Arial, sans-serif;
        font-weight: bold !important;
    }


    .invoice-header {
        display: flex;
        gap: 10px;
        border-bottom: 1px solid #000;
    }

    .logo {
        width: 20%;
    }

    .detail {
        width: 60%;
        text-align: center;
    }

    .detail h4 {
        text-transform: uppercase;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .prv-status {
        float: right;
        width: 20%;
        text-align: right;
    }

    .invoice-main {
        display: flex;
        gap: 10px;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .invoice-main .left {
        width: 40%;
    }

    .invoice-main .center {
        width: 20%;
        text-align: center;
    }

    .invoice-main .right {
        width: 20%;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .gap-1 {
        gap: 5px;
    }

    table {
        width: 100%;
        border: 1px solid #000;
        border-collapse: collapse;
        text-wrap: wrap;
    }

    .table-dece {
        width: 500px;
    }

    .tfoot {
        border-top: 1px solid #000;
    }

    @media screen and (max-width:768px) {
        .invoice-header {
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            width: 100%;
            order: 2;
        }

        .detail {
            width: 100%;
            order: 3;
        }

        .prv-status {
            text-align: end;
            width: 100%;
            order: 1;
        }

        .invoice-main {
            flex-direction: column;
            justify-content: center;
        }

        .invoice-main .left {
            width: 100%;
        }

        .invoice-main .center {
            width: 100%;
        }

        .invoice-main .right {
            width: 100%;
        }

        .product-label {
            cursor: pointer;
            font-weight: bold;
        }


    }


    @media only screen and (max-width: 768px) {

        table th,
        table td {
            white-space: nowrap;
            border: none;
        }

        .form-body {
            overflow-x: auto;
            margin: -50px;
        }
    }

    @media screen and (max-width: 768px) {
        table.table td {
            display: block;
            width: 100%;
            text-align: left;
            border: none !important;
            margin-bottom: 10px;
        }

        table.table td label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        table.table td select,
        table.table td input {
            width: 100%;
        }

        table.table tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
        }
    }
</style>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('2902', $role_resources_ids) || in_array('8001', $role_resources_ids)) { ?>


    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h4 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>Material Requisition Form (MRF)</h4>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary">
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a> </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_purchase_requistion', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('user_id' => $session['user_id']); ?>
                    <?php echo form_open_multipart('', $attributes, $hidden); ?>
                    <div class="form-body">
                        <div class="invoice">
                            <div class="left">
                                <label>Project Name</label>
                                <select name="project_id" id="project_id" class="form-control">
                                    <option value="">Select Project</option>
                                    <?php foreach ($all_projects as $project) {
                                        echo "<option value=" . $project->project_id . ">" . $project->project_title . "</option>";
                                    } ?>
                                </select>
                                <label>Milestone</label>
                                <select name="milestone_id" id="milestone_id" class="form-control">

                                </select>
                                <label>Task</label>
                                <select name="task_id" id="task_id" class="form-control" data-plugin="select_hrm">

                                </select>
                            </div>
                            <br />
                            <div class="table-responsive">

                                <table style="width: 100%;" class="table">
                                    <tr>
                                        <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Material Requisition Form (MRF) (internal)</b></td>
                                        <td style="width: 33.33%;text-align:left;border-right:none;border:1px solid;">
                                            <select name="crane" id="crane" class="form-control">
                                                <option value="">Mode of Transport</option>
                                                <?php
                                                $transport = $this->db->get('mode_of_transport')->result();
                                                foreach ($transport as $t) {
                                                    echo "<option value='" . $t->mst_id . "'>" . $t->mst_title . "</option>";
                                                }
                                                ?>
                                                <option value="others">Others</option>
                                            </select>
                                            <div id="other_crane_box" style="display:none; margin-top:10px;">
                                                <input type="text" name="other_crane" id="other_crane" class="form-control" placeholder="Enter Mode of Transport">
                                            </div>
                                        </td>
                                        <td rowspan="6" style="width: 50%; border-top:#000;  border-right:none; text-align: center; vertical-align: middle;">
                                            <?php $proc_logo = $this->db->get('xin_quo')->result(); ?>
                                            <img src="<?php echo site_url('uploads/quo/' . $proc_logo[0]->logo4) ?>" class="img-fluid" width="300px" alt="">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Project Department-Purchasing Department</b></td>
                                        <td style="width: 33.33%;text-align:left; border:1px solid;"><b>MRF Date: <?php echo date('d-m-Y') ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 33.33%;text-align:left;border:1px solid;" colspan="2"><b>Site:</b>
                                            <select name="site_address" id="site_address" class="form-control">
                                                <option>Select Site Address</option>

                                            </select>
                                            <input type="text" name="other_site_address" id="other_site_address" class="form-control" style="display:none; margin-top:10px;">
                                        </td>
                                    </tr>
                                </table>
                                <table class="table">
                                    <tr>
                                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site4" name="check1[]" value="Storeroom No Stock. I have checked with Storeman"><label for="site4">Storeroom No Stock. I have checked with Storeman.</label></td>
                                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site5" name="check1[]" value="Supervisor has checked with Engineer before ordering"><label for="site5">Supervisor has checked with Engineer before ordering.</label></td>
                                    </tr>
                                    <tr>
                                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site6" name="check1[]" value="Please check Yishun Storeroom before you order"><label for="site6">Please check Yishun Storeroom before you order.</label></td>
                                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site7" name="check1[]" value="We have already checked with Boss to order"><label for="site7">We have already checked with Boss to order.</label></td>
                                    </tr>
                                </table>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <div style="text-align: left;">
                                    <a href="javascript:void(0)" class="btn btn-primary addButton" id="addButton1">Add Row</a>
                                    <table style="margin-top: 5px; margin-bottom: 5px;  height: fit-content; ">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Material/Tool</th>
                                                <th>Qty</th>
                                                <th>UOM</th>
                                                <th>Which Level?</th>
                                                <th>Where did you use?</th>
                                                <th>Name of Sub Con</th>
                                                <th>Purchase Order No.</th>
                                                <th>Delivery Order No.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="AddItem" id="vendor_items_table1" style=" border:1px solid;">
                                            <tr>
                                                <td style="min-width:30px">
                                                    <label>1<label>
                                                </td>
                                                <td style="min-width:350px">
                                                    <div class="product-select-container" id="product-container-1">
                                                        <select class="form-control product-select" id="product-select-1" onchange="selectProduct(this, 1)">
                                                            <option value="">Select product</option>
                                                            <?php foreach ($all_products as $product) {
                                                                echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                                            } ?>
                                                        </select>
                                                        <span class="product-label" id="product-label-1" style="display:none;" onclick="editProduct(1)"></span>
                                                    </div>
                                                    <input type="hidden" name="product_id[]" id="product-id-1" />
                                                </td>

                                                <td style="min-width:150px">
                                                    <input type="number" min="1" id="qty1" class="form-control" name="qty[]" placeholder="Qty">
                                                </td>
                                                <td style="min-width:150px">
                                                    <input type="text" id="uom1" name="uom[]" class="form-control">
                                                </td>
                                                <td style="min-width:150px">
                                                    <input type="text" id="level1" class="form-control" name="level[]">
                                                </td>
                                                <td style="min-width:150px">
                                                    <input type="text" id="use1" class="form-control" name="use[]">
                                                </td>
                                                <td style="min-width:150px">
                                                    <input type="text" id="sub_con1" class="form-control" name="sub_con[]">
                                                </td>
                                                <td style="min-width:150px">
                                                    <input type="text" id="po_no1" class="form-control" name="po_no[]">
                                                </td>
                                                <td style="min-width:150px">
                                                    <input type="text" id="do_no1" class="form-control" name="do_no[]">
                                                </td>
                                                <td>
                                                    <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <table class="table">
                                    <tr>
                                        <td style="width:70%;text-align:left;border:1px solid;">
                                            <label>Name of Supervisor who order:</label>
                                            <select class="form-control" name="supervisor" id="supervisor">
                                                <option>Select Supervisor</option>
                                                <?php foreach ($all_customers as $customer) {
                                                    echo "<option value='" . $customer->user_id . "' data-signature='" . $customer->signature . "'>" . $customer->first_name . ' ' . $customer->last_name . "</option>";
                                                } ?>
                                            </select>

                                            <br />
                                            <label> Sub-Contractor Company:</label>
                                            <select class="form-control" name="sub_contractor">
                                                <option>Select Sub-contractor</option>
                                                <?php foreach ($all_subcontractor as $subcontractor) {
                                                    echo "<option value=" . $subcontractor->supplier_id  . ">" . $subcontractor->supplier_name . "</option>";
                                                } ?>
                                            </select>
                                        </td>
                                        <td style="width:30%;text-align:left;border:1px solid;vertical-align:top;">
                                            <label>Date, Name & Signature of Engineer who check this order: </label><br /><br />
                                        </td>
                                    </tr>
                                    <tr style="vertical-align:bottom;">
                                        <td style="width:70%;text-align:left;border:1px solid;">
                                            <span id="suprev_signature"></span>
                                            <hr style="border: 1px solid;width: 20%; margin-bottom:0;" align="left">
                                            <label>Signature:</label><br />
                                            <label>Requested by site Supervisor </label>
                                            <div id="superviser"></div>
                                        </td>
                                        <td style="width:30%;text-align:left;border:1px solid;">

                                            <label>Date of Materials required:</label><br /><br />

                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <label style="min-width: 100px;">Earliest Date:</label>
                                                <input type="text" readonly id="earliest_day" style="width: 80px; border: none; font-weight: bold; background: transparent;">
                                                <input type="date" name="earliest_date" class="form-control" id="earliest_date">
                                            </div>

                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <label style="min-width:100px;">Latest Date:</label>
                                                <input type="text" readonly id="latest_day" style="width: 80px; border: none; font-weight: bold; background: transparent;">
                                                <input type="date" name="latest_date" class="form-control" id="latest_date">
                                            </div>
                                        </td>
                                    </tr>
                                </table>


                            </div>
                            <div>
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select status</option>
                                    <option value="Engineer Confirmation">Engineer Confirmation</option>
                                    <option value="Pending Engineer Verification">Pending Engineer Verification</option>
                                    <option value="Pending Project Manager Approval">Pending Project Manager Approval</option>
                                    <option value="Pending Management Approval">Pending Management Approval</option>
                                    <!-- <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option> -->
                                </select>
                                <textarea class="form-control" id="status_reason" name="status_reason"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions box-footer">
                        <button type="submit" id="btn_purchase_requistion" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                            <?php echo $this->lang->line('xin_save'); ?> </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php //echo $this->lang->line('xin_purchase_requistion'); 
            ?> Material Requisition Form (MRF)</h3>
    </div>
    <div class="box-body">

        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th><?php echo $this->lang->line('xin_action'); ?></th>
                        <th>MRF No.</th>
                        <th>Date of Request</th>
                        <th>Employee</th>
                        <th>Milestone</th>
                        <th style="width:350px">Task</th>
                        <th><?php echo $this->lang->line('xin_status'); ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>



<script>
    function selectProduct(selectElem, rowId) {
        var productName = selectElem.options[selectElem.selectedIndex].text;
        var productId = selectElem.value;

        if (productId) {
            document.getElementById('product-label-' + rowId).innerText = productName;
            document.getElementById('product-id-' + rowId).value = productId;

            // Hide select, show label
            selectElem.style.display = 'none';
            document.getElementById('product-label-' + rowId).style.display = 'block';
        }
    }

    function editProduct(rowId) {
        // Show select again
        document.getElementById('product-select-' + rowId).style.display = 'block';
        document.getElementById('product-label-' + rowId).style.display = 'none';
    }

    $(document).ready(function() {
        $('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
        $('[data-plugin="select_hrm"]').select2({
            width: 'resolve',
            dropdownAutoWidth: true,
            dropdownCssClass: "big-dropdown"
        });

        $(document).ready(function() {
            $('#crane').change(function() {
                if ($(this).val() == 'others' || $(this).val() == 'Others') {
                    $('#other_crane_box').show();
                } else {
                    $('#other_crane_box').hide();
                }
            });

        });

        $("#status_reason").hide();
        $('#addButton1').on('click', function() {
            const number = $('.AddItem tr').length;

            if (number >= 15) {
                alert("Maximum 15 rows allowed.");
                return;
            }

            const item = number + 1;
            $('.AddItem').append(`
                                    <tr style="border:1px solid;">
                                        <td style="min-width:30px">
                                            <label class="item-label">${item}</label>
                                        </td>
                                      <td style="min-width:350px">
                                                <div class="product-select-container" id="product-container-${item}">
                                                    <select class="form-control product-select" id="product-select-${item}" onchange="selectProduct(this,${item})">
                                                        <option value="">Select product</option>
                                                        <?php foreach ($all_products as $product) {
                                                            echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                                        } ?>
                                                    </select>
                                                    <span class="product-label" id="product-label-${item}" style="display:none;" onclick="editProduct(${item})"></span>
                                                </div>
                                                <input type="hidden" name="product_id[]" id="product-id-${item}" />
                                            </td>
                                        <td>
                                            <input id="qty${item}" type="number" min="1" class="form-control" name="qty[]" placeholder="Qty">
                                        </td>
                                        <td>
                                            <input type="text" id="uom${item}" class="form-control" name="uom[]">                                            
                                        </td>
                                        <td>
                                            <input type="text" id="level${item}" class="form-control" name="level[]">
                                        </td>
                                        <td style="min-width:150px">
                                            <input type="text" id="use${item}" class="form-control" name="use[]">
                                        </td>
                                        <td style="min-width:150px">
                                            <input type="text" id="sub_con${item}" class="form-control" name="sub_con[]">
                                        </td>
                                        <td style="min-width:150px">
                                            <input type="text" id="po_no${item}" class="form-control" name="po_no[]">
                                        </td>
                                        <td style="min-width:150px">
                                            <input type="text" id="do_no${item}" class="form-control" name="do_no[]">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                                        </td>
                                    </tr>
                                `);
        });
        $("#status").change(function() {
            if ($(this).val() == "Rejected") {
                $("#status_reason").show();
            } else {
                $("#status_reason").hide();

            }
        });
        $('#project_id').on('change', function() {
            var project_id = $('#project_id').val();
            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/get_quotation_from_project/'; ?>" + project_id,
                type: "POST",
                success: function(response) {
                    // Clear existing values
                    $('#milestone_id').empty(); // Clear the milestone dropdown
                    $("#site_address").empty();

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

            $.ajax({
                url: "<?php echo base_url('admin/purchase/get_project_data_by_id/'); ?>" + project_id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    projectData = response;

                    const select = $('#site_address');
                    select.empty();
                    select.append('<option value="">Select Site Address</option>');

                    response.forEach(function(item) {
                        select.append('<option value="' + item.project_site + '">' + item.project_site + '</option>');
                    });

                    select.append('<option value="others">Others</option>');

                    // Optional: set default address and supervisor
                    if (response.quotation_no && response.quotation_no.length > 0) {
                        $('#site_address').val(response.quotation_no[0].project_site);
                        $('#supervisor').val(response.quotation_no[0].supervisor).trigger('change');
                    }


                    $('#site_address').on('change', function() {
                        const selected = $(this).val();
                        if (selected == 'others') {
                            $('#other_site_address').show();
                        } else {
                            $('#other_site_address').hide();

                            // Get the matched project data
                            const matched = projectData.find(item => item.project_site === selected);
                            if (matched) {
                                $('#supervisor').val(matched.supervisor).trigger('change');
                            }
                        }
                    });

                    // Trigger change manually to apply initial logic
                    $('#site_address').trigger('change');
                },
                error: function() {
                    alert('Failed to load site addresses.');
                }
            });




        });

        $('#milestone_id').on('change', function() {
            var milestone_id = $('#milestone_id').val();
            var project_id = $('#project_id').val();
            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/get_task_from_milestone/'; ?>" + milestone_id + "/" + project_id,
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


        // When supervisor changes, update the displayed supervisor name
        $("#supervisor").change(function() {
            var supervisor_name = $(this).find('option:selected').text();
            var supervisor_signature = $(this).find('option:selected').data('signature');
            $('#suprev_signature').empty().html('<img src="<?php echo base_url('uploads/document/signature/'); ?>' + supervisor_signature + '" alt="Signature" style="max-width: 100px; max-height: 50px;">');
            $('#superviser').empty().html(supervisor_name);
        });
    });




    // function getProjectAddress() {
    //     var prj_id = $('#project_id').val();
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url() . 'admin/purchase/get_project_details/'; ?>" + prj_id,
    //         data: JSON,
    //         success: function(data) {
    //             var product_data = jQuery.parseJSON(data);
    //             if (product_data.length > 0 && product_data[0].project_address !== null) {
    //                 $("#site_address").empty();
    //                 $("#site_address").val(product_data[0].project_address);
    //             } else {
    //                 handleAddressNotFound();
    //             }
    //         },
    //         error: function() {
    //             handleAddressNotFound();
    //         }
    //     });
    // }

    function getProductDetail(id, number) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/purchase/get_product_details/'; ?>" + id,
            dataType: 'json', // <-- Add this
            success: function(product_data) {
                console.log(product_data);
                if (product_data && product_data[0]) {
                    $("#uom" + number).val(product_data[0].base_uom);
                } else {
                    toastr.error("Product data not found.");
                }
            },
            error: function() {
                toastr.error("Description or UOM Not Found");
            }
        });
    }



    // Remove row and re-index
    $(document).on('click', '.remove-input-field', function() {
        $(this).closest('tr').remove();
        reIndexRows();
    });

    // Function to re-index row numbers after delete
    function reIndexRows() {
        $('.AddItem tr').each(function(index) {
            $(this).find('.item-label').text(index + 1);
        });
    }
</script>