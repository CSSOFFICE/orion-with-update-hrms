$(document).ready(function() {
    $('#Procurement').addClass('active');
    $('#submenu_po').addClass('active');


///////////////////////////////////GRN/////////////////////////
    var xin_table = $('#xin_table_grn').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + "/grn_list/",
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

    $('.edit-modal-data').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var grn_id = button.data('grn_id');
        var po_number = button.data('order_id');
        var modal = $(this);
        if (grn_id != undefined && po_number != undefined) {
            $.ajax({
                url: base_url + "/read_grn/",
                type: "GET",
                data: 'jd=1&is_ajax=1&edit_type=edit_grn&mode=modal&data=edit_grn&grn_id=' + grn_id+'&order_id='+po_number,
                success: function(response) {
                    if (response) {
                        $("#ajax_modal").html(response);
                        
                        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
                        $('[data-plugin="select_hrm"]').select2({ width: '100%' });
                    }
                }
            });
        }
    });


    //Add GRN Data
    $("#add_grn").submit(function(e) {
        // alert("ggg")
            e.preventDefault();
            var obj = $(this),
                action = obj.attr('name');
            $('.save').prop('disabled', true);
            $('.icon-spinner3').show();
            $.ajax({
                type: "POST",
                url: base_url + "/grn_add",
                data: obj.serialize() + "&is_ajax=1&add_type=add_grn&form=" + action,
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
                            window.location.reload();
                        }, true);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);                     
                        $('.select2-selection__rendered').html('--Select--');
                        $('.icon-spinner3').hide();
                        $('#add_grn')[0].reset(); // To reset form fields
                        $('.save').prop('disabled', false);                                                
                    }
                }
            });
        });



//GRN Edit
        /*Form Submit*/
        $("#edit_grn").submit(function(e) {
        // alert("ggg")
            e.preventDefault();
            var obj = $(this),
                action = obj.attr('name');
            $('.save').prop('disabled', true);
            $('.icon-spinner3').show();
            $.ajax({
                type: "POST",
                url: base_url + "/grn_update1",
                data: obj.serialize() + "&is_ajax=1&edit_type=edit_grn&form=" + action,
                cache: false,
                success: function(JSON) {
                    if (JSON.error != '') {
                        toastr.error(JSON.error);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                        $('.icon-spinner3').hide();
                    } else {
                    $('.edit-modal-data').modal('toggle');                        
                        xin_table.api().ajax.reload(function() {
                            toastr.success(JSON.result);
                        }, true);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                        $('.add-form').removeClass('in');
                        $('.select2-selection__rendered').html('--Select--');
                        $('.icon-spinner3').hide();
                        $('#xin-form')[0].reset(); // To reset form fields
                        $('.save').prop('disabled', false);
                    }
                }
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
    
    
        $(document).on("click", ".delete", function() {
            $('input[name=_token]').val($(this).data('record-id'));
            $('#delete_record').attr('action', base_url + '/grn_delete/' + $(this).data('record-id'));
        });

    /////////////////////////GRN END/////////////////////////////////

});