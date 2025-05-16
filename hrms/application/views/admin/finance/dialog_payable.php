<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['payable_id']) && $_GET['data'] == 'payable') {

?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data">Payable Info</h4>
    </div>
    <?php $attributes = array('name' => 'edit_payable', 'id' => 'edit_payable', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['payable_id'], 'ext_name' => $_GET['payable_id']); ?>
    <?php echo form_open_multipart('admin/payable/update', $attributes, $hidden); ?>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="edit_type" value="payable">
            <input type="hidden" name="payable_id" value="<?php echo $_GET['payable_id']; ?>">
            <input type="hidden" name="purchase_order_id" value="<?php echo isset($purchase_order_id) ? $purchase_order_id : ''; ?>">


            <div class="col-md-12">
                <div class="form-group">
                    <label id="after_gst_po_gt">Total Amount: <?php echo "s$ " . number_format(isset($po_gt) ? $po_gt : 0, 2); ?></label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Invoice Number: <?php echo  $invoice_no ?></label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>PO Number: <?php echo  $purchase_order_no ?></label>
                    <input type="hidden" name="purchase_order_no1" id="purchase_order_no1" value="<?php echo $purchase_order_no; ?>">
                </div>
            </div>
            <?php if ($due_date) { ?>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="paid_amount">Due Date:</label>
                        <label><?php echo $due_date; ?></label>
                    </div>
                </div>
            <?php } else if ($manual_due_date) { ?>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="paid_amount">Due Date:</label>
                        <label><?php echo $manual_due_date; ?></label>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="paid_amount">Paid Amount:</label>
                    <label><?php echo "$" . number_format($total_paid_amount[0]->paid_amount, 2); ?></label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="total_amount">Remaining Amount:</label>
                    <p id="remaining_amount1"></p>
                    <input type="hidden" name="hdn_remaining_amount1" id="hdn_remaining_amount1">
                   

                </div>
            </div>

            <?php
            $attachment = $this->db->select('exp_attachment')->from('xin_payable')->where('purchase_order_id', $purchase_order_id)->where_not_in('exp_attachment', '')->get()->num_rows();
            $attachment1 = $this->db->select('exp_attachment')->from('xin_payable')->where('purchase_order_id', $purchase_order_id)->where_not_in('exp_attachment', '')->get()->result();
            // print_r($attachment1);exit;
            if ($attachment == 1) { ?>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="amount">Attachment File</label>
                        <label><a href="<?php echo base_url() . 'uploads/payment/' . $attachment1[0]->exp_attachment; ?>" target="_blank">View Here</a></label>
                        <input type="file" name="payment_picture_edit" id="payment_picture_edit" style="display:none;" class="form-control">
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="amount">Attachment File</label>
                        <input type="file" name="payment_picture_edit" id="payment_picture_edit" class="form-control">
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-12 d-none">
                <div class="form-group">
                    <label for="invoice_no">Purchase Invoice No.</label>
                    <input type="text" name="invoice_no1" id="invoice_no1" class="form-control" placeholder="Invoice No." value="<?php echo $invoice_no; ?>">
                </div>
            </div>
            <div class="col-md-12 d-none">
                <div class="form-group">
                    <label for="do_no1">DO No.</label>
                    <input type="text" name="do_no1" id="do_no1" class="form-control" placeholder="DO No." value="<?php echo $do_no; ?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="amount">Amount ($)</label>
                    <input type="text" name="amount1" id="amount1" class="form-control" placeholder="Amount" oninput="calculation_payable()">
                </div>
            </div>

            <div class="col-md-12 d-none">
                <div class="form-group">
                    <label for="payable_total_amount">Total Amount ($)</label>
                    <input type="text" name="payable_total_amount1" id="payable_total_amount1" class="form-control" placeholder="Total Amount" value="<?php echo $total; ?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Payment Details</label>
                    <textarea class="form-control" name="pay_detail1"><?php echo $pay_detail ?></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Remark</label>
                    <textarea class="form-control" name="remark1"><?php echo $remark ?></textarea>
                </div>
            </div>


            <?php if ($due_date) { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input class="form-control" id="due_date1" name="due_date1" type="date"
                            value="<?php echo ($due_date == '') ? '' : date('Y-m-d', strtotime($due_date));
                                    ?>">
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input class="form-control" id="manual_due_date1" name="manual_due_date1" type="date"
                            value="<?php echo ($manual_due_date == '') ? '' : date('Y-m-d', strtotime($manual_due_date));
                                    ?>">

                    </div>
                </div>
            <?php } ?>

            <div class="col-md-6">
                <label for="payment_type"><?php echo $this->lang->line('xin_payment_type'); ?></label>

                <select class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_type'); ?>" name="payment_type1">
                    <option value="">Select Payment Method</option>
                    <?php foreach ($get_payment_methods as $methods) { ?>
                        <option value="<?php echo $methods->method_name; ?>" <?php echo (($methods->payment_method_id == $payment_type) ? "selected" : ""); ?>>
                            <?php echo $methods->method_name; ?></option>
                    <?php } ?>
                </select>
            </div>

            <hr />

            <?php if (count($get_payables_list) > 0) { ?>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="total_amount">Payment Transaction Details</label>
                        <table class="table table-bordered">
                            <tr>

                                <th>Paid Amount</th>
                                <th>Paid Date Time</th>
                                <th>Payment Mode</th>
                                <th>Remark</th>
                                <th>Payment Details</th>

                            </tr>
                            <?php
                            foreach ($get_payables_list as $r) {
                            ?>
                                <tr>

                                    <td><?php echo "$" . $r->total; ?></td>
                                    <td><?php echo $r->modified_datetime; ?></td>
                                    <td><?php echo $r->payment_type ?></td>
                                    <td><?php echo $r->remark; ?></td>
                                    <td><?php echo $r->pay_details; ?></td>


                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            <?php } ?>



            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
            </div>
            <?php echo form_close(); ?>

        <?php } else { ?>
            <style>
                #ajax_modal_view {
                    width: 1100px !important;
                    margin-left: -190px;
                }
            </style>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="edit-modal-data">Payable Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="edit_type" value="payable">
                    <input type="hidden" name="payable_id" value="<?php echo $_GET['payable_id']; ?>">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Invoice Number: <?php echo  $invoice_no ?></label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>PO Number: <?php echo  $purchase_order_no ?></label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="total_amount">Payable Total Amount:</label>
                            <label><?php echo "$" . $po_gt; ?></label><br>
                            <!-- <label>GST <?php echo "(" . $gst_on_total . "%):" ?> <?php echo "s$ " . $gst_num ?></label><br>
                            <label>Grand Total: <?php echo "s$ " . $po_gt ?></label> -->
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="paid_amount">Total Paid Amount:</label>
                            <label><?php echo "$" . number_format($total_paid_amount[0]->paid_amount, 2); ?></label>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="amount">Remaining Amount:</label>
                            <label><?php echo "$" . ($po_gt - ($total_paid_amount[0]->paid_amount)); ?> </label>
                        </div>
                    </div>


                    <div class="col-12">
                        <label for="status" class="form-label">Status:</label>
                        <label><?php echo ucfirst($status); ?></label>

                        </select>
                        <!-- <input class="form-control" type="text" value="" id="total_gst1" name="gst"
                                            placeholder="0" onkeyup="totalGSTAmount()"> -->
                    </div>

                    <?php if ($attachment != "") { ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="due_date">Attachment:</label>
                                <label><a href="<?php echo base_url() . 'uploads/payment/' . $attachment; ?>">View Here</a></label>
                            </div>
                        </div>
                    <?php } ?>


                    <?php if (count($get_payables_list) > 0) { ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total_amount">Payment Transaction Details</label>
                                <table class="table table-bordered">
                                    <tr>

                                        <th>Paid Amount</th>
                                        <th>Paid Date Time</th>
                                        <th>Payment Mode</th>
                                        <th>Remark</th>

                                    </tr>
                                    <?php
                                    foreach ($get_payables_list as $r) {
                                    ?>
                                        <tr>

                                            <td><?php echo "$" . $r->total; ?></td>
                                            <td><?php echo $r->modified_datetime; ?></td>
                                            <td><?php echo $r->payment_type ?></td>
                                            <td><?php echo $r->remark; ?></td>


                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>

                    </div>
                <?php } ?>

                <script>
                    $(document).ready(function() {
                        var total_amount = parseFloat('<?php echo $po_gt; ?>');
                        var pay_amount = parseFloat($("#amount1").val()) || 0;
                        var paid_amount = parseFloat('<?php echo $total_paid_amount[0]->paid_amount; ?>');

                        var remaining_amount = parseFloat(total_amount - paid_amount);
                        console.log(total_amount)
                        // Convert to fixed-point arithmetic with 2 decimal places
                        remaining_amount = remaining_amount;

                        $("#hdn_remaining_amount1").val(remaining_amount);
                        $("#remaining_amount1").text(`$${remaining_amount}`);
                        $("#payable_total_amount1").val(pay_amount);
                    });





                    function calculation_payable() {
                        var pay_amount = 0;
                        var total_amount = <?php echo $po_gt; ?>; // Assuming $po_gt is a PHP variable
                        pay_amount = parseFloat($("#amount1").val()); // Parse as float

                        $("#payable_total_amount1").val(pay_amount); // Adjusted this line

                        var final_amount = parseFloat($("#amount1").val()); // Parse as float
                        var paid_amount = <?php echo $total_paid_amount[0]->paid_amount; ?>; // Assuming $total_paid_amount is a PHP variable
                        var remaining_amount = parseFloat($("#hdn_remaining_amount1").val()); // Parse as float

                        if (!isNaN(pay_amount) && pay_amount > 0) {
                            remaining_amount = total_amount - (parseFloat(paid_amount) + pay_amount); // Parse as float
                        } else {
                            remaining_amount = parseFloat(total_amount) - parseFloat(paid_amount); // Parse as float
                        }

                        $("#remaining_amount1").text(`$${remaining_amount.toFixed(2)}`);
                        // Corrected the method name toFixed()
                    }
                </script>

                <script>
                    $("#edit_payable").submit(function(e) {
                        /*Form Submit*/
                        var fd = new FormData(this);
                        var remaining_amount1 = $('#remaining_amount1').text();
                        var agst_po_t = $('#after_gst_po_gt').text();
                        var t = parseFloat(agst_po_t.match(/\d+/)[0]);
                        var obj = $(this),
                            action = obj.attr('name');
                        fd.append('a_gst_po_gt', t)
                        fd.append("remaining_amount1", remaining_amount1);
                        fd.append("is_ajax", 1);
                        fd.append("data", 'edit_payable');
                        fd.append("type", 'payable');
                        fd.append("form", action);

                        e.preventDefault();

                        $('.save').prop('disabled', true);
                        $('.icon-spinner3').show();
                        $.ajax({
                            type: "POST",
                            // url: e.target.action,
                            url: base_url + "/update",

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
                                    var xin_table = $('#xin_table').dataTable({
                                        "bDestroy": true,
                                        "ajax": {
                                            url: base_url + "/payable_list/",
                                            type: 'GET',

                                        },

                                    });
                                    xin_table.api().ajax.reload(function() {
                                        toastr.success(JSON.result);
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

                            }
                        });
                    });
                </script>