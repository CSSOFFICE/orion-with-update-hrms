<style>
    #ajax_modal_view {
        width: 1200px !important;
        margin-left: -250px;
        overflow-x: scroll;
    }

    /* #subcon_expense_table td {
        max-width: 200px;
        /* Adjust based on your design */
    /* word-wrap: break-word;
        white-space: normal;
        text-align: left; */
    /* Allows multi-line wrapping */
    /* overflow-wrap: break-word; */
    /* Ensures long words break */
    /* } */
    #subcon_expense_table {
        word-wrap: break-word;
    }
</style>

<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['subcon_id']) && $_GET['data'] == 'expense_subcon') {

?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data">Add Expense</h4>
    </div>
    <?php $attributes = array('name' => 'add_subcon_exp_data', 'id' => 'add_subcon_exp_data', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['subcon_id'], 'ext_name' => $_GET['subcon_id']); ?>
    <?php echo form_open_multipart('admin/Purchase/add_expence_data', $attributes, $hidden); ?>
    <div class="modal-body">

        <input type="hidden" name="edit_type" value="payable1">
        <input type="hidden" name="subcon_id" value="<?php echo $_GET['subcon_id']; ?>">
        <input type="hidden" name="subcon_sup_id" value="<?php echo $_GET['subcon_sup_id']; ?>">
        <input type="hidden" name="project_id_subcon" value="<?php echo $subcon_detail[0]->project_id; ?>">

        <div class="col-md-12">
            <button type="button" class="btn btn-info float-right" id="add_row_ex">Add New Invoice</button>
        </div>

        <div>
            <h3>Contracted Amount: S$ <?php echo $subcon_detail[0]->contracted_amount; ?></h3>
            <h3>Agreement Number: <?php echo $subcon_detail[0]->agreement_number; ?></h3>
            <h3>Project Name: <?php echo $subcon_detail[0]->project_title; ?></h3>
            <h3>Milestone: <?php echo $subcon_detail[0]->milestone_title; ?></h3>
            <h3>Task: <?php echo $subcon_detail[0]->task_title; ?></h3>
        </div>
        <div id="div_expense_table">
            <table class="table table-bordered" id="subcon_expense_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PO No</th>
                        <th>Invoice No</th>
                        <th>Invoice Subtotal</th>
                        <th>Invoice GST</th>
                        <th>Invoice Amount</th>
                        <th>Invoice Date</th>
                        <th>Invoice Due Date</th>
                        <th>Remark</th>
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <!-- <a class="display-value ">Click to edit</a> -->
                            <input style="width:100px" type="text" name="porder_id[]" id="porder_id_1" class="form-control edit-input" placeholder="PO No">
                        </td>
                        <td>
                            <!-- <span class="display-value">Click to edit</span> -->
                            <input style="width:100px" type="text" name="invoice_no[]" id="invoice_no_1" class="form-control edit-input">
                        </td>
                        <td>
                            <!-- <span class="display-value ">Click to edit</span> -->
                            <input style="width:100px" type="text" name="invoice_subt[]" id="invoice_subt_1" class="form-control edit-input invoice_subt">
                        </td>
                        <td>
                            <!-- <span class="display-value">Click to edit</span> -->
                            <input style="width:100px" type="text" name="invoice_gst[]" id="invoice_gst_1" class="form-control edit-input invoice_gst">
                        </td>
                        <td>
                            <!-- <span class="display-value">Click to edit</span> -->
                            <input style="width:100px" type="text" name="invoice_amount[]" id="invoice_amount_1" class="form-control edit-input invoice_amount" readonly>
                        </td>
                        <td>
                            <!-- <span class="display-value">Click to select date</span> -->
                            <input type="date" name="date[]" id="date_1" class="form-control edit-input">
                        </td>
                        <td>
                            <!-- <span class="display-value">Click to select due date</span> -->
                            <input type="date" name="manual_due_date[]" id="manual_due_date_1" class="form-control edit-input">
                        </td>
                        <td>
                            <!-- <span class="display-value">Click to edit</span> -->
                            <input style="width:100px" type="text" name="do_no[]" id="do_no_1" class="form-control edit-input">
                        </td>
                        <td>
                            <!-- <span class="display-value">Click to upload</span> -->
                            <input type="file" name="payment_picture[]" id="payment_picture_1" class="form-control edit-file">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="modal-close"><?php echo $this->lang->line('xin_close'); ?></button>
            <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_add'); ?></button>
        </div>
        <?php echo form_close(); ?>
        <script>
            $(document).ready(function() {
                // Handle click on span to show input/select/file
                // $('#subcon_expense_table').on('click', '.display-value', function() {
                //     var $td = $(this).parent('td');
                //     $(this).hide();
                //     $td.find('.edit-input, .edit-select, .edit-file').show().focus();
                // });

                // // Handle text/date input blur to update span
                $('#subcon_expense_table').on('blur', '.edit-input', function() {
                    var $td = $(this).parent('td');
                    var value = $(this).val();
                    // Update span text; revert to placeholder if empty
                    // $td.find('.display-value').text(value || ($td.find('.edit-input[type="date"]').length ? 'Click to select date' : 'Click to edit')).show();
                    // $(this).hide();

                    // If invoice_subt or invoice_gst changed, update invoice_amount
                    if ($(this).hasClass('invoice_subt') || $(this).hasClass('invoice_gst')) {
                        var $row = $(this).closest('tr');
                        var subt = parseFloat($row.find('.invoice_subt').val()) || 0;
                        var gst = parseFloat($row.find('.invoice_gst').val()) || 0;
                        var amount = subt + gst;
                        $row.find('.invoice_amount').val(amount);
                        $row.find('.invoice_amount').siblings('.display-value').text(amount || 'Click to edit');
                    }
                });

                // // Handle select change to update span
                // $('#subcon_expense_table').on('change', '.edit-select', function() {
                //     var $td = $(this).parent('td');
                //     var selectedText = $(this).find('option:selected').text();
                //     $td.find('.display-value').text(selectedText).show();
                //     $(this).hide();
                // });

                // // Handle file input change to update span
                // $('#subcon_expense_table').on('change', '.edit-file', function() {
                //     var $td = $(this).parent('td');
                //     var fileName = $(this).val().split('\\').pop() || 'Click to upload';
                //     $td.find('.display-value').text(fileName).show();
                //     $(this).hide();
                // });
            });
        </script>
        <script>
            var rowCount = 1;

            $('#add_row_ex').on('click', function() {
                rowCount++;
                var newRow = '<tr>' +
                    '<td>' + rowCount + '</td>' +
                    '<td><input type="text" name="porder_id[]" style="width:100px" class="form-control edit-input"></td>' +
                    '<td><input type="text" name="invoice_no[]" style="width:100px" class="form-control edit-input"></td>' +
                    '<td><input type="text" name="invoice_subt[]" style="width:100px" class="form-control edit-input invoice_subt"></td>' +
                    '<td><input type="text" name="invoice_gst[]"  style="width:100px" class="form-control edit-input invoice_gst"></td>' +
                    '<td><input type="text" name="invoice_amount[]" style="width:100px" class="form-control edit-input invoice_amount" readonly></td>' +
                    '<td><input type="date" name="date[]" class="form-control edit-input"></td>' +
                    '<td><input type="date" name="manual_due_date[]" class="form-control edit-input"></td>' +
                    '<td><input type="text" name="do_no[]" style="width:100px" class="form-control edit-input"></td>' +
                    '<td><input type="file" name="payment_picture[]" class="form-control edit-file"></td>' +
                    '<td><button type="button" class="btn btn-danger remove-row">Remove</button></td>' +
                    '</tr>';
                $('#subcon_expense_table tbody').append(newRow);
                reindexRows();

            });
            // Remove row and reindex
            $(document).on("click", ".remove-row", function() {
                $(this).closest("tr").remove();
                reindexRows();
            });

            // Re-index all input name attributes
            function reindexRows() {
                $("#subcon_expense_table tbody tr").each(function(index, row) {
                    $(row).find("td:first").text(index + 1); // Update row number
                    $(row).find("input, select").each(function() {
                        let name = $(this).attr("name");
                        if (name) {
                            // Handles both name[] and name[0] cases
                            let baseName = name.replace(/\[\d*\]/, '').replace(/\[\]$/, '');
                            $(this).attr("name", baseName + "[" + index + "]");
                        }
                    });
                });
            }
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
                $('[data-plugin="select_hrm"]').select2({
                    width: '100%'
                });
            });


            $("#add_subcon_exp_data").submit(function(e) {
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
                            $('#add_subcon_exp_data')[0].reset(); // To reset form fields
                            $('#modal-close').click(); // Close modal                            

                        }
                    }
                });
            });
        </script>


    <?php } ?>