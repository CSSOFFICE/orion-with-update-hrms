<?php

/* Purchase view
*/
?>
<style>
    #accordion{
        height:100%;
    }
</style>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('3003',$role_resources_ids)) {?>

<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $this->lang->line('xin_edit_quotation');?>
                <?php echo $this->lang->line('xin_quotation');?></h3>
            <div class="box-tools pull-right"> 
                <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                    <?php if( $result[0]->status !="confirmed"){ ?>
                    <button type="button" class="btn btn-xs btn-primary" id="btn_confirm" data-quotation_id="<?php echo $this->uri->segment(4);?>"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_confirm_del');?></button>
                        <?php } ?>
                </a> </div>
        </div>
        <div id="add_form1" class="add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
            <div class="box-body">
                <?php $attributes = array('name' => 'update_quotation', 'id' => 'xin-form1', 'autocomplete' => 'off');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open_multipart('admin/finance/update_quotation', $attributes, $hidden);?>
                <div class="form-body">
                    <input type="hidden" name="quotation_id" value="<?php echo $this->uri->segment(4);?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="project_id"><?php echo $this->lang->line('xin_project');?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <input type="text" name="project_id" class="form-control" placeholder="Project Name" value="<?php echo $result[0]->project_name; ?>" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>>
                                
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="customer_id"><?php echo $this->lang->line('xin_customer');?>
                                            <i class="hrsale-asterisk">*</i>
                                        </label>
                                        <select class="form-control" name="customer_id" id="customer_id"
                                            data-plugin="xin_select"
                                            data-placeholder="<?php echo $this->lang->line('xin_customer');?>">
                                            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                            <?php foreach($get_all_customer as $customer) {?>
                                            <option value="<?php echo $customer->client_id;?>" <?php echo(($customer->client_id == $result[0]->customer_id)?"selected":"");?>>
                                                <?php echo $customer->f_name;?>
                                            </option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                    <div class="col-md-12" id="supplier_address">
                                        <label for="xin_department_head"><?php echo $this->lang->line('xin_customer_address');?></label>
                                        <textarea class="form_control col-md-12" name="supplier_name"
                                            id="supplier_name" rows="8" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>><?php echo $result[0]->f_name."\n". $result[0]->address." ".$result[0]->client_phone;?></textarea>
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <label for="pic_name">PIC Name</label>
                                        <input type="text" name="pic_name" id="pic_name" class="form-control"
                                            placeholder="Attn" value="<?php echo $result[0]->pic_name; ?>" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pic_email">PIC Email</label>
                                        <input type="email" name="pic_email" id="pic_email" class="form-control"
                                            placeholder="Email" value="<?php echo $result[0]->pic_email; ?>" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pic_phone">PIC Phone</label>
                                        <input type="email" name="pic_phone" id="pic_phone" class="form-control"
                                            placeholder="Email" value="<?php echo $result[0]->pic_phone; ?>" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label
                                    for="note"><?php echo $this->lang->line('xin_quotation_terms_condition');?></label>
                                <textarea class="form-control"
                                    placeholder="<?php echo $this->lang->line('xin_quotation_terms_condition');?>"
                                    name="terms_condition" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>><?php echo $result[0]->term_condition_description; ?></textarea>
                            </div>
                            <div class="form-group">

                                <a href="javascript:void(0)" class="btn-sm btn-success task">Add New Task</a>

                            </div>
                            <div id="task_div">
                            <div style="margin-top:10px;"><div class="row">
                                <?php $i=1; foreach($get_all_task as $tasks){ ?>
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-12">
                                                   <label>Task <?php echo $i; ?></label>
                                                    <input type="text" name="task_name[]" class="form-control"
                                                        placeholder="Task Name" value="<?php echo $tasks->task; ?>" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>>
                                                </div>

                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-12">

                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tabs-1-<?php echo $i;?>"
                                                    role="tab">Task Description</a>
                                            </li>
                                           
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tabs-2-<?php echo $i;?>" role="tab">Sub Tasks</a>
                                            </li>
                                        </ul><!-- Tab panes -->
                                        <div class="tab-content"
                                            style="border: 1px solid; border-top: none; border-color: #dee2e6;">
                                            <div class="tab-pane active" id="tabs-1-<?php echo $i;?>" role="tabpanel">
                                                <div class="container-fluid">
                                                <textarea name="task_description[]" id="task_description<?php echo $i;?>" placeholder="Detail" style="width:250px" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>><?php echo $tasks->task_description; ?></textarea>
                                                </div>

                                            </div>
                                           
                                            <div class="tab-pane" id="tabs-2-<?php echo $i;?>" role="tabpanel">
                                                <div class="container-fluid">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Description</th>
                                                                <th>Detail</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="AddItem" id="vendor_items_table<?php echo $i;?>">
                                                        <?php $j=1;foreach($get_all_subtasks as $subtasks){ ?>
                                                        <tr>
                                                        <td style="min-width:100px">
                                                                <label><?php echo $j;?><label>
                                                            </td>
                                                            <td style="min-width:250px">
                                                                <textarea name="description[]" id="description<?php echo $j;?>" placeholder="Description" style="width:250px" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>><?php echo $subtasks->description; ?></textarea>
                                                            </td>
                                                            <td style="min-width:100px">
                                                            <textarea name="detail[]" id="detail<?php echo $j;?>" placeholder="Detail" style="width:250px" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>><?php echo $subtasks->detail; ?></textarea>
                                                            </td>
                                                            <td style="min-width:100px">
                                                                <select class="packing_dropdown form-control select22" name="unit_id[]">
                                                                <option value="">Select Unit</option>
                                                                <?php foreach($all_units->result() as $unit){?>
                                                                    <option value="<?php echo $unit->unit_id;?>"<?php echo (($subtasks->unit_id == $unit->unit_id)?'selected':''); ?>><?php echo $unit->unit;?></option>
                                                                    <?php } ?>
                                                                
                                                                </select>
                                                            </td>
                                                            
                                                            <td style="min-width:100px">
                                                                <input type="text" name="unit_rate[]" id="unit_rate<?php echo $j;?>"  placeholder="Unit Rate" class="form-control calculate" value="<?php echo $subtasks->unit_rate; ?>" <?php echo (( $result[0]->status =="confirmed"?"readonly":"")); ?>>
                                                            </td>
                                                        
                                                            <td>
                                                                <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                        <?php $j++; } ?>
                                                    </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="border: none !important;">
                                                                    <a href="javascript:void(0)"
                                                                        class="btn-sm btn-success addButton1" onclick="sub_tasks('<?php echo $i;?>')">Add</a>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div></div>
                                <?php $i++;  } ?>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="form-actions box-footer">
                <?php if( $result[0]->status !="confirmed"){ ?>
                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                        <?php echo $this->lang->line('xin_save');?> </button>
                        <?php } ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script>
$(document).ready(function() {
    var counter=<?php echo count($get_all_task); ?>;
   // $("#task_div").hide();
    var input = $('.timepicker_m').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $(".task").on('click', function() {
        counter +=1;
        //$("#task_div").show();
        $("#task_div").append(`<div style="margin-top:10px;"><div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-12">
                                                   <label>Task`+counter+`</label>
                                                    <input type="text" name="task_name[]" class="form-control"
                                                        placeholder="Task Name">
                                                </div>

                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-12">

                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tabs-1-`+counter+`"
                                                    role="tab">Task Description</a>
                                            </li>
                                           
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tabs-2-`+counter+`" role="tab">Sub Tasks</a>
                                            </li>
                                        </ul><!-- Tab panes -->
                                        <div class="tab-content"
                                            style="border: 1px solid; border-top: none; border-color: #dee2e6;">
                                            <div class="tab-pane active" id="tabs-1-`+counter+`" role="tabpanel">
                                                <div class="container-fluid">
                                                <textarea name="task_description[]" id="task_description`+counter+`" placeholder="Detail" style="width:250px"></textarea>
                                                </div>

                                            </div>
                                           
                                            <div class="tab-pane" id="tabs-2-`+counter+`" role="tabpanel">
                                                <div class="container-fluid">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Description</th>
                                                                <th>Detail</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="AddItem" id="vendor_items_table`+counter+`"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="border: none !important;">
                                                                    <a href="javascript:void(0)"
                                                                        class="btn-sm btn-success addButton1" onclick="sub_tasks('`+counter+`')">Add</a>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div></div>`);
    });
    
   

});
$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();

    updateCalculationPQ();
});
function sub_tasks(id){
    var number = $('#vendor_items_table'+id+' tr').length;
        var item = number + 1;
        $('#vendor_items_table'+id).append(`
                    <tr>
                    <td style="min-width:100px">
                            <label>` + item + `<label>
                        </td>
                        <td style="min-width:250px">
                            <textarea name="description[]" id="description`+id+`" placeholder="Description" style="width:250px"></textarea>
                        </td>
                        <td style="min-width:100px">
                        <textarea name="detail[]" id="detail`+id+`" placeholder="Detail" style="width:250px"></textarea>
                        </td>
                        <td style="min-width:100px">
                             <select class="packing_dropdown form-control select22" name="unit_id[]">
                             <option value="">Select Unit</option>
                             <?php foreach($all_units->result() as $unit){?>
                                <option value="<?php echo $unit->unit_id;?>"><?php echo $unit->unit;?></option>
                                <?php } ?>
                               
                            </select>
                        </td>
                        
                        <td style="min-width:100px">
                            <input type="text" name="unit_rate[]" id="unit_rate`+id+`"  placeholder="Unit Rate" class="form-control calculate ">
                        </td>
                       
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);
    }



jQuery("#customer_id").change(function() {

    jQuery.get(base_url + "/get_supplier_address/" + jQuery(this).val(), function(data, status) {
        jQuery('#supplier_address').html(data);
    });
});
jQuery('#btn_confirm').click(function(){
    var id=$(this).data('quotation_id');
   
    $.ajax({
        type:"POST",
        url:"<?php echo base_url().'admin/finance/get_details/';?>"+id, 
        success:function(data){
            toastr.success("Quotation has been confirmed");
            setInterval(function () {   
            window.location.href="<?php echo base_url().'admin/finance/quotation_list/';?>",1000
            });
        },
        error:function(){
            toastr.error("Quotation Not confirmed");
        }
    });
});
</script>