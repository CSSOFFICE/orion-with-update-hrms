$(document).ready(function() {
    
    $('#finance').addClass('active');

    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/claim_report_list/",
            type: 'GET',
            data: function(data) {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                data.start_date = start_date;
                data.end_date = end_date;



            },
        }

        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings){
        $('[data-toggle="tooltip"]').tooltip();          
        }*/
    });


    var xin_table1 = $('#xin_table1').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/claim_payable_report_list/",
            type: 'GET',
            data: function(data) {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                data.start_date = start_date;
                data.end_date = end_date;



            },
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings){
        $('[data-toggle="tooltip"]').tooltip();          
        }*/
    });

    $("#start_date").change(function() {
        xin_table.api().ajax.reload();
        xin_table1.api().ajax.reload();

    });
    $("#end_date").change(function() {
        xin_table.api().ajax.reload();
        xin_table1.api().ajax.reload();

    });

});