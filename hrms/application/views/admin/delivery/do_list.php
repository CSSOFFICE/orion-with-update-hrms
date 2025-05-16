<style>
    #add_form {
        height: 100% !important;
    }
</style>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>


<div class="box mb-4 <?php echo $get_animate; ?>">
    <div id="accordion">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Delivery Order</h3>
            <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                        <?php echo $this->lang->line('xin_add_new'); ?></button>
                </a> </div>
        </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
        <?php $attributes = array('name' => 'add_receivable', 'id' => 'add_receivable', 'autocomplete' => 'off'); ?>
        <?php $hidden = array('user_id' => $session['user_id']); ?>
        <?php echo form_open_multipart('admin/Receivable/add', $attributes, $hidden); ?>

        <div class="form-body">
            <div class="box-body">
                <div class="row">
                <div class="col-md-3">
                        <div class="form-group">
                            <label>Quotation Number</label>
                            <select class="form-control" name="quotation_no" id="quotation_no" data-plugin="xin_select" data-placeholder="<?php echo $this->lang->line('xin_customer'); ?>">
                                            <option value=""><?php echo $this->lang->line('xin_select_one'); ?></option>
                                            <?php $all_quotation=$this->db->where('bill_status','accepted')->get('estimates')->result(); foreach ($all_quotation as $estimate) { ?>
                                                <option value="<?php echo $estimate->bill_estimateid; ?>">
                                                    <?php echo $estimate->quotation_no; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Issue Date</label>
                            <input type="date" name="issue_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer PO / Date</label>
                            <input type="text" name="complete_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control"></textarea>
                        </div>
                    </div>             
                </div>
                <div class="table-responsive my-3 purchaseTable">
                                            <table class="table" id="data_table" border="1">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Job Description</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Rate</th>
                                                        <th>Amount</th>
                                                        <th></th>
                                                        <!-- Add more headers as needed -->
                                                    </tr>
                                                </thead>
                                                <tbody  class="AddItem">
                                                    <!-- Data will be populated here -->
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>
                                                            <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton1">Add</a>                                                            
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>





            </div>
            <div class="form-actions box-footer">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i>
                    <?php echo $this->lang->line('xin_save'); ?> </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title">List All Delivery Orders</h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">

                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('xin_action'); ?></th>
                        <th><?php echo $this->lang->line('xin_customer'); ?></th>
                        <th>Quotation Ammount</th>
                        <!-- <th>Received Total</th> -->
                        <th>Invoice Number</th>
                        <th>Status</th>
                        <th>Quotation Date</th>


                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {      
    $(document).on('click', '.remove-input-field', function() {
        $(this).parents('tr').remove();
    });
    
    $(document).on("change", "#quotation_no", function() {
    var quotation_id = $(this).val();

    $.ajax({
        url: '<?php echo base_url("admin/Finance/related_data/")?>' + quotation_id,
        type: "POST",
        success: function(response) {
            var i = 1;
            $("#data_table tbody").empty();

            $.each(response.records, function(key, value) {
                console.log(value['lineitem_description']);
                $("#data_table tbody").append(
                    "<tr>" +
                    "<td>" + i + "</td>" +
                    "<td><textarea class='form-control'>" + value['lineitem_description'] + "</textarea></td>" +
                    "<td><textarea class='form-control'>" + value['lineitem_quantity'] + "</textarea></td>" +
                    "<td><textarea class='form-control'>" + value['lineitem_unit'] + "</textarea></td>" +
                    "<td><textarea class='form-control'>" + value['lineitem_rate'] + "</textarea></td>" +
                    "<td>" + (Number(value['lineitem_quantity']) * Number(value['lineitem_rate'])) + "</td>" +
                    "<td><button type='button' name='clear' id='clear' class='btn btn-danger remove-input-field'><i class='ti-trash'></i></button></td>" +
                    "</tr>"
                );
                i++;
            });
        }
    });
});

$(document).on("click", ".remove-row", function() {
    $(this).closest("tr").remove();
});

        
        
        //Table Button Click JS
        
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
                            <input type="number" min="0" id="cost${++$('.AddItem tr').length}"  class="form-control calculate" name="cost[]" id="cost_` + item + `" placeholder="Cost" onkeyup="calculation(${++$('.AddItem tr').length})">
                        </td>
                        <td style="min-width:200px">
                             <select class="packing_dropdown form-control select22" name="type_id[]" id="type_id_` + item + `" onchange="get_type('` + item + `');">
                                <option value="">Select Type</option>
                                <option value="add">Addition</option>
                                <option value="subtraction">Subtraction</option>

                            </select>
                        </td>

                        <td style="min-width:200px">
                            <input type="text" name="amount[]" id="amount` + item + `"  placeholder="Total Amount" class="calculate form-control" onkeyup="calculation(${++$('.AddItem tr').length})">
                        </td>

                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

        });
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    });
    
  
</script>