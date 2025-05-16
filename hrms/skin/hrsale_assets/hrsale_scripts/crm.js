
 $(document).ready(function () {
    $("#CRM").addClass('active');
   

    var table_individual = $('#table_individual').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + '/crmlist/',
            type: 'GET'
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var table_company = $('#table_company').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + '/com_crmlist/',
            type: 'GET'
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

   var usrid = $('#userid').val();
    var crm_table_individual_proj = $('#crm_table_individual_proj').dataTable({
        
        "bDestroy": true,
        "ajax": {
            url: base_url + '/project_list_crm_indv/' + usrid,
            type: 'GET'
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var crm_table_individual_quote = $('#crm_table_individual_quote').dataTable({
       
        "bDestroy": true,
        "ajax": {
            url: base_url + '/crm_indv_quotation_list/' + usrid,
            type: 'GET'
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var crm_table_individual_invoice = $('#crm_table_individual_invoice').dataTable({
        
        "bDestroy": true,
        "ajax": {
            url: base_url + '/crm_indv_invoice_list/' + usrid,
            type: 'GET'
        },
        /*dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

   



    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });

    $("#xin-form").submit(function(e) {
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/add_crm",
            data: obj.serialize() + "&is_ajax=1&add_type=crm&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    table_individual.api().ajax.reload(function() {
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


    $("#crm-proj-form").submit(function(e) {
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/add_crm_proj",
            data: obj.serialize() + "&is_ajax=1&add_type=crm_proj&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    crm_table_individual_proj.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.add-form').removeClass('in');
                    $('.select2-selection__rendered').html('--Select--');
                    $('.icon-spinner3').hide();
                    $('#supplier_address').hide();
                    $('#crm-proj-form')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });

    $("#crm-quot-form").submit(function(e) {
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/add_crm_quote",
            data: obj.serialize() + "&is_ajax=1&add_type=crm_quote&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    crm_table_individual_quote.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                   
                    /////
                    crm_table_individual_invoice.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);

                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.add-form').removeClass('in');
                    $('.select2-selection__rendered').html('--Select--');
                    $('.icon-spinner3').hide();
                    $('#supplier_address').hide();
                    $('#crm-quot-form')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });


        //COMPANY CUSTOMER FUNCTIONS//
        var usrid = $('#userid').val();
        var crm_table_com_proj = $('#crm_table_com_proj').dataTable({
            
            "bDestroy": true,
            "ajax": {
                url: base_url + '/project_list_crm_com/' + usrid,
                type: 'GET'
            },
            /*dom: 'lBfrtip',
            "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
            "fnDrawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    
        var crm_table_com_quote = $('#crm_table_com_quote').dataTable({
            
            "bDestroy": true,
            "ajax": {
                url: base_url + '/crm_com_quotation_list/' + usrid,
                type: 'GET'
            },
            /*dom: 'lBfrtip',
            "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
            "fnDrawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    
        var crm_table_com_invoice = $('#crm_table_com_invoice').dataTable({
            
            "bDestroy": true,
            "ajax": {
                url: base_url + '/crm_com_invoice_list/' + usrid,
                type: 'GET'
            },
            /*dom: 'lBfrtip',
            "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
            "fnDrawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $("#crm-proj-com-form").submit(function(e) {
            e.preventDefault();
            var obj = $(this),
                action = obj.attr('name');
            $('.save').prop('disabled', true);
            $('.icon-spinner3').show();
            $.ajax({
                type: "POST",
                url: base_url + "/add_crm_com_proj",
                data: obj.serialize() + "&is_ajax=1&add_type=crm_com_proj&form=" + action,
                cache: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                        $('.icon-spinner3').hide();
                    } else {
                        crm_table_com_proj.api().ajax.reload(function() {
                            toastr.success(JSON.result);
                        }, true);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.add-form').removeClass('in');
                        $('.select2-selection__rendered').html('--Select--');
                        $('.icon-spinner3').hide();
                        $('#supplier_address').hide();
                        $('#crm-proj-com-form')[0].reset(); // To reset form fields
                        $('.save').prop('disabled', false);
                    }
                }
            });
        });

        $("#crm-com-quot-form").submit(function(e) {
            e.preventDefault();
            var obj = $(this),
                action = obj.attr('name');
            $('.save').prop('disabled', true);
            $('.icon-spinner3').show();
            $.ajax({
                type: "POST",
                url: base_url + "/add_crm_com_quote",
                data: obj.serialize() + "&is_ajax=1&add_type=crm_quote&form=" + action,
                cache: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                        $('.icon-spinner3').hide();
                    } else {
                        crm_table_com_quote.api().ajax.reload(function() {
                            toastr.success(JSON.result);
                        }, true);
                       
                        /////Invoice Table Auto Update If any Changes to Quotetion Table/////////////
                        crm_table_com_invoice.api().ajax.reload(function() {
                            toastr.success(JSON.result);
                        }, true);
    
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.add-form').removeClass('in');
                        $('.select2-selection__rendered').html('--Select--');
                        $('.icon-spinner3').hide();
                        $('#supplier_address').hide();
                        $('#crm-com-quot-form')[0].reset(); // To reset form fields
                        $('.save').prop('disabled', false);
                    }
                }
            });
        });



        //COMPANY CUSTOMER FUNCTIONS END//
        
    


    $('.view-modal-data').on('show.bs.modal', function(event) {
   
        var button = $(event.relatedTarget);
        var crm_id = button.data('crm_id');
        var modal = $(this);
        if (crm_id != undefined) {
            $.ajax({
                url: base_url + "/view_profile/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=pro_crm&mode=modal&data=pro_crm&crm_id=' + crm_id,
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
        var crm_c_id = button.data('crm_c_id');
        var modal = $(this);
        if (crm_c_id != undefined) {
            $.ajax({
                url: base_url + "/comcrm_read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=com_crm&mode=modal&data=com_crm&crm_c_id=' + crm_c_id,
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
        var crm_id = button.data('crm_id');
        var modal = $(this);
        if (crm_id != undefined) {
            $.ajax({
                url: base_url + "/read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=crm&mode=modal&data=crm&crm_id=' + crm_id,
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
        var crm_id = button.data('proj_id');
        var modal = $(this);
        if (crm_id != undefined) {
            $.ajax({
                url: base_url + "/indv_pro_read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=edit_indv_pro_crm&mode=modal&data=crm&crm_id=' + crm_id,
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
        var quote_id = button.data('quote_id');
        var modal = $(this);

        if (quote_id != undefined) {
            $.ajax({
                url: base_url + "/indv_quote_read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=edit_indv_pro_crm&mode=modal&data=crm&crm_id=' + quote_id,
                success: function(response) {
                   
                    if (response) {

                        $("#ajax_modal").html(response);
                       
                    }
                },
                 error: function(response) {
                    console.log(response);
                   
                }
            });
        }
    });
/* company CRM 23-11-2023 */
    $('.edit-modal-data').on('show.bs.modal', function(event) {
   
        var button = $(event.relatedTarget);
        var crm_proj_id = button.data('crm_proj_id');
        var modal = $(this);

        if (crm_proj_id != undefined) {
            $.ajax({
                url: base_url + "/crm_com_project_read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=edit_com_pro_crm&mode=modal&data=crm&crm_id=' + crm_proj_id,
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
                url: base_url + "/crm_com_quote_read/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=edit_com_quote_crm&mode=modal&data=crm&crm_id=' + crm_quote_id,
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
        $('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
    });

    $(document).on("click", ".delete", function() {
        $('input[name=_token]').val($(this).data('record-id'));
        $('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
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
                table_individual.api().ajax.reload(function() {
                    toastr.success(JSON.result);
                }, true);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            }
        }
    });
});

/* indivisual Project delete 20/11/2023*/
$(document).on("click", ".proj-delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_proj/' + $(this).data('record-id'));
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
                crm_table_individual_proj.api().ajax.reload(function() {
                    toastr.success(JSON.result);
                }, true);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            }
        }
    });
});

$(document).on("click", ".quote-delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_quote/' + $(this).data('record-id'));
});
$("#delete_record").submit(function(e) {
    // alert('hi');
    /*Form Submit*/
     e.preventDefault();
    var obj = $(this),
        action = obj.attr('name'); 
        console.log(e.target.action);
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

$(document).on("click", ".com-cust-delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_com_cust/' + $(this).data('record-id'));
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
                table_company.api().ajax.reload(function() {
                    toastr.success(JSON.result);
                }, true);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            }
        }
    });
});

 /* company project delete 23/11/2023*/
 $(document).on("click", ".com-project-delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete_com_poject/' + $(this).data('record-id'));
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
                    crm_table_com_proj.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });


    $(document).on("click", ".com-quote-delete", function() {
        $('input[name=_token]').val($(this).data('record-id'));
        $('#delete_record').attr('action', base_url + '/delete_com_quote/' + $(this).data('record-id'));
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
                    crm_table_com_quote.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });


    // $(document).on("click",".update",function(){
    //     $('input[name=_token]').val($(this).data('invoice_id'));
    //     $.ajax({
    //         url: base_url + '/update_revice_status/'+ $(this).data('invoice_id'),
    //         type: "GET",
    //         success: function(response) {
    //             if (response) {
    //                 $("#ajax_modal").html(response);
    //             }
    //         }
    //     });
    // });


});

