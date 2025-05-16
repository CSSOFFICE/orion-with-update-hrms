<style>
    #add_form {
        height: 100% !important;
    }
</style>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('3102', $role_resources_ids)) { ?>

    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title">Add New Receivable</h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a> </div>
            </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
            <?php $attributes = array('name' => 'add_receivable', 'id' => 'add_receivable', 'autocomplete' => 'off'); ?>
            <?php $hidden = array('user_id' => $session['user_id']); ?>
            <?php echo form_open_multipart('admin/Receivable/add', $attributes, $hidden); ?>
            <!-- <input type="hidden" name="" id="invoice_url" value="<?php //echo base_url('admin/Finance/related_data') 
                                                                        ?>"> -->
            <div class="form-body">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="invoice_id">Invoice No
                                <i class="hrsale-asterisk">*</i>
                            </label>
                            <select class="form-control" name="invoice_id" id="invoice_id" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                                <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                <?php foreach ($all_quotation = $this->db->get('finance_invoice')->result() as $invoice) { ?>
                                    <option value="<?php echo $invoice->invoice_id; ?>">
                                        <?php echo $invoice->invoice_no; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <br><br>
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

                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-sm-1 float-right">
                        <?php //$result = $this->Receivable_model->read_invoice($id); print_r($result);?>
                        </div>
                        <div class="col-md-2">
                            <div >
                                <label>Order Total: </label>&nbsp;<span id="ord_total"></span><br>
                                <label>GST</label>(<span id="n_gst_val"></span>)%:
                                <span id="gst_val" name="gst_val"></span><br>
                                <label>Grand Total: </label>&nbsp;<span id="def_val"></span>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-3">

                            <div class="form-group">
                                <label for="amount">Receivable Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" oninput="calculation()">
                            </div>
                        </div>

                        <div class="col-md-3 d-none">

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
                                <input class="form-control date" id="due_date" placeholder="Due date" name="due_date" id="due_date" type="text">
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
        </div>
    </div>
<?php } ?>
<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php echo $this->lang->line('xin_receivable'); ?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">

                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('xin_action'); ?></th>
                        <th><?php echo $this->lang->line('xin_customer'); ?></th>
                        <th>Quotation Ammount</th>
                        <!-- <th>Received Total</th> -->
                        <th>Invoice Number</th>
                        <th>Status</th>
                        <th>Quotation Date</th>


                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on("change", "#invoice_id", function() {
            var quotation_id = $(this).val();

            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/getInvDetails/'; ?>" + quotation_id,
                type: "POST",
                success: function(response) {
                    var product_data = jQuery.parseJSON(response);
                    console.log(product_data)

                    var t = 0;

                    let abc = product_data.map((r, index) => {
                        t += parseFloat(r.total)
                        return (`
                        <tr>
                            <td>${index+1}</td>
                            <td>${(r.item_type=="plain") ? r.job_description : r.product_name}</td>
                            <td>${r.item_qtn}</td>
                            <td>${r.unit}</td>
                            <td>${r.rate}</td>                      
                            <td>${r.total}</td>                      
                        </tr>                        
                    `);
                    });
                    $('#ord_total').text(t.toFixed(2));
                 
                    $('#n_gst_val').text(product_data[0].gst);
                    $('#gst_val').text(product_data[0].gst_value);
                    $('#def_val').text(product_data[0].sum);
                    // console.log(final);
                    // console.log(t)

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
                    var xin_table = $('#xin_table').dataTable({
                        "bDestroy": true,
                        "ajax": {
                            url: base_url + "/receivable_list/",
                        },

                    });                  
                        toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.add-form').removeClass('in');
                    $('.select2-selection__rendered').html('--Select--');
                    $('.icon-spinner3').hide();
                    $('#supplier_address').hide();
                    $('#add_receivable')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
</script>
<script>
    function GSTinTotal() {
        var selectedGst = $("#payable_total_gst1 option:selected").val();
        var pur_order = $('#quotation_no').val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/Finance/getInvDetails/'; ?>" + pur_order,
            data: JSON,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);
                if (selectedGst != 0) {
                    $('#n_gst_val').text("GST " + selectedGst + "%");
                    var t = parseFloat($('#ord_total').text()).toFixed(2);
                    var def_nine = (parseFloat($('#ord_total').text()).toFixed(2) * (selectedGst / 100)).toFixed(2);
                    var final = (parseFloat(def_nine) + parseFloat(t)).toFixed(2);
                    $('#gst_val').text(def_nine);
                    $('#def_val').text(final);
                } else {
                    $('#n_gst_val').text("GST 9%");
                    var def_nine = (parseFloat($('#ord_total').text()).toFixed(2) * (9 / 100)).toFixed(2);
                    var t = parseFloat($('#ord_total').text()).toFixed(2);
                    var final = (parseFloat(def_nine) + parseFloat(t)).toFixed(2);
                    $('#gst_val').text(def_nine);
                    $('#def_val').text(final);
                }
            }
        });
    }
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
