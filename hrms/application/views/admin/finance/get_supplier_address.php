<?php 
$result = $this->Quotation_model->ajax_customer_address_info($client_id);

?>
  <div class="row">
                <div class="col-md-12" id="supplier_address">
                    <label for="supplier_name"><?php echo $this->lang->line('xin_customer_address');?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_remarks');?>"
                        name="customer_name" id="customer_name" rows="8" readonly> <?php echo $result[0]->client_company_name."\n". $result[0]->address." ".$result[0]->client_phone;?></textarea>
                </div>
            </div>

<?php
//}
?>
