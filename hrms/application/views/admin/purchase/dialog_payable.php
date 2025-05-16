<style>
    #ajax_modal_view {
        width: 1200px !important;
        margin-left: -250px;
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
        <h4 class="modal-title" id="edit-modal-data">Add Expense</h4>
    </div>
    <?php $attributes = array('name' => 'edit_purchase_order', 'id' => 'edit_purchase_order', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['purchase_order_id'], 'ext_name' => $_GET['purchase_order_id']); ?>
    <?php echo form_open_multipart('admin/Purchase/add_expence_data', $attributes, $hidden); ?>
    <div class="modal-body">


        <input type="hidden" name="purchase_order_id" value="<?php echo $_GET['purchase_order_id']; ?>">
        <input type="hidden" name="prj_id" value="<?php echo $project_id; ?>">
        <div class="col-md-12">
            <div class="form-group">
                <label>Purchase Order No</label>
                <label><?php $po_id = $this->db->select('porder_id,payment_term,delivery_date,po_dates,order_total')->from('purchase_order')->where('purchase_order_id', $_GET['purchase_order_id'])->get()->result();
                        echo $po_id[0]->porder_id ?>
                </label><br>
                <label>Purchase Order Total</label>
                <label><?php echo number_format($po_id[0]->order_total, 2) ?></label>
            </div>

        </div>

        <?php if ($po_id[0]->payment_term) { ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Payment Term: </label>
                    <label><?php echo htmlspecialchars($po_id[0]->payment_term); ?></label>
                </div>
                <?php
                // Initialize variables
                $due_date = null;
                $payment_term = $po_id[0]->payment_term;
                $po_date = $po_id[0]->po_dates;

                // Case-insensitive comparison for C.O.D
                if (strcasecmp($payment_term, "C.O.D") === 0) {
                    $due_date = date('d-m-Y', strtotime($po_id[0]->delivery_date));
                }
                // Check if payment term contains a number with "days" or "DAYS"
                elseif (preg_match('/\b(\d+)\s*(days?|DAYS?)\b/i', $payment_term, $matches)) {
                    $days = (int)$matches[1]; // Extract the number of days
                    $due_date = date('d-m-Y', strtotime("+$days days", strtotime($po_date)));
                }
                ?>

                <?php if ($due_date) { ?>
                    <div class="form-group">
                        <label>Due Date</label>
                        <label><?php echo $due_date; ?></label>
                        <input type="hidden" name="poduedate" value="<?php echo $due_date; ?>" />
                    </div>
                <?php } ?>
            </div>
        <?php } ?>


        <div class="col-md-12">
            <button type="button" class="btn btn-info float-right" id="add_row_ex">Add New Invoice</button>
        </div>
        <div id="div_expense_table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Invoice Subtotal</th>
                        <th>Invoice GST</th>
                        <th>Invoice Amount</th>
                        <th>Date</th>
                        <th>Remark</th>
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="invoice_no[]" class="form-control">
                            <input type="hidden" name="porder_id[]" value="<?php echo $po_id[0]->porder_id; ?>">
                        </td>
                        <td>
                            <input type="text" name="invoice_subt[]" class="form-control invoice_subt">
                        </td>
                        <td>
                            <input type="text" name="invoice_gst[]" class="form-control invoice_gst">
                        </td>
                        <td>
                            <input type="text" name="invoice_amount[]" class="form-control invoice_amount" readonly>
                        </td>
                        <td>
                            <input type="date" name="date[]" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="do_no[]" class="form-control">
                        </td>
                        <td>
                            <input type="file" name="payment_picture[]" class="form-control">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
            <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_add'); ?></button>
        </div>
        <?php echo form_close(); ?>


        <script>
            $(document).ready(function() {
                $("#add_row_ex").on("click", function() {
                    let row = `<tr>
                                    <td>
                                        <input type="text" name="invoice_no[]" class="form-control">
        <input type="hidden" name="porder_id[]" value="<?php echo $po_id[0]->porder_id; ?>">

                                    </td>
                                    <td>
                                        <input type="text" name="invoice_subt[]" class="form-control invoice_subt">
                                    </td>
                                    <td>
                                        <input type="text" name="invoice_gst[]" class="form-control invoice_gst">
                                    </td>
                                    <td>
                                        <input type="text" name="invoice_amount[]" class="form-control invoice_amount" readonly>
                                    </td>
                                    <td>
                                        <input type="date" name="date[]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="do_no[]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="file" name="payment_picture[]" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                                    </td>
                                </tr>`;
                    $("#div_expense_table tbody").append(row);
                });

                $(document).on("input", ".invoice_subt, .invoice_gst", function() {
                    let row = $(this).closest("tr");
                    let subt = parseFloat(row.find(".invoice_subt").val()) || 0;
                    let gst = parseFloat(row.find(".invoice_gst").val()) || 0;
                    let amount = subt + gst;
                    row.find(".invoice_amount").val(amount.toFixed(2));
                });

                $(document).on("click", ".remove-row", function() {
                    $(this).closest("tr").remove();
                });

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
                // e.preven/tDefault()
                /*Form Submit*/
                var fd = new FormData(this);

                // console.log(ord_total,gst_val,gstNumber,def_val)

                // return false;
                var obj = $(this),
                    action = obj.attr('name');

                fd.append("form", action);

                e.preventDefault();

                $('.save').prop('disabled', true);
                $('.icon-spinner3').show();
                $.ajax({
                    type: "POST",
                    // url: e.target.action,
                    url: "<?php echo base_url() . 'admin/Purchase/add_expence_data'; ?>",


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
                            $('#edit_purchase_order')[0].reset(); // To reset form fields
                            $('.view-modal-data').modal('toggle'); // To reset form fields
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
    <?php } ?>