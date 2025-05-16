@extends('pages.settings.wrapper')
@section('settings-page')
<!--page notification-->
    <div class="row">
        <div class="col-12">
            <div class="page-notification-imaged">
                <img src="{{ url('/') }}/public/images/settings.png" alt="Application Settings" /> 
                <div class="message"><h3>{{ cleanLang(__('lang.setting_welcome_message')) }}</h2></div>
                <div class="sub-message"><h4>{{ cleanLang(__('lang.setting_welcome_message_sub')) }}</h2></div>
            </div>
        </div>
    </div>
@endsection