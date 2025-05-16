<?php
/* Purchase view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('3102',$role_resources_ids)) {?>

<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_add_new');?>
                <?php echo $this->lang->line('xin_employee_set_oth_claims');?></h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new');?></button>
                </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
            <div class="box-body">
                <?php $attributes = array('name' => 'add_quotation', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open_multipart('admin/claim/add_claim', $attributes, $hidden);?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                            <div class="form-group">
                                <label for="project_id"><?php echo $this->lang->line('xin_project');?>
                                   
                                </label>
                                <select class="form-control" name="project_id" id="project_id" data-plugin="xin_select"
                                    data-placeholder="<?php echo $this->lang->line('xin_customer');?>">
                                    <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                    <?php foreach($get_all_projects as $project) {?>
                                    <option value="<?php echo $project->project_id;?>">
                                        <?php echo $project->project_title;?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="amount">Name of Claimer <i class="hrsale-asterisk">*</i></label>
                               <input type="text" name="claimer_name" id="claimer_name" class="form-control" placeholder="Claimer Name">
                            </div>
                            <div class="form-group">
                                <label for="amount"><?php echo $this->lang->line('xin_amount');?></label>
                               <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" onkeyup="get_total_amount()">
                            </div>
                            <div class="form-group">
                            <input type="checkbox" class="listcheckbox listcheckbox-files filled-in chk-col-light-blue" id="is_gst" name="is_gst" value="1"><label for="is_gst">GST Inclusive</label>
                            </div>
                            <div class="form-group"  id="gst_div">
                                <label for="total_gst" class="form-label">GST</label>
                                <select class="form-control select22" id="total_gst" name="gst" onchange="totalGSTAmount()" readonly>
                                            <option value="">Select GST</option>
                                        <?php foreach($get_gst->result() as $gst){ ?>
                                                    <option value="<?php echo $gst->gst; ?>"><?php echo $gst->gst;?></option>
                                                <?php } ?>
                                            </select>
                                <!-- <input class="form-control" type="text" value="" id="u_total_gst1" name="u_total_gst1"
                                    placeholder="0" value="<?php //echo (($gst >0)?$gst:0) ;?>" onkeyup="totalGSTAmount()"> -->
                            </div>
                            <div class="form-group">
                                <label for="amount"><?php echo $this->lang->line('xin_total_amount');?></label>
                               <input type="text" name="total_amount" id="total_amount" class="form-control" placeholder="Total Amount" readonly>
                            </div>
                            <div class="form-group">
                                <label for="description"><?php echo $this->lang->line('xin_description');?> </label>
                               <textarea name="description" class="form-control"  placeholder="Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file"><?php echo $this->lang->line('xin_employee_select_d_file');?></label>
                               <input type="file" name="document_file">
                            </div>

                           
                        </div>


                    </div>
                </div>
                <div class="form-actions box-footer">
                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                        <?php echo $this->lang->line('xin_save');?> </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?>
            <?php echo $this->lang->line('xin_employee_set_oth_claims');?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                <tr>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                        <th><?php echo $this->lang->line('xin_project');?></th>
                        <th><?php echo $this->lang->line('xin_claimer_name');?></th>
                        <th><?php echo $this->lang->line('xin_amount');?></th>
                        <th><?php echo $this->lang->line('xin_created_date');?></th>


                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
     $("#is_gst").change(function() {
    if(this.checked) {
       $("#gst_div").hide();
     
    }
  else
    {
        $("#gst_div").show();

      
    }
});
    function totalGSTAmount() {
    var total_amount = parseFloat($('#amount').val());
    var tax = parseFloat($("#total_gst option:selected").text());
    var total = total_amount + total_amount * (tax / 100);
    $('#total_amount').val(total);
}
function get_total_amount(){
    var amount=$('#amount').val();
    $("#total_amount").val(amount);
}


</script>