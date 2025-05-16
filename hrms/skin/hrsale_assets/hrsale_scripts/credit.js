$(document).ready(function() {
    $('#Sales').addClass('active');
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/all_credit_list/",
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



    /* Delete data */
    $("#delete_record").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=2&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                } else {
                    $('.delete-modal').modal('toggle');
                    xin_table.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });
    // edit
    $('.edit-modal-data').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget);
        var credit_id = button.data('credit_id');

        var modal = $(this);
        if (credit_id != undefined) {
            $.ajax({
                url: base_url + "/read_credit/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=credit&mode=modal&data=credit&credit_id=' + credit_id,
                success: function(response) {
                    if (response) {
                        $("#ajax_modal").html(response);
                    }
                }
            });
        }
    });

    // view
    $('.view-modal-data').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget);
        var credit_id = button.data('credit_id');

        var modal = $(this);
        $.ajax({
            url: base_url + "/view_credit/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&view_type=view_credit&data=view_credit&credit_id=' + credit_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal_view").html(response);
                }
            }
        });
    });

    /* Add data */
    /*Form Submit*/
    $("#xin-form").submit(function(e) {
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/add_credit",
            data: obj.serialize() + "&is_ajax=1&add_type=credit&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    xin_table.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.add-form').removeClass('in');
                    $('.select2-selection__rendered').html('--Select--');
                    $('.icon-spinner3').hide();
                    $('#supplier_address').hide();
                    $('#xin-form')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });


});
$(document).on("click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_credit/' + $(this).data('record-id'));
});