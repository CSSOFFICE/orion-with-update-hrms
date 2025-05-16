$(document).ready(function() {
    $('#reports').addClass('active');

    $("#submenu_aplist").addClass('active');



    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/ap_list/",
            type: 'GET'
        },
        dom: 'lBfrtip',
        "buttons": ['excel'], // colvis > if needed
        "fnDrawCallback": function(settings){
        $('[data-toggle="tooltip"]').tooltip();          
        }
    });
});