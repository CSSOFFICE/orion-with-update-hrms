<?php
/* Constants view
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $moduleInfo = $this->Xin_model->read_setting_info(1); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<div class="row match-heights">
	<div class="col-lg-3 col-md-3 <?php echo $get_animate; ?>">

		<div class="box">
			<div class="box-blocks">
				<div class="list-group">
					<a class="list-group-item list-group-item-action nav-tabs-link active" href="#contract" data-constant="1" data-constant-block="contract" data-toggle="tab" aria-expanded="true" id="constant_1">
						<i class="fa fa-pencil-square-o"></i>
						<?php echo $this->lang->line('xin_e_details_contract_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#company_type" data-constant="14" data-constant-block="company_type" data-toggle="tab" aria-expanded="true" id="constant_14">
						<i class="fa fa-building"></i>
						<?php echo $this->lang->line('xin_company_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#currency_type" data-constant="13" data-constant-block="currency_type" data-toggle="tab" aria-expanded="true" id="constant_13">
						<i class="fa fa-dollar"></i>
						<?php echo $this->lang->line('xin_currency_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#delivery" data-constant="28" data-constant-block="delivery" data-toggle="tab" aria-expanded="true" id="constant_28">
						<i class="fa fa-pencil-square-o"></i>
						Delivery
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#document_type" data-constant="3" data-constant-block="document_type" data-toggle="tab" aria-expanded="true" id="constant_3">
						<i class="fa fa-file-zip-o"></i>
						<?php echo $this->lang->line('xin_e_details_dtype'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#ethnicity_type" data-constant="16" data-constant-block="ethnicity_type" data-toggle="tab" aria-expanded="true" id="constant_16">
						<i class="fa fa-road"></i>
						<?php echo $this->lang->line('xin_ethnicity_type_title'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#expense_type" data-constant="8" data-constant-block="expense_type" data-toggle="tab" aria-expanded="true" id="constant_8">
						<i class="fa fa-money"></i>
						<?php echo $this->lang->line('xin_expense_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#gst" data-constant="17" data-constant-block="gst" data-toggle="tab" aria-expanded="true" id="constant_17">
						<i class="fa fa-building"></i>
						<?php echo $this->lang->line('xin_gst'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#income_type" data-constant="17" data-constant-block="income_type" data-toggle="tab" aria-expanded="true" id="constant_17">
						<i class="fa fa-gg"></i>
						<?php echo $this->lang->line('xin_income_type'); ?>
					</a>
					<?php if ($moduleInfo[0]->module_recruitment == 'true') { ?>
						<a class="list-group-item list-group-item-action nav-tabs-link" href="#job_categories" data-constant="15" data-constant-block="job_categories" data-toggle="tab" aria-expanded="true" id="constant_15">
							<i class="fa fa-newspaper-o"></i>
							<?php echo $this->lang->line('xin_rec_job_categories'); ?>
						</a>
						<a class="list-group-item list-group-item-action nav-tabs-link" href="#job_type" data-constant="9" data-constant-block="job_type" data-toggle="tab" aria-expanded="true" id="constant_9">
							<i class="fa fa-newspaper-o"></i>
							<?php echo $this->lang->line('xin_job_type'); ?>
						</a>
					<?php } ?>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#leave_type" data-constant="5" data-constant-block="leave_type" data-toggle="tab" aria-expanded="true" id="constant_5">
						<i class="fa fa-plane"></i>
						<?php echo $this->lang->line('xin_leave_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#modeoftransport" data-constant="31" data-constant-block="modeoftransport" data-toggle="tab" aria-expanded="true" id="constant_31">
						<i class="fa fa-ship"></i> Mode of Transport
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#loantype" data-constant="32" data-constant-block="loantype" data-toggle="tab" aria-expanded="true" id="constant_32">
						<i class="fa fa-money"></i> Loan Type
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#packing_type" data-constant="16" data-constant-block="packing_type" data-toggle="tab" aria-expanded="true" id="constant_16">
						<i class="fa fa-building"></i>
						<?php echo $this->lang->line('xin_packing_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#payment_term" data-constant="18" data-constant-block="payment_term" data-toggle="tab" aria-expanded="true" id="constant_18">
						<i class="fa fa-building"></i>
						<?php echo $this->lang->line('xin_payment_term'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#ptype" data-constant="29" data-constant-block="ptype" data-toggle="tab" aria-expanded="true" id="constant_29">
						<i class="fa fa-pencil-square-o"></i>
						Payment Types
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#project" data-constant="21" data-constant-block="project" data-toggle="tab" aria-expanded="true" id="constant_21">
						<i class="fa fa-pencil-square-o"></i>
						Project Type
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#purpose" data-constant="27" data-constant-block="purpose" data-toggle="tab" aria-expanded="true" id="constant_27">
						<i class="fa fa-pencil-square-o"></i>
						Purpose of Purchase
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#qualification" data-constant="2" data-constant-block="qualification" data-toggle="tab" aria-expanded="true" id="constant_2">
						<i class="fa fa-square-o"></i>
						<?php echo $this->lang->line('xin_e_details_qualification'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#Quotation_templets" data-constant="30" data-constant-block="Quotation_templets" data-toggle="tab" aria-expanded="true" id="constant_30">
						<i class="fa fa-pencil-square-o"></i>
						Quotation template
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#security_level" data-constant="15" data-constant-block="security_level" data-toggle="tab" aria-expanded="true" id="constant_15">
						<i class="fa fa-get-pocket"></i>
						<?php echo $this->lang->line('xin_security_level'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#shipping_term" data-constant="28" data-constant-block="shipping_term" data-toggle="tab" aria-expanded="true" id="constant_18">
						<i class="fa fa-truck"></i>
						Shipping Term
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#term_condition_level" data-constant="15" data-constant-block="term_condition_level" data-toggle="tab" aria-expanded="true" id="constant_20">
						<i class="fa fa-get-pocket"></i>
						<?php echo $this->lang->line('xin_term_condition'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#termination_type" data-constant="7" data-constant-block="termination_type" data-toggle="tab" aria-expanded="true" id="constant_7">
						<i class="fa fa-user-times"></i>
						<?php echo $this->lang->line('xin_termination_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#travel_arr_type" data-constant="11" data-constant-block="travel_arr_type" data-toggle="tab" aria-expanded="true" id="constant_11">
						<i class="fa fa-car"></i>
						<?php echo $this->lang->line('xin_travel_arrangement_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#units" data-constant="19" data-constant-block="units" data-toggle="tab" aria-expanded="true" id="constant_19">
						<i class="fa fa-building"></i>
						<?php echo $this->lang->line('xin_unit'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#warning_type" data-constant="6" data-constant-block="warning_type" data-toggle="tab" aria-expanded="true" id="constant_6">
						<i class="fa fa-exclamation-triangle"></i>
						<?php echo $this->lang->line('xin_warning_type'); ?>
					</a>
					<a class="list-group-item list-group-item-action nav-tabs-link" href="#exit_type" data-constant="10" data-constant-block="exit_type" data-toggle="tab" aria-expanded="true" id="constant_10">
						<i class="fa fa-sign-out"></i>
						<?php echo $this->lang->line('xin_employee_exit_type'); ?>
					</a>
					<?php if ($moduleInfo[0]->module_awards == 'true') { ?>
						<a class="list-group-item list-group-item-action nav-tabs-link" href="#award_type" data-constant="4" data-constant-block="award_type" data-toggle="tab" aria-expanded="true" id="constant_4">
							<i class="fa fa-trophy"></i>
							<?php echo $this->lang->line('xin_award_type'); ?>
						</a>
					<?php } ?>
				</div>


			</div>
		</div>
	</div>


	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="contract">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_e_details_contract_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'contract_type_info', 'id' => 'contract_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_contract_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/contract_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_e_details_contract_type'); ?></label>
							<input type="text" class="form-control" name="contract_type"
								placeholder="<?php echo $this->lang->line('xin_e_details_contract_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_e_details_contract_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_contract_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_e_details_contract_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="Quotation_templets" style="display:none;">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							Quotation Template </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'Quotation_templets_add', 'id' => 'Quotation_templets_id', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('Quotation_templets' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/Quotation_templets_add/', $attributes, $hidden); ?>
						<div class="row">
							<div class="col-3 m-2">
								<div class="form-group">
									<label for="name">Choose Category</label>
									<select name="Category_name" id="Category_name" class="form-control">
										<!-- <option value="1">SUMMARY</option> -->
										<option value="1">Preliminaries</option>
										<option value="2">Insurance</option>
										<option value="3">Schedule Of Works</option>
										<option value="4">Plumbing & Sanitary</option>
										<option value="5">Elec & Acmv</option>
										<option value="6">External Works</option>
										<option value="7">Pc & Ps Sums</option>
										<option value="8">Others</option>
									</select>
								</div>
							</div>
							<div class="col-1 m-2">
								<div class="form-group">
									<label for="name">Unit</label>
									<input type="text" name="quotation_unit" id="quotation_unit" class="form-control" />


								</div>
							</div>
							<div class="col m-2">
								<div class="form-group">
									<label for="name">Description Name</label><br>
									<textarea name="description_of_quotation" rows="5" class="form-control" id="description_of_quotation"></textarea>
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
			<div class="col-md-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							Quotation Template </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_Quotation_templets_id">
								<thead>
									<tr>
										<th style="width: 10%;"><?php echo $this->lang->line('xin_action'); ?></th>
										<th>Category</th>
										<th>Unit</th>
										<th>Description Name</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="document_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_e_details_dtype'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'document_type_info', 'id' => 'document_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_document_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/document_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_e_details_dtype'); ?></label>
							<input type="text" class="form-control" name="document_type"
								placeholder="<?php echo $this->lang->line('xin_e_details_dtype'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_e_details_dtype'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_document_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_e_details_dtype'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="qualification" style="display:none;">
		<div class="row mb-4">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_e_details_edu_level'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'edu_level_info', 'id' => 'edu_level_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_document_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/edu_level_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_e_details_edu_level'); ?></label>
							<input type="text" class="form-control" name="name"
								placeholder="<?php echo $this->lang->line('xin_e_details_edu_level'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_e_details_edu_level'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_education_level">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_e_details_edu_level'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_e_details_language'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'edu_language_info', 'id' => 'edu_language_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_edu_language' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/edu_language_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_e_details_language'); ?></label>
							<input type="text" class="form-control" name="name"
								placeholder="<?php echo $this->lang->line('xin_e_details_language'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_e_details_language'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_qualification_language">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_e_details_language'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_skill'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'edu_skill_info', 'id' => 'edu_skill_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_edu_skill' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/edu_skill_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_skill'); ?></label>
							<input type="text" class="form-control" name="name"
								placeholder="<?php echo $this->lang->line('xin_skill'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_skill'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_qualification_skill">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_skill'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($moduleInfo[0]->module_awards == 'true') { ?>
		<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="award_type" style="display:none;">
			<div class="row">
				<div class="col-md-5">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
								<?php echo $this->lang->line('xin_award_type'); ?> </h3>
						</div>
						<div class="box-body">
							<?php $attributes = array('name' => 'award_type_info', 'id' => 'award_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
							<?php $hidden = array('set_award_type' => 'UPDATE'); ?>
							<?php echo form_open('admin/settings/award_type_info/', $attributes, $hidden); ?>
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_award_type'); ?></label>
								<input type="text" class="form-control" name="award_type"
									placeholder="<?php echo $this->lang->line('xin_award_type'); ?>">
							</div>
							<div class="form-actions box-footer">
								<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
									<?php echo $this->lang->line('xin_save'); ?> </button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
								<?php echo $this->lang->line('xin_award_type'); ?> </h3>
						</div>
						<div class="box-body">
							<div class="box-datatable table-responsive">
								<table class="datatables-demo table table-striped table-bordered" id="xin_table_award_type">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('xin_action'); ?></th>
											<th><?php echo $this->lang->line('xin_award_type'); ?></th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="leave_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_leave_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'leave_type_info', 'id' => 'leave_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_leave_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/leave_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_leave_type'); ?></label>
							<input type="text" class="form-control" name="leave_type"
								placeholder="<?php echo $this->lang->line('xin_leave_type'); ?>">
						</div>
						<div class="form-group">
							<label>
								<input type="checkbox" class="minimal" value="1" id="leave_is_paid" name="leave_is_paid"
									checked>
								<span>Is Paid</span>
							</label>
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_leave_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered" id="xin_table_leave_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_leave_type'); ?></th>
										<th>Is Paid</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="warning_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_warning_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'warning_type_info', 'id' => 'warning_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_warning_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/warning_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_warning_type'); ?></label>
							<input type="text" class="form-control" name="warning_type"
								placeholder="<?php echo $this->lang->line('xin_warning_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_warning_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_warning_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_warning_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="termination_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_termination_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'termination_type_info', 'id' => 'termination_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_termination_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/termination_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_termination_type'); ?></label>
							<input type="text" class="form-control" name="termination_type"
								placeholder="<?php echo $this->lang->line('xin_termination_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_termination_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_termination_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_termination_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="expense_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_expense_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'expense_type_info', 'id' => 'expense_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_expense_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/expense_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="company_name"><?php echo $this->lang->line('module_company_title'); ?></label>
							<select class="form-control" name="company" id="aj_company" data-plugin="select_hrm"
								data-placeholder="<?php echo $this->lang->line('module_company_title'); ?>">
								<option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
								<?php foreach ($all_companies as $company) { ?>
									<option value="<?php echo $company->company_id; ?>"> <?php echo $company->name; ?>
									</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_expense_type'); ?></label>
							<input type="text" class="form-control" name="expense_type"
								placeholder="<?php echo $this->lang->line('xin_expense_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_expense_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_expense_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('left_company'); ?></th>
										<th><?php echo $this->lang->line('xin_expense_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($moduleInfo[0]->module_recruitment == 'true') { ?>
		<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="job_type" style="display:none;">
			<div class="row">
				<div class="col-md-5">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
								<?php echo $this->lang->line('xin_job_type'); ?> </h3>
						</div>
						<div class="box-body">
							<?php $attributes = array('name' => 'job_type_info', 'id' => 'job_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
							<?php $hidden = array('set_job_type' => 'UPDATE'); ?>
							<?php echo form_open('admin/settings/job_type_info/', $attributes, $hidden); ?>
							<div class="form-group">
								<label for="name"><?php echo $this->lang->line('xin_job_type'); ?></label>
								<input type="text" class="form-control" name="job_type"
									placeholder="<?php echo $this->lang->line('xin_job_type'); ?>">
							</div>
							<div class="form-actions box-footer">
								<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
									<?php echo $this->lang->line('xin_save'); ?> </button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
								<?php echo $this->lang->line('xin_job_type'); ?> </h3>
						</div>
						<div class="box-body">
							<div class="box-datatable table-responsive">
								<table class="datatables-demo table table-striped table-bordered" id="xin_table_job_type">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('xin_action'); ?></th>
											<th><?php echo $this->lang->line('xin_job_type'); ?></th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="job_categories" style="display:none;">
			<div class="row">
				<div class="col-md-5">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
								<?php echo $this->lang->line('xin_rec_job_category'); ?> </h3>
						</div>
						<div class="box-body">
							<?php $attributes = array('name' => 'job_category_info', 'id' => 'job_category_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
							<?php $hidden = array('set_job_type' => 'UPDATE'); ?>
							<?php echo form_open('admin/settings/job_category_info/', $attributes, $hidden); ?>
							<div class="form-group">
								<label for="job_category"><?php echo $this->lang->line('xin_rec_job_category'); ?></label>
								<input type="text" class="form-control" name="job_category"
									placeholder="<?php echo $this->lang->line('xin_rec_job_category'); ?>">
							</div>
							<div class="form-actions box-footer">
								<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
									<?php echo $this->lang->line('xin_save'); ?> </button>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
								<?php echo $this->lang->line('xin_rec_job_categories'); ?> </h3>
						</div>
						<div class="box-body">
							<div class="box-datatable table-responsive">
								<table class="datatables-demo table table-striped table-bordered"
									id="xin_table_job_category">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('xin_action'); ?></th>
											<th><?php echo $this->lang->line('xin_rec_job_category'); ?></th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="exit_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_exit_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'exit_type_info', 'id' => 'exit_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_exit_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/exit_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_employee_exit_type'); ?></label>
							<input type="text" class="form-control" name="exit_type"
								placeholder="<?php echo $this->lang->line('xin_exit_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_exit_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered" id="xin_table_exit_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_employee_exit_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="travel_arr_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_travel_arrangement_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'travel_arr_type_info', 'id' => 'travel_arr_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_travel_arr_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/travel_arr_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_travel_arrangement_type'); ?></label>
							<input type="text" class="form-control" name="travel_arr_type"
								placeholder="<?php echo $this->lang->line('xin_travel_arrangement_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_travel_arrangement_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_travel_arr_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_travel_arrangement_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="currency_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_currency_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'currency_type_info', 'id' => 'currency_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_currency_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/currency_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_currency_name'); ?></label>
							<input type="text" class="form-control" name="name"
								placeholder="<?php echo $this->lang->line('xin_currency_name'); ?>">
						</div>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_currency_code'); ?></label>
							<input type="text" class="form-control" name="code"
								placeholder="<?php echo $this->lang->line('xin_currency_code'); ?>">
						</div>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_currency_symbol'); ?></label>
							<input type="text" class="form-control" name="symbol"
								placeholder="<?php echo $this->lang->line('xin_currency_symbol'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_currencies'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_currency_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_name'); ?></th>
										<th><?php echo $this->lang->line('xin_code'); ?></th>
										<th><?php echo $this->lang->line('xin_symbol'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="company_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_company_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'company_type_info', 'id' => 'company_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_company_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/company_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_company_type'); ?></label>
							<input type="text" class="form-control" name="company_type"
								placeholder="<?php echo $this->lang->line('xin_company_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_company_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_company_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_company_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="packing_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_packing_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'packing_type_info', 'id' => 'packing_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_packing_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/packing_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_packing_type'); ?></label>
							<input type="text" class="form-control" name="packing_type"
								placeholder="<?php echo $this->lang->line('xin_packing_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_packing_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_packing_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_packing_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="gst" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_gst'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'gst_info', 'id' => 'gst_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_gst' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/gst_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_gst'); ?></label>
							<input type="text" class="form-control" name="gst"
								placeholder="<?php echo $this->lang->line('xin_gst'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_gst'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered" id="xin_table_gst">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_gst'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="payment_term" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_payment_term'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'payment_term_info', 'id' => 'payment_term_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_payment_term' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/payment_term_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_payment_term'); ?></label>
							<input type="text" class="form-control" name="payment_term"
								placeholder="<?php echo $this->lang->line('xin_payment_term'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_payment_term'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_payment_term">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_payment_term'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="shipping_term" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> Add New Shipping Term</h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'shipping_term_info', 'id' => 'shipping_term_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_shipping_term' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/shipping_term_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name">Shipping Term</label>
							<input type="text" class="form-control" name="shipping_term"
								placeholder="Shipping Term">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> List All Shipping Term</h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_shipping_term">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo "Shipping Term"; ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="units" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_unit'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'unit_info', 'id' => 'unit_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_unit' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/unit_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_unit'); ?></label>
							<input type="text" class="form-control" name="unit"
								placeholder="<?php echo $this->lang->line('xin_unit'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_unit'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered" id="xin_table_unit">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_unit'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="ethnicity_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_ethnicity_type_title'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'ethnicity_type_info', 'id' => 'ethnicity_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_ethnicity_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/ethnicity_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label
								for="ethnicity_type"><?php echo $this->lang->line('xin_ethnicity_type_title'); ?></label>
							<input type="text" class="form-control" name="ethnicity_type"
								placeholder="<?php echo $this->lang->line('xin_ethnicity_type_title'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_ethnicity_type_title'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_ethnicity_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_ethnicity_type_title'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="income_type" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_income_type'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'income_type_info', 'id' => 'income_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_ethnicity_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/income_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="income_type"><?php echo $this->lang->line('xin_income_type'); ?></label>
							<input type="text" class="form-control" name="income_type"
								placeholder="<?php echo $this->lang->line('xin_income_type'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_income_type'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_income_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_income_type'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="security_level" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_security_level'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'security_level_info', 'id' => 'security_level_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_security_level' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/security_level_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_security_level'); ?></label>
							<input type="text" class="form-control" name="security_level"
								placeholder="<?php echo $this->lang->line('xin_security_level'); ?>">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_security_level'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_security_level">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_security_level'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="term_condition_level" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							<?php echo $this->lang->line('xin_term_condition'); ?> </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'term_condition_info', 'id' => 'term_condition_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_term_condition_level' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/term_level_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_term_condition'); ?></label>
							<input type="text" class="form-control" name="term_condition_title"
								placeholder="<?php echo $this->lang->line('xin_term_condition'); ?>">
						</div>
						<div class="form-group">
							<label for="name"><?php echo $this->lang->line('xin_description'); ?></label>
							<textarea class="form-control" name="term_condition_description"
								placeholder="<?php echo $this->lang->line('xin_description'); ?>"></textarea>
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							<?php echo $this->lang->line('xin_term_condition'); ?> </h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_term_condition_level">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th><?php echo $this->lang->line('xin_term_condition'); ?></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="project" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							Project Type </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'project_type_info', 'id' => 'project_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_project_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/project_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name">Project Type</label>
							<input type="text" class="form-control" name="project_type"
								placeholder="Project Type">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							Project Type</h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_project_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th>Project Type</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="purpose" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							Purchase Purpose </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'purpose_type_info', 'id' => 'purpose_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_purpose_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/purchase_purpose_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name">Purchase Purpose</label>
							<input type="text" class="form-control" name="purchase_type"
								placeholder="Purchase Purpose">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							Purchase Purpose</h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="xin_table_purpose_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th>Purchase Purpose</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="delivery" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							Delivery </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'purpose_type_info', 'id' => 'delivery_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_purpose_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/delivery_time_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name">Delivery</label>
							<input type="text" class="form-control" name="delivery_type"
								placeholder="Delivery Week">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							Delivery Weeks</h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="delivery_type_list">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th>Delivery Weeks</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="ptype" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_add_new'); ?>
							Payment Types </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'pay_type_info', 'id' => 'pay_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_purpose_type' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/pay_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name">Payment Types</label>
							<input type="text" class="form-control" name="pay_type"
								placeholder="Payment Types">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
							Payment Types</h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered"
								id="Pay_type_list">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th>Types</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="modeoftransport" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"> Mode of Transport </h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'mode_of_transport_info', 'id' => 'mode_of_transport_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_mode_of_transport_info' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/mode_of_transport_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name">Transport Mode</label>
							<input type="text" class="form-control" name="transport_mode"
								placeholder="Transport Mode">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Mode of Transport</h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered" id="mode_of_transport">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th>Transport Title</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 current-tab <?php echo $get_animate; ?>" id="loantype" style="display:none;">
		<div class="row">
			<div class="col-md-5">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Loan Type</h3>
					</div>
					<div class="box-body">
						<?php $attributes = array('name' => 'loan_type_info', 'id' => 'loan_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1 add'); ?>
						<?php $hidden = array('set_mode_of_transport_info' => 'UPDATE'); ?>
						<?php echo form_open('admin/settings/loan_type_info/', $attributes, $hidden); ?>
						<div class="form-group">
							<label for="name">Loan Type</label>
							<input type="text" class="form-control" name="loan_type"
								placeholder="Loan Type">
						</div>
						<div class="form-actions box-footer">
							<button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
								<?php echo $this->lang->line('xin_save'); ?> </button>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Loan Type</h3>
					</div>
					<div class="box-body">
						<div class="box-datatable table-responsive">
							<table class="datatables-demo table table-striped table-bordered" id="loan_type">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('xin_action'); ?></th>
										<th>Loan Type</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



</div>
<div class="modal  edit_setting_datail" id="edit_setting_datail" tabindex="-1" role="dialog"
	aria-labelledby="edit-modal-data" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="ajax_setting_info">
		</div>
	</div>
</div>
<style type="text/css">
	.table-striped {
		width: 100% !important;
	}
</style>