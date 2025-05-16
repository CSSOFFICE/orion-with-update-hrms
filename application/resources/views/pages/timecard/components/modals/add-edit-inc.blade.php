<div class="row">
    <div class="col-lg-12 p-t-30">
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.invoice_date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control  form-control-sm pickadate" id="add_invoices_date" name="add_invoices_date"
                autocomplete="off" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.due_date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate"  name="add_invoices_due_date"
                autocomplete="off" placeholder="">
                    <input class="mysql-date" type="hidden" name="add_invoices_due_date" id="add_invoices_due_date"value="">
            </div>
        </div>

        <div class="form-group row">
            <label for="example-month-input" class="col-sm-12 col-lg-3 col-form-label text-left">{{ cleanLang(__('lang.client_name')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <!--select2 basic search-->
                <select name="add_invoices_client_id" id="add_invoices_client_id" class="form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                    data-ajax--url="{{ url('/') }}/feed"></select>
                <!--select2 basic search-->
            </div>
        </div>
        <div class="form-group row">
            <label for="example-month-input" class="col-sm-12 col-lg-3 col-form-label text-left">{{ cleanLang(__('lang.client_name')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <!--select2 basic search-->
                <select name="add_invoices_project_id" id="add_invoices_project_id" class="form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                    data-ajax--url="{{ url('/') }}/feed"></select>
                <!--select2 basic search-->
            </div>
        </div>


        <!--spacer-->
        <div class="spacer row">
            <div class="col-sm-12 col-lg-8">
                <span class="title">{{ cleanLang(__('lang.additional_information')) }}</span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" name="show_more_settings_timesheets" id="show_more_settings_timesheets" class="js-switch-toggle-hidden-content"
                            data-target="add_invoice_additional_information">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>
        <!--spacer-->
        <div id="add_invoice_additional_information" class="hidden">
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.tax')) }} {{ cleanLang(__('lang.tax_rate')) }}</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="select2-basic form-control-sm " id="add_invoices_tax" name="add_invoices_tax">
                        <option></option>
                        <option value="10">10%</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.default_units')) }}</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="select2-basic form-control-sm " id="add_invoices_settings_default_unit" name="add_invoices_settings_default_unit">
                        <option></option>
                        <option value="hours">Hours</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>