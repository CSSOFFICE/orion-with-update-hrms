<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['invoice_id']) && $_GET['data'] == 'receivable') {

?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data">Receivable Info</h4>

    </div>
    <?php $attributes = array('name' => 'edit_receivable', 'id' => 'edit_receivable', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['invoice_id'], 'ext_name' => $_GET['invoice_id']); ?>
    <?php echo form_open_multipart('admin/receivable/add', $attributes, $hidden); ?>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="edit_type" value="receivable">
            <input type="hidden" name="invoice_id" value="<?php echo $_GET['invoice_id']; ?>">
            <?php if ($total_paid_amount != "" || $total_paid_amount > 0) {
                $invoice_total_amount =  $total_amount - $total_paid_amount;
            } else {
                $invoice_total_amount = $total_amount;
            }
            ?>
            <div class="col-md-12 main_form">
                <div class="form-group">
                    <label for="total_amount">Invoice Date:</label>
                    <label><?php echo  $invoice_date; ?></label>

                </div>
            </div>
            <div class="col-md-12 main_form">
                <div class="form-group">
                    <label for="total_amount">Invoice Number:</label>
                    <label><?php echo $invoice_no; ?></label>
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
                    <p id="remaining_amount1"></p>
                    <input type="hidden" name="hdn_remaining_amount1" id="hdn_remaining_amount1">
                </div>
            </div>
            <?php if ($total_amount != $invoice_total_amount) { ?>
                <div class="col-md-12 main_form">
                    <div class="form-group">
                        <label for="remaining_total_amount">Total Remaining Amount:</label>
                        <label>$<?php echo $invoice_total_amount; ?></label>
                        <input type="hidden" name="remaining_total_amount" id="remaining_total_amount" value="<?php echo $invoice_total_amount; ?>">
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
                    <label for="amount">Receiving Amount</label>
                    <input type="text" name="pay_amount" id="pay_amount" class="form-control">
                </div>
            </div>

            <!-- <div class="col-md-12 main_form">
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
            </div>
            <div class="col-md-12 main_form">
                <div class="form-group">
                    <label for="amount">Total Amount</label>
                    <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" oninput="calculation()">
                </div>
            </div> -->
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
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
                var total = ($('#remaining_total_amount').val() !== undefined) ?
                    $('#remaining_total_amount').val() :
                    '<?php echo $total_amount; ?>';

                var pay_amount = $("#pay_amount").val();
                var remaining_amount = parseFloat(total) - parseFloat(pay_amount);

                // Ensure 2 decimal places
                remaining_amount = remaining_amount.toFixed(2);

                $("#hdn_remaining_amount1").val(remaining_amount);
                $("#remaining_amount1").text('$' + remaining_amount);
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
    </script>


    <script>
        $("#edit_receivable").submit(function(e) {
            e.preventDefault();

            var fd = new FormData(this);
            var obj = $(this),action = obj.attr('name');
            fd.append("data", 'edit_receivable');
            fd.append("is_ajax", 1);
            fd.append("form", action);
            $.ajax({
                type: "POST",
                url: base_url + "/add",
                // data: obj.serialize() + "&is_ajax=1&form=" + action,
                data: fd,
                contentType: false,
                cache: false,
                processData: false,
                success: function(JSON) {
                    // toastr.success(JSON.result);

                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                        $('.icon-spinner3').hide();
                    } else {

                        var xin_table = $('#xin_table').dataTable({
                            "bDestroy": true,
                            "ajax": {
                                url: "<?php echo site_url() . 'admin/receivable/receivable_list' ?>",

                            },

                        });


                        toastr.success(JSON.result);


                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.add-form').removeClass('in');
                        $('.select2-selection__rendered').html('--Select--');
                        $('.icon-spinner3').hide();
                        $('#edit_receivable')[0].reset(); // To reset form fields
                        $('.save').prop('disabled', false);
                    }
                }
            });
        })
    </script>
<?php } ?>