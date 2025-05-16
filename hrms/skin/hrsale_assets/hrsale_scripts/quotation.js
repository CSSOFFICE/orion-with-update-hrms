$(document).ready(function() {
    $('#Sales').addClass('active');
    
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/all_quotation_list/",
            type: 'GET'
        },
    }); 
    

    var table_company_quote = $('#table_company').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/com_quote_list/",
            type: 'GET'
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings){
        $('[data-toggle="tooltip"]').tooltip();
        }*/
    });

    // var crm_table_individual_quote = $('#crm_table_individual_quote').dataTable({
        
    //     "bDestroy": true,
    //     "ajax": {
    //         url: base_url + '/crm_indv_quotation_list/',
    //         type: 'GET'
    //     },
    // });

    // $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    // $('[data-plugin="select_hrm"]').select2({ width: '100%' });



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
        var purchase_order_id = button.data('purchase_order_id');
        var modal = $(this);
        if (purchase_order_id != undefined) {
            $.ajax({
                url: base_url + "/read_order/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=purchase_order&mode=modal&data=purchase_order&purchase_order_id=' + purchase_order_id,
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
        var quotation = button.data('quotation_id');
        var modal = $(this);
        $.ajax({
            url: base_url + "/read_quotation_view/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&view_type=quotation&data=quotation&quotation_id=' + quotation_id,
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
            url: base_url + "/add_quotation",
            data: obj.serialize() + "&is_ajax=1&add_type=quotation&form=" + action,
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
                    setInterval(function() {
                        window.location.href = base_url + "/quotation_list/", 1000
                    });


                }
            }
        });
    });

    /* 25/Nov/2023 */
    $(document).on("click", ".quote-delete", function() {
        $('input[name=_token]').val($(this).data('sales-record-id'));
        $('#delete_record').attr('action', base_url + '/delete_quote/' + $(this).data('sales-record-id'));
    });
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
                    // $('.delete-modal').modal('toggle');
                    crm_table_individual_quote.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });
    $(document).on("click", ".com-quote-delete", function() {
        $('input[name=_token]').val($(this).data('record-q-id'));
        $('#delete_record').attr('action', base_url + '/delete_com_quote/' + $(this).data('record-q-id'));
    });
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
                    // $('.delete-modal').modal('toggle');
                    table_company_quote.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });
/* 25/Nov/2023 Braincave*/
$('.edit-modal-data').on('show.bs.modal', function(event) {
   
    var button = $(event.relatedTarget);
    var quote_id = button.data('sales-quote_id');
    var modal = $(this);

    if (quote_id != undefined) {
        $.ajax({
            url: base_url + "/indv_quote_read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&edit_type=edit_indv_pro_crm&sales_indv_q=quotation&mode=modal&data=crm&crm_id=' + quote_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    }
});


$('.edit-modal-data').on('show.bs.modal', function(event) {
   
    var button = $(event.relatedTarget);
    var crm_quote_id = button.data('crm_quote_id');
    var modal = $(this);

    if (crm_quote_id != undefined) {
        $.ajax({
            url: base_url + "/crm_s_com_quote_read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&edit_type=edit_com_quote_crm@sales_edit_c_q=quotation&mode=modal&data=crm&crm_id=' + crm_quote_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    }
});

});
$(document).on("click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_quotation/' + $(this).data('record-id'));
});

