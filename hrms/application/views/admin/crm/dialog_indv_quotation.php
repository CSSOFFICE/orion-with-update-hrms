<?php


defined('BASEPATH') or exit('No direct script access allowed');

if (isset($_GET['jd']) && isset($_GET['crm_id']) && $_GET['data'] == 'crm') {

?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-Individual-modal-data"><?php echo "Edit Quotation Data"; ?></h4>
    </div>
    <?php $attributes = array('name' => 'edit_indv_pro_crm', 'id' => 'edit_indv_quote_crm123', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => 1, 'ext_name' => 1); ?>
    <?php echo form_open('admin/crm/indv_quote_update/' . 1, $attributes, $hidden); ?>
    <div class="modal-body">
        <input type="hidden" name="quotation_id" value="<?php echo  $result[0]->crm_q_id; ?>">

        <input type="hidden" id="crm_i" name="crm_i" value="<?php echo $result[0]->quote_for ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <label for="quotation_amount">Quotation Subject Title <i class="hrsale-asterisk">*</i></label>
                        <input type="text" name="q_title" id="q_title" value="<?php echo $result[0]->q_title;?>" class="form-control" placeholder="Quotation Subject Title">
                    </div>
                    <div class="col-md-6">
                        <label for="quotation_amount">Project Name <i class="hrsale-asterisk">*</i></label>
                        <input type="text" name="proj_name" id="proj_name" value="<?php echo $result[0]->proj_name;?>" class="form-control" placeholder="Quotation Subject Title">
                    </div>


                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-md-6">
                            <label for="acceptance_letter_no">Project Site Address <i class="hrsale-asterisk">*</i></label>
                            <textarea class="form-control" placeholder="Project Site" name="project_s_add"><?php echo $result[0]->project_s_add; ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="quotation_amount">Payment Term<i class="hrsale-asterisk">*</i></label>
                            <select class="form-control" name="pay_term" id="pay_term">
                                <option value="">Select</option>
                                <?php foreach ($all_payment_terms as $term) { ?>
                                    <option value="<?php echo $term->payment_term ?>" <?php if ($result[0]->pay_term == $term->payment_term) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $term->payment_term ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        
                        <div class="col-md-6">
                            <label for="quotation_amount">Shipping Term<i class="hrsale-asterisk">*</i></label>
                            <select class="form-control" name="ship_term" id="ship_term">
                            <option value="">Select</option>
                                <?php foreach($all_shipping_terms as $term){?>
                                    <option value="<?php echo $term->shipping_term?>" <?php if($result[0]->ship_term == $term->shipping_term){echo "selected";}?>><?php echo $term->shipping_term?></option>
                                <?php }?>
                            </select>                                            
                        </div>
                        

                        <div class="col-md-6">

                            <label for="q_validity">Quotation Validity <i class="hrsale-asterisk">* </i></label>
                            <input class="form-control date" placeholder="Select Required date" name="q_validity" id="q_validity" type="text" value="<?php echo $result[0]->q_date ?>">
                        </div>


                        <div class="col-md-6">
                            <label for="quote_pic">PIC Name</label>
                            <input type="text" name="pic_name" id="pic_name" class="form-control" placeholder="PIC Name" value="<?php echo $result[0]->quote_pic; ?>" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>>
                        </div>
                        <div class="col-md-6">
                            <label for="quote_email">PIC Email</label>
                            <input type="email" name="pic_email" id="pic_email" class="form-control" placeholder="Email" value="<?php echo $result[0]->quote_email; ?>" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>>
                        </div>
                        <div class="col-md-6">
                            <label for="quote_phone">PIC Number</label>
                            <input type="number" name="pic_phone" id="pic_phone" class="form-control" placeholder="PIC Number" value="<?php echo $result[0]->quote_phone; ?>" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>>
                        </div>
                        <div class="row pt-4">
                            <div class="col-md-4">
                            
                                <label>Quotation Terms & Condition</label>
                                <select name="term_condition_id" id="term_condition_id" class="form-control">
                                <option>Select Term Condition</option>
                                <?php foreach($get_term_condition as $term_condition){ ?>
                                <option value="<?php echo $term_condition->term_id; ?>" <?php if($term_condition->term_id == $result[0]->term_condition_id){ echo "selected";}?>><?php echo $term_condition->term_title; ?></option>
                                            <?php } ?>
                                        </select>
                                </div>
                            
                            <div class="col-md-4">
                                <label> </label>
                                <textarea class="form-control" placeholder="Quotation Terms &amp; Condition" name="terms_condition" id="terms_conditions"><?php echo $result[0]->term_condition_id; ?></textarea>
                            </div>
                            </div>
                        <div class="form-group">

                            <a href="javascript:void(0)" class="btn-sm btn-success task">Add New Task</a>

                        </div>
                        <div class="col-md-12">
                            <div id="task_div1">
                                <div style="margin-top:10px;">
                                    <div class="row">
                                        <?php $i = 1;
                                        foreach ($get_all_task as $tasks) { ?>
                                            <div class="col-md-12">

                                                <div class="form-group">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <label>Task <?php echo $i; ?></label>
                                                            <input type="text" name="task_name[]" class="form-control" placeholder="Task Name" value="<?php echo $tasks->task; ?>" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>>
                                                        </div>

                                                    </div>
                                                </div>


                                            </div>

                                            <div class="col-md-12">

                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab" href="#tabs-1-<?php echo $i; ?>" role="tab">Task Description</a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#tabs-2-<?php echo $i; ?>" role="tab">Sub Tasks</a>
                                                    </li>
                                                </ul><!-- Tab panes -->
                                                <div class="tab-content" style="border: 1px solid; border-top: none; border-color: #dee2e6;">
                                                    <div class="tab-pane active" id="tabs-1-<?php echo $i; ?>" role="tabpanel">
                                                        <div class="container-fluid">
                                                            <textarea name="task_description[]" id="task_description<?php echo $i; ?>" placeholder="Detail" style="width:250px" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>><?php echo $tasks->task_description; ?></textarea>
                                                        </div>

                                                    </div>

                                                    <div class="tab-pane" id="tabs-2-<?php echo $i; ?>" role="tabpanel">
                                                        <div class="container-fluid">
                                                            <table class="table table-responsive">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sl</th>
                                                                        <th>Product</th>
                                                                        <th>Description</th>
                                                                        <th>Detail</th>
                                                                        <th>Unit</th>
                                                                        <th>Price</th>
                                                                        <th>Qtn</th>
                                                                        <th>Gross Price</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="AddItem" id="vendor_items_table<?php echo $i; ?>">
                                                                    <?php $j = 1;
                                                                    foreach ($get_all_subtasks as $subtasks) { ?>
                                                                        <tr>
                                                                            <td style="min-width:1px">
                                                                                <label><?php echo $j; ?><label>
                                                                            </td>
                                                                            <td style="min-width:2px">
                                                                                <textarea name="product[]" id="product<?php echo $j; ?>" placeholder="Product" cols="10" rows="2" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>><?php echo $subtasks->product; ?></textarea>
                                                                            </td>
                                                                            <td style="min-width:2px">
                                                                                <textarea name="description[]" id="description<?php echo $j; ?>" placeholder="Description" cols="10" rows="2" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>><?php echo $subtasks->description; ?></textarea>
                                                                            </td>
                                                                            <td style="min-width:0px">
                                                                                <textarea name="detail[]" id="detail<?php echo $j; ?>" placeholder="Detail" cols="10" rows="2" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>><?php echo $subtasks->detail; ?></textarea>
                                                                            </td>
                                                                            <td style="min-width:0px">
                                                                                <select class="packing_dropdown form-control select22" name="unit_id[]" style="width:auto;">
                                                                                    <option value="">Select Unit</option>
                                                                                    <?php foreach ($all_units->result() as $unit) { ?>
                                                                                        <option value="<?php echo $unit->unit_id; ?>" <?php echo (($subtasks->unit_id == $unit->unit_id) ? 'selected' : ''); ?>><?php echo $unit->unit; ?></option>
                                                                                    <?php } ?>

                                                                                </select>
                                                                            </td>

                                                                            <td style="min-width:0px">
                                                                                <input type="text" name="unit_rate[]" id="unit_rate<?php echo $j; ?>" placeholder="Unit Rate" class="form-control calculate" value="<?php echo $subtasks->unit_rate; ?>" <?php echo (($result[0]->status == "confirmed" ? "readonly" : "")); ?>>
                                                                            </td>
                                                                            <td><input type="number" class="form-control calculate" name="qtn[]" id="quantity<?php echo $j; ?>" oninput="calculation(<?php echo $j; ?>)" value="<?php echo $subtasks->qtn; ?>"></td>
                                                                            <td style="min-width:0px">
                                                                                <input type="text" name="gross_price[]" id="gross_price_<?php echo $j; ?>" placeholder="Gross Amount" class="form-control" value="<?php echo ($subtasks->qtn) * ($subtasks->unit_rate); ?>">
                                                                            </td>

                                                                            <td>
                                                                                <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php $j++;
                                                                    } ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th style="border: none !important;">
                                                                            <a href="javascript:void(0)" class="btn-sm btn-success addButton1" onclick="sub_tasks('<?php echo $i; ?>')">Add</a>
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>

                                                            <div class="form-group">
                                                                <div class="col-md-6" style="float:right;">
                                                                    <div class="form-group row" style="margin-bottom: 0;">
                                                                        <div class="col-12">
                                                                            <label for="sub_total" class="form-label">Sub Total<span class="text-danger">*</span></label>
                                                                            <input class="form-control" readonly type="text" name="sub_total" id="sub_total" placeholder="0" value="<?php echo $result[0]->total_item_amount; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group" style="margin-bottom: 0;">
                                                                        <div class="col-12">
                                                                            <input type="checkbox" class="listcheckbox listcheckbox-files" id="is_gst1" name="is_gst1" value="1" <?php echo (($result[0]->is_gst_inclusive == "1") ? 'checked' : ''); ?>><label for="is_gst1">GST Inclusive</label>
                                                                        </div>


                                                                        <div class="col-12" id="gst_div1">
                                                                            <label for="total_gst1" class="form-label">GST</label>
                                                                            <select class="form-control select22" id="total_gst1" name="total_gst1" onchange="totalGSTAmount()">
                                                                                <?php foreach ($get_gst as $gst) { ?>
                                                                                    <option value="<?php echo $gst->gst; ?>" <?php echo (($gst->gst == $result[0]->gst) ? 'selected' : ''); ?>><?php echo $gst->gst; ?></option>
                                                                                <?php } ?>
                                                                            </select>

                                                                        </div>
                                                                    </div>

                                                                    <hr>

                                                                    <div class="form-group row" style="margin-bottom: 0;">
                                                                        <div class="col-12">
                                                                            <label for="total_amount1" class="form-label">Total Amount<span class="text-danger">*</span></label>
                                                                            <input class="form-control" readonly type="text" name="total_amount" id="total_amount1" placeholder="0" value="<?php echo $result[0]->total; ?>">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                    </div>
                                </div>
                            <?php $i++;
                                        } ?>
                            </div>
                            </div>
                        </div>


                    </div>
                </div>


            </div>



        </div>
        <div class="modal-footer">
            <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
            <?php echo form_button(array('id' => 'babug', 'name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
        </div>
        <?php echo form_close(); ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {


            $("#edit_indv_quote_crm123").submit(function(e) {
                e.preventDefault();
                var obj = $(this),
                    action = obj.attr('name');
                $('.save').prop('disabled', true);
                $('.icon-spinner3').show();
                $.ajax({
                    type: "POST",
                    url: base_url + "/indv_quote_update",
                    // url:e.target.action,
                    data: obj.serialize() + "&is_ajax=1&edit_type=edit_indv_quote_crm&form=" + action,
                    cache: false,
                    success: function(JSON) {
                        console.log(JSON);
                        if (JSON.error != '') {
                            toastr.error(JSON.error);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.save').prop('disabled', false);
                            $('.icon-spinner3').hide();
                        } else {
                            toastr.success(JSON.result);
                            var usrid = $('#crm_i').val();
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

                            crm_table_individual_quote.api().ajax.reload(function() {

                            }, true);
                            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                            $('.icon-spinner3').hide();
                            $('.edit-modal-data').modal('toggle');
                            $('.save').prop('disabled', false);

                        }
                    }
                });
            });

        });
        $(document).ready(function() {
            var counter = <?php echo count($get_all_task); ?>;
            // $("#task_div").hide();
            var input = $('.timepicker_m').clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                'default': 'now'
            });
            $(".task").on('click', function(e) {
                e.preventDefault();
                counter += 1;
                $("#task_div").show();
                $("#task_div1").append(`<div style="margin-top:10px;"><div class="row">
                                    <div class="col-md-12">
 
                                        <div class="form-group">
                                            <div class="row">
 
                                                <div class="col-md-12">
                                                   <label>Task` + counter + `</label>
                                                    <input type="text" name="task_name[]" class="form-control"
                                                        placeholder="Task Name">
                                                </div>
 
                                            </div>
                                        </div>
 
 
                                    </div>
 
                                    <div class="col-md-12">
 
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tabs-1-` + counter + `"
                                                    role="tab">Task Description</a>
                                            </li>
                                           
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tabs-2-` + counter + `" role="tab">Sub Tasks</a>
                                            </li>
                                        </ul><!-- Tab panes -->
                                        <div class="tab-content"
                                            style="border: 1px solid; border-top: none; border-color: #dee2e6;">
                                            <div class="tab-pane active" id="tabs-1-` + counter + `" role="tabpanel">
                                                <div class="container-fluid">
                                                <textarea name="task_description[]" id="task_description` + counter + `" placeholder="Detail" style="width:250px"></textarea>
                                                </div>
 
                                            </div>
                                           
                                            <div class="tab-pane" id="tabs-2-` + counter + `" role="tabpanel">
                                                <div class="container-fluid">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Description</th>
                                                                <th>Detail</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="AddItem" id="vendor_items_table` + counter + `"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="border: none !important;">
                                                                    <a href="javascript:void(0)"
                                                                        class="btn-sm btn-success addButton1" onclick="sub_tasks('` + counter + `')">Add</a>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
 
                                    </div>
 
                                </div></div>`);
            });



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
        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();

            updateCalculationPQ();
        });

        function calculation(id) {
            var total = 0;
            var total_tax = 0;
            var final_total = 0;
            var total_amount = 0;

            var unit_price = $("#cost_price_" + id).val();
            var quantity = $("#quantity" + id).val();

            var total = parseFloat(unit_price) * parseFloat(quantity);
            if (total > 0) {
                $("#gross_price_" + id).val(total);
            } else {
                $("#gross_price_" + id).val('0');
            }





            $('#vendor_items_table1 > tr').each(function() {

                total_amount += parseFloat($(this).find('input[name="gross_price[]"]').val());

            });


            var final_total = total_amount;
            $('#sub_total').val(total_amount.toFixed(2));

            $('#total_gst1').val(total_tax.toFixed(2));

            $('#total_amount1').val(final_total.toFixed(2));
        }

        function sub_tasks(id) {
            var number = $('#vendor_items_table' + id + ' tr').length;
            var item = number + 1;

            $('#vendor_items_table' + id).append(`
            <tr>
            <td style="min-width:0px">
            <label>` + item + `<label>
            </td>
             <td style="">
                 <textarea name="product[]" id="product` + id + `" placeholder="Product" cols="10" rows="2"></textarea>
            </td>
                    
                        <td style="min-width:250px">
                            <textarea name="description[]" id="description` + id + `" placeholder="Description" cols="10" rows="2"></textarea>
                        </td>
                        <td style="min-width:100px">
                        <textarea name="detail[]" id="detail` + id + `" placeholder="Detail" cols="10" rows="2"></textarea>
                        </td>
                        <td style="min-width:100px">
                             <select class="packing_dropdown form-control select22" name="unit_id[]">
                             <option value="">Select Unit</option>
                             <?php foreach ($all_units->result() as $unit) {
                                ?>
                                <option value="<?php echo $unit->unit_id;
                                                ?>"><?php echo $unit->unit;
                                                    ?></option>
                                <?php  }
                                ?>
                               
                            </select>
                        </td>
                       
                        <td style="min-width:100px">
                            <input type="text" name="unit_rate[]" id="cost_price_` + item + `"  placeholder="Unit Rate" class="form-control calculate">
                        </td>
                        <td><input type="number" class="form-control calculate" name="qtn[]" id="quantity` + item + `" oninput="calculation(` + item + `)"></td>
                        <td style="min-width:200px">
                            <input type="text" name="gross_price[]" id="gross_price_` + item + `"  placeholder="Gross Amount" class="form-control">
                        </td>
                       
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);
        }
   
     $("#is_gst").change(function() {
    if(this.checked) {
       $("#gst_div").hide();
     
    }
  else
    {
        $("#gst_div").show();

      
    }
});
$(document).ready(function(){
    var id=$("#terms_conditions").val();
    $.ajax({
            type:'GET',
            url: base_url + "/get_term_details/" + id,
            data: JSON,

            success: function(JSON) {
                 var data= jQuery.parseJSON(JSON);
               $("#terms_conditions").val(data[0].term_description)
            },
            error: function() {
                toastr.error("Something went wrong");
            }

        });
});

$(document).on('change',"#term_condition_id",function(){
        var id=$(this).val();
        
        $.ajax({
            type:'GET',
            url: base_url + "/get_term_details/" + id,
            data: JSON,

            success: function(JSON) {
                 var data= jQuery.parseJSON(JSON);
               $("#terms_conditions").val(data[0].term_description)
            },
            error: function() {
                toastr.error("Something went wrong");
            }

        });
    });

 </script>
<?php } ?>