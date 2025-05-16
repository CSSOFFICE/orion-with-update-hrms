<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<div class="box <?php echo $get_animate; ?>">
	<div class="box-header with-border">
		<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?> Debtor/Creditor Data</h3>
	</div>
	<div class="box-body">
		<div class="box-datatable table-responsive">
			<table class="datatables-demo table table-striped table-bordered" id="xin_table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>CVB</th>
						<th>CCB</th>
						<th>Price Type</th>
						<th>Hot Number</th>
						<th>Cash Balance</th>
						<th>Credit Balance</th>
						<th>Vendor Balance</th>
						<th>InActive</th>					
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

