<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('207', $role_resources_ids)) { ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>
                    <?php echo "Sub Contractor Agreement"; ?></h3>
                <div class="box-tools pull-right">
                    <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary">
                            <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a>
                </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <div class="bg-white">
                        <?php $attributes = array('name' => 'add_supplier', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                        <?php $hidden = array('_user' => $session['user_id']); ?>
                        <?php echo form_open_multipart('admin/supplier/add_subcontractor', $attributes, $hidden); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subcon">Sub Contractor 
                                    <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select name="subcon" class="form-control" data-plugin="select_hrm" data-placeholder="Select Sub Contractor">
                                        <?php foreach ($all_subcontractors as $subcontractors) { ?>
                                            <option value="">Select Sub Contractor</option>
                                            <option value="<?php echo $subcontractors->supplier_id; ?>"><?php echo $subcontractors->supplier_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="project">Project
                                    <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select name="project" id="project_id" class="form-control" data-plugin="select_hrm" data-placeholder="Select Project">
                                        <?php foreach ($all_projects as $project) { ?>
                                            <option value="">Select Project</option>
                                            <option value="<?php echo $project->project_id; ?>"><?php echo $project->project_title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="milestone">Milestone
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select class="form-control" name="milestone" id="milestone" data-plugin="select_hrm" data-placeholder="Select Milestone">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="qt_no">Task
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select class="form-control" name="task" id="task" data-plugin="select_hrm" data-placeholder="Select Task">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label for="agreement_number">Agreement Number
                                    <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <input type="text" class="form-control" name="agreement_number" id="agreement_number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="attachment">Attachment
                                    <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <input type="file" class="form-control" name="attachment" id="attachment">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="contract_sum">Project Total
                                    <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <input type="text" class="form-control" name="contract_sum" id="contract_sum" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="contracted_ammount">Contracted Amount
                                    <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <input type="text" class="form-control" name="contracted_ammount" id="contracted_ammount">
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="form-actions box-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                        <?php echo $this->lang->line('xin_save'); ?> </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
<?php } ?>
<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php echo "Sub Contractor Agreement"; ?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th style="width:120px;"><?php echo $this->lang->line('xin_action'); ?></th>
                        <th>Sub Contractor</th>
                        <th>Project</th>
                        <th>Milestone</th>
                        <th>Task</th>
                        <th>Attachment</th>
                        <th>Contracted Amount</th>
                        <th>Agreement Number</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({
            width: '100%'
        });
        $('#contracted_ammount').on('input', function() {
            var contracted_ammount = $('#contracted_ammount').val();
            // Ensure the input is a valid number
            if (!/^\d*\.?\d*$/.test(contracted_ammount)) {
            toastr.error("Please enter a valid number.");
            $('#contracted_ammount').val('');
            return;
            }
        });

        $('#contracted_ammount').on('blur', function() {
            var contracted_ammount = parseFloat($('#contracted_ammount').val());
            if (!isNaN(contracted_ammount)) {
            $('#contracted_ammount').val(contracted_ammount.toFixed(2));
            }
        });

        $('#project_id').on('change', function() {
            var project_id = $('#project_id').val();
            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/get_quotation_from_project/'; ?>" + project_id,
                type: "POST",
                success: function(response) {
                    // Clear existing values
                    $('#qt_no').val('');
                    $('#qt_id').val('');
                    $('#milestone').empty(); // Clear the milestone dropdown
                    $("#client_id").val(response.quotation_no[0].project_clientid).trigger('change');
                    let projectSum = parseFloat(response.quotation_no[0].project_sn);
                    $("#contract_sum").val(isNaN(projectSum) ? "0.00" : projectSum.toFixed(2));


                    // Check if quotation data exists
                    if (response.quotation_no && response.quotation_no.length > 0) {
                        $('#qt_no').val(response.quotation_no[0].quotation_no);
                        $('#qt_nos').val(response.quotation_no[0].quotation_no);
                        $('#qt_id').val(response.quotation_no[0].bill_estimateid);
                    } else {
                        toastr.error("No quotation found for this project.");
                    }

                    // Milestone ID-to-name mapping
                    const milestoneMapping = {
                        1: 'Preliminaries',
                        2: 'Insurances',
                        3: 'Schedule Of Works',
                        4: 'Plumbing & Sanitary',
                        5: 'Elec & Acmv',
                        6: 'External Works',
                        7: 'Pc & Ps Sums',
                        8: 'Others'
                    };

                    // Check if Milestone data exists
                    if (response.milestone_list && response.milestone_list.length > 0) {
                        let milestoneOptions = '<option value="">Select Milestone</option>';
                        $.each(response.milestone_list, function(index, milestone) {
                            const milestoneName = milestoneMapping[milestone.task_cat_id] || 'Unknown Milestone';
                            milestoneOptions += `<option value="${milestone.task_cat_id}">${milestoneName}</option>`;
                        });
                        $('#milestone').html(milestoneOptions);
                    } else {
                        toastr.error("No Milestone found for this project.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: ", error);
                    alert("An error occurred while fetching the quotation. Please try again later.");
                }
            });
        });

        $('#milestone').on('change', function() {
            var milestone_id = $('#milestone').val();
            var project_id = $('#project_id').val();

            $.ajax({
                url: "<?php echo base_url() . 'admin/Finance/get_task_from_milestone/'; ?>" + milestone_id + "/" + project_id,
                type: "POST",
                success: function(response) {
                    // Clear existing values                    
                    $('#task').empty(); // Clear the Task dropdown

                    // Check if Task data exists
                    if (response.task_list && response.task_list.length > 0) {
                        let taskOptions = '<option value="">Select Task</option>';
                        $.each(response.task_list, function(index, task) {
                            taskOptions += `<option value="${task.task_id}">${task.task_title}</option>`;
                        });
                        $('#task').html(taskOptions);
                    } else {
                        toastr.error("No Task found for this project.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: ", error);
                    alert("An error occurred while fetching the quotation. Please try again later.");
                }
            });
        });
    });
</script>