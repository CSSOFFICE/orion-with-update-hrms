<?php
/* Purchase view
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('3002', $role_resources_ids)) { ?>

    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>
                    <?php echo $this->lang->line('xin_quotation'); ?></h3>
                <!-- <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php //echo $this->lang->line('xin_add_new');
                        ?></button>
                </a> </div> -->
            </div>
            <div id="add_form1" class=" add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_quotation', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('user_id' => $session['user_id']); ?>
                    <?php echo form_open_multipart('admin/quotation/add_quotation', $attributes, $hidden); ?>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="project_id"><?php echo $this->lang->line('xin_project'); ?>
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select name="project_id" id="project_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_project'); ?>">
                                        <option value="">Select Project</option>
                                        <?php foreach ($all_projects as $project) { ?>
                                            <option value="<?php echo $project->project_id; ?>">
                                                <?php echo $project->project_title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="supplier_id"><?php echo $this->lang->line('xin_supplier'); ?>
                                                <i class="hrsale-asterisk">*</i>
                                            </label>
                                            <select class="form-control" name="supplier_id" id="supplier_id" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_supplier'); ?>">
                                                <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                                <?php foreach ($all_suppliers as $supplier) { ?>
                                                    <option value="<?php echo $supplier->supplier_id; ?>">
                                                        <?php echo $supplier->supplier_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-md-12" id="supplier_address"></div>
                                        <div class="col-md-6">
                                            <label for="attn_name">ATTN</label>
                                            <input type="text" name="attn_name" id="attn_name" class="form-control" placeholder="Attn">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="attn_email">Email</label>
                                            <input type="email" name="attn_email" id="attn_email" class="form-control" placeholder="Email">
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="note"><?php echo $this->lang->line('xin_quotation_terms_condition'); ?></label>
                                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_quotation_terms_condition'); ?>" name="terms_condition"></textarea>
                                </div>

                            </div>

                            <div class="col-md-12">

                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">First Panel</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Second Panel</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Third Panel</a>
                                    </li>
                                </ul><!-- Tab panes -->
                                <div class="tab-content" style="border: 1px solid; border-top: none; border-color: #dee2e6;">
                                    <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                        <div class="container-fluid">
                                            <p>First Panel</p>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tabs-2" role="tabpanel">
                                        <div class="container-fluid">
                                            <p>Second Panel</p>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tabs-3" role="tabpanel">
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
                                <tbody class="AddItem" id="vendor_items_table1"></tbody>
                                <tfoot>
                                    <tr>
                                        <th style="border: none !important;">
                                            <a href="javascript:void(0)" class="btn-sm btn-success"
                                                id="addButton1">Add</a>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                    </div>
                    <div class="form-actions box-footer">
                        <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                            <?php echo $this->lang->line('xin_save'); ?> </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    $(document).ready(function(){
        $('#addButton1').on('click', function() {
            var number = $('.AddItem tr').length;
            var item = number + 1;
            $('.AddItem').append(`
                    <tr>
                    <td style="min-width:100px">
                            <label>` + item + `<label>
                        </td>
                        <td style="min-width:250px">
                            <textarea name="description[]" id="description${++$('.AddItem tr').length}" placeholder="Description" style="width:250px"></textarea>
                        </td>
                        <td style="min-width:100px">
                            <input type="number" min="0" id="quantity${++$('.AddItem tr').length}"  class="calculate" name="quantity[]" placeholder="Quantity" onkeyup="calculation(${++$('.AddItem tr').length})">
                        </td>
                        <td style="min-width:100px">
                             <select class="packing_dropdown form-control select22" name="packing_id[]">
                                <option value="">Select Packing</option>
                               
                            </select>
                        </td>
                        
                        <td style="min-width:100px">
                            <input type="text" name="cost_price[]" id="cost_field${++$('.AddItem tr').length}"  placeholder="Sales Price" class="calculate " onkeyup="calculation(${++$('.AddItem tr').length})">
                        </td>
                       
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

        });

    });

</script>