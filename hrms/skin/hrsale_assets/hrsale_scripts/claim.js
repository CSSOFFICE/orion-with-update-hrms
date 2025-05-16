$(document).ready(function() {
    $('#finance').addClass('active');

    
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/claim_list/",
            type: 'GET'
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings){
        $('[data-toggle="tooltip"]').tooltip();          
        }*/
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });

});
$('.edit-modal-data').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var claim_id = button.data('claim_id');
    var modal = $(this);
    if (claim_id != undefined) {
        $.ajax({
            url: base_url + "/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&edit_type=claim&mode=modal&data=claim&claim_id=' + claim_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    }
});
$(document).on("click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_claim/' + $(this).data('record-id'));
});


// $(document).on("click", ".delete", function() {
//     $('input[name=_token]').val($(this).data('record-id'));
//     $('#delete_record').attr('action', base_url + '/delete_order/' + $(this).data('record-id'));
// });