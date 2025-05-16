<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('207',$role_resources_ids)) {?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>

<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_add_new_warehouse');?>
                <?php echo $this->lang->line('xin_warehouse');?></h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new_warehouse');?></button>
                </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
            <div class="box-body">
                <?php $attributes = array('name' => 'add_warehouse', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('_user' => $session['user_id']);?>
                <?php echo form_open('admin/warehouse/add_warehouse', $attributes, $hidden);?>
                <div class="bg-white">
                    <div class="box-block">
                        <div class="row">
                            <div class="col-md-12">                                                            
                                
                                <div class="row">
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="supplier_name"><?php echo $this->lang->line('xin_warehouse_name');?>
                                                <i class="hrsale-asterisk">*</i></label>
                                            </label>
                                            <input class="form-control"
                                                placeholder="<?php echo $this->lang->line('xin_warehouse_name');?>" 
                                                name="w_name" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sku"><?php echo $this->lang->line('xin_warehouse_address');?>
                                                <i class="hrsale-asterisk">*</i></label>
                                            </label>
                                            <textarea class="form-control textarea"
                                                placeholder="<?php echo $this->lang->line('xin_warehouse_address');?>" 
                                                name="w_address" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sku"><?php echo "Organization";?>
                                                <i class="hrsale-asterisk">*</i></label>
                                            </label>
                                            <select class="form-control" name="org_id" type="text">
                                                <option>Select</option>
                                                <?php foreach($get_org as $org){?>
                                                    <option value="<?php echo $org['company_id']?>"><?php echo $org['name']?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        </div>
                                    </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('xin_warehouse_pcode');?>
                                            <i class="hrsale-asterisk">*</i>
                                        </label>
                                            <input class="form-control"
                                                placeholder="<?php echo $this->lang->line('xin_warehouse_pcode');?>" 
                                                name="w_postal_code" type="text" value="">
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('xin_warehouse_uno');?>
                                            <i class="hrsale-asterisk">*</i></label>
                                            <input class="form-control"
                                                placeholder="<?php echo $this->lang->line('xin_warehouse_uno');?>" 
                                                name="w_uno" type="text" value="">
                                        </div>
                                    </div>                                    
                                  
                                </div>
                                
                            </div>
                           
                        </div>
                       
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label
                                        for="award_information"><?php echo $this->lang->line('xin_award_info');?></label>
                                    <textarea class="form-control"
                                        placeholder="<?php echo $this->lang->line('xin_award_info');?>"
                                        name="award_information" cols="30" rows="3" id="award_information"></textarea>
                                </div>
                            </div>
                        </div> -->
                       
                        <div class="form-actions box-footer">
                            <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_save'))); ?>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
    <div class="box-header with-border">
    <h3 class="box-title"><?php echo $this->lang->line('xin_list_warehouse');?>
                <?php echo $this->lang->line('xin_warehouse');?></h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th style="width:120px;"><?php echo $this->lang->line('xin_action');?></th>
                        <th width="350"><i class="fa fa-home"></i> <?php echo "Warehouse Name";?>
                        </th>
                        <th><i class="fa fa-location"></i> <?php echo "Address";?></th>                        
                        <th><i class="fa fa-location"></i> <?php echo "Organization";?></th>                        
                        <th><i class="fa fa-gift"></i> <?php echo "Postal Code";?></th>
                        <th><i class="fa fa-gift"></i> <?php echo "Unit Number";?></th>
                       
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<style type="text/css">
.hide-calendar .ui-datepicker-calendar {
    display: none !important;
}

.hide-calendar .ui-priority-secondary {
    display: none !important;
}
</style>