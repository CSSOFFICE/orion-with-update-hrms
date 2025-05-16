<style type="text/css">
    body {
        margin: 10px auto;
        background: #eee;

    }

    .invoice-brand-img {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .invoice-brand-img li {
        display: inline-block;
    }

    .bg-blue {
        background-color: #00b0f0;
    }

    .quotation-box {
        border: 2px solid #333;
    }

    .quotation-list-details {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .quotation-list-name {
        margin: 0;
        padding: 0;
        list-style-type: none;
        padding-left: 10px;
        height: 150px;
    }

    .quotation-box p {
        margin: 0;
        padding: 0;
    }

    /* thi=s i=s drop down c=s=s */
    .dropdown-list {
        width: 92%;
        /* ya phir specific value jaise 200px */
        max-height: 200px;
        /* Scroll limit ke liye */
        overflow-y: auto;
        /* Scroll agar items jyada hon to */
        border: 1px solid #ccc;
        background-color: #fff;

    }

    .dropdown-item {
        padding: 10px;
        font-weight: 500;
        /* font-size: 50px; */
        /* Space around the item */
        text-align: left;
        /* Text ko left se align karne ke liye */
        cursor: pointer;
        white-space: nowrap;
        /* Taaki text line break na ho */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Overflow hone par "..." dikhaye */
    }

    .dropdown-list {
        position: absolute;
        /* Ya 'relative' agar context ke according ho */
        z-index: 1000;
        /* Ensure dropdown upar dikhaye */
    }

    .dropdown-list,
    .dropdown-item {
        box-sizing: border-box;
    }

    @media (max-width: 600px) {
        .dropdown-list {
            width: 100%;
        }
    }


    /* thi=s i=s drop down c=s=s */
</style>
@php
use Illuminate\Support\Facades\DB;
$pro = DB::table('product')->get();
$quotation_templet = DB::table('quotation_templet')->get();
$quotation_templet1 = DB::table('quotation_templates')->get();
$p_item = DB::table('product_line_item')->where('quotation_id', $bill->bill_estimateid)->get();
$totalAmount = 0; // Initialize total amount
$schw_array = []; // Array to store sums
foreach ($quotation_templet1 as $q) {
if ($q->template_id == 3 && $q->quotation_no == $page['crumbs'][2]) {
if ($q->type == 'row') {
// Accumulate the amount
$totalAmount += $q->amount;
} elseif ($q->type == 'head') {
// Process the accumulated sum for rows
// For example, you might want to echo or store it
$schw_array[] = $totalAmount;

// Reset the totalAmount for the next set of rows
$totalAmount = 0;
}
}
}

@endphp


{{-- <div class="col-12">
    <div class="table-responsive  invoice-table-wrapper {{ config('css.bill_mode') }} clear-both">
<table class="table table-hover invoice-table {{ config('css.bill_mode') }}">
    <thead>
        <div class="row mb-2">
            <div class="col-lg-6">
                <div class="invoice-to mt-2">
                    <h4 style="font-weight:600; color:#000;" class="bg-blue" style="padding: 5px;">Date of
                        Quotation: <span>{{ runtimeDate($bill->bill_date) }}</span>
                    </h4>
                    <div class="quotation-box">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="quotation-border">
                                    <ul class="quotation-list-name bg-blue">
                                        <li style="font-weight:600; color:#000;">Name</li>

                                    </ul>
                                </div>

                            </div>
                            <div class="col-lg-9">
                                <ul class="quotation-list-details">

                                    <select name="" id="addressDefault" style="display: none"
                                        class="form-control">
                                        <option value="">Select Address</option>
                                        @foreach ($d_add as $d)
                                        <option value="<?= $d->id ?>"><?= $d->p_i ?></option>
                                        @endforeach
                                    </select>
                                    <li id="cccc">{{ $bill->pic_name }}
                                        <p>
                                            {{ $bill->client_company_name ?? '' }}


                                        </p>
                                    </li>
                                    <br />
                                    <span id="p_address_d"> {{ $bill->site_address }}</span>{{ ($bill->pic_city)?',':'' }}<span id="p_address_city"> {{ $bill->pic_city }}</span>{{ ($bill->pic_country)?',':'' }}<span id="p_address_country"> {{ $bill->pic_country }}</span> {{ ($bill->pic_zipcode)?',':'' }}<span id="p_address_postalcode"> {{ $bill->pic_zipcode }}</span>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="quotation-border">
                                    <ul class="quotation-list-name bg-blue" style="height: auto;">
                                        <li style="font-weight:600; color:#000;">Email:</li>

                                    </ul>
                                </div>

                            </div>
                            <div class="col-lg-9">
                                <ul class="quotation-list-details">
                                    <li>
                                        <p id="p_email_d">{{ $bill->pic_email }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="quotation-border">
                                    <ul class="quotation-list-name bg-blue" style="height: auto;">
                                        <li style="font-weight:600; color:#000;">Phone:</li>

                                    </ul>
                                </div>
                            </div>
                            <!-- <div class="col-lg-9">
                                                <ul class="quotation-list-details">
                                                    <li>
                                                        <p>{{ $bill->pic_phone }}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                    </div> -->

                            <!-- <div class="row"> -->

                            <div class="col-lg-9">
                                <ul class="quotation-list-details">
                                    <li>
                                        <p id="p_phone_d">{{ $bill->client_phone }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <input type="hidden" name="p_email" id="p_ee" value="{{ $bill->pic_email }}">
            <input type="hidden" name="p_contact" id="p_ce" value="{{ $bill->client_phone }}">
            <input type="hidden" name="p_address" id="p_ae" value="{{ $bill->site_address }}">
            <input type="hidden" name="p_name" id="p_ne" value="{{ $bill->pic_name }}">
            <input type="hidden" name="p_city" id="p_city" value="{{ $bill->pic_city }}">
            <input type="hidden" name="p_zipcode" id="p_zipcode" value="{{ $bill->pic_zipcode }}">
            <input type="hidden" name="p_country" id="p_country" value="{{ $bill->pic_country }}">
            <div class="col-lg-6">
                <div class="invoice-to mt-2">
                    <h4 style="font-weight:600; color:#000;" class="bg-blue quotation-no" style="padding: 5px;">
                        Quotation No: <span id="qt_no">
                            {{ $bill->est_quotation_no }}/R{{ $bill->rev_no }}</span>
                    </h4>
                    <div class="quotation-box">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="quotation-border">
                                    <ul class="quotation-list-name bg-blue">
                                        <li style="font-weight:600; color:#000;">Name</li>

                                    </ul>
                                </div>

                            </div>
                            <div class="col-lg-9">
                                <ul class="quotation-list-details">
                                    <li>
                                        <p>Enjum Venkat</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="quotation-border">
                                    <ul class="quotation-list-name bg-blue" style="height: auto;">
                                        <li style="font-weight:600; color:#000;">Email: </li>

                                    </ul>
                                </div>

                            </div>
                            <div class="col-lg-9">
                                <ul class="quotation-list-details">
                                    <li>
                                        <p>venkat@pico-tech.sg</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="quotation-border">
                                    <ul class="quotation-list-name bg-blue" style="height: auto;">
                                        <li style="font-weight:600; color:#000;">Phone:</li>

                                    </ul>
                                </div>

                            </div>
                            <div class="col-lg-9">
                                <ul class="quotation-list-details">
                                    <li>
                                        <p>+65 92261502</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @if (config('visibility.bill_mode') == 'editing')
        <textarea class="form-control form-control-sm tinymce-textarea" rows="3" name="subb" id="exclutiony">{!! clean($bill->subb??'') !!}</textarea>
        @else
        <h6 class="pt-2">{!! clean($bill->subb) !!}</h6>
        @endif
        <tr class="bg-blue">
            <!--action-->
            @if (config('visibility.bill_mode') == 'editing')
            <th class="text-left x-action bill_col_action"></th>
            @endif
            <!--SR No-->
            <th style="font-weight:600; color:#000;" class="per1  x-quantity bill_col_quantity">S/N</th>
            <!--description-->
            <th style="font-weight:600; color:#000;"
                class="per20  text-center x-description bill_col_description">
                {{ 'Items' }}
            </th>
            <th style="font-weight:600; color:#000;"
                class="per20  text-center x-description bill_col_description">
                {{ cleanLang(__('lang.description')) }}
            </th>
            <!--quantity-->
            <th style="font-weight:600; color:#000;" class="per70 text-center  x-quantity bill_col_quantity">
                {{ cleanLang(__('lang.qty')) }}
            </th>
            <!--unit price-->
            <th style="font-weight:600; color:#000;" class="per5 text-center  x-unit bill_col_unit">
                {{ cleanLang(__('lang.unit')) }}
            </th>
            <!--rate-->
            <th style="font-weight:600; color:#000;" class="per5 text-center  x-rate bill_col_rate">
                {{ cleanLang(__('lang.rate')) }}
            </th>
            <!--tax-->
            <th style="font-weight:600; color:#000;"
                class="per25 text-center text-left x-tax bill_col_tax {{ runtimeVisibility('invoice-column-inline-tax', $bill->bill_tax_type) }}">
                {{ cleanLang(__('lang.tax')) }}
            </th>
            <!--total-->
            <th style="font-weight:600; color:#000;" class="per25 text-center  x-total bill_col_total"
                id="bill_col_total">{{ cleanLang(__('lang.total')) }}</th>
        </tr>
        <tr style="background-color: #fde9d9;" class="d-none">
            <th colspan="6" class="text-left">N2 Main and Normal line - Present Test and Certify Leak</th>

        </tr>
    </thead>
    @if (config('visibility.bill_mode') == 'editing')
    <tbody id="billing-items-container">
        @foreach ($lineitems as $lineitem)
        <!--plain line-->
        @if ($lineitem->lineitem_type == 'plain')
        @include('pages.variation_bill.components.elements.line-plain')
        @endif
        <!--time line-->
        @if ($lineitem->lineitem_type == 'time')
        @include('pages.variation_bill.components.elements.line-time')
        @endif
        @if ($lineitem->lineitem_type == 'product')
        @include('pages.variation_bill.components.elements.line-product')
        @endif
        @endforeach

    </tbody>
    @else
    <tbody id="billing-items-container">
        @include('pages.variation_bill.components.elements.lineitems')

    </tbody>
    @endif
</table>
</div>
</div> --}}
<div class="col-12" id="content-container">
    <form id="templates-form" method="POST" action="{{ url('/variation/' . $bill->vo_id . '/edit-estimate') }}">
        @csrf
        <div id="template-summary" class="template-content">
            @include('pages.variation_bill.components.elements.templates.summary')
        </div>
        <div id="template-preliminaries" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.preliminaries')
        </div>
        <div id="template-insurance" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.insurance')
        </div>
        <div id="template-schedule_of_works" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.schedule_of_works')
        </div>
        <div id="template-plumbing_sanity" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.plumbing_sanity')
        </div>
        <div id="template-elec_acme" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.elec_acme')
        </div>

        <div id="template-external_works" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.external_works')
        </div>
        <div id="template-pc_ps_sums" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.pc_ps_sums')
        </div>
        <div id="template-others" class="template-content" style="display: none;">
            @include('pages.variation_bill.components.elements.templates.others')
        </div>
        <div class="text-right p-t-25">
            @if (config('visibility.bill_mode') == 'editing')
            <button type="submit" class="btn btn-danger btn-sm" id="save-button">Save Changes</button>
            @endif
        </div>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- jQuery UI CSS for styling -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
    /*Yoyo ka Code*/

    document.addEventListener('DOMContentLoaded', function() {
        const contentContainer = document.getElementById('content-container');
        const templates = document.querySelectorAll('.template-content');

        document.querySelectorAll('.option-button').forEach(function(button) {
            button.addEventListener('click', function() {
                const templateName = this.getAttribute('data-template');
                if (templateName == "summary") {
                    document.getElementById('quote_header').style.display = 'block';
                    $('.term_by_rk').css('display', 'block');
                    // document.getElementById('bill-terms1').style.display = 'block';

                } else {
                    document.getElementById('quote_header').style.display = 'none';
                    $('.term_by_rk').css('display', 'none');
                    // document.getElementById('bill-terms').style.display = 'none';
                    // document.getElementById('bill-terms1').style.display = 'none';

                }
                templates.forEach(template => {
                    template.style.display = 'none';
                });
                document.getElementById('template-' + templateName).style.display = 'block';
                // console.log('Selected option:', templateName);
            });
        });
    });

    $('#save-button').on('click', function(event) {
        event.preventDefault();

        var form = $('#templates-form')[0];
        var formData = new FormData(form);
        // $("#q_title").val();
        // console.log(formData);
        formData.append('q_title', $("#q_title").val());
        formData.append('subb', $("#exclutiony_subb").val());
        formData.append('project_id', '{{$bill->bill_projectid}}')

        // Append hidden fields manually
        $('div.template-content input, div.template-content select, div.template-content textarea').each(
            function() {
                // formData.append($(this).attr('name'), $(this).val());
            });

        $.ajax({
            url: form.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (typeof data === 'string') {
                    $('body').html(data);
                } else if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else if (data.error) {
                    console.error('Error:', data.error);
                    alert('Error: ' + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', errorThrown);
                alert('An error occurred. Please try again.');
            }
        });
    });



    /*Humara Code*/
    $('body').on('input', '.humara-class', function() {
        let $sktr = $(this).closest('tr');
        let total = 0.0;
        let amount = 0.0;
        let grandTotal = 0.00;

        // Loop through all '.humara-class' inputs in the current row to calculate total
        $sktr.find('.humara-class').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        let a = parseFloat($sktr.find('.a').val()) || 0;
        let b = parseFloat($sktr.find('.b').val()) || 0;
        let c = parseFloat($sktr.find('.c').val()) || 0;
        let d = parseFloat($sktr.find('.d').val()) || 0;
        let d_data = (d / 100);
        let datae = (d_data * (b + c)).toFixed(2);
        console.log('Total: ', a);
        // console.log('Total: ', a);
        // console.log('Total: ', b);
        // console.log('Total: ', c);
        // console.log('Total: ', d);
        let e = parseFloat($sktr.find('.e').val(datae)) || 0
        let f = parseFloat($sktr.find('.f').val()) || 0;
        $sktr.find('.g').val((a + b) + (c + e + f))
        let g = parseFloat($sktr.find('.g').val()) || 0
        let h = parseFloat($sktr.find('.h').val()) || 0;
        let h_value=(h/100);
        let datai = (h_value * g).toFixed(2);
       
        $sktr.find('.i').val(datai)
        let i = parseFloat($sktr.find('.i').val()) || 0;
        let dataj = i + g;
        let j = parseFloat($sktr.find('.j').val(dataj)) || 0;
        let k = parseFloat($sktr.find('.k').val(2 * dataj)) || 0;
        // console.log('K: ');

        // Calculate amount based on the quantity input and total
        let qty = parseFloat($sktr.find('.qty-input').val()) || 0;
        amount = qty * total;

        // Format the total and amount with two decimal places before setting values
        total = total.toFixed(2);
        amount = amount.toFixed(2);

        // Set the calculated values back to the appropriate input fields
        // $sktr.find('.total-input').val(total);
        $sktr.find('.quotation-amount-input').val(amount);


        // console.log('Amount: ', amount);
    });
    // $('.selected-option')


    var dec_name = <?php echo json_encode($quotation_templet); ?>


    $("body").on('input', '.description-input', function() {
        let data_id = $('.selected-option').data('id');
        let suggestions = dec_name.filter((re) => {
            return re.category == data_id
        });
        var suggestionsk = suggestions.map(item => ({
            description_name: item.description_name,
            quotation_unit: item.quotation_unit
        }));


        let $tr = $(this).closest('tr');
        var query = $(this).val().toLowerCase();

        $tr.find('.dropdown-list').empty().hide();
        if (query) {
            var filteredSuggestions = suggestionsk.filter(function(item) {
                return item.description_name.toLowerCase().includes(query);
            });

            filteredSuggestions.forEach(function(item) {
                $tr.find('.dropdown-list').append('<div class="dropdown-item" data-id="' + item
                    .quotation_unit + '">' + item.description_name + '</div>');
            });

            $tr.find('.dropdown-list').show();
        }
    });
    $(document).on('click', '.dropdown-item', function() {
        var selectedValue = $(this).text();
        var unit = $(this).data('id');
        let $tr = $(this).closest('tr');
        $tr.find('.description-input').val(selectedValue);
        $tr.find('.unit-input').val(unit);
        $tr.find('.dropdown-list').hide();
    });
    $(document).click(function(e) {
        if (!$(e.target).closest('.description-input, .dropdown-list').length) {
            $('.dropdown-list').hide();
        }
    });
</script>
