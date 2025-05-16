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
                <div class="col-md-12" id="attn_name">
                    <label for="attn_name"><?php echo $this->lang->line('xin_attn_name');?></label>
                    <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('xin_attn_name');?>" name="attn_name" id="attn_name"  readonly value="<?php echo $result[0]->attn_name;?>">
                </div>
                <input type="hidden" name="client_id" value="<?php echo $result[0]->project_clientid;?>">
            </div>
            <div class="row">
                <div class="col-md-12" id="term_condition">
                    <label for="term_condition"><?php echo $this->lang->line('xin_term_condition');?></label>
                    <textarea class="form-control tinymce-textarea" placeholder="<?php echo $this->lang->line('xin_term_condition');?>"
                        name="term_condition" id="term_condition" rows="8" readonly> <?php echo $result[0]->bill_terms;?></textarea>
                </div>
            <br/><br/>
<?php 
$get_all_invoices= $this->Quotation_model->ajax_project_invoice_info($project_id);
?>
  <div class="form-group">
                <label for="invoice_id"><?php echo $this->lang->line('xin_invoice_number');?></label>
                <select class="form-control" name="invoice_id" id="invoice_id" data-plugin="select_hrm"
                    data-placeholder="<?php echo $this->lang->line('xin_invoice_number');?>">
                    <option value=""> <?php echo "Select ".$this->lang->line('xin_invoice_number');?></option>
                    <?php foreach($get_all_invoices as $invoices) {?>
                    <option value="<?php echo $invoices->invoice_id ?>"><?php echo $invoices->invoice_no; ?></option>
                    <?php } ?>
                </select>
            </div>
            <script>

                $("#invoice_id").on('change',function(){
                    jQuery.get(base_url + "/get_invoice_detail/" +  jQuery(this).val(), function(data, status) {
                        jQuery('#invoice_detail').html(data);
                    });
                });
            </script>