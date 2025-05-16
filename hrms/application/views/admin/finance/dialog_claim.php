<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['claim_id']) && $_GET['data']=='claim'){
 
?>
<?php $system = $this->Xin_model->read_setting_info(1);?>
<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Xin_model->read_user_info($session['user_id']);?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_employee_set_oth_claims');?></h4>
</div>
<?php $attributes = array('name' => 'edit_claim', 'id' => 'edit_payable', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' =>$_GET['claim_id'], 'ext_name' => $_GET['claim_id']);?>
<?php echo form_open_multipart('admin/claim/update', $attributes, $hidden);?>
<div class="modal-body">
    <div class="row">
        <input type="hidden" name="edit_type" value="claim">
        <input type="hidden" name="claim_id" value="<?php echo $_GET['claim_id']; ?>">
        <div class="col-md-12">
            <div class="form-group">
                <label for="project_id"><?php echo $this->lang->line('xin_project');?>
                    
                </label>
                <select class="form-control" name="project_id1" id="project_id1" data-plugin="xin_select"
                    data-placeholder="<?php echo $this->lang->line('xin_customer');?>">
                    <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                    <?php foreach($get_all_projects as $project) {?>
                    <option value="<?php echo $project->project_id;?>"
                        <?php echo ($project->project_id == $project_id ? 'selected':'');?>>
                        <?php echo $project->project_title;?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-12">

        <div class="form-group">
                <label for="amount">Name of Claimer <i class="hrsale-asterisk">*</i></label>
                <input type="text" name="claimer_name1" id="claimer_name1" class="form-control" placeholder="Claimer Name" value="<?php echo $claimer_name; ?>">
        </div>
                    </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="text" name="amount1" id="amount1" class="form-control" placeholder="Amount"
                    value="<?php echo $amount; ?>" onkeyup="changeAmount()">
            </div>
        </div>

        <div class="col-md-12">
            <input type="checkbox" class="listcheckbox listcheckbox-files filled-in chk-col-light-blue" id="is_gst1"
                name="is_gst1" value="1" <?php echo(($is_gst_inclusive == "1")?'checked':'');?>><label for="is_gst1">GST
                Inclusive</label>
        </div>
        <div class="col-md-12" id="gst_div1">

            <label for="total_gst1" class="form-label">GST</label>
            <select class="form-control select22" id="u_total_gst1" name="u_gst" onchange="totalGSTAmount()">
                <?php foreach($get_gst->result() as $gst){ ?>
                <option value="<?php echo $gst->gst; ?>" <?php echo (($gst->gst == $gst_value)?'selected':'');?>>
                    <?php echo $gst->gst;?></option>
                <?php } ?>
            </select>
            <!-- <input class="form-control" type="text" value="" id="u_total_gst1" name="u_total_gst1"
                                placeholder="0" value="<?php //echo (($gst >0)?$gst:0) ;?>" onkeyup="totalGSTAmount()"> -->
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="text" name="total_amount1" id="total_amount1" class="form-control" placeholder="total_amount"
                    value="<?php echo $total_amount; ?>">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="due_date">Description</label>
                <textarea class="form-control" id="description1" placeholder="Description" name="description1"><?php echo $description; ?></textarea>
            </div>
        </div>


        <?php if($attachment != ""){ ?>
        <div class="col-md-12">
            <div class="form-group">
                <label for="due_date">Attachment:</label>
                <input type="file" name="document_file1"><br />
                <label><a href="<?php echo base_url().'uploads/claims/'. $attachment; ?>" target="_blank">View
                        Here</a></label>
            </div>

            <?php } else{ ?>
                <div class="col-md-12">
            <div class="form-group">
                <label for="due_date">Attachment:</label>
                <input type="file" name="document_file1"><br />
                
            </div>
                <?php } ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
                <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
            </div>
            <?php echo form_close(); ?>
            <?php } ?>
            <script>
            $(document).ready(function() {
                <?php if($is_gst_inclusive == "1"){ ?>
                $("#gst_div1").hide();
                <?php } ?>
                $("#is_gst1").change(function() {
                    if (this.checked) {
                        $("#gst_div1").hide();
                        var total_amount = parseFloat($('#amount1').val());
                        $('#total_amount1').val(total_amount);
                    } else {
                        $("#gst_div1").show();
                        var total_amount = parseFloat($('#amount1').val());
                        var tax = parseFloat($("#u_total_gst1 option:selected").text());
                        var total = total_amount + total_amount * (tax / 100);
                        
                        $('#total_amount1').val(total);

                    }
                });
            });
            function changeAmount(){
                <?php if($is_gst_inclusive == "1"){ ?>
                $("#gst_div1").hide();
                var total_amount = parseFloat($('#amount1').val());
                   
                    var total = total_amount;
                    
                    $('#total_amount1').val(total);
                <?php } else{ ?>
                    var total_amount = parseFloat($('#amount1').val());
                    var tax = parseFloat($("#u_total_gst1 option:selected").text());
                    var total = total_amount + total_amount * (tax / 100);
                    
                    $('#total_amount1').val(total);
                    <?php } ?>
                
            $("#is_gst").change(function() {
                if(this.checked) {
                    var total_amount = parseFloat($('#amount1').val());
                    $('#total_amount1').val(total);
                }
            else
                {
                    var total_amount = parseFloat($('#amount1').val());
                    var tax = parseFloat($("#u_total_gst1 option:selected").text());
                    var total = total_amount + total_amount * (tax / 100);
                    
                    $('#total_amount1').val(total);

                
                }
            });
         }
            function totalGSTAmount() {
               
    var total_amount = parseFloat($('#amount1').val());
    var tax = parseFloat($("#u_total_gst1 option:selected").text());
    var total = total_amount + total_amount * (tax / 100);
    
    $('#total_amount1').val(total);
}
            </script>