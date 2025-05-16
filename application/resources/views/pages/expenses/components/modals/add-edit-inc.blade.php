<?php

use Illuminate\Support\Facades\DB;

$purchaseOrder = DB::table('purchase_order')->get();
$projects = DB::table('projects')->get();
?>
<div class="row" id="js-trigger-expenses" data-client-id="{{ $expense->expense_clientid ?? '' }}"
    data-payload="{{ config('visibility.expense_modal_trigger_clients_project_list') }}">

    <div class="col-lg-12">
        @if(config('visibility.expenses_col_project_op'))
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Project Selecte</label>
            <div class="col-sm-12 col-lg-9">
                <select class="form-control" class="form-control form-control-sm" name="expense_projectid"
                    data-plugin="select_hrm" id="expense_projectid">
                    <option>Project Select</option>
                    <?php foreach ($projects as $k => $pr) { ?>
                        <option value="<?php echo $pr->project_id; ?>"><?php echo $pr->project_title; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Purchase Order
                <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <select class="form-control form-control-sm" id="porder_id" name="porder_id" data-plugin="select_hrm">
                    <option value="" selected>Select Option</option>
                    @foreach ($purchaseOrder as $po)
                    <option value="{{ $po->purchase_order_id }}"
                        {{ runtimePreselected($expense->purchase_order_no ?? '', $po->purchase_order_id) }}>
                        {{ runtimeLang($po->porder_id) }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Choose Category </label>
            <div class="col-sm-12 col-lg-9">
                <select class="form-control" class="form-control form-control-sm" name="category_id"
                    data-plugin="select_hrm" id="category_id_prili">
                    <option>Choose Category</option>
                    <?php foreach ($quotation_category as $k => $purchase) { ?>
                        <option value="<?php echo $purchase->milestonecategory_id; ?>"><?php echo $purchase->milestonecategory_title; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">Task </label>
            <div class="col-sm-12 col-lg-9">
                <select name="task_id" id="task_id" class="form-control" data-plugin="select_hrm">

                </select>
            </div>
        </div>
        <input type="hidden" name="new_invoice_no" id="new_invoice_no">
        <input type="hidden" name="new_invoice_amount" id="new_invoice_amount">
        @if(config('visibility.expenses_col_project_op')==null)
        <input type="hidden" value="{{ request('expenseresource_id') }}" name="expense_projectid"
            id="expense_projectid">
        @endif

        <!--Purchase Order-->

        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Amount
                <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon" id="basic-addon2">$</span>
                    <input type="number" name="expense_amount" class="form-control form-control-sm ">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Petty Cash
                <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <div class="input-group input-group-sm">
                    <input class="form-check-input" type="checkbox" name="petty_cash" id="exampleCheckbox"
                        data-id="" data-client-id="">
                    <label class="form-check-label" for="exampleCheckbox"></label>
                </div>
            </div>
        </div>
        <!--
        <div id="div_expence">

            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice No <span>*</span></label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm invoice_no" name="invoice_no[]" onblur="add_data()">
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice Amount <span>*</span></label>
                <div class="col-sm-12 c}}ol-lg-9">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="basic-addon2">$</span>
                        <input type="number" name="invoice_amount[]" class="form-control form-control-sm invoice_amount" aria-describedby="basic-addon2" onblur="add_data()">
                    </div>
                </div>
            </div>
        </div> -->

        <!-- <div class="form-group text-right">
            <button type="button" class="btn btn-primary" id="add_column_ex">Add Invoice</button>
        </div> -->

        <!--date-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" autocomplete="off"
                    name="expense_date" value="{{ date('d-m-Y') }}">
                <input class="mysql-date" type="hidden" name="expense_date" value="{{ date('d-m-Y') }}" id="date">
            </div>
        </div>

        <!--do no-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">DO No
                <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" name="do_no">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice No
                <span>*</span></label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" name="invoice"
                    value="{{ $expense->purchase_invoice_no ?? '' }}">
            </div>
        </div>


        <!--Attach Invoice-->
        {{-- <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">Attachment</label>
            <div class="col-sm-12 col-lg-9">
                <input type="file" class="form-control" name="expense_attachment">
            </div>
        </div> --}}

        <!--fileupload-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Attachment</label>
            <div class="col-12">
                <div class="dropzone dz-clickable text-center file-upload-box" id="fileupload_expense_receipt">
                    <div class="dz-default dz-message">
                        <div>
                            <h4>{{ cleanLang(__('lang.drag_drop_file')) }}</h4>
                        </div>
                        <div class="p-t-10"><small>{{ cleanLang(__('lang.allowed_file_types')) }}: (jpg|png)</small>
                        </div>
                        <div class=""><small>{{ cleanLang(__('lang.best_image_dimensions')) }}: (185px X
                                45px)</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!--existing files-->
        @if (isset($page['section']) && $page['section'] == 'edit')
        <table class="table table-bordered">
            <tbody>
                @foreach ($attachments as $attachment)
                <tr id="expense_attachment_{{ $attachment->attachment_id }}">
                    <td>{{ $attachment->attachment_filename }} </td>
                    <td class="w-px-40"> <button type="button"
                            class="btn btn-danger btn-circle btn-sm confirm-action-danger"
                            data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" active"
                            data-ajax-type="DELETE"
                            data-url="{{ url('/expenses/attachments/' . $attachment->attachment_uniqiueid) }}">
                            <i class="sl-icon-trash"></i>
                        </button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    </div>

    <!--pass source-->
    <input type="hidden" name="source" value="{{ request('source') }}">
    <input type="hidden" name="ref" value="{{ request('ref') }}">

    <div class="row">
        <div class="col-12">
            <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
        </div>
    </div>
</div>

<script>
    // console.log($("#div_expence").parents('#commonModalForm').get(0));

    function add_data() {
        var invoice_no_arr = [];
        var invoice_amount_arr = [];

        var inputs1 = $(".invoice_no");
        var inputs2 = $(".invoice_amount");

        for (var i = 0; i < inputs1.length; i++) {
            invoice_no_arr.push($(inputs1[i]).val());
        }

        for (var i = 0; i < inputs2.length; i++) {
            invoice_amount_arr.push($(inputs2[i]).val());
        }

        console.log(invoice_no_arr);
        console.log(invoice_amount_arr);

        $("#new_invoice_no").val(invoice_no_arr);
        $("#new_invoice_amount").val(invoice_amount_arr);
    }

    $(document).ready(function() {

        $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
        $('[data-plugin="select_hrm"]').select2({
            width: '100%'
        });

        $("#add_column_ex").on("click", function() {

            var html = `<div class="form-group row">
                            <label
                                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice No <span>*</span></label>
                            <div class="col-sm-12 col-lg-9">
                                <input type="text" class="form-control form-control-sm invoice_no" name="invoice_no[]" onblur="add_data()">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Invoice Amount <span>*</span></label>
                            <div class="col-sm-12 col-lg-9">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon" id="basic-addon2">$</span>
                                    <input type="number" name="invoice_amount[]" class="form-control form-control-sm invoice_amount" aria-describedby="basic-addon2" onblur="add_data()">
                                </div>
                            </div>
                        </div>`;

            $("#div_expence").append(html);

        });

    });
    $(document).ready(function() {
        $("#category_id_prili").on("change", function() {

            let id = $(this).val();

            let jsArray = @json($budgtrepo);





            jsArray = jsArray.data.filter((re) => re.task_cat_id == id);
            let op = `<option value="" selected>Task</option>`;
            op += jsArray.map(re => `<option value="${re.task_id}">${re.task_title}</option>`).join('');

            $("#task_id").html(op);



        })
    })
    $('#expense_projectid').on('change', function() {
        var project_id = $('#expense_projectid').val();
        $.ajax({
            url: "<?php echo url('/') . '/hrms/admin/Finance/get_quotation_from_project/'; ?>" + project_id,
            type: "POST",
            success: function(response) {
                // Clear existing values
                $('#qt_no').val('');
                $('#qt_id').val('');
                $('#milestone_id').empty(); // Clear the milestone dropdown
                $("#client_id").val(response.quotation_no[0].project_clientid).trigger('change');
                let projectSum = parseFloat(response.quotation_no[0].project_sn);
                $("#contract_sum").val(isNaN(projectSum) ? "0.00" : projectSum.toFixed(2));
                $("#supervisor").val(response.quotation_no[0].supervisor).trigger('change');
                $("#site_address").empty();
                $("#site_address").val(response.quotation_no[0].project_address);



                // Milestone ID-to-name mapping
                const milestoneMapping = {
                    1: 'Preliminaries',
                    2: 'Insurances',
                    3: 'Schedule Of Works',
                    4: 'Plumbing & Sanitary',
                    5: 'Elec & Acmv',
                    6: 'External Works',
                    7: 'Pc & Ps Sums',
                    8: 'Others'
                };

                // Check if Milestone data exists
                if (response.milestone_list && response.milestone_list.length > 0) {
                    let milestoneOptions = '<option value="">Select Milestone</option>';
                    $.each(response.milestone_list, function(index, milestone) {
                        const milestoneName = milestoneMapping[milestone.task_cat_id] || 'Unknown Milestone';
                        milestoneOptions += `<option value="${milestone.task_cat_id}">${milestoneName}</option>`;
                    });
                    $('#category_id_prili').html(milestoneOptions);
                } else {
                    toastr.error("No Milestone found for this project.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error occurred: ", error);
                alert("An error occurred while fetching the quotation. Please try again later.");
            }
        });
    });
    $('#milestone_id').on('change', function() {
        var milestone_id = $('#milestone_id').val();
        var project_id = $('#project_id').val();
        $.ajax({
            url: "<?php echo url('/') . 'admin/Finance/get_task_from_milestone/'; ?>" + milestone_id + "/" + project_id,
            type: "POST",
            success: function(response) {
                // Clear existing values
                $('#task_id').empty(); // Clear the Task dropdown

                // Check if Task data exists
                if (response.task_list && response.task_list.length > 0) {
                    let taskOptions = '<option value="">Select Task</option>';
                    $.each(response.task_list, function(index, task) {
                        taskOptions += `<option value="${task.task_id}">${task.task_title}</option>`;
                    });
                    $('#task_id').html(taskOptions);
                } else {
                    toastr.error("No Task found for this project.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error occurred: ", error);
                alert("An error occurred while fetching the quotation. Please try again later.");
            }
        });
    });
</script>
