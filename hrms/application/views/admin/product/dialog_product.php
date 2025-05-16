<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (isset($_GET['jd']) && isset($_GET['product_id']) && $_GET['data'] == 'product') {
?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_product'); ?></h4>
    </div>
    <?php $attributes = array('name' => 'edit_product', 'id' => 'edit_product', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $product_id, 'ext_name' => $product_id); ?>
    <?php echo form_open_multipart('admin/product/update/' . $product_id, $attributes, $hidden); ?>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_name"><?php echo $this->lang->line('xin_category_name'); ?>
                                <i class="hrsale-asterisk">*</i></label>
                            </label>

                            <select class="form-control"
                                placeholder="<?php echo $this->lang->line('xin_category_name'); ?>"
                                name="category_name">
                                <option>Select Category</option>
                                <?php foreach ($get_categories as $category) { ?>
                                    <option value="<?php echo $category->category_id; ?>" <?php echo ($category->category_id == $category_id ? 'selected' : ''); ?>><?php echo $category->category; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_name"><?php echo $this->lang->line('xin_product_name'); ?>
                                <i class="hrsale-asterisk">*</i></label>
                            </label>
                            <input class="form-control"
                                placeholder="<?php echo $this->lang->line('xin_product_name'); ?>"
                                name="product_name" type="text" value="<?php echo $product_name; ?>">
                        </div>
                    </div>                    
                </div>

                <div class="row">                   
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description"><?php echo $this->lang->line('xin_product_description'); ?></label>

                            <textarea class="form-control textarea"
                                placeholder="<?php echo $this->lang->line('xin_product_description'); ?>"
                                name="description" cols="30" rows="5" id="description"><?php echo $description; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku">Rate
                                <i class="hrsale-asterisk">*</i></label>
                            <input class="form-control" value="<?php echo $sell_p; ?>" name="sell_p" type="text">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku">Unit of Measurement (UOM)
                                <i class="hrsale-asterisk">*</i></label>
                            <input class="form-control" value="<?php echo $std_uom ?>" name="std_uom" type="text">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku">Size
                                <i class="hrsale-asterisk">*</i></label>
                            <input class="form-control" value="<?php echo $size ?>" name="size" type="text">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="prd_img">Product Image<i class="hrsale-asterisk">*</i></label>
                        <img src="<?php echo site_url('uploads/product/' . $prd_img) ?>" width="50px" alt="">
                        <input type="hidden" class="form-control" name="old_prd_img" value="<?php echo $prd_img; ?>">
                        <input type="hidden" class="form-control" name="old_qr_code" value="<?php echo $old_qr_code; ?>">
                        <input type="file" class="form-control" name="prd_img">
                    </div>                   
                </div>                
            </div>

        </div>

        <div class="col-md-12">
            <label for="supplier_price"><?php echo $this->lang->line('xin_supplier_price'); ?></label>
            <table class="table table-bordered" id="supplier_price_table_edit">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch suppliers already mapped with the product_id
                    $mapped_suppliers = $this->db->where('supplier_item_name', $product_id)
                        ->get('xin_supplier_item_mapping')
                        ->result();
                    if (!empty($mapped_suppliers)) {
                        foreach ($mapped_suppliers as $index => $mapped_supplier) {
                    ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <select name="supplier_name[]" class="form-control" data-plugin="select_hrm" data-placeholder="Select Supplier">
                                        <option value="">Select Supplier</option>
                                        <?php foreach ($get_suppliers as $supplier) { ?>
                                            <option value="<?php echo $supplier->supplier_id; ?>"
                                                <?php echo ($mapped_supplier->supplier_id == $supplier->supplier_id) ? "selected" : ""; ?>>
                                                <?php echo $supplier->supplier_name; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="supplier_price[]" class="form-control"
                                        value="<?php echo isset($mapped_supplier->supplier_item_price) ? $mapped_supplier->supplier_item_price : ''; ?>" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php }
                    } else { // If no mapped suppliers exist, show an empty row 
                        ?>
                        <tr>
                            <td>1</td>
                            <td>
                                <select name="supplier_name[]" class="form-control" data-plugin="select_hrm" data-placeholder="Select Supplier">
                                    <option value="">Select Supplier</option>
                                    <?php foreach ($get_suppliers as $supplier) { ?>
                                        <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->supplier_name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="supplier_price[]" class="form-control" placeholder="Price" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm" id="add_supplier_roww">
                <i class="fa fa-plus"></i> <?php echo $this->lang->line('xin_add_row'); ?>
            </button>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                function resetSerialNumbers() {
                    $('#supplier_price_table_edit tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                }

                $('#add_supplier_roww').click(function() {
                    var newRow = `
            <tr>
                <td></td>
                <td>
                    <select name="supplier_name[]" class="form-control" data-plugin="select_hrm" data-placeholder="Select Supplier">
                        <option value="">Select Supplier</option>
                        <?php foreach ($get_suppliers as $supplier) { ?>
                            <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->supplier_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="supplier_price[]" class="form-control" placeholder="Price" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
                    $('#supplier_price_table_edit tbody').append(newRow);
                    resetSerialNumbers();
                });

                $(document).on('click', '.remove-row', function() {
                    $(this).closest('tr').remove();
                    resetSerialNumbers();
                });

                resetSerialNumbers();
            });
        </script>



    </div>
    <!--</div>-->
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
        <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {

            jQuery("#ajx_company").change(function() {
                jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
                    jQuery('#employee_ajx').html(data);
                });
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({
                width: '100%'
            });
            // Award Date
            $('.d_award_date').datepicker({
                changeMonth: true,
                changeYear: true,
                format: 'dd-mm-yyyy',
                yearRange: '1900:' + (new Date().getFullYear() + 15),
                beforeShow: function(input) {
                    $(input).datepicker("widget").show();
                }
            });
            // Award Month & Year
            $('.d_month_year').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                format: 'yyyy-mm',
                yearRange: '1900:' + (new Date().getFullYear() + 15),
                beforeShow: function(input) {
                    $(input).datepicker("widget").addClass('hide-calendar');
                },
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1));
                    $(this).datepicker('widget').removeClass('hide-calendar');
                    $(this).datepicker('widget').hide();
                }

            });
            $("#edit_product").submit(function(e) {
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                var abc = new FormData(this);
                abc.append('is_ajax', 1);
                abc.append('edit_type', 'product');
                abc.append('form', action);
                $('.save').prop('disabled', true);
                $('.icon-spinner3').show();
                $.ajax({
                    type: "POST",
                    url: base_url + "/update",
                    // data: obj.serialize() + "&is_ajax=1&add_type=product&form=" + action,
                    data: abc,
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

                            // Initialize DataTable for the first category by default
                            var category_id = '<?php echo $category_id; ?>'; // Default category
                            var xin_table = $('#xin_table').dataTable({
                                "bDestroy": true,
                                "ajax": {
                                    url: base_url + '/get_products_by_category/' + category_id,
                                    type: 'GET'
                                },
                                "fnDrawCallback": function(settings) {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });

                            // Handle tab click and load products for the selected category
                            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                                // Get category ID from tab
                                category_id = $(this).attr('href').split('-')[1]; // Extract category ID from tab href

                                // Destroy the current DataTable and reinitialize it with the new category data
                                xin_table.DataTable().clear().destroy();
                                xin_table = $('#xin_table').dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: base_url + '/get_products_by_category/' + category_id,
                                        type: 'GET'
                                    },
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                            });
                            xin_table.api().ajax.reload(function() {
                                toastr.success(JSON.result);
                            }, true);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.icon-spinner3').hide();
                            $('.edit-modal-data').modal('toggle');
                            $('.save').prop('disabled', false);

                        }
                    }
                });
            });
        });
    </script>
<?php } else if (isset($_GET['jd']) && isset($_GET['award_id']) && $_GET['data'] == 'view_award') {
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_view_award'); ?></h4>
    </div>
    <form class="m-b-1">
        <div class="modal-body">
            <table class="footable-details table table-striped table-hover toggle-circle">
                <tbody>
                    <tr>
                        <th><?php echo $this->lang->line('module_company_title'); ?></th>
                        <td style="display: table-cell;"><?php foreach ($get_all_companies as $company) { ?>
                                <?php if ($company_id == $company->company_id): ?>
                                    <?php echo $company->name; ?>
                                <?php endif; ?>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('dashboard_single_employee'); ?></th>
                        <td style="display: table-cell;"><?php foreach ($all_employees as $employee) { ?>
                                <?php if ($employee_id == $employee->user_id): ?>
                                    <?php echo $employee->first_name . ' ' . $employee->last_name; ?>
                                <?php endif; ?>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_award_type'); ?></th>
                        <td style="display: table-cell;"><?php foreach ($all_award_types as $award_type) { ?>
                                <?php if ($award_type_id == $award_type->award_type_id): ?>
                                    <?php echo $award_type->award_type; ?>
                                <?php endif; ?>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_e_details_date'); ?></th>
                        <td style="display: table-cell;"><?php echo $this->Xin_model->set_date_format($created_at); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_award_month_year'); ?></th>
                        <td style="display: table-cell;"><?php echo $award_month_year; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_gift'); ?></th>
                        <td style="display: table-cell;"><?php echo $gift_item; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_cash'); ?></th>
                        <td style="display: table-cell;"><?php echo $this->Xin_model->currency_sign($cash_price); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_award_photo'); ?></th>
                        <td style="display: table-cell;"><?php if ($award_photo != '' && $award_photo != 'no file') { ?>
                                <img src="<?php echo base_url() . 'uploads/award/' . $award_photo; ?>" width="70px"
                                    id="u_file">&nbsp; <a
                                    href="<?php echo site_url() ?>admin/download?type=award&filename=<?php echo $award_photo; ?>"><?php echo $this->lang->line('xin_download'); ?></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_award_info'); ?></th>
                        <td style="display: table-cell;"><?php echo html_entity_decode($award_information); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('xin_description'); ?></th>
                        <td style="display: table-cell;"><?php echo html_entity_decode($description); ?></td>
                    </tr>
                    <?php $count_module_attributes = $this->Custom_fields_model->count_awards_module_attributes(); ?>
                    <?php $module_attributes = $this->Custom_fields_model->awards_hrsale_module_attributes(); ?>
                    <?php foreach ($module_attributes as $mattribute): ?>
                        <?php $attribute_info = $this->Custom_fields_model->get_employee_custom_data($award_id, $mattribute->custom_field_id); ?>
                        <?php
                        if (!is_null($attribute_info)) {
                            $attr_val = $attribute_info->attribute_value;
                        } else {
                            $attr_val = '';
                        }
                        ?>
                        <?php if ($mattribute->attribute_type == 'date') { ?>
                            <tr>
                                <th><?php echo $mattribute->attribute_label; ?></th>
                                <td style="display: table-cell;"><?php echo $attr_val; ?></td>
                            </tr>
                        <?php } else if ($mattribute->attribute_type == 'select') { ?>
                            <?php $iselc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id); ?>
                            <tr>
                                <th><?php echo $mattribute->attribute_label; ?></th>
                                <td style="display: table-cell;"><?php foreach ($iselc_val as $selc_val) { ?>
                                        <?php if ($attr_val == $selc_val->attributes_select_value_id): ?>
                                            <?php echo $selc_val->select_label ?> <?php endif; ?><?php } ?></td>
                            </tr>
                        <?php } else if ($mattribute->attribute_type == 'multiselect') { ?>
                            <?php $multiselect_values = explode(',', $attr_val); ?>
                            <?php $imulti_selc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id); ?>
                            <tr>
                                <th><?php echo $mattribute->attribute_label; ?></th>
                                <td style="display: table-cell;"><?php foreach ($imulti_selc_val as $multi_selc_val) { ?>
                                        <?php if (in_array($multi_selc_val->attributes_select_value_id, $multiselect_values)): ?><br />
                                            <?php echo $multi_selc_val->select_label ?> <?php endif; ?><?php } ?></td>
                            </tr>
                        <?php } else if ($mattribute->attribute_type == 'textarea') { ?>
                            <tr>
                                <th><?php echo $mattribute->attribute_label; ?></th>
                                <td style="display: table-cell;"><?php echo $attr_val; ?></td>
                            </tr>
                        <?php } else if ($mattribute->attribute_type == 'fileupload') { ?>
                            <tr>
                                <th><?php echo $mattribute->attribute_label; ?></th>
                                <td style="display: table-cell;"><?php if ($attr_val != '' && $attr_val != 'no file') { ?>
                                        <img src="<?php echo base_url() . 'uploads/custom_files/' . $attr_val; ?>" width="70px"
                                            id="u_file">&nbsp; <a
                                            href="<?php echo site_url('admin/download'); ?>?type=custom_files&filename=<?php echo $attr_val; ?>"><?php echo $this->lang->line('xin_download'); ?></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <th><?php echo $mattribute->attribute_label; ?></th>
                                <td style="display: table-cell;"><?php echo $attr_val; ?></td>
                            </tr>
                        <?php } ?>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
        </div>
        <?php echo form_close(); ?>
    <?php }
    ?>