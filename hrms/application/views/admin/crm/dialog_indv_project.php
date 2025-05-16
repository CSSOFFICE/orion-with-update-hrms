<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['crm_id']) && $_GET['data']=='crm'){

?>

<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>

<div class="modal-header">
    <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
    <h4 class="modal-title" id="edit-Individual-modal-data"><?php echo "Edit Individual Project Data";?></h4>
</div>
<?php $attributes = array('name' => 'edit_indv_pro_crm', 'id' => 'edit_indv_pro_crm', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $crm_proj_id, 'ext_name' => $crm_proj_id);?>
<?php echo form_open('admin/crm/indv_project_update/'.$crm_proj_id, $attributes, $hidden);?>
<div class="modal-body">
<div class="row">
                    <input type="hidden" name="crm_proj_id" value="<?php echo $crm_proj_id; ?>">
                    <input type="hidden" name="proj_for" id="userid" value="<?php echo $proj_for; ?>">
                            <div class="col-md-12">                                                                                               
                            <div class="row">                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Title<i class="hrsale-asterisk">*</i></label>
                                            <input type="text" name="crm_proj_title" id="proj_title" class="form-control"
                                            placeholder="Project Title" value="<?php echo $crm_proj_title;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Start Date<i class="hrsale-asterisk">*</i></label>
                                            <input type="date" name="crm_proj_start" id="proj_s_date" class="form-control"
                                            placeholder="Contact Number" value="<?php echo $crm_proj_start;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Deadline</label>
                                            <input type="date" name="crm_proj_stop" id="proj_stop" class="form-control"
                                            placeholder="Customer Email" value="<?php echo $crm_proj_stop;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Desctiption<i class="hrsale-asterisk">*</i></label>
                                            <input type="text" name="crm_proj_des" id="proj_des" class="form-control"
                                            placeholder="Project Description" value="<?php echo $crm_proj_des;?>">
                                        </div>
                                    </div> 
                                </div> 
    
                            </div>                          
                        </div>
                       
   
   
</div>
<!--</div>-->
<div class="modal-footer">
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?>
    <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
$(document).ready(function() {

$("#edit_indv_pro_crm").submit(function(e) {
e.preventDefault();
var obj = $(this),
    action = obj.attr('name');
$('.save').prop('disabled', true);
$('.icon-spinner3').show();
$.ajax({
    type: "POST",
    url: base_url + "/indv_project_update",
    data: obj.serialize() + "&is_ajax=1&edit_type=edit_indv_pro_crm&form=" + action,
    cache: false,
    success: function(JSON) {
        if (JSON.error != '') {
            toastr.error(JSON.error);
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            $('.save').prop('disabled', false);
            $('.icon-spinner3').hide();
        } else {
            var usrid = $('#userid').val();
            var crm_table_individual_proj = $('#crm_table_individual_proj').dataTable({
                
                "bDestroy": true,
                "ajax": {
                    url: base_url + '/project_list_crm_indv/' + usrid,
                    type: 'GET'
                },
                /*dom: 'lBfrtip',
                "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
                "fnDrawCallback": function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
            crm_table_individual_proj.api().ajax.reload(function() {
                toastr.success(JSON.result);
            }, true);
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            $('.icon-spinner3').hide();
            $('.edit-modal-data').modal('toggle');
            $('.save').prop('disabled', false);

        }
    }
});
});

});
</script>

<?php }?>