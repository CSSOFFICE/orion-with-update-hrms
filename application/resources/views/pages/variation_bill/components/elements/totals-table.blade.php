<div class="col-12" id="bill-totals-wrapper">
    <!--total amounts-->
    <div class="pull-right mt-2 text-right">

        <table class="invoice-total-table">

            <!--invoice amount-->
            <tbody id="billing-table-section-subtotal" class="{{ $bill->visibility_subtotal_row }}">
                <tr>
                    <td>{{ cleanLang(__('lang.subtotal')) }}</td>
                    <td id="billing-subtotal-figure">
                        <span>{{ runtimeMoneyFormat($bill->bill_subtotal) }}</span>
                    </td>
                </tr>
            </tbody>

            <!--discounted invoice-->
            <tbody id="billing-table-section-discounts" class="{{ $bill->visibility_discount_row }}">
<input type="hidden" name="abc" id="baba_id" value="">

                <tr id="billing-sums-discount-container">
                    @if($bill->bill_discount_type == 'percentage')
                    <td>{{ cleanLang(__('lang.discount')) }} <span class="x-small"
                            id="dom-billing-discount-type">({{ $bill->bill_discount_percentage }}%)</span>
                    </td>
                    @else
                    <td>{{ cleanLang(__('lang.discount')) }} <span class="x-small" id="dom-billing-discount-type">({{ cleanLang(__('lang.fixed')) }})</span></td>
                    @endif
                    <td id="billing-sums-discount">
                        {{ runtimeMoneyFormat($bill->bill_discount_amount) }}
                    </td>
                </tr>
                <tr id="billing-sums-before-tax-container" class="{{ $bill->visibility_before_tax_row }}">
                    <td>Total <span class="x-small">({{ cleanLang(__('lang.before_tax')) }})</span></td>
                    <td id="billing-sums-before-tax">
                        <span>{{runtimeMoneyFormat($bill->bill_amount_before_tax) }}</span></td>
                </tr>
            </tbody>

           

            <!--invoice total-->
            <tbody id="invoice-table-section-total">
                <tr class="text-themecontrast d-none" id="billing-sums-total-container">
                    <td class="billing-sums-total">{{ cleanLang(__('lang.invoice_total')) }}</td>
                    <td id="billing-sums-total">
                        <span>{{ runtimeMoneyFormat($bill->bill_final_amount) }}</span>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

</div>
