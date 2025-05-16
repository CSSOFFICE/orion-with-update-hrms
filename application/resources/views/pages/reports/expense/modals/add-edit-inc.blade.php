<style>
    .select2-selection {
        width: 170px;
    }
</style>
<div class="row" id="js-trigger-expenses" data-client-id="{{ $expense->expense_clientid ?? '' }}"
    data-payload="{{ config('visibility.expense_modal_trigger_clients_project_list') }}">
    <div class="col-lg-12">


        <!--purchase invoice number-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.purchase_invoice_number')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" name="purchase_invoice_number"
                    value="{{ $expense->purchase_invoice_number ?? '' }}">
            </div>
        </div>


        <!--supplier list-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">{{ cleanLang(__('lang.supplier_list')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="supplier_list_id"
                    name="supplier_list_id">
                    <option value=" ">--Select--</option>
                    @foreach ($supplier_list as $list)
                        <option value="{{ $list->supplier_id }}"
                            {{ runtimePreselected($expense->expense_supplier_id ?? '', $list->supplier_id) }}>
                            {{ runtimeLang($list->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>



        <!--description-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.description')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <textarea class="w-100" id="expense_description" rows="4" name="expense_description">{{ $expense->expense_description ?? '' }}</textarea>
            </div>
        </div>

        <!--date-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" autocomplete="off"
                    name="expense_date" value="{{ runtimeDatepickerDate($expense->expense_date ?? '') }}">
                <input class="mysql-date" type="hidden" name="expense_date" id="expense_date"
                    value="{{ $expense->expense_date ?? '' }}">
            </div>
        </div>


        <!--amount-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.amount')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon" id="basic-addon2">$</span>
                    <input type="number" name="expense_amount" id="expense_amount" class="form-control form-control-sm"
                        value="{{ $expense->expense_amount ?? '' }}" aria-describedby="basic-addon2">
                </div>
            </div>
        </div>


        <!--category-->
        <!-- <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">{{ cleanLang(__('lang.category')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="expense_categoryid"
                    name="expense_categoryid">
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}"
                            {{ runtimePreselected($expense->expense_categoryid ?? '', $category->category_id) }}>
                            {{ runtimeLang($category->category_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div> -->

        <!-- {{-- <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label  required">{{ cleanLang(__('lang.account_code')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="account_code_id" name="account_code_id">
                    <option value=" ">Select</option>
                    @foreach ($account_code as $code)
                        <option value="{{ $code->account_code_id }}"
                            {{ runtimePreselected($expense->account_code_id ?? '', $code->account_code_id) }}>
                            {{ runtimeLang($code->acc_no) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div> --}} -->



        {{-- <div class="line"></div> --}}
        {{-- <div class="row">
            <label
                class="col-md-12  text-left control-label col-form-label  required">{{ cleanLang(__('lang.product_section')) }}</label>
        </div> --}}
        {{-- <div class="row">
            <table class="table m-t-0 m-b-0 table-hover no-wrap">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qnt</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="append_new_product_section">
                    <tr>
                        <td>
                            <select name="product_id[]" class="select2-basic form-control form-control-sm"
                                id="product_id">
                                <option value=" ">--Select--</option>
                                @foreach ($product_list as $list)
                                    <option value="{{ $list->item_id }}">
                                        {{ runtimeLang($list->item_name) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="product_qnt[]" class="form-control form-control-sm"
                                min="1" id="product_qnt">
                        </td>
                        <td>
                            <input type="text" name="product_amount[]" class="form-control form-control-sm"
                                id="product_amount">
                        </td>
                        <td>
                            <i class="ti-plus" style="cursor: pointer;" id="add_new_product"></i>
                        </td>
                    </tr>

                    @if ($expense->product_section ?? false)
                        @foreach (json_decode($expense->product_section) as $item)
                            <tr>
                                <td>
                                    <select name="product_id[]" class="select2-basic form-control form-control-sm"
                                        id="product_id">
                                        <option value=" ">--Select--</option>
                                        @foreach ($product_list as $list)
                                            <option value="{{ $list->item_id }}"
                                                {{ runtimePreselected($item->product_id ?? '', $list->item_id) }}>
                                                {{ runtimeLang($list->item_name) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="product_qnt[]" class="form-control form-control-sm"
                                        min="1" id="product_qnt" value="{{ $item->qnt }}">
                                </td>
                                <td>
                                    <input type="text" name="product_amount[]" class="form-control form-control-sm"
                                        id="product_amount" value="{{ $item->amount }}">
                                </td>
                                <td>
                                    <i class="sl-icon-trash remove_add_new_product" style="cursor: pointer;"></i>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div> --}}

        {{-- <div class="line"></div>
        <div class="float-right">
            <i class="ti-plus" style="cursor: pointer;" id="add_new_main_product_section"></i>
        </div> --}}
        <div class="row">
            <label
                class="col-md-12  text-left control-label col-form-label  required">{{ cleanLang(__('lang.project_section')) }}</label>
        </div>
        <div class="" id="main_add_new_project_section">
            @if (empty($expense->client_with_project_section) ?? false)
                <div class="row">
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-form-label">{{ cleanLang(__('lang.client')) }}</label>
                            <div>
                                <!--select2 basic search-->
                                <select name="expense_clientid[]" id="expense_clientid"
                                    class="clients_and_projects_toggle form-control form-control-sm js-select2-basic-search-modal expense_client_id"
                                    data-projects-dropdown="expense_project_list"
                                    data-feed-request-type="clients_projects"
                                    data-ajax--url="{{ url('/') }}/feed/company_names">
                                    @if (isset($expense->expense_clientid) && $expense->expense_clientid != '')
                                        <option value="{{ $expense->expense_clientid ?? '' }}">
                                            {{ $expense->client_company_name }}
                                        </option>
                                    @endif
                                </select>
                                <!--select2 basic search-->
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-month-input"
                                class=" col-form-label text-left">{{ cleanLang(__('lang.project')) }}</label>
                            <div class="">
                                @if (isset($expense->expense_projectid) && $expense->expense_projectid == '')
                                    <select
                                        class="select2-basic form-control form-control-sm all_product_list more_expense_project_list"
                                        id="expense_project_list" name="expense_projectid[]" disabled>
                                    </select>
                                @else
                                    <select
                                        class="select2-basic form-control form-control-sm all_product_list more_expense_project_list"
                                        id="expense_project_list" name="expense_projectid[]">
                                        <option value="{{ $expense->expense_projectid ?? '' }}">
                                            {{ $expense->project_title ?? '' }}
                                        </option>
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-4">
                        <div class="form-group" id="back_charges_field_section"
                            style="display: {{ !empty($expense->back_charges) ?? '' }}">
                            <label
                                class=" text-left control-label col-form-label ">{{ cleanLang(__('lang.back_charges')) }}</label>
                            <div class="">
                                <input type="text" name="back_charges[]" class="form-control form-control-sm"
                                    value="{{ $expense->back_charges ?? '' }}">
                            </div>
                        </div>

                    </div> --}}

                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="text-center">
                                <th scope="col">{{ cleanLang(__('lang.client')) }}</th>
                                <th scope="col">{{ cleanLang(__('lang.project')) }}</th>
                                <th scope="col">{{ cleanLang(__('lang.back_charges')) }}</th>
                                <th scope="col">Product</th>
                                <th scope="col">Qnt</th>
                                <th scope="col">Amount</th>
                                <th scope="col">{{ cleanLang(__('lang.account_code')) }}</th>
                            </thead>
                            <tbody class="add_append_new_product_project_section">
                                <tr>
                                    <td>
                                        <select name="expense_clientid[]" id="expense_clientid"
                                            class="clients_and_projects_toggle form-control form-control-sm js-select2-basic-search-modal expense_client_id"
                                            data-projects-dropdown="expense_project_list"
                                            data-feed-request-type="clients_projects"
                                            data-ajax--url="{{ url('/') }}/feed/company_names">
                                            @if (isset($expense->expense_clientid) && $expense->expense_clientid != '')
                                                <option value="{{ $expense->expense_clientid ?? '' }}">
                                                    {{ $expense->client_company_name }}
                                                </option>
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        @if (isset($expense->expense_projectid) && $expense->expense_projectid == '')
                                            <select
                                                class="select2-basic form-control form-control-sm all_product_list more_expense_project_list"
                                                id="expense_project_list" name="expense_projectid[]" disabled>
                                            </select>
                                        @else
                                            <select
                                                class="select2-basic form-control form-control-sm all_product_list more_expense_project_list"
                                                id="expense_project_list" name="expense_projectid[]">
                                                <option value="{{ $expense->expense_projectid ?? '' }}">
                                                    {{ $expense->project_title ?? '' }}
                                                </option>
                                            </select>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="back_charges[]" class="form-control back_charges"
                                            value="{{ $expense->back_charges ?? '' }}" style="width: 170px;">
                                    </td>
                                    <td>
                                        <select name="project_id[]"
                                            class="select2-basic form-control form-control-sm product_id">
                                            <option value=" ">--Select--</option>
                                            @foreach ($product_list as $list)
                                                <option value="{{ $list->item_id }}">
                                                    {{ runtimeLang($list->item_name) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="project_qnt[]"
                                            class="form-control form-control-sm product_qnt" min="1"
                                            style="width: 170px;">
                                    </td>
                                    <td>
                                        <input type="text" name="project_amount[]"
                                            class="form-control form-control-sm product_amount" style="width: 170px;">
                                    </td>
                                    <td>
                                        <select class="select2-basic form-control form-control-sm account_code"
                                            id="account_code_id" name="account_code[]">
                                            <option value=" ">Select</option>
                                            @foreach ($account_code as $code)
                                                <option value="{{ $code->account_code_id }}"
                                                    {{ runtimePreselected($expense->account_code_id ?? '', $code->account_code_id) }}>
                                                    {{ runtimeLang($code->acc_no) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <i class="ti-plus add_new_section" style="cursor: pointer;"></i>
                                    </td>
                                </tr>
                                @if ($expense->project_with_product_section ?? false)
                                    @foreach (json_decode($expense->project_with_product_section) as $item)
                                        <tr>
                                            <td class="store_client_id" style="display:none">{{ $item->client_id }}
                                            </td>
                                            <td class="store_client_name">{{ $item->client_name }}</td>
                                            <td class="store_project_id" style="display:none">{{ $item->project_id }}
                                            </td>
                                            <td class="store_project_name">{{ $item->project_name }}</td>
                                            <td class="store_back_charges">{{ $item->back_charge }}</td>
                                            <td class="store_product_id" style="display:none">{{ $item->product_id }}
                                            </td>
                                            <td class="store_product_name">{{ $item->product_name }}</td>
                                            <td class="store_product_qnt">{{ $item->product_qnt }}</td>
                                            <td class="store_product_amount">{{ $item->product_amount }}</td>
                                            <td class="store_account_code">{{ $item->account_code }}</td>
                                            <td class="store_account_code_id" style="display:none">
                                                {{ $item->account_code_id }}
                                            </td>
                                            <td>
                                                <i class="sl-icon-trash remove_add_new_product_project"
                                                    style="cursor: pointer;"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif


                            </tbody>
                        </table>
                    </div>




                </div>
            @endif

        </div>


        <div class="line"></div>



        <!--column visibility-->
        {{-- @if (config('visibility.expense_modal_client_project_fields'))
            <div>
                <!--not yet invoice invoiced - can change client/project-->
                @if (config('visibility.expense_modal_edit_client_and_project'))
                    <!--client-->
                    <div class="form-group row">
                        <label
                            class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.client')) }}</label>
                        <div class="col-sm-12 col-lg-9">
                            <!--select2 basic search-->
                            <select name="expense_clientid" id="expense_clientid"
                                class="clients_and_projects_toggle form-control form-control-sm js-select2-basic-search-modal"
                                data-projects-dropdown="expense_project_list"
                                data-feed-request-type="clients_projects"
                                data-ajax--url="{{ url('/') }}/feed/company_names">
                                @if (isset($expense->expense_clientid) && $expense->expense_clientid != '')
                                    <option value="{{ $expense->expense_clientid ?? '' }}">
                                        {{ $expense->client_company_name }}
                                    </option>
                                @endif
                            </select>
                            <!--select2 basic search-->
                        </div>
                    </div>
                    <!--clients projects-->
                    <div class="form-group row">
                        <label for="example-month-input"
                            class="col-sm-12 col-lg-3 col-form-label text-left">{{ cleanLang(__('lang.project')) }}</label>
                        <div class="col-sm-12 col-lg-9">
                            @if (isset($expense->expense_projectid) && $expense->expense_projectid == '')
                                <select class="select2-basic form-control form-control-sm" id="expense_project_list"
                                    name="expense_projectid" disabled>
                                </select>
                            @else
                                <select class="select2-basic form-control form-control-sm" id="expense_project_list"
                                    name="expense_projectid">
                                    <option value="{{ $expense->expense_projectid ?? '' }}">
                                        {{ $expense->project_title ?? '' }}
                                    </option>
                                </select>
                            @endif
                        </div>
                    </div>
                @else
                    <!--already invoiced - cannot change client/project-->
                    <!--existing client-->
                    <div class="form-group row">
                        <label
                            class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.client')) }}</label>
                        <div class="col-sm-12 col-lg-9">
                            <input type="text" class="form-control"
                                value="{{ $expense->client_company_name ?? '' }}" disabled>
                            <input type="hidden" name="expense_clientid"
                                value="{{ $expense->expense_clientid ?? '' }}">
                        </div>
                    </div>
                    <!--existing client-->
                    <div class="form-group row">
                        <label
                            class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.project')) }}</label>
                        <div class="col-sm-12 col-lg-9">
                            <input type="text" class="form-control form-control-sm"
                                value="{{ $expense->project_title ?? '' }}" disabled>
                            <input type="hidden" name="expense_projectid"
                                value="{{ $expense->expense_projectid ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-right">
                            <small>{{ cleanLang(__('lang.expense_has_already_been_invoiced')) }}</small>
                        </div>
                    </div>
                    <div class="line"></div>
                @endif
            </div>
        @endif --}}

        <!--clients projects-->
        @if (config('visibility.expense_modal_clients_projects'))
            <div class="form-group row">
                <label for="example-month-input"
                    class="col-sm-12 col-lg-3 col-form-label text-left">{{ cleanLang(__('lang.project')) }}</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="select2-basic form-control form-control-sm" id="expense_projectid"
                        name="expense_projectid">
                        @foreach (config('settings.clients_projects') as $project)
                            <option value="{{ $project->project_id ?? '' }}">{{ $project->project_title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif


        {{-- <!--billable-->
        <div class="form-group form-group-checkbox row" id="expense_billable_option">
            <label class="col-sm-12 col-lg-3 col-form-label text-left">{{ cleanLang(__('lang.billable')) }}?</label>
            <div class="col-6 text-left p-t-5">
                @if (isset($page['section']) && $page['section'] == 'edit')
                    <input type="checkbox" id="expense_billable" name="expense_billable"
                        class="filled-in chk-col-light-blue"
                        {{ runtimePrechecked($expense['expense_billable'] ?? '') }}
                        {{ runtimeExpenseBillable($expense->expense_billing_status ?? '') }}>
                @else
                    <input type="checkbox" id="expense_billable" name="expense_billable"
                        class="filled-in chk-col-light-blue"
                        {{ runtimePrechecked(config('system.settings_expenses_billable_by_default')) }}>
                @endif
                <label for="expense_billable"></label>
            </div>
        </div>
        --}}


        <!--attach recipt - toggle-->
        <div class="spacer row">
            <div class="col-sm-12 col-lg-8">
                <span class="title">{{ cleanLang(__('lang.attach_receipt')) }}</span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" name="show_more_settings_expenses" id="show_more_settings_expenses"
                            class="js-switch-toggle-hidden-content" data-target="add_expense_attach_receipt">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>


        <!--attach recipt-->
        <div class="hidden" id="add_expense_attach_receipt">
            <!--fileupload-->
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="dropzone dz-clickable" id="fileupload_expense_receipt">
                        <div class="dz-default dz-message">
                            <i class="icon-Upload-toCloud"></i>
                            <span>{{ cleanLang(__('lang.drag_drop_file')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--fileupload-->
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
                <div><small><strong>* {{ cleanLang(__('lang.required')) }} {{ request('expense') }}</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>
@csrf

<div class="modal-footer" id="expence_commonModalFooter">
    <button type="button" class="btn btn-rounded-x btn-secondary waves-effect text-left"
        data-dismiss="modal">Close</button>
    <button type="submit" id="expence_commonModalSubmitButton"
        class="btn btn-rounded-x btn-danger waves-effect text-left"
        data-url="https://localhost/Seng-Fa-Piling/expenses?expenseresource_id=15&expenseresource_type=project"
        data-loading-target="commonModalBody" data-ajax-type="POST"
        data-on-start-submit-button="disable">Submit</button>
</div>


{{-- <a href="javascript:void(0);" id="submit_link"> click</a> --}}

<style>
    .remove {
        display: none;
    }
</style>
<script>
    $(document).ready(function() {
        $('.coustom_class').addClass('remove');
    });
    // if("{{ request('expense') }}")
    $('#expence_commonModalSubmitButton').unbind().click(function(e) {
        e.preventDefault();
        let project_product_details = [];
        $('.add_append_new_product_project_section > tr').each(function(e) {
            let client_id = $(this).find('.store_client_id').text();
            let client_name = $(this).find('.store_client_name').text();
            let project_id = $(this).find('.store_project_id').text();
            let project_name = $(this).find('.store_project_name').text();
            let product_id = $(this).find('.store_product_id').text();
            let product_name = $(this).find('.store_product_name').text();
            let product_qnt = $(this).find('.store_product_qnt').text();
            let product_amount = $(this).find('.store_product_amount').text();
            let back_charge = $(this).find('.store_back_charges').text();
            let account_code_id = $(this).find('.store_account_code_id').text();
            let account_code = $(this).find('.store_account_code').text();

            if (client_id != '' && project_id != '' && product_id != '' && product_qnt != '' &&
                product_amount != '') {
                let arr = {
                    client_id: client_id,
                    client_name: client_name,
                    project_id: project_id,
                    project_name: project_name,
                    product_id: product_id,
                    product_name: product_name,
                    product_qnt: product_qnt,
                    product_amount: product_amount,
                    back_charge: back_charge,
                    account_code_id: account_code_id,
                    account_code: account_code
                }

                project_product_details.push(arr);
            }
        });

        let url = "{{ request('expense') }}" ?
            "{{ urlResource('/expenses/update_expense/' . request('expense')) }}" :
            "{{ url('expenses') }}";

        let form = $('#commonModalForm')[0];
        let data = new FormData(form);
        data.append('project_product_details', JSON.stringify(project_product_details));
        data.append("_token", "{{ csrf_token() }}")
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: data,
            processData: false,
            contentType: false,
            success: function(payload) {
                console.log(payload);
                if (payload.notification.type == 'success') {
                    noty({
                        text: payload.notification.value,
                        layout: 'bottomLeft',
                        type: 'success',
                        timeout: '3000',
                        progressBar: false,
                        closeWith: ['click', 'button', 'backdrop'],
                    });

                    window.location.reload()
                }

            },
            error: function(error) {
                console.log(error)
                if (error.responseJSON.notification.type == 'error') {
                    noty({
                        text: error.responseJSON.notification.value,
                        layout: 'bottomLeft',
                        type: 'warning',
                        timeout: '3000',
                        progressBar: false,
                        closeWith: ['click', 'button', 'backdrop'],
                    });
                }
            }
        });
    })

    $('.remove_more_project_product').click(function() {
        $(this).parent().parent().remove();
    })

    $('.add_new_section').click(function() {
        let client_id = $(this).parent().parent().find('.expense_client_id').val();
        let client_name = $(this).parent().parent().find('.expense_client_id option:selected').text();
        let project_id = $(this).parent().parent().find('.more_expense_project_list').val();
        let project_name = $(this).parent().parent().find('.more_expense_project_list option:selected')
            .text();
        let back_charges = $(this).parent().parent().find('.back_charges').val();
        let product_id = $(this).parent().parent().find('.product_id').val();
        let product_name = $(this).parent().parent().find('.product_id option:selected').text();
        let product_qnt = $(this).parent().parent().find('.product_qnt').val();
        let product_amount = $(this).parent().parent().find('.product_amount').val();
        let account_code_id = $(this).parent().parent().find('.account_code option:selected').val();
        let account_code = $(this).parent().parent().find('.account_code option:selected').text();
        if (client_id != '' && project_id != '') {
            if (product_id != '' && product_qnt != '' && product_amount != '' && account_code != '') {
                $(this).parent().parent().closest('.add_append_new_product_project_section').append(`
                    <tr>   
                        <td class="store_client_id" style="display:none">${client_id}</td>
                        <td class="store_client_name">${client_name}</td>
                        <td class="store_project_id" style="display:none">${project_id}</td>
                        <td class="store_project_name">${project_name}</td>
                        <td class="store_back_charges">${back_charges}</td>
                        <td class="store_product_id" style="display:none">${product_id}</td>
                        <td class="store_product_name">${product_name}</td>
                        <td class="store_product_qnt">${product_qnt}</td>
                        <td class="store_product_amount">${product_amount}</td>
                        <td class="store_account_code">${account_code}</td>
                        <td class="store_account_code_id" style="display:none">${account_code_id}</td>
                        <td>
                            <i class="sl-icon-trash remove_add_new_product_project" style="cursor: pointer;"></i>
                        </td>
                    </tr>`)
            } else {
                noty({
                    text: 'Please Enter All Product Data!',
                    layout: 'bottomLeft',
                    type: 'warning',
                    timeout: '3000',
                    progressBar: false,
                    closeWith: ['click', 'button', 'backdrop'],
                });
            }
        } else {
            noty({
                text: 'Please Select Project First!',
                layout: 'bottomLeft',
                type: 'warning',
                timeout: '3000',
                progressBar: false,
                closeWith: ['click', 'button', 'backdrop'],
            });
        }

        product_id = $(this).parent().parent().find('.product_id').val(' ');
        $(this).parent().parent().find(
            '.product_id').trigger('change');
        product_qnt = $(this).parent().parent().find('.product_qnt').val(
            '');
        product_amount = $(this).parent().parent().find('.product_amount').val('');

        $(this).parent().parent().find('.expense_client_id').val('');
        $(this).parent().parent().find('.expense_client_id').trigger('change');
        $(this).parent().parent().find('.more_expense_project_list').val();
        $(this).parent().parent().find('.more_expense_project_list').trigger('change');
        $(this).parent().parent().find('.back_charges').val('');
        $(this).parent().parent().find('.product_id').val('');
        $(this).parent().parent().find('.product_id').trigger('change');
        $(this).parent().parent().find('.product_qnt').val('');
        $(this).parent().parent().find('.product_amount').val('');
        $(this).parent().parent().find('.account_code').val('');
        $(this).parent().parent().find('.account_code').trigger('change');


        $('.remove_add_new_product_project').click(function() {
            $(this).parent().parent().remove();
        });
    })

    $('.remove_add_new_product_project').click(function() {
        $(this).parent().parent().remove();
    });
</script>
