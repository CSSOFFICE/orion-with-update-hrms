$(document).ready(function() {
    $("#Inventory").addClass('active');

$('.edit-modal-data').on('show.bs.modal', function(event) {

    var button = $(event.relatedTarget);
    var product_id = button.data('product_id');
    var modal = $(this);
    if (product_id != undefined) {
        $.ajax({
            url: base_url + "/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&edit_type=product&mode=modal&data=product&product_id=' + product_id,
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


// $(document).on("click", ".delete", function() {
// // alert('hi');
//     $('input[name=_token]').val($(this).data('record-id'));
//     $('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
// });

});