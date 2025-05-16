<?php
    $purchase_purpose = DB::table('purchase_purpose')->get();
    $all_customers = DB::table('xin_employees')->get();
    $all_products = DB::table('product')->get();
    $w = DB::table('warehouse')->get();

?>

<div class="row">
    <div class="col-lg-12">

        <!--title-->
        <div class="form-group row">
            <div class="table-responsive my-3 purchaseTable">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Item</th>
                            <th>Warehouse</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="AddItem" id="vendor_items_table1">


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
        <!--tags-->

        <!--/#tags-->


        <!--pass source-->
        <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

        <!--notes-->


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
                        <select id="warehouse` + item + `" class="form-control" name="warehouse[${item}]" placeholder="warehouse">
                            <?php $__currentLoopData = $w; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $we): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($we->w_id); ?>"><?php echo e($we->w_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/projectinventory/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>