        <!--HEADER-->
        <div>
            <span class="pull-left">
                <h3><b>{{ cleanLang(__('lang.invoice')) }}</b>
                    <!--recurring icon-->
                    @if(auth()->user()->is_team)
                    <i class="sl-icon-refresh text-danger cursor-pointer {{ runtimeVisibility('invoice-recurring-icon', $bill->bill_recurring) }}"
                        data-toggle="tooltip" id="invoice-recurring-icon" title="{{ cleanLang(__('lang.recurring_invoice')) }}"></i>
                    <!--child invoice-->
                    @if($bill->bill_recurring_child == 'yes')
                    <a href="{{ url('invoices/'.$bill->bill_recurring_parent_id) }}">
                        <i class="ti-back-right text-success" data-toggle="tooltip" data-html="true"
                            title="{{ cleanLang(__('lang.invoice_automatically_created_from_recurring')) }} <br>(#{{ runtimeInvoiceIdFormat($bill->bill_recurring_parent_id) }})"></i>
                    </a>
                    @endif
                    @endif
                </h3>
                <span>
                    <h5>#{{ $bill->formatted_bill_invoiceid }}</h5>
                </span>
            </span>
            <!--status-->
            <span class="pull-right">
                <!--draft-->
                <span class="js-invoice-statuses {{ runtimeInvoiceStatus('draft', $bill->bill_status) }}"
                    id="invoice-status-draft">
                    <h1 class="text-uppercase {{ runtimeInvoiceStatusColors('draft', 'text') }} muted">{{ cleanLang(__('lang.draft')) }}</h1>
                </span>
                <!--due-->
                <span class="js-invoice-statuses {{ runtimeInvoiceStatus('due', $bill->bill_status) }}"
                    id="invoice-status-due">
                    <h1 class="text-uppercase {{ runtimeInvoiceStatusColors('due', 'text') }}">{{ cleanLang(__('lang.due')) }}</h1>
                </span>
                <!--overdue-->
                <span class="js-invoice-statuses {{ runtimeInvoiceStatus('overdue', $bill->bill_status) }}"
                    id="invoice-status-overdue">
                    <h1 class="text-uppercase {{ runtimeInvoiceStatusColors('overdue', 'text') }}">{{ cleanLang(__('lang.overdue')) }}</h1>
                </span>
                <!--paid-->
                <span class="js-invoice-statuses {{ runtimeInvoiceStatus('paid', $bill->bill_status) }}"
                    id="invoice-status-paid">
                    <h1 class="text-uppercase {{ runtimeInvoiceStatusColors('paid', 'text') }}">{{ cleanLang(__('lang.paid')) }}</h1>
                </span>
            </span>
        </div>
