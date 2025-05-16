<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>

<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title">Stock Transfer</h3>
      
    </div>
    <div class="box-body">
        <?php
        $whouse_id = $this->uri->segment(4);
        $list = $this->db->select('stock_management.warehouse_id,
         product.product_name,                                   
         product.product_id,                                   
         stock_management.quantity')
            ->from('stock_management')
            ->join('product', 'stock_management.prd_id = product.product_id', 'left')
            ->where('stock_management.warehouse_id', $whouse_id)
            ->where('stock_management.quantity >', 0)

            // ->group_by('grn_log.item')
            ->get()->result();

            $warehouse_list=$this->db->select('warehouse.*,xin_companies.name as organization,xin_companies.company_id')
            ->from('warehouse')
            ->join('xin_companies', 'warehouse.org_id=xin_companies.company_id')->where_not_in('w_id',$whouse_id)->get()->result();
        ?>

       
       
            <div class="box-datatable table-responsive">
                <table class="table table-striped" id="stock_transfer_table">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th width="350">Product Name</th>
                            <th data-exclude="true">In House Quantity</th>                            
                            <th data-exclude="true">Transfer Quantity</th>                            
                            <th data-exclude="true">Transfer To</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i = 0;
                    foreach ($list as $warehouse_data) {
                        $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $warehouse_data->product_name; ?></td>
                            <td><?php echo $warehouse_data->quantity; ?></td>
                            <td>
                                <input type="hidden" class="product_name" value="<?php echo $warehouse_data->product_name; ?>">
                                <input type="number" class="form-control transfer-quantity" name="transfer_qty[<?php echo $warehouse_data->product_id; ?>]"
                                    data-max="<?php echo $warehouse_data->quantity; ?>" min="1" max="<?php echo $warehouse_data->quantity; ?>">
                            </td>
                            <td>
                                <select name="transfer_to[<?php echo $warehouse_data->product_id; ?>]" class="form-control transfer-warehouse">
                                    <option value="">Select Warehouse</option>
                                    <?php foreach($warehouse_list as $w_list) { ?>
                                    <option value="<?php echo $w_list->w_id; ?>"><?php echo $w_list->w_name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                </table>
                <button id="transferBtn" class="btn btn-primary">Transfer Stock</button> 
            </div>
       
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#transferBtn').on('click', function() {
        let transferData = [];

        $('#stock_transfer_table tbody tr').each(function() { // Corrected table ID
            let product_name=$(this).find('.product_name').val();
            let product_id = $(this).find('.transfer-quantity').attr('name').match(/\[(.*?)\]/)[1];
            let quantity = $(this).find('.transfer-quantity').val();
            let maxQuantity = $(this).find('.transfer-quantity').data('max');
            let warehouse_id = $(this).find('.transfer-warehouse').val();

            // Check that quantity and warehouse are selected
            if (quantity && warehouse_id) {
                // if (quantity <= maxQuantity) {
                    transferData.push({
                        product_name: product_name,
                        product_id: product_id,
                        quantity: quantity,
                        warehouse_id: warehouse_id
                    });
                // } else {
                //     alert("Transfer quantity for " + product_name + " cannot exceed available stock.");
                //     return false;
                // }
            }
        });

        // Perform AJAX request to transfer stock
        $.ajax({
            url: '<?php echo base_url("admin/Warehouse/transfer_stock/"); ?>'+ <?php echo $this->uri->segment(4)?>,
            type: 'POST',
            data: { transferData: transferData },
            success: function(response) {
                var response = JSON.parse(response);
                if(response.result) {
                    toastr.success(response.result);
                } else {
                    toastr.error(response.error);
                }
            },
            error: function() {
                toastr.error("Error occurred while transferring stock.");
            }
        });
    });
});

</script>