<style>
	[type="checkbox"]:checked+label:before {
		display: none;
	}

	[type="checkbox"]:checked+label {
		padding-left: 12px;

	}

	[type="checkbox"].filled-in:checked.chk-col-light-blue+label:after {
		display: none;
	}

	#add_form {
		height: 100% !important;
	}
</style>
<?php
/* Purchase view
*/
?>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('3102', $role_resources_ids)) { ?>


    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>
                    <?php echo $this->lang->line('xin_invoices_title'); ?></h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a> </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_quotation', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('user_id' => $session['user_id']); ?>
                    <?php echo form_open_multipart('admin/finance/add_invoice', $attributes, $hidden); ?>
                    <div class="form-body">
                        <div class="row">
                            <input type="hidden" name="contract_sum" id="contract_sum">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="project_id"><?php echo $this->lang->line('xin_project'); ?>
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select class="form-control" name="project_id" id="project_id" data-plugin="select_hrm" data-placeholder="Select Project">
                                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                        <?php foreach ($get_all_projects as $project) { ?>
                                            <option value="<?php echo $project->project_id; ?>">
                                                <?php echo $project->project_title; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="qt_no">Quotation No
                                        <!-- <i class="hrsale-asterisk">*</i> -->
                                    </label>
                                    <input type="text" id="qt_no" class="form-control" readonly />
                                    <input type="hidden" id="qt_id" name="qt_id" />
                                    <input type="hidden" id="qt_nos" name="qt_nos" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>DO Number</label>
                                    <input type="text" name="m_do_no" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Order Number</label>
                                    <input type="text" name="m_order_no" id="m_order_no" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="project_id"><?php echo $this->lang->line('xin_customer'); ?>
                                        <i class="hrsale-asterisk">*</i>
                                    </label>
                                    <select class="form-control" name="client_id" id="client_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                        <?php foreach ($all_customer as $crm) { ?>
                                            <option value="<?php echo $crm->client_id; ?>">
                                                <?php echo ($crm->f_name) ?? $crm->client_company_name; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Billing Address</label>
                                    <select class="form-control" placeholder="Billing Address" id="bill_address" name="bill_address" data-plugin="select_hrm">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Delivery Address</label>
                                    <select class="form-control" placeholder="Delivery Address" id="delivery_address" name="delivery_address" data-plugin="select_hrm">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="terms">Invoice Date<i class="hrsale-asterisk">*</i></label>
                                    <input class="form-control date" type="text" id="invoice_date" name="invoice_date" placeholder="Invoice Date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_term"><?php echo $this->lang->line('xin_payment_term'); ?></label>
                                    <select class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_term'); ?>" name="payment_term" id="terms" data-plugin="select_hrm">
                                        <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                        <?php foreach ($all_payment_terms->result() as $payment_terms) { ?>
                                            <option value="<?php echo $payment_terms->payment_term_id; ?>"><?php echo $payment_terms->payment_term; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="terms">Due Date<i class="hrsale-asterisk">*</i></label>
                                    <input type="text" name="due_date" id="due_date" class="form-control date" placeholder="Invoice Due Date">
                                </div>
                            </div>

                        </div>
                        <div class="row">
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
                                        
                                    </label>
                                    <select class="form-control" name="task" id="task" data-plugin="select_hrm" data-placeholder="Select Task">

                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="p-20">
                            <div class="table-responsive my-3 purchaseTable">
                                <table class="table" id="data_table" border="1">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Item</th>
                                            <!-- <th>Job Description</th> -->
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th></th>
                                            <!-- Add more headers as needed -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <button id="add_product" type="button" style="border-radius:50px; padding:5px;" class="btn btn-success">Add Product</button>
                                        <button id="add_blank" type="button" style="border-radius:50px; padding:3px; margin:5px;" class="btn btn-info">Add Blank Line</button>
                                        <p id="sum_over"></p>
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <label>Sub Total</label>
                                    <input type="text" class="form-control" id="sub_t" name="sub_t" readonly>
                                    <input type="checkbox" id="inclusive_gst" name="inclusive_gst" checked>
                                    <label for="inclusive_gst">Inclusive GST</label><br>
                                    <div id="gst_box">
                                        <label>GST</label>
                                        <select class="form-control" id="order_gst2" name="order_gst2">
                                            <option>Select</option>
                                            <?php $all_gst = $this->db->get('xin_gst')->result();
                                            foreach ($all_gst as $gst) { ?>
                                                <option value="<?php echo $gst->gst ?>"><?php echo $gst->gst ?></option>
                                            <?php } ?>
                                        </select>

                                        <label>GST Value</label>
                                        <input type="text" class="form-control" id="g_val" name="g_val" readonly>
                                    </div>
                                    <div id="gst_box1">
                                        <?php $def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result() ?>
                                        <label>Inclusive GST Value (<?php echo $def_gst[0]->d_gst ?> %)</label>
                                        <input type="text" class="form-control" id="d_gst_i" name="d_gst_i" readonly>
                                    </div>
                                    <!-- <label>Discount (%)</label>
                                                <input type="text" class="form-control" id="discount2" name="discount2"> -->

                                    <label>Total</label>
                                    <input type="text" class="form-control" id="t" name="t" readonly>
                                </div>
                            </div>


                        </div>
                        <div class="row">


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="terms">Terms & Condition</label>

                                    <script src="<?php echo base_url('assets/ckeditor.js') ?>"></script>
                                    <?php $query = $this->db->get('xin_system_setting')->result(); ?>
                                    <textarea name="cterm" class="form-control" id="editor1" cols="30" rows="10"><?php echo $query[0]->invoice_terms_condition ?></textarea>
                                    <script>
                                        ClassicEditor.create(document.querySelector('#editor1')).then(editor => {
                                            console.log(editor);
                                        }).catch(error => {
                                            console.error(error);
                                        });
                                    </script>
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
        </div>
    </div>

<?php } ?>
<div class="box <?php echo $get_animate; ?>">
	<div class="box-header with-border">
		<h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
			<?php echo $this->lang->line('xin_invoices_title'); ?> </h3>
	</div>
	<div class="box-body">
		<div class="box-datatable table-responsive">
			<table class="datatables-demo table table-striped table-bordered" id="xin_table">
				<thead>
					<tr>
						<th><?php echo $this->lang->line('xin_action'); ?></th>
						<th>Invoice No</th>
						<th><?php echo $this->lang->line('xin_project'); ?></th>
						<th><?php echo $this->lang->line('xin_customer'); ?></th>
						<th><?php echo $this->lang->line('xin_created_date'); ?></th>
						<!-- <th>Status</th> -->


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
		$('.date').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: '1900:' + new Date().getFullYear()
		});

		$("#terms").on("change", function() {
			var invoice_date = $("#invoice_date").val();

			var term_text = $("#terms option:selected").text();
			term_text = parseInt(term_text.replace("days", ""));

			var date = new Date(invoice_date.split("-").reverse().join("-"));
			//alert(date);return false;
			date.setDate(date.getDate() + term_text);
			// alert(date);return false;
			var month = date.getMonth() + 1;
			var day = date.getDate();

			var output = (day < 10 ? '0' : '') + day + '-' + (month < 10 ? '0' : '') + month + '-' + date.getFullYear();
			$('#due_date').val(output);


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
				url: "<?php echo base_url() . 'admin/Finance/get_task_from_milestone/'; ?>" + milestone_id+ "/" + project_id,
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

		$('#client_id').on('change', function() {
			let clientId = $(this).val();

			$.ajax({
				url: "<?php echo base_url() . 'admin/Finance/get_client_address/'; ?>" + clientId,
				type: "POST",
				success: function(response) {
					let data = JSON.parse(response); // Parse the JSON response
					let billingOptions = '';
					let deliveryOptions = '';

					// Populate billing addresses
					if (data.billing_address.length > 0) {
						$.each(data.billing_address, function(index, address) {
							let fullAddress = '';

							if (address.street) fullAddress += `${address.street}\n`; // Add street if not null
							if (address.state) fullAddress += `${address.state}\n`; // Add state if not null
							if (address.city) fullAddress += `${address.city}\n`; // Add city if not null
							if (address.zipcode) fullAddress += `${address.zipcode}`; // Add zipcode if not null

							billingOptions += `<option value="${address.id}">${fullAddress.trim()}</option>`;
						});
					} else {
						billingOptions = '<option>No billing addresses available</option>';
					}


					// Populate delivery addresses
					if (data.shipping_address.length > 0) {
						$.each(data.shipping_address, function(index, address) {
							let fullAddress = '';

							if (address.street) fullAddress += `${address.street}\n`; // Add street if not null
							if (address.state) fullAddress += `${address.state}\n`; // Add state if not null
							if (address.city) fullAddress += `${address.city}\n`; // Add city if not null
							if (address.zipcode) fullAddress += `${address.zipcode}`; // Add zipcode if not null

							deliveryOptions += `<option value="${address.id}">${fullAddress.trim()}</option>`;
						});
					} else {
						deliveryOptions = '<option>No delivery addresses available</option>';
					}

					// Append options to the select elements
					$('#bill_address').html(billingOptions);
					$('#delivery_address').html(deliveryOptions);
				}
			});
		});

		// Add Product button click event
		$(document).on("click", "#add_product", function() {
			var rowCount = $("#data_table tbody tr").length + 1;
			var rowCount1 = $("#data_table tbody tr").length;
			// Initialize the select element with an opening <select> tag
			let selectElement = `<select name="u_item[${rowCount1}]" id="u_item_${rowCount}" class="form-control">`;
			selectElement += `<option value="">Select Product</option>`;

			$.ajax({
				url: '<?php echo base_url('admin/Finance/getProducts') ?>',
				type: "POST",
				dataType: "json",
				success: function(response) {
					// Populate the select options dynamically
					$.each(response, function(key, value) {
						selectElement += `<option value="${value.product_id}">${value.product_name}</option>`;
					});
					selectElement += `</select>`; // Closing the <select> tag

					// Append the new row to the table
					$("#data_table tbody").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>${selectElement}
                                <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='product'>  
                                <input type='hidden' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount1}]'>
                            </td>
                            <td><input type='text' class='form-control' name='quantity[]' id='quantity_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='unit[]' id='unit_${rowCount}'></td>
                            <td><input type='text' class='form-control' name='rate[]' id='rate_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='total[]' id='total_${rowCount}' readonly></td>
                            <td>
                                <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                            </td>
                        </tr>
                    `);
				}
			});
		});

		// Add Blank Line button click event
		$(document).on("click", "#add_blank", function() {
			var rowCount = $("#data_table tbody tr").length + 1;
			var rowCount5 = $("#data_table tbody tr").length;

			$("#data_table tbody").append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>
                                <input type='text' class='form-control' id="item_description_${rowCount}" name='item_description[${rowCount5}]'>
                                <input type='hidden' class='form-control' name='type[]' id='type_${rowCount}' value='plain'>   
                                <input type='hidden' class='form-control' id="u_item_${rowCount}" name='u_item[${rowCount5}]'>
                            </td>
                            <td><input type='text' class='form-control' name='quantity[]' id='quantity_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='unit[]' id='unit_${rowCount}'></td>
                            <td><input type='text' class='form-control' name='rate[]' id='rate_${rowCount}' oninput='updateRowTotal(this)'></td>
                            <td><input type='text' class='form-control' name='total[]' id='total_${rowCount}' readonly></td>
                            <td>
                                <button type="button" name="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                            </td>
                        </tr>
                    `);
		});



	});

	function updateRowTotal(element) {
		var $row = $(element).closest('tr');
		var rate = parseFloat($row.find("input[name='rate[]']").val());

		if ($row.find("input[name='timers[]']").val() == 'time') {
			var hrs = parseFloat($row.find("input[name='hours[]']").val());
			var mins = parseFloat($row.find("input[name='mins[]']").val());
			var totalHours = parseFloat(hrs + "." + (mins < 10 ? "0" + mins : mins)); // Combine hours and minutes into float
			var total = totalHours * rate;
			$row.find("input[name='total[]']").val(total.toFixed(2));
		} else {
			var quantity = parseFloat($row.find("input[name='quantity[]']").val());
			var total = quantity * rate;
			$row.find("input[name='total[]']").val(total.toFixed(2));
		}

		calculateSubTotal();
	}


	function calculateSubTotal() {
		var subTotal = 0;
		$("input[name='total[]']").each(function() {
			subTotal += parseFloat($(this).val()) || 0;
		});
		$("#sub_t").val(subTotal.toFixed(2));
		calculateTotal();
	}

	function calculateTotal() {
		var subTotal = parseFloat($("#sub_t").val()) || 0;
		var discount = parseFloat($("#discount2").val()) || 0;
		var gst = parseFloat($("#order_gst2").val()) || 0;
		var gst1 = parseFloat("<?php echo $def_gst[0]->d_gst ?>");
		var isInclusive = $("#inclusive_gst").is(":checked");

		var discountedSubTotal = subTotal - discount;
		let contractSum = parseFloat($("#contract_sum").val()) || 0;

		// Clear existing alert
		$("#sum_over").empty();

		if (isInclusive) {
			gst1 = parseFloat(gst1);
            subTotal = parseFloat(subTotal);
            // Calculate GST value when GST is inclusive
            var gstValue = parseFloat((subTotal / (100 + gst1)) * gst1);

			$("#d_gst_i").val(gstValue.toFixed(2));
			$("#t").val(discountedSubTotal.toFixed(2));
			$("#grand_total").val(discountedSubTotal.toFixed(2));

			// Check if the total exceeds the contract sum
			if (discountedSubTotal > contractSum) {
				let alertHtml = `<div class="alert alert-warning" role="alert">Project Total Exceded</div>`;
				$("#sum_over").append(alertHtml);
			}
		} else {
			var gstValue = discountedSubTotal * (gst / 100);
			var total = discountedSubTotal + gstValue;

			$("#g_val").val(gstValue.toFixed(2));
			$("#t").val(total.toFixed(2));
			$("#grand_total").val(total.toFixed(2));

			// Check if the total exceeds the contract sum
			if (total > contractSum) {
				let alertHtml = `<div class="alert alert-warning" role="alert">Project Total Exceded</div>`;
				$("#sum_over").append(alertHtml);
			}
		}
	}


	function toggleGSTInput() {
		var isInclusive = $("#inclusive_gst").is(":checked");
		if (isInclusive) {
			$("#gst_box").hide();
			$("#gst_box1").show();
		} else {
			$("#gst_box").show();
			$("#gst_box1").hide();

		}
	}

	$(document).ready(function() {
		$("#inclusive_gst").change(function() {
			toggleGSTInput();
			calculateTotal();
		});

		$("#order_gst2, #discount2").change(function() {
			calculateTotal();
		});

		toggleGSTInput();
	});

	function updateUnit(selectElement, item) {
		var productId = selectElement.value;
		$.ajax({
			url: "<?php echo base_url() . 'admin/Finance/get_prod_details/'; ?>" + productId,
			type: "POST",
			success: function(response) {
				// Assuming response contains the product details
				var selectedProduct = response.products.find(product => product.product_id == productId);
				// Update the unit field
				if (selectedProduct) {
					$('#unit_' + item).val(selectedProduct.std_uom);
					$('#rate_' + item).val(selectedProduct.sell_p);
				}
			}
		});
	}

	$(document).on('click', '.remove-input-field', function() {
		$(this).closest('tr').remove();
		calculateSubTotal();
	});
</script>
