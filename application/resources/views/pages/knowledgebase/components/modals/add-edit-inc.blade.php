<!--title-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.title')) }}*</label>
    <div class="col-12">
        <input type="text" class="form-control form-control-sm" id="knowledgebase_title" name="knowledgebase_title"
            value="{{ $knowledgebase->knowledgebase_title ?? '' }}">
    </div>
</div>

<!--description-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.description')) }}*</label>
    <div class="col-12">
        <textarea class="form-control form-control-sm tinymce-textarea" rows="5" name="knowledgebase_text"
            id="knowledgebase_text">{{ $knowledgebase->knowledgebase_text ?? '' }}</textarea>
    </div>
</div>

<!--category-->
<div class="form-group row">
    <label for="example-month-input"
        class="col-12 control-label  col-form-label text-left required">{{ cleanLang(__('lang.category')) }}*</label>
    <div class="col-12">
        <select class="select2-basic form-control form-control-sm" id="knowledgebase_categoryid"
            name="knowledgebase_categoryid">
            @foreach($categories as $category)
            <option value="{{ $category->kbcategory_id }}"
                {{ runtimePreselected(@request('knowledgebase_categoryid'), $category->kbcategory_id) }}>{{
                        runtimeLang($category->kbcategory_title) }}</option>
            @endforeach
        </select>
    </div>
</div>

<!--notes-->
<div class="row">
    <div class="col-12">
        <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
    </div>
</div>