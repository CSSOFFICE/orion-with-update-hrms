<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('2902', $role_resources_ids) || in_array('8001', $role_resources_ids)) { ?>

	<style>
		.invoice table th,
		.invoice table td {
			border: 1px solid #000;
			/* text-align: center; */
		}


		.invoice-header {
			display: flex;
			gap: 10px;
			border-bottom: 1px solid #000;
		}

		.logo {
			width: 20%;
		}

		.detail {
			width: 60%;
			text-align: center;
		}

		.detail h4 {
			text-transform: uppercase;
			margin-top: 0.5rem;
			margin-bottom: 0.5rem;
		}

		.prv-status {
			float: right;
			width: 20%;
			text-align: right;
		}

		.invoice-main {
			display: flex;
			gap: 10px;
			padding-top: 0.5rem;
			padding-bottom: 0.5rem;
		}

		.invoice-main .left {
			width: 40%;
		}

		.invoice-main .center {
			width: 20%;
			text-align: center;
		}

		.invoice-main .right {
			width: 10%;
		}

		.d-flex {
			display: flex;
		}

		.justify-content-between {
			justify-content: space-between;
		}

		.gap-1 {
			gap: 5px;
		}

		table {
			width: 100%;
			border: 1px solid #000;
			border-collapse: collapse;
			text-wrap: wrap;
		}

		.table-dece {
			width: 500px;
		}

		.tfoot {
			border-top: 1px solid #000;
		}

		@media screen and (max-width:768px) {
			.invoice-header {
				flex-direction: column;
				justify-content: center;
			}

			.logo {
				width: 100%;
				order: 2;
			}

			.detail {
				width: 100%;
				order: 3;
			}

			.prv-status {
				text-align: end;
				width: 100%;
				order: 1;
			}

			.invoice-main {
				flex-direction: column;
				justify-content: center;
			}

			.invoice-main .left {
				width: 100%;
			}

			.invoice-main .center {
				width: 100%;
			}

			.invoice-main .right {
				width: 100%;
			}

		}

		.k-checkbox [type="checkbox"]:not(:checked),
		[type="checkbox"]:checked {
			position: fixed;
			left: 0;
			opacity: 1;
		}
	</style>
	<div class="form-body">
		<input type="hidden" name="purchase_requistion_id" value="<?php echo $purchase_requistion_id; ?>">
		<div class="invoice">
			<div class="invoice-header" style="margin-top: 15px;">
				<div class="logo">
					<!-- <img style="text-align:center;" src="<?php echo site_url('uploads/logo/' . $invoice_settings[0]->invoice_logo) ?>" class="img-fluid" width="150px" alt=""> -->
				</div>

				<div class="detail">
					<h4>Material Requisition Form (MRF)</h4>
				</div>


				<div class="prv-status">
					<?php if ($status == "Approved") { ?>

						<label class="text-success"><?php echo $status; ?></label>
					<?php } else if ($status == "Rejected") { ?>
						<label class="text-danger"><?php echo $status; ?></label>
					<?php } else { ?>
						<?php echo $button; ?>
						<button class="btn btn-danger waves-effect waves-light icon-btn btn-xs" name="rej" id="rej" onclick="rejectPR()">Reject</button>




					<?php } ?>
				</div>
			</div>
			<div class="invoice-main">

				<div class="left">

					<b>PROJECT NAME / No: </b>
					<b><?php echo $project_name ?></b><br><br>
					<b>Milestone: </b>
					<b><?php if ($milestone == '1') {
							$name = "PRELIMINARIES";
						} elseif ($milestone == '2') {
							$name = "INSURANCE";
						} elseif ($milestone == '3') {
							$name = "SCHEDULE OF WORKS";
						} elseif ($milestone == '4') {
							$name = "Plumbing & Sanitary";
						} elseif ($milestone == '5') {
							$name = "ELEC & ACMV";
						} elseif ($milestone == '6') {
							$name = "EXTERNAL WORKS";
						} elseif ($milestone == '7') {
							$name = "PC & PS SUMS";
						}
						echo $name; ?></b><br><br>
					<b>Task: </b>
					<b><?php echo $description_name ?></b><br><br>
					<?php if ($crane) { ?>
						<b>Mode of Transport: </b>
						<b><?php $tp = $this->db->where('mst_id', $crane)->get('mode_of_transport')->result();
							echo $tp[0]->mst_title ?></b><br><br>
					<?php } elseif ($crane == 0) { ?>
						<b>Others: <?php echo $others; ?></b>
					<?php } ?>
				</div>

				<?php
				$loc = explode(',', $location);
				?>

			</div>
			<?php
			$arr_site = explode(',', $site);
			?>
			<table style="width: 100%;text-align:center;">
				<tr>
					<td style="width: 33.33%;text-align:left;border:1px solid;"><b>Material Requisition Form (MRF)</b></td>
					<td style="width: 33.33%;text-align:left;border-right:none;border:1px solid;"><b>Form No. <?php echo $porder_id ?></b></td>
					<td rowspan="6" style="width: 50%;border-right:none;">
						<?php $proc_logo = $this->db->get('xin_quo')->result(); ?>
						<img src="<?php echo site_url('uploads/quo/' . $proc_logo[0]->logo4) ?>" class="img-fluid" width="300px" alt="">
					</td>
				</tr>
				<tr>
					<td style="width: 33.33%;text-align:left;border:1px solid;"><b>Project Department-Purchasing Department</b></td>
					<td style="width: 33.33%;text-align:left; border:1px solid;"><b>MRF Date: <?php echo date('d-m-Y', strtotime($order_date)) ?></b></td>
				</tr>
				<tr>
					<td style="width: 33.33%;text-align:left;border:1px solid;" colspan="2"><b>Site: <?php echo $site_address ?></b>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<!-- <td style="width:30%;text-align:left;">
                    <input type="checkbox" id="site4" name="chk_site1[]" value="office">
                    <label for="site4"> OFFICE</label>
                    </td> -->
					<td style="width:30%;text-align:left;">
						<?php if (in_array("Storeroom No Stock.I have checked with Storeman", $arr_site)) { ?>
							<span style="border: 1px solid #000; width: 15%; text-align:center;"> <i class="fa fa-check"></i></span>
							<label for="u_site4"> Storeroom No Stock.I have checked with Storeman.</label>

						<?php } else { ?>
							<i class="far fa-square"></i>
							<label for="u_site4">Storeroom No Stock.I have checked with Storeman.</label>
						<?php } ?>
					</td>

					<td style="width:30%;text-align:left;">
						<?php if (in_array("Supervisor has checked with Engineer before ordering", $arr_site)) { ?>
							<span style="border: 1px solid #000; width: 15%; text-align:center;"> <i class="fa fa-check"></i></span>
							<label for="u_site4"> Supervisor has checked with Engineer before ordering.</label>

						<?php } else { ?>
							<i class="far fa-square"></i>
							<label for="u_site4">Supervisor has checked with Engineer before ordering.</label>
						<?php } ?>
					</td>

				</tr>
				<tr>
					<td style="width:30%;text-align:left;">
						<?php if (in_array("Please check Yishun Storeroom before you order", $arr_site)) { ?>
							<span style="border: 1px solid #000; width: 15%; text-align:center;"> <i class="fa fa-check"></i></span>
							<label for="u_site4"> Please check Yishun Storeroom before you order.</label>

						<?php } else { ?>
							<i class="far fa-square"></i>

							<label for="u_site4">Please check Yishun Storeroom before you order.</label>
						<?php } ?>
					</td>
					<td style="width:30%;text-align:left;">
						<?php if (in_array("We have already checked with Boss to order", $arr_site)) { ?>
							<span style="border: 1px solid #000; width: 15%; text-align:center;"> <i class="fa fa-check"></i></span>
							<label for="u_site4"> We have already checked with Boss to order.</label>

						<?php } else { ?>
							<i class="far fa-square"></i>

							<label for="u_site4">We have already checked with Boss to order.</label>
						<?php } ?>
					</td>
				</tr>
				<!-- <tr>
                    <td style="width:30%;text-align:left;"><input type="checkbox" id="u_site8" name="u_check1[]" value="Book Crane Lorry" <?php echo (in_array("Book Crane Lorry", $arr_site) ? 'checked' : ''); ?>><label for="u_site8">Book Crane Lorry</label></td>
                </tr> -->
			</table>
			<!-- <div style="text-align: right;">
                                <a href="javascript:void(0)" class="btn-sm btn-success addButton" id="addButton2">Add</a>
                            </div> -->
			<table style="margin-top: 5px;margin-bottom: 5px; height: fit-content; width:100%; table-layout:fixed; word-break:break-word;">
				<thead style="text-align:center;">
					<tr>
						<th style="word-break:break-word;width:50px">Item</th>
						<th style="word-break:break-word;width:300px">Material/Tool</th>
						<th style="word-break:break-word;">Qty</th>
						<th style="word-break:break-word;">Uom</th>
						<th style="word-break:break-word;">Which Level?</th>
						<th style="word-break:break-word;">Where did you use?</th>
						<th style="word-break:break-word;">Which Sub Con used</th>
						<th style="word-break:break-word;">Purchase Order No.</th>
						<th style="word-break:break-word;">Delivery Order No.</th>
						<!-- <th>Action</th> -->
					</tr>
				</thead>
				<tbody class="AddItem1" id="vendor_items_table2" style="text-align: left !important;">
					<?php
					$i = 1;
					// print_r($all_items);exit();
					foreach ($all_items as $item) { ?>
						<tr>
							<td style="word-break:break-word;"><label><?php echo $i; ?><label></td>
							<td style="word-break:break-word;">
								<?php echo $item->product_name ?>
							</td>
							<td style="word-break:break-word;">
								<?php echo $item->qty; ?>
							</td>
							<td style="word-break:break-word;">
								<?php echo $item->uom; ?>
							</td>
							<td style="word-break:break-word;">
								<?php echo $item->level; ?>
							</td>
							<td style="word-break:break-word;">
								<?php echo $item->where_use; ?>
							</td>
							<td style="word-break:break-word;">
								<?php echo $item->sub_con; ?>
							</td>
							<td style="word-break:break-word;">
								<?php echo $item->po_no; ?>
							</td>
							<td style="word-break:break-word;">
								<?php echo $item->do_no; ?>
							</td>
						</tr>
					<?php
						$i++;
					}
					?>
				</tbody>
			</table>


			<table>
				<tr>
					<td style="width:70%;text-align:left;">
						<label>Name of Supervisor who order:</label>
						<b><?php echo $supervisor; ?></b>
						<br />
						<label>Name of Sub-Contractor who order:</label>
						<b><?php echo $sub_contractor; ?></b>

					</td>
					<td style="width:30%;text-align:left;">
						<label>Date,Name & Signature of Engineer who check this order: </label><br /><br />
						<?php if ($engineer_signature != 'No Signature') { ?>
							<span><?= $engineer_date . " " . $engineer_name ?></span>
							<img src="<?php echo base_url('uploads/document/signature/' . $engineer_signature) ?>" height="100px" width="150px">
						<?php } ?>
					</td>

				</tr>
				<tr style="vertical-align:bottom;">
					<td style="width:70%;text-align:left;">
						<?php if ($supervisor_signature != 'No Signature') { ?>
							<label><u>Signature:</u>
								<img src="<?php echo base_url('uploads/document/signature/' . $supervisor_signature) ?>" height="100px" width="150px">
							</label><br />

							<label>Requested by site Supervisor <u>
									<?php echo $approvers_name ?></u>
							</label>
						<?php } else { ?>
							<label><u>Signature:</u><br>
								<label>Requested by site Supervisor <u>
									<?php } ?>
					</td>
					<td style="width:30%;text-align:left;">
						<label>Date of Materials required: </label><br />
						<p>Earliest Date:<?php echo date(' l, d/m/Y', strtotime($earliest_date)); ?></p><br />
						<p>Latest Date:<?php echo date(' l, d/m/Y', strtotime($latest_date)); ?></p>
						<br />
					</td>

				</tr>
			</table>
			<div style="background-color: white; height: 150px; padding: 10px; display: flex; flex-direction: column; justify-content: space-between;">
				<div style="display: flex; justify-content: space-between; align-items: center;">
					<label>Status: <?php echo $status; ?></label>
				</div>

				<?php if ($status == "Rejected") { ?>
					<textarea class="form-control" id="u_status_reason" name="u_status_reason" style="width: 100%;"><?php echo $status_reason; ?></textarea>
				<?php } ?>
				<?php if ($status != "Approved" && $status != "Rejected") { ?>
					<div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 10px;">
						<?php if ($eng_status == 'No' && (in_array('2919', $role_resources_ids))) {
							echo $EngineerButton;
						} ?>
						<?php if ($projm_status == 'No' && (in_array('2920', $role_resources_ids))) {
							echo $ProjectManagerButton;
						} ?>
						<?php if ($management_status == 'No' && (in_array('2921', $role_resources_ids))) {
							echo $ManagementButton;
						} ?>

					</div>
				<?php } ?>
			</div>

		</div>



	</div>
	<!-- <div class="table-responsive">
        <table>

            <thead>
                <tr>
                    <td>
                        S/N
                    </td>
                    <td>
                        ITEM
                    </td>
                    <td>
                        DESCRIPTION
                    </td>
                    <td>
                        UOM
                    </td>
                    <td>
                        QUNTITY REQUESTED
                    </td>
                    <td>
                        QUANTITY ISSUED
                    </td>
                    <td>
                        BALANCE STOCK
                    </td>
                    <td>
                        REMARK
                    </td>
                    <td>
                        REQUESTED DATE
                    </td>
                </tr>
            </thead>
            <tbody>

                <?php $i = 0;

				foreach ($product as $item) {
					$i++; ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $item->product_id; ?></td>
                        <td class="table-dece"><?php echo $item->qty; ?></td>
                        <td><?php echo $item->level; ?></td>
                        <td><?php echo $item->where_use; ?></td>
                        <td><?php echo $item->sub_con; ?></td>
                        <td><?php echo $item->po_no; ?></td>
                        <td><?php echo $item->do_no; ?></td>
                      

                    </tr>


                <?php } ?>


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><b>DATE : <?php echo $required_date; ?></b></td>
                    <td colspan="3"><b>REQUESTED NAME: <?php echo $client_company_name; ?></b></td>
                    <td colspan="4"><b>APPROVED NAME: <?php echo $app_by; ?><br>

                        </b></td>
                </tr>
            </tfoot>
        </table>
    </div> -->







	<script>
		$(document).on("click", ".statusbutton", function(e) {
			e.preventDefault();
			const url = $(this).data("url");

			$.ajax({
				url: url,
				type: "POST",
				success: function(response) {
					if (response && response.result) {
						toastr.success(response.result);
						setTimeout(() => {
							window.location.reload();
						}, 3000);
					} else if (response && response.error) {
						toastr.error(response.error);
					} else {
						toastr.error("An unexpected error occurred.");
					}
				}
			});
		});

		function rejectPR() {
			$.ajax({
				url: '<?php echo site_url("admin/Purchase/rej_pr/" . $this->uri->segment(4)) ?>',
				type: "POST",
				data: 'jd=1&is_ajax=1&purchase_requistion_id=' + <?php echo $this->uri->segment(4) ?>,
				success: function(response) {
					console.log(response);
					if (response) {

						toastr.success(response.result)
						setTimeout(() => {
							window.location.reload();
						}, 3000);

					} else {
						toastr.error(response.error)

					}
				}
			});
		}

		function p_management(id) {
			$.ajax({
				url: '<?php echo site_url("admin/Purchase/change_status/" . $this->uri->segment(4)) ?>',
				type: "POST",
				data: 'jd=1&is_ajax=1&purchase_requistion_id=' + id + '&status=Pending Project Manager Verification',
				success: function(response) {
					//console.log(response);
					if (response) {

						toastr.success(response.result)
						setTimeout(() => {
							window.location.reload();
						}, 3000);

					} else {
						toastr.error(response.error)

					}
				}
			});
		}

		function pm_management(id) {
			$.ajax({
				url: '<?php echo site_url("admin/Purchase/change_status/" . $this->uri->segment(4)) ?>',
				type: "POST",
				data: 'jd=1&is_ajax=1&purchase_requistion_id=' + id + '&status=Pending Management Verification',

				success: function(response) {
					//console.log(response);
					if (response) {
						toastr.success(response.result);
						setTimeout(() => {
							window.location.reload();
						}, 3000);
					} else {
						toastr.error(response.error);
					}
				}
			});
		}

		function m_management(id) {
			$.ajax({
				url: '<?php echo site_url("admin/Purchase/change_status/" . $this->uri->segment(4)) ?>',
				type: "POST",
				data: 'jd=1&is_ajax=1&purchase_requistion_id=' + id + '&status=Management Approval',

				success: function(response) {
					//console.log(response);
					if (response) {
						toastr.success(response.result);
						setTimeout(() => {
							window.location.reload();
						}, 3000);
					} else {
						toastr.error(response.error);
					}
				}
			});
		}

		function rejectPR() {
			$.ajax({
				url: '<?php echo site_url("admin/Purchase/rej_pr/" . $this->uri->segment(4)) ?>',
				type: "POST",
				data: 'jd=1&is_ajax=1&purchase_requistion_id=' + <?php echo $this->uri->segment(4) ?>,
				success: function(response) {
					console.log(response);
					if (response) {

						toastr.success(response.result)
						setTimeout(() => {
							window.location.reload();
						}, 3000);

					} else {
						toastr.error(response.error)

					}
				}
			});
		}
	</script>

<?php } ?>