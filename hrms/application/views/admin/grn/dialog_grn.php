<style>
    #ajax_modal {
        width: 1250px !important;
        margin-left: -150px;
    }
</style>
<?php defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['grn_id']) && $_GET['data'] == 'edit_grn') {
?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data">View GRN</h4>
    </div>

    <?php $attributes = array('name' => 'edit_grn', 'id' => 'edit_grn', 'autocomplete' => 'off', 'class' => 'edit_grn m-b-1 in'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['grn_id'], 'ext_name' => $_GET['grn_id']); ?>
    <?php echo form_open_multipart('admin/purchase/grn_update1', $attributes, $hidden); ?>
    <div class="modal-body">
        <?php if ($status == 'Complete') { ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="text-success" for="total_amount">Receive Complete:</label>
                    <label class="text-success"><?php echo date('d-M-Y', strtotime($date)); ?></label>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <input type="hidden" name="grn_id" value="<?php echo $_GET['grn_id']; ?>">
            <input type="hidden" name="order_id" value="<?php echo $_GET['order_id']; ?>">
            <input type="hidden" name="proj_id" id="proj_id" value="<?php echo ($purchase_order_id == 'M') ? 0 : $purchase_order_id ?>">
            <input type="hidden" name="recive[]" value="recive">

            <div class="col-md-12">
                <div class="form-group">
                    <label>Project Name:</label>
                    <p id="p_name1"></p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Project Address:</label>
                    <p id="p_address1"></p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Employee Name:</label>
                    <p id="e_name1"></p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Purchase Order Number:</label>
                    <p id="pr_no1"></p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Supplier Name:</label>
                    <p id="sup_name1"></p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="total_amount">GRN Number:</label>
                    <?php echo $grn_id; ?>
                </div>
            </div>
            <!-- <div class="col-md-12">
                <div class="form-group">
                    <label for="total_amount">Warehouse:</label>
                </div>
            </div> -->
            <div class="col-md-12">
                <?php if ($status != 'Complete') { ?>
                    <table class="table table-striped" style="overflow-y: scroll !important;" width="10px">
                        <thead>
                            <tr>
                                <!-- <th>Supplier</th> -->
                                <th>Item</th>
                                <th>Unit</th>
                                <th>Warehouse</th>
                                <th>Quantity Need</th>
                                <th>Quantity Receive</th>
                                <th>Quantity Remaining</th>
                                <th>DO Number</th>
                                <th>DO File</th>
                                <th>Remark</th>
                                <th>Receive Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;




                            foreach ($order_items as $item) {

                                $i++;
                                if ($item->go_status != "Complete") { ?>

                                    <tr data-qty-need="<?php echo $item->qty_need; ?>">

                                        <!-- <td><?php echo $item->supplier_name; ?></td> -->
                                        <td><?php echo $item->product_name; ?></td>
                                        <td><?php echo $item->std_uom; ?></td>
                                        <td>
                                            <?php $wh = $this->db->get('warehouse')->result(); ?>
                                            <select id="2house_<?php echo $i; ?>" name="house2[]" class="form-control" data-plugin="select_hrm" data-placeholder="Select Warehouse">
                                                <!-- <option value="">Select Warehouse</option> -->

                                                <?php foreach ($wh as $w) { ?>
                                                    <option value="<?php echo $w->w_id ?>"><?php echo $w->w_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><?php echo $item->qty_need; ?></td>
                                        <td>
                                            <input type="hidden" name="prd_id1[]" value="<?php echo $item->prd_id; ?>">
                                            <!-- <input type="hidden" name="prd_uom1[]" value="<?php //echo $item->prd_uom; 
                                                                                                ?>"> -->
                                            <input type="hidden" name="sup_id1[]" value="<?php echo $item->supplier_id; ?>">
                                            <input type="hidden" name="w_prd_qtn1[]" id="w_prd_qtn_<?php echo $i; ?>" value="<?php echo $item->qty_need; ?>">
                                            <input type="text" name="r_prd_qtn1[]" id="r_prd_qtn_<?php echo $i; ?>" class="form-control" oninput="calrem1(<?php echo $i; ?>)">
                                        </td>
                                        <td>
                                            <input type="hidden" name="rem_prd_qtns[]" id="rem_prd_qtn1_<?php echo $i; ?>" value="<?php echo ($item->qty_rem) ?? 0; ?>">
                                            <input type="text" readonly name="rem_prd_qtn1[]" id="rem_prd_qtn_<?php echo $i; ?>" value="<?php echo ($item->qty_rem) ?? 0; ?>" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="u_do1[]" id="u_do_<?php echo $i; ?>" class="form-control">
                                        </td>
                                        <td>
                                            <input type="file" name="u_dofile1[]" id="u_dofile_<?php echo $i; ?>" class="form-control" style="width: 50px; height: 50px;">
                                        </td>
                                        <td>
                                            <textarea rows="4" style="width: 150px;" name="u_rek1[]" id="u_rek_<?php echo $i; ?>" class="form-control"></textarea>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control" placeholder="Select Date" name="u_date1[]" id="u_date_<?php echo $i; ?>" value="<?php echo date('Y-m-d') ?>">
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                <?php } ?>
                <?php
                $grn_log =
                    // $this->db->select('*')->from('grn_log')
                    $this->db->select('product.product_name as gprd_name, 
                            product.std_uom,
                            grn_log.qtn, 
                            grn_log.remark, 
                            grn_log.do_file, 
                            grn_log.do_no, 
                            grn_log.date,
                            grn_log.wh_no,
                            warehouse.w_name AS gwname')
                    ->from('grn_log')
                    ->join('product', 'grn_log.item = product.product_id', 'left')
                    // ->join('product_uom_mapping', 'grn_log.item_uom = product_uom_mapping.uom_id')
                    ->join('warehouse', 'grn_log.wh_no = warehouse.w_id', 'left')
                    ->where('grn_id', $_GET['grn_id'])
                    // ->where_not_in('wh_no',"p")
                    ->get()
                    ->result();
                ?>

                <table class="table table-striped">
                    <thead>
                        <h2>GRN Log</h2>
                        <tr>
                            <th>Sl</th>
                            <th>Item</th>
                            <th>Unit</th>
                            <th>Quantity Receive</th>
                            <th>Remark</th>
                            <th>DO Number</th>
                            <th>DO File</th>
                            <th>Receive Date</th>
                            <th>Warehouse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php


                        if (empty($grn_log)) {
                        ?>
                            <tr>
                                <td colspan="9">No Data Found</td>
                            </tr>
                            <?php
                        } else {
                            $i = 0;
                            foreach ($grn_log as $grn) {
                                $i++;
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $grn->gprd_name; ?></td>
                                    <td><?php echo $grn->std_uom; ?></td>
                                    <td><?php echo $grn->qtn; ?></td>
                                    <td><?php echo $grn->remark; ?></td>
                                    <td><?php echo $grn->do_no; ?></td>
                                    <td><?php if ($grn->do_file) { ?><a href="<?php echo base_url('uploads/grn/' . $grn->do_file) ?>" target="_blank">Click to View</a><?php } ?></td>
                                    <td><?php echo date('d-M-Y', strtotime($grn->date)); ?></td>
                                    <td><?php echo ($grn->wh_no == "p") ? "Project Site" : $grn->gwname; ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="modal-footer">
            <?php if ($status == 'Complete') { ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
            <?php } else { ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo "Receive"; ?></button>
            <?php } ?>
        </div>
    </div>
    <?php echo form_close(); ?>

    <script>
        function calrem1(index) {
    // Select the current row using the `index`
    var row = document.querySelector(`#r_prd_qtn_${index}`).closest('tr');
    var qtyNeed = parseInt(row.getAttribute('data-qty-need')) || 0;
    var prevRemQty = parseInt(document.getElementById(`rem_prd_qtn1_${index}`).value) || 0;
    var initialRemQty = qtyNeed;

    // If remaining quantity is already set, use it instead of initialRemQty
    if (prevRemQty != 0) {
        initialRemQty = prevRemQty;
    }

    // Get the input value for the received quantity
    var r_prd_qtn = parseInt(document.getElementById(`r_prd_qtn_${index}`).value) || 0;

    // Ensure received quantity is within valid range
    if (r_prd_qtn > initialRemQty) {
        alert('Received quantity cannot be greater than required quantity');
        r_prd_qtn = initialRemQty;
        document.getElementById(`r_prd_qtn_${index}`).value = initialRemQty;
    }

    // Calculate the remaining quantity
    var rem_prd_qtn = initialRemQty - r_prd_qtn;

    // Display the remaining quantity in the corresponding field
    document.getElementById(`rem_prd_qtn_${index}`).value = rem_prd_qtn;

    // Get the warehouse number (w_no) for the current row
    var w_no = row.querySelector(`[id^='2house_']`).value; // Adjust selector if necessary

    // Validate if warehouse is required or display it
    if (!w_no || w_no.trim() === "") {
        alert("Warehouse is required for this row.");
    } else {
        console.log("Warehouse Number:", w_no);
    }
}


        $(document).ready(function() {
            getPODetails();
            var xin_table = $('#xin_table_grn').dataTable({
                "bDestroy": true,
                "ajax": {
                    url: base_url + "/grn_list/",
                    type: 'GET'
                }
            });

            /*Form Submit*/
            $("#edit_grn").submit(function(e) {
                var fd = new FormData(this);
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("is_ajax", 1);
                fd.append("edit_type", 'edit_grn');
                fd.append("form", action);
                e.preventDefault();

                // e.preventDefault();
                // var obj = $(this),
                //     action = obj.attr('name');
                $('.save').prop('disabled', true);
                $('.icon-spinner3').show();
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
                            $('.edit-modal-data').modal('toggle');
                            xin_table.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                            }, true);
                            // window.location.reload();
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.edit_grn').removeClass('in');
                            $('.save').prop('disabled', false);
                        }
                    }
                });
            });

            function getPODetails() {
                var pur_order = <?php echo $purchase_order_id ?>;
                var grnId = "<?php echo isset($_GET['grn_id']) ? $_GET['grn_id'] : ''; ?>";

                var requestUrl = "<?php echo base_url(); ?>admin/purchase/getPODetails/" + pur_order + "/" + <?php echo $_GET['grn_id'] ?>;

                $.ajax({
                    type: "POST",
                    url: requestUrl,
                    success: function(data) {
                        try {
                            var product_data = jQuery.parseJSON(data);
                            console.log(product_data);
                            populatePODetails(product_data);
                        } catch (error) {
                            console.error("Error parsing response:", error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("Failed to fetch purchase order details. Please try again.");
                    }
                });
            }

            function populatePODetails(product_data) {
                if (product_data.po_detail && product_data.po_detail.length > 0) {
                    $("#proj_id").val(product_data.po_detail[0].project_id);
                    $('[name="house2[]"]').val(product_data.po_detail[0].warehouse_id).trigger('change');
                    $("#p_name1").text(product_data.po_detail[0].project_title);
                    $('#p_address1').text(product_data.po_detail[0].site_add);
                    $("#e_name1").text(product_data.po_detail[0].first_name + " " + product_data.po_detail[0].last_name);
                    $("#sup_name1").text(product_data.po_detail[0].supplier_name);
                } else {
                    console.error("No purchase order details found.");
                }
            }


        });
    </script>

<?php } ?>