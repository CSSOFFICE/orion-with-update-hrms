<style>
    #add_form {
        height: 100% !important;
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
                <h3 class="box-title"><?php echo "Add New Customer"; ?></h3>
                <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                        <button type="button" class="btn btn-danger rounded-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form">  <i class="ti-plus"></i>
                            </button>
                    </a> </div>
            </div>
            
            <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
                <div class="box-body">
                    <?php $attributes = array('name' => 'add_crm', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('_user' => $session['user_id']); ?>
                    <?php echo form_open('admin/crm/add_crm', $attributes, $hidden); ?>
                    <div class="bg-white">
                        <div class="box-block">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Select Customer Type</label>
                                            <select id="customer" name="customer" class="form-control">
                                                <option value="" selected="selected">Select Customer</option>
                                                <option value="company">Company Customer</option>
                                                <option value="individual">Individual Customer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div name="individual" id="individual" style="display:none">
                                        <h5>Individual Customer add Form</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Customer Name<i class="hrsale-asterisk">*</i></label>
                                                    <input type="text" name="cust_name" id="cust_name" class="form-control" placeholder="Customer Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Contact Number<i class="hrsale-asterisk">*</i></label>
                                                    <input type="number" name="cust_number" id="cust_number" class="form-control" placeholder="Contact Number">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="custmr_email" id="custmr_email" class="form-control" placeholder="Customer Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Postal Code<i class="hrsale-asterisk">*</i></label>
                                                    <input type="number" name="po_code" id="po_code" class="form-control" placeholder="Postal Code">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea name="indv_addres" id="indv_addres" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Unit Number<i class="hrsale-asterisk">*</i></label>
                                                    <input type="text" name="u_num" id="u_num" class="form-control" placeholder="Unit Number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Credit Limit</label>
                                                    <input type="number" name="c_limit" id="c_limit" class="form-control" placeholder="Credit Limit">
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
                                                    <tbody class="AddItem2" id="vendor_items_table1"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th style="border: none !important;">
                                                                <a href="javascript:void(0)" class="btn-sm btn-success" id="addButton2">+ Add New</a>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                    <div name="company" id="company" style="display:none">
                                        <h5>Company Customer add Form</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Company Name<i class="hrsale-asterisk">*</i></label>
                                                    <input type="text" name="com_name" id="com_name" class="form-control" placeholder="Company Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Name<i class="hrsale-asterisk">*</i></label>
                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Company UEN</label>
                                                    <input type="text" name="c_uen" id="c_uen" class="form-control" placeholder="Company UEN">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Contact Number<i class="hrsale-asterisk">*</i></label>
                                                    <input type="number" name="c_number" id="c_number" class="form-control" placeholder="Contact Number">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="cust_email" id="cust_email" class="form-control" placeholder="Customer Email">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Postal Code<i class="hrsale-asterisk">*</i></label>
                                                    <input type="number" name="pos_code" id="pos_code" class="form-control" placeholder="Customer Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea name="com_address" id="com_address" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Unit Number<i class="hrsale-asterisk">*</i></label>
                                                    <input type="text" name="un_num" id="un_num" class="form-control" placeholder="Unit Number">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Credit Limit</label>
                                                    <input type="number" name="cr_limit" id="cr_limit" class="form-control" placeholder="Credit Limit">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Company Logo</label>
                                                    <input type="file" name="c_logo" id="c_logo" class="form-control">
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
                                                    <tbody class="AddItem" id="vendor_items_table1"></tbody>
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


    <div class="tabs">
        <span data-tab-value="#tab_1">Indiuvidual</span>
        <span data-tab-value="#tab_2">Company</span>
    </div>
    
    <div class="tab-content">
        <div class="tabs__tab active" id="tab_1" data-tab-info>
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo "List all"; ?>
                    <?php echo "Individual Customers"; ?></h3>
            </div>
            <div class="box-body">
                <div class="box-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered" id="table_individual">
                        <thead>
                            <tr>
                                <th><?php echo "Sl No."; ?></th>
                                <th><?php echo "Company Name"; ?></th>
                                <th><?php echo "Contact Number"; ?></th>
                                <th><?php echo "Email"; ?></th>
                                <th><?php echo "Postal Code"; ?></th>
                                <th><?php echo "Address"; ?></th>
                                <th><?php echo "Unit Number"; ?></th>
                                <th><?php echo "Credit Limit"; ?></th>
                                <th><?php echo $this->lang->line('xin_action'); ?></th>

                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>




        <div class="tabs__tab" id="tab_2" data-tab-info>

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo "List all Company Customers"; ?></h3>
            </div>
            <div class="box-body">
                <div class="box-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered" id="table_company" style="width:100%!important;">
                        <thead>
                            <tr>
                                <th><?php echo "Sl No."; ?></th>
                                <th><?php echo "Customer Name"; ?></th>
                                <th><?php echo "Company Name"; ?></th>
                                <th><?php echo "Company UEN"; ?></th>
                                <th><?php echo "Contact Number"; ?></th>
                                <th><?php echo "Email"; ?></th>
                                <th><?php echo "Postal Code"; ?></th>
                                <th><?php echo "Customer Address"; ?></th>
                                <th><?php echo "Unit Number"; ?></th>
                                <th><?php echo "Credit Limit"; ?></th>
                                <th><?php echo $this->lang->line('xin_action'); ?></th>

                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>



<!-- </div> -->


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
    [data-tab-info] {
        display: none;
    }

    .active[data-tab-info] {
        display: block;
    }

    .tab-content {
        margin-top: 1rem;
        padding-left: 1rem;
        font-size: 14px;
        font-family: sans-serif;
        font-weight: bold;
        color: rgb(0, 0, 0);
    }

    .tabs {
        /* border-bottom: 1px solid grey; */
        background-color: solid #d2d6de !important;
        font-size: 14px;
        color: rgb(0, 0, 0);
        display: flex;
        margin: 0;
    }

    .tabs span {
        background: #f8f9fa;
        padding: 10px;
        border: 1px solid rgb(255, 255, 255);
    }

    .tabs span:hover {
        background: rgb(55, 219, 46);
        cursor: pointer;
        color: black;
    }
</style>
<script type="text/javascript">
    // function to get each tab details
    const tabs = document.querySelectorAll('[data-tab-value]')
    const tabInfos = document.querySelectorAll('[data-tab-info]')

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = document
                .querySelector(tab.dataset.tabValue);
            tabInfos.forEach(tabInfo => {
                tabInfo.classList.remove('active')
            })
            target.classList.add('active');
        })
    })
</script>
<script>
    $("#customer").on("change", function() {
        $("#" + $(this).val()).show().siblings().hide();
    })


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
                    <td style="min-width:130px">
                            <label id="` + item + `">` + item + `<label>
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Person In Charge" name="pr_ic[]" id="pr_ic` + item + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Contact Number" name="c_n[]" id="c_n` + item + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Email" name="e_mail[]" id="e_mail` + item + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <textarea placeholder="Address" name="a_dd[]" id="a_dd` + item + `" class="form-control"></textarea>
                        </td>
                        
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

        });
        $('#addButton1').on('click', function() {
            var item = $('.AddItem tr').length;
            var lst = $('#vendor_items_table1 tr:last td label').attr('id');

            var item = item + 1;
            if (item <= lst) {
                item = (Number(lst) + 1);
            }
            $('.AddItem').append(`
                    <tr>
                    <td style="min-width:130px">
                            <label id="` + item + `">` + item + `<label>
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Person In Charge" name="pr_ic[]" id="pr_ic` + item + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Contact Number" name="c_n[]" id="c_n` + item + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <input type="text" placeholder="Email" name="e_mail[]" id="e_mail` + item + `" class="form-control">
                        </td>
                        <td style="min-width:200px">
                            <textarea placeholder="Address" name="a_dd[]" id="a_dd` + item + `" class="form-control"></textarea>
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