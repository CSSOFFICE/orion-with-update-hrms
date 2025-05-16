<?php 
$result = $this->Quotation_model->ajax_all_invoice_info($invoice_id);

?>
<div class="row">
    <div class="form-group col-md-12">
        <label for="terms">Invoice Date<i class="hrsale-asterisk">*</i></label>
        <input type="text" id="invoice_date" name="invoice_date" class="form-control date" placeholder="Invoice Date" value="<?php echo $result[0]->invoice_date; ?>" readonly>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="payment_term"><?php echo $this->lang->line('xin_payment_term');?></label>
        <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('xin_payment_term');?>"
            name="payment_term" id="terms" value="<?php echo $result[0]->payment_term; ?>" readonly>
    </div>
    <input type="hidden" name="term_id" value="<?php echo $result[0]->terms; ?>">
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="terms">Due Date<i class="hrsale-asterisk">*</i></label>
        <input type="text" name="due_date" id="due_date" class="form-control date" value="<?php echo $result[0]->invoice_due_date; ?>" readonly placeholder="Invoice Due Date">
    </div>
</div><br /><br />
