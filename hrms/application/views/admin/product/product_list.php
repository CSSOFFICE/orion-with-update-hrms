<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('1702', $role_resources_ids)) { ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>
                    <?php echo $this->lang->line('xin_product'); ?></h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                        aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a> </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_product', 'id' => 'xin-form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data'); ?>
                    <?php $hidden = array('_user' => $session['user_id']); ?>
                    <?php echo form_open_multipart('admin/product/add_product', $attributes, $hidden); ?>
                    <div class="bg-white">
                        <div class="box-block">
                            <div class="row">
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
                                                        <option value="<?php echo $category->category_id; ?>"><?php echo $category->category; ?></option>
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
                                                    name="product_name" type="text">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sku">Image</label>
                                                <input class="form-control" name="prd_img" type="file">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sku">Rate

                                                </label>
                                                <input class="form-control" name="sell_p" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="sku">Unit of Measurement (UOM)

                                                    <input class="form-control" name="std_uom" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="sku">Size

                                                </label>
                                                <input class="form-control" name="size" type="text">
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="location">Location</label>
                                                <input class="form-control" name="location" type="text">
                                            </div>
                                        </div> -->


                                    </div>

                                    <div class="row">
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="supplier_name">Base UOM
                                                </label>
                                                </label>
                                                <input type="text" class="form-control" name="base_uom">
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="cost_price"><?php //echo $this->lang->line('xin_product_cost');
                                                                    ?>
                                                
                                            </label>
                                            <input class="form-control"
                                                placeholder="<?php //echo $this->lang->line('xin_product_cost');
                                                                ?>" 
                                                name="cost_price" type="text">
                                        </div> 
                                    </div>-->


                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cost_price">Stock Qtn

                                                </label>
                                                <input class="form-control" name="stock_qtn" type="text">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cost_price">Safety Limit

                                                </label>
                                                <input class="form-control" name="safety_limit" type="text">
                                            </div>
                                        </div> -->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description"><?php echo $this->lang->line('xin_product_description'); ?></label>
                                                <textarea class="form-control textarea"
                                                    placeholder="<?php echo $this->lang->line('xin_product_description'); ?>"
                                                    name="description" cols="30" rows="5" id="description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="supplier_price_table">Supplier and Price</label>
                                        <table class="table table-bordered" id="supplier_price_table">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Supplier</th>
                                                    <th>Price</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <select name="supplier[]" class="form-control" data-plugin="select_hrm" data-placeholder="Select Supplier">
                                                            <option value="">Select Supplier</option>
                                                            <?php foreach ($get_suppliers as $supplier) { ?>
                                                                <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->supplier_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="price[]" class="form-control" placeholder="Enter Price">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" id="add_supplier_price_row" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Row</button>
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        // Function to reset serial numbers
                                        function resetSerialNumbers() {
                                            $('#supplier_price_table tbody tr').each(function(index) {
                                                $(this).find('td:first').text(index + 1); // Update serial number
                                            });
                                        }

                                        // Add new row
                                        $('#add_supplier_price_row').click(function() {
                                            var newRow = `
                                                <tr>
                                                    <td></td> <!-- Serial number column -->
                                                    <td>
                                                        <select name="supplier[]" class="form-control" data-plugin="select_hrm" data-placeholder="Select Supplier">
                                                            <option value="">Select Supplier</option>
                                                            <?php foreach ($get_suppliers as $supplier) { ?>
                                                                <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->supplier_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="price[]" class="form-control" placeholder="Enter Price">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>`;
                                            $('#supplier_price_table tbody').append(newRow);
                                            resetSerialNumbers(); // Reset serial numbers after adding a row
                                        });

                                        // Remove row
                                        $(document).on('click', '.remove-row', function() {
                                            $(this).closest('tr').remove();
                                            resetSerialNumbers(); // Reset serial numbers after removing a row
                                        });

                                        // Initialize serial numbers on page load
                                        resetSerialNumbers();
                                    });
                                </script>
                            </div>
                            <div class="form-actions box-footer">
                                <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php echo $this->lang->line('xin_products'); ?> </h3>
    </div>
    <div class="box-body">
        <!-- Category Tabs -->
        <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
            <?php foreach ($get_categories as $category): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($category->category_id == $get_categories[0]->category_id) ? 'active' : ''; ?>" id="category-tab-<?php echo $category->category_id; ?>" data-toggle="tab" href="#category-<?php echo $category->category_id; ?>" role="tab" aria-controls="category-<?php echo $category->category_id; ?>" aria-selected="true">
                        <?php echo $category->category; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Tab Content (will dynamically load based on the selected category) -->
        <div class="tab-content" id="categoryTabContent">
            <div class="tab-pane fade show active" id="category-1" role="tabpanel" aria-labelledby="category-tab-1">
                <div class="box-datatable table-responsive">
                    <table class="table table-striped" id="xin_table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Action</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Product QR Code</th>
                                <!-- <th>Location</th> -->
                                <th>Rate</th>
                                <th>Stock Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Product data will be dynamically loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<style type="text/css">
    .hide-calendar .ui-datepicker-calendar {
        display: none !important;
    }

    .hide-calendar .ui-priority-secondary {
        display: none !important;
    }
</style>

<script>
    var category_id = "<?php echo isset($get_categories[0]->category_id) ? $get_categories[0]->category_id : ''; ?>"; // Default category
    function CopyProduct(val) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/product/copy_product/'; ?>" + val,
            data: JSON,
            success: function(data) {
                toastr.success(data.result);
                var xin_table = $('#xin_table').dataTable({
                    pageLength: 100,
                    "bDestroy": true,
                    "ajax": {
                        url: base_url + '/get_products_by_category/' + category_id,
                        type: 'GET'
                    },
                    "fnDrawCallback": function(settings) {
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        // Check if get_categories is available



        if (category_id !== '') {
            // Initialize DataTable for the first category
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
        }

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



        $("#xin-form").submit(function(e) {
            e.preventDefault();
            var obj = $(this),
                action = obj.attr('name');
            var abc = new FormData(this);
            abc.append('is_ajax', 1);
            abc.append('form', action);

            // Validate supplier and price fields
            var isValid = true;
            $('#supplier_price_table tbody tr').each(function() {
                var supplier = $(this).find('select[name="supplier[]"]').val();
                var price = $(this).find('input[name="price[]"]').val();
                if (!supplier || !price) {
                    isValid = false;
                    toastr.error('Supplier and Price fields cannot be empty.');
                    return false; // Break out of the loop
                }
            });

            if (!isValid) {
                return; // Stop form submission if validation fails
            }

            $('.save').prop('disabled', true);
            $('.icon-spinner3').show();
            $.ajax({
                type: "POST",
                url: base_url + "/add_product",
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
                        xin_table.api().ajax.reload(function() {
                            toastr.success(JSON.result);
                        }, true);
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
        /* Delete data */
        $("#delete_record").submit(function(e) {
            /*Form Submit*/
            e.preventDefault();
            var obj = $(this),
                action = obj.attr('name');
            $.ajax({
                type: "POST",
                url: e.target.action,
                data: obj.serialize() + "&is_ajax=2&form=" + action,
                cache: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    } else {
                        $('.delete-modal').modal('toggle');
                        xin_table.api().ajax.reload(function() {
                            toastr.success(JSON.result);
                        }, true);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }
                }
            });
        });
        $(document).on("click", ".delete", function(e) {
            // alert('hi');
            e.preventDefault();

            $('input[name=_token]').val($(this).data('record-id'));
            $('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
        });

    });
</script>