<?php
/* Awards view
*/
?>
<style>
    #add_form {
        height: 100% !important;
    }

    /* The container */
    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .container:hover input~.checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .container input:checked~.checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container input:checked~.checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .container .checkmark:after {
        left: 9px;
        top: 5px;
        width: 8px;
        height: 12px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>
<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
<?php if (in_array('207', $role_resources_ids)) { ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div id="accordion">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $this->lang->line('xin_add_new'); ?>
                    <?php echo $this->lang->line('xin_suppliers'); ?></h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-xs btn-primary"> <span class="ion ion-md-add"></span>
                            <?php echo $this->lang->line('xin_add_new'); ?></button>
                    </a> </div>
            </div>
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_supplier', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('_user' => $session['user_id']); ?>
                    <?php echo form_open_multipart('admin/supplier/add_supplier', $attributes, $hidden); ?>
                    <div class="bg-white">
                        <div class="box-block">

                            <div class="form-group">
                                <label>Choose add Type</label>
                                <select class="form-control" name="add_type1" id="addTypeSelect">
                                    <option value="">Please Select</option>
                                    <option value="manual">Manual Entry</option>
                                    <option value="bulk">Bulk Upload</option>
                                </select>
                            </div>
                            <!--for manual entry-->
                            <div id="manual" class="hidden">


                                <div id="details">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="supplier_name" placeholder="Name of Supplier">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address_1">Address 1</label>
                                            <input type="text" class="form-control" name="address_1" placeholder="Address 1">
                                        </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_2">Address 2</label>
                                                <input type="text" class="form-control" name="address_2" placeholder="Address 2">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_3">Address 3</label>
                                                <input type="text" class="form-control" name="address_3" placeholder="Address 3">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_4">Address 4</label>
                                                <input type="text" class="form-control" name="address_4" placeholder="Address 4">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact">Contact</label>
                                                <input type="text" class="form-control" name="contact_person" placeholder="Contact">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="tel_no_1">Phone 1</label>
                                                <input type="text" class="form-control" name="tel_no_1" placeholder="Phone 1">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="tel_no_2">Phone 2</label>
                                                <input type="text" class="form-control" name="tel_no_2" placeholder="Phone 2">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="fax">Fax</label>
                                                <input type="text" class="form-control" name="fax1" placeholder="Fax">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email_id" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="row" id="dynamic_table">
                                    <div class="col-md-12">
                                        <label>Assign Products of This Supplier</label>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sl No.</th>
                                                        <th>Item</th>
                                                        <th>Description</th>
                                                        <!-- <th>Uom</th> -->
                                                        <th>Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="AddItem2" id="vendor_items_table1"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th style="border: none !important;">
                                                            <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton2">+
                                                                Add New</a>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <!-- Bulk Upload -->
                            <div id="bulkDiv" class="hidden">
                                <div class="form-group">
                                    <p>The first line in downloaded csv file should remain as it is. Please do not change the order of columns in csv file.<br><br>
                                        The correct column order is (Code, Account Group, Company Name, Address 1, Currency Code, Default GST Code, Phone 1, Fax 1, Mobile, Email Address, Registration No.,GST Registration No.,Website,Active,Multi Pricing,Allow Change Multi Price) and you must follow the csv file, otherwise you will get an error while importing the csv file.</p>
                                    <h6><a href="<?php echo base_url(); ?>uploads/csv/sample-csv-suppliers.csv" class="btn btn-primary"> <i class="fa fa-download"></i> <?php echo $this->lang->line('xin_employee_import_download_sample'); ?> </a></h6>
                                    <label>Bulk Upload</label>
                                    <input type="file" class="form-control-file" id="file" name="file">

                                </div>
                            </div>




                            <div class="form-actions box-footer">
                                <?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_save'))); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php echo $this->lang->line('xin_suppliers'); ?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th style="width:120px;"><?php echo $this->lang->line('xin_action'); ?></th>
                        <th>Supplier Code</th>
                        <th>Supplier Name</th>
                        <th>Supplier Address 1</th>
                        <th>Supplier Address 2</th>
                        <th>Supplier Address 3</th>
                        <th>Supplier Address 4</th>
                        <th>Contact</th>
                        <th>Phone 1</th>
                        <th>Phone 2</th>
                        <th>Fax</th>
                        <th>Email</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<style type="text/css">
    .hide-calendar .ui-datepicker-calendar {
        display: none !important;
    }

    .hide-calendar .ui-priority-secondary {
        display: none !important;
    }

    .hidden {
        display: none;
    }
</style>
<!-- <script>
    $(document).ready(function() {
        // Add event listener for checkbox click
        $('.form-check-input').click(function() {
            // Check if the checkbox is checked
            if ($(this).is(':checked')) {
                // Checkbox is checked, do something
                console.log($(this).attr('id') + ' checked');
            } else {
                // Checkbox is unchecked, do something else
                console.log($(this).attr('id') + ' unchecked');
            }
        });
    });
</script> -->
<script>
    document.getElementById('addTypeSelect').addEventListener('change', function() {
        var selectedValue = $(this).val();

        if (selectedValue === 'bulk') {
            document.getElementById('manual').classList.add('hidden');
            document.getElementById('bulkDiv').classList.remove('hidden');
        } else if (selectedValue === 'manual') {
            document.getElementById('manual').classList.remove('hidden');
            document.getElementById('bulkDiv').classList.add('hidden');
        } else {
            document.getElementById('manual').classList.add('hidden');
            document.getElementById('bulkDiv').classList.add('hidden');
        }
    });
</script>



<script>
    $(document).ready(function() {

        $('#addButton2').on('click', function() {
            var item = $('.AddItem tr').length;
            var lst = $('#vendor_items_table1 tr:last td label').attr('id');

            var item = item + 1;
            if (item <= lst) {
                item = (Number(lst) + 1);
            }
            $('.AddItem2').append(`
    <tr>
        <td>
            <label id="` + item + `">` + item + `<label>
        </td>
        <td>
            <select class="packing_dropdown form-control select22" name="item[]" id="prd` + item + `" data-placeholder="Select Products" onchange="getProductDetail1(` + item + `)">
                <option value="">Select product</option>
                <?php foreach ($all_products as $product) {
                    echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                } ?>
            </select>
        </td>

        <td>
            <textarea placeholder="Description" name="description[]" id="description` + item + `" class="form-control"></textarea>
        </td>

        <td>
            <input type="text" placeholder="Price" name="price[]" id="price` + item + `" class="form-control">
        </td>
        <td>
            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
        </td>
    </tr>
`);


        });




        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();
        });
    });
</script>
<script>
function getProductDetail1(number) {
    var prd_id = $('#prd' + number).val();

    $.ajax({
        type: "POST",
        url: "<?php echo base_url() . 'admin/purchase/get_product_details/'; ?>" + prd_id,
        success: function(data) {
            var product_data = jQuery.parseJSON(data);
            console.log(data);
            $("#description" + number).val(product_data[0].description);
            $("#price" + number).val(product_data[0].sell_p);
            
        },
        error: function() {
            toastr.error("Description Not Found");
        }
    });
}

</script>