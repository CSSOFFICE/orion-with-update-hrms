<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['crm_id']) && $_GET['data']=='crm'){

?>

<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>

<div class="modal-header">
    <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
    <h4 class="modal-title" id="edit-com-modal-data"><?php echo "Edit Company Project Data";?></h4>
</div>
<?php $attributes = array('name' => 'edit_com_pro_crm', 'id' => 'edit_com_pro_crm', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $crm_com_proj_id, 'ext_name' => $crm_com_proj_id);?>
<?php echo form_open('admin/crm/com_project_update/'.$crm_com_proj_id, $attributes, $hidden);?>
<div class="modal-body">
<div class="row">
                    <input type="hidden" name="crm_com_proj_id" value="<?php echo $crm_com_proj_id; ?>">
                    <input type="hidden" name="crm_com_proj_for" id="userid" value="<?php echo $crm_com_proj_for; ?>">
                            <div class="col-md-12">                                                                                               
                            <div class="row">                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Title<i class="hrsale-asterisk">*</i></label>
                                            <input type="text" name="crm_com_proj_title" id="crm_com_proj_title" class="form-control"
                                            placeholder="Project Title" value="<?php echo $crm_com_proj_title;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Start Date<i class="hrsale-asterisk">*</i></label>
                                            <input type="date" name="crm_com_proj_start" id="crm_com_proj_start" class="form-control"
                                            placeholder="Contact Number" value="<?php echo $crm_com_proj_start;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Deadline</label>
                                            <input type="date" name="crm_com_proj_stop" id="crm_com_proj_stop" class="form-control"
                                            placeholder="Customer Email" value="<?php echo $crm_com_proj_stop;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Project Desctiption<i class="hrsale-asterisk">*</i></label>
                                            <input type="text" name="crm_com_proj_des" id="crm_com_proj_des" class="form-control"
                                            placeholder="Project Description" value="<?php echo $crm_com_proj_des;?>">
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

$("#edit_com_pro_crm").submit(function(e) {
e.preventDefault();
var obj = $(this),
    action = obj.attr('name');
$('.save').prop('disabled', true);
$('.icon-spinner3').show();
$.ajax({
    type: "POST",
    url: base_url + "/com_project_update",
    data: obj.serialize() + "&is_ajax=1&edit_type=edit_com_pro_crm&form=" + action,
    cache: false,
    success: function(JSON) {
        if (JSON.error != '') {
            toastr.error(JSON.error);
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            $('.save').prop('disabled', false);
            $('.icon-spinner3').hide();
        } else {
            var usrid = $('#userid').val();
            var crm_table_com_proj = $('#crm_table_com_proj').dataTable({
                
                "bDestroy": true,
                "ajax": {
                    url: base_url + '/project_list_crm_com/' + usrid,
                    type: 'GET'
                },
                /*dom: 'lBfrtip',
                "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
                "fnDrawCallback": function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
            crm_table_com_proj.api().ajax.reload(function() {
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