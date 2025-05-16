<?php
if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_document_type' && $_GET['type'] == 'ed_document_type') {
	$row = $this->Xin_model->read_document_type($_GET['field_id']);
?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_document_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_document_type_info', 'id' => 'ed_document_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->document_type_id, 'ext_name' => $row[0]->document_type); ?>
	<?php echo form_open('admin/settings/update_document_type/' . $row[0]->document_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_e_details_dtype'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_e_details_dtype'); ?>" value="<?php echo $row[0]->document_type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_document_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=21&type=edit_record&data=ed_document_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_document_type = $('#xin_table_document_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/document_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_document_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_contract_type' && $_GET['type'] == 'ed_contract_type') {
	$row = $this->Xin_model->read_contract_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_contract_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_contract_type_info', 'id' => 'ed_contract_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->contract_type_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_contract_type/' . $row[0]->contract_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_e_details_contract_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_e_details_contract_type'); ?>" value="<?php echo $row[0]->name ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_contract_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=22&type=edit_record&data=ed_contract_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_contract_type = $('#xin_table_contract_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/contract_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_contract_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_payment_method' && $_GET['type'] == 'ed_payment_method') {
	$row = $this->Xin_model->read_payment_method($_GET['field_id']);
?>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'Quotation_templets' && $_GET['type'] == 'Quotation_templets') {
	$row = $this->Xin_model->read_Quotation_templets($_GET['field_id']);
	$selected_category_id = $row[0]->category;
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">Edit Quotation Template</h4>
	</div>
	<?php $attributes = array('name' => 'Quotation_templets_update', 'id' => 'Quotation_templets_update_id', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->id, 'ext_name' => $row[0]->id); ?>
	<?php echo form_open('admin/settings/Quotation_templets_update/' . $row[0]->id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="row">
			<div class="col-3 m-2">
				<div class="form-group">
					<label for="name">Choose Category</label>
					<select name="Category_name" id="Category_name" class="form-control">
						<option value="1" <?= ($selected_category_id == 1) ? 'selected' : ''; ?>>PRELIMINARIES</option>
						<option value="2" <?= ($selected_category_id == 2) ? 'selected' : ''; ?>>INSURANCE</option>
						<option value="3" <?= ($selected_category_id == 3) ? 'selected' : ''; ?>>SCHEDULE OF WORKS</option>
						<option value="4" <?= ($selected_category_id == 4) ? 'selected' : ''; ?>>Plumbing & Sanitary</option>
						<option value="5" <?= ($selected_category_id == 5) ? 'selected' : ''; ?>>ELEC & ACMV</option>
						<option value="6" <?= ($selected_category_id == 6) ? 'selected' : ''; ?>>EXTERNAL WORKS</option>
						<option value="7" <?= ($selected_category_id == 7) ? 'selected' : ''; ?>>PC & PS SUMS</option>
						<option value="8" <?= ($selected_category_id == 8) ? 'selected' : ''; ?>>OTHERS</option>
					</select>

				</div>
			</div>

			<div class="col m-2">
				<div class="form-group">
					<label for="name">Description Name</label><br>
					<textarea name="description_of_quotation" class="form-control" id="description_of_quotation" rows="5"> <?= $row[0]->description_name ?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-4 m-2">
				<div class="form-group">
					<label for="name">Unit</label>
					<input type="text" value="<?= $row[0]->quotation_unit ?>" name="quotation_unit" id="quotation_unit" class="form-control" />


				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#Quotation_templets_update_id").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=22&type=Quotation_templets_update&data=Quotation_templets_update&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_Quotation_templets_id = $('#xin_table_Quotation_templets_id').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"bLengthChange": false,
								"iDisplayLength": 15,
								"aLengthMenu": [
									[15, 30, 50, 75, 100, -1],
									[15, 30, 50, 75, 100, "All"]
								],
								"ajax": {
									url: site_url + "settings/Quotation_templets_list/",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_Quotation_templets_id.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_payment_method' && $_GET['type'] == 'ed_payment_method') {
	$row = $this->Xin_model->read_payment_method($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_payment_method'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_payment_method_info', 'id' => 'ed_payment_method_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->payment_method_id, 'ext_name' => $row[0]->method_name); ?>
	<?php echo form_open('admin/settings/update_payment_method/' . $row[0]->payment_method_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_payment_method'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="Enter <?php echo $this->lang->line('xin_payment_method'); ?>" value="<?php echo $row[0]->method_name; ?>">
		</div>
		<div class="form-group">
			<label for="payment_percentage" class="form-control-label"><?php echo $this->lang->line('xin_payroll_pdf_pay_percent'); ?>:</label>
			<input type="text" class="form-control" name="payment_percentage" placeholder="Enter <?php echo $this->lang->line('xin_payroll_pdf_pay_percent'); ?>" value="<?php echo $row[0]->payment_percentage; ?>">
		</div>
		<div class="form-group">
			<label for="account_number" class="form-control-label"><?php echo $this->lang->line('xin_payroll_pdf_acc_number'); ?>:</label>
			<input type="text" class="form-control" name="account_number" placeholder="Enter <?php echo $this->lang->line('xin_payroll_pdf_acc_number'); ?>" value="<?php echo $row[0]->account_number; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_payment_method_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=23&type=edit_record&data=ed_payment_method_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_payment_method = $('#xin_table_payment_method')
								.dataTable({
									"bDestroy": true,
									"bFilter": false,
									"iDisplayLength": 5,
									"aLengthMenu": [
										[5, 10, 30, 50, 100, -1],
										[5, 10, 30, 50, 100, "All"]
									],
									"ajax": {
										url: "<?php echo site_url("admin/settings/payment_method_list") ?>",
										type: 'GET'
									},
									"fnDrawCallback": function(settings) {
										$('[data-toggle="tooltip"]').tooltip();
									}
								});
							xin_table_payment_method.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_education_level' && $_GET['type'] == 'ed_education_level') {
	$row = $this->Xin_model->read_education_level($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_education_level'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_education_level_info', 'id' => 'ed_education_level_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->education_level_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_education_level/' . $row[0]->education_level_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_e_details_edu_level'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_e_details_edu_level'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_education_level_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=24&type=edit_record&data=ed_education_level_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_education_level = $('#xin_table_education_level')
								.dataTable({
									"bDestroy": true,
									"bFilter": false,
									"iDisplayLength": 5,
									"aLengthMenu": [
										[5, 10, 30, 50, 100, -1],
										[5, 10, 30, 50, 100, "All"]
									],
									"ajax": {
										url: "<?php echo site_url("admin/settings/education_level_list") ?>",
										type: 'GET'
									},
									"fnDrawCallback": function(settings) {
										$('[data-toggle="tooltip"]').tooltip();
									}
								});
							xin_table_education_level.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_qualification_language' && $_GET['type'] == 'ed_qualification_language') {
	$row = $this->Xin_model->read_qualification_language($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_language'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_qualification_language_info', 'id' => 'ed_qualification_language_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->language_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_qualification_language/' . $row[0]->language_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_e_details_language'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_e_details_language'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_qualification_language_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=25&type=edit_record&data=ed_qualification_language_info&form=" +
						action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_qualification_language = $(
								'#xin_table_qualification_language').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/qualification_language_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_qualification_language.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_qualification_skill' && $_GET['type'] == 'ed_qualification_skill') {
	$row = $this->Xin_model->read_qualification_skill($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_skill'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_qualification_skill_info', 'id' => 'ed_qualification_skill_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->skill_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_qualification_skill/' . $row[0]->skill_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_skill'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_skill'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_qualification_skill_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=26&type=edit_record&data=ed_qualification_skill_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_qualification_skill = $('#xin_table_qualification_skill')
								.dataTable({
									"bDestroy": true,
									"bFilter": false,
									"iDisplayLength": 5,
									"aLengthMenu": [
										[5, 10, 30, 50, 100, -1],
										[5, 10, 30, 50, 100, "All"]
									],
									"ajax": {
										url: "<?php echo site_url("admin/settings/qualification_skill_list") ?>",
										type: 'GET'
									},
									"fnDrawCallback": function(settings) {
										$('[data-toggle="tooltip"]').tooltip();
									}
								});
							xin_table_qualification_skill.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_award_type' && $_GET['type'] == 'ed_award_type') {
	$row = $this->Xin_model->read_award_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_award_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_award_type_info', 'id' => 'ed_award_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->award_type_id, 'ext_name' => $row[0]->award_type); ?>
	<?php echo form_open('admin/settings/update_award_type/' . $row[0]->award_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_award_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_award_type'); ?>" value="<?php echo $row[0]->award_type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_award_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=38&type=edit_record&data=ed_award_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_award_type = $('#xin_table_award_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/award_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_award_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_leave_type' && $_GET['type'] == 'ed_leave_type') {
	$row = $this->Xin_model->read_leave_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_leave_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_leave_type_info', 'id' => 'ed_leave_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->leave_type_id, 'ext_name' => $row[0]->type_name); ?>
	<?php echo form_open('admin/settings/update_leave_type/' . $row[0]->leave_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_leave_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_leave_type'); ?>" value="<?php echo $row[0]->type_name; ?>">
		</div>
		<div class="form-group">
			<label>
				<input type="checkbox" class="minimal" value="1" id="leave_is_paid" name="leave_is_paid" <?php if ($row[0]->is_paid) echo 'checked' ?>>
				<span>Is Paid</span>
			</label>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_leave_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=39&type=edit_record&data=ed_leave_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_leave_type = $('#xin_table_leave_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 10,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/leave_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_leave_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_warning_type' && $_GET['type'] == 'ed_warning_type') {
	$row = $this->Xin_model->read_warning_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_warning_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_warning_type_info', 'id' => 'ed_warning_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->warning_type_id, 'ext_name' => $row[0]->type); ?>
	<?php echo form_open('admin/settings/update_warning_type/' . $row[0]->warning_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_warning_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_warning_type'); ?>" value="<?php echo $row[0]->type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_warning_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=40&type=edit_record&data=ed_warning_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_warning_type = $('#xin_table_warning_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/warning_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_warning_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_termination_type' && $_GET['type'] == 'ed_termination_type') {
	$row = $this->Xin_model->read_termination_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_termination_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_termination_type_info', 'id' => 'ed_termination_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->termination_type_id, 'ext_name' => $row[0]->type); ?>
	<?php echo form_open('admin/settings/update_termination_type/' . $row[0]->termination_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_termination_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_termination_type'); ?>" value="<?php echo $row[0]->type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_termination_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=41&type=edit_record&data=ed_termination_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_termination_type = $('#xin_table_termination_type')
								.dataTable({
									"bDestroy": true,
									"bFilter": false,
									"iDisplayLength": 5,
									"aLengthMenu": [
										[5, 10, 30, 50, 100, -1],
										[5, 10, 30, 50, 100, "All"]
									],
									"ajax": {
										url: "<?php echo site_url("admin/settings/termination_type_list") ?>",
										type: 'GET'
									},
									"fnDrawCallback": function(settings) {
										$('[data-toggle="tooltip"]').tooltip();
									}
								});
							xin_table_termination_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_expense_type' && $_GET['type'] == 'ed_expense_type') {
	$row = $this->Xin_model->read_expense_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_expense_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_expense_type_info', 'id' => 'ed_expense_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->expense_type_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_expense_type/' . $row[0]->expense_type_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="company_name"><?php echo $this->lang->line('module_company_title'); ?></label>
			<select class="form-control" name="company" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>">
				<option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
				<?php foreach ($this->Xin_model->get_companies() as $company) { ?>
					<option value="<?php echo $company->company_id; ?>" <?php if ($company->company_id == $row[0]->company_id) : ?> selected="selected" <?php endif; ?>> <?php echo $company->name; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_expense_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_expense_type'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_expense_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=42&type=edit_record&data=ed_expense_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_expense_type = $('#xin_table_expense_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/expense_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_expense_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_job_type' && $_GET['type'] == 'ed_job_type') {
	$row = $this->Xin_model->read_job_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_job_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_job_type_info', 'id' => 'ed_job_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->job_type_id, 'ext_name' => $row[0]->type); ?>
	<?php echo form_open('admin/settings/update_job_type/' . $row[0]->job_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_job_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_job_type'); ?>" value="<?php echo $row[0]->type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_job_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=43&type=edit_record&data=ed_job_type_info&form=" +
						action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_job_type = $('#xin_table_job_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/job_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_job_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_job_category' && $_GET['type'] == 'ed_job_category') {
	$row = $this->Xin_model->read_job_category($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_rec_edit_job_category'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_job_category_info', 'id' => 'ed_job_category_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->category_id, 'ext_name' => $row[0]->category_name); ?>
	<?php echo form_open('admin/settings/update_job_category/' . $row[0]->category_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="job_category" class="form-control-label"><?php echo $this->lang->line('xin_rec_job_category'); ?>:</label>
			<input type="text" class="form-control" name="job_category" placeholder="<?php echo $this->lang->line('xin_rec_job_category'); ?>" value="<?php echo $row[0]->category_name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_job_category_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=43&type=edit_record&data=ed_job_category_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_job_category = $('#xin_table_job_category').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/job_category_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_job_category.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_exit_type' && $_GET['type'] == 'ed_exit_type') {
	$row = $this->Xin_model->read_exit_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_employee_exit_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_exit_type_info', 'id' => 'ed_exit_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->exit_type_id, 'ext_name' => $row[0]->type); ?>
	<?php echo form_open('admin/settings/update_exit_type/' . $row[0]->exit_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_employee_exit_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_employee_exit_type'); ?>" value="<?php echo $row[0]->type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_exit_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=44&type=edit_record&data=ed_exit_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_exit_type = $('#xin_table_exit_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/exit_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_exit_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_travel_arr_type' && $_GET['type'] == 'ed_travel_arr_type') {
	$row = $this->Xin_model->read_travel_arr_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_travel_arr_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_travel_arr_type_info', 'id' => 'ed_travel_arr_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->arrangement_type_id, 'ext_name' => $row[0]->type); ?>
	<?php echo form_open('admin/settings/update_travel_arr_type/' . $row[0]->arrangement_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_travel_arrangement_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_travel_arrangement_type'); ?>" value="<?php echo $row[0]->type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_travel_arr_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_travel_arr_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_travel_arr_type = $('#xin_table_travel_arr_type')
								.dataTable({
									"bDestroy": true,
									"bFilter": false,
									"iDisplayLength": 5,
									"aLengthMenu": [
										[5, 10, 30, 50, 100, -1],
										[5, 10, 30, 50, 100, "All"]
									],
									"ajax": {
										url: "<?php echo site_url("admin/settings/travel_arr_type_list") ?>",
										type: 'GET'
									},
									"fnDrawCallback": function(settings) {
										$('[data-toggle="tooltip"]').tooltip();
									}
								});
							xin_table_travel_arr_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_currency_type' && $_GET['type'] == 'ed_currency_type') {
	$row = $this->Xin_model->read_currency_types($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_currency_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_currency_type_info', 'id' => 'ed_currency_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->currency_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_currency_type/' . $row[0]->currency_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name"><?php echo $this->lang->line('xin_currency_name'); ?></label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_currency_name'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
		<div class="form-group">
			<label for="name"><?php echo $this->lang->line('xin_currency_code'); ?></label>
			<input type="text" class="form-control" name="code" placeholder="<?php echo $this->lang->line('xin_currency_code'); ?>" value="<?php echo $row[0]->code; ?>">
		</div>
		<div class="form-group">
			<label for="name"><?php echo $this->lang->line('xin_currency_symbol'); ?></label>
			<input type="text" class="form-control" name="symbol" placeholder="<?php echo $this->lang->line('xin_currency_symbol'); ?>" value="<?php echo $row[0]->symbol; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_currency_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_currency_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_currency_type = $('#xin_table_currency_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/currency_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_currency_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_company_type' && $_GET['type'] == 'ed_company_type') {
	$row = $this->Xin_model->read_company_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_company_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_company_type_info', 'id' => 'ed_company_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->type_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_company_type/' . $row[0]->type_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_company_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_company_type'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_company_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_company_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_company_type = $('#xin_table_company_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/company_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_company_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_packing_type' && $_GET['type'] == 'ed_packing_type') {
	$row = $this->Xin_model->read_packing_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_company_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_packing_type_info', 'id' => 'ed_packing_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->type_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_packing_type/' . $row[0]->type_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_packing_type'); ?>:</label>
			<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line('xin_packing_type'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_packing_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_packing_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_company_type = $('#xin_table_packing_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/packing_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_company_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_gst' && $_GET['type'] == 'ed_gst') {
	$row = $this->Xin_model->read_gst($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_gst'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_gst_info', 'id' => 'ed_gst_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->gst_id, 'ext_name' => $row[0]->gst); ?>
	<?php echo form_open('admin/settings/update_gst/' . $row[0]->gst_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_gst'); ?>:</label>
			<input type="text" class="form-control" name="gst" placeholder="<?php echo $this->lang->line('xin_gst'); ?>" value="<?php echo $row[0]->gst; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_gst_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_gst&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_company_type = $('#xin_table_gst').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/gst_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_company_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_mode_of_transport' && $_GET['type'] == 'ed_mode_of_transport') {
	// $row = $this->Xin_model->read_gst($_GET['field_id']);
	$row = $this->db->where('mst_id', $_GET['field_id'])->get("mode_of_transport")->result();
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">Edit Mode of Transport</h4>
	</div>
	<?php $attributes = array('name' => 'ed_mode_of_transport_info', 'id' => 'ed_mode_of_transport_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->mst_id, 'ext_name' => $row[0]->mst_title); ?>
	<?php echo form_open('admin/settings/update_mode_of_transport_type/' . $row[0]->mst_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label">Mode of Transport :</label>
			<input type="text" class="form-control" name="transport_mode1" placeholder="Mode of Transport" value="<?php echo $row[0]->mst_title; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_mode_of_transport_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_mode_of_transport_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_modeoftransport = $('#mode_of_transport').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/modeoftransport_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_modeoftransport.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>

<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_loan_type' && $_GET['type'] == 'ed_loan_type') {
	// $row = $this->Xin_model->read_gst($_GET['field_id']);
	$row = $this->db->where('loan_id', $_GET['field_id'])->get("loan_type")->result();
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">Edit Mode of Transport</h4>
	</div>
	<?php $attributes = array('name' => 'ed_loan_type', 'id' => 'ed_loan_type', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->loan_id, 'ext_name' => $row[0]->loan_id); ?>
	<?php echo form_open('admin/settings/update_loan_type/' . $row[0]->loan_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label">Loan Type :</label>
			<input type="text" class="form-control" name="loan_type1" placeholder="Loan Type" value="<?php echo $row[0]->loan_title; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_loan_type").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_loan_type&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_loan_type = $('#loan_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/loantype_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_loan_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_payment_term' && $_GET['type'] == 'ed_payment_term') {
	$row = $this->Xin_model->read_payment_term($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_gst'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_payment_term_info', 'id' => 'ed_payment_term_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->payment_term_id, 'ext_name' => $row[0]->payment_term); ?>
	<?php echo form_open('admin/settings/update_payment_term/' . $row[0]->payment_term_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_payment_term'); ?>:</label>
			<input type="text" class="form-control" name="payment_term" placeholder="<?php echo $this->lang->line('xin_payment_term'); ?>" value="<?php echo $row[0]->payment_term; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_payment_term_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_payment_term&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_payment_term = $('#xin_table_payment_term').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/payment_term_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_payment_term.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_unit' && $_GET['type'] == 'ed_unit') {
	$row = $this->Xin_model->read_unit($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_unit'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_unit_info', 'id' => 'ed_unit_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->unit_id, 'ext_name' => $row[0]->unit); ?>
	<?php echo form_open('admin/settings/update_unit/' . $row[0]->unit_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_unit'); ?>:</label>
			<input type="text" class="form-control" name="unit" placeholder="<?php echo $this->lang->line('xin_unit'); ?>" value="<?php echo $row[0]->unit; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_unit_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_unit&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_unit = $('#xin_table_unit').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/unit_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_unit.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_security_level' && $_GET['type'] == 'ed_security_level') {
	$row = $this->Xin_model->read_security_level($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_security_level'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_security_level_info', 'id' => 'ed_security_level_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->type_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_security_level/' . $row[0]->type_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_security_level'); ?>:</label>
			<input type="text" class="form-control" name="security_level" placeholder="<?php echo $this->lang->line('xin_security_level'); ?>" value="<?php echo $row[0]->name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_security_level_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_security_level_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var exin_table_security_level = $('#xin_table_security_level')
								.dataTable({
									"bDestroy": true,
									"bFilter": false,
									"iDisplayLength": 5,
									"aLengthMenu": [
										[5, 10, 30, 50, 100, -1],
										[5, 10, 30, 50, 100, "All"]
									],
									"ajax": {
										url: "<?php echo site_url("admin/settings/security_level_list") ?>",
										type: 'GET'
									},
									"fnDrawCallback": function(settings) {
										$('[data-toggle="tooltip"]').tooltip();
									}
								});
							exin_table_security_level.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_term_condition' && $_GET['type'] == 'ed_term_condition') {
	$row = $this->Xin_model->read_term_condition($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_term_condition'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_term_condition', 'id' => 'ed_term_condition', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->term_id, 'ext_name' => $row[0]->term_title); ?>
	<?php echo form_open('admin/settings/update_term_condition/' . $row[0]->term_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="term_title" class="form-control-label"><?php echo $this->lang->line('xin_term_condition'); ?>:</label>
			<input type="text" class="form-control" name="term_title" placeholder="<?php echo $this->lang->line('xin_term_condition'); ?>" value="<?php echo $row[0]->term_title; ?>">
		</div>
		<div class="form-group">
			<label for="term_title" class="form-control-label"><?php echo $this->lang->line('xin_description'); ?>:</label>
			<textarea class="form-control" name="term_description" placeholder="<?php echo $this->lang->line('xin_description'); ?>"><?php echo $row[0]->term_description; ?></textarea>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_term_condition").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_term_condition&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_term_condition_level = $('#xin_table_term_condition_level').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/term_condition_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_term_condition_level.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_project_type' && $_GET['type'] == 'ed_project_type') {
	$row = $this->db->where('project_type_id', $_GET['field_id'])->get('xin_project_type')->result();
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">Edit Project Type</h4>
	</div>
	<?php $attributes = array('name' => 'ed_project_type', 'id' => 'ed_project_type', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->project_type_id, 'ext_name' => $row[0]->project_type); ?>
	<?php echo form_open('admin/settings/update_project_type/' . $row[0]->project_type_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="term_title" class="form-control-label">Project Type:</label>
			<input type="text" class="form-control" name="project_type1" placeholder="Project Type" value="<?php echo $row[0]->project_type; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_project_type").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_project_type&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_project_type = $('#xin_table_project_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/project_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_project_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_pay_type' && $_GET['type'] == 'ed_pay_type') {
	$row = $this->db->where('payment_method_id', $_GET['field_id'])->get('xin_payment_method')->result();
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">Edit Payment Type</h4>
	</div>
	<?php $attributes = array('name' => 'ed_pay_type', 'id' => 'ed_pay_type', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->payment_method_id, 'ext_name' => $row[0]->method_name); ?>
	<?php echo form_open('admin/settings/update_pay_type/' . $row[0]->payment_method_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="term_title" class="form-control-label">Types:</label>
			<input type="text" class="form-control" name="method_name1" placeholder="Types" value="<?php echo $row[0]->method_name; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_pay_type").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_pay_type&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var pay_type_list = $('#Pay_type_list').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/pay_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							pay_type_list.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_delivery_type' && $_GET['type'] == 'ed_delivery_type') {
	$row = $this->db->where('id', $_GET['field_id'])->get('delivery_weeks')->result();
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">Edit Purchase Purpose</h4>
	</div>
	<?php $attributes = array('name' => 'ed_delivery_type', 'id' => 'ed_delivery_type', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->id, 'ext_name' => $row[0]->delivery_time); ?>
	<?php echo form_open('admin/settings/update_delivery_type/' . $row[0]->id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="term_title" class="form-control-label">Delivery Time:</label>
			<input type="text" class="form-control" name="delivery_time1" placeholder="Types" value="<?php echo $row[0]->delivery_time; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_delivery_type").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_delivery_type&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var delivery_type_list = $('#delivery_type_list').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/delivery_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							delivery_type_list.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_purchase_type' && $_GET['type'] == 'ed_purchase_type') {
	$row = $this->db->where('id', $_GET['field_id'])->get('purchase_purpose')->result();
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">Edit Purchase Purpose</h4>
	</div>
	<?php $attributes = array('name' => 'ed_purchase_type', 'id' => 'ed_purchase_type', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->id, 'ext_name' => $row[0]->purpose_title); ?>
	<?php echo form_open('admin/settings/update_purchase_type/' . $row[0]->id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="term_title" class="form-control-label">Types:</label>
			<input type="text" class="form-control" name="purpose_title1" placeholder="Types" value="<?php echo $row[0]->purpose_title; ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_purchase_type").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=46&type=edit_record&data=ed_purchase_type&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_purpose_type = $('#xin_table_purpose_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/purchase_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_purpose_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['user_id']) && $_GET['data'] == 'password' && $_GET['type'] == 'password') { ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('header_change_password'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'e_change_password', 'id' => 'profile_password', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', 'user_id' => $_GET['user_id']); ?>
	<?php echo form_open('admin/employees/change_password/' . $row[0]->currency_id, $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="new_password"><?php echo $this->lang->line('xin_e_details_enpassword'); ?></label>
					<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_enpassword'); ?>" name="new_password" type="text">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="new_password_confirm" class="control-label"><?php echo $this->lang->line('xin_e_details_ecnpassword'); ?></label>
					<input class="form-control" placeholder="<?php echo $this->lang->line('xin_e_details_ecnpassword'); ?>" name="new_password_confirm" type="text">
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			/* change password */
			jQuery("#profile_password").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = jQuery(this),
					action = obj.attr('name');
				jQuery('.save').prop('disabled', true);
				jQuery.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=31&data=e_change_password&type=change_password&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							jQuery('.save').prop('disabled', false);
						} else {
							$('.pro_change_password').modal('toggle');
							toastr.success(JSON.result);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							jQuery('#profile_password')[0].reset(); // To reset form fields
							jQuery('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_ethnicity_type' && $_GET['type'] == 'ed_ethnicity_type') {
	$row = $this->Xin_model->read_ethnicity_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_ethnicity_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_ethnicity_type_info', 'id' => 'ed_ethnicity_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->ethnicity_type_id, 'ext_name' => $row[0]->type); ?>
	<?php echo form_open('admin/settings/update_ethnicity_type/' . $row[0]->ethnicity_type_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="name" class="form-control-label"><?php echo $this->lang->line('xin_ethnicity_type_title'); ?>:</label>
			<input type="text" class="form-control" name="ethnicity_type" placeholder="<?php echo $this->lang->line('xin_ethnicity_type_title'); ?>" value="<?php echo $row[0]->type ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_ethnicity_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=22&type=edit_record&data=ed_ethnicity_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_ethnicity_type = $('#xin_table_ethnicity_type')
								.dataTable({
									"bDestroy": true,
									"bFilter": false,
									"iDisplayLength": 5,
									"aLengthMenu": [
										[5, 10, 30, 50, 100, -1],
										[5, 10, 30, 50, 100, "All"]
									],
									"ajax": {
										url: "<?php echo site_url("admin/settings/ethnicity_type_list") ?>",
										type: 'GET'
									},
									"fnDrawCallback": function(settings) {
										$('[data-toggle="tooltip"]').tooltip();
									}
								});
							xin_table_ethnicity_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['field_id']) && $_GET['data'] == 'ed_income_type' && $_GET['type'] == 'ed_income_type') {
	$row = $this->Xin_model->read_income_type($_GET['field_id']);
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_income_type'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'ed_income_type_info', 'id' => 'ed_income_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $row[0]->category_id, 'ext_name' => $row[0]->name); ?>
	<?php echo form_open('admin/settings/update_income_type/' . $row[0]->category_id, $attributes, $hidden); ?>
	<div class="modal-body">

		<div class="form-group">
			<label for="income_type" class="form-control-label"><?php echo $this->lang->line('xin_income_type'); ?>:</label>
			<input type="text" class="form-control" name="income_type" placeholder="<?php echo $this->lang->line('xin_income_type'); ?>" value="<?php echo $row[0]->name ?>">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			/* Edit data */
			$("#ed_income_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() +
						"&is_ajax=22&type=edit_record&data=ed_income_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							$('.edit_setting_datail').modal('toggle');
							// On page load: datatable
							var xin_table_income_type = $('#xin_table_income_type').dataTable({
								"bDestroy": true,
								"bFilter": false,
								"iDisplayLength": 5,
								"aLengthMenu": [
									[5, 10, 30, 50, 100, -1],
									[5, 10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("admin/settings/income_type_list") ?>",
									type: 'GET'
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_income_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						}
					}
				});
			});
		});
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['p']) && $_GET['data'] == 'policy' && $_GET['type'] == 'policy') {
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_company_policy'); ?></h4>
	</div>
	<div class="modal-body">
		<div class="form-group">
			<div id="accordion" role="tablist" aria-multiselectable="true">
				<?php foreach ($this->Xin_model->all_policies() as $_policy) : ?>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $_policy->policy_id; ?>" aria-expanded="true" aria-controls="collapseOne">
									<?php
									if ($_policy->company_id == 0) {
										$cname = $this->lang->line('xin_all_companies');
									} else {
										$company = $this->Xin_model->read_company_info($_policy->company_id);
										if (!is_null($company)) {
											$cname = $company[0]->name;
										} else {
											$cname = '--';
										}
									}
									?>
									<?php echo $_policy->title; ?> (<?php echo $cname; ?>) </a> </h4>
						</div>
						<div id="collapse<?php echo $_policy->policy_id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" style="margin:10px;">
							<?php echo html_entity_decode($_policy->description); ?> </div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
	</div>

<?php } ?>