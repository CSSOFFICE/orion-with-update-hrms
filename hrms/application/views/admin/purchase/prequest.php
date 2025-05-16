

<div class="box ">
        <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_req_pur');?></h3>
        </div>
        <div class="box-body">
            <div class="box-datatable table-responsive">
                <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Supplier</th>
                            <th>Required Date</th>
                            <th>Created Date</th>
                            <th>Status</th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <?php 
                        if(!empty($all_list_pending)){
                        foreach($all_list_pending as $list){?>
                            <tr>
                                <td><a class="btn btn-success btn-sm" onclick="return confirm('Confirm this Purchase Request ?')" href="<?php echo site_url('admin/purchase/updatestatus/'.$list['purchase_requistion_id'])?>">Confirm</a></td>
                                <td><?php echo $list['supplier_name']?></td>
                                <td><?php echo $list['required_date']?></td>
                                <td><?php echo date('d-m-Y',strtotime($list['created_datetime']))?></td>
                                <td><?php echo $list['status']?></td>
                            </tr>


                        <?php }}else{?>
                                <tr ><td colspan="5" align="center"><?php echo "No Pending Request Found";?></td></th>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
<script>
    $(document).ready(function() {

      $('#xin_table').dataTable();
        <?php if($this->session->flashdata('success')){ ?> 
            toastr.success($this->session->flashdata('success'));
        <?php unset($_SESSION['success']); }?>

                  


 });                    
</script>


   