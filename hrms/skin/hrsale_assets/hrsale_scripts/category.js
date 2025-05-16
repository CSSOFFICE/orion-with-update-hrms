$(document).ready(function() {
    $("#Inventory").addClass('active');

    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: base_url + '/category_list/',
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
            var fd=new FormData(this);
            fd.append('is_ajax',1);
            fd.append('add_type','category');
            fd.append('form',action);
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/add_category",
            // data: obj.serialize() + "&is_ajax=1&add_type=product&form=" + action,
            data:fd,                       
            contentType: false,
            cache: false,
            processData:false,
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



$('.edit-modal-data').on('show.bs.modal', function(event) {

    var button = $(event.relatedTarget);
    var category_id = button.data('category_id');
    var modal = $(this);
    if (category_id != undefined) {
        $.ajax({
            url: base_url + "/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&edit_type=category&mode=modal&data=category&category_id=' + category_id,
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
    var product_id = button.data('product_id');
    var modal = $(this);
    if (product_id != undefined) {
        $.ajax({
            url: base_url + "/product_suppliers/",
            type: "GET",
            data: 'jd=1&is_ajax=1&edit_type=product&mode=modal&data=product&product_id=' + product_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal_view").html(response);
                }
            }
        });
    }
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
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                toastr.error(JSON.error);
            } else {
                $('.delete-modal').modal('toggle');
                var xin_table = $('#xin_table').dataTable({
                    "bDestroy": true,
                    "ajax": {
                        url: base_url + '/category_list/',
                        type: 'GET'
                    },
                    /*dom: 'lBfrtip',
                    "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
                    "fnDrawCallback": function(settings) {
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
                xin_table.api().ajax.reload(function() {
                    toastr.success(JSON.result);
                }, true);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            }
        }
    });
});