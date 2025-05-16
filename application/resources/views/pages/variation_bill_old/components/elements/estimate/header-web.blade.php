        <!--HEADER-->
        {{-- <div class="row">
            <div class="col-lg-3">
            <div class="invoice-logo"><img width="200" src="{{ url('public/logo/logo.png')}}" alt="Invoice logo" class="img-fluid"></div>
        </div>

        <div class="col-lg-3">

            <div class="invoice-from">
                <ul class="list-unstyled text-left">
                    <li>
                        <p class="mb-0">{{ config('system.settings_company_name') }}
                            <br />@if(config('system.settings_company_address_line_1'))
                            {{ config('system.settings_company_address_line_1') }}
                            @endif
                            @if(config('system.settings_company_state'))
                            <br />{{ config('system.settings_company_state') }}
                            @endif
                            @if(config('system.settings_company_city'))
                            <br /> {{ config('system.settings_company_city') }}
                            @endif
                            @if(config('system.settings_company_zipcode'))
                            <br /> {{ config('system.settings_company_zipcode') }}
                            @endif
                            @if(config('system.settings_company_country'))
                            <br /> {{ config('system.settings_company_country') }}
                            @endif
                        </p>
                    </li>

                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <ul class="invoice-brand-img">
                <?php

                use Illuminate\Support\Facades\DB;

                $query = DB::table('xin_quo')->first();

                $logoUrl = url('hrms/uploads/quo/' . $query->logo1);
                $logoUrl2 = url('hrms/uploads/quo/' . $query->logo2);
                $logoUrl3 = url('hrms/uploads/quo/' . $query->logo3);
                $logoUrl4 = url('hrms/uploads/quo/' . $query->logo4);
                ?>
                <li> <img src="<?php echo $logoUrl; ?>" alt="" width="80px"></li>
                <li> <img src="<?php echo $logoUrl2; ?>" alt="" width="80px"></li>
                <li> <img src="<?php echo $logoUrl3; ?>" alt="" width="80px"></li>
                <li> <img src="<?php echo $logoUrl4; ?>" alt="" width="80px"></li>

            </ul>
        </div>
        </div> --}}
        <style>
            /* .card-body {
    padding-left: 25px;
    padding-right: 25px;
    width: 1000px;
    margin: auto;

} */
            /* #content-container{
    overflow-x: scroll;
} */
            .wrap-text {
                white-space: normal;
                /* Allows the text to break into multiple lines */
                word-wrap: break-word;
                /* Break long words to fit in the cell */
            }
        </style>

        <div class="row">
            <div class="col-md-3">
                <img src="{{ url('public/logo/logo.png') }}" alt="Invoice logo" height="100">
            </div>
            <div class="col-md-9">
                <table style=" font-family: Arial, sans-serif;">
                    <tr>
                        <td colspan="2" style="font-weight: bold; font-size: 20px;">
                            ORION INTEGRATED SERVICES PTE LTD<br>
                        </td>

                    </tr>

                    <tr>
                        <td style="vertical-align: top;">
                            1 YISHUN INDUSTRIAL STREET 1<br>
                            #08-15 A'POSH BIZHUB<br>
                            SINGAPORE 768160
                        </td>
                        <td></td>
                        <td style="vertical-align: top;">
                            TEL: 6734 0032&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FAX: 6734 0728<br>
                            E-MAIL: accounts@ois.com.sg<br>
                            GST REG NO.: 200809511K<br>
                            CO REG NO.: 200809511K
                        </td>
                    </tr>

                </table>

            </div>
        </div>

        <div class="d-none">
            <span class="pull-left">
                <h3><b>{{ cleanLang(__('lang.estimate')) }}</b>
                </h3>
                <span>
                    <!-- {{print_r($bill)}} -->
                    <h5>{{ $bill->quotation_no}}</h5>
                </span>
            </span>
            <!--status-->
            <span class="pull-right">
                <!--draft-->
                <span class="js-estimate-statuses {{ runtimeEstimateStatus('draft', $bill->bill_status) }}"
                    id="estimate-status-draft">
                    <h1 class="text-uppercase {{ runtimeEstimateStatusColors('draft', 'text') }} muted">{{ cleanLang(__('lang.draft')) }}</h1>
                </span>
                <!--new-->
                <span class="js-estimate-statuses {{ runtimeEstimateStatus('new', $bill->bill_status) }}"
                    id="estimate-status-new">
                    <h1 class="text-uppercase {{ runtimeEstimateStatusColors('new', 'text') }}">{{ cleanLang(__('lang.new')) }}</h1>
                </span>
                <!--accepted-->
                <span class="js-estimate-statuses {{ runtimeEstimateStatus('accepted', $bill->bill_status) }}"
                    id="estimate-status-accpeted">
                    <h1 class="text-uppercase {{ runtimeEstimateStatusColors('accepted', 'text') }}">{{ cleanLang(__('lang.accepted')) }}</h1>
                </span>
                <!--declined-->
                <span class="js-estimate-statuses {{ runtimeEstimateStatus('declined', $bill->bill_status) }}"
                    id="estimate-status-declined">
                    <h1 class="text-uppercase {{ runtimeEstimateStatusColors('declined', 'text') }}">{{ cleanLang(__('lang.declined')) }}</h1>
                </span>
                <!--revised-->
                <span class="js-estimate-statuses {{ runtimeEstimateStatus('revised', $bill->bill_status) }}"
                    id="estimate-status-revised">
                    <h1 class="text-uppercase {{ runtimeEstimateStatusColors('revised', 'text') }}">{{ cleanLang(__('lang.revised')) }}</h1>
                </span>
                <!--expired-->
                <span class="js-estimate-statuses {{ runtimeEstimateStatus('expired', $bill->bill_status) }}"
                    id="estimate-status-expired">
                    <h1 class="text-uppercase {{ runtimeEstimateStatusColors('expired', 'text') }}">{{ cleanLang(__('lang.expired')) }}</h1>
                </span>
            </span>
        </div>
