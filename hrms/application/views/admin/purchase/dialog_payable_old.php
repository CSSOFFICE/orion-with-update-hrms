<style>
    #ajax_modal_view {
        width: 1100px !important;
        margin-left: -190px;
    }
</style>

<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['purchase_order_id']) && $_GET['data'] == 'add_payable') {

?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data">Add Payable</h4>
    </div>
    <?php $attributes = array('name' => 'edit_purchase_order', 'id' => 'edit_purchase_order', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['purchase_order_id'], 'ext_name' => $_GET['purchase_order_id']); ?>
    <?php echo form_open_multipart('admin/payable/add_payable', $attributes, $hidden); ?>
    <div class="modal-body">


        <input type="hidden" name="purchase_order_id" value="<?php echo $_GET['purchase_order_id']; ?>">
        <input type="hidden" name="prj_id" value="<?php echo $project_id; ?>">

        <div class="row">
        <div class="col-md-3">
                                <div class="form-group">
                                    <label>Invoice No</label>
                                    <input type="text" name="invoice_no" class="form-control">
                                </div>
                            </div>
            <div class="col-md-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control">
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <fieldset class="form-group">
                        <label for="logo"><?php echo $this->lang->line('xin_payment_photo'); ?>
                            <!-- <i class="hrsale-asterisk">*</i> -->
                        </label>
                        <input type="file" class="form-control-file" id="payment_picture" name="payment_picture">
                        <small><?php echo $this->lang->line('xin_company_file_type'); ?></small>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-3">

                <label>Payment Details</label>
                <textarea class="form-control" name="pay_details"></textarea>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Project Name:</label>
                        <label id="p_name"></label>
                    </div>
                    <div class="form-group">
                        <label>Employee Name:</label>
                        <label id="e_name"></label>
                    </div>
                    <div class="form-group">
                        <label>Purchase Order Number:</label>
                        <label id="pr_no"></label>
                    </div>
                    <div class="form-group">
                        <label>Supplier Name:</label>
                        <label id="sup_name"></label>
                    </div>

                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Product Quantity</th>
                            <th>Product Total</th>

                        </tr>
                    </thead>
                    <tbody class="AddItem2" id="conf_pr_data">

                    </tbody>

                </table>
                <div class="row">
                    <div class="col-md-8"></div>
                    <!-- <div class="col-sm-2 float-right">
                            <br>
                            <select class="form-control" id="payable_total_gst1" onchange="GSTinTotal()">
                                <option value="0">GST</option>
                                <?php foreach ($get_gst as $gst) { ?>
                                    <option value="<?php echo $gst->gst; ?>">
                                        <?php echo $gst->gst; ?></option>
                                <?php } ?>
                            </select>
                        </div> -->
                    <div class="col-md-2">
                        <div>
                            <label>Order Total: </label>&nbsp;<span id="ord_total"></span><br>
                            <label>GST</label><label id="n_gst_val"></label>%
                            <span id="gst_val" name="gst_val"></span><br>
                            <label>Grand Total: </label>&nbsp;<span id="def_val"></span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <br><br>
        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label>Remark</label>
                    <textarea name="remark" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="amount">Payable Amount</label>
                    <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" oninput="calculation()">
                </div>
            </div>
            <!-- <div class="col-md-3">

                    <div class="form-group">
                        <input type="checkbox" class="listcheckbox listcheckbox-files filled-in chk-col-light-blue" id="is_payable_gst" name="is_payable_gst" value="1">
                        <label for="is_payable_gst">GST
                            Inclusive</label>


                        <div id="payable_gst_div">
                            <label for="total_gst1" class="form-label">GST</label>
                            <select class="form-control select22" id="payable_total_gst" name="payable_gst" onchange="totalGSTAmount()">
                                <option value="0">Select GST</option>
                                <?php foreach ($get_gst as $gst) { ?>
                                    <option value="<?php echo $gst->gst; ?>">
                                        <?php echo $gst->gst; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div> -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="total_amount">Remaining Amount:</label>
                    <input type="text" id="remaining_amount" name="remaining_amount" readonly class="text-danger form-control">
                    <input type="hidden" name="hdn_remaining_amount" id="hdn_remaining_amount">
                </div>
            </div>



        </div>

        <div class="row">
            <div class="col-md-3">

                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Select</option>
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="overdue">Overdue</option>

                    </select>
                </div>
            </div>

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
                            <option value="<?php echo $methods->payment_method_id; ?>">
                                <?php echo $methods->method_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 d-none">

                <div class="form-group">
                    <label for="payable_total_amount">Total Amount</label>
                    <input type="text" name="payable_total_amount" id="payable_total_amount" class="form-control" placeholder="Total Amount">
                </div>
            </div>

        </div>





        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
            <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_add'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php }
?>
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

    function calculation() {
        var pay_amount = 0;
        var total_amount = $('#def_val').text();

        var pay_amount = $("#amount").val();
        var tmp_pay_amount = $("#payable_total_amount").val(pay_amount);
        var final_pay_amount = $("#payable_total_amount").val();


        var paid_amount = 0;
        //var remaining_amount=  $("#hdn_remaining_amount").val();


        if (parseFloat(pay_amount) != NaN && parseFloat(pay_amount) > 0) {

            var remaining_amount = parseFloat(total_amount) - (parseFloat(paid_amount) + parseFloat(final_pay_amount));

        } else {
            var remaining_amount = parseFloat(total_amount) - parseFloat(paid_amount);
        }

        $("#remaining_amount").val(remaining_amount);
    }

    function GSTinTotal() {
        var selectedGst = $("#payable_total_gst1 option:selected").val();
        var pur_order = <?php echo $_GET['purchase_order_id'] ?>;
        // console.log(pr_id);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/Payable/getPODetails/'; ?>" + pur_order,
            data: JSON,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);

                $('#n_gst_val').text(product_data.po_detail[0].gst);
                var def_nine = product_data.po_detail[0].ord_total * (product_data.po_detail[0].gst / 100);
                var final = def_nine + product_data.po_detail[0].ord_total;
                $('#gst_val').text(def_nine);
                $('#def_val').text(final);
                console.log(final);

            }
        });


    }

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
    $("#edit_purchase_order").submit(function(e) {
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
            url: "<?php echo base_url() . 'admin/payable/add_payable'; ?>",


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

        var pur_order = $('#purchase_order_id').val();
        // console.log(pr_id);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/Payable/getPODetails/'; ?>" + <?php echo $_GET['purchase_order_id'] ?>,
            data: JSON,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);
                console.log(product_data);
                $("#p_name").text(product_data.po_detail[0].project_title);
                $("#e_name").text(product_data.po_detail[0].first_name + " " + product_data.po_detail[0].last_name);
                $("#pr_no").text(product_data.po_detail[0].porder_id);
                $("#sup_name").text(product_data.po_detail[0].supplier_name);



                let abc = product_data.po_items.map((r, index) => {

                    return (`
                <tr>
                    <td>${index+1}</td>
                    <td>${r.product_name}</td>
                    <td>${r.prd_price}</td>
                    <td>${r.prd_qtn}</td>
                    <td>${r.prd_total}</td>                      
                </tr>                        
            `);
                });

                $("#ord_total").text(product_data.po_detail[0].ord_total);
                $('#ord_total1').val(product_data.po_detail[0].ord_total);
                var def_nine = product_data.po_detail[0].ord_total * (product_data.po_detail[0].gst / 100);
                var final = def_nine + product_data.po_detail[0].ord_total;
                $('#n_gst_val').text(product_data.po_detail[0].gst);
                $('#gst_val').text(def_nine);
                $('#def_val').text(final);
                console.log(final);

                $('#conf_pr_data').html(abc.join(''));

            }
        });

    })
</script>