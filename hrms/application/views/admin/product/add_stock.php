<?php
defined('BASEPATH') or exit('No direct script access allowed');


?>
<?php $session = $this->session->userdata('username'); ?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title" id="ajax_modal_view">Add Stock</h4>
</div>
<?php $attributes = array('name' => 'stock_add', 'id' => 'stock_add', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['w_id'], 'ext_name' => $_GET['w_id']); ?>
<?php echo form_open('admin/warehouse/insertStock/' . $_GET['w_id'], $attributes, $hidden); ?>
<input type="hidden" name="warehouse_id" value="<?php echo $_GET['w_id'] ?>">
<div class="modal-body">
    <div class="col-md-12">
        <div class="form-group">
            <label for="w_name">Product Name
                <i class="hrsale-asterisk">*</i></label>
            </label>
            <select class="form-control" name="u_item" id="u_item" data-plugin="select_hrm" data-placeholder="Select Product">
                <option value="Select Product">Select Product</option>

                <?php foreach ($all_products as $product) { ?>
                    <option value="<?php echo $product->product_id ?>"><?php echo $product->product_name?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="w_address">Total Quantity
                <i class="hrsale-asterisk">*</i></label>
            </label>
            <input type="number" name="quantity" id="quantity" class="form-control">
        </div>
    </div>
    <!-- <div class="alert alert-primary" role="alert">
        "Please add the total <b>Quantity</b> of the product; it will <span class="text-danger">refresh</span> the current stock level accordingly."
    </div> -->

</div>
<div class="modal-footer">
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
    <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa-check-square-o"></i> ' . 'Add')); ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({
            width: '100%'
        });
        $("#stock_add").submit(function(e) {
            e.preventDefault();
            var obj = $(this),
                action = obj.attr('name');
            $('.save').prop('disabled', true);
            $('.icon-spinner3').show();

            $.ajax({
                type: "POST",
                url: base_url + "/insertStock",
                data: obj.serialize() + "&is_ajax=1&edit_type=warehouse&form=" + action,
                cache: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                        $('.icon-spinner3').hide();
                    } else {
                        toastr.success(JSON.result);
                        setTimeout(function() {
                            $('.view-modal-data').modal('toggle'); // Close the modal
                            location.reload(); // Refresh the page
                        }, 2000); // Delay of 2 seconds
                    }
                }
            });
        });

    });
</script>