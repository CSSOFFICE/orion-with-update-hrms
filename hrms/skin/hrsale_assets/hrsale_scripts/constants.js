$(document).ready(function () {
	$('#settings').addClass('active');

	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width: '100%' });
	// listing
	// On page load:
	var xin_table_travel_arr_type = $('#xin_table_travel_arr_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/travel_arr_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_exit_type = $('#xin_table_exit_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/exit_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_job_type = $('#xin_table_job_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/job_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_job_category = $('#xin_table_job_category').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 15,
		"aLengthMenu": [
			[15, 30, 50, 75, 100, -1],
			[15, 30, 50, 75, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/job_category_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_Quotation_templets_id = $('#xin_table_Quotation_templets_id').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 15,
		"autoWidth": false,  // Disable auto width calculation
		"aLengthMenu": [
			[15, 30, 50, 75, 100, -1],
			[15, 30, 50, 75, 100, "All"]
		],
		"columns": [
			{ "width": "5%" },  // Column 1 width
			{ "width": "10%" },  // Column 2 width
			{ "width": "10%" },  // Column 2 width
			{ "width": "70%" }   // Column 3 width
		],
		"ajax": {
			url: site_url + "settings/Quotation_templets_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_expense_type = $('#xin_table_expense_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/expense_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_termination_type = $('#xin_table_termination_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/termination_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_warning_type = $('#xin_table_warning_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/warning_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_leave_type = $('#xin_table_leave_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/leave_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_award_type = $('#xin_table_award_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/award_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_document_type = $('#xin_table_document_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/document_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});


	var xin_table_contract_type = $('#xin_table_contract_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/contract_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_payment_method = $('#xin_table_payment_method').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/payment_method_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_education_level = $('#xin_table_education_level').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/education_level_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_qualification_language = $('#xin_table_qualification_language').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/qualification_language_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_qualification_skill = $('#xin_table_qualification_skill').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/qualification_skill_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_currency_type = $('#xin_table_currency_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/currency_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_company_type = $('#xin_table_company_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/company_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_packing_type = $('#xin_table_packing_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/packing_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_ethnicity_type = $('#xin_table_ethnicity_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/ethnicity_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_gst = $('#xin_table_gst').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/gst_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_payment_term = $('#xin_table_payment_term').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/payment_term_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_shipping_term = $('#xin_table_shipping_term').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/shipping_term_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_unit = $('#xin_table_unit').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/unit_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_income_type = $('#xin_table_income_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/income_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_security_level = $('#xin_table_security_level').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 5,
		"aLengthMenu": [
			[5, 10, 30, 50, 100, -1],
			[5, 10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/security_level_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_term_condition_level = $('#xin_table_term_condition_level').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/term_condition_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_project_type = $('#xin_table_project_type').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/project_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	var xin_table_purpose_type = $('#delivery_type_list').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/delivery_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});


	var xin_table_purpose_type = $('#Pay_type_list').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/pay_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_task = $('#task_list').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/task_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_overtime = $('#overtime_type_list').dataTable({
		"bDestroy": true,
		"bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		"aLengthMenu": [
			[10, 30, 50, 100, -1],
			[10, 30, 50, 100, "All"]
		],
		"ajax": {
			url: site_url + "settings/overtime_type_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_modeoftransport = $('#mode_of_transport').dataTable({
		// "bDestroy": true,
		// "bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		// "aLengthMenu": [
		// 	[10, 30, 50, 100, -1],
		// 	[10, 30, 50, 100, "All"]
		// ],
		"ajax": {
			url: site_url + "settings/modeoftransport_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	var xin_table_loan_type = $('#loan_type').dataTable({
		// "bDestroy": true,
		// "bFilter": false,
		"bLengthChange": false,
		"iDisplayLength": 10,
		// "aLengthMenu": [
		// 	[10, 30, 50, 100, -1],
		// 	[10, 30, 50, 100, "All"]
		// ],
		"ajax": {
			url: site_url + "settings/loantype_list/",
			type: 'GET'
		},
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	jQuery("#mode_of_transport_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=15&data=mode_of_transport_info&type=mode_of_transport_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_modeoftransport.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#mode_of_transport_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#loan_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=15&data=loan_type_info&type=loan_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_loan_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#loan_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#overtime_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=15&data=overtime_type_info&type=overtime_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_overtime.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#overtime_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#Quotation_templets_id").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=15&data=Quotation_templets&type=Quotation_templets&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_Quotation_templets_id.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#Quotation_templets_id')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});


	jQuery("#document_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=15&data=document_type_info&type=document_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_document_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#document_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#contract_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=16&data=contract_type_info&type=contract_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_contract_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#contract_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#payment_method_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=17&data=payment_method_info&type=payment_method_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_payment_method.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#payment_method_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#edu_level_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=18&data=edu_level_info&type=edu_level_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_education_level.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#edu_level_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#edu_language_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=19&data=edu_language_info&type=edu_language_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_qualification_language.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#edu_language_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#edu_skill_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=20&data=edu_skill_info&type=edu_skill_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_qualification_skill.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#edu_skill_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#leave_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=23&data=leave_type_info&type=leave_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_leave_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#leave_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});


	jQuery("#travel_arr_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=45&data=travel_arr_type_info&type=travel_arr_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					jQuery('.save').prop('disabled', false);
					$('.icon-spinner3').hide();
				} else {
					xin_table_travel_arr_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#travel_arr_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#award_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=22&data=award_type_info&type=award_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_award_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#award_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#warning_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=24&data=warning_type_info&type=warning_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_warning_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#warning_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#ethnicity_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=24&data=ethnicity_type_info&type=ethnicity_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_ethnicity_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#ethnicity_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#income_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=24&data=income_type_info&type=income_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_income_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#income_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#termination_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=25&data=termination_type_info&type=termination_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_termination_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#termination_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#expense_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=26&data=expense_type_info&type=expense_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_expense_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#expense_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#job_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=27&data=job_type_info&type=job_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_job_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#job_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#job_category_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=27&data=job_category_info&type=job_category_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_job_category.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#job_category_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#exit_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=28&data=exit_type_info&type=exit_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_exit_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#exit_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#currency_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=28&data=currency_type_info&type=currency_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_currency_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#currency_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#company_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=28&data=company_type_info&type=company_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_company_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#company_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#packing_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=28&data=packing_type_info&type=packing_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_packing_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#packing_type_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#gst_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=29&data=gst_info&type=gst_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_gst.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#gst_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#payment_term_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=29&data=payment_term_info&type=payment_term_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_payment_term.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#payment_term_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#shipping_term_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=29&data=shipping_term_info&type=shipping_term_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_shipping_term.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#shipping_term_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});


	jQuery("#unit_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=29&data=unit_info&type=unit_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_unit.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#unit_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#security_level_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=30&data=security_level_info&type=security_level_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_security_level.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#security_level_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#term_condition_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=35&data=term_condition_info&type=term_condition_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_term_condition_level.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#term_condition_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#project_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=35&data=project_type_info&type=project_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_project_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#term_condition_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#purpose_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=35&data=purpose_type_info&type=purpose_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_purpose_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#term_condition_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#delivery_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=35&data=purpose_type_info&type=delivery_time_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_purpose_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#term_condition_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	jQuery("#pay_type_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=35&data=pay_type_info&type=pay_type_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_purpose_type.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#term_condition_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});
	jQuery("#task_info").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = jQuery(this),
			action = obj.attr('name');
		jQuery('.save').prop('disabled', true);
		$('.icon-spinner3').show();
		jQuery.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=35&data=task_info&type=task_info&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('.save').prop('disabled', false);
				} else {
					xin_table_task.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
					jQuery('#term_condition_info')[0].reset(); // To reset form fields
					jQuery('.save').prop('disabled', false);
				}
			}
		});
	});

	/* Delete data */
	$("#delete_record").submit(function (e) {
		var tk_type = $('#token_type').val();
		$('.icon-spinner3').show();
		if (tk_type == 'document_type') {
			var field_add = '&is_ajax=9&data=delete_document_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'contract_type') {
			var field_add = '&is_ajax=10&data=delete_contract_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'payment_method') {
			var field_add = '&is_ajax=11&data=delete_payment_method&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'education_level') {
			var field_add = '&is_ajax=12&data=delete_education_level&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'qualification_language') {
			var field_add = '&is_ajax=13&data=delete_qualification_language&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'qualification_skill') {
			var field_add = '&is_ajax=14&data=delete_qualification_skill&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'award_type') {
			var field_add = '&is_ajax=31&data=delete_award_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'leave_type') {
			var field_add = '&is_ajax=32&data=delete_leave_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'warning_type') {
			var field_add = '&is_ajax=33&data=delete_warning_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'termination_type') {
			var field_add = '&is_ajax=34&data=delete_termination_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'expense_type') {
			var field_add = '&is_ajax=35&data=delete_expense_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'job_type') {
			var field_add = '&is_ajax=36&data=delete_job_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'exit_type') {
			var field_add = '&is_ajax=37&data=delete_exit_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'travel_arr_type') {
			var field_add = '&is_ajax=47&data=delete_travel_arr_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'currency_type') {
			var field_add = '&is_ajax=47&data=delete_currency_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'company_type') {
			var field_add = '&is_ajax=47&data=delete_company_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'job_category') {
			var field_add = '&is_ajax=47&data=delete_job_category&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'ethnicity_type') {
			var field_add = '&is_ajax=47&data=delete_ethnicity_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'income_type') {
			var field_add = '&is_ajax=47&data=delete_income_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'security_level') {
			var field_add = '&is_ajax=47&data=delete_security_level&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'packing_type') {
			var field_add = '&is_ajax=47&data=delete_packing_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'gst') {
			var field_add = '&is_ajax=47&data=delete_gst&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'payment_term') {
			var field_add = '&is_ajax=47&data=delete_payment_term&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'shipping_term') {
			var field_add = '&is_ajax=47&data=delete_shipping_term&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;

		} else if (tk_type == 'unit') {
			var field_add = '&is_ajax=47&data=delete_unit&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'term_condition_level') {
			var field_add = '&is_ajax=47&data=delete_term_condition&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'project_type_level') {
			var field_add = '&is_ajax=47&data=delete_project_type&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'pay_ty') {
			var field_add = '&is_ajax=47&data=pay_ty&type=delete_record&';
			var tb_name = 'xin_table_' + tk_type;
		} else if (tk_type == 'del_task') {
			var field_add = '&is_ajax=47&data=del_task&type=delete_record&';
			var tb_name = 'task_list';
		}
		else if (tk_type == 'del_overtime_type') {
			var field_add = '&is_ajax=47&data=del_overtime_type&type=delete_record&';
			var tb_name = 'overtime_type_list';
		}
		else if (tk_type == 'Quotation_templets') {
			var field_add = '&is_ajax=47&data=Quotation_templets&type=delete_record&';
			var tb_name = 'xin_table_Quotation_templets_id';
		}else if (tk_type == 'mode_of_transport') {
			var field_add = '&is_ajax=47&data=mode_of_transport_info&type=delete_record&';
			var tb_name = 'mode_of_transport';
		}
		else if (tk_type == 'loan_type') {
			var field_add = '&is_ajax=47&data=loan_type_info&type=delete_record&';
			var tb_name = 'loan_type';
		}


		/*Form Submit*/
		e.preventDefault();
		var obj = $(this),
			action = obj.attr('name');
		$.ajax({
			url: e.target.action,
			type: "post",
			data: '?' + obj.serialize() + field_add + "form=" + action,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.icon-spinner3').hide();
				} else {
					$('.delete-modal').modal('toggle');
					$('.icon-spinner3').hide();
					$('#' + tb_name).dataTable().api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
				}
			}
		});
	});

	$('#edit_setting_datail').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var field_type = button.data('field_type');
		$('.icon-spinner3').show();
		if (field_type == 'document_type') {
			var field_add = '&data=ed_document_type&type=ed_document_type&';
		} else if (field_type == 'contract_type') {
			var field_add = '&data=ed_contract_type&type=ed_contract_type&';
		} else if (field_type == 'payment_method') {
			var field_add = '&data=ed_payment_method&type=ed_payment_method&';
		} else if (field_type == 'education_level') {
			var field_add = '&data=ed_education_level&type=ed_education_level&';
		} else if (field_type == 'qualification_language') {
			var field_add = '&data=ed_qualification_language&type=ed_qualification_language&';
		} else if (field_type == 'qualification_skill') {
			var field_add = '&data=ed_qualification_skill&type=ed_qualification_skill&';
		} else if (field_type == 'award_type') {
			var field_add = '&data=ed_award_type&type=ed_award_type&';
		} else if (field_type == 'leave_type') {
			var field_add = '&data=ed_leave_type&type=ed_leave_type&';
		} else if (field_type == 'warning_type') {
			var field_add = '&data=ed_warning_type&type=ed_warning_type&';
		} else if (field_type == 'termination_type') {
			var field_add = '&data=ed_termination_type&type=ed_termination_type&';
		} else if (field_type == 'expense_type') {
			var field_add = '&data=ed_expense_type&type=ed_expense_type&';
		} else if (field_type == 'job_type') {
			var field_add = '&data=ed_job_type&type=ed_job_type&';
		} else if (field_type == 'exit_type') {
			var field_add = '&data=ed_exit_type&type=ed_exit_type&';
		} else if (field_type == 'travel_arr_type') {
			var field_add = '&data=ed_travel_arr_type&type=ed_travel_arr_type&';
		} else if (field_type == 'currency_type') {
			var field_add = '&data=ed_currency_type&type=ed_currency_type&';
		} else if (field_type == 'company_type') {
			var field_add = '&data=ed_company_type&type=ed_company_type&';
		} else if (field_type == 'job_category') {
			var field_add = '&data=ed_job_category&type=ed_job_category&';
		} else if (field_type == 'ethnicity_type') {
			var field_add = '&data=ed_ethnicity_type&type=ed_ethnicity_type&';
		} else if (field_type == 'income_type') {
			var field_add = '&data=ed_income_type&type=ed_income_type&';
		} else if (field_type == 'security_level') {
			var field_add = '&data=ed_security_level&type=ed_security_level&';
		} else if (field_type == 'packing_type') {
			var field_add = '&data=ed_packing_type&type=ed_packing_type&';
		} else if (field_type == 'gst') {
			var field_add = '&data=ed_gst&type=ed_gst&';
		} else if (field_type == 'payment_term') {
			var field_add = '&data=ed_payment_term&type=ed_payment_term&';
		} else if (field_type == 'shipping_term') {
			var field_add = '&data=ed_shipping_term&type=ed_shipping_term&';
		}
		else if (field_type == 'unit') {
			var field_add = '&data=ed_unit&type=ed_unit&';
		} else if (field_type == 'ed_term_condition') {
			var field_add = '&data=ed_term_condition&type=ed_term_condition&';
		} else if (field_type == 'ed_project_type') {
			var field_add = '&data=ed_project_type&type=ed_project_type&';
		} else if (field_type == 'ed_pay_type') {
			var field_add = '&data=ed_pay_type&type=ed_pay_type&';
		} else if (field_type == 'ed_task') {
			var field_add = '&data=ed_task&type=ed_task&';
		} else if (field_type == 'ed_overtime_type') {
			var field_add = '&data=ed_overtime_type&type=ed_overtime_type&';
		} else if (field_type == 'Quotation_templets') {
			var field_add = '&data=Quotation_templets&type=Quotation_templets&';
		}else if (field_type == 'mode_of_transport') {
			var field_add = '&data=ed_mode_of_transport&type=ed_mode_of_transport&';
		}else if (field_type == 'loan_type') {
			var field_add = '&data=ed_loan_type&type=ed_loan_type&';
		}



		var modal = $(this);
		$.ajax({
			url: site_url + 'settings/constants_read/',
			type: "GET",
			data: 'jd=1' + field_add + 'field_id=' + field_id,
			success: function (response) {
				if (response) {
					$('.icon-spinner3').hide();
					$("#ajax_setting_info").html(response);
				}
			}
		});
	});

	$(".nav-tabs-link").click(function () {
		var profile_id = $(this).data('constant');
		var profile_block = $(this).data('constant-block');
		$('.list-group-item').removeClass('active');
		$('.current-tab').hide();
		$('#constant_' + profile_id).addClass('active');
		$('#' + profile_block).show();
	});
});
$(document).on("click", ".delete", function () {
	$('input[name=_token]').val($(this).data('record-id'));
	$('input[name=token_type]').val($(this).data('token_type'));
	$('#delete_record').attr('action', site_url + 'settings/delete_' + $(this).data('token_type') + '/' + $(this).data('record-id')) + '/';
});

