<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>

<?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title">Warehouse Products</h3>

    </div>
    <div class="box-body">
        <a href="<?php echo base_url('admin/Warehouse/stocktake/' . $this->uri->segment(4)) ?>">
            <button type="button" class="btn btn-info" id="export_btn">Stock Take Report</button>
        </a>
        <a href="<?php echo base_url('admin/Warehouse/stocktransfer/' . $this->uri->segment(4)) ?>">
            <button type="button" class="btn btn-info" id="export_btn">Stock Transfer</button>
        </a>
        <a type="button" 
                                    class="btn btn-primary"  
                                    data-toggle="modal" 
                                    data-target=".view-modal-data"  
                                    data-w_id="<?php echo $this->uri->segment(4)?>">
                                    <i class="fa fa-plus"></i> Add Stock
                            </a>
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
        ?>



        <div class="box-datatable table-responsive">
            <table class="table table-striped" id="stock_report_table">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th width="350">Product Name</th>
                        <th data-exclude="true">In House Quantity</th>
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

                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.0/js/dataTables.buttons.min.js"></script>
<script>
    $(document).ready(function() {
        var xin_table = $('#stock_report_table').dataTable({
            dom: 'lBfrtip',
            "buttons": ['excel'], // colvis > if needed
            "fnDrawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>