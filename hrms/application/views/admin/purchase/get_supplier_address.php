<?php 
$result = $this->Purchase_model->ajax_supplier_address_info($supplier_id);
$country=$this->Xin_model->get_countries_by_id($result[0]->country_id);
?>

<?php if(!empty($result[0])){?>
  <div class="row">
                <div class="col-md-12">
                    <label for="supplier_name"><?php echo $this->lang->line('xin_supplier_address');?></label>
                    <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_remarks');?>"
                        name="supplier_name" id="supplier_name" rows="8" readonly> <?php echo $result[0]->supplier_name."\n". $result[0]->address." ".$result[0]->phone."\n".$country[0]->country_name." ".$result[0]->pincode;?></textarea>
                </div>
            </div>

<?php }?>
