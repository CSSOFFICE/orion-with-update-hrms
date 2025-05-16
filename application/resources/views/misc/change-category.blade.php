<div class="form-group row">
    <label for="example-month-input" class="col-12 col-form-label text-left"> {{ cleanLang(__('lang.change_status')) }}</label>
    <div class="col-12">
        <select class="select2-basic form-control form-control-sm" id="category" name="category">
            @foreach (config('settings.quo_statuses') as $key => $value)
                <option value="{{ $key }}">{{ runtimeLang($key) }}</option>
            @endforeach
        </select>
    </div>
</div>
