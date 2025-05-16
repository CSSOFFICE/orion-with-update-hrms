<style>
    #add_form {
        height: 100% !Important;
    }
</style>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<div class="box <?php echo $get_animate; ?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title">Add New GRN</h3>
            <div class="box-tools pull-right">
                <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new'); ?>
                    </button>
                </a>
            </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_grn', 'id' => 'add_grn', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('user_id' => $session['user_id']); ?>
                    <?php echo form_open('admin/purchase/grn_add', $attributes, $hidden); ?>
                    <div class="form-body">
                        <!-- For Manual Entry Start-->
                        <div id="new_div">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Supplier Name</label>
                                    <select class="form-control" name="supplier1" id="supplier1" data-plugin="select_hrm">
                                        <option>Select</option>
                                        <?php $sup_name = $this->db->get('xin_suppliers')->result(); ?>
                                        <?php foreach ($sup_name as $name) { ?>
                                            <option value="<?php echo $name->supplier_id ?>"><?php echo $name->supplier_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <input type="hidden" name="type" id="type" value="new">

                                <div class="col-md-3">
                                    <label>Warehouse</label>
                                    <?php $wh = $this->db->get('warehouse')->result(); ?>
                                    <select id="house1" name="house1" class="form-control" data-plugin="select_hrm" data-placeholder="Select Warehouse">
                                        <option value="">Select Warehouse</option>
                                        
                                        <?php foreach ($wh as $w) { ?>
                                            <option value="<?php echo $w->w_id ?>"><?php echo $w->w_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Receiving Date</label>
                                    <input type="date" class="form-control" name="date1" id="date1">
                                </div>


                            </div>
                            <div class="row">
                                <!-- <div class="p-20"> -->
                                <div class="table-responsive purchaseTable">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Product</th>
                                                <th>Uom</th>
                                                <!-- <th>Description</th> -->
                                                <th>Quantity Ordered</th>
                                                <th>Quantity Received</th>
                                                <th>Quantity in Transit</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="AddItem" id="vendor_items_table1"></tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="border: none !important;">
                                                    <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton1">Add</a>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- </div> -->
                            </div>


                        </div>
                        <!-- For Manual Entry End-->

                        <div class="form-actions box-footer">
                            <button type="submit" class="btn btn-primary" id="sub_btn"> <i class="fa fa-check-square-o"></i>
                                <?php echo $this->lang->line('xin_save'); ?> </button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>

            </div>
    </div>
</div>



<div class="box <?php echo $get_animate; ?>">

    <div class="box-header with-border">
        <h3 class="box-title"> List All GRN </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table_grn">
                <thead>
                    <tr>
                        <th><?php echo "Sl No."; ?></th>
                        <th><?php echo $this->lang->line('xin_action'); ?></th>
                        <th>Warehouse</th>
                        <th>GRN No.</th>
                        <th>PO No.</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.remove-input-field', function() {
        $(this).parents('tr').remove();
    });
    $(document).on('click', '#addButton1', function() {
        $('[data-plugin="select_hrm"]').each(function() {
            var options = $(this).data('options');
            $(this).select2(options);
            $(this).select2({
                width: '100%'
            });
        });
        var number = $('.AddItem tr').length;
        var item = number + 1;

        // var supid = $('#sup').val();
        // getProductbySup(supid, item);
        $('.AddItem').append(`
            <tr>
                <td style="min-width:130px">
                    <label>${item}</label>
                </td>
                <td>
                    <select class="form-control" name="u_item[]" id="u_item_${item}" data-plugin="select_hrm" data-placeholder="Select Product" onchange="getProdDe(${item})">
                    <?php $all_products = $this->db->get('product')->result(); ?>
                    <option value="">Select Product</option>
                        <?php foreach ($all_products as $product) { ?>
                            <option value="<?php echo $product->product_id ?>"><?php echo $product->product_name ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><input type="text" class="form-control" name="u_description[]" id="u_description_${item}"></td>
                <td><input class="form-control" type="text" name="u_qty_order[]" id="u_qty_order_${item}"></td>
                <td><input class="form-control" type="text" name="u_qty_rec[]" id="u_qty_rec_${item}" oninput="calculateRem(${item})"></td>
                <td><input readonly class="form-control" type="text" name="u_qty_rem[]" id="u_qty_rem_${item}"></td>
                <td><textarea class="form-control" name="u_remark[]" id="u_remark_${item}"></textarea></td>
                <td>
                    <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                </td>
            </tr>
        `);
    });

    function calculateRem(number) {
        var wq = $('#u_qty_order_' + number).val();
        var qr = $('#u_qty_rec_' + number).val();


        $('#u_qty_rem_' + number).val(wq - qr);





    }

    function getProdDe(number) {
        var p_id = $('#u_item_' + number).val();
        console.log(p_id)
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/purchase/podetail/'; ?>" + p_id,
            data: JSON,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);
                console.log(data);
                $("#u_description_" + number).val(product_data[0].std_uom);
            }
        });
    }
</script>
