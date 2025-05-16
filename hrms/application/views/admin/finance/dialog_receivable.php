<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['invoice_id']) && $_GET['data'] == 'receivable') {
    $query = $this->db->where('invoice_id', $_GET['invoice_id'])->get('xin_receivable')->num_rows();
    if ($query > 0) {

        $total_amount = $total_paid_amount[0]->after_gst_inv_gt;
?>
        <?php $system = $this->Xin_model->read_setting_info(1); ?>
        <?php $session = $this->session->userdata('username'); ?>
        <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="edit-modal-data">Add Receivable</h4>
        </div>
        <?php $attributes = array('name' => 'edit_receivable', 'id' => 'edit_receivable', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
        <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['invoice_id'], 'ext_name' => $_GET['invoice_id']); ?>
        <?php echo form_open('admin/receivable/add', $attributes, $hidden); ?>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" name="edit_type" value="receivable">
                <input type="hidden" name="invoice_id" value="<?php echo $_GET['invoice_id']; ?>">
                <?php if ($total_paid_amount != "" || $total_paid_amount > 0) {
                    $invoice_total_amount =  $total_amount - $total_paid_amount[0]->paid_amount;
                } else {
                    $invoice_total_amount = $total_amount;
                }
                ?>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="total_amount">Invoice Date:</label>
                        <label><?php echo  $result[0]->invoice_date; ?></label>

                    </div>
                </div>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="total_amount">Invoice Number:</label>
                        <label><?php echo $result[0]->invoice_no; ?></label>
                    </div>
                </div>

                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="total_amount">Total Invoice Amount:</label>
                        <label>$<?php echo $total_amount; ?></label>
                        <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $total_amount; ?>">
                    </div>
                </div>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="total_amount">Remaining Amount:</label>
                        <p id="remaining_amount"></p>
                        <input type="hidden" name="hdn_remaining_amount" id="hdn_remaining_amount">
                    </div>
                </div>
                <?php if ($total_amount != $invoice_total_amount) { ?>
                    <div class="col-md-12 main_form">
                        <div class="form-group">
                            <label for="remaining_total_amount">Total Remaining Amount:</label>
                            <label>$<?php echo $invoice_total_amount; ?></label>
                            <input type="hidden" name="hdn_remaining_amount1" id="hdn_remaining_amount1" value="<?php echo $invoice_total_amount; ?>">
                        </div>
                    </div>
                <?php } ?>
                <?php
            $attachment = $this->db->select('attachment')->from('xin_receivable')->where('invoice_id', $_GET['invoice_id'])->where_not_in('attachment', '')->get()->num_rows();
            $attachment1 = $this->db->select('attachment')->from('xin_receivable')->where('invoice_id', $_GET['invoice_id'])->where_not_in('attachment', '')->get()->result();
            // print_r($attachment1);exit;
            if ($attachment == 1) { ?>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="amount">Attachment File</label>
                        <label><a href="<?php echo base_url() . 'uploads/payment/' . $attachment1[0]->attachment; ?>" target="_blank">View Here</a></label>
                        <input type="file" name="payment_picture" id="payment_picture" style="display:none;" class="form-control">
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="amount">Attachment File</label>
                        <input type="file" name="payment_picture" id="payment_picture" class="form-control">
                    </div>
                </div>
            <?php } ?>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="amount">Paying Amount</label>
                        <input type="text" name="pay_amount" id="pay_amount" class="form-control">
                    </div>
                </div>

                <div class="col-md-12 main_form">
                    <label for="payment_type"><?php echo $this->lang->line('xin_payment_type'); ?></label>
                    <select class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_type'); ?>" name="payment_type">
                        <option value="">Select Payment Method</option>
                        <?php foreach ($get_payment_methods as $methods) { ?>
                            <option value="<?php echo $methods->method_name; ?>">
                                <?php echo $methods->method_name; ?></option>
                        <?php } ?>
                    </select>
                </div>




                <?php if (count($get_receivables) > 0) { ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="total_amount">Payment Transaction Details</label>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Paid Amount</th>
                                    <th>Paid Date Time</th>
                                    <th>Payment Type</th>

                                </tr>
                                <?php
                                foreach ($get_receivables as $r) {
                                ?>
                                    <tr>
                                        <td><?php echo $r->total; ?></td>
                                        <td><?php echo $r->created_datetime; ?></td>
                                        <td><?php echo $r->payment_type; ?></td>

                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                <?php } ?>

                <div class="modal-footer main_form">
                    <button type="button" class="btn btn-secondary abc" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>

                    <?php if ($invoice_total_amount == 0) { ?>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
                    <?php } ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <script>
            $(document).ready(function() {

                var total_amount = '<?php echo $total_amount; ?>';
                <?php if (count($get_receivables) > 0) { ?>
                    $('.main_form').show();

                <?php } else { ?>
                    $('.main_form').show();
                <?php } ?>

                $("#pay_amount").keyup(function() {

                    if ($('#hdn_remaining_amount1').val() != undefined) {
                        var total = $('#hdn_remaining_amount1').val();

                    } else {
                        var total = '<?php echo $total_amount; ?>';

                    }
                    var pay_amount = $("#pay_amount").val();
                    var remaining_amount = parseFloat(total) - parseFloat(pay_amount);
                    $("#hdn_remaining_amount").val(remaining_amount);
                    $("#remaining_amount").text('$' + remaining_amount);


                });



                $('.date').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    format: 'dd-mm-yyyy',
                    yearRange: '1900:' + (new Date().getFullYear() + 15),
                    beforeShow: function(input) {
                        $(input).datepicker("widget").show();
                    }

                });



            });


            function totalGSTAmount() {
                var pay_total_amount = 0;
                var total_amount = $('#def_val').text();
                var paid_amount = 0;

                var pay_total_amount = parseFloat($('#amount').val());

                var tax = parseFloat($("#payable_total_gst option:selected").text());
                var total = pay_total_amount + pay_total_amount * (tax / 100);

                var remaining_amount = parseFloat(total_amount) - (parseFloat(paid_amount) + parseFloat(total));
                $('#payable_total_amount').val(total);
                $("#remaining_amount").val(remaining_amount);
            }
        </script>
        <script>
            $(document).ready(function() {
                var total_amount = $('#def_val').text();
                var paid_amount = 0;
                var remaining_amount = parseFloat(total_amount) - (parseFloat(paid_amount));
                $("#remaining_amount").val(remaining_amount);

                $("#is_payable_gst").change(function() {
                    if (this.checked) {
                        $("#payable_gst_div").hide();
                        var total_amount = parseFloat($('#amount').val());
                        $('#total_amount1').val(total_amount);
                    } else {
                        $("#payable_gst_div").show();
                        var total_amount = parseFloat($('#amount').val());
                        var tax = parseFloat($("#payable_total_gst option:selected").text());
                        var total = total_amount + total_amount * (tax / 100);
                        $('#payable_total_amount').val(total);
                    }
                });
            });
        </script>
        <script>
            $("#edit_receivable").submit(function(e) {
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $('.icon-spinner3').show();
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url() . 'admin/receivable/add' ?>",
                    data: obj.serialize() + "&is_ajax=1&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        toastr.success(JSON.result);
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
            })
        </script>
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
            <h4 class="modal-title" id="edit-modal-data">Add Receivable</h4>
        </div>
        <?php $attributes = array('name' => 'add_receivable', 'id' => 'add_receivable', 'autocomplete' => 'off'); ?>

        <?php echo form_open_multipart('admin/Receivable/add', $attributes); ?>
        <input type="hidden" name="invoice_id" value="<?php echo $_GET['invoice_id']; ?>">
        <div class="form-body">
            <div class="box-body">

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Rate</th>
                                    <th>Amount</th>

                                </tr>
                            </thead>
                            <tbody class="AddItem2" id="conf_pr_data">

                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-8"></div>
                    <div class="col-sm-2 float-right">
                        <br>

                    </div>
                    <div class="col-md-2">
                        <div class="">
                            <label>Order Total: </label>&nbsp;<span id="ord_total"><?php echo $result[0]->sub_total ?></span><br>
                            <label id="n_gst_val">GST(<?php echo $result[0]->gst ?> %)</label>
                            <span id="gst_val" name="gst_val"><?php echo $result[0]->gst_value ?></span><br>
                            <label>Grand Total: </label>&nbsp;<span id="def_val"><?php echo $result[0]->total ?></span>
                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-3">

                        <div class="form-group">
                            <label for="amount">Receiving Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" oninput="calculation()">
                        </div>
                    </div>

                    <div class="col-md-3 d-none">

                        <div class="form-group">
                            <label for="payable_total_amount">Total Amount:</label>
                            <input type="text" name="payable_total_amount" id="payable_total_amount" class="form-control" placeholder="Total Amount">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="total_amount">Remaining Amount:</label>
                            <input type="text" id="remaining_amount" name="remaining_amount" readonly class="text-danger form-control">
                            <input type="hidden" name="hdn_remaining_amount" id="hdn_remaining_amount">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">                           
                            <label for="amount">Attachment File:</label>
                        <input type="file" name="payment_picture" id="payment_picture" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- <div class="col-md-3">

                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Select</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                                <option value="overdue">Overdue</option>

                            </select>
                        </div>
                    </div> -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input class="form-control" id="due_date" placeholder="Due date" name="due_date" id="due_date" type="date">
                        </div>
                    </div>

                    <div class="col-md-3">

                        <div class="form-group">
                            <label for="payment_type"><?php echo $this->lang->line('xin_payment_type'); ?></label>

                            <select class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_type'); ?>" name="payment_type">
                                <option value="">Select Payment Method</option>
                                <?php foreach ($get_payment_methods as $methods) { ?>
                                    <option value="<?php echo  $methods->method_name; ?>">
                                        <?php echo $methods->method_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control">
                        </div>
                    </div>


                </div>




            </div>
            <div class="form-actions box-footer">
                <button type="button" class="btn btn-danger abc" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>

                <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                    <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
        </div>


        <script>
            $(document).ready(function() {
                $.ajax({
                    url: "<?php echo base_url() . 'admin/Finance/getInvDetails/' . $_GET['invoice_id'] ?>",
                    type: "POST",
                    success: function(response) {
                        var product_data = jQuery.parseJSON(response);
                        console.log(product_data);

                        var t = 0;

                        let abc = product_data.map((r, index) => {
                            t += parseFloat(r.total) || 0;

                            // Check if the item is null, undefined, or an empty string
                            let itemDetails = r.item ?
                                `${r.product_name || ''}`.trim() :
                                r.job_description;

                            return (`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${itemDetails}</td>
                            <td>${r.item_qtn}</td>
                            <td>${r.unit}</td>
                            <td>${r.rate}</td>
                            <td>${parseFloat(r.total).toFixed(2)}</td>
                        </tr>
                    `);
                        });

                        $('#conf_pr_data').html(abc.join(''));
                    }
                });

                var t = $('#bgt').text();
            });
        </script>

        <script>
            $("#add_receivable").submit(function(e) {
                /*Form Submit*/
                var fd = new FormData(this);
                var gst_val = $('#gst_val').text();
                var def_val = $('#def_val').text();
                var n_gst_val = $('#n_gst_val').text();
                var ord_total = $('#ord_total').text();
                var gstNumber = parseFloat(n_gst_val.match(/\d+/)[0]);

                // console.log(ord_total,gst_val,gstNumber,def_val)

                // return false;
                var obj = $(this),
                    action = obj.attr('name');
                fd.append("ord_total", ord_total);
                fd.append("gst_val", gst_val);
                fd.append("gstNumber", gstNumber);
                fd.append("def_val", def_val);
                fd.append("is_ajax", 1);
                fd.append("data", 'add_payable');
                fd.append("type", 'add_payable');
                fd.append("form", action);

                e.preventDefault();

                $('.save').prop('disabled', true);
                $('.icon-spinner3').show();
                $.ajax({
                    type: "POST",
                    // url: e.target.action,
                    url: '<?php echo base_url() . 'admin/Receivable/add' ?>',

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


        <script>
            $(document).ready(function() {
                var total_amount = parseFloat($('#def_val').text()).toFixed(2);
                var paid_amount = 0;
                var remaining_amount = total_amount - paid_amount;
                $("#remaining_amount").val(remaining_amount.toFixed(2));

                $("#is_payable_gst").change(function() {
                    if (this.checked) {
                        $("#payable_gst_div").hide();
                        var total_amount = parseFloat($('#amount').val()).toFixed(2);
                        $('#total_amount1').val(total_amount);
                    } else {
                        $("#payable_gst_div").show();
                        var total_amount = parseFloat($('#amount').val()).toFixed(2);
                        var tax = parseFloat($("#payable_total_gst option:selected").text());
                        var total = (total_amount + total_amount * (tax / 100)).toFixed(2);
                        $('#payable_total_amount').val(total);
                    }
                });
            });

            function calculation() {
                var total_amount = parseFloat($('#def_val').text()).toFixed(2);
                var pay_amount = $("#amount").val();
                var tmp_pay_amount = $("#payable_total_amount").val(pay_amount);
                var final_pay_amount = $("#payable_total_amount").val();

                var paid_amount = 0;

                if (!isNaN(parseFloat(pay_amount)) && parseFloat(pay_amount) > 0) {
                    var remaining_amount = (total_amount - (paid_amount + parseFloat(final_pay_amount))).toFixed(2);
                } else {
                    var remaining_amount = (total_amount - paid_amount).toFixed(2);
                }

                $("#remaining_amount").val(remaining_amount);
            }

            function totalGSTAmount() {
                var total_amount = parseFloat($('#def_val').text()).toFixed(2);
                var paid_amount = 0;
                var pay_total_amount = parseFloat($('#amount').val()).toFixed(2);
                var tax = parseFloat($("#payable_total_gst option:selected").text());
                var total = (pay_total_amount + pay_total_amount * (tax / 100)).toFixed(2);
                var remaining_amount = (total_amount - (paid_amount + parseFloat(total))).toFixed(2);
                $('#payable_total_amount').val(total);
                $("#remaining_amount").val(remaining_amount);
            }
        </script>


    <?php }
} else { ?>
    <style>
        #ajax_modal_view {
            width: 1100px !important;
            margin-left: -190px;
        }
    </style>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data">Receivable Info</h4>
    </div>
    <?php $attributes = array('name' => 'add_receivable', 'id' => 'add_receivable', 'autocomplete' => 'off'); ?>

    <?php echo form_open_multipart('admin/Receivable/add', $attributes); ?>
    <input type="hidden" name="invoice_id" value="<?php echo $_GET['invoice_id']; ?>">
    <div class="form-body">
        <div class="box-body">

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Rate</th>
                                <th>Amount</th>

                            </tr>
                        </thead>
                        <tbody class="AddItem2" id="conf_pr_data">

                        </tbody>

                    </table>
                </div>
            </div>

            <div class="row ">
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    <div class="">
                        <label>Order Total: </label>&nbsp;<span id="ord_total"></span><br>
                        <label id="n_gst_val">GST(9%)</label>
                        <span id="gst_val" name="gst_val"></span><br>
                        <label>Grand Total: </label>&nbsp;<span id="def_val"></span>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-md-3">

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" oninput="calculation()">
                    </div>
                </div>

                <div class="col-md-3">

                    <div class="form-group">
                        <label for="payable_total_amount">Total Amount</label>
                        <input type="text" name="payable_total_amount" id="payable_total_amount" class="form-control" placeholder="Total Amount">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_amount">Remaining Amount:</label>
                        <input type="text" id="remaining_amount" name="remaining_amount" readonly class="text-danger form-control">
                        <input type="hidden" name="hdn_remaining_amount" id="hdn_remaining_amount">
                    </div>
                </div>
            </div>

            <div class="row">
                <!--<div class="col-md-3">

                     <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Select</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="overdue">Overdue</option>

                        </select>
                    </div> 
                </div>-->

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input class="form-control" id="due_date" placeholder="Due date" name="due_date" id="due_date" type="date">
                    </div>
                </div>

                <div class="col-md-3">

                    <div class="form-group">
                        <label for="payment_type"><?php echo $this->lang->line('xin_payment_type'); ?></label>

                        <select class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_type'); ?>" name="payment_type">
                            <option value="">Select Payment Method</option>
                            <?php foreach ($get_payment_methods as $methods) { ?>
                                <option value="<?php echo $methods->method_name; ?>">
                                    <?php echo $methods->method_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                </div>


            </div>




        </div>
        <div class="form-actions box-footer">
            <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                <?php echo $this->lang->line('xin_save'); ?> </button>
        </div>
        <?php echo form_close(); ?>
    </div>


    <script>
        $(document).ready(function() {
            $(document).ready(function() {


                $.ajax({
                    url: "<?php echo base_url() . 'admin/Finance/getInvDetails/' . $_GET['invoice_id'] ?>",
                    type: "POST",
                    success: function(response) {
                        var product_data = jQuery.parseJSON(response);
                        console.log(product_data)

                        var t = 0;

                        let abc = product_data.map((r, index) => {
                            t += parseFloat(r.lineitem_total)
                            return (`
                        <tr>
                            <td>${index+1}</td>
                            <td>${r.lineitem_description}</td>
                            <td>${r.lineitem_quantity}</td>
                            <td>${r.lineitem_unit}</td>
                            <td>${r.lineitem_rate}</td>                      
                            <td>${r.lineitem_total}</td>                      
                        </tr>                        
                    `);
                        });


                        $('#conf_pr_data').html(abc.join(''));

                    }
                });

                var t = $('#bgt').text();
            });




        });
    </script>
    <script>
        $("#add_receivable").submit(function(e) {
            /*Form Submit*/
            var fd = new FormData(this);
            var gst_val = $('#gst_val').text();
            var def_val = $('#def_val').text();
            var n_gst_val = $('#n_gst_val').text();
            var ord_total = $('#ord_total').text();
            var gstNumber = parseFloat(n_gst_val.match(/\d+/)[0]);

            // console.log(ord_total,gst_val,gstNumber,def_val)

            // return false;
            var obj = $(this),
                action = obj.attr('name');
            fd.append("ord_total", ord_total);
            fd.append("gst_val", gst_val);
            fd.append("gstNumber", gstNumber);
            fd.append("def_val", def_val);
            fd.append("is_ajax", 1);
            fd.append("data", 'add_payable');
            fd.append("type", 'add_payable');
            fd.append("form", action);

            e.preventDefault();

            $('.save').prop('disabled', true);
            $('.icon-spinner3').show();
            $.ajax({
                type: "POST",
                // url: e.target.action,
                url: '<?php echo base_url() . 'admin/Receivable/add' ?>',

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

                        toastr.success(JSON.result);

                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.add-form').removeClass('in');
                        $('.select2-selection__rendered').html('--Select--');
                        $('.icon-spinner3').hide();
                        $('#supplier_address').hide();
                        $('#xin-form')[0].reset(); // To reset form fields
                        $('.save').prop('disabled', false);
                    }
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            var total_amount = parseFloat($('#def_val').text()).toFixed(2);
            var paid_amount = 0;
            var remaining_amount = total_amount - paid_amount;
            $("#remaining_amount").val(remaining_amount.toFixed(2));

            $("#is_payable_gst").change(function() {
                if (this.checked) {
                    $("#payable_gst_div").hide();
                    var total_amount = parseFloat($('#amount').val()).toFixed(2);
                    $('#total_amount1').val(total_amount);
                } else {
                    $("#payable_gst_div").show();
                    var total_amount = parseFloat($('#amount').val()).toFixed(2);
                    var tax = parseFloat($("#payable_total_gst option:selected").text());
                    var total = (total_amount + total_amount * (tax / 100)).toFixed(2);
                    $('#payable_total_amount').val(total);
                }
            });
        });

        function calculation() {
            var total_amount = parseFloat($('#def_val').text()).toFixed(2);
            var pay_amount = $("#amount").val();
            var tmp_pay_amount = $("#payable_total_amount").val(pay_amount);
            var final_pay_amount = $("#payable_total_amount").val();

            var paid_amount = 0;

            if (!isNaN(parseFloat(pay_amount)) && parseFloat(pay_amount) > 0) {
                var remaining_amount = (total_amount - (paid_amount + parseFloat(final_pay_amount))).toFixed(2);
            } else {
                var remaining_amount = (total_amount - paid_amount).toFixed(2);
            }

            $("#remaining_amount").val(remaining_amount);
        }

        function totalGSTAmount() {
            var total_amount = parseFloat($('#def_val').text()).toFixed(2);
            var paid_amount = 0;
            var pay_total_amount = parseFloat($('#amount').val()).toFixed(2);
            var tax = parseFloat($("#payable_total_gst option:selected").text());
            var total = (pay_total_amount + pay_total_amount * (tax / 100)).toFixed(2);
            var remaining_amount = (total_amount - (paid_amount + parseFloat(total))).toFixed(2);
            $('#payable_total_amount').val(total);
            $("#remaining_amount").val(remaining_amount);
        }
    </script>

<?php } ?>