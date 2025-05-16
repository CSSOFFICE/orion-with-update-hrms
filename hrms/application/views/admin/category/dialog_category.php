<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['category_id']) && $_GET['data']=='category'){
?>
<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>

<div class="modal-header">
    <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
    <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_category');?></h4>
</div>
<?php $attributes = array('name' => 'edit_category', 'id' => 'edit_category', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $category_id, 'ext_name' => $category_id);?>
<?php echo form_open_multipart('admin/category/update/'.$category_id, $attributes, $hidden);?>
<div class="modal-body">
<div class="row">
    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                            <div class="col-md-12">
                              
                                <div class="row">
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="supplier_name"><?php echo $this->lang->line('xin_category_name');?>
                                                <i class="hrsale-asterisk">*</i></label>
                                            </label>
                                            <input class="form-control"
                                                placeholder="<?php echo $this->lang->line('xin_category_name');?>" 
                                                name="category_name" type="text" value="<?php echo $category_name;?>">
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

    $("#edit_category").submit(function(e) {
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
            var fd=new FormData(this);
            fd.append('is_ajax',1);
            fd.append('edit_type','category');
            fd.append('form',action);
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/update",
            // data: obj.serialize() + "&is_ajax=1&add_type=product&form=" + action,
            data:fd,                       
            contentType: false,
            cache: false,
            processData:false,
            success: function(JSON) {
        if (JSON.error != '') {
            toastr.error(JSON.error);
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            $('.save').prop('disabled', false);
            $('.icon-spinner3').hide();
        } else {

            var xin_table = $('#xin_table').dataTable({
                        "bDestroy": true,
                        "ajax": {
                            url: "<?php echo site_url("admin/category/category_list") ?>",
                            type: 'GET'
                        },
                        // dom: 'lBfrtip',
                        // "buttons": ['csv', 'excel', 'pdf',
                        // 'print'], // colvis > if needed
                        "fnDrawCallback": function(settings) {
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    });
                    xin_table.api().ajax.reload(function() {
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
<?php } else if(isset($_GET['jd']) && isset($_GET['award_id']) && $_GET['data']=='view_award'){
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_view_award');?></h4>
</div>
<form class="m-b-1">
    <div class="modal-body">
        <table class="footable-details table table-striped table-hover toggle-circle">
            <tbody>
                <tr>
                    <th><?php echo $this->lang->line('module_company_title');?></th>
                    <td style="display: table-cell;"><?php foreach($get_all_companies as $company) {?>
                        <?php if($company_id==$company->company_id):?>
                        <?php echo $company->name;?>
                        <?php endif;?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('dashboard_single_employee');?></th>
                    <td style="display: table-cell;"><?php foreach($all_employees as $employee) {?>
                        <?php if($employee_id==$employee->user_id):?>
                        <?php echo $employee->first_name.' '.$employee->last_name;?>
                        <?php endif;?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_award_type');?></th>
                    <td style="display: table-cell;"><?php foreach($all_award_types as $award_type) {?>
                        <?php if($award_type_id==$award_type->award_type_id):?>
                        <?php echo $award_type->award_type;?>
                        <?php endif;?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_e_details_date');?></th>
                    <td style="display: table-cell;"><?php echo $this->Xin_model->set_date_format($created_at);?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_award_month_year');?></th>
                    <td style="display: table-cell;"><?php echo $award_month_year;?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_gift');?></th>
                    <td style="display: table-cell;"><?php echo $gift_item;?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_cash');?></th>
                    <td style="display: table-cell;"><?php echo $this->Xin_model->currency_sign($cash_price);?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_award_photo');?></th>
                    <td style="display: table-cell;"><?php if($award_photo!='' && $award_photo!='no file') {?>
                        <img src="<?php echo base_url().'uploads/award/'.$award_photo;?>" width="70px"
                            id="u_file">&nbsp; <a
                            href="<?php echo site_url()?>admin/download?type=award&filename=<?php echo $award_photo;?>"><?php echo $this->lang->line('xin_download');?></a>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_award_info');?></th>
                    <td style="display: table-cell;"><?php echo html_entity_decode($award_information);?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('xin_description');?></th>
                    <td style="display: table-cell;"><?php echo html_entity_decode($description);?></td>
                </tr>
                <?php $count_module_attributes = $this->Custom_fields_model->count_awards_module_attributes();?>
                <?php $module_attributes = $this->Custom_fields_model->awards_hrsale_module_attributes();?>
                <?php foreach($module_attributes as $mattribute):?>
                <?php $attribute_info = $this->Custom_fields_model->get_employee_custom_data($award_id,$mattribute->custom_field_id);?>
                <?php
            if(!is_null($attribute_info)){
                $attr_val = $attribute_info->attribute_value;
            } else {
                $attr_val = '';
            }
        ?>
                <?php if($mattribute->attribute_type == 'date'){?>
                <tr>
                    <th><?php echo $mattribute->attribute_label;?></th>
                    <td style="display: table-cell;"><?php echo $attr_val;?></td>
                </tr>
                <?php } else if($mattribute->attribute_type == 'select'){?>
                <?php $iselc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                <tr>
                    <th><?php echo $mattribute->attribute_label;?></th>
                    <td style="display: table-cell;"><?php foreach($iselc_val as $selc_val) {?>
                        <?php if($attr_val==$selc_val->attributes_select_value_id):?>
                        <?php echo $selc_val->select_label?> <?php endif;?><?php } ?></td>
                </tr>
                <?php } else if($mattribute->attribute_type == 'multiselect'){?>
                <?php $multiselect_values = explode(',',$attr_val);?>
                <?php $imulti_selc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                <tr>
                    <th><?php echo $mattribute->attribute_label;?></th>
                    <td style="display: table-cell;"><?php foreach($imulti_selc_val as $multi_selc_val) {?>
                        <?php if(in_array($multi_selc_val->attributes_select_value_id,$multiselect_values)):?><br />
                        <?php echo $multi_selc_val->select_label?> <?php endif;?><?php } ?></td>
                </tr>
                <?php } else if($mattribute->attribute_type == 'textarea'){?>
                <tr>
                    <th><?php echo $mattribute->attribute_label;?></th>
                    <td style="display: table-cell;"><?php echo $attr_val;?></td>
                </tr>
                <?php } else if($mattribute->attribute_type == 'fileupload'){?>
                <tr>
                    <th><?php echo $mattribute->attribute_label;?></th>
                    <td style="display: table-cell;"><?php if($attr_val!='' && $attr_val!='no file') {?>
                        <img src="<?php echo base_url().'uploads/custom_files/'.$attr_val;?>" width="70px"
                            id="u_file">&nbsp; <a
                            href="<?php echo site_url('admin/download');?>?type=custom_files&filename=<?php echo $attr_val;?>"><?php echo $this->lang->line('xin_download');?></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } else { ?>
                <tr>
                    <th><?php echo $mattribute->attribute_label;?></th>
                    <td style="display: table-cell;"><?php echo $attr_val;?></td>
                </tr>
                <?php } ?>

                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
            data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    </div>
    <?php echo form_close(); ?>
    <?php }
?>