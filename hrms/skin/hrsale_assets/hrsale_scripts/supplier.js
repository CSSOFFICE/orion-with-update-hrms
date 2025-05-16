$(document).ready(function() {
    $('#Procurement').addClass('active');
    $('#submenu_supplier').addClass('active');


    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + '/supplier_list/',
            type: 'GET'
        },
        dom: 'lBfrtip',
        "buttons": ['excel'], // colvis > if needed
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });

    jQuery("#aj_company").change(function() {
        jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
            jQuery('#employee_ajax').html(data);
        });
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
        var supplier_id = button.data('supplier_id');
        var modal = $(this);
        if (supplier_id != undefined) {
            $.ajax({
                url: base_url + "/read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&mode=modal&data=supplier&supplier_id=' + supplier_id,
                success: function(response) {
                    if (response) {
                        $("#ajax_modal").html(response);
                    }
                }
            });
        }
    });

    $('.view-modal-data').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var supplier_id = button.data('supplier_id');
        var modal = $(this);
        $.ajax({
            url: base_url + "/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&data=view_supplier&supplier_id=' + supplier_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal_view").html(response);
                }
            }
        });
    });

    // Award Month & Year
    $('.d_month_year').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        format: 'yyyy-mm',
        yearRange: '1900:' + (new Date().getFullYear() + 15),
        beforeShow: function(input) {
            $(input).datepicker("widget").addClass('hide-calendar');
        },
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            $(this).datepicker('widget').removeClass('hide-calendar');
            $(this).datepicker('widget').hide();
        }

    });

    /* Update logo */
    $("#xin-form").submit(function(e) {
        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 1);
        fd.append("add_type", 'supplier');
        fd.append("form", action);
        e.preventDefault();
        $('.icon-spinner3').show();
        $('.save').prop('disabled', true);
        $.ajax({
            url: e.target.action,
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
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
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('#xin-form')[0].reset(); // To reset form fields
                    $('.add-form').removeClass('in');
                    $('.select2-selection__rendered').html('--Select--');
                    $('.save').prop('disabled', false);
                }
            },
            error: function() {
                toastr.error(JSON.error);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                $('.icon-spinner3').hide();
                $('.save').prop('disabled', false);
            }
        });
    });
    $('.view-modal-data-bg').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var p_supplier_id = button.data('p_supplier_id');
        var modal = $(this);
        $.ajax({
            url: base_url + '/read/',
            type: "GET",
            data: 'jd=1&is_ajax=4&mode=modal&data=view_supplier&type=view_supplier&supplier_id=' + p_supplier_id,
            success: function(response) {
                if (response) {
                    $("#pajax_modal_view").html(response);
                }
            }
        });
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
                    var xin_table2 = $('#xin_table').dataTable({
                        "bDestroy": true,
                        "ajax": {
                            url: base_url + '/supplier_list/',
                            type: 'GET'
                        },
                        /*dom: 'lBfrtip',
                        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
                        // "fnDrawCallback": function(settings) {
                        //     $('[data-toggle="tooltip"]').tooltip();
                        // }
                    });
                
                    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
                    $('[data-plugin="select_hrm"]').select2({ width: '100%' });
                    // $('.delete-modal').modal('toggle');
                    
                    xin_table2.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });

    /* Add data */
    /*Form Submit*/
    //	$("#xin-form").submit(function(e){});
});
$(document).on("click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
});