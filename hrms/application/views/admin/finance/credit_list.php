<style>
    [type="checkbox"]:checked + label:before{
        display: none;
    }
    [type="checkbox"]:checked + label {
        padding-left: 12px;
        
    }
    [type="checkbox"].filled-in:checked.chk-col-light-blue + label:after {
        display: none;
    }
    #add_form{
        height:100%!important;
    }
</style>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Xin_model->get_content_animate();?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if(in_array('3102',$role_resources_ids)) {?>

<div class="box mb-4 <?php echo $get_animate;?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Credit List</h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form"
                    aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new');?></button>
                </a> </div>
        </div>
        <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
            <div class="box-body">
                <?php $attributes = array('name' => 'add_quotation', 'id' => 'xin-form', 'autocomplete' => 'off');?>
                <?php $hidden = array('user_id' => $session['user_id']);?>
                <?php echo form_open_multipart('admin/finance/add_credit', $attributes, $hidden);?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                            <div class="form-group">
                                <label for="project_id"><?php echo $this->lang->line('xin_project');?>
                                    <i class="hrsale-asterisk">*</i>
                                </label>
                                <select class="form-control" name="project_id" id="project_id" data-plugin="xin_select"
                                    data-placeholder="<?php echo $this->lang->line('xin_customer');?>">
                                    <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                                    <?php foreach($get_all_projects as $project) {?>
                                    <option value="<?php echo $project->project_id;?>">
                                        <?php echo $project->project_title;?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div id="customer_address"></div>
                            <div id="invoice_detail"></div>
                            <!-- <div class="col-md-6">
                                        <label for="attn_name">ATTN</label>
                                        <input type="text" name="attn_name" id="attn_name" class="form-control"
                                            placeholder="Attn">
                                    </div> 

                             <div class="form-group">
                                <label
                                    for="note"><?php echo $this->lang->line('xin_quotation_terms_condition');?></label>
                                <textarea class="form-control"
                                    placeholder="<?php echo $this->lang->line('xin_quotation_terms_condition');?>"
                                    name="terms_condition"></textarea>
                            </div> -->
                            
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

                                        <div class="form-group row" style="margin-bottom: 0;">
                                        <div class="col-12">
                                    <input type="checkbox" class="listcheckbox listcheckbox-files filled-in chk-col-light-blue" id="is_gst" name="is_gst" value="1"><label for="is_gst">GST Inclusive</label>
                                </div>
                                    <div class="col-12" id="gst_div">
                                            
                                                <label for="total_gst1" class="form-label">GST</label>
                                                <select class="form-control" id="total_gst1" name="gst"
                                                    onchange="totalGSTAmount()">
                                                    <?php foreach($get_gst->result() as $gst){ ?>
                                                    <option value="<?php echo $gst->gst; ?>"><?php echo $gst->gst;?>
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
                        </div>


                    </div>
                </div>
                <div class="form-actions box-footer">
                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                        <?php echo $this->lang->line('xin_save');?> </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all');?>
            <?php echo $this->lang->line('xin_invoices_title');?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('xin_action');?></th>
                        <th><?php echo $this->lang->line('xin_project');?></th>
                        <th><?php echo $this->lang->line('xin_customer');?></th>
                        <th><?php echo $this->lang->line('xin_created_date');?></th>


                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
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
                
       
    });
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
    var input = $('.timepicker_m').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
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
                            <textarea name="description[]" id="description${++$('.AddItem tr').length}" placeholder="Description" style="width:500px"></textarea>
                        </td>
                        <td style="min-width:200px">
                            <input type="number" min="0" id="cost${++$('.AddItem tr').length}"  class="form-control calculate" name="cost[]" id="cost_`+item+`" placeholder="Cost" onkeyup="calculation(${++$('.AddItem tr').length})">
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="type_id[]" id="type_id_`+item+`" onchange="get_type('`+item+`');">
                                <option value="">Select Type</option>
                                <option value="add">Addition</option>
                                <option value="subtraction">Subtraction</option>

                            </select>
                        </td>
                        
                        <td style="min-width:200px">
                            <input type="text" name="amount[]" id="amount`+item+`"  placeholder="Total Amount" class="calculate form-control" onkeyup="calculation(${++$('.AddItem tr').length})">
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
    // var tax = parseFloat($("#total_gst1 option:selected").text());
    // if (tax > 0) {
    //     var final_total = total_amount + total_amount * (tax / 100);

    // } else {
    //     var final_total = total_amount;
    // }
         var final_total = total_amount;

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