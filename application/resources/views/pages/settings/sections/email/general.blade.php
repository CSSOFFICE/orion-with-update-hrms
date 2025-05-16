@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<form class="form" id="settingsFormEmailGeneral">

    <!--from email address-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label">{{ cleanLang(__('lang.system_email_address')) }}</label>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" id="settings_email_from_address"
                name="settings_email_from_address" value="{{ $settings->settings_email_from_address ?? '' }}">
        </div>
    </div>

    <!--from name-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label">{{ cleanLang(__('lang.system_from_name')) }}</label>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" id="settings_email_from_name"
                name="settings_email_from_name" value="{{ $settings->settings_email_from_name ?? '' }}">
        </div>
    </div>

    <!--server type-->
    <div class="form-group row">
        <label for="example-month-input" class="col-12 col-form-label text-left">{{ cleanLang(__('lang.order_articles_by')) }}</label>
        <div class="col-12">
            <select class="select2-basic form-control form-control-sm" id="settings_email_server_type"
                name="settings_email_server_type">
                <option value="sendmail" {{ runtimePreselected('sendmail', $settings->settings_email_server_type ?? '') }}>
                    Sendmail</option>
                <option value="smtp" {{ runtimePreselected('smtp', $settings->settings_email_server_type ?? '') }}>
                    SMTP</option>
            </select>
        </div>
    </div>

    <div>
        <!--settings documentation help-->
        <!-- <a href="https://growcrm.io/documentation/email-settings/"  target="_blank" class="btn btn-sm btn-info  help-documentation"><i class="ti-info-alt"></i>
            {{ cleanLang(__('lang.help_documentation')) }}</a> -->
    </div>

    <!--buttons-->
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton"
            class="btn btn-rounded-x btn-danger waves-effect text-left"
            data-url="/settings/email/general" data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_changes')) }}</button>
    </div>
</form>
@endsection