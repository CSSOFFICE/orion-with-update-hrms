@php
$purchase_purpose = DB::table('purchase_purpose')->get();
$all_customers = DB::table('xin_employees')->get();
// $all_products = DB::table('product')->get();
$w = DB::table('warehouse')->get();
$all_products = DB::table('stock_management')
->Join('product', 'product.product_id', '=', 'stock_management.prd_id')
->select('stock_management.prd_id', 'stock_management.quantity', 'stock_management.warehouse_id', 'product.*')
->groupBy('stock_management.prd_id')
->get();
@endphp

<div class="row">
    <div class="col-lg-12">
        <input type="hidden" id="prj_id" name="prj_id" value="{{ request('noteresource_id') }}">

        <!--title-->
        <div class="form-group row">
            <div class="table-responsive my-3 purchaseTable">
                <table class="table" id="v_table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Item</th>
                            <th>Warehouse</th>
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
        <input type="hidden" name="source" value="{{ request('source') }}">

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
                         <select class="packing_dropdown form-control select22" name="product_id[]" id="product_"` +
                item +
                ` onchange="getProductDetail(this.value,` + item + `)" required>
                            <option value="">Select product</option>
                            <?php foreach ($all_products as $product) {
                                echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                            } ?>
                        </select>
                    </td>
                    <td >
                        <select id="warehouse` + item +
                `" class="form-control whouse" name="warehouse[]" placeholder="warehouse" onchange="changeWhaouse(this,` +
                number + `)" required>
                            <option value="">Select Warehouse</option>
                            </select>
                    </td>

                    <td style="min-width:200px">
                        <input type="number" min="0" id="quantity` + item +
                `" value="" class="form-control qtns" name="quantity[]" placeholder="Quantity" onblur="checkQtn(this,` +
                item + `)" required>
                    </td>
                    <td style="min-width:200px">
                        <input type="text" id="remark` + item + `" class="form-control" name="remark[]" placeholder="Remark">
                    </td>
                    <td>
                        <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                        </tr>
                        `);

        });
        // <td style="min-width:200px">
        //     <input type="text" id="remark` + item + `" class="form-control" name="remark[]" placeholder="Remark">
        // </td>


        $("#commonModalForm").attr("id", "inventoryModalForm");
        $("#inventoryModalForm").attr("action", " ");
        $("#commonModalSubmitButton").attr("data-url", " ");
        $('#inventoryModalForm').on('submit', function(e) {
            e.preventDefault();
            console.log("object");
            $.ajax({
                type: "POST",
                url: "{{ route('projectinventory.inventory-submit') }}",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Add CSRF token to header
                },
                success: function(response) {
                    console.log(response)
                    if (response.status == "error") {
                        $.each(response.error, function(key, value) {
                            noty({
                                text: value,
                                layout: 'bottomLeft',
                                type: 'warning',
                                timeout: 3000,
                                progressBar: false,
                                closeWith: ['click', 'button', 'backdrop'],
                            });
                            // alert(value);

                        });
                    } else if (response.status == "success") {
                        noty({
                            text: response.status,
                            layout: 'bottomLeft',
                            type: 'success',
                            timeout: 3000,
                            progressBar: false,
                            closeWith: ['click', 'button', 'backdrop'],
                        });
                        location.reload();
                    } else {
                        noty({
                            text: response.message,
                            layout: 'bottomLeft',
                            type: 'warning',
                            timeout: 3000,
                            progressBar: false,
                            closeWith: ['click', 'button', 'backdrop'],
                        });
                    }
                }
            });
        });


    });


    function getProductDetail(id, number) {


        $.ajax({
            type: "get",

            url: "{{ url('get_product_details') }}",
            data: {
                id
            },
            success: function(response) {
                // console.log(response.ress.length);
                if (response.ress.length > 0) {
                    $("#warehouse" + number).empty();
                    $("#quantity" + number).val(0);
                    $.each(response.ress, function(key, value) {
                        var html =
                            `<option value="${value.w_id}" data-quantity="${value.quantity}">${value.w_name}</option>`;
                        $("#warehouse" + number).append(html);
                    });
                }

            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function changeWhaouse(el, name) {
        $(el).parents('tr').find(".qtns").val(0);
    }

    function checkQtn(el, number) {
        var input_qty = $(el).val();

        // var w_qty=$(el).parents('tr').find('.whouse').data('quantity')
        var w_qty = $(el).parents('tr').find(".whouse").find(":selected").data('quantity');


        if (input_qty > w_qty) {

            noty({
                text: 'Avalable Quantity: ' + w_qty,
                layout: 'bottomLeft',
                type: 'warning',
                timeout: 3000,
                progressBar: false,
                closeWith: ['click', 'button', 'backdrop'],
            });

            $(el).val(0);

        }
        console.log(w_qty);
    }



    $(document).on('click', '.remove-input-field', function() {
        $(this).parents('tr').remove();


    });
</script>
