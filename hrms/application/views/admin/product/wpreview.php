<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>

<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title">Stock Take Report</h3>
        <button style="float:right;" type="button" class="btn btn-info" id="export_btn" disabled>Export</button>
    </div>
    <div class="box-body">
        <?php
        $whouse_id = $this->uri->segment(4);
        $list = $this->db->select('stock_management.warehouse_id,
         product.product_name,                                   
         product.product_id, 
         category.category,                                 
         stock_management.quantity')
            ->from('stock_management')
            ->join('product', 'stock_management.prd_id = product.product_id', 'left')
            ->join('category', 'product.category_id = category.category_id', 'left')
            ->where('stock_management.warehouse_id', $whouse_id)
            // ->group_by('grn_log.item')
            ->get()->result();
        ?>

        <form action="" method="POST" id="stock_report_form">
            <input type="hidden" id="warehouse_id" name="warehouse_id" value="<?php echo $this->uri->segment(4) ?>">
            <div class="box-datatable table-responsive">
                <table class="table table-striped" id="stock_report_table">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th width="350">Product Category</th>
                            <th width="350">Product Name</th>
                            <th data-exclude="true">Warehouse Qty</th>
                            <th data-exclude="true">Count Quantity</th>
                            <th style="display: none;">Count Quantity</th>
                            <th class="th_warehouse_qty" style="display: none;">Warehouse Qty</th>
                            <th class="th_diff_qty" style="display: none;">Difference</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($list as $warehouse_data) {
                            $i++; ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $warehouse_data->category; ?></td>
                                <td><?php echo $warehouse_data->product_name; ?></td>
                                <td data-exclude="true"><?php echo $warehouse_data->quantity; ?></td>
                                <td class="td_current_qty" data-exclude="true">
                                    <input type="hidden" name="p_id[]" value="<?php echo $warehouse_data->product_id; ?>">
                                    <input type="number" class="form-control current_qty_class" name="current_qty[]" min="0" style="width: 100px;">
                                </td>
                                <td class="hide_qty" style="display: none;" data-t="n"></td>
                                <td class="td_warehouse_qty" style="display: none;" data-t="n"><?php echo $warehouse_data->quantity; ?></td>
                                <td class="td_diff_qty" style="display: none;" data-t="n"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div style="text-align: end;" class="mt-3">
                    <button type="button" class="btn btn-info" id="next_btn" disabled>Next</button>
                    <button type="submit" class="btn btn-info" id="update_btn" style="display: none;">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<script>
    function check_qty_filled() {
        var size = $("#stock_report_table .td_current_qty").get().length;

        if (size > 0) {
            var flag = 0;

            for (var i = 0; i < size; i++) {
                var qty = $("#stock_report_table .current_qty_class").eq(i).val();

                // console.log(qty);

                if (qty != "") {
                    flag++;
                }
            }

            // if(flag == size)
            if (flag > 0) {
                $("#next_btn").attr('disabled', false);
                $("#export_btn").attr('disabled', false);
            } else {
                $("#next_btn").attr('disabled', true);
                $("#export_btn").attr('disabled', true);
            }
        }
    }
    $(document).ready(function() {

        // show hide

        $("body").on('click', '#next_btn', function() {

            // $('.th_warehouse_qty').show();
            $('.th_diff_qty').show();
            // $('.td_warehouse_qty').show();
            $('.td_diff_qty').show();

            $(this).hide();
            $("#update_btn").show();

        });

        // calculate difference

        $("body").on('blur', '.current_qty_class', function() {

            var curr_qty = $(this).val();
            var w_qty = parseInt($(this).parents("tr").find(".td_warehouse_qty").text());

            var diff = curr_qty - w_qty;

            // if(curr_qty > w_qty)
            // {
            //     var diff = curr_qty - w_qty;
            // }
            // else
            // {
            //     var diff = w_qty - curr_qty;
            // }

            $(this).parents("tr").find(".td_diff_qty").text(diff);
            $(this).parents("tr").find(".hide_qty").text(curr_qty);

            check_qty_filled();

        });

        // stock report update

        $("body").on('submit', '#stock_report_form', function(e) {

            e.preventDefault();

            $.ajax({
                type: "post",
                url: "<?php echo base_url('admin/Warehouse/update_quantity')?>",
                data: $(this).serialize(),
                success: function(result) {
                    var result=JSON.parse(result);
                    console.log(result);
                    if (result) {
                        toastr.success(result.result);
                    }
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

        // export

        $("body").on('click', '#export_btn', function() {

            TableToExcel.convert(document.getElementById("stock_report_table"), {
                name: "stock_report.xlsx",
                sheet: {
                    name: "Sheet 1"
                }
            });

        });

    });
</script>