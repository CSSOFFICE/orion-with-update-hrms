<!--CRUMBS CONTAINER (LEFT)-->
<div class="col-md-12 {{ runtimeCrumbsColumnSize($page['crumbs_col_size'] ?? '') }} align-self-center {{ $page['crumbs_special_class'] ?? '' }}" id="breadcrumbs">
    <h3 class="text-themecolor">{{ $page['heading'] }}</h3>
    <!--crumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">{{ cleanLang(__('lang.app')) }}</li>
        @if(isset($page['crumbs']))
        @foreach ($page['crumbs'] as $title)
        <li class="breadcrumb-item @if ($loop->last) active active-bread-crumb @endif">{{ $title ?? '' }}</li>
        @endforeach
        @endif
    </ol>
    <!--crumbs-->
    @if($title=="Customer")

    <div class="d-flex ">
        <form id="header-search_by_month">
            <div class="form-check">
                <input class="form-check-input client_status" type="radio" id="exampleRadio1" class="btn icon-btn btn-light float-right border" data-form-id="header-search_by_month" name="client_status" data-ajax-type="post" data-type="form" data-url="{{ $page['dynamic_search_url'] ?? '' }}" value="1" {{ $cust_type == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="exampleRadio1">
                    Individual
                </label>
            </div>
        </form>
        <form id="header-search_by_month_b">
            <div class="d-flex">
                <div class="form-check">
                    <input class="form-check-input client_status_b" type="radio" id="exampleRadio2" class="btn icon-btn btn-light float-right border" data-form-id="header-search_by_month_b" name="client_status" data-ajax-type="post" data-type="form" data-url="{{ $page['dynamic_search_url'] ?? '' }}" value="2" {{ $cust_type == 2 ? 'checked' : '' }}>
                    <label class="form-check-label" for="exampleRadio2">
                        Company
                    </label>
                </div>


            </div>

        </form>
    </div>

    @endif
</div>

<!--include various checkbox actions-->

@if(isset($page['page']) && $page['page'] == 'files')
@include('pages.files.components.actions.checkbox-actions')
@endif

@if(isset($page['page']) && $page['page'] == 'notes')
@include('pages.notes.components.actions.checkbox-actions')
@endif
