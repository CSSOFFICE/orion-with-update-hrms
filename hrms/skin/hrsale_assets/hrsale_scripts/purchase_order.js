$(document).ready(function () {
	$("#Procurement").addClass("active");
	$("#submenu_po").addClass("active");
	
	///////////////////////////////////GRN/////////////////////////
	var xin_table = $("#xin_grn_table").dataTable({
		bDestroy: true,
		ajax: {
			url: base_url + "/grn_list/",
			type: "GET",
		},
		/*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}*/
		});
		/////////////////////////GRN END/////////////////////////////////
		
		
		jQuery.extend( jQuery.fn.dataTableExt.oSort, {
			"date-eu-pre": function ( date ) {
				date = date.replace(" ", "");
				if (!date) return 0;
		
				var eu_date = date.split('-');
				return (eu_date[2] + eu_date[1] + eu_date[0]) * 1;
			},
		 
			"date-eu-asc": function ( a, b ) {
				return ((a < b) ? -1 : ((a > b) ? 1 : 0));
			},
		 
			"date-eu-desc": function ( a, b ) {
				return ((a < b) ? 1 : ((a > b) ? -1 : 0));
			}
		});
		var xin_table = $("#xin_table").dataTable({
		bDestroy: true,
		ajax: {
			url: base_url + "/purchase_order_list/",
			type: "GET",
		},
		dom: 'lBfrtip',
		"buttons": ['excel'],
		"fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
		},
		"columnDefs": [
			{ "type":"date-eu", "targets": 6 } // ensure column 6 is treated as date
		]
	});
	

	$('[data-plugin="select_hrm"]').select2($(this).attr("data-options"));
	$('[data-plugin="select_hrm"]').select2({ width: "100%" });

	/* Delete data */
	$("#delete_record").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this),
			action = obj.attr("name");
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=2&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != "") {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
				} else {
					$(".delete-modal").modal("toggle");
					xin_table.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
				}
			},
		});
	});

	// edit
	$(".edit-modal-data").on("show.bs.modal", function (event) {
		var button = $(event.relatedTarget);
		var purchase_order_id = button.data("purchase_order_id");
		var modal = $(this);
		if (purchase_order_id != undefined) {
			$.ajax({
				url: base_url + "/read_order/",
				type: "GET",
				data:
					"jd=1&is_ajax=1&edit_type=purchase_order&mode=modal&data=purchase_order&purchase_order_id=" +
					purchase_order_id,
				success: function (response) {
					if (response) {
						$("#ajax_modal").html(response);
					}
				},
			});
		}
	});

	// view
	$(".view-modal-data").on("show.bs.modal", function (event) {
		var button = $(event.relatedTarget);
		var purchase_order_id = button.data("purchase_order_id");
		var modal = $(this);
		if (purchase_order_id != undefined) {
			$.ajax({
				url: base_url + "/add_payable/",
				type: "GET",
				data:
					"jd=1&is_ajax=1&mode=modal&data=add_payable&purchase_order_id=" +
					purchase_order_id,
				success: function (response) {
					if (response) {
						$("#ajax_modal_view").html(response);
					}
				},
			});
		}
	});

	/* Add data */
	/*Form Submit*/
	// Bind form submit event
	$("#xin-form").submit(function (e) {
		// Prevent the form's default submit behavior
		e.preventDefault();

		// Create a FormData object from the form
		var fd = new FormData(this);

		// Append additional data if necessary
		fd.append("is_ajax", "1");
		fd.append("add_type", "purchase_order");

		// Disable the submit button and show a loading spinner
		$(".save").prop("disabled", true);
		$(".icon-spinner3").show();

		// Perform the AJAX request
		$.ajax({
			type: "POST",
			url: base_url + "/p_order_add", // Make sure base_url is correctly defined elsewhere
			data: fd,
			cache: false, // Disable cache
			contentType: false, // Tell jQuery not to process the data
			processData: false, // Tell jQuery not to set contentType
			success: function (JSON) {
				// Check if the server returned an error
				if (JSON.error) {
					toastr.error(JSON.error); // Display error notification
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash); // Update CSRF token
					$(".save").prop("disabled", false); // Re-enable the save button
					$(".icon-spinner3").hide(); // Hide the loading spinner
				} else {
					// If successful, reload the table and show success notification
					xin_table.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);

					// Update CSRF token
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);

					// Reset the form fields and clear the Select2 dropdowns
					$("#xin-form")[0].reset(); // Reset all form fields
					
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				// Handle AJAX request errors
				toastr.error(
					"There was an error processing your request. Please try again."
				);
				$(".save").prop("disabled", false); // Re-enable the button
				$(".icon-spinner3").hide(); // Hide spinner
			},
		});
	});
});
$(document).on("click", ".delete", function () {
	$("input[name=_token]").val($(this).data("record-id"));
	$("#delete_record").attr(
		"action",
		base_url + "/delete_order/" + $(this).data("record-id")
	);
});

$(".date").datepicker({
	changeMonth: true,
	changeYear: true,
	format: "dd-mm-yyyy",
	yearRange: "1940:" + new Date().getFullYear(),
});
