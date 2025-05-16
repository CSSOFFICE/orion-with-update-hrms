<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['subcon_id']) && $_GET['data'] == 'subcon') {

?>
  <style>
    #ajax_modal {
      width: 1200px !important;
      margin-left: -180px;
      overflow-y: scroll !important
    }
  </style>
  <?php $system = $this->Xin_model->read_setting_info(1); ?>
  <?php $session = $this->session->userdata('username'); ?>
  <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    <h4 class="modal-title" id="edit-modal-data">Edit Sub Contractor</h4>
  </div>
  <?php $attributes = array('name' => 'edit_subcon', 'id' => 'edit_subcon', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
  <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['subcon_id'], 'ext_name' => $_GET['subcon_id']); ?>
  <?php echo form_open_multipart('admin/supplier/update_subcon/' . $_GET['subcon_id'], $attributes, $hidden); ?>
  <div class="modal-body">

    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="subcon1">Sub Contractor
            <i class="hrsale-asterisk">*</i>
          </label>
          <select name="subcon1" class="form-control" data-plugin="select_hrm" data-placeholder="Select Sub Contractor">
            <option value="">Select Sub Contractor</option>
            <?php foreach ($subcontractors as $subcontractors1) { ?>
              <option value="<?php echo $subcontractors1->supplier_id; ?>" <?php if ($subcontractors1->supplier_id == $result[0]->subcon_sup_id) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $subcontractors1->supplier_name; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="project">Project
            <i class="hrsale-asterisk">*</i>
          </label>
          <select name="project1" id="project_id1" class="form-control" data-plugin="select_hrm" data-placeholder="Select Project">
            <option value="">Select Project</option>
            <?php foreach ($all_projects as $project1) { ?>
              <option value="<?php echo $project1->project_id; ?>"><?php echo $project1->project_title; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="milestone">Milestone
            <i class="hrsale-asterisk">*</i>
          </label>
          <select class="form-control" name="milestone1" id="milestone1" data-plugin="select_hrm" data-placeholder="Select Milestone">

          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="qt_no">Task
            <i class="hrsale-asterisk">*</i>
          </label>
          <select class="form-control" name="task1" id="task1" data-plugin="select_hrm" data-placeholder="Select Task">

          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="agreement_number1">Agreement Number
            <i class="hrsale-asterisk">*</i>
          </label>
          <input type="text" class="form-control" name="agreement_number1" id="agreement_number1" value="<?php echo $result[0]->agreement_number; ?>">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="attachment">Attachment
            <i class="hrsale-asterisk">*</i>
          </label>
          <?php if ($result[0]->subcon_attachment) { ?>
            <a href="<?php echo base_url() . 'uploads/subcontractor_attachment/' . $result[0]->subcon_attachment; ?>" target="_blank">View Attachment</a>
            <input type="hidden" name="old_attachment1" value="<?php echo $result[0]->subcon_attachment; ?>">
          <?php } ?>
          <input type="file" class="form-control" name="attachment1" id="attachment1">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="contract_sum">Project Total
            <i class="hrsale-asterisk">*</i>
          </label>
          <input type="text" class="form-control" name="contract_sum1" id="contract_sum1" readonly>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="agreement_number1">Contracted Amount
            <i class="hrsale-asterisk">*</i>
          </label>
          <input type="text" class="form-control" name="contracted_ammount1" id="contracted_ammount1" value="<?php echo $result[0]->contracted_amount; ?>">
        </div>
      </div>
    </div>
  </div>


  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
  </div>
  <?php echo form_close(); ?>
  <script>
    $(document).ready(function() {
      $("#project_id1").val(<?php echo $result[0]->subcon_project_id ?>).trigger('change');


      $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
      $('[data-plugin="select_hrm"]').select2({
        width: '100%'
      });


      $('#contracted_ammount1').on('input', function() {
        var contracted_ammount = $('#contracted_ammount').val();
        // Ensure the input is a valid number
        if (!/^\d*\.?\d*$/.test(contracted_ammount)) {
          toastr.error("Please enter a valid number.");
          $('#contracted_ammount1').val('');
          return;
        }
      });

      $('#contracted_ammount1').on('blur', function() {
        var contracted_ammount = parseFloat($('#contracted_ammount1').val());
        if (!isNaN(contracted_ammount)) {
          $('#contracted_ammount1').val(contracted_ammount.toFixed(2));
        }
      });

    });
    $('#project_id1').on('change', function() {
      var project_id1 = $('#project_id1').val();
      $.ajax({
        url: "<?php echo base_url(); ?>admin/Finance/get_quotation_from_project/" + project_id1,
        type: "POST",
        dataType: "json",
        success: function(response) {
          // Clear existing values
          $('#milestone1').empty();

          // Handle Contract Sum
          if (response.quotation_no && response.quotation_no.length > 0) {
            let projectSum = parseFloat(response.quotation_no[0].project_sn);
            $("#contract_sum1").val(isNaN(projectSum) ? "0.00" : projectSum.toFixed(2));
          } else {
            $("#contract_sum1").val("0.00");
            toastr.warning("No quotation data found for this project.");
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

          // Handle Milestone Data
          if (response.milestone_list && response.milestone_list.length > 0) {
            let milestoneOptions = '<option value="">Select Milestone</option>';
            $.each(response.milestone_list, function(index, milestone) {
              const milestoneName = milestoneMapping[milestone.task_cat_id] || 'Unknown Milestone';
              milestoneOptions += `<option value="${milestone.task_cat_id}">${milestoneName}</option>`;
            });
            $('#milestone1').html(milestoneOptions);

            // Pre-select milestone
            const preSelectedMilestone = <?php echo isset($result[0]->subcon_milestone) ? json_encode($result[0]->subcon_milestone) : 'null'; ?>;
            if (preSelectedMilestone) {
              $('#milestone1').val(preSelectedMilestone).trigger('change');
            }
          } else {
            toastr.error("No Milestone found for this project.");
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: ", error);
          toastr.error("Failed to fetch quotation. Error: " + (xhr.responseText || error));
        }
      });
    });

    $('#milestone1').on('change', function() {
      var milestone_id = $('#milestone1').val();
      $.ajax({
        url: "<?php echo base_url() . 'admin/Finance/get_task_from_milestone/'; ?>" + milestone_id + "/" + $('#project_id1').val(),
        type: "POST",
        success: function(response) {
          // Clear existing values                    
          $('#task1').empty(); // Clear the Task dropdown

          // Check if Task data exists
          if (response.task_list && response.task_list.length > 0) {
            let taskOptions = '<option value="">Select Task</option>';
            $.each(response.task_list, function(index, task) {
              taskOptions += `<option value="${task.task_id}">${task.task_title}</option>`;
            });
            $('#task1').html(taskOptions);

            // Pre-select task
            const preSelectedTask = <?php echo $result[0]->subcon_task ?>;
            if (preSelectedTask) {
              $('#task1').val(preSelectedTask).trigger('change');
            }
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
    $("#edit_subcon").submit(function(e) {
      var fd = new FormData(this);
      var obj = $(this),
        action = obj.attr('name');
      fd.append("is_ajax", 1);
      fd.append("edit_type", 'edit_subcontractor');
      fd.append("form", action);
      e.preventDefault();
      $('.icon-spinner3').show();
      $('.save').prop('disabled', true);
      $.ajax({
        url: e.target.action,
        type: "POST",
        data: fd,
        contentType: false,
        cache: false,
        processData: false,
        success: function(JSON) {
          if (JSON.error != '') {
            toastr.error(JSON.error);
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            $('.save').prop('disabled', false);
            $('.icon-spinner3').hide();
          } else {
            toastr.success(JSON.result);
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            $('#edit-modal-data').modal('toggle');
            var xin_table = $('#xin_table').dataTable({
              "bDestroy": true,
              "ajax": {
                url: base_url + '/subcontractor_list/',
                type: 'GET'
              },
              dom: 'lBfrtip',
              "buttons": ['excel'], // colvis > if needed
              "fnDrawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
              }
            });
          }
        }
      });
    });
  </script>
<?php } ?>