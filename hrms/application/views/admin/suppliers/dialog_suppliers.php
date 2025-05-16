<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['supplier_id']) && $_GET['data'] == 'supplier') {

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
		<h4 class="modal-title" id="edit-modal-data">Edit <?php echo $this->lang->line('xin_suppliers'); ?></h4>
	</div>
	<?php $attributes = array('name' => 'edit_supplier', 'id' => 'edit_supplier', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['supplier_id'], 'ext_name' => $supplier_name); ?>
	<?php echo form_open('admin/supplier/update/' . $_GET['supplier_id'], $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="row">
			<input type="hidden" name="supplier_id" value="<?php echo $_GET['supplier_id']; ?>">
			<div id="details">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" class="form-control" name="supplier_name" value="<?php echo $supplier_name ?>" placeholder="Name of Supplier">
							<input type="hidden" name="old_name" value="<?php echo $supplier_name ?>">
							<input type="hidden" name="old_code" value="<?php echo $code ?>">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="name">Terms</label>
							<?php $term = $this->db->get('xin_payment_term')->result() ?>
							<select name="sup_terms1" class="form-control" data-plugin="select_hrm" data-placeholder="Terms">
								<option value="">Select</option>
								<?php foreach ($term as $terms) { ?>
									<option value="<?php echo $terms->payment_term ?>" <?php if ($supplier_terms == $terms->payment_term) {
																							echo "selected";
																						} ?>><?php echo $terms->payment_term ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>GST Supplier </label>
							<select name="gst_sup1" class="form-control" data-plugin="select_hrm" data-placeholder="GST Supplier">
								<option value="">Select</option>
								<option value="Yes" <?php if ($supplier_gst == "Yes") {
														echo "selected";
													} ?>>Yes</option>
								<option value="No" <?php if ($supplier_gst == "No") {
														echo "selected";
													} ?>>No</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input type="checkbox" name="subcontractor1" id="subcontractor1" value="Yes" <?php if ($subcon_supplier == "Yes") {
																												echo "checked";
																											} ?>>
							<label for="subcontractor1">Subcontractor</label>

						</div>
					</div>
					<div class="col-md-6">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<nav class="ic-customer-details-tab">
							<div class="nav nav-tabs" id="myTab1" role="tablist">
								<a class="nav-item nav-link active" id="nav-billing_address-tab1" data-toggle="tab" href="#nav-billing_address1" role="tab" aria-controls="nav-billing_address1" aria-selected="true">Billing Address</a>
								<!-- <a class="nav-item nav-link" id="nav-delivery_address-tab1" data-toggle="tab" href="#nav-delivery_address1" role="tab" aria-controls="nav-delivery_address1" aria-selected="false">Shipping Address</a> -->
							</div>
						</nav>

						<div class="tab-content">
							<!-- Billing Address Tab -->
							<div class="tab-pane fade show active" id="nav-billing_address1" role="tabpanel" aria-labelledby="nav-billing_address-tab1">
								<div class="row">
									<div class="col">
										<label class="text-muted">Billing Address</label>
									</div>
									<div class="col-auto">
										<button type="button" class="btn btn-success" onclick="addBillRow()">Add +</button>
									</div>
								</div>
								<div id="billing_addr_group1">
									<?php foreach ($billing_address as $bill) { ?>
										<div class="row custom_billing_addr1 billing_addr_row1" style="margin-bottom: 20px;">
											<div class="form-group col-sm-6">
												<label>Person In Charge <span class="error">*</span></label>
												<input type="text" name="billing_addr_pic1[]" class="form-control" value="<?= $bill->pic ?>">
											</div>
											<div class="form-group col-sm-6">
												<label>Contact Number <span class="error">*</span></label>
												<input type="text" name="billing_addr_contant_number1[]" class="form-control" value="<?= $bill->contact ?>">
											</div>
											<div class="form-group col-sm-6">
												<label>Postal <span class="error">*</span></label>
												<input type="text" name="billing_addr_zipcode1[]" class="form-control" value="<?= $bill->zipcode ?>" onchange="get_address_from_postalcode1(this, 'billing_addr_row1', 'billing_address')">
											</div>
											<div class="form-group col-sm-6">
												<label>Address <span class="error">*</span></label>
												<textarea name="billing_address1[]" class="form-control"><?= $bill->address ?></textarea>
											</div>
											<div class="form-group col-lg-6">
												<label>Email</label>
												<input type="email" name="billing_addr_email1[]" class="form-control" value="<?= $bill->email ?>">
											</div>
											<div class="form-group col-sm-1" style="margin-top: 32px;">
												<a class="btn btn-danger billing_addr_remove_btn1"><i class="fa fa-times"></i></a>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>

							<!-- Shipping Address Tab -->
							<div class="tab-pane fade" id="nav-delivery_address1" role="tabpanel" aria-labelledby="nav-delivery_address-tab1">
								<div class="row">
									<div class="col">
										<label class="text-muted">Shipping Address</label>
									</div>
									<div class="col-auto">
										<button type="button" class="btn btn-success" id="add_delivery_addr_btn1">Add +</button>
									</div>
								</div>
								<div id="delivery_addr_group1">
									<?php foreach ($shipping_address as $ship) { ?>
										<div class="row delivery_addr_row1 custom_delivery_addr1" style="margin-bottom: 20px;">
											<div class="form-group col-sm-6">
												<label>Person In Charge <span class="error">*</span></label>
												<input type="text" name="shipping_addr_pic1[]" class="form-control" value="<?= $ship->pic ?>">
											</div>
											<div class="form-group col-sm-6">
												<label>Contact Number <span class="error">*</span></label>
												<input type="text" name="shipping_addr_contant_number1[]" class="form-control" value="<?= $ship->contact ?>">
											</div>
											<div class="form-group col-sm-6">
												<label>Postal <span class="error">*</span></label>
												<input type="text" name="shipping_addr_zipcode1[]" class="form-control" value="<?= $ship->zipcode ?>" onchange="get_address_from_postalcode1(this, 'delivery_addr_row1', 'shipping_address')">
											</div>
											<div class="form-group col-sm-6">
												<label>Address <span class="error">*</span></label>
												<textarea name="shipping_address1[]" class="form-control"><?= $ship->address ?></textarea>
											</div>
											<div class="form-group col-lg-6">
												<label>Email</label>
												<input type="email" name="shipping_addr_email1[]" class="form-control" value="<?= $ship->email ?>">
											</div>
											<div class="form-group col-sm-1" style="margin-top: 32px;">
												<a class="btn btn-danger delivery_addr_remove_btn1"><i class="fa fa-times"></i></a>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th>Sl</th>
						<th>Item</th>
						<th>Description</th>
						<th>Price </th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody class="AddItem1" id="vendor_items_table1">
					<?php $i = 0;
					foreach ($all_items as $items) {
						$i++; ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<select class="packing_dropdown form-control select22" name="u_item[]" id="u_item<?php echo $i; ?>" onchange="getProductDetail(<?php echo $i; ?> )">
									<option value="">Select product</option>
									<?php foreach ($all_products as $product) { ?>
										<option value="<?php echo $product->product_id ?>" <?php if ($product->product_id == $items->supplier_item_name) {
																								echo "selected";
																							} ?>><?php echo $product->product_name ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<textarea class="form-control" name="u_description[]" id="u_description<?php echo $i; ?>"><?= $items->supplier_item_description ?></textarea>
							</td>
							<td>
								<input class="form-control" type="text" value="<?= $items->supplier_item_price ?>" name="u_price[]" id="u_price_<?php echo $i; ?>">
							</td>
							<td>
								<button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
							</td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th style="border: none !important;">
							<a href="javascript:void(0)" class="btn-sm btn-success addButton" id="addButton2" onclick="addRow()">Add</a>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
	</div>
	<?php echo form_close(); ?>
	<script>
		$('#myTab1 a').on('click', function(event) {
			event.preventDefault()
			var a = $(this).attr('href');
			console.log($(a).parents('.tab-content').get(0));

			$(a).parents('.tab-content').find('.show').removeClass('show');
			$(a).parents('.tab-content').find('.active').removeClass('active');
			$(a).parents('.tab-content').find('.test').removeClass('test');

			$(a).addClass('show');
			//   $(a).addClass('active');
			$(a).addClass('test');
			//   $('#myTab a[data-target="#profile"]').addClass('show') // Select tab by name
			//   $('#myTab a[data-target="#profile"]').addClass('active') // Select tab by name

		})

		function get_address_from_postalcode1(el, parents_class, target_class) {
			var postal_code = $(el).val();

			$.ajax({
				type: "get",
				url: "https://www.onemap.gov.sg/api/common/elastic/search?searchVal=" + postal_code + "&returnGeom=Y&getAddrDetails=Y&pageNum=1",
				success: function(response) {
					console.log(response);

					if (response.found != 0) {
						var address = response.results[0].ADDRESS;

						$(el).parents('.' + parents_class).find('.' + target_class).val(address);
					} else {
						$(el).parents('.' + parents_class).find('.' + target_class).val("");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}
		$('#myTab1 a').on('click', function(event) {
			event.preventDefault();
			$('.tab-pane').removeClass('show active');
			$($(this).attr('href')).addClass('show active');
			$('#myTab1 a').removeClass('active');
			$(this).addClass('active');
		});

		function get_address_from_postalcode1(el, parents_class, target_class) {
			let postal_code = $(el).val();
			$.ajax({
				type: "get",
				url: `https://www.onemap.gov.sg/api/common/elastic/search?searchVal=${postal_code}&returnGeom=Y&getAddrDetails=Y&pageNum=1`,
				success: function(response) {
					if (response.found !== 0) {
						let address = response.results[0].ADDRESS;
						$(el).closest('.' + parents_class).find('.' + target_class).val(address);
					} else {
						$(el).closest('.' + parents_class).find('.' + target_class).val("");
					}
				}
			});
		}

		$('body').on('click', '.billing_addr_remove_btn1', function() {
			$(this).closest('.custom_billing_addr1').remove();
		});

		$('body').on('click', '.delivery_addr_remove_btn1', function() {
			$(this).closest('.custom_delivery_addr1').remove();
		});

		function addBillRow() {
			const html = `
		<div class="row custom_billing_addr1 billing_addr_row1" style="margin-bottom: 20px;">
			<div class="form-group col-sm-6">
				<label>Person In Charge <span class="error">*</span></label>
				<input type="text" name="billing_addr_pic1[]" class="form-control">
			</div>
			<div class="form-group col-sm-6">
				<label>Contact Number <span class="error">*</span></label>
				<input type="text" name="billing_addr_contant_number1[]" class="form-control">
			</div>
			<div class="form-group col-sm-6">
				<label>Postal <span class="error">*</span></label>
				<input type="text" name="billing_addr_zipcode1[]" class="form-control" onchange="get_address_from_postalcode1(this, 'billing_addr_row1', 'billing_address')">
			</div>
			<div class="form-group col-sm-6">
				<label>Address <span class="error">*</span></label>
				<textarea name="billing_address1[]" class="form-control billing_address"></textarea>
			</div>
			<div class="form-group col-lg-6">
				<label>Email</label>
				<input type="email" name="billing_addr_email1[]" class="form-control">
			</div>
			<div class="form-group col-sm-1" style="margin-top: 32px;">
				<a class="btn btn-danger billing_addr_remove_btn1"><i class="fa fa-times"></i></a>
			</div>
		</div>`;
			$("#billing_addr_group1").append(html);
		}

		$('#add_delivery_addr_btn1').on('click', function() {
			const html = `
		<div class="row delivery_addr_row1 custom_delivery_addr1" style="margin-bottom: 20px;">
			<div class="form-group col-sm-6">
				<label>Person In Charge <span class="error">*</span></label>
				<input type="text" name="shipping_addr_pic1[]" class="form-control">
			</div>
			<div class="form-group col-sm-6">
				<label>Contact Number <span class="error">*</span></label>
				<input type="text" name="shipping_addr_contant_number1[]" class="form-control">
			</div>
			<div class="form-group col-sm-6">
				<label>Postal <span class="error">*</span></label>
				<input type="text" name="shipping_addr_zipcode1[]" class="form-control" onchange="get_address_from_postalcode1(this, 'delivery_addr_row1', 'shipping_address')">
			</div>
			<div class="form-group col-sm-6">
				<label>Address <span class="error">*</span></label>
				<textarea name="shipping_address1[]" class="form-control shipping_address"></textarea>
			</div>
			<div class="form-group col-lg-6">
				<label>Email</label>
				<input type="email" name="shipping_addr_email1[]" class="form-control">
			</div>
			<div class="form-group col-sm-1" style="margin-top: 32px;">
				<a class="btn btn-danger delivery_addr_remove_btn1"><i class="fa fa-times"></i></a>
			</div>
		</div>`;
			$("#delivery_addr_group1").append(html);
		});

		$(document).ready(function() {



			$('body').on('click', '.billing_addr_remove_btn1', function() {
				$(this).parents('.custom_billing_addr1').remove();
			});

			$('body').on('click', '#add_delivery_addr_btn1', function() {

				var html = ` <div class="row delivery_addr_row1 custom_delivery_addr1" style="margin-bottom: 20px;">
              <div class="form-group col-sm-6">
                  <label for="">Person In Charge <span class="error">*</span></label>
                  <input type="text" name="shipping_addr_pic1[]" class="form-control shipping_addr_pic" >

              </div>

              <div class="form-group col-sm-6">
                  <label for="">Contact Number <span class="error">*</span></label>
                  <input type="text" name="shipping_addr_contant_number1[]" class="form-control shipping_addr_contant_number" >

              </div>

              <div class="form-group col-sm-6">
                  <label for="">Postal<span class="error">*</span></label>
                  <input type="text" name="shipping_addr_zipcode1[]" class="form-control shipping_addr_zipcode" onchange="get_address_from_postalcode1(this, 'delivery_addr_row1', 'shipping_address')" >

              </div>

              <div class="form-group col-sm-6">
                  <label for="">Address <span class="error">*</span></label>
                  <textarea name="shipping_address1[]" class="form-control shipping_address" placeholder="Address" ></textarea>

              </div>

              <div class="form-group col-lg-6">
                  <label for="#">Email</label>
                  <input type="text" name="shipping_addr_email1[]" class="form-control shipping_addr_email">

              </div>
                 <div class="form-group col-sm-1" style="margin-top: 32px;">
                  <a class="btn btn-danger delivery_addr_remove_btn1">
                    <i class="fa fa-times"></i>
                  </a>
              </div>
          </div>
       
          `;

				$("#delivery_addr_group1").append(html);

			});

			$('body').on('click', '.delivery_addr_remove_btn1', function() {

				$(this).parents('.custom_delivery_addr1').remove();

			});


		});

		function addBillRow() {
			var html = `<div class="row custom_billing_addr1 billing_addr_row1" style="margin-bottom: 20px;">
                        <div class="form-group col-sm-6">
                            <label for="">Person In Charge <span class="error">*</span></label>
                            <input type="text" name="billing_addr_pic1[]" class="form-control billing_addr_pic" >

                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">Contact Number <span class="error">*</span></label>
                            <input type="text" name="billing_addr_contant_number1[]" class="form-control billing_addr_contant_number" >

                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">Postal<span class="error">*</span></label>
                            <input type="text" name="billing_addr_zipcode1[]" class="form-control billing_addr_zipcode" onchange="get_address_from_postalcode1(this, 'billing_addr_row1', 'billing_address')" >

                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">Address <span class="error">*</span></label>
                            <textarea name="billing_address1[]" class="form-control billing_address" placeholder="Address" ></textarea>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="#">Email</label>
                            <input type="text" name="billing_addr_email1[]" class="form-control billing_addr_email">

                        </div>
                        <div class="form-group col-sm-1" style="margin-top: 32px;">
                                <a class="btn btn-danger billing_addr_remove_btn1">
                                    <i class="fa fa-times"></i>
                                </a>
                        </div>

                        </div>`;

			$("#billing_addr_group1").append(html);
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});


			/* Edit data */
			$("#edit_supplier").submit(function(e) {
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$('.save').prop('disabled', true);

				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=1&edit_type=supplier&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.save').prop('disabled', false);
						} else {
							// On page load: datatable
							var xin_table = $('#xin_table').dataTable({
								"bDestroy": true,
								"ajax": {
									url: "<?php echo site_url("admin/supplier/supplier_list") ?>",
									type: 'GET'
								},
								dom: 'lBfrtip',
								"buttons": ['excel'], // colvis > if needed
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
							$('.edit-modal-data').modal('toggle');
							$('.save').prop('disabled', false);
						}
					}
				});
			});



			$(document).on('click', '.remove-input-field', function() {
				$(this).parents('tr').remove();
			});




		});

		function addRow() {
			var number = $('.AddItem1 tr').length;
			var item = number + 1;
			// alert(item);
			$('.AddItem1').append(`
                <tr>
                <td><label>` + item + `</label></td>
                <td>
                <select class="packing_dropdown form-control select22" name="u_item[]" id="u_item` + item + `" onchange="getProductDetail(` + item + `)">
                        <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
									echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
								} ?>
                    </select>  
                 
                </td>
                        <td><textarea id="u_description` + item + `"  class="form-control" name="u_description[]"></textarea></td>
                        <td><input type="text" id="u_price` + item + `"  class="form-control" name="u_price[]" placeholder="Price"></td>
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                </tr>`);
		}
	</script>
	<script>
		function getProductDetail(number) {
			var prd_id = $('#u_item' + number).val();
			// console.log(supplier_id);

			$.ajax({
				type: "POST",
				url: "<?php echo base_url() . 'admin/purchase/get_product_details/'; ?>" + prd_id,
				data: JSON,
				success: function(data) {
					var product_data = jQuery.parseJSON(data);
					console.log(data);
					$("#u_description" + number).val(product_data[0].description);
					$("#u_price" + number).val(product_data[0].sell_p);

				},
				error: function() {
					toastr.error("Description Not Found");
				}
			});
		}
	</script>
<?php } else if (isset($_GET['jd']) && isset($_GET['supplier_id']) && $_GET['data'] == 'view_supplier') {
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title" id="edit-modal-data">View <?php echo $this->lang->line('xin_suppliers'); ?></h4>
	</div>
	<form class="m-b-1">
		<div class="modal-body">
			<h3>Supplier</h3>
			<table class="footable-details table table-hover toggle-circle">
				<tbody>

					<tr>
						<th>Supplier Code</th>
						<td style="display: table-cell;">
							<?php echo $code; ?>
						</td>
					</tr>

					<tr>
						<th><?php echo $this->lang->line('xin_supplier_name'); ?></th>
						<td style="display: table-cell;">
							<?php echo $supplier_name; ?>
						</td>
					</tr>

					<tr>
						<th>Supplier Term</th>
						<td style="display: table-cell;">
							<?php echo $supplier_terms; ?>
						</td>
					</tr>

					<tr>
						<th>Supplier GST</th>
						<td style="display: table-cell;">
							<?php echo $supplier_gst; ?>
						</td>
					</tr>



				</tbody>
			</table>

			<h3>Billing Address</h3>
			<table class="footable-details table table-hover ">
				<thead class="table-primary">
					<tr>
						<th>Person Incharge</th>
						<th>Contact Number</th>

						<th>Address</th>
						<th>Email</th>
					</tr>
				</thead>
				<tbody class="table-warning">
					<?php $supplier_item = $this->db->select('xin_supplier_billing.*,')->from('xin_supplier_billing')->join('xin_suppliers', 'xin_supplier_billing.supplier_id=xin_suppliers.supplier_id')->where('xin_supplier_billing.supplier_id', $_GET['supplier_id'])->get()->result();
					foreach ($supplier_item as $item) { ?>
						<tr>
							<td><?php echo $item->pic; ?></td>
							<td><?php echo $item->address; ?></td>
							<td><?php echo $item->zipcode; ?></td>
							<td><?php echo $item->email; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>

			<h3>Products</h3>
			<table class="footable-details table table-hover ">
				<thead class="table-primary">
					<tr>
						<th>Item</th>
						<th>Description</th>
						<th>Product Size [Std UOM]</th>
						<th>Cost Price $</th>
					</tr>
				</thead>
				<tbody class="table-warning">
					<?php $supplier_item = $this->db->select('xin_supplier_item_mapping.*, product.product_name,product.size,product.std_uom')->from('xin_supplier_item_mapping')->join('product', 'xin_supplier_item_mapping.supplier_item_name=product.product_id')->where('supplier_id', $_GET['supplier_id'])->get()->result();
					foreach ($supplier_item as $item) { ?>
						<tr>
							<td><?php echo $item->product_name; ?></td>
							<td><?php echo $item->supplier_item_description; ?></td>
							<td><?php echo $item->size . " " . $item->std_uom; ?></td>
							<td>$<?php echo $item->supplier_item_price; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>

			<h3>Purchase Orders</h3>
			<table class="footable-details table table-hover ">
				<thead class="table-primary">
					<tr>
						<th>PO Number</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="table-warning">
					<?php $po = $this->db->select(
						'purchase_order.porder_id as pid,
                                         purchase_order.purchase_order_id,
                                         purchase_order_item_mapping.porder_id,
                                         purchase_order_item_mapping.supplier_id'
					)
						->from('purchase_order_item_mapping')
						->join('purchase_order', 'purchase_order_item_mapping.porder_id=purchase_order.purchase_order_id')
						->where('purchase_order_item_mapping.supplier_id', $_GET['supplier_id'])
						//  ->group_by('purchase_order_item_mapping.supplier_id', $_GET['supplier_id'])
						->get()->result();
					foreach ($po as $item) { ?>
						<tr>
							<td><?php echo $item->pid; ?></td>
							<td><a href="<?php echo base_url('admin/purchase/view_po/' . $item->purchase_order_id) ?>" target="_blank">Click here to View PO</a></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
		</div>

	<?php }
	?>