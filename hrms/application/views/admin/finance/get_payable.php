<style>
    [type="checkbox"]:checked+label:before {
        display: none;
    }

    [type="checkbox"]:checked+label {
        padding-left: 12px;

    }

    [type="checkbox"].filled-in:checked.chk-col-light-blue+label:after {
        display: none;
    }
</style>

<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php echo $this->lang->line('xin_payable'); ?> </h3>
    </div>
    <div class="box-body">
        <div class="box-datatable ">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <div class="row d-flex justify-content-end">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3">

                                <select class="form-control" id="search_supplier" aria-label="Default select example">
                                    <option value="" selected>Select Supplier</option>
                                    <?php foreach ($all_suppliers as $supplier) { ?>
                                        <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->supplier_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">

                                <input placeholder="start date" id="start_date" class="textbox-n form-control" type="text" onfocus="(this.type='date')" id="date" />
                            </div>
                            <div class="col-md-3">

                                <input placeholder="end date" id="end_date" class="textbox-n form-control" type="text" onfocus="(this.type='date')" id="date" />
                            </div>
                            <!-- <div class="col-md-3">

                                <select class="form-control" id="search_status" aria-label="Default select example">
                                    <option value="" selected>Select Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="overdue">Overdue</option>

                                </select>
                            </div> -->
                        </div>

                    </div>
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('xin_action'); ?></th>
                            <th><?php echo $this->lang->line('xin_purchase_invoice_no'); ?></th>
                            <th><?php echo $this->lang->line('xin_purchase_order_no'); ?></th>
                            <th><?php echo $this->lang->line('xin_supplier'); ?></th>
                            <th><?php echo $this->lang->line('xin_amount'); ?></th>
                            <!-- <th><?php echo $this->lang->line('xin_due_date'); ?></th> -->
                            <th><?php echo $this->lang->line('xin_created_date'); ?></th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </div>
            </table>
    </div>
</div>

<script>
    function GSTinTotal() {
        var selectedGst = $("#payable_total_gst1 option:selected").val();
        var pur_order = $('#pur_order').val();
        // console.log(pr_id);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/Payable/getPODetails/'; ?>" + pur_order,
            data: JSON,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);

                $('#n_gst_val').text(product_data.po_detail[0].gst);
                var def_nine = product_data.po_detail[0].ord_total * (product_data.po_detail[0].gst / 100);
                var final = def_nine + product_data.po_detail[0].ord_total;
                $('#gst_val').text(def_nine);
                $('#def_val').text(final);
                console.log(final);

            }
        });


    }

    function getPODetails() {

        var pur_order = $('#pur_order').val();
        // console.log(pr_id);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'admin/Payable/getPODetails/'; ?>" + pur_order,
            data: JSON,
            success: function(data) {
                var product_data = jQuery.parseJSON(data);
                $("#p_name").text(product_data.po_detail[0].project_title);
                $("#e_name").text(product_data.po_detail[0].first_name + " " + product_data.po_detail[0].last_name);
                $("#pr_no").text(product_data.po_detail[0].porder_id);
                $("#sup_name").text(product_data.po_detail[0].supplier_name);



                let abc = product_data.po_items.map((r, index) => {

                    return (`
                        <tr>
                            <td>${index+1}</td>
                            <td>${r.product_name}</td>
                            <td>${r.prd_price}</td>
                            <td>${r.prd_qtn}</td>
                            <td>${r.prd_total}</td>                      
                        </tr>                        
                    `);

                });

                $("#ord_total").text(product_data.po_detail[0].ord_total);
                $('#ord_total1').val(product_data.po_detail[0].ord_total);
                $('#n_gst_val').text(product_data.po_detail[0].gst);

                var def_nine = product_data.po_detail[0].ord_total * (product_data.po_detail[0].gst / 100);
                var final = def_nine + product_data.po_detail[0].ord_total;
                $('#gst_val').text(def_nine);
                $('#def_val').text(final);
                console.log(final);

                $('#conf_pr_data').html(abc.join(''));

            }
        });

    }
</script>


<script>
    $("#add_payable").submit(function(e) {
        /*Form Submit*/
        var fd = new FormData(this);
        var gst_val = $('#gst_val').text();
        var def_val = $('#def_val').text();
        var n_gst_val = $('#n_gst_val').text();
        var ord_total = $('#ord_total').text();
        var gstNumber = parseFloat(n_gst_val.match(/\d+/)[0]);

        // console.log(ord_total,gst_val,gstNumber,def_val)

        // return false;
        var obj = $(this),
            action = obj.attr('name');
        fd.append("ord_total", ord_total);
        fd.append("gst_val", gst_val);
        fd.append("gstNumber", gstNumber);
        fd.append("def_val", def_val);
        fd.append("is_ajax", 1);
        fd.append("data", 'add_payable');
        fd.append("type", 'add_payable');
        fd.append("form", action);

        e.preventDefault();

        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            // url: e.target.action,
            url: base_url + "/add_payable",

            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    var xin_table = $('#xin_table').dataTable({
                        "bDestroy": true,
                        "ajax": {
                            url: "<?php echo site_url("admin/Payable/payable_list") ?>",
                            type: 'GET'
                        },

                    });
                    xin_table.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        var total_amount = parseFloat($('#def_val').text()).toFixed(2);
        var paid_amount = 0;
        var remaining_amount = total_amount - paid_amount;
        $("#remaining_amount").val(remaining_amount.toFixed(2));

        $("#is_payable_gst").change(function() {
            if (this.checked) {
                $("#payable_gst_div").hide();
                var total_amount = parseFloat($('#amount').val()).toFixed(2);
                $('#total_amount1').val(total_amount);
            } else {
                $("#payable_gst_div").show();
                var total_amount = parseFloat($('#amount').val()).toFixed(2);
                var tax = parseFloat($("#payable_total_gst option:selected").text());
                var total = (total_amount + total_amount * (tax / 100)).toFixed(2);
                $('#payable_total_amount').val(total);
            }
        });
    });

    function calculation() {
        var total_amount = parseFloat($('#def_val').text()).toFixed(2);
        var pay_amount = $("#amount").val();
        var tmp_pay_amount = $("#payable_total_amount").val(pay_amount);
        var final_pay_amount = $("#payable_total_amount").val();

        var paid_amount = 0;

        if (!isNaN(parseFloat(pay_amount)) && parseFloat(pay_amount) > 0) {
            var remaining_amount = (total_amount - (paid_amount + parseFloat(final_pay_amount))).toFixed(2);
        } else {
            var remaining_amount = (total_amount - paid_amount).toFixed(2);
        }

        $("#remaining_amount").val(remaining_amount);
    }

    function totalGSTAmount() {
        var total_amount = parseFloat($('#def_val').text()).toFixed(2);
        var paid_amount = 0;
        var pay_total_amount = parseFloat($('#amount').val()).toFixed(2);
        var tax = parseFloat($("#payable_total_gst option:selected").text());
        var total = (pay_total_amount + pay_total_amount * (tax / 100)).toFixed(2);
        var remaining_amount = (total_amount - (paid_amount + parseFloat(total))).toFixed(2);
        $('#payable_total_amount').val(total);
        $("#remaining_amount").val(remaining_amount);
    }
</script>