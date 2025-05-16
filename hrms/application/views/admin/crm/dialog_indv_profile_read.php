<style>
    #add_form {
        height: 100% !important;
    }
    #add_quot_form{
        height: 100% !important;
    }

    .profile-pic-wrapper {

  top: 3.5rem;
}
.profile-list{
    list-style-type: none;
    margin:0;
    padding:0;
}
</style>
<input type="hidden" id="userid" value="<?php echo $crm_id; ?>">
<div class="row">
    <div class="col-xl-3 d-none d-xl-block">
                <!-- Column -->
    <div class="card">
        <!--has logo-->
            <!--no logo -->
        <div class="card-body profile_header client logo-text">
            <!-- ABC Pte Ltd -->
        </div>
            <div class="card-body p-t-0 p-b-0">
                    <div>
                <small class="text-muted">Customer Name</small>
                <h6><?php echo $customer_name; ?></h6>
                <small class="text-muted"><i class="fa fa-phone" aria-hidden="true"></i> Phone</small>
                <h6><?php echo $contact_number; ?></h6>
                <small class="text-muted"><i class="fa fa-envelope-o" aria-hidden="true"></i> Email</small>
                <h6><?php echo $email; ?></h6>
            
                <!-- <small class="text-muted">Account Owner</small>
                <div class="m-b-10"><img src="https://localhost/zyhconstruction/storage/avatars/system/default_avatar.jpg" alt="user" class="img-circle avatar-xsmall"> Test test</div>
                <small class="text-muted">Account Status</small>
                <div>
                                    <span class="badge badge-pill badge-success">Active</span>
                                </div> -->
            </div>
                </div>
        <div>
            <hr> </div>
        <div class="card-body p-t-0 p-b-0">
            <div>
                <table class="table no-border m-b-0">
                    <tbody>                                                         
                        <tr>
                            <td class="p-l-0 p-t-5"><i class="fa fa-usd" aria-hidden="true"></i> Credit Limit</td>
                            <td class="font-medium p-r-0 p-t-5">s$ <?php echo $credit_limit; ?>
                                <div class="progress">
                                    <div class="progress-bar bg-success w-100 h-px-3" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="<?php echo $credit_limit; ?>"></div>
                                </div>
                            </td>
                        </tr>                                       
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <hr> </div>
            <!--client address-->
        <div class="card-body p-t-0 p-b-0">
                    <small class="text-muted">
                        <i class="fa fa-home" aria-hidden="true"></i> 
                        Address</small>
                        <h6><?php echo $cust_address; ?></h6>  
                    
                    <small class="text-muted">
                    <i class="fa fa-location-arrow" aria-hidden="true"></i>
                        Postal Code
                    </small>
                        <h6><?php echo $postal_code; ?></h6>
                    
                    <small class="text-muted">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        Unit Number
                    </small>
                        <h6><?php echo $unit_number; ?></h6>
                </div>

        <div class="d-none last-line">
            <hr> </div>
    </div>
    <!-- Column -->        
    </div>
    
    
    
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <div class="tabs">
                        <span data-tab-value="#tab_1">Profile</span>
                        <span data-tab-value="#tab_2">Project</span>
                        <span data-tab-value="#tab_3">Quotations</span>
                        <span data-tab-value="#tab_4">Invoice</span>

                    </div>
                </h5>
                <!-- <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6> -->
                <p class="card-text">
                <div class="tab-content">
                    <div class="tabs__tab active" id="tab_1" data-tab-info>
                        <div class="box-header with-border">
                            <h3 class="box-title">Profile</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <form method="post" action="<?php echo site_url('admin/crm/tabUpdate') ?>">
                                    <input type="hidden" name="crm_id" value="<?php echo $crm_id; ?>">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Customer Name<i class="hrsale-asterisk">*</i></label>
                                                    <input type="text" name="cust_name" id="cust_name" class="form-control" placeholder="Customer Name" value="<?php echo $customer_name; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Contact Number<i class="hrsale-asterisk">*</i></label>
                                                    <input type="number" name="cust_number" id="cust_number" class="form-control" placeholder="Contact Number" value="<?php echo $contact_number; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="custmr_email" id="custmr_email" class="form-control" placeholder="Customer Email" value="<?php echo $email; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Postal Code<i class="hrsale-asterisk">*</i></label>
                                                    <input type="number" name="po_code" id="po_code" class="form-control" placeholder="Postal Code" value="<?php echo $postal_code; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea name="indv_addres" id="indv_addres" class="form-control"><?php echo $cust_address; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Unit Number<i class="hrsale-asterisk">*</i></label>
                                                    <input type="text" name="u_num" id="u_num" class="form-control" placeholder="Unit Number" value="<?php echo $unit_number; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Credit Limit</label>
                                                    <input type="number" name="c_limit" id="c_limit" class="form-control" placeholder="Credit Limit" value="<?php echo $credit_limit; ?>">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Update">
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--TAB 2 START-->

                    <div class="tabs__tab" id="tab_2" data-tab-info>

                        <div class="box-header with-border">
                            <h3 class="box-title">Project</h3>
                        </div>
                        <div class="box-body">
                            <?php $session = $this->session->userdata('username'); ?>
                            <?php $table_id = $crm_id; ?>
                            <?php $get_animate = $this->Xin_model->get_content_animate(); ?>
                            <?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
                            <?php if (in_array('207', $role_resources_ids)) { ?>
                                <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

                                <div class="box mb-4 <?php echo $get_animate; ?>">
                                    <div id="accordion">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"><?php echo "Add New Project"; ?></h3>
                                            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_proj_form" aria-expanded="false">
                                                    <button type="button" class="btn btn-sm  btn btn-danger rounded-circle"><i class="ti-plus"></i>
                                                        </button>
                                                </a> </div>
                                        </div>
                                        <div id="add_proj_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                                            <div class="box-body">
                                                <?php $attributes = array('name' => 'crm_proj', 'id' => 'crm-proj-form', 'autocomplete' => 'off'); ?>
                                                <?php $hidden = array('_user' => $session['user_id']); ?>
                                                <?php echo form_open('admin/crm/add_crm_proj', $attributes, $hidden); ?>
                                                <div class="bg-white">
                                                    <div class="box-block">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="hidden" name="proj_for" value="<?php echo $crm_id ?>">

                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label>Project Title</label>
                                                                        <input type="text" class="form-control" name="proj_title" id="proj_title" placeholder="Project Title">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Project Start Date</label>
                                                                        <input type="date" class="form-control" name="proj_s_date" id="proj_s_date" placeholder="Project Start Date">

                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label>Project Deadline</label>
                                                                        <input type="date" class="form-control" name="proj_stop" id="proj_stop" placeholder="Project Deadline">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Project Desctiption</label>
                                                                        <textarea class="form-control" name="proj_des" id="proj_des" placeholder="Project Desctiption"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>




                                                        </div>


                                                        <div class="form-actions box-footer">
                                                            <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-body">

                                    <div class="box-datatable table-responsive">
                                        <table class="datatables-demo table table-striped table-bordered" id="crm_table_individual_proj" style="width:100%!important;">
                                            <thead>
                                                <tr>
                                                    <th><?php echo "Sl No."; ?></th>
                                                    <th><?php echo "Project Title"; ?></th>
                                                    <th><?php echo "Project Start Date"; ?></th>
                                                    <th><?php echo "Project Deadline"; ?></th>
                                                    <th><?php echo "Project Desctiption"; ?></th>
                                                    <th><?php echo $this->lang->line('xin_action'); ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>


                            <?php } ?>
                        </div>

                    </div>

                    <!--TAB 2 END-->

                    <!--TAB 3 START-->
                    <div class="tabs__tab" id="tab_3" data-tab-info>

                        <div class="box-header with-border">
                            <h3 class="box-title">Quotations</h3>
                        </div>
                        <div class="box mb-4 <?php echo $get_animate; ?>">
                            <div id="accordion">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo "Add New Quotation"; ?></h3>
                                    <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_quot_form" aria-expanded="false">
                                            <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                                                <?php echo "Add New Quotation"; ?></button>
                                        </a> </div>
                                </div>
                                <div id="add_quot_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                                    <div class="box-body">
                                        <?php $attributes = array('name' => 'crm_quote', 'id' => 'crm-quot-form', 'autocomplete' => 'off'); ?>
                                        <?php $hidden = array('_user' => $session['user_id']); ?>
                                        <?php echo form_open('admin/crm/add_crm_quote', $attributes, $hidden); ?>
                                        <div class="bg-white">
                                            <div class="box-block">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="quotation_amount">Quotation Subject Title <i class="hrsale-asterisk">*</i></label>
                                                                <input type="text" name="q_title" id="q_title" class="form-control" placeholder="Quotation Subject Title">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="quotation_amount">Project Name<i class="hrsale-asterisk">*</i></label>
                                                                <input type="text" name="proj_name" id="proj_name" class="form-control" placeholder="Project Name">
                                                            </div>
                                                            <input type="hidden" name="quote_for" value="<?php echo $crm_id ?>">

                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="acceptance_letter_no">Project Site Address <i class="hrsale-asterisk">*</i></label>
                                                                    <textarea class="form-control" placeholder="Project Site" name="project_s_add"></textarea>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="quotation_amount">Payment Term<i class="hrsale-asterisk">*</i></label>
                                                                    <select class="form-control" name="pay_term" id="pay_term">
                                                                        <option value="">Select</option>
                                                                        <?php foreach ($all_payment_terms as $term) { ?>
                                                                            <option value="<?php echo $term->payment_term ?>"><?php echo $term->payment_term ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="quotation_amount">Shipping Term<i class="hrsale-asterisk">*</i></label>
                                                                    <select class="form-control" name="ship_term" id="ship_term">
                                                                        <option value="">Select</option>
                                                                        <?php foreach ($all_payment_terms as $term) { ?>
                                                                            <option value="<?php echo $term->payment_term ?>"><?php echo $term->payment_term ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">

                                                                    <label for="q_validity">Quotation Validity <i class="hrsale-asterisk">*</i></label>
                                                                    <input class="form-control date" placeholder="Select Required date" name="q_validity" id="q_validity" type="text">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="note"><?php echo $this->lang->line('xin_quotation_terms_condition'); ?></label>
                                                                    <select name="term_condition_id" id="term_condition_id" class="form-control">
                                                                        <option>Select Term Condition</option>
                                                                        <?php foreach ($get_term_condition as $term_condition) { ?>
                                                                            <option value="<?php echo $term_condition->term_id; ?>"><?php echo $term_condition->term_title; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <label for="note"></label>
                                                                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_quotation_terms_condition'); ?>" name="terms_condition" id="terms_condition"></textarea>

                                                                    <label for="remark">Remarks</label>
                                                                    <textarea class="form-control" name="remark" id="remark"></textarea>
                                                                </div>
                                                                <div class="form-group">

                                                                    <div class="col-md-12">
                                                                        <label for="pic_name">Person in charge <i class="hrsale-asterisk">*</i></label>
                                                                        <input type="text" name="pic_name" id="pic_name" class="form-control" placeholder="Person in charge">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="pic_email">PIC Email</label>
                                                                        <input type="email" name="pic_email" id="pic_email" class="form-control" placeholder="PIC Email">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="pic_phone">PIC Phone</label>
                                                                        <input type="number" name="pic_phone" id="pic_phone" class="form-control number" placeholder="PIC Phone">
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>
                                                        <div class="form-group">
                                                            <a href="javascript:void(0)" class="btn-sm btn-success task">Add New Task</a>
                                                        </div>
                                                        <div id="task_div">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-actions box-footer">
                                                    <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-datatable table-responsive">
                                <table class="datatables-demo table table-striped table-bordered" id="crm_table_individual_quote" style="width:100%!important;">
                                    <thead>
                                        <tr>
                                            <th><?php echo "Sl No";?></th>                                             
                                            <th><?php echo "Quotation No";?></th>
                                            <th><?php echo "Quotation Title";?></th>
                                            <th><?php echo "Project Name";?></th>                                                                        
                                            <th><?php echo "Person In Charge";?></th>       
                                            <th><?php echo "Quotation Amount";?></th>
                                            <th><?php echo "Quotation Valid Upto";?></th>                        
                                            <th><?php echo "Status";?></th>                                                        
                                            <th><?php echo $this->lang->line('xin_action');?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!--TAB 3 END-->

                    <!--TAB 4 START-->
                    <div class="tabs__tab" id="tab_4" data-tab-info>

                        <div class="box-header with-border">
                            <h4 class="box-title">Invoice</h4>
                        </div>
                        <div class="box-body">
                            <div class="box-datatable table-responsive">
                                <table class="datatables-demo table table-striped table-bordered" id="crm_table_individual_invoice" style="width:100%!important;">
                                    <thead>
                                        <tr>
                                            <th><?php echo "Sl No."; ?></th>
                                            <th><?php echo $this->lang->line('xin_action'); ?></th>
                                            <th><?php echo "Project Name"; ?></th>
                                            <th><?php echo "Name"; ?></th>
                                            <th><?php echo "Created Date"; ?></th>

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!--TAB 4 END-->



                </div>
                </p>

            </div>
        </div>
    </div>
</div>



</div>




<style>
    [data-tab-info] {
        display: none;
    }

    .active[data-tab-info] {
        display: block;

    }

    .tab-content {
        margin-top: 1rem;
        padding-left: 1rem;
        font-size: 14px;
        font-family: sans-serif;
        font-weight: bold;
        color: rgb(0, 0, 0);
    }

    .tabs {
        /* border-bottom: 1px solid grey; */
        background-color: solid #d2d6de !important;
        font-size: 14px;
        color: rgb(0, 0, 0);
        display: flex;
        margin: 0;
    }

    .tabs span {
        background: #f8f9fa;
        padding: 10px;
        border: 1px solid rgb(255, 255, 255);
    }

    .tabs span:hover {
        background: rgb(55, 219, 46);
        cursor: pointer;
        color: black;
    }
</style>
<script type="text/javascript">
    // function to get each tab details
    const tabs = document.querySelectorAll('[data-tab-value]')
    const tabInfos = document.querySelectorAll('[data-tab-info]')

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = document
                .querySelector(tab.dataset.tabValue);
            tabInfos.forEach(tabInfo => {
                tabInfo.classList.remove('active')
            })
            target.classList.add('active');
        })
    })

    $("#term_condition_id").on('change', function() {
        var id = $(this).val();
        $.ajax({
            type: 'GET',
            url: base_url + "/get_term_details/" + id,
            data: JSON,

            success: function(JSON) {
                var data = jQuery.parseJSON(JSON);
                $("#terms_condition").val(data[0].term_description)
            },
            error: function() {
                toastr.error("Something went wrong");
            }

        });
    });

    // this is add task code

    var counter = 0;
    $("#task_div").hide();

    $(".task").on('click', function() {
        counter += 1;
        $("#task_div").show();
        $("#task_div").append(`<div style="margin-top:10px;"><div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-12">
                                                   <label>Task` + counter + `</label>
                                                    <input type="text" name="task_name[]" class="form-control"
                                                        placeholder="Task Name">
                                                </div>

                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-12">

                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tabs-1-` + counter + `"
                                                    role="tab">Task Description</a>
                                            </li>
                                           
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tabs-2-` + counter + `" role="tab">Sub Tasks</a>
                                            </li>
                                        </ul><!-- Tab panes -->
                                        <div class="tab-content"
                                            style="border: 1px solid; border-top: none; border-color: #dee2e6;">
                                            <div class="tab-pane active" id="tabs-1-` + counter + `" role="tabpanel">
                                                <div class="container-fluid">
                                                <textarea name="task_description[]" id="task_description` + counter + `" placeholder="Detail" style="width:250px"></textarea>
                                                </div>

                                            </div>
                                           
                                            <div class="tab-pane" id="tabs-2-` + counter + `" role="tabpanel">
                                                <div class="container-fluid">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl</th>
                                                                <th>Product</th>
                                                                <th>Description</th>
                                                                <th>Detail</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Qtn</th>
                                                                <th>Gross Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="AddItem" id="vendor_items_table` + counter + `"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="border: none !important;">
                                                                    <a href="javascript:void(0)"
                                                                        class="btn-sm btn-success addButton1" onclick="sub_tasks('` + counter + `')">Add</a>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <div class="col-md-6">
                                                    
                                                        <div class="form-group row" style="margin-bottom: 0;">
                                                            <div class="col-12">
                                                                <label for="sub_total" class="form-label">Sub Total<span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" readonly type="text" name="sub_total" id="sub_total"
                                                                    placeholder="0">
                                                            </div>
                                                        </div>



                                                        <div class="form-group">
                            <input type="checkbox" class="" id="is_gst" name="is_gst" value="1">
                            <label for="is_gst">GST Inclusive</label>
                            </div>
                            <div class="form-group"  id="gst_div">
                                <label for="total_gst" class="form-label">GST</label>
                                <select class="form-control select22" id="total_gst" name="gst" onchange="totalGSTAmount()" readonly>
                                            <option value="">Select GST</option>
                                        <?php foreach($get_gst as $gst){ ?>
                                                    <option value="<?php echo $gst->gst; ?>"><?php echo $gst->gst;?></option>
                                                <?php } ?>
                                            </select>
                                <!-- <input class="form-control" type="text" value="" id="u_total_gst1" name="u_total_gst1"
                                    placeholder="0" value="<?php //echo (($gst >0)?$gst:0) ;?>" onkeyup="totalGSTAmount()"> -->
                            </div>



                                                        

                                                        <hr>

                                                        <div class="form-group row" style="margin-bottom: 0;">
                                                            <div class="col-12">
                                                                <label for="total_amount1" class="form-label">Total Amount<span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" readonly type="text" name="total_amount" value=""
                                                                    id="total_amount1" placeholder="0">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div></div>`);
    });

    function sub_tasks(id) {
        var number = $('#vendor_items_table' + id + ' tr').length;
        var item = number + 1;
        $('#vendor_items_table' + id).append(`
                    <tr>
                    <td style="min-width:10px">
            <label>` + item + `<label>
            </td>
             <td style="min-width:250px">
                 <textarea name="product[]" id="product` + id + `" placeholder="Product" cols="10" rows="2"></textarea>
            </td>
                  
                        <td style="min-width:250px">
                            <textarea name="description[]" id="description` + id + `" placeholder="Description" cols="10" rows="2"></textarea>
                        </td>
                        <td style="min-width:100px">
                        <textarea name="detail[]" id="detail` + id + `" placeholder="Detail" cols="10" rows="2"></textarea>
                        </td>
                        <td style="min-width:100px">
                             <select class="packing_dropdown form-control select22" name="unit_id[]">
                             <option value="">Select Unit</option>
                             <?php foreach ($all_units as $unit) {
                                ?>
                                <option value="<?php echo $unit->unit_id;
                                                ?>"><?php echo $unit->unit;
                                                    ?></option>
                                <?php  }
                                ?>
                               
                            </select>
                        </td>
                       
                        <td style="min-width:100px">
                            <input type="text" name="unit_rate[]" id="cost_price_` + item + `"  placeholder="Unit Rate" class="form-control calculate">
                        </td>
                        <td><input type="number" class="form-control calculate" name="qtn[]" id="quantity` + item + `" oninput="calculation(` + item + `)"></td>
                        <td style="min-width:200px">
                            <input type="text" name="gross_price[]" id="gross_price_` + item + `"  placeholder="Gross Amount" class="form-control">
                        </td>
                       
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);
    }
    $(document).on('click', '.remove-input-field', function() {
        $(this).parents('tr').remove();

        updateCalculationPQ();
    });



    function updateCalculationPQ() {

        var total = 0;
        var total_tax = 0;
        var untaxed = 0;
        var total_amount = 0;
        var tax = parseFloat($('#tax').val());

        $('#vendor_items_table1 > tr').each(function() {

            total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

        });

        if ($('#tax_inclusive1').prop('checked') == true) {
            total_tax = (parseFloat(total_amount) * tax) / 100;
            total = total_amount + total_tax;
        } else {
            total = total_amount;
        }

        $('#sub_total').val(total_amount.toFixed(2));

        $('#total_gst1').val(total_tax.toFixed(2));

        $('#total_amount1').val(total.toFixed(2));

    }

    function calculation(id) {
        var total = 0;
        var total_tax = 0;
        var final_total = 0;
        var total_amount = 0;

        var unit_price = $("#cost_price_" + id).val();
        var quantity = $("#quantity" + id).val();

        var total = parseFloat(unit_price) * parseFloat(quantity);
        if (total > 0) {
            $("#gross_price_" + id).val(total);
        } else {
            $("#gross_price_" + id).val('0');
        }





        $('#vendor_items_table1 > tr').each(function() {

            total_amount += parseFloat($(this).find('input[name="gross_price[]"]').val());

        });


        var final_total = total_amount;
        $('#sub_total').val(total_amount.toFixed(2));

        $('#total_gst1').val(total_tax.toFixed(2));

        $('#total_amount1').val(final_total.toFixed(2));
    }

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
        var total_amount = parseFloat($('#sub_total').val());
        var tax = parseFloat($("#total_gst1 option:selected").text());
        var total = total_amount + total_amount * (tax / 100);
        $('#total_amount1').val(total);
    }
    $("#term_condition_id").on('change', function() {
        var id = $(this).val();
        $.ajax({
            type: 'GET',
            url: base_url + "/get_term_details/" + id,
            data: JSON,

            success: function(JSON) {
                var data = jQuery.parseJSON(JSON);
                $("#terms_condition").val(data[0].term_description)
            },
            error: function() {
                toastr.error("Something went wrong");
            }

        });
    });
</script>