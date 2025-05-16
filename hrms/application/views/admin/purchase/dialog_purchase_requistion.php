<style>
    #ajax_modal {
        width: 1140px !important;
        margin-left: -180px;
    }

    /* .modal-full {
    width: 100%;
    max-width: none;
    height: 100%;
    margin: 0;
} */

    /* .modal-content {
    height: 100vh;
} */
</style>
<style>
    .invoice table th,
    .invoice table td {
        border: 1px solid #000;
        text-align: center;
    }

    #mrf_table td {
        max-width: 200px;
        /* Adjust based on your design */
        word-wrap: break-word;
        white-space: normal;
        text-align: left;

        /* Allows multi-line wrapping */
        overflow-wrap: break-word;
        /* Ensures long words break */
    }

    .editable-span {
        display: inline-block;
        word-wrap: break-word;
        white-space: pre-wrap;
        /* Ensures text wraps */
        max-width: 150px;
        /* Adjust as needed */
        overflow-wrap: break-word;
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
        width: 24%;
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
        /* border: 1px solid #000; */
        border-collapse: collapse;
        text-wrap: wrap;
        /* overflow-y: scroll; */
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

    }
</style>
<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['purchase_requistion_id']) && $_GET['data'] == 'purchase_requistion') {
?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>

        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_mrf'); ?></h4>

    </div>
    <div class="box-body">
        <?php $attributes = array('name' => 'update_purchase_requistion', 'id' => 'edit_purchase_requistion', 'autocomplete' => 'off'); ?>
        <?php $hidden = array('user_id' => $session['user_id']); ?>
        <?php echo form_open_multipart('admin/purchase/update', $attributes, $hidden); ?>
        <div class="form-body">
            <input type="hidden" name="purchase_requistion_id" value="<?php echo $purchase_requistion_id; ?>">
            <div class="invoice">

                <?php
                $arr_site = explode(',', $site);
                ?>
                <div class="left">
                    <b>MRF No. <?= $porder_id ?></b> <br> <br><br>
                    <b>PROJECT NAME / No: </b>
                    <select name="u_project_id" id="u_project_id" class="form-control">
                        <option>Select Project</option>
                        <?php foreach ($get_all_project as $project) {
                            echo "<option value='" . $project->project_id . "'" . ($project->project_id == $project_id ? 'selected' : '') . ">" . $project->project_title . "</option>";
                        }
                        ?>
                    </select>
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
                        <option value="8" <?php echo ($milestone == 8) ? 'selected' : '' ?>>Others</option>

                    </select>
                    <label>Task</label>
                    <select name="task_id1" id="task_id1" class="form-control" data-plugin="select_hrm">

                    </select>
                </div>

                <td style="width: 33.33%;text-align:left;border-right:none;border:1px solid;">
                    <div class="right" style="width:auto">
                        <label>Mode of Transport</label>
                        <select name="crane1" id="crane1" class="form-control">
                            <option value="">Mode of Transport</option>
                            <?php $transport1 = $this->db->get('mode_of_transport')->result();
                            foreach ($transport1 as $t1) {
                                echo "<option value='" . $t1->mst_id . "'" . ($t1->mst_id == $crane ? " selected" : "") . ">" . $t1->mst_title . "</option>";
                            } ?>
                            <option value="others" <?php if ($crane == 0) {
                                                        echo "selected";
                                                    } ?>>Others</option>

                        </select>
                        <div id="other_crane_box1" style="display:none; margin-top:10px;">
                            <input type="text" name="other_crane1" id="other_crane1" class="form-control" placeholder="Enter Mode of Transport" value="<?php echo $others; ?>">
                        </div>
                    </div>
                </td>

                <table style="width: 100%;">
                    <tr>
                        <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Material Requisition Form (MRF) (internal)</b></td>
                        <td style="width: 33.33%;text-align:left;border-right:none;border:1px solid;"><b>MRF No.: <?php echo $porder_id; ?></b></td>
                        <td rowspan="6" style="width: 50%;border-right:none;">
                            <img src="<?php echo site_url('uploads/logo/logo-with-bizsafe.png') ?>" class="img-fluid" width="200px" alt="">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Project Department-Purchasing Departmrent</b></td>
                        <td style="width: 33.33%;text-align:left; border:1px solid;"><b>MRF Date : <?php echo date('d-m-Y', strtotime($order_date)) ?></b><input type="hidden" name="u_order_date" class="e_date form-control" value="<?php echo $order_date; ?>"></td>
                    </tr>
                    <tr>
                        <td style="width: 33.33%;text-align:left;border:1px solid;" colspan="2"><b>Site:</b>
                            <select name="u_site_address_select" id="u_site_address" class="form-control">
                                <option>Select Site Address</option>
                            </select>

                            <input type="text" name="other_u_site_address" id="other_u_site_address" class="form-control" style="display:none; margin-top:10px;" value="<?php echo $site_address; ?>">

                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:30%;text-align:left;"><input type="checkbox" id="u_site4" name="u_check1[]" value="Storeroom No Stock. I have checked with Storeman" <?php echo (in_array("Storeroom No Stock. I have checked with Storeman", $arr_site) ? 'checked' : ''); ?>><label for="u_site4">Storeroom No Stock. I have checked with Storeman.</label></td>
                        <td style="width:30%;text-align:left;"><input type="checkbox" id="u_site5" name="u_check1[]" value="Supervisor has checked with Engineer before ordering" <?php echo (in_array("Supervisor has checked with Engineer before ordering", $arr_site) ? 'checked' : ''); ?>><label for="u_site5">Supervisor has checked with Engineer before ordering.</label></td>

                    </tr>
                    <tr>
                        <td style="width:30%;text-align:left;"><input type="checkbox" id="u_site6" name="u_check1[]" value="Please check Yishun Storeroom before you order" <?php echo (in_array("Please check Yishun Storeroom before you order", $arr_site) ? 'checked' : ''); ?>><label for="u_site6">Please check Yishun Storeroom before you order.</label></td>
                        <td style="width:30%;text-align:left;"><input type="checkbox" id="u_site7" name="u_check1[]" value="We have already checked with Boss to order" <?php echo (in_array("We have already checked with Boss to order", $arr_site) ? 'checked' : ''); ?>><label for="u_site7">We have already checked with Boss to order.</label></td>
                    </tr>

                </table>
                <div style="text-align: right;">
                    <a href="javascript:void(0)" class="btn-sm btn-success addButton" id="addButton2">Add</a>
                </div>
                <div style="text-align: left;">
                    <table style="margin-top: 5px;" id="mrf_table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Material/Tool</th>
                                <th>Qty</th>
                                <th>Which Level?</th>
                                <th>Where did you use?</th>
                                <th>Name of Sub Con</th>
                                <th>Purchase Order No.</th>
                                <th>Delivery Order No.</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="AddItem1" id="vendor_items_table2">
                            <?php
                            $i = 0;
                            foreach ($all_items as $item) {
                                $i++; ?>
                                <tr>
                                    <td><label><?php echo $i; ?><label></td>
                                    <td>
                                        <?php
                                        $selected_product_name = '';
                                        foreach ($all_products as $product) {
                                            if ($product->product_id == $item->product_id) {
                                                $selected_product_name = $product->product_name;
                                                break;
                                            }
                                        }
                                        ?>
                                        <select class=" form-control product-select" name="u_product_id[]" id="product_<?php echo $i; ?>" onchange="handleProductChange(this, <?php echo $i; ?>)" style="display:none;">
                                            <option value="">Select product</option>
                                            <?php foreach ($all_products as $product) {
                                                echo '<option value="' . $product->product_id . '"' . ($product->product_id == $item->product_id ? "selected" : "") . '>' . $product->product_name . '</option>';
                                            } ?>
                                        </select>
                                        <span class="product-text" data-id="<?php echo $i; ?>" style="cursor:pointer; " title="Click to edit">
                                            <?php echo !empty($selected_product_name) ? $selected_product_name : 'Select product'; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="editable-span"><?php echo !empty($item->qty) ? $item->qty : "Click to edit"; ?></span>
                                        <input type="text" id="qty<?php echo $i; ?>" class="form-control editable-input" name="u_qty[]" placeholder="Qty" value="<?php echo $item->qty; ?>" style="display:none;">
                                    </td>

                                    <td>
                                        <span class="editable-span"><?php echo !empty($item->level) ? $item->level : "Click to edit"; ?></span>
                                        <input type="text" id="level<?php echo $i; ?>" class="form-control editable-input" name="u_level[]" value="<?php echo $item->level; ?>" style="display:none;">
                                    </td>

                                    <td>
                                        <span class="editable-span"><?php echo !empty($item->where_use) ? $item->where_use : "Click to edit"; ?></span>
                                        <input type="text" id="use<?php echo $i; ?>" class="form-control editable-input" name="u_use[]" value="<?php echo $item->where_use; ?>" style="display:none;">
                                    </td>

                                    <td>
                                        <span class="editable-span"><?php echo !empty($item->sub_con) ? $item->sub_con : "Click to edit"; ?></span>
                                        <input type="text" id="sub_con<?php echo $i; ?>" class="form-control editable-input" name="u_sub_con[]" value="<?php echo $item->sub_con; ?>" style="display:none;">
                                    </td>

                                    <td>
                                        <span class="editable-span"><?php echo !empty($item->po_no) ? $item->po_no : "Click to edit"; ?></span>
                                        <input type="text" id="po_no<?php echo $i; ?>" class="form-control editable-input" name="u_po_no[]" value="<?php echo $item->po_no; ?>" style="display:none;">
                                    </td>

                                    <td>
                                        <span class="editable-span"><?php echo !empty($item->do_no) ? $item->do_no : "Click to edit"; ?></span>
                                        <input type="text" id="do_no<?php echo $i; ?>" class="form-control editable-input" name="u_do_no[]" value="<?php echo $item->do_no; ?>" style="display:none;">
                                    </td>


                                    <td>
                                        <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
                <table>
                    <tr>
                        <td style="width:70%;text-align:left;">
                            <label>Name of Supervisor who order:</label>
                            <select class="form-control" name="u_supervisor" id="u_supervisor">
                                <option value="">Select Supervisor</option>
                                <?php foreach ($all_customers as $customer) {
                                    echo "<option value='" . $customer->user_id . "'" . ($customer->user_id == $supervisor ? 'selected' : '') . "  data-u_signature='" . $customer->signature . "'>" . $customer->first_name . ' ' . $customer->last_name . "</option>";
                                } ?>
                            </select>
                            <br />
                            <label>Sub contractor Company:</label>
                            <select class="form-control" name="u_sub_contractor">
                                <option>Select Sub-contractor</option>
                                <?php foreach ($all_subcontractors as $subcontractors) {
                                    echo "<option value='" . $subcontractors->supplier_id . "'" . ($subcontractors->supplier_id == $sub_contractor ? 'selected' : '') . ">" . $subcontractors->supplier_name . "</option>";
                                } ?>
                            </select>
                        </td>
                        <td style="width:30%;text-align:left;">
                            <label>Date,Name & Signature of Engineer who check this order: </label><br /><br />
                        </td>

                    </tr>
                    <tr>
                        <td style="width:70%;text-align:left;border:1px solid;">
                            <span id="u_suprev_signature"></span>
                            <hr style="border: 1px solid;width: 20%; margin-bottom:0;" align="left">
                            <label>Signature:</label><br />
                            <label>Requested by site Supervisor </label>
                            <div id="u_supervisor1"></div>
                        </td>
                        <td style="width:30%;text-align:left;">
                            <label>Date of Materials required: </label><br />
                            <p>Earliest Date:<input type="text" name="u_earliest_date" class="e_date form-control" value="<?php echo $earliest_date; ?>"></p><br />
                            <p>Latest Date:<input type="text" name="u_latest_date" class="e_date form-control" value="<?php echo $latest_date; ?>"></p>
                            <br />
                        </td>

                    </tr>
                </table>
                <div>
                    <label>Status</label>
                    <select name="u_status" id="u_status" class="form-control">
                        <option value="">Select status</option>
                        <option value="Engineer Confirmation" <?php echo ($status == "Engineer Confirmation" ? "selected" : ""); ?>>Engineer Confirmation</option>
                        <option value="Pending Engineer Verification" <?php echo ($status == "Pending Engineer Verification" ? "selected" : ""); ?>>Pending Engineer Verification</option>
                        <option value="Pending Project Manager Approval" <?php echo ($status == "Pending Project Manager Approval" ? "selected" : ""); ?>>Pending Project Manager Approval</option>
                        <option value="Pending Management Approval" <?php echo ($status == "Pending Management Approval" ? "selected" : ""); ?>>Pending Management Approval</option>
                    </select>
                    <textarea class="form-control" id="u_status_reason" name="u_status_reason"></textarea>
                </div>
            </div>
        </div>
        <div class="form-actions box-footer">
            <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                <?php echo $this->lang->line('xin_save'); ?> </button>
        </div>
        <?php echo form_close(); ?>
    </div>
<?php } ?>

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

    function getProductDetails(id, number) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/purchase/get_product_details/'; ?>" + id,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);
                console.log(data);
                $("#u_description_" + number).text(product_data[0].description);
                $("#u_uom_" + number).val(product_data[0].base_uom);
            },
            error: function() {
                toastr.error("Description or UOM Not Found");
            }
        });
    }
</script>

<!-- Your HTML and other JavaScript code here -->
<script>
    $(document).ready(function() {
        // Initially check if 'others' is already selected (in case of edit form)
        if ($('#crane1').val() == 'others') {
            $('#other_crane_box1').show();
        } else {
            $('#other_crane_box1').hide();
        }

        // On change of select
        $('#crane1').change(function() {
            if ($(this).val() == 'others') {
                $('#other_crane_box1').show();
            } else {
                $('#other_crane_box1').hide();
            }
        });
    });

    $(document).ready(function() {
        var selectedTask = "<?php echo $description_name; ?>"; // Holds the pre-selected task from the database



        // Trigger task loading on milestone change
        $("#milestone_id1").on('change', function() {
            loadTasks($("#u_project_id").val(), $(this).val());
        });

        // Initial load on page load to set pre-selected task
        loadTasks($("#u_project_id").val(), $("#milestone_id1").val());
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
    $(document).ready(function() {
        const oldSiteAddress = "<?php echo $site_address; ?>";
        const oldSupervisor = "<?php echo $supervisor; ?>";

        // Supervisor signature update logic
        $("#u_supervisor").on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const supervisorName = selectedOption.text();
            const supervisorSignature = selectedOption.data('u_signature');

            $('#u_suprev_signature').html(
                '<img src="<?php echo base_url('uploads/document/signature/'); ?>' + supervisorSignature + '" alt="Signature" style="max-width: 100px; max-height: 50px;">'
            );
            $('#u_supervisor1').empty().html(supervisorName);

        });

        //Trigger the initial signature update for pre-selected supervisor
        if (oldSupervisor) {
            $("#u_supervisor").val(oldSupervisor).trigger('change');
        }

        // Project dropdown change logic
        $('#u_project_id').on('change', function() {
            const project_id = $(this).val();

            $.ajax({
                url: "<?php echo base_url('admin/purchase/get_project_data_by_id/'); ?>" + project_id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    const projectData = response;
                    const select = $('#u_site_address');
                    select.empty().append('<option value="">Select Site Address</option>');

                    response.forEach(function(item) {
                        select.append('<option value="' + item.project_site + '">' + item.project_site + '</option>');
                    });

                    select.append('<option value="others">Others</option>');

                    // Check if oldSiteAddress matches any site address
                    let matched = projectData.find(item => item.project_site === oldSiteAddress);
                    if (matched) {
                        select.val(oldSiteAddress).trigger('change');
                    } else if (oldSiteAddress && oldSiteAddress !== "") {
                        // If not matched, select 'others' and show the input
                        select.val('others').trigger('change');
                        $('#other_u_site_address').show();
                    } else {
                        select.val('').trigger('change');
                    }

                    $('#u_site_address').on('change', function() {
                        const selected = $(this).val();
                        console.log(selected)
                        if (selected === 'others') {
                            $('#other_u_site_address').show();
                            // For 'others', do not update supervisor
                        } else {
                            $('#other_u_site_address').hide();

                            const matched = projectData.find(item => item.project_site == selected);
                            if (matched) {
                                console.log(matched);
                                $('#u_supervisor').val(matched.supervisor).trigger('change');
                            }
                        }
                    });

                    // Trigger change manually to apply initial logic
                    $('#u_site_address').trigger('change');
                },
                error: function() {
                    alert('Failed to load site addresses.');
                }
            });
        });

        // Trigger project_id change if it's pre-selected (edit mode)
        if ($('#u_project_id').val()) {
            $('#u_project_id').trigger('change');
        }
        // When supervisor changes, update the displayed supervisor name
        // $("#u_supervisor").change(function() {
        //     var supervisor_name1 = $(this).find('option:selected').text();
        //     var supervisor_signature1 = $(this).find('option:selected').data('u_signature');
        //     // console.log($(this).find('option:selected'))
        //     $('#u_suprev_signature').empty().html('<img src="<?php echo base_url('uploads/document/signature/'); ?>' + supervisor_signature1 + '" alt="Signature" style="max-width: 100px; max-height: 50px;">');
        //     $('#u_supervisor').empty().html(supervisor_name1);
        // });
    });
    $(document).ready(function() {
        if ($('#u_project_id').val()) {
            $('#u_project_id').trigger('change');
        }
        $("#u_status_reason").hide();
        $('.modal-dialog').addClass('handleUpdate');
        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({
            width: '100%'
        });

        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();
        });
        $('#addButton2').on('click', function() {
            var number = $('.AddItem1 tr').length;
            var item = number + 1;
            $('.AddItem1').append(`
                <tr>
                    <td><label>` + item + `</label></td>
                    <td>
                        <select class=" form-control product-select" data-plugin="select_hrm"  name="u_product_id[]" id="product_` + item + `" onchange="handleProductChange(this, ` + item + `)" style="display:none;">
                            <option value="">Select product</option>
                            <?php foreach ($all_products as $product) {
                                echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                            } ?>
                        </select>
                        <span class="product-text" data-id="` + item + `" style="cursor:pointer;" title="Click to edit">Select product</span>
                    </td>
                    <td><input type="text" id="qty` + item + `" class="form-control" name="u_qty[]" placeholder="Qty"></td>
                    <td><input type="text" id="level` + item + `" class="form-control" name="u_level[]"></td>
                    <td><input type="text" id="use` + item + `" class="form-control" name="u_use[]"></td>
                    <td><input type="text" id="sub_con` + item + `" class="form-control" name="u_sub_con[]"></td>
                    <td><input type="text" id="po_no` + item + `" class="form-control" name="u_po_no[]"></td>
                    <td><input type="text" id="do_no` + item + `" class="form-control" name="u_do_no[]"></td>
                    <td><button type="button" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button></td>
                </tr>
            `);
        });






        $("#u_status").change(function() {
            if ($(this).val() == "Rejected") {
                $("#u_status_reason").show();
            } else {
                $("#u_status_reason").hide();

            }
        });
        $("#edit_purchase_requistion").submit(function(e) {
            var fd = new FormData(this);
            var obj = $(this),
                action = obj.attr('name');
            fd.append("is_ajax", 1);
            fd.append("edit_type", 'edit_purchase_requistion');
            fd.append("form", action);
            e.preventDefault();
            $('.icon-spinner3').show();
            $('.save').prop('disabled', true);
            $.ajax({
                url: e.target.action,
                type: "POST",
                data: fd,
                contentType: false,
                cache: false,
                processData: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                        $('.icon-spinner3').hide();
                    } else {
                        // On page load: datatable
                        var xin_table = $('#xin_table').dataTable({
                            "bDestroy": true,
                            "ajax": {
                                url: "<?php echo site_url("admin/purchase/purchase_requistion_list") ?>",
                                type: 'GET'
                            },
                            // dom: 'lBfrtip',
                            // "buttons": ['csv', 'excel', 'pdf',
                            // 'print'], // colvis > if needed
                            // "fnDrawCallback": function(settings) {
                            //     $('[data-toggle="tooltip"]').tooltip();
                            // }
                        });
                        xin_table.api().ajax.reload(function() {
                            toastr.success(JSON.result);

                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        }, true);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);


                        $('.icon-spinner3').hide();
                        $('.edit-modal-data').modal('toggle');
                        $('.save').prop('disabled', false);
                    }
                },
                error: function() {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            });
        });

        // Date
        $('.e_date').datepicker({
            changeMonth: true,
            changeYear: true,
            format: 'dd-mm-yyyy',
            yearRange: '1900:' + (new Date().getFullYear() + 10),
        });


    });
</script>