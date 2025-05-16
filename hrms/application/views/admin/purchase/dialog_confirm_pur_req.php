<style>
    #ajax_modal_view table tbody tr {
        /* width: 1200px !important;
        margin-left: -180px; */
        overflow-y: scroll !important
    }

    table.table-fit {
        width: auto !important;
        table-layout: auto !important;

    }

    table.table-fit thead th,
    table.table-fit tbody td,
    table.table-fit tbody tr,
    table.table-fit tfoot th,
    table.table-fit tfoot td {
        width: auto !important;
    }
</style>


<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['purchase_requistion_id']) && $_GET['data'] == 'view_purchase') {

?>
    <?php $system = $this->Xin_model->read_setting_info(1); ?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="view-modal-data">Confirm Purchase Requsition</h4>
    </div>
    <?php $attributes = array('name' => 'view_conf_purchase', 'id' => 'view_conf_purchase', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['purchase_requistion_id'], 'ext_name' => $_GET['purchase_requistion_id']); ?>
    <?php echo form_open('admin/purchase/conf_req', $attributes, $hidden); ?>

    <!-- Modal body -->
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-striped" style="overflow-y: scroll !important">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Item</th>
                        <th>Quantity Requested</th>
                        <th style="color:blue;">Warehouse</th>
                        <th>Quantity Issue</th>
                        <th>Balance Stock</th>
                        <th style="color:green;">Supplier</th>
                        <th>Price</th>
                        <!-- <th>Description</th> -->
                        <th>Supplier Ref No</th>
                        <th>Terms</th>
                        <th>Remark</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    foreach ($all_items as $items) {
                        $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <select name="u_item[]" id="u_item_<?php echo $i; ?>" onclick="checkProductExistence(this.value,'<?php echo $i; ?>');" class="form-control" style="width: 150px;">
                                    <option value="">Select product</option>
                                    <?php foreach ($all_products as $product) { ?>
                                        <option value="<?php echo $product->product_id ?>" <?php if ($product->product_id == $items->product_id) {
                                                                                                echo "selected";
                                                                                            } ?>><?php echo $product->product_name ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="prd_uom_from_prq[]" id="prd_uom_from_prq<?php echo $i; ?>" value="<?= $items->uom ?>">
                            </td>
                            <td>
                                <input style="width: 150px;" class="form-control" type="text" value="<?= $items->qty ?>" name="u_qty[]" id="u_qty_<?php echo $i; ?>">
                            </td>
                            <td>
                                <select style="width: 150px;" name="u_warehouse_[]" id="u_warehouse_<?php echo $i; ?>" onclick="getProductQtn(this.value,'<?php echo $i; ?>')" class="form-control" data-placeholder="Select Supplier">
                                    <option value="">Select Warehouse</option>
                                </select>
                            </td>
                            <td>
                                <input style="width: 150px;" class="form-control" type="text" name="u_apr[]" id="u_apr_<?php echo $i; ?>">
                            </td>
                            <td>
                                <input style="width: 150px;" class="form-control" type="text" name="feed[]" id="feed_<?php echo $i; ?>">
                            </td>
                            <td>
                                <select style="width: 150px;" name="u_supplier[]" id="u_supplier_warehouse_<?php echo $i; ?>" onclick="getProductQtn(this.value,'<?php echo $i; ?>')" class="form-control" data-placeholder="Select Supplier">
                                    <option value="">Select Supplier</option>
                                </select>
                                <input type="hidden" name="supplier_warehouse_type[]" id="supplier_warehouse_type_<?php echo $i; ?>" value="">
                            </td>
                            <td>
                                <input style="width: 150px;" type="text" name="u_price[]" id="u_price_<?php echo $i; ?>" class="form-control">
                                <input type="hidden" name="u_total[]" id="u_total_<?php echo $i; ?>">
                            </td>


                            <td><input style="width: 150px;" type="text" class="form-control" name="sup_ref[]" id="sup_ref_<?php echo $i; ?>"></td>
                            <td>
                                <?php $term = $this->db->get('xin_payment_term')->result() ?>
                                <select class="form-control" name="terms[]" id="terms_<?php echo $i; ?>" style="width: 150px;" class="form-control" data-plugin="select_hrm" data-placeholder="Terms">
                                    <option value="">Select</option>
                                    <?php foreach ($term as $terms) { ?>
                                        <option value="<?php echo $terms->payment_term ?>"><?php echo $terms->payment_term ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <textarea class="form-control" name="u_remark[]" id="u_remark_<?php echo $i; ?>" rows="5" style="width: 150px;"><?= $items->remark ?></textarea>
                            </td>
                            <td>
                                <select style="width: 150px;" name="item_status[]" id="item_status_<?php echo $i; ?>" class="form-control" data-placeholder="Select Status">
                                    <option value="">Select Status</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Accepted">Accepted</option>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
            <input type="hidden" name="grand_total" id="grand_total">
            <input type="hidden" name="pr_proj_id" id="pr_proj_id" value="<?php echo $project_id[0]->project_id ?>">

        </div>

    </div>



    <!-- Modal footer -->
    <p class="text-danger"><b>** When selecting data from the <font style="color:blue;">Warehouse</font>, ensure that you also select <font style="color:green;">Supplier</font>.**</b></p>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
    <?php echo form_close(); ?>

    <script>
        $(document).ready(function() {
            $('.modal-dialog').addClass('modal-xxl');

            // Trigger checkProductExistence for each item select dropdown on document load
            $('select[name^="u_item"]').each(function() {
                var number = $(this).attr('id').split('_')[2];
                checkProductExistence($(this).val(), number);
            });
            $('select[name^="u_item"]').on('change', function() {
                var number = $(this).attr('id').split('_')[2];
                checkProductExistence($(this).val(), number);
            });
            // Add event listener for u_apr input fields
            var timeoutId;

            $('input[name^="u_apr"]').on('input', function() {
                var number = $(this).attr('id').split('_')[2];

                // Clear the timeout if it's already set
                if (timeoutId) {
                    clearTimeout(timeoutId);
                }

                // Set a new timeout
                timeoutId = setTimeout(function() {
                    updateBalanceStock(number);
                }, 1000); // Wait for 1 second (1000 milliseconds)
            });


            $('input[name^="u_qty"]').on('input', function() {
                var number = $(this).attr('id').split('_')[2];
                checkProductExistence($('#u_item_' + number).val(), number);
            });
        });




        // Usage
        function checkProductExistence(productId, number) {
            var quantity = $('#u_qty_' + number).val();
            // console.log("Quantity " + quantity);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'admin/purchase/check_product_existence/'; ?>" + productId + "/" + quantity,
                dataType: "json",
                success: function(data) {
                    // $('#feed_' + number).val(data.total_quantity);

                    if (data.exists) {
                        populateWarehouseList1(productId, number);
                        getProductDetail(productId, number);

                        getProductQtn(productId, number, 'warehouse');
                        updateSupplierWarehouseType(number, 'supplier');
                    } else {
                        // $("#u_price_" + number).empty();

                        populateWarehouseList1(productId, number);
                        getProductDetail(productId, number);
                        getProductQtn(productId, number, 'supplier');
                        updateSupplierWarehouseType(number, 'supplier');
                    }
                },
                error: function() {
                    toastr.error("Error checking product existence.");
                }
            });
        }


        function updateSupplierWarehouseType(number, type) {
            $('#supplier_warehouse_type_' + number).val(type);
        }


        function getProductQtn(id, supplier, number, type) {
            var iid2 = $('#u_item_' + number).val();
            // console.log(id,supplier,type);
            if (type === 'supplier') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() . 'admin/purchase/get_product_qtn/'; ?>" + iid2 + "/" + supplier,
                    data: JSON,
                    success: function(data) {
                        var product_data = jQuery.parseJSON(data);

                        console.log(product_data);

                        // Handling supplier case
                        // if(product_data[0].length > 0){
                        $("#u_price_" + number).val(product_data[0].supplier_item_price);
                        $("#terms_" + number).val(product_data[0].supplier_terms).trigger("change") ;
                        $("#u_total_" + number).val(parseFloat($("#u_price_" + number).val()) * parseFloat($("#u_qty_" + number).val()));

                        var totalFields = document.querySelectorAll('input[name="u_total[]"]');
                        var grandTotal = 0;

                        // Sum all row totals
                        totalFields.forEach(function(field) {
                            grandTotal += parseFloat(field.value) || 0;
                        });

                        // Update the grand total somewhere on the page
                        document.getElementById('grand_total').value = grandTotal.toFixed(2);
                        // }else{
                        //     $("#u_price_" + number).empty();
                        // }

                    },
                    // error: function() {
                    //     toastr.error("Error loading data.");
                    // }
                });
            } else if (type === 'warehouse') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() . 'admin/purchase/get_product_qtn1/'; ?>" + id,
                    data: JSON,
                    success: function(data) {
                        var product_data = jQuery.parseJSON(data);


                        // Handling warehouse case
                        // $('#feed_' + number).val(product_data.warehouse_qtn[0].item_qty);

                    },
                    // error: function() {
                    //     toastr.error("Error loading data.");
                    // }
                });
            }

        }

        function populateWarehouseList(productId, number) {
            var selectElement = $("#u_supplier_warehouse_" + number);
            // var feedElement = $('#feed_' + number);

            selectElement.empty();
            selectElement.append('<option value="">Select Warehouse</option>');

            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'admin/purchase/get_warehouses/'; ?>" + productId,
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    data.forEach(function(warehouse) {
                        if (warehouse.quantity > 0) {
                            selectElement.append('<option value="' + warehouse.w_id + '" data-quantity="' + warehouse.quantity + '">' + warehouse.w_name + ' (' + warehouse.quantity + ' in stock)</option>');
                        }
                    });

                    // Set initial balance stock in feed field
                    // if (data.length > 0) {
                    //     feedElement.val(data[0].quantity);
                    // }
                },
                error: function() {
                    toastr.error("Error loading warehouses.");
                }
            });

            // Handle warehouse selection change
            selectElement.on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var selectedQuantity = selectedOption.data('quantity');

                // Set the feed field to the selected warehouse's quantity
                feedElement.val(selectedQuantity || '');
            });
        }

        function populateWarehouseList1(productId, number) {
            var selectElement = $("#u_warehouse_" + number);
            var feedElement = $('#feed_' + number);
            var Projects_id = $('#pr_proj_id').val();

            selectElement.empty();
            selectElement.append('<option value="">Select Warehouse</option>');

            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'admin/purchase/get_warehouses/'; ?>" + productId +"/"+ Projects_id,
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    data.forEach(function(warehouse) {
                        if (warehouse.quantity > 0) {
                            selectElement.append('<option value="' + warehouse.w_id + '" data-quantity="' + warehouse.quantity + '">' + warehouse.w_name + ' (' + warehouse.quantity + ' in stock)</option>');
                        }
                    });

                    // Set initial balance stock in feed field
                    // if (data.length > 0) {
                    //     feedElement.val(data[0].quantity);
                    // }
                },
                error: function() {
                    toastr.error("Error loading warehouses.");
                }
            });

            // Handle warehouse selection change
            selectElement.on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var selectedQuantity = selectedOption.data('quantity');

                // Set the feed field to the selected warehouse's quantity
                feedElement.val(selectedQuantity || '');
            });
        }


        function getProductDetail(id, number) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'admin/purchase/get_product_detail/'; ?>" + id,
                dataType: "json",
                success: function(data) {


                    var product_data = data;
                    var targetSelect = $("#u_supplier_warehouse_" + number);
                    targetSelect.empty();
                    // $("#u_price_" + number).empty();
                    targetSelect.append('<option value="">Select Supplier</option>');
                    for (var i = 0; i < product_data.length; i++) {
                        var tempName = product_data[i].temp_name ? product_data[i].temp_name : product_data[i].supplier_name;
                        // var selected = (product_data[i].supplier_id != 0 || product_data[i].emps_id != 0) ? 'selected' : '';
                        // ' + selected + '
                        targetSelect.append('<option value="' + product_data[i].supplier_id + '">' + tempName + '</option>');
                    }

                    //if (product_data.length > 0) {
                    //     getProductQtn(id, product_data[0].supplier_id, number, 'supplier');
                    // }

                    // Add event listener for supplier dropdown change
                    targetSelect.on('click', function() {
                      
                        var selectedSupplier = $(this).val();
                        var selectedPrice = $(this).find('option:selected').data('price');
                        var selectedTerm = $(this).find('option:selected').data('terms');
                       
                        $("#u_price_" + number).val(selectedPrice || '');
                        $("#terms_" + number).val(selectedTerm||'').trigger("change");
                        getProductQtn(id, selectedSupplier, number, 'supplier');
                    });
                },
                error: function() {
                    toastr.error("Supplier Not Found");
                }
            });
        }

        function updateBalanceStock(number) {
            var $feed = $('#feed_' + number);

            // Get the initial stock value from the data attribute, or set it if it doesn't exist
            if (!$feed.data('initial-stock')) {
                $feed.data('initial-stock', parseFloat($feed.val()) || 0);
            }
            var initialStock = $feed.data('initial-stock');

            // Get the aprValue, or default to 0 if the field is empty
            var aprValue = parseFloat($('#u_apr_' + number).val()) || 0;
            $("#u_total_" + number).val(parseFloat($("#u_price_" + number).val()) * ($("#u_qty_" + number).val() - aprValue));

            var totalFields = document.querySelectorAll('input[name="u_total[]"]');
            var grandTotal = 0;

            // Sum all row totals
            totalFields.forEach(function(field) {
                grandTotal += parseFloat(field.value) || 0;
            });

            // Update the grand total somewhere on the page
            document.getElementById('grand_total').value = grandTotal.toFixed(2);

            console.log(initialStock);
            console.log(aprValue);

            var balanceStock = initialStock - aprValue;

            // Ensure balanceStock is not negative
            balanceStock = balanceStock < 0 ? 0 : balanceStock;

            // Update the value of the feed input
            $feed.val(balanceStock);
        }


        $("#view_conf_purchase").submit(function(e) {
            var fd = new FormData(this);

            var obj = $(this),
                action = obj.attr('name');
            fd.append("is_ajax", 1);
            fd.append("edit_type", 'conf_purchase_requistion');
            fd.append("form", action);
            e.preventDefault();
            $('.icon-spinner3').show();
            $('.save').prop('disabled', true);
            $.ajax({
                url: e.target.action,
                type: "POST",
                data: obj.serialize() + "&edit_type=conf_purchase_requistion",
                // contentType: false,
                cache: false,
                // processData: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                        $('.icon-spinner3').hide();
                    } else {

                        toastr.success(JSON.result);
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);

                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.icon-spinner3').hide();
                        $('.view-modal-data').modal('toggle');
                        $('.save').prop('disabled', false);
                    }
                },
                error: function() {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            });
        });
    </script>
<?php } ?>