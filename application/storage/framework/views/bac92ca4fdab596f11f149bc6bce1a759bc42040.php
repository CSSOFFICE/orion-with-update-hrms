<div class="row" id="js-trigger-invoices-modal-add-edit" data-payload="<?php echo e($page['section'] ?? ''); ?>">
    <div class="col-lg-12">

        <!--meta data - creatd by-->
        <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
        <div class="modal-meta-data">
            <small><strong><?php echo e(cleanLang(__('lang.created_by'))); ?>:</strong> <?php echo e($invoice->first_name); ?> <?php echo e($invoice->last_name); ?> |
                <?php echo e(runtimeDate($invoice->bill_created)); ?></small>
        </div>
        <?php endif; ?>

        <!--invoice date-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.invoice_date'))); ?>*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm pickadate" id="invoice_date" name="bill_date_add_edit" autocomplete="off"
                    value="<?php echo e(runtimeDatepickerDate($invoice->invoice_date ?? '')); ?>">
                <input class="mysql-date" type="hidden" name="bill_date" id="bill_date_add_edit"
                    value="<?php echo e($invoice->bill_date ?? ''); ?>">
            </div>
        </div>


        <input type="hidden" name="client_id" value="<?php echo e($client[0]->project_clientid); ?>">
        <input type="hidden" name="project_id" value="<?php echo e($client[0]->project_id); ?>">
        <input type="hidden" name="total_invoice_amount" value="<?php echo e($total_invoice_amount); ?>">
        





        <div class="form-group row">
                                <label for="payment_terms" class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Payment Terms
                                </label>
                                <div class="col-sm-12 col-lg-9">
                               <select name="payment_terms" class="form-control" placeholder="Terms" id="terms">
                                    <option value="">Select Payment Term</option>
                                    <?php $__currentLoopData = $payment_terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $terms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>:
                                        <option value="<?php echo e($terms->payment_term_id); ?>"><?php echo e($terms->payment_term); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                               </select>
                            </div>
        </div>

<!--due date-->
<div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.due_date'))); ?>*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" id="due_date" class="form-control form-control-sm pickadate" name="bill_due_date_add_edit"
                    autocomplete="off" value="<?php echo e(runtimeDatepickerDate($invoice->invoice_due_date ?? '')); ?>">
                <input class="mysql-date" type="hidden" name="bill_due_date" id="bill_due_date_add_edit"
                    value="<?php echo e($invoice->bill_due_date ?? ''); ?>">
            </div>
        </div>

        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
            </div>
        </div>
        <div class="col-12">

<div class="table-responsive my-3 purchaseTable">
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Job Description</th>
                <th>Cost Price</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="AddItem" id="vendor_items_table1"></tbody>
        <tfoot>
            <tr>
                <th style="border: none !important;">
                    <a href="javascript:void(0)" class="btn-sm btn-success"
                        id="addButton1">Add</a>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="row">
    <div class="col-md-6">

    </div>
    <div class="col-md-6">

        <div class="form-group row" style="margin-bottom: 0;">
            <div class="col-12">
                <label for="sub_total" class="form-label">Sub Total<span
                        class="text-danger">*</span></label>
                <input class="form-control" readonly type="text" name="sub_total"
                    id="sub_total" placeholder="0">
            </div>
        </div>
        <div class="col-12">
                                    <input type="checkbox" class="listcheckbox listcheckbox-files filled-in chk-col-light-blue" id="is_gst" name="is_gst_inclusive" value="1"><label for="is_gst">GST Inclusive</label>
                                </div>
        <div class="form-group row" style="margin-bottom: 0;" id="gst_div">
            <div class="col-12">
                <label for="total_gst1" class="form-label">GST</label>
                <select class="form-control" id="total_gst1" name="gst"
                    onchange="totalGSTAmount()">
                    <?php foreach($gst as $g){ ?>
                    <option value="<?php echo $g->gst; ?>"><?php echo $g->gst;?>
                    </option>
                    <?php } ?>
                </select>
                <!-- <input class="form-control" type="text" value="" id="total_gst1" name="gst"
                 placeholder="0" onkeyup="totalGSTAmount()"> -->
            </div>
        </div>

        <hr>

        <div class="form-group row" style="margin-bottom: 0;">
            <div class="col-12">
                <label for="total_amount" class="form-label">Total Amount<span
                        class="text-danger">*</span></label>
                <input class="form-control" readonly type="text" name="total_amount"
                    value="" id="total_amount1" placeholder="0">
            </div>
        </div>

    </div>
</div>
</div>
        <!--recurring notes-->
        <!-- <div class="alert alert-info m-t-10"><i class="sl-icon-refresh text-warning"></i>
            <?php echo e(cleanLang(__('lang.recurring_invoice_options_info'))); ?></div>
    </div> -->
</div>

<script>
$(document).ready(function() {
    $("#is_gst").change(function() {
    if(this.checked) {
       $("#gst_div").hide();
    }
  else
    {
        $("#gst_div").show();


    }
});
    var counter = 0;
    $("#task_div").hide();

    $("#terms").on("change",function(){
        var invoice_date = $("#invoice_date").val();

        var term_text=$("#terms option:selected").text();
        term_text = parseInt(term_text.replace("days",""));

        var date = new Date(invoice_date.split("-").reverse().join("-"));
        //alert(date);return false;
        date.setDate(date.getDate() + term_text);
       // alert(date);return false;
        var month = date.getMonth()+1;
        var day = date.getDate();

        var output = (day<10 ? '0' : '') + day+'-'+(month<10 ? '0' : '') + month + '-' + date.getFullYear();
                $('#due_date').val(output);
                $('#bill_due_date_add_edit').val(output);

    });
    $('#addButton1').on('click', function() {
        var number = $('.AddItem tr').length;
        var item = number + 1;
        $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item + `<label>
                        </td>
                        <td style="min-width:500px">
                            <textarea name="description[]" class="description" id="description${++$('.AddItem tr').length}" placeholder="Description" style="width:500px"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="cost${++$('.AddItem tr').length}"  class="form-control calculate cost" name="cost[]" id="cost_`+item+`" placeholder="Cost" onkeyup="calculation(${++$('.AddItem tr').length})">
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="type_id[]" id="type_id_`+item+`" onchange="get_type('`+item+`');">
                                <option value="">Select Type</option>
                                <option value="add">Addition</option>
                                <option value="subtraction">Subtraction</option>

                            </select>
                        </td>

                        <td style="min-width:200px">
                            <input type="text" name="amount[]" id="amount`+item+`"  placeholder="Total Amount" class="form-control calculate amount" onkeyup="calculation(${++$('.AddItem tr').length})">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

    });

});

$("#commonModalSubmitButton").unbind().click(function(e){
    e.preventDefault();
        let project_product_details = [];
        $('#vendor_items_table1 > tr').each(function(e) {
            let description = $(this).find('.description').val();
            let cost = $(this).find('.cost').val();
            let amount = $(this).find('.amount').val();

            if (description != '' && cost != '' && amount != '') {

                let arr = {
                    description: description,
                    cost: cost,
                    amount: amount,
             }

         project_product_details.push(arr);
     }else {
         noty({
             text: 'Please Enter At least one detail',
             layout: 'bottomLeft',
             type: 'warning',
             timeout: '3000',
             progressBar: false,
             closeWith: ['click', 'button', 'backdrop'],
         });
     }

        });

        let url = "<?php echo e(urlResource('/invoices/create')); ?>";

        let form = $('#commonModalForm')[0];
        let data = new FormData(form);
        data.append('project_product_details', JSON.stringify(project_product_details));
        data.append("_token", "<?php echo e(csrf_token()); ?>")
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: data,
            processData: false,
            contentType: false,
            success: function(payload) {
                console.log(payload);
                //if (payload.notification.type == 'success') {
                    noty({
                        text: payload.notification.value,
                        layout: 'bottomLeft',
                        type: 'success',
                        timeout: '3000',
                        progressBar: false,
                        closeWith: ['click', 'button', 'backdrop'],
                    });

                    window.location.reload()
               // }

            },
            error: function(error) {
                console.log(error)
                if (error.responseJSON.notification.type == 'error') {
                    noty({
                        text: error.responseJSON.notification.value,
                        layout: 'bottomLeft',
                        type: 'warning',
                        timeout: '3000',
                        progressBar: false,
                        closeWith: ['click', 'button', 'backdrop'],
                    });
                }
            }
        });
    });
$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();

    updateCalculationPQ();
});

function updateCalculationPQ() {

    var total = 0;
    var total_tax = 0;
    var untaxed = 0;
    var total_amount = 0;
    var tax = parseFloat($('#tax').val());

    $('#vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

    });

    if ($('#tax_inclusive1').prop('checked') == true) {
        total_tax = (parseFloat(total_amount) * tax) / 100;
        total = total_amount + total_tax;
    } else {
        total = total_amount;
    }

    $('#sub_total').val(total_amount.toFixed(2));

    $('#total_gst1').val(total_tax.toFixed(2));

    $('#total_amount1').val(total.toFixed(2));

}

function calculation(id) {
    var total = 0;
    var total_tax = 0;
    var final_total = 0;
    var total_amount = 0;

    var unit_price = $("#cost" + id).val();
    var type = $("#type_id_"+id+" option:selected").text();
    var cost=$("#cost"+id).val();
    if(type == "Select Type" || type == "Addition"){
        $("#amount"+id).val("+" + cost)
    }else{
        $("#amount"+id).val("-" + cost)
    }

    // var total = parseFloat(unit_price) * parseFloat(quantity);
    // if (total > 0) {
    //     $("#amount" + id).val(total);
    // } else {
    //     $("#amount" + id).val('0');
    // }





    $('#vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

    });

    // if($('#tax_inclusive1').prop('checked') == true){
    //     total_tax = (parseFloat(total_amount)*tax)/100;
    //     total = total_amount+total_tax;
    // }else{
    //     total = total_amount;
    // }
    //var tax = parseFloat($('#total_gst1').val());
    var tax = parseFloat($("#total_gst1 option:selected").text());
    if (tax > 0) {
        var final_total = total_amount + total_amount * (tax / 100);

    } else {
        var final_total = total_amount;
    }
    $('#sub_total').val(total_amount.toFixed(2));

    $('#total_gst1').val(total_tax.toFixed(2));

    $('#total_amount1').val(final_total.toFixed(2));
}

function totalGSTAmount() {
    var total_amount = parseFloat($('#sub_total').val());
    var tax = parseFloat($("#total_gst1 option:selected").text());
    var total = total_amount + total_amount * (tax / 100);
    $('#total_amount1').val(total);
}
jQuery("#project_id").change(function() {

    jQuery.get(base_url + "/get_customer_address/" + jQuery(this).val(), function(data, status) {
        jQuery('#customer_address').html(data);
    });
});
function get_type(id){
    var total = 0;
    var total_tax = 0;
    var final_total = 0;
    var total_amount = 0;

    var type = $("#type_id_"+id+" option:selected").text();
    var cost=$("#cost"+id).val();
    if(type == "Select Type" ||  type == "Addition"){
        $("#amount"+id).val("+" + cost)
    }else{
        $("#amount"+id).val("-" + cost)
    }

    $('#vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="amount[]"]').val());

    });

    var tax = parseFloat($("#total_gst1 option:selected").text());
    if (tax > 0) {
        var final_total = total_amount + total_amount * (tax / 100);

    } else {
        var final_total = total_amount;
    }
    $('#sub_total').val(total_amount.toFixed(2));

    $('#total_gst1').val(total_tax.toFixed(2));

    $('#total_amount1').val(final_total.toFixed(2));
}
</script>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/invoices/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>