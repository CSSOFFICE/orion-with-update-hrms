$(document).ready(function() {
    $('#finance').addClass('active');

    
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/payable_list/",
            type: 'GET',
            data: function(data) {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var search_status = $('#search_status').find(":selected").val();
                var search_supplier = $('#search_supplier').find(":selected").val();


                data.start_date = start_date;
                data.end_date = end_date;
                data.search_status = search_status;
                data.search_supplier = search_supplier;
            },
        },
        dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings){
        $('[data-toggle="tooltip"]').tooltip();          
        }
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });

    $("#start_date").change(function() {
        xin_table.api().ajax.reload();
    });
    $("#end_date").change(function() {
        xin_table.api().ajax.reload();
    });
    $("#search_status").change(function() {
        xin_table.api().ajax.reload();
    });
    $("#search_supplier").change(function() {
        xin_table.api().ajax.reload();
    });

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
        var payable_id = button.data('payable_id');
        var modal = $(this);
        if (payable_id != undefined) {
            $.ajax({
                url: base_url + "/read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=payable&mode=modal&data=payable&payable_id=' + payable_id,
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
        var payable_id = button.data('payable_id');
        var modal = $(this);
        $.ajax({
            url: base_url + "/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&edit_type=view_payable&data=view_payable&payable_id=' + payable_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal_view").html(response);
                }
            }
        });
    });

    // /* Add data */
    // /*Form Submit*/
    // $("#xin-form").submit(function(e) {
    //     e.preventDefault();
    //     var obj = $(this),
    //         action = obj.attr('name');
    //     $('.save').prop('disabled', true);
    //     $('.icon-spinner3').show();
    //     $.ajax({
    //         type: "POST",
    //         url: base_url + "/update",
    //         data: obj.serialize() + "&is_ajax=1&edit_type=payable&form=" + action,
    //         cache: false,
    //         success: function(JSON) {
    //             if (JSON.error != '') {
    //                 toastr.error(JSON.error);
    //                 $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
    //                 $('.save').prop('disabled', false);
    //                 $('.icon-spinner3').hide();
    //             } else {
    //                 xin_table.api().ajax.reload(function() {
    //                     toastr.success(JSON.result);
    //                 }, true);
    //                 $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
    //                 $('.add-form').removeClass('in');
    //                 $('.select2-selection__rendered').html('--Select--');
    //                 $('.icon-spinner3').hide();
    //                 $('#supplier_address').hide();
    //                 $('#xin-form')[0].reset(); // To reset form fields
    //                 $('.save').prop('disabled', false);
    //             }
    //         }
    //     });
    // });
    $("#xin-form1").submit(function(e) {

        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/update_quotation",
            data: obj.serialize() + "&is_ajax=1&edit_type=quotation&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {

                    toastr.success(JSON.result);


                }
            }
        });
    });
});
$(document).on("click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_payable/' + $(this).data('record-id'));
});