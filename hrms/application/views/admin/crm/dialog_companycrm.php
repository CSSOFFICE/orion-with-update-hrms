<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (isset($_GET['jd']) && isset($_GET['crm_c_id']) && $_GET['data'] == 'com_crm') {

?>

    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data"><?php echo "Edit Customer Data"; ?></h4>
    </div>
    <?php $attributes = array('name' => 'edit_comcrm', 'id' => 'edit_comcrm', 'autocomplete' => 'off', 'class' => 'm-b-1' ,'enctype'=>'multipart/form-data'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $crm_id, 'ext_name' => $crm_id); ?>
    <?php echo form_open_multipart('admin/crm/com_update/' . $crm_id, $attributes, $hidden); ?>
    <div class="modal-body">
        <div class="row">

            <input type="hidden" name="crm_c_id" value="<?php echo $crm_id; ?>">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Company Name<i class="hrsale-asterisk">*</i></label>
                            <input type="text" name="com_name" id="com_name" class="form-control" placeholder="Company Name" value="<?php echo $company_name; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Company Logo<i class="hrsale-asterisk">*</i></label>
                            <?php if(!empty($c_logo)){?>
                                <img src="<?php echo base_url('uploads/crm/'.$c_logo)?>" class="form-control">
                                <input type="file" name="c_logo" id="c_logo" class="form-control" >
                                <?php }else{?>
                                 <input type="file" name="c_logo" id="c_logo" class="form-control" >
                                <?php }?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Name<i class="hrsale-asterisk">*</i></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="<?php echo $name; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Company UEN</label>
                            <input type="text" name="c_uen" id="c_uen" class="form-control" placeholder="Company UEN" value="<?php echo $company_uen; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Contact Number<i class="hrsale-asterisk">*</i></label>
                            <input type="number" name="c_number" id="c_number" class="form-control" placeholder="Contact Number" value="<?php echo $c_contact_number; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="cust_email" id="cust_email" class="form-control" placeholder="Customer Email" value="<?php echo $c_email; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Postal Code<i class="hrsale-asterisk">*</i></label>
                            <input type="number" name="pos_code" id="pos_code" class="form-control" placeholder="Postal Code" value="<?php echo $c_postal_code; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="com_address" id="com_address" class="form-control"><?php echo $address; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Unit Number<i class="hrsale-asterisk">*</i></label>
                            <input type="text" name="un_num" id="un_num" class="form-control" placeholder="Unit Number" value="<?php echo $c_unit_number; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Credit Limit</label>
                            <input type="number" name="cr_limit" id="cr_limit" class="form-control" placeholder="Credit Limit" value="<?php echo $c_credit_limit; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label>Additinal Info</label>

                    <div class="table-responsive my-3 purchaseTable">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Person In Charge</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="AddItematEdit" id="vendor_items_table1"></tbody>
                            <tfoot>
                                <tr>
                                    <th style="border: none !important;">
                                        <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton1">+ Add New</a>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                </div>

            </div>



        </div>
        <!--</div>-->
        <div class="modal-footer">
            <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>
            <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_update'))); ?>
        </div>
        <?php echo form_close(); ?>
        <script type="text/javascript">
            $(document).ready(function() {








                $("#edit_comcrm").submit(function(e) {

                    e.preventDefault();
                    var obj = $(this),
                        action = obj.attr('name');
                        var abc=new FormData(this);
                        abc.append('is_ajax',1);
                        abc.append('edit_type','edit_comcrm');
                        abc.append('form',action);
                    $('.save').prop('disabled', true);
                    $('.icon-spinner3').show();
                    $.ajax({
                        type: "POST",
                        url: base_url + "/com_update",
                        // data: obj.serialize() + "&is_ajax=1&edit_type=edit_comcrm&form=" + action,
                        data:abc,                       
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(JSON) {
                            if (JSON.error != '') {
                                toastr.error(JSON.error);
                                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                                $('.save').prop('disabled', false);
                                $('.icon-spinner3').hide();
                            } else {

                                var table_company = $('#table_company').dataTable({
                                    "bDestroy": true,
                                    "ajax": {
                                        url: base_url + '/com_crmlist/',
                                        type: 'GET'
                                    },
                                    /*dom: 'lBfrtip',
                                    "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed*/
                                    "fnDrawCallback": function(settings) {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    }
                                });
                                table_company.api().ajax.reload(function() {
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
        <script>
            $(document).ready(function() {
                var number = $('.AddItematEdit tr').length;
                var item = number + 1;
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() . 'admin/crm/get_details/'; ?>" + <?php echo $crm_id; ?>,
                    data: JSON,
                    success: function(data) {
                        var product_data = jQuery.parseJSON(data);
                        if (product_data.p_ic != " ") {
                            var i = 1;
                            $.each(product_data, function(key, value) {
                                $('.AddItematEdit').append(`
                                <tr class="Rmtr">
                                <td style="min-width:130px">
                                        <label>` + i + `<label>
                                    </td>
                                    <td style="min-width:200px; display:none;">
                                        <input type="hidden" placeholder="Item In Charge" name="item_ids[]" id="item_ids` + item + `" class="form-control" value="` + value.crm_company_cust_item_id + `">
                                    </td>
                                    <td style="min-width:200px">
                                        <input type="text" placeholder="Person In Charge" name="pr_ic[]" id="pr_ic` + item + `" class="form-control" value="` + value.p_ic + `">
                                    </td>
                                    <td style="min-width:200px">
                                        <input type="text" placeholder="Contact Number" name="c_n[]" id="c_n` + item + `" class="form-control" value="` + value.c_n + `">
                                    </td>
                                    <td style="min-width:200px">
                                        <input type="text" placeholder="Email" name="e_mail[]" id="e_mail` + item + `" class="form-control" value="` + value.e_mail + `">
                                    </td>
                                    <td style="min-width:200px">
                                        <textarea placeholder="Address" name="a_dd[]" id="a_dd` + item + `" class="form-control">` + value.a_dd + `</textarea>
                                    </td>
                                    
                                    <td>
                                        <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                                    </td>
                                </tr>
                            `);
                                i++;
                            });
                        }
                    },
                    error: function() {
                        toastr.error("Additional Details Not Found");
                    }
                });

                $(document).on('click', '.remove-input-field', function() {
                    $(this).parents('tr').remove();
                });

                $(document).on('click', '#edit-modal-data', function() {
                    // $('.Rmtr').remove();
                    const element = document.querySelector('#edit-modal-data')
                    const display = element.style.display;
                    if (display == 'none') {
                        var numbers = 0;
                        var items = 0;
                    }
                });
            });


            $("body").on("click", "#addButton1", function(e) {
                e.preventDefault()
                // $('#vendor_items_table1 tr:last td label').attr('id');
                var numbers = $('.AddItematEdit tr').length;
                
                var items = numbers + 1;
                $('.AddItematEdit').append(`
                    <tr class="Rmtr">
                    <td style="min-width:130px">
                            <label >` + items + `<label>
                        </td>
                        <td style="min-width:200px; display:none;">
                            <input type="hidden" placeholder="Item In Charge" name="item_ids[]" id="item_ids` + items + `" class="form-control" >
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Person In Charge" name="pr_ic[]" id="pr_ic` + items + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Contact Number" name="c_n[]" id="c_n` + items + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Email" name="e_mail[]" id="e_mail` + items + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <textarea placeholder="Address" name="a_dd[]" id="a_dd` + items + `" class="form-control"></textarea>
                        </td>
                        
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

            });
        </script>

    <?php } ?>