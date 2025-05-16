<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['credit_id']) && $_GET['data']=='credit'){
  
?>
<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_credit_edit');?></h4>
</div>
<?php $attributes = array('name' => 'edit_invoice', 'id' => 'edit_credit', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' =>$_GET['credit_id'], 'ext_name' => $_GET['credit_id']);?>
<?php echo form_open('admin/finance/update_credit/'.$_GET["credit_id"], $attributes, $hidden);?>
<div class="modal-body">
    <input type="hidden" name="credit_id" value="<?php echo $_GET['credit_id']; ?>">
    

    <div class="row">
        <div class="col-md-12">
            <?php if($user_info[0]->user_role_id==1){ ?>
            <div class="form-group">
                <label for="first_name"><?php echo $this->lang->line('xin_project');?></label>
                <select class="form-control" name="u_project_id" id="u_project_id" data-plugin="select_hrm"
                    data-placeholder="<?php echo $this->lang->line('xin_project');?>">
                    <option value=""></option>
                    <?php foreach($get_all_project as $projects) {?>
                    <option value="<?php echo $projects->project_id ?>" <?php if($projects->project_id==$project_id) {?>
                        selected="selected" <?php } ?>><?php echo $projects->project_title; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } else {?>
            <?php $ecompany_id = $user_info[0]->company_id;?>
            <div class="form-group">
                <label for="u_project_id"><?php echo $this->lang->line('xin_project');?></label>
                <select class="form-control" name="u_project_id" id="u_project_id" data-plugin="select_hrm"
                    data-placeholder="<?php echo $this->lang->line('xin_project');?>">
                    <option value=""></option>
                    <?php foreach($get_all_project as $projects) {?>
                    <?php if($projects->project_id==$project_id):?>
                    <option value="<?php echo $projects->project_id?>" <?php if($projects->project_id==$project_id) {?>
                        selected="selected" <?php } ?>><?php echo $projects->project_title; ?></option>
                    <?php endif;?>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
        </div>
       
        <div class="col-md-12">
        <div class="row">
                <div class="col-md-12" id="client_address">
                    <label for="supplier_name"><?php echo $this->lang->line('xin_customer_address');?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_customer_address');?>"
                        name="u_customer_name" id="u_customer_name" rows="8" readonly> <?php echo $client_company_name."\n". $address." ".$client_phone;?></textarea>
                </div>
            </div>
            <div class="row" >
                <div class="col-md-12" id="attn_name">
                    <label for="attn_name"><?php echo $this->lang->line('xin_attn_name');?></label>
                    <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('xin_attn_name');?>" name="attn_name" id="attn_name"  readonly value="<?php echo $attn_name;?>">
                    <input type="hidden" name="client_id" value="<?php echo $project_clientid;?>">
                </div>
        <div class="form-group col-md-12" id="u_customer_address"></div>
        <div class="form-group  col-md-12">
                                <label for="terms">Invoice Date<i class="hrsale-asterisk">*</i></label>
                               <input type="text" id="u_invoice_date" name="u_invoice_date" class="form-control date" placeholder="Invoice Date" value="<?php echo date('d-m-Y',strtotime($invoice_date));?>">
                            </div>
                            <div  class="form-group col-md-12">
                                        <label for="payment_term"><?php echo $this->lang->line('xin_payment_term');?></label>
                                        <select class="form-control"
                                            placeholder="<?php echo $this->lang->line('xin_payment_term');?>"
                                            name="payment_term" id="u_terms">
                                            <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                                <?php foreach($all_payment_terms->result() as $payment_terms){ ?>
                                                        <option value="<?php echo $payment_terms->payment_term_id; ?>" <?php echo (($payment_terms->payment_term_id == $term_id) ? 'selected':'');?>><?php echo $payment_terms->payment_term; ?></option>
                                               <?php } ?>
                                        </select>
                                    </div>
                            <div class="form-group  col-md-12">
                                <label for="terms">Due Date<i class="hrsale-asterisk">*</i></label>
                               <input type="text" name="u_due_date" id="u_due_date" class="form-control date" placeholder="Invoice Due Date" value="<?php echo $invoice_due_date;?>">
                            </div>
                            <!-- <div class="form-group col-md-12">
                                <label for="bill_status"><?php echo $this->lang->line('xin_bill_status');?>
                                   
                                </label>
                               <select name="bill_status" class="form-control">
                                
                               <option value="not_paid" <?php echo ($bill_status == 'not_paid'?'selected':'');?>>Not Paid</option>
                                <option value="paid" <?php echo ($bill_status == 'paid'?'selected':'');?>>Paid</option>
                               

                               </select>
                            </div> -->
       
    </div>
    <div class="p-20">

        <div class="table-responsive my-3 purchaseTable">
            <table class="table">
                <thead>
                    <tr>
                    <th>Item</th>
                                                <th>Job Description</th>
                                                <th>Unit Price</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                    </tr>
                </thead>
                <tbody class="AddItem1" id="u_vendor_items_table1">
                    <?php $i=0; foreach($get_all_items as $items){ ?>
                        <tr>
                    <td style="min-width:130px">
                            <label><?php echo ++$i; ?><label>
                        </td>
                        <td style="min-width:500px">
                            <textarea name="u_description[]" id="u_description<?php echo $i; ?>" placeholder="Description" style="width:500px"><?php echo $items->job_description; ?></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="u_cost<?php echo $i; ?>"  class="form-control calculate" name="u_cost[]" id="u_cost_<?php echo $i; ?>" value="<?php echo $items->cost; ?>" placeholder="Cost" onkeyup="u_calculation(<?php echo $i; ?>)">
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="u_type_id[]" id="u_type_id_<?php echo $i; ?>" onchange="get_type('<?php echo $i; ?>');">
                                <option value="">Select Type</option>
                                <option value="add" <?php echo(($items->total > 0)?'selected':''); ?>>Addition</option>
                                <option value="subtraction" <?php echo(($items->total < 0)?'selected':''); ?>>Subtraction</option>

                            </select>
                        </td>
                        
                        <td style="min-width:200px">
                            <input type="text" name="u_amount[]" id="u_amount<?php echo $i; ?>"  placeholder="Total Amount" value="<?php echo $items->total; ?>" class="calculate form-control " onkeyup="u_calculation(<?php echo $i; ?>)">
                        </td>
                        
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="border: none !important;">
                            <a href="javascript:void(0)" class="btn-sm btn-success" id="u_addButton2">Add</a>
                        </th>
                    </tr>
                </tfoot>
            </table>

            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">

                    <div class="form-group row" style="margin-bottom: 0;">
                        <div class="col-12">
                            <label for="u_sub_total" class="form-label">Sub Total<span
                                    class="text-danger">*</span></label>
                                    <input class="form-control" readonly type="text" name="u_sub_total"
                                                    id="u_sub_total" placeholder="0" value="<?php echo $sub_total;?>">
                        </div>
                    </div>

                    <div class="form-group row" style="margin-bottom: 0;">
                    <div class="col-12">
                            <input type="checkbox" class="listcheckbox listcheckbox-files filled-in chk-col-light-blue" id="is_gst1" name="is_gst1" value="1" <?php echo(($is_gst_inclusive == "1")?'checked':'');?>><label for="is_gst1">GST Inclusive</label>
                    </div>
                        <div class="col-12" id="gst_div1">
                   
                        
                            <label for="total_gst1" class="form-label">GST</label>
                            <select class="form-control select22" id="u_total_gst1" name="u_gst" onchange="totalGSTAmount()">
                                            <?php foreach($get_gst->result() as $gst){ ?>
                                                <option value="<?php echo $gst->gst; ?>" <?php echo (($gst->gst == $gst_value)?'selected':'');?>><?php echo $gst->gst;?></option>
                                            <?php } ?>
                                        </select>
                            <!-- <input class="form-control" type="text" value="" id="u_total_gst1" name="u_total_gst1"
                                placeholder="0" value="<?php //echo (($gst >0)?$gst:0) ;?>" onkeyup="totalGSTAmount()"> -->
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row" style="margin-bottom: 0;">
                        <div class="col-12">
                            <label for="total_amount" class="form-label">Total Amount<span
                                    class="text-danger">*</span></label>
                            <input class="form-control" readonly type="text" name="u_total_amount" id="u_total_amount"
                                placeholder="0" value="<?php echo $total;?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
            <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
        </div>
        <?php echo form_close(); ?>
        <?php }
?>
       <script>
$(document).ready(function() {
    $('.date').datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'dd-mm-yyyy',
        yearRange: '1900:' + (new Date().getFullYear() + 15),
        beforeShow: function(input) {
            $(input).datepicker("widget").show();
        }
    });
    <?php if($is_gst_inclusive == "1"){ ?>
        $("#gst_div1").hide();
    <?php } ?>
    $("#is_gst1").change(function() {
    if(this.checked) {
       $("#gst_div1").hide();
    }
  else
    {
        $("#gst_div1").show();

      
    }
});
    $("#u_terms").on("change",function(){
        var invoice_date = $("#u_invoice_date").val();
        
        var term_text=$("#u_terms option:selected").text();
        term_text = parseInt(term_text.replace("days",""));
        
        var date = new Date(invoice_date.split("-").reverse().join("-"));
        //alert(date);return false;
        date.setDate(date.getDate() + term_text);
       // alert(date);return false;
        var month = date.getMonth()+1;
        var day = date.getDate();

        var output = (day<10 ? '0' : '') + day+'-'+(month<10 ? '0' : '') + month + '-' + date.getFullYear();
                $('#u_due_date').val(output);
                
       
    });

    var counter = 0;
    $("#task_div").hide();
    var input = $('.timepicker_m').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });

    $('#u_addButton2').on('click', function() {
        var number = $('.AddItem1 tr').length;
        var item = number + 1;
        $('.AddItem1').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label>` + item + `<label>
                        </td>
                        <td style="min-width:500px">
                            <textarea name="u_description[]" id="u_description${++$('.AddItem1 tr').length}" placeholder="Description" style="width:500px"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="u_cost${++$('.AddItem1 tr').length}"  class="form-control calculate" name="u_cost[]" id="u_cost_`+item+`" placeholder="Cost" onkeyup="u_calculation(${++$('.AddItem1 tr').length})">
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="u_type_id[]" id="u_type_id_`+item+`" onchange="get_type('`+item+`');">
                                <option value="">Select Type</option>
                                <option value="add">Addition</option>
                                <option value="subtraction">Subtraction</option>

                            </select>
                        </td>
                        
                        <td style="min-width:200px">
                            <input type="text" name="u_amount[]" id="u_amount`+item+`"  placeholder="Total Amount" class="calculate form-control" onkeyup="u_calculation(${++$('.AddItem1 tr').length})">
                        </td>
                        
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

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

    $('#u_vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="u_amount[]"]').val());

    });

    // if ($('#tax_inclusive1').prop('checked') == true) {
    //     total_tax = (parseFloat(total_amount) * tax) / 100;
    //     total = total_amount + total_tax;
    // } else {
    //     total = total_amount;
    // }

    $('#u_sub_total').val(total_amount.toFixed(2));

    $('#u_total_gst1').val(total_tax.toFixed(2));

    $('#u_total_amount1').val(total.toFixed(2));

}

function u_calculation(id) {
    var total = 0;
    var total_tax = 0;
    var final_total = 0;
    var total_amount = 0;

    var unit_price = $("#u_cost" + id).val();
    var type = $("#u_type_id_"+id+" option:selected").text();
    var cost=$("#u_cost"+id).val();
    if(type == "Select Type" || type == "Addition"){
        $("#u_amount"+id).val("+" + cost)
    }else{
        $("#u_amount"+id).val("-" + cost)
    }

    
    $('#u_vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="u_amount[]"]').val());

       
       
    

    });

    // if($('#tax_inclusive1').prop('checked') == true){
    //     total_tax = (parseFloat(total_amount)*tax)/100;
    //     total = total_amount+total_tax;
    // }else{
    //     total = total_amount;
    // }
    //var tax = parseFloat($('#total_gst1').val());
    // var tax = parseFloat($("#u_total_gst1 option:selected").text());
    // if (tax > 0) {
    //     var final_total = total_amount + total_amount * (tax / 100);

    // } else {
    //     var final_total = total_amount;
    // }
    var final_total = total_amount;

    $('#u_sub_total').val(total_amount.toFixed(2));

    $('#u_total_gst1').val(total_tax.toFixed(2));

    $('#u_total_amount').val(final_total.toFixed(2));
}

function totalGSTAmount() {
    var total_amount = parseFloat($('#u_sub_total').val());
    var tax = parseFloat($("#u_total_gst1 option:selected").text());
    var total = total_amount + total_amount * (tax / 100);
    $('#u_total_amount').val(total);
}
jQuery("#u_project_id").change(function() {
    jQuery("#client_address").hide();
    jQuery("#attn_name").hide();
    
    jQuery.get(base_url + "/get_customer_address/" + jQuery(this).val(), function(data, status) {
        jQuery("#u_customer_address").show();
        jQuery('#u_customer_address').html(data);
    });
});
function get_type(id){
    var total = 0;
    var total_tax = 0;
    var final_total = 0;
    var total_amount = 0;
    
    var type = $("#u_type_id_"+id+" option:selected").text();
    var cost=$("#u_cost"+id).val();
    if(type == "Select Type" ||  type == "Addition"){
        $("#u_amount"+id).val("+" + cost)
    }else{
        $("#u_amount"+id).val("-" + cost)
    }

    $('#u_vendor_items_table1 > tr').each(function() {

        total_amount += parseFloat($(this).find('input[name="u_amount[]"]').val());

    });

    var tax = parseFloat($("#u_total_gst1 option:selected").text());
    if (tax > 0) {
        var final_total = total_amount + total_amount * (tax / 100);

    } else {
        var final_total = total_amount;
    }
    $('#u_sub_total').val(total_amount.toFixed(2));

    $('#u_total_gst1').val(total_tax.toFixed(2));

    $('#u_total_amount1').val(final_total.toFixed(2));
}
$("#edit_credit").submit(function(e) {
        
        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 1);
        fd.append("edit_type", 'credit');
        fd.append("form", action);

        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: base_url + "/update_credit",
            data: obj.serialize() + "&is_ajax=1&edit_type=credit&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                    $('#ajax_modal').modal('toggle');
                } else {

                    toastr.success(JSON.result);


                }
            }
        });
    });
</script>