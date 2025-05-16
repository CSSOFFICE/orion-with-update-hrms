<?php
    $purchase_purpose = DB::table('purchase_purpose')->get();
    $all_customers = DB::table('xin_employees')->get();
    $all_products = DB::table('product')->get();

?>


<?php if(isset($page['section']) && $page['section'] == 'show'): ?>
    <div class="row">
        <div class="col-lg-12">

            <!--title-->


            <!--description-->
            <div class="form-group row">
                <label>Purpose of Purchase</label>
                <select class="form-control" name="pp" <?php if(true): echo 'readonly'; endif; ?>>
                    <option>Select</option>
                    <?php $__currentLoopData = $purchase_purpose; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($purchase->purpose_title); ?>"
                            <?php echo e($note->purchase == $purchase->purpose_title ? 'selected' : ''); ?>>
                            <?php echo e($purchase->purpose_title ?? ''); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <input type="hidden" name="uniq_id" id="" value="<?php echo e($note->purchase_requistion_id); ?>">
            <input type="hidden" name="project_id" id="" value="<?php echo e($note->project_id); ?>">
            <!--tags-->
            <div class="form-group row">
                <label>Date</label>
                <input type="date" name="pur_date" <?php if(true): echo 'readonly'; endif; ?> value="<?php echo e($note->required_date); ?>"
                    class="form-control">
            </div>
            <div class="form-group row">
                <label for="customer_id">Employee Name<i class="hrsale-asterisk">*</i></label>
                <select name="customer_id" id="customer_id" <?php if(true): echo 'readonly'; endif; ?> class="form-control"
                    data-plugin="select_hrm" data-placeholder="Employee Name">
                    <option value="">Select Employee</option>
                    <?php $__currentLoopData = $all_customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($customer->user_id); ?>" <?php echo e($note->customer_id ? 'selected' : ''); ?>>
                            <?php echo e($customer->first_name); ?><?php echo e($customer->last_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group row">
                <label>Project Site Address</label>
                <textarea class="form-control" <?php if(true): echo 'readonly'; endif; ?> name="s_address" id="s_address"><?php echo e($note->site_address ?? ''); ?></textarea>
            </div>
            <div class="form-group row">
                <label>Location:</label>
                <select name="location" class="form-control" id="" <?php if(true): echo 'readonly'; endif; ?>>
                    <option value="<?php echo e($note->location ?? ''); ?>"><?php echo e($note->location ?? ''); ?></option>
                    <option value="workshop">Workshop</option>
                    <option value="site">Site</option>
                    <option value="office">Office</option>
                </select>

            </div>
            <div class="form-group row">
                <div class="table-responsive my-3 purchaseTable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="AddItem" id="vendor_items_table1">
                            <?php $__currentLoopData = $note_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="min-width:130px">
                                        <label><?php echo e($k + 1); ?><label>
                                    </td>
                                    <td style="min-width:200px">
                                        <select class="packing_dropdown form-control select22" <?php if(true): echo 'readonly'; endif; ?>
                                            name="product_id[<?php echo e($k + 1); ?>]" id="product_<?php echo e($k + 1); ?>"
                                            onchange="getProductDetail(this.value,<?php echo e($k + 1); ?>)">
                                            <option value="">Select product</option>
                                            <?php $__currentLoopData = $all_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($product->product_id); ?>"
                                                    <?php echo e($i->product_id == $product->product_id ? 'selected' : ''); ?>>
                                                    <?php echo e($product->product_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>

                                    <td>
                                        <textarea <?php if(true): echo 'readonly'; endif; ?> id="description<?php echo e($k + 1); ?>" <?php if(true): echo 'readonly'; endif; ?> class="form-control"
                                            name="description[<?php echo e($k + 1); ?>]" placeholder="Description"><?php echo e($i->description); ?></textarea>
                                    </td>
                                    <td style="min-width:200px">
                                        <input <?php if(true): echo 'readonly'; endif; ?> type="number" min="0" id="quantity` + item + `"
                                            value="<?php echo e($i->qty); ?>" class="form-control"
                                            name="quantity[<?php echo e($k + 1); ?>]" placeholder="Quantity">
                                    </td>
                                    <td style="min-width:200px">
                                        <input <?php if(true): echo 'readonly'; endif; ?> type="text" id="remark<?php echo e($k + 1); ?>"
                                            class="form-control" name="remark[<?php echo e($k + 1); ?>]"
                                            placeholder="Remark" value="<?php echo e($i->remark); ?>">
                                    </td>

                                    <td>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>
                </div>
            </div>
            <!--/#tags-->


            <!--pass source-->
            <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

            <!--notes-->
            <div class="row">
                <div class="col-12">
                    <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
                </div>
            </div>

            <!--info-->


        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#addButton1').on('click', function() {
                var number = $('.AddItem tr').length;
                var item = number + 1;
                $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item +
                    `<label>
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="product_id[${item}]" id="product_"` +
                    item +
                    ` onchange="getProductDetail(this.value,` + item + `)">
                                <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                            </select>
                        </td>

                        <td >
                            <textarea id="description` + item + `" class="form-control" name="description[${item}]" placeholder="Description"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="quantity` + item + `" value="" class="form-control" name="quantity[${item}]" placeholder="Quantity">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" id="remark` + item + `" class="form-control" name="remark[${item}]" placeholder="Remark">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

            });

        });


        function getProductDetail(id, number) {


            $.ajax({
                type: "get",

                url: "<?php echo e(url('get_product_details')); ?>",
                data: {
                    id
                },
                success: function(data) {
                    var product_data = jQuery.parseJSON(data);

                    $("#description" + number).text(product_data[0].description);
                },
                error: function() {
                    toastr.error("Description Not Found");
                }
            });
        }



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();


        });
    </script>
<?php endif; ?>
<?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
    <div class="row">
        <div class="col-lg-12">

            <!--title-->


            <!--description-->
            <div class="form-group row">
                <label>Purpose of Purchase</label>
                <select class="form-control" name="pp">
                    <option>Select</option>
                    <?php $__currentLoopData = $purchase_purpose; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($purchase->purpose_title); ?>"
                            <?php echo e($note->purchase == $purchase->purpose_title ? 'selected' : ''); ?>>
                            <?php echo e($purchase->purpose_title ?? ''); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <input type="hidden" name="uniq_id" id="" value="<?php echo e($note->purchase_requistion_id); ?>">
            <input type="hidden" name="project_id" id="" value="<?php echo e($note->project_id); ?>">
            <!--tags-->
            <div class="form-group row">
                <label>Date</label>
                <input type="date" name="pur_date" value="<?php echo e($note->required_date); ?>" class="form-control">
            </div>
            <div class="form-group row">
                <label for="customer_id">Employee Name<i class="hrsale-asterisk">*</i></label>
                <select name="customer_id" id="customer_id" class="form-control" data-plugin="select_hrm"
                    data-placeholder="Employee Name">
                    <option value="">Select Employee</option>
                    <?php $__currentLoopData = $all_customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($customer->user_id); ?>" <?php echo e($note->customer_id ? 'selected' : ''); ?>>
                            <?php echo e($customer->first_name); ?><?php echo e($customer->last_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group row">
                <label>Project Site Address</label>
                <textarea class="form-control" name="s_address" id="s_address"><?php echo e($note->site_address ?? ''); ?></textarea>
            </div>
            <div class="form-group row">
                <label>Location:</label>
                <select name="location" class="form-control" id="">
                    <option value="<?php echo e($note->location ?? ''); ?>"><?php echo e($note->location ?? ''); ?></option>
                    <option value="workshop">Workshop</option>
                    <option value="site">Site</option>
                    <option value="office">Office</option>
                </select>

            </div>
            <div class="form-group row">
                <div class="table-responsive my-3 purchaseTable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="AddItem" id="vendor_items_table1">
                            <?php $__currentLoopData = $note_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="min-width:130px">
                                        <label><?php echo e($k + 1); ?><label>
                                    </td>
                                    <td style="min-width:200px">
                                        <select class="packing_dropdown form-control select22"
                                            name="product_id[<?php echo e($k + 1); ?>]" id="product_<?php echo e($k + 1); ?>"
                                            onchange="getProductDetail(this.value,<?php echo e($k + 1); ?>)">
                                            <option value="">Select product</option>
                                            <?php $__currentLoopData = $all_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($product->product_id); ?>"
                                                    <?php echo e($i->product_id == $product->product_id ? 'selected' : ''); ?>>
                                                    <?php echo e($product->product_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>

                                    <td>
                                        <textarea id="description<?php echo e($k + 1); ?>" class="form-control" name="description[<?php echo e($k + 1); ?>]"
                                            placeholder="Description"><?php echo e($i->description); ?></textarea>
                                    </td>
                                    <td style="min-width:200px">
                                        <input type="number" min="0" id="quantity` + item + `"
                                            value="<?php echo e($i->qty); ?>" class="form-control"
                                            name="quantity[<?php echo e($k + 1); ?>]" placeholder="Quantity">
                                    </td>
                                    <td style="min-width:200px">
                                        <input type="text" id="remark<?php echo e($k + 1); ?>" class="form-control"
                                            name="remark[<?php echo e($k + 1); ?>]" placeholder="Remark"
                                            value="<?php echo e($i->remark); ?>">
                                    </td>

                                    <td>
                                        <button type="button" name="clear" id="clear"
                                            class="btn btn-danger remove-input-field"><i
                                                class="ti-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="border: none !important;">
                                    <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton1">Add</a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!--/#tags-->


            <!--pass source-->
            <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

            <!--notes-->
            <div class="row">
                <div class="col-12">
                    <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
                </div>
            </div>

            <!--info-->


        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#addButton1').on('click', function() {
                var number = $('.AddItem tr').length;
                var item = number + 1;
                $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item +
                    `<label>
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="product_id[${item}]" id="product_"` +
                    item +
                    ` onchange="getProductDetail(this.value,` + item + `)">
                                <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                            </select>
                        </td>

                        <td >
                            <textarea id="description` + item + `" class="form-control" name="description[${item}]" placeholder="Description"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="quantity` + item + `" value="" class="form-control" name="quantity[${item}]" placeholder="Quantity">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" id="remark` + item + `" class="form-control" name="remark[${item}]" placeholder="Remark">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

            });

        });


        function getProductDetail(id, number) {


            $.ajax({
                type: "get",

                url: "<?php echo e(url('get_product_details')); ?>",
                data: {
                    id
                },
                success: function(data) {
                    var product_data = jQuery.parseJSON(data);

                    $("#description" + number).text(product_data[0].description);
                },
                error: function() {
                    toastr.error("Description Not Found");
                }
            });
        }



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();


        });
    </script>
<?php endif; ?>
<?php if(isset($page['section']) && $page['section'] == 'create'): ?>
    <div class="row">
        <div class="col-lg-12">

            <!--title-->


            <!--description-->
            <div class="form-group row">
                <label>Purpose of Purchase</label>
                <select class="form-control" name="pp">
                    <option>Select</option>
                    <?php foreach ($purchase_purpose as $purchase) { ?>
                    <option value="<?php echo $purchase->purpose_title; ?>"><?php echo $purchase->purpose_title; ?></option>
                    <?php } ?>
                </select>
            </div>

            <!--tags-->
            <div class="form-group row">
                <label>Date</label>
                <input type="date" name="pur_date" class="form-control">
            </div>
            <div class="form-group row">
                <label for="customer_id">Employee Name<i class="hrsale-asterisk">*</i></label>
                <select name="customer_id" id="customer_id" class="form-control" data-plugin="select_hrm"
                    data-placeholder="Employee Name">
                    <option value="">Select Employee</option>
                    <?php foreach ($all_customers as $customer) { ?>
                    <option value="<?php echo $customer->user_id; ?>"> <?php echo $customer->first_name . ' ' . $customer->last_name; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group row">
                <label>Project Site Address</label>
                <textarea class="form-control" name="s_address" id="s_address"></textarea>
            </div>

            <div class="form-group row">
                <label>Location:</label>
                <select name="location" class="form-control" id="">

                    <option value="workshop">Workshop</option>
                    <option value="site">Site</option>
                    <option value="office">Office</option>
                </select>

            </div>

            <div class="form-group row">
                <div class="table-responsive my-3 purchaseTable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Remark</th>
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
            </div>
            <!--/#tags-->


            <!--pass source-->
            <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

            <!--notes-->
            <div class="row">
                <div class="col-12">
                    <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
                </div>
            </div>

            <!--info-->


        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#addButton1').on('click', function() {
                var number = $('.AddItem tr').length;
                var item = number + 1;
                $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item +
                    `<label>
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="product_id[${item}]" id="product_"` +
                    item +
                    ` onchange="getProductDetail(this.value,` + item + `)">
                                <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                            </select>
                        </td>

                        <td >
                            <textarea id="description` + item + `" class="form-control" name="description[${item}]" placeholder="Description"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="quantity` + item + `" value="0" class="form-control" name="quantity[${item}]" placeholder="Quantity">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" id="remark` + item + `" class="form-control" name="remark[${item}]" placeholder="Remark">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

            });

        });


        function getProductDetail(id, number) {


            $.ajax({
                type: "get",

                url: "<?php echo e(url('get_product_details')); ?>",
                data: {
                    id
                },
                success: function(data) {
                    var product_data = jQuery.parseJSON(data);

                    $("#description" + number).text(product_data[0].description);
                },
                error: function() {
                    toastr.error("Description Not Found");
                }
            });
        }



        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();


        });
    </script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/budget/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>