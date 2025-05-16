<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['jd']) && isset($_GET['crm_id']) && $_GET['data']=='crm'){

?>

<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>

<div class="modal-header">
    <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
    <h4 class="modal-title" id="edit-Individual-modal-data"><?php echo "Edit Individual Quotation Data";?></h4>
</div>
<?php $attributes = array('name' => 'edit_indv_pro_crm', 'id' => 'edit_indv_quote_crm', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $crm_q_id, 'ext_name' => $crm_q_id);?>
<?php echo form_open('admin/crm/indv_quote_update/'.$crm_q_id, $attributes, $hidden);?>
<div class="modal-body">
<div class="row">
                    <input type="hidden" name="crm_q_id" value="<?php echo $crm_q_id; ?>">
                    <input type="hidden" name="quote_for" id="userid" value="<?php echo $quote_for; ?>">
                            <div class="col-md-12">                                                                                               
                            <div class="row">  
                                     <div class="col-md-6">
                                        <label for="quotation_amount">Quotation Subject Title <i class="hrsale-asterisk">*</i></label>
                                        <input type="text" name="q_title" id="q_title" value="<?php echo $qtitle;?>" class="form-control"
                                            placeholder="Quotation Subject Title">
                                    </div>                                  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>Project Title<i class="hrsale-asterisk">*</i></label>
                                            <select name="indv_proj_id" id="indv_proj_id" class="form-control">
                                                <option value="" disabled>select option</option>
                                                <?php foreach($projects as $project){ ?>
                                                    <option value="<?php echo $project->crm_proj_id; ?>" 
                                                    <?php echo ($project->crm_proj_id == $indv_proj_id) 
                                                      ? 'selected' : ''; ?>
                                                    ><?php echo $project->crm_proj_title; ?></option>

                                              <?php  }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>PIC Name<i class="hrsale-asterisk">*</i></label>
                                            <input type="text" name="pic_name" id="pic_name" class="form-control"
                                            placeholder="PIC Name" value="<?php echo $indv_quote_attn;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Quotation Amount</label>
                                            <input type="text" name="quote_amnt" id="quote_amnt" class="form-control"
                                            placeholder="Customer Email" value="<?php echo $quote_amnt;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Letter of Acceptance No<i class="hrsale-asterisk">*</i></label>
                                            <input type="text" name="quote_letter" id="quote_letter" class="form-control"
                                            placeholder="Project Description" value="<?php echo $quote_letter;?>">
                                        </div>
                                    </div> 
                                </div> 
    
                            </div> 
                            <div class="row pt-4">
                            <div class="col-md-4">
                                <label>Quotation Terms & Condition</label>
                                <select name="term_condition_id" id="term_condition_id" class="form-control">
                                <option>Select Term Condition</option>
                                <?php foreach($get_term_condition as $term_condition){ ?>
                                <option <?php echo ($term_condition->term_id == $term_condition_id)? "selected" : " "; ?> value="<?php echo $term_condition->term_id; ?>"><?php echo $term_condition->term_title; ?></option>
                                            <?php } ?>
                                        </select>
                                </div>
                            
                            <div class="col-md-4">
                                <label> </label>
                                <textarea class="form-control" placeholder="Quotation Terms &amp; Condition" name="terms_condition" id="terms_conditions"><?php echo $terms_condition; ?></textarea>
                            </div>
                            </div>                         
                        </div>
                       
   
   
</div>
<!--</div>-->
<div class="modal-footer">
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?>
    <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_update'))); ?>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
$(document).ready(function() {
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

$("#edit_indv_quote_crm").submit(function(e) {
e.preventDefault();
var obj = $(this),
    action = obj.attr('name');
$('.save').prop('disabled', true);
$('.icon-spinner3').show();
$.ajax({
    type: "POST",
    url: base_url + "/indv_quote_update",
    data: obj.serialize() + "&is_ajax=1&edit_type=edit_indv_quote_crm&form=" + action,
    cache: false,
    success: function(JSON) {
        if (JSON.error != '') {
            toastr.error(JSON.error);
            $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
            $('.save').prop('disabled', false);
            $('.icon-spinner3').hide();
        } else {
            var usrid = $('#userid').val();
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
                toastr.success(JSON.result);
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
</script>

<?php }?>