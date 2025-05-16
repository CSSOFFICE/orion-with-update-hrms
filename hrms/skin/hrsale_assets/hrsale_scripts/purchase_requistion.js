
$(document).ready(function() {
    // $('#dashboard_menu').removeClass('active');
    $('#Procurement').addClass('active');




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
    

    var xin_table = $('#xin_table').DataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/purchase_requistion_list/",
            type: 'GET'
        },
        dom: 'lBfrtip',
        "buttons": ['excel'],
        "columnDefs": [
            { "type": "date-eu", "targets": 3 } // column index of "Date of Request"
        ],
        "fnDrawCallback": function(settings){
            $('[data-toggle="tooltip"]').tooltip();          
        }
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
        var purchase_requistion_id = button.data('purchase_requistion_id');
        var modal = $(this);
        if (purchase_requistion_id != undefined) {
            $.ajax({
                url: base_url + "/read/"+purchase_requistion_id,
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=purchase_requistion&mode=modal&data=purchase_requistion&purchase_requistion_id=' + purchase_requistion_id,
                success: function(response) {
                    if (response) {
                        $("#ajax_modal").html(response);
                    }
                }
            });
        }
    });

    $('.view-modal-data').on('show.bs.modal',function(event) {
        // console.log(1);
        // alert("ggg")
        var button = $(event.relatedTarget);
        var purchase_requistion_id = button.data('purchase_requistion_id');
        var modal = $(this);
        if (purchase_requistion_id != undefined) {
            $.ajax({
                url: base_url + "/read_pr/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=view_purchase&mode=modal&data=view_purchase&purchase_requistion_id=' + purchase_requistion_id,
                success: function(response) {
                    if (response) {
                        $("#ajax_modal_view").html(response);
                    }
                }
            });
        }
    });


    // view
    // $('.view-modal-data').on('show.bs.modal', function(event) {
    //     var button = $(event.relatedTarget);
    //     var project_id = button.data('project_id');
    //     var modal = $(this);
    //     $.ajax({
    //         url: base_url + "/read/",
    //         type: "GET",
    //         data: 'jd=1&is_ajax=1&mode=modal&edit_type=purchase_requistion&data=view_project&project_id=' + project_id,
    //         success: function(response) {
    //             if (response) {
    //                 $("#ajax_modal_view").html(response);
    //             }
    //         }
    //     });
    // });

    /* Add data */
    /*Form Submit*/
    $("#xin-form").submit(function(e) {
     // $("#btn_purchase_requistion").click(function(){
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/add_purchase_requistion",
            data: obj.serialize() + "&is_ajax=1&add_type=purchase_requistion&form=" + action,
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
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.add-form').removeClass('in');
                    $('.select2-selection__rendered').html('--Select--');
                    $('.icon-spinner3').hide();
                    $("#add_form").modal('hide');
                    $('#xin-form')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
});
$(document).on("click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
});

$('.date').datepicker({
    changeMonth: true,
    changeYear: true,
    format: 'dd-mm-yyyy',
    yearRange: '1940:' + new Date().getFullYear()
});