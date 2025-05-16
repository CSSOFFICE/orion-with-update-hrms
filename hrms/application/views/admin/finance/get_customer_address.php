<?php 
$result = $this->Quotation_model->ajax_project_customer_info($project_id);
// print_r($result);exit;
?>
  <div class="row">
                <div class="col-md-12" id="supplier_address">
                    <label for="supplier_name"><?php echo $this->lang->line('xin_customer_address');?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_customer_address');?>"
                        name="customer_name" id="customer_name" rows="8" readonly> <?php echo $result[0]->f_name."\n". $result[0]->address." ".$result[0]->client_phone;?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" id="attn_name">
                    <label for="attn_name"><?php echo $this->lang->line('xin_attn_name');?></label>
                    <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('xin_attn_name');?>" name="attn_name" id="attn_name"  readonly value="<?php echo $result[0]->pic_name;?>">
                </div>
                <input type="hidden" name="client_id" value="<?php echo $result[0]->project_clientid;?>">
            </div>
            <div class="row">
                <div class="col-md-12" id="term_condition">
                    <label for="term_condition"><?php echo $this->lang->line('xin_term_condition');?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_term_condition');?>"
                        name="term_condition" id="term_condition" rows="8" readonly> <?php echo $result[0]->bill_terms;?></textarea>
                </div>
            </div><br/><br/>
<?php 
$quotation_result = $this->Quotation_model->ajax_project_quotation_info($project_id);
?>
  <div class="row">
                <div class="col-md-12" id="quotation_amount">
                  <input type="hidden" name="quotation_amount" value="<?php echo ((isset($quotation_result) && count($quotation_result) >0)?$quotation_result[0]->quotation_amount:'');  ?>">
            </div>

<?php
$invoice_result = $this->Quotation_model->ajax_invoice_info($project_id);

?>
 <div class="row">
                <div class="col-md-12" id="previous_invoice_amount">
                  <input type="hidden" name="previous_invoice_amount" value="<?php echo ((isset($invoice_result) && count($invoice_result) >0 )?$invoice_result[0]->invoice_amount:'');  ?>">
            </div>