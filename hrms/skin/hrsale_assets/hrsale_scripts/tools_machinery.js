$(document).ready(function () {
	$("#Procurement").addClass("active");
	$("#submenu_po").addClass("active");

	var xin_table = $("#xin_table").dataTable({
		bDestroy: true,
		ajax: {
			url: base_url + "/tools_machinery_data/",
			type: "GET",
		},
		/*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings){
			$('[data-toggle="tooltip"]').tooltip();          
			}*/
		});
    
});
    