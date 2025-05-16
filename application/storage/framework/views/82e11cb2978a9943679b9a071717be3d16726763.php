<?php
$all_customers=DB::table('xin_employees')->get();
$all_products=DB::table('product')->get();
$a = request('prqresource_id');
$b = request('noteresource_id');
$id = 0;
if ($a) {
$id = $a;
} else {
$id = $b;
}
?>
<style>
    .modal .modal-content {
        width: 159%;

    }

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
    /* .invoice table th {
        border: 1px solid #000;
        text-align: center;
    } */


    /* .invoice-header {
        display: flex;
        gap: 10px;
        border-bottom: 1px solid #000;
    } */
    [type="checkbox"]:checked+label:before {
        display: block !important;
    }

    [type="checkbox"]:checked+label {
        padding-left: 12px !important;

    }

    [type="checkbox"].filled-in:checked.chk-col-light-blue+label:after {
        display: block !important;
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

    /* .invoice-main {
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
    } */

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

    }
</style>

<?php if(isset($page['section']) && $page['section'] == 'create'): ?>
<div class="row">

    <div class="box-body">

        <div class="form-body">

            <div class="invoice">
                <div class="left">

                    <label>Project Name :<?= $budgtrepo[0]->q_title ?? '' ?></label>

                    <input type="hidden" name="project_id" value="<?= $id; ?>">
                    <select class="form-control" name="milestone_id" id="category_id_prili">
                        <option>Choose Category</option>
                        <?php foreach ($templete_category as $k => $purchase) { ?>
                            <option value="<?php echo $purchase->milestonecategory_id; ?>"><?php echo $purchase->milestonecategory_title; ?></option>
                        <?php } ?>
                    </select>
                    <label>Task</label>
                    <select name="task_id" id="task_id" class="form-control" data-plugin="select_hrm">

                    </select>
                </div>
                <br />
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Material Requisition Form (MRF)(MRF)</b></td>
                        <!-- <td style="width: 33.33%;text-align:left;"><b>MRF No.</b>:<input type="text" name="mrf_no" class="form-control"></td> -->
                        <td style="width: 33.33%;text-align:left;border-right:none;border:1px solid;"></td>
                        <!-- <td rowspan="3" style="width: 10%;border-right:none;"></td> -->
                        <td rowspan="6" style="width: 50%;border-right:none; align-items: center!important;">
                            <img src="<?php echo e(asset('uploads/logo/logo-with-bizsafe.png')); ?>" class="img-fluid" width="200px" alt="">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Project Department-Purchasing Department</b></td>
                        <td style="width: 33.33%;text-align:left; border:1px solid;"><b>MRF Date: <?php echo date('d-m-Y') ?></b>
                            <input type="hidden" name="order_date" class="date form-control" value="<?php echo date('d-m-Y') ?>">
                        </td>
                        <!-- <td style="width: 20%;"></td>
                    <td style="width: 30%;border-right:none;"></td> -->

                    </tr>
                    <tr>
                        <td style="width: 33.33%;text-align:left;border:1px solid;" colspan="2"><b>Site:</b>
                            <input type="text" name="site_address" id="site_address" class="form-control" value="<?php echo e($budgtrepo[0]->site_address??''); ?>">
                        </td>
                        <!-- <td style="width: 33.33%;"></td> -->
                        <!-- <td style="width: 20%;"></td>
                    <td style="width: 30%;border-right:none;"></td> -->

                    </tr>
                </table>
                <table>
                    <tr>



                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site4" name="check1[]" value="Storeroom No Stock.I have checked with Storeman"><label for="site4">Storeroom No Stock.I have checked with Storeman.</label></td>
                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site5" name="check1[]" value="Supervisor has checked with Engineer before ordering"><label for="site5">Supervisor has checked with Engineer before ordering.</label></td>

                    </tr>
                    <tr>
                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site6" name="check1[]" value="Please check Yishun Storeroom before you order"><label for="site6">Please check Yishun Storeroom before you order.</label></td>
                        <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site7" name="check1[]" value="We have already checked with Boss to order"><label for="site7">We have already checked with Boss to order.</label></td>


                    </tr>

                </table>
                <div style="text-align: right;">
                    <a href="javascript:void(0)" class="btn-sm btn-success addButton" id="addButton1">Add</a>
                </div>
                <table style="margin-top: 5px;">
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
                    <tbody class="AddItem" id="vendor_items_table1"></tbody>

                </table>
                <table>
                    <tr>
                        <td style="width:70%;text-align:left;border:1px solid;">
                            <label>Name of Supervisor who order:</label>
                            <select class="form-control" name="supervisor" id="supervisor">
                                <option>Select Supervisor</option>
                                <?php $__currentLoopData = $dataSupervisor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value='<?php echo e($customer->user_id); ?>' <?php echo e(($customer->user_id==$project_d[0]->Supervisor)?"selected":""); ?>> <?php echo e($customer->first_name); ?><?php echo e($customer->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <br />
                            <label> Sub contractor Company:</label>
                            <!-- <input type="text" class="form-control" name="sub_contractor"> -->
                            <select class="form-control" name="sub_contractor">
                                <option>Select Sub-contractor</option>
                                <?php foreach ($all_customers as $customer) {
                                    echo "<option value=" . $customer->user_id . " >" . $customer->first_name . ' ' . $customer->last_name . "</option>";
                                } ?>
                            </select>
                        </td>
                        <td style="width:30%;text-align:left;border:1px solid;">
                            <label>Date,Name & Signature of Engineer who check this order: </label><br /><br />
                            <?php echo e(get_designation_detail($project_d[0]->Engineer)); ?>

                        </td>

                    </tr>
                    <tr>
                        <td style="width:70%;text-align:left;border:1px solid;">
                            <label><u>Signature:</u></label><br />
                            <label>Requested by site Supervisor </label>
                            <div id="superviser"></div>

                        </td>
                        <td style="width:30%;text-align:left;border:1px solid;">
                            <label>Date of Materials required: </label><br />
                            <p>Earliest Date:<input type="date" name="earliest_date" class="date form-control"></p><br />
                            <p>Latest Date:<input type="date" name="latest_date" class="date form-control"></p>
                            <br />
                        </td>

                    </tr>
                </table>
                <div>
                    <label>Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Select status</option>
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





    </div>
    <script>
        $(document).ready(function() {
            $("#status_reason").hide();
            $('#addButton1').on('click', function() {
                var number = $('.AddItem tr').length;
                var item = number + 1;
                $('.AddItem').append(`
                    <tr>
                    <td style="min-width:30px">
                            <label>` + item + `<label>
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="product_id[]" id="product_"` + item + ` onchange="getProductDetail(this.value,` + item + `)">
                                <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                            </select>
                        </td>
                        <td >
                        <input type="text" id="qty` + item + `" class="form-control" name="qty[]" placeholder="Qty">
                        </td>
                        <td>
                        <input type="text" id="level` + item + `" class="form-control" name="level[]">
                      </td>

                        <td style="min-width:150px">
                            <input type="text" id="use` + item + `"  class="form-control" name="use[]">
                        </td>
                        <td style="min-width:150px">
                            <input type="text" id="sub_con` + item + `" class="form-control" name="sub_con[]">
                        </td>
                        <td style="min-width:150px">
                            <input type="text" id="po_no` + item + `" class="form-control" name="po_no[]" >
                        </td>
                        <td style="min-width:150px">
                            <input type="text" id="do_no` + item + `" class="form-control" name="do_no[]" >
                        </td>
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
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


            $("#milestone_id").on('change', function() {
                // AJAX call to fetch tasks based on selected milestone
                $.ajax({
                    type: "POST",
                    url: "<?php echo e(url('admin/purchase/get_tasks_by_milestone')); ?>" + $('#milestone_id').val(),
                    dataType: "json",
                    success: function(taskData) {
                        if (taskData.length > 0) {
                            // Clear existing options and add new ones based on the response
                            $("#task_id").empty();

                            // Populate task_id select box with options from taskData
                            $.each(taskData, function(index, item) {
                                var option = $("<option></option>")
                                    .attr("value", item.id) // Assuming task_id is the identifier
                                    .text(item.description_name)
                                    .css({
                                        "white-space": "normal",
                                        "word-wrap": "break-word",
                                        "width": "50%",
                                        "display": "block",
                                    }); // Assuming task_name is the display name
                                $("#task_id").append(option);
                            });
                        } else {
                            toastr.error("No tasks found for this milestone");
                        }
                    },
                    error: function() {
                        toastr.error("Error retrieving tasks for the selected milestone");
                    }
                });
            });


            // When supervisor changes, update the displayed supervisor name
            $("#supervisor").change(function() {
                var supervisor_name = $(this).find('option:selected').text();
                $('#superviser').text(supervisor_name);
            });


        });


        function getProductDetail(id, number) {
            //var supplier_id=$('#sup').val();
            // console.log(supplier_id);

            $.ajax({
                type: "POST",
                url: "<?php echo e(url('admin/purchase/get_product_details/')); ?>" + id,
                data: JSON,
                success: function(data) {

                    console.log(data);
                    $("#description" + number).text(product_data[0].description);
                    $("#uom" + number).val(product_data[0].base_uom);
                },
                error: function() {
                    toastr.error("Description or UOM Not Found");
                }
            });
        }


        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();

        });



        $(document).ready(function() {
            $("#category_id_prili").on("change", function() {
                let id = $(this).val();

                let jsArray = <?php echo json_encode($budgtrepo, 15, 512) ?>;


                jsArray = jsArray.data.filter((re) => re.template_id == id);
                let op = `<option value=""  selected>Task</option>`;
                op += jsArray.map(re => `<option value="${re.description}">${re.description}</option>`).join('');

                $("#task_id").html(op);



            })
        })
    </script>
    <?php endif; ?>

    <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
    <?php
    $all_items=DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id',$note->purchase_requistion_id)->get();

    ?>
    <div class="row">

        <div class="box-body">

            <div class="form-body">

                <div class="invoice">
                    <div class="left">
                        <input type="hidden" name="uniq_id" id="" value="<?php echo e($note->purchase_requistion_id); ?>">
                        <input type="hidden" name="project_id" id="" value="<?php echo e($note->project_id); ?>">
                        <label>Project Name : <?php echo e($budgtrepo[0]->q_title??''); ?></label>

                        <input type="hidden" name="project_id" value="<?php echo e($budgtrepo[0]->project_id??''); ?>">
                        <select class="form-control" name="milestone_id" id="category_id_prili">
                            <option>Choose Category</option>
                            <?php foreach ($templete_category as $k => $purchase) { ?>
                                <option value="<?php echo $purchase->milestonecategory_id; ?>" <?php echo e($purchase->milestonecategory_id==$note->mile_stone?'selected':''); ?>><?php echo $purchase->milestonecategory_title; ?></option>
                            <?php } ?>
                        </select>
                        <label>Task</label>
                        <select name="task_id" id="task_id" class="form-control" data-plugin="select_hrm">
                            <option value="<?php echo $note->task; ?>"><?php echo $note->task; ?></option>
                        </select>
                    </div>
                    <br />
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Material Requisition Form (MRF)(MRF)</b></td>
                            <!-- <td style="width: 33.33%;text-align:left;"><b>MRF No.</b>:<input type="text" name="mrf_no" class="form-control"></td> -->
                            <td style="width: 33.33%;text-align:left;border-right:none;border:1px solid;"></td>
                            <!-- <td rowspan="3" style="width: 10%;border-right:none;"></td> -->
                            <td rowspan="6" style="width: 50%;border-right:none; align-items: center!important;">
                                <img src="<?php echo e(asset('uploads/logo/logo-with-bizsafe.png')); ?>" class="img-fluid" width="200px" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Project Department-Purchasing Department</b></td>
                            <td style="width: 33.33%;text-align:left; border:1px solid;"><b>MRF Date: <?php echo date('d-m-Y') ?></b>
                                <input type="hidden" name="order_date" class="date form-control" value="<?php echo date('d-m-Y') ?>">
                            </td>


                        </tr>
                        <tr>
                            <td style="width: 33.33%;text-align:left;border:1px solid;" colspan="2"><b>Site:</b>
                                <input type="text" name="site_address" id="site_address" class="form-control" value="<?php echo e($note->site_address??''); ?>">
                            </td>


                        </tr>
                    </table>
                    <table>
                        <tr>

                            <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site4" name="check1[]" value="Storeroom No Stock.I have checked with Storeman"><label for="site4">Storeroom No Stock.I have checked with Storeman.</label></td>
                            <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site5" name="check1[]" value="Supervisor has checked with Engineer before ordering"><label for="site5">Supervisor has checked with Engineer before ordering.</label></td>

                        </tr>
                        <tr>
                            <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site6" name="check1[]" value="Please check Yishun Storeroom before you order"><label for="site6">Please check Yishun Storeroom before you order.</label></td>
                            <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site7" name="check1[]" value="We have already checked with Boss to order"><label for="site7">We have already checked with Boss to order.</label></td>


                        </tr>

                    </table>
                    <div style="text-align: right;">
                        <a href="javascript:void(0)" class="btn-sm btn-success addButton" id="addButton1">Add</a>
                    </div>
                    <table style="margin-top: 5px;">
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
                        <tbody class="AddItem" id="vendor_items_table1">
                            <?php
                            $i = 0;
                            foreach ($all_items as $item) {
                                $i++; ?>
                                <tr>
                                    <td><label><?php echo $i; ?><label></td>
                                    <td>
                                        <select class="packing_dropdown form-control select22" name="product_id[]" id="product_<?php echo $i; ?>" onchange="getProductDetail(this.value,` + item + `)">
                                            <option value="">Select product</option>
                                            <?php foreach ($all_products as $product) {
                                                echo '<option value="' . $product->product_id . '"' . ($product->product_id == $item->product_id ? "selected" : "") . '>' . $product->product_name . '</option>';
                                            } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="qty<?php echo $i; ?>" class="form-control" name="qty[]" placeholder="Qty" value="<?php echo $item->qty; ?>">
                                    </td>
                                    <td>
                                        <input type="text" id="level<?php echo $i; ?>" class="form-control" name="level[]" value="<?php echo $item->level; ?>">
                                    </td>

                                    <td>
                                        <input type="text" id="use<?php echo $i; ?>" class="form-control" name="use[]" value="<?php echo $item->where_use; ?>">
                                    </td>
                                    <td>
                                        <input type="text" id="sub_con<?php echo $i; ?>" class="form-control" name="sub_con[]" value="<?php echo $item->sub_con; ?>">
                                    </td>
                                    <td>
                                        <input type="text" id="po_no<?php echo $i; ?>" class="form-control" name="po_no[]" value="<?php echo $item->po_no; ?>">
                                    </td>
                                    <td>
                                        <input type="text" id="do_no<?php echo $i; ?>" class="form-control" name="do_no[]" value="<?php echo $item->do_no; ?>">
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
                    <table>
                        <tr>
                            <td style="width:70%;text-align:left;border:1px solid;">
                                <label>Name of Supervisor who order:</label>
                                <select class="form-control" name="supervisor" id="supervisor">
                                    <option>Select Supervisor</option>
                                    <?php $__currentLoopData = $dataSupervisor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->user_id); ?>" <?php echo e(($customer->user_id==$project_d[0]->Supervisor)?"selected":""); ?>>
                                        <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <br />
                                <label> Sub contractor Company:</label>
                                <!-- <input type="text" class="form-control" name="sub_contractor"> -->
                                <select class="form-control" name="sub_contractor">
                                    <option>Select Sub-contractor</option>
                                    <?php $__currentLoopData = $all_customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->user_id); ?>" <?php echo e($customer->user_id == $note->sub_contractor ? 'selected' : ''); ?>>
                                        <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td style="width:30%;text-align:left;border:1px solid;">
                                <label>Date,Name & Signature of Engineer who check this order: </label><br /><br />
                                <?php echo e(get_designation_detail($project_d[0]->Engineer)); ?>

                            </td>

                        </tr>
                        <tr>
                            <td style="width:70%;text-align:left;border:1px solid;">
                                <label><u>Signature:</u></label><br />
                                <label>Requested by site Supervisor </label>
                                <div id="superviser"></div>

                            </td>
                            <td style="width:30%;text-align:left;border:1px solid;">
                                <label>Date of Materials required: </label><br />
                                <p>Earliest Date:<input type="date" value="<?php echo e($note->earliest_date); ?>" name="earliest_date" class="date form-control"></p><br />
                                <p>Latest Date:<input type="date" name="latest_date" value="<?php echo e($note->latest_date); ?>" class="date form-control"></p>
                                <br />
                            </td>

                        </tr>
                    </table>
                    <div>
                        <label>Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value=""><?php echo e($note->status??''); ?></option>
                            <option value="Pending Engineer Verification">Pending Engineer Verification</option>
                            <option value="Pending Project Manager Approval">Pending Project Manager Approval</option>
                            <option value="Pending Management Approval">Pending Management Approval</option>


                        </select>
                        <textarea class="form-control" id="status_reason" name="status_reason"></textarea>
                    </div>
                </div>


            </div>





        </div>
        <script>
            $(document).ready(function() {
                $("#status_reason").hide();
                $('#addButton1').on('click', function() {
                    var number = $('.AddItem tr').length;
                    var item = number + 1;
                    $('.AddItem').append(`
            <tr>
                <td style="min-width:30px">
                    <label>` + item + `<label>
                </td>
                <td style="min-width:200px">
                    <select class="packing_dropdown form-control select22" name="product_id[]" id="product_" ` + item + ` onchange="getProductDetail(this.value,` + item + `)">
                        <option value="">Select product</option>
                        <?php foreach ($all_products as $product) {
                            echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                        } ?>
                    </select>
                </td>
                <td>
                    <input type="text" id="qty` + item + `" class="form-control" name="qty[]" placeholder="Qty">
                </td>
                <td>
                    <input type="text" id="level` + item + `" class="form-control" name="level[]">
                </td>

                <td style="min-width:150px">
                    <input type="text" id="use` + item + `" class="form-control" name="use[]">
                </td>
                <td style="min-width:150px">
                    <input type="text" id="sub_con` + item + `" class="form-control" name="sub_con[]">
                </td>
                <td style="min-width:150px">
                    <input type="text" id="po_no` + item + `" class="form-control" name="po_no[]">
                </td>
                <td style="min-width:150px">
                    <input type="text" id="do_no` + item + `" class="form-control" name="do_no[]">
                </td>
                <td>
                    <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
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


                $("#milestone_id").on('change', function() {
                    // AJAX call to fetch tasks based on selected milestone
                    $.ajax({
                        type: "POST",
                        url: "<?php echo e(url('admin/purchase/get_tasks_by_milestone')); ?>" + $('#milestone_id').val(),
                        dataType: "json",
                        success: function(taskData) {
                            if (taskData.length > 0) {
                                // Clear existing options and add new ones based on the response
                                $("#task_id").empty();

                                // Populate task_id select box with options from taskData
                                $.each(taskData, function(index, item) {
                                    var option = $("<option></option>")
                                        .attr("value", item.id) // Assuming task_id is the identifier
                                        .text(item.description_name)
                                        .css({
                                            "white-space": "normal",
                                            "word-wrap": "break-word",
                                            "width": "50%",
                                            "display": "block",
                                        }); // Assuming task_name is the display name
                                    $("#task_id").append(option);
                                });
                            } else {
                                toastr.error("No tasks found for this milestone");
                            }
                        },
                        error: function() {
                            toastr.error("Error retrieving tasks for the selected milestone");
                        }
                    });
                });


                // When supervisor changes, update the displayed supervisor name
                $("#supervisor").change(function() {
                    var supervisor_name = $(this).find('option:selected').text();
                    $('#superviser').text(supervisor_name);
                });


            });


            function getProductDetail(id, number) {
                //var supplier_id=$('#sup').val();
                // console.log(supplier_id);

                $.ajax({
                    type: "POST",
                    url: "<?php echo e(url('admin/purchase/get_product_details/')); ?>" + id,
                    data: JSON,
                    success: function(data) {

                        console.log(data);
                        $("#description" + number).text(product_data[0].description);
                        $("#uom" + number).val(product_data[0].base_uom);
                    },
                    error: function() {
                        toastr.error("Description or UOM Not Found");
                    }
                });
            }


            $(document).on('click', '.remove-input-field', function() {
                $(this).parents('tr').remove();

            });



            $(document).ready(function() {
                $("#category_id_prili").on("change", function() {

                    let id = $(this).val();

                    let jsArray = <?php echo json_encode($budgtrepo, 15, 512) ?>;


                    jsArray = jsArray.data.filter((re) => re.template_id == id);
                    let op = `<option value="" selected>Task</option>`;
                    op += jsArray.map(re => `<option value="${re.description}">${re.description}</option>`).join('');

                    $("#task_id").html(op);



                })
            })
        </script>
        <?php endif; ?>

        <?php if(isset($page['section']) && $page['section'] == 'show'): ?>
        <?php
        $all_items=DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id',$note->purchase_requistion_id)->get();

        ?>
        <div class="row" id="babag_id">

            <div class="box-body">

                <div class="form-body">

                    <div class="invoice">
                        <div class="left">
                            <input type="hidden" name="uniq_id" id="" value="<?php echo e($note->purchase_requistion_id); ?>">
                            <input type="hidden" name="project_id" id="" value="<?php echo e($note->project_id); ?>">
                            <label>Project Name : <?php echo e($budgtrepo[0]->q_title??''); ?></label>

                            <input type="hidden" name="project_id" value="<?php echo e($budgtrepo[0]->project_id??''); ?>">
                            <select class="form-control" name="milestone_id" id="category_id_prili">
                                <option>Choose Category</option>
                                <?php foreach ($templete_category as $k => $purchase) { ?>
                                    <option value="<?php echo $purchase->milestonecategory_id; ?>" <?php echo e($purchase->milestonecategory_id==$note->mile_stone?'selected':''); ?>><?php echo $purchase->milestonecategory_title; ?></option>
                                <?php } ?>
                            </select>
                            <label>Task</label>
                            <select name="task_id" id="task_id" class="form-control" data-plugin="select_hrm">
                                <option value="<?php echo $note->task; ?>"><?php echo $note->task; ?></option>
                            </select>
                        </div>
                        <br />
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Material Requisition Form (MRF)(MRF)</b></td>
                                <!-- <td style="width: 33.33%;text-align:left;"><b>MRF No.</b>:<input type="text" name="mrf_no" class="form-control"></td> -->
                                <td style="width: 33.33%;text-align:left;border-right:none;border:1px solid;"></td>
                                <!-- <td rowspan="3" style="width: 10%;border-right:none;"></td> -->
                                <td rowspan="6" style="width: 50%;border-right:none; align-items: center!important;">
                                    <img src="<?php echo e(asset('uploads/logo/logo-with-bizsafe.png')); ?>" class="img-fluid" width="200px" alt="">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Project Department-Purchasing Department</b></td>
                                <td style="width: 33.33%;text-align:left; border:1px solid;"><b>MRF Date: <?php echo date('d-m-Y') ?></b>
                                    <input type="hidden" name="order_date" class="date form-control" value="<?php echo date('d-m-Y') ?>">
                                </td>
                                <!-- <td style="width: 20%;"></td>
                    <td style="width: 30%;border-right:none;"></td> -->

                            </tr>
                            <tr>
                                <td style="width: 33.33%;text-align:left;border:1px solid;" colspan="2"><b>Site:</b>
                                    <input type="text" name="site_address" id="site_address" class="form-control" value="<?php echo e($note->site_address??''); ?>">
                                </td>
                                <!-- <td style="width: 33.33%;"></td> -->
                                <!-- <td style="width: 20%;"></td>
                    <td style="width: 30%;border-right:none;"></td> -->

                            </tr>
                        </table>
                        <table>
                            <tr>
                                <!-- <td style="width:30%;text-align:left;">
                <input type="checkbox" id="site4" name="chk_site1[]" value="office">
                <label for="site4"> OFFICE</label>
                </td> -->
                                <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site4" name="check1[]" value="Storeroom No Stock.I have checked with Storeman"><label for="site4">Storeroom No Stock.I have checked with Storeman.</label></td>
                                <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site5" name="check1[]" value="Supervisor has checked with Engineer before ordering"><label for="site5">Supervisor has checked with Engineer before ordering.</label></td>

                            </tr>
                            <tr>
                                <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site6" name="check1[]" value="Please check Yishun Storeroom before you order"><label for="site6">Please check Yishun Storeroom before you order.</label></td>
                                <td style="width:30%;text-align:left;border:1px solid;"><input type="checkbox" id="site7" name="check1[]" value="We have already checked with Boss to order"><label for="site7">We have already checked with Boss to order.</label></td>


                            </tr>
                            <!-- <tr>
                    <td style="width:30%;text-align:left;"><input type="checkbox" id="site8" name="check1[]" value="Book Crane Lorry"><label for="site8">Book Crane Lorry</label></td>
                </tr> -->
                        </table>
                        <div style="text-align: right;">

                        </div>
                        <table style="margin-top: 5px;">
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
                            <tbody class="AddItem" id="vendor_items_table1">
                                <?php
                                $i = 0;
                                foreach ($all_items as $item) {
                                    $i++; ?>
                                    <tr>
                                        <td><label><?php echo $i; ?><label></td>
                                        <td>
                                            <select class="packing_dropdown form-control select22" name="product_id[]" id="product_<?php echo $i; ?>" onchange="getProductDetail(this.value,` + item + `)">
                                                <option value="">Select product</option>
                                                <?php foreach ($all_products as $product) {
                                                    echo '<option value="' . $product->product_id . '"' . ($product->product_id == $item->product_id ? "selected" : "") . '>' . $product->product_name . '</option>';
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="qty<?php echo $i; ?>" class="form-control" name="qty[]" placeholder="Qty" value="<?php echo $item->qty; ?>">
                                        </td>
                                        <td>
                                            <input type="text" id="level<?php echo $i; ?>" class="form-control" name="level[]" value="<?php echo $item->level; ?>">
                                        </td>

                                        <td>
                                            <input type="text" id="use<?php echo $i; ?>" class="form-control" name="use[]" value="<?php echo $item->where_use; ?>">
                                        </td>
                                        <td>
                                            <input type="text" id="sub_con<?php echo $i; ?>" class="form-control" name="sub_con[]" value="<?php echo $item->sub_con; ?>">
                                        </td>
                                        <td>
                                            <input type="text" id="po_no<?php echo $i; ?>" class="form-control" name="po_no[]" value="<?php echo $item->po_no; ?>">
                                        </td>
                                        <td>
                                            <input type="text" id="do_no<?php echo $i; ?>" class="form-control" name="do_no[]" value="<?php echo $item->do_no; ?>">
                                        </td>

                                    </tr>

                                <?php

                                }
                                ?>


                            </tbody>

                        </table>
                        <table>
                            <tr>
                                <td style="width:70%;text-align:left;border:1px solid;">
                                    <label>Name of Supervisor who order:</label>
                                    <select class="form-control" name="supervisor" id="supervisor">
                                        <option>Select Supervisor</option>
                                        <?php $__currentLoopData = $dataSupervisor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->user_id); ?>" <?php echo e(($customer->user_id==$project_d[0]->Supervisor)?"selected":""); ?>>
                                            <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <br />
                                    <label> Sub contractor Company:</label>
                                    <!-- <input type="text" class="form-control" name="sub_contractor"> -->
                                    <select class="form-control" name="sub_contractor">
                                        <option>Select Sub-contractor</option>
                                        <?php $__currentLoopData = $all_customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->user_id); ?>" <?php echo e($customer->user_id == $note->sub_contractor ? 'selected' : ''); ?>>
                                            <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td style="width:30%;text-align:left;border:1px solid;">
                                    <label>Date,Name & Signature of Engineer who check this order: </label><br /><br />
                                    <?php echo e(get_designation_detail($project_d[0]->Engineer)); ?>


                                </td>

                            </tr>
                            <tr>
                                <td style="width:70%;text-align:left;border:1px solid;">
                                    <label><u>Signature:</u></label><br />
                                    <label>Requested by site Supervisor </label>
                                    <div id="superviser"></div>

                                </td>
                                <td style="width:30%;text-align:left;border:1px solid;">
                                    <label>Date of Materials required: </label><br />
                                    <p>Earliest Date:<input type="date" value="<?php echo e($note->earliest_date); ?>" name="earliest_date" class="date form-control"></p><br />
                                    <p>Latest Date:<input type="date" name="latest_date" value="<?php echo e($note->latest_date); ?>" class="date form-control"></p>
                                    <br />
                                </td>

                            </tr>
                        </table>
                        <div>
                            <label>Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value=""><?php echo e($note->status??''); ?></option>
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





            </div>
            <script>
                $(document).ready(function() {

                    $('#babag_id input').prop('disabled', true);
                    $('#babag_id select').prop('disabled', true);

                })
            </script>




            <?php endif; ?>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/prq/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>