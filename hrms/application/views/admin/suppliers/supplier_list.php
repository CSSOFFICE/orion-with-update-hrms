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
<?php if (in_array('2802', $role_resources_ids)) { ?>
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="supplier_name" placeholder="Name of Supplier">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Terms</label>
                                                <?php $term = $this->db->get('xin_payment_term')->result() ?>
                                                <select name="sup_terms" class="form-control" data-plugin="select_hrm" data-placeholder="Terms">
                                                    <option value="">Select</option>
                                                    <?php foreach ($term as $terms) { ?>
                                                        <option value="<?php echo $terms->payment_term ?>"><?php echo $terms->payment_term ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>GST Supplier </label>
                                                <select name="gst_sup" class="form-control" data-plugin="select_hrm" data-placeholder="GST Supplier">
                                                    <option value="">Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        
                                            <div class="form-group" style="margin-top: 32px;">
                                                <input type="checkbox" name="subcontractor" id="subcontractor" value="Yes">
                                                <label for="subcontractor">Subcontractor</label>

                                            </div>
                                        </div>

                                    </div>



                                    <!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">Home</div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Profile</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Contact</div>
                                    </div> -->




                                    <div class="row">
                                        <div class="col-sm-12">
                                            <nav class="ic-customer-details-tab ">
                                                <div class="nav nav-tabs " id="myTab" role="tablist">
                                                    <a class="nav-item nav-link active" id="nav-billing_address-tab" data-toggle="tab" href="#nav-billing_address" role="tab" aria-controls="nav-billing_address" aria-selected="true">Billing Address</a>
                                                    <!-- <a class="nav-item nav-link" id="nav-delivery_address-tab" data-toggle="tab" href="#nav-delivery_address" role="tab" aria-controls="nav-delivery_address" aria-selected="true">Shipping Address</a> -->
                                                    <!-- <a class="nav-item nav-link" id="nav-sup_product-tab" data-toggle="tab" href="#nav-sup_product" role="tab" aria-controls="nav-sup_product" aria-selected="true">Products</a> -->
                                                </div>
                                            </nav>

                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="nav-billing_address" role="tabpanel" aria-labelledby="nav-billing_address-tab">
                                                <div class="row">
                                                    <div class="col">
                                                            <label for="" class="text-muted">Billing Address</label>
                                                        </div>
                                                        
                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-success" id="add_billing_addr_btn">Add +</button>
                                                        </div>
                                                </div>
                                                        

                                               

                                                    <div id="billing_addr_group">
                                                        <!-- <div class="row billing_addr_row" style="margin-bottom: 20px;">
                                                            <div class="form-group col-sm-6">
                                                                <label for="">Person In Charge <span class="error">*</span></label>
                                                                <input type="text" name="billing_addr_pic[]" class="form-control billing_addr_pic" >

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="">Contact Number <span class="error">*</span></label>
                                                                <input type="text" name="billing_addr_contant_number[]" class="form-control billing_addr_contant_number" >

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="">postal code<span class="error">*</span></label>
                                                                <input type="text" name="billing_addr_zipcode[]" class="form-control billing_addr_zipcode" onchange="get_address_from_postalcode(this, 'billing_addr_row', 'billing_address')" >

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="">Address <span class="error">*</span></label>
                                                                <textarea name="billing_address[]" class="form-control billing_address" placeholder="Address" ></textarea>

                                                            </div>

                                                            <div class="form-group col-lg-6">
                                                                <label for="#">Email</label>
                                                                <input type="text" name="billing_addr_email[]" class="form-control billing_addr_email">

                                                            </div>


                                                        </div> -->
                                                    </div>
                                                </div>


                                                <div class="tab-pane fade" id="nav-delivery_address" role="tabpanel" aria-labelledby="nav-delivery_address-tab">
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="" class="text-muted">Shipping Address</label>
                                                        </div>

                                                        <div class="col">
                                                            <!-- <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" name="same_address" id="same_address"> Same as Billing Address
                                                                </label>
                                                            </div> -->
                                                        </div>

                                                        <div class="col-auto">
                                                            <button type="button" class="btn btn-success" id="add_delivery_addr_btn">Add +</button>
                                                        </div>
                                                    </div>

                                                    <div id="delivery_addr_group">
                                                        <!-- <div class="row delivery_addr_row" style="margin-bottom: 20px;">
                                                            <div class="form-group col-sm-6">
                                                                <label for="">Person In Charge <span class="error">*</span></label>
                                                                <input type="text" name="shipping_addr_pic[]" class="form-control shipping_addr_pic" >

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="">Contact Number <span class="error">*</span></label>
                                                                <input type="text" name="shipping_addr_contant_number[]" class="form-control shipping_addr_contant_number" >

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="">Postal<span class="error">*</span></label>
                                                                <input type="text" name="shipping_addr_zipcode[]" class="form-control shipping_addr_zipcode" onchange="get_address_from_postalcode(this, 'delivery_addr_row', 'shipping_address')" >

                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="">Address <span class="error">*</span></label>
                                                                <textarea name="shipping_address[]" class="form-control shipping_address" placeholder="Address" ></textarea>

                                                            </div>

                                                            <div class="form-group col-lg-6">
                                                                <label for="#">Email</label>
                                                                <input type="text" name="shipping_addr_email[]" class="form-control shipping_addr_email">

                                                            </div>

                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <div class="row" id="dynamic_table">
                                      
                                            <label>Assign Products of This Supplier</label>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl No.</th>
                                                            <th>Item</th>
                                                            <th>Description</th>

                                                            <th>Price $</th>
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
                        <th>Sub Contractor</th>
                        <th>Supplier Name</th>
                        <th>Supplier Term</th>
                        <th>Supplier GST</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


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
    // $('#myTab a').on('click', function(event) {
    //     event.preventDefault()
    //     var a = $(this).attr('href');
    //     console.log($(a).parents('.tab-content').get(0));

    //     $(a).parents('.tab-content').find('.show').removeClass('show');
    //     $(a).parents('.tab-content').find('.active').removeClass('active');
    //     $(a).parents('.tab-content').find('.test').removeClass('test');

    //     $(a).addClass('show');
    //     //   $(a).addClass('active');
    //     $(a).addClass('test');
    //     //   $('#myTab a[data-target="#profile"]').addClass('show') // Select tab by name
    //     //   $('#myTab a[data-target="#profile"]').addClass('active') // Select tab by name

    // })

    function get_address_from_postalcode(el, parents_class, target_class) {
        var postal_code = $(el).val();

        $.ajax({
            type: "get",
            url: "https://www.onemap.gov.sg/api/common/elastic/search?searchVal=" + postal_code + "&returnGeom=Y&getAddrDetails=Y&pageNum=1",
            success: function(response) {
                console.log(response);

                if (response.found != 0) {
                    var address = response.results[0].ADDRESS;

                    $(el).parents('.' + parents_class).find('.' + target_class).val(address);
                } else {
                    $(el).parents('.' + parents_class).find('.' + target_class).val("");
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
</script>
<script>
    $(document).ready(function() {

        $('body').on('click', '#add_billing_addr_btn', function() {

            var html = `<div class="row custom_billing_addr billing_addr_row" style="margin-bottom: 20px;">
                        <div class="form-group col-sm-6">
                            <label for="">Person In Charge <span class="error">*</span></label>
                            <input type="text" name="billing_addr_pic[]" class="form-control billing_addr_pic" >

                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">Contact Number <span class="error">*</span></label>
                            <input type="text" name="billing_addr_contant_number[]" class="form-control billing_addr_contant_number" >

                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">postal code<span class="error">*</span></label>
                            <input type="text" name="billing_addr_zipcode[]" class="form-control billing_addr_zipcode" onchange="get_address_from_postalcode(this, 'billing_addr_row', 'billing_address')" >

                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">Address <span class="error">*</span></label>
                            <textarea name="billing_address[]" class="form-control billing_address" placeholder="Address" ></textarea>

                        </div>

                        <div class="form-group col-lg-6">
                            <label for="#">Email</label>
                            <input type="text" name="billing_addr_email[]" class="form-control billing_addr_email">

                        </div>
                        <div class="form-group col-sm-1" style="margin-top: 32px;">
                                <a class="btn btn-danger billing_addr_remove_btn">
                                    <i class="fa fa-times"></i>
                                </a>
                        </div>

                        </div>`;

            $("#billing_addr_group").append(html);

        });

        $('body').on('click', '.billing_addr_remove_btn', function() {

            $(this).parents('.custom_billing_addr').remove();

        });

        $('body').on('click', '#add_delivery_addr_btn', function() {

            var html = ` <div class="row delivery_addr_row custom_delivery_addr" style="margin-bottom: 20px;">
                          <div class="form-group col-sm-6">
                              <label for="">Person In Charge <span class="error">*</span></label>
                              <input type="text" name="shipping_addr_pic[]" class="form-control shipping_addr_pic" >

                          </div>

                          <div class="form-group col-sm-6">
                              <label for="">Contact Number <span class="error">*</span></label>
                              <input type="text" name="shipping_addr_contant_number[]" class="form-control shipping_addr_contant_number" >

                          </div>

                          <div class="form-group col-sm-6">
                              <label for="">Postal<span class="error">*</span></label>
                              <input type="text" name="shipping_addr_zipcode[]" class="form-control shipping_addr_zipcode" onchange="get_address_from_postalcode(this, 'delivery_addr_row', 'shipping_address')" >

                          </div>

                          <div class="form-group col-sm-6">
                              <label for="">Address <span class="error">*</span></label>
                              <textarea name="shipping_address[]" class="form-control shipping_address" placeholder="Address" ></textarea>

                          </div>

                          <div class="form-group col-lg-6">
                              <label for="#">Email</label>
                              <input type="text" name="shipping_addr_email[]" class="form-control shipping_addr_email">

                          </div>
                             <div class="form-group col-sm-1" style="margin-top: 32px;">
                              <a class="btn btn-danger delivery_addr_remove_btn">
                                <i class="fa fa-times"></i>
                              </a>
                          </div>
                      </div>
                   
                      `;

            $("#delivery_addr_group").append(html);

        });

        $('body').on('click', '.delivery_addr_remove_btn', function() {

            $(this).parents('.custom_delivery_addr').remove();

        });
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
                                            <input type="text" placeholder="Price $" value="" name="price[]" id="price` + item + `" class="form-control">
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
