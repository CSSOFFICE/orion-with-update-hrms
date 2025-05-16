<div class="card-title" id="{{ runtimePermissions('lead-edit-title', $lead->permission_edit_lead) }}">
    {{ $lead->lead_title }}
</div>


<!--buttons: edit-->
@if($lead->permission_edit_lead)
<div id="card-title-edit" class="card-title-edit hidden">
    <input type="text" class="form-control form-control-sm card-title-input" id="lead_title" name="lead_title">
    <!--button: subit & cancel-->
    <div id="card-title-submit" class="p-t-10 text-right">
        <button type="button" class="btn waves-effect waves-light btn-xs btn-default"
            id="card-title-button-cancel">{{ cleanLang(__('lang.cancel')) }}</button>
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
            data-url="{{ url('/leads/'.$lead->lead_id.'/update-title') }}" data-progress-bar='hidden' data-type="form"
            data-form-id="card-title-edit" data-ajax-type="post" id="card-title-button-save">{{ cleanLang(__('lang.save')) }}</button>
    </div>
</div>
@endif
