<div class="row" id="js-projects-modal-add-edit" data-section="{{ $page['section'] }}">
    <div class="col-lg-12">
        <!--meta data - creatd by-->
        @if (isset($page['section']) && $page['section'] == 'edit')
            <div class="modal-meta-data">
                <small><strong>{{ cleanLang(__('lang.created_by')) }}:</strong>
                    {{ $project->f_name ?? runtimeUnkownUser() }} |
                    {{ runtimeDate($project->project_created) }}</small>
            </div>
        @endif

        <!--category<>-->
        @if (isset($page['section']) && $page['section'] == 'edit')
            <div class="form-group row d-none">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Category</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="form-control form-control-sm" id="project_cat" name="project_cat">
                        <option value="">Please Select</option>
                        <option value="Project" {{ $project->project_cat == 'Project' ? 'selected' : '' }}>Project
                        </option>
                        <option value="Maintenance" {{ $project->project_cat == 'Maintenance' ? 'selected' : '' }}>
                            Maintenance</option>
                        <option value="Manpower supply"
                            {{ $project->project_cat == 'Manpower supply' ? 'selected' : '' }}>
                            Manpower supply</option>
                        <option value="Spare parts, Equipment"
                            {{ $project->project_cat == 'Spare parts, Equipment' ? 'selected' : '' }}>Spare parts,
                            Equipment
                        </option>
                        <option value="LSS (Life Safety System)"
                            {{ $project->project_cat == 'LSS (Life Safety System)' ? 'selected' : '' }}>LSS (Life Safety
                            System)</option>
                        <option value="TGCM" {{ $project->project_cat == 'TGCM' ? 'selected' : '' }}>TGCM</option>
                    </select>
                </div>
            </div>
        @endif
        @if (isset($page['section']) && $page['section'] == 'create')
            <div class="form-group row d-none">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Category</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="form-control form-control-sm" id="project_cat" name="project_cat">
                        <option value="">Please Select</option>
                        <option value="Project">Project</option>
                        <option value="Maintenance">
                            Maintenance</option>
                        <option value="Manpower supply">
                            Manpower supply</option>
                        <option value="Spare parts, Equipment">Spare parts, Equipment
                        </option>
                        <option value="LSS (Life Safety System)">LSS (Life Safety
                            System)</option>
                        <option value="TGCM">TGCM</option>
                    </select>
                </div>
            </div>
        @endif
        <!--/#sn no-->
        <!--sn no<>-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Project Code*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="project_code" name="project_code"
                    placeholder="" value="{{ $project->project_code ?? '' }}">
            </div>
        </div>
        @if (auth()->user()->role->role_projects_billing > 0)
            <!--/#sn no-->
            <div class="form-group row ">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Contact Sum</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" id="contact_sub" name="project_sn"
                        placeholder="" value="{{ $project->project_sn ?? '' }}">
                </div>
            </div>
        @endif
        <!--title<>-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.project_title')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="project_title" name="project_title"
                    placeholder="" value="{{ $project->project_title ?? '' }}">
            </div>
        </div>
        <!--/#title-->

        <!--client<>-->
        {{-- @if (config('visibility.project_modal_client_fields')) --}}
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">
                {{ cleanLang(__('lang.client')) }}*
            </label>
            <div class="col-sm-12 col-lg-9">
                <!--select2 basic search-->
                <select name="project_clientid" id="project_clientid"
                    class="form-control form-control-sm js-select2-basic">
                    <option value="" {{ empty($project->project_clientid ?? 0) ? 'selected' : '' }}>Please Select
                    </option>
                    @php
                        $clients = DB::table('clients')->get();
                    @endphp
                    @foreach ($clients as $client)
                        <option value="{{ $client->client_id }}"
                            {{ $project->project_clientid ?? 0 == $client->client_id ? 'selected' : '' }}>
                            {{ $client->f_name ?? $client->client_company_name }}
                        </option>
                    @endforeach
                </select>
                <!--select2 basic search-->
            </div>
        </div>

        {{-- @endif --}}
        @if (isset($page['section']) && $page['section'] == 'edit')

            <div id="multiple_addre">
                @foreach ($project_address as $key => $add)
                    <div class="chiled_multi_field border-4" id="chiled_multi_field_{{ $key }}"
                        style="border: 2px solid #d5b9b9; padding: 8px;">
                        <div class="form-group row ">
                            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project
                                Site</label>
                            <div class="col-sm-12 col-lg-9">
                                <input type="text" name="project_site[{{ $key }}]"
                                    class="form-control form-control-sm project_site" placeholder="project site..."
                                    value="{{ $add->project_site ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Postal code</label>
                            <div class="col-sm-12 col-lg-9">
                                <input type="number" data-id="0"
                                    class="form-control form-control-sm employee_postal" id="employee_postal_0"
                                    name="employee_postal[{{ $key }}]" placeholder=""
                                    value="{{ $add->employee_postal ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project
                                Address</label>
                            <div class="col-sm-12 col-lg-9">
                                <input type="text" class="form-control form-control-sm" id="project_address_0"
                                    name="project_address[{{ $key }}]" placeholder=""
                                    value="{{ $add->project_address ?? '' }}">
                                <input type="hidden" name="longitude[{{ $key }}]"
                                    id="longitude_{{ $key }}" class="form-control form-control-sm"
                                    value="{{ $add->longitude ?? '' }}">
                                <input type="hidden" name="latitude[{{ $key }}]"
                                    id="latitude_{{ $key }}" class="form-control form-control-sm"
                                    value="{{ $add->latitude ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.assigned')) }}</label>
                            <div class="col-sm-12 col-lg-9">
                                <select name="assigned[{{ $key }}]" id="assigned_{{ $key }}"
                                    data-id="assigned_{{ $key }}"
                                    class="assigned form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="ALL">Select All</option>
                                    @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $user)
<option value="{{ $user->user_id }}"
                                {{ check_user($user->user_id, $add->project_id, $add->project_site) }}>
                                {{ $user->first_name }} {{ $user->last_name }} {{ $user->employee_id }}
                            </option>
@endforeach
                            <!--/#employee list-->
                        </select>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Supervisor</label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="supervisor[{{ $key }}]" id="Supervisor" class="form-control form-control-sm select2-basic">
                            @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $s)
@if ($page['section'] == 'edit')
<option value="{{ $s->user_id }}"
                                {{ $add->supervisor == $s->user_id ? 'selected' : '' }}>{{ $s->first_name }}
                                {{ $s->last_name }}
                            </option>
@else
<option value="{{ $s->user_id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
@endif
@endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Engineer</label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="engineer[{{ $key }}]" id="Engineer" class="form-control form-control-sm select2-basic">
                            @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $e)
@if ($page['section'] == 'edit')
<option value="{{ $e->user_id }}"
                                {{ $add->engineer == $e->user_id ? 'selected' : '' }}>{{ $e->first_name }}
                                {{ $e->last_name }}
                            </option>
@else
<option value="{{ $e->user_id }}">{{ $e->first_name }} {{ $e->last_name }}</option>
@endif
@endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row ">
                    <div class="col-sm-12 col-lg-12 col-12 text-end" style="justify-content: normal;text-align: end;">
                        <button class=" bg-danger remove-row" id="remove-row_{{ $key }}" data-id="{{ $key }}">Remove</button>

                    </div>
                </div>
            </div>
@endforeach
        </div>
@else
<!--/#client-->
        <div id="multiple_addre">
            <div class="chiled_multi_field " id="chiled_multi_field_0" style="border: 2px solid #d5b9b9; padding: 8px;">

                <div class="form-group row ">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Site</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" name="project_site[0]"
                            class="form-control form-control-sm project_site" placeholder="project site...">
                    </div>
                </div>
                <div class="form-group row ">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Postal code</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="number" data-id="0" class="form-control form-control-sm employee_postal" id="employee_postal_0" name="employee_postal[0]"
                            placeholder="" value="{{ $project->employee_postal ?? '' }}">
                    </div>
                </div>
                <div class="form-group row ">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Address</label>
                    <div class="col-sm-12 col-lg-9">
                        <input type="text" class="form-control form-control-sm" id="project_address_0"
                            name="project_address[0]" placeholder="" value="{{ $project->project_address ?? '' }}">
                        <input type="hidden" name="longitude[0]" id="longitude_0" class="form-control form-control-sm">
                        <input type="hidden" name="latitude[0]" id="latitude_0" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.assigned')) }}</label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="assigned[0]" id="assigned_0" data-id="assigned_0"
                            class="assigned form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible" multiple="multiple"
                            tabindex="-1" aria-hidden="true">
                            <option value="ALL">Select All</option>

                            @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $user)
<option value="{{ $user->user_id }}"
                                {{ runtimePreselectedInArray($user->user_id ?? '', $assigned ?? []) }}>
                                {{ $user->first_name }} {{ $user->last_name }} {{ $user->employee_id }}
                            </option>
@endforeach
                            <!--/#employee list-->
                        </select>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Supervisor</label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="supervisor[0]" id="Supervisor" class="form-control form-control-sm select2-basic">
                            @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $s)
@if ($page['section'] == 'edit')
<option value="{{ $s->user_id }}"
                                {{ $project->Supervisor == $s->user_id ? 'selected' : '' }}>{{ $s->first_name }}
                                {{ $s->last_name }}
                            </option>
@else
<option value="{{ $s->user_id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
@endif
@endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Engineer</label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="engineer[0]" id="Engineer" class="form-control form-control-sm select2-basic">
                            @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $e)
@if ($page['section'] == 'edit')
<option value="{{ $e->user_id }}"
                                {{ $project->Engineer == $e->user_id ? 'selected' : '' }}>{{ $e->first_name }}
                                {{ $e->last_name }}
                            </option>
@else
<option value="{{ $e->user_id }}">{{ $e->first_name }} {{ $e->last_name }}</option>
@endif
@endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        @endif
        <button type="button" id="addRow" class="btn btn-sm btn-primary mt-2">Add Site</button>


        <!--dates<>-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.start_date')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" name="project_date_start"
                    autocomplete="off" value="{{ runtimeDatepickerDate($project->project_date_start ?? '') }}">
                <input class="mysql-date" type="hidden" name="project_date_start" id="project_date_start"
                    value="{{ $project->project_date_start ?? '' }}">
            </div>
        </div>


        <div class="form-group row d-none">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.extension_of_time_period')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="number" class="form-control form-control-sm" name="extension_of_time_period"
                    autocomplete="off" value="{{ $project->extension_of_time_period ?? '' }}">
            </div>
        </div>

        <!--dates<>-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.deadline')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate project_date_due"
                    name="project_date_due" autocomplete="off"
                    value="{{ runtimeDatepickerDate($project->project_date_due ?? '') }}">
                <input class="mysql-date" type="hidden" name="project_date_due" id="project_date_due"
                    value="{{ $project->project_date_due ?? '' }}">
            </div>
        </div>

        <div class="form-group row d-none">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.defects_liability_period')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate defects_liability_period"
                    name="defects_liability_period" autocomplete="off"
                    value="{{ $project->defects_liability_period ?? '' }}">
            </div>
        </div>

        <!--assigned team members<>-->
        <!--/#assigned team members-->

        <!--project manager<>-->
        @if (config('visibility.project_modal_assign_fields'))
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.manager')) }}
                <a class="align-middle font-16 toggle-collapse" href="#project_manager_info" role="button"><i
                        class="ti-info-alt text-themecontrast"></i></a></label>
            <div class="col-sm-12 col-lg-9">
                <select name="manager" id="manager" class="form-control form-control-sm select2-basic">
                    <!--array of assigned-->
                    @if (isset($page['section']) && $page['section'] == 'edit' && isset($project->managers))
                    @foreach ($project->managers as $user)
@php $manager[] = $user->id; @endphp
@endforeach
                    @endif
                    <!--/#array of assigned-->
                    <!--users list-->
                    @foreach (config('system.team_members') as $user)
                                        <option></option>
                                        <option value="{{ $user->id }}"
                                            {{ runtimePreselectedInArray($user->id ?? '', $manager ?? []) }}>
                                            {{ $user->full_name }}
                                        </option>
                                    @endforeach
                                    <!--/#users list-->
                                </select>
                            </div>
                        </div>
                @endif



                <!--/#project manager-->
                <div class="collapse" id="project_manager_info">
                    <div class="alert alert-info">{{ cleanLang(__('lang.project_manager_info')) }}</div>
                </div>

                <!--[billing details]-->
                <!-- @if (auth()->user()->role->role_projects_billing == 2)
-->
                <!-- <div class="highlighted-panel"> -->
                <!--billing type-->
                <!-- <div class="form-group row">
                <label for="example-month-input"
                    class="col-sm-12 col-lg-4 col-form-label text-left">{{ cleanLang(__('lang.billing')) }}</label>
                <div class="col-sm-12 col-lg-4">
                    <input type="number" class="form-control form-control-sm" id="project_billing_rate"
                        name="project_billing_rate" placeholder="" value="{{ $project['project_billing_rate'] ?? '' }}">

                </div>
                <div class="col-sm-12 col-lg-4">
                    <select class="select2-basic form-control form-control-sm" id="project_billing_type"
                        data-allow-clear="false" name="project_billing_type">
                        <option value="hourly" {{ runtimePreselected('hourly', $project['project_billing_type'] ?? '') }}>
                            {{ cleanLang(__('lang.hourly')) }}</option>
                        <option value="fixed" {{ runtimePreselected('fixed', $project['project_billing_type'] ?? '') }}>
                            {{ cleanLang(__('lang.fixed_fee')) }}</option>
                    </select>
                </div>
            </div> -->
                <!--estimated hours-->
                <!-- <div class="form-group row">
                <label class="col-sm-12 col-lg-4 text-left control-label col-form-label">{{ cleanLang(__('lang.estimated_hours')) }}
                    <a class="align-middle font-16 toggle-collapse" href="#project_estimated_hours_info" role="button"
                        ><i class="ti-info-alt text-themecontrast"></i></a></label>
                <div class="col-sm-12 col-lg-4">
                    <input type="number" class="form-control form-control-sm" id="project_billing_estimated_hours"
                        name="project_billing_estimated_hours" placeholder=""
                        value="{{ $project['project_billing_estimated_hours'] ?? '' }}">
                </div>
                <div class="collapse col-sm-12 m-t-15" id="project_estimated_hours_info">
                    <div class="alert alert-info">{{ cleanLang(__('lang.project_estimated_hours_info')) }}</div>
                </div>
            </div> -->
                <!--cost estimate-->
                <!-- <div class="form-group row m-b-0">
                <label class="col-sm-12 col-lg-4 text-left control-label col-form-label">{{ cleanLang(__('lang.costs_estimate')) }} <a
                        class="align-middle font-16 toggle-collapse" href="#project_cost_estimate_info" role="button"
                        ><i class="ti-info-alt text-themecontrast"></i></a></label>
                <div class="col-sm-12 col-lg-4">
                    <input type="number" class="form-control form-control-sm" id="project_billing_costs_estimate"
                        name="project_billing_costs_estimate" placeholder=""
                        value="{{ $project['project_billing_costs_estimate'] ?? '' }}">
                </div>
                <div class="collapse col-sm-12 m-t-15" id="project_cost_estimate_info">
                    <div class="alert alert-info">{{ cleanLang(__('lang.project_cost_estimate_info')) }}</div>
                </div>
            </div>
        </div> -->
                <!--
@endif -->
                <!--/#[billing details]-->


                <!--edit description- toggle<>-->
                <div class="spacer row">
                    <div class="col-sm-8">
                        <span class="title">Project {{ cleanLang(__('lang.description')) }}</span class="title">
                    </div>
                    <div class="col-sm-12 col-lg-4">
                        <div class="switch  text-right">
                            <label>
                                <input type="checkbox" class="js-switch-toggle-hidden-content"
                                    data-target="edit_project_description_toggle">
                                <span class="lever switch-col-light-blue"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="hidden" id="edit_project_description_toggle">
                    <textarea id="project_description" name="project_description" class="tinymce-textarea">{{ $project->project_description ?? '' }}</textarea>
                    <div class="line m-t-30"></div>
                </div>
                <!--edit description- toggle-->


                <!--project options-->
                <div class="spacer row">
                    <div class="col-sm-8">
                        <span class="title">{{ cleanLang(__('lang.additional_settings')) }}</span class="title">
                    </div>
                    <div class="col-sm-4 text-right">
                        <div class="switch">
                            <label>
                                <input type="checkbox" class="js-switch-toggle-hidden-content"
                                    data-target="edit_project_setings">
                                <span class="lever switch-col-light-blue"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="hidden" id="edit_project_setings">



                    <!--spacer-->
                    @if (config('visibility.project_modal_project_permissions'))
                        <div class="spacer row">
                            <div class="col-sm-8">
                                <span class="title">{{ cleanLang(__('lang.assigned_user_permissions')) }}</span>
                            </div>
                            <div class="col-sm-4">
                                <div class="switch  text-right">
                                    <label>
                                        <input type="checkbox" name="show_more_settings_projects"
                                            id="show_more_settings_projects" class="js-switch-toggle-hidden-content"
                                            data-target="edit_project_assigned_permissions">
                                        <span class="lever switch-col-light-blue"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--spacer-->
                        <!--option toggle-->
                        <div class="hidden highlighted-panel" id="edit_project_assigned_permissions">
                            <div class="form-group form-group-checkbox row m-b-0">
                                <label
                                    class="col-5 col-form-label text-left">{{ cleanLang(__('lang.task_collaboration')) }}
                                    <a class="align-middle font-16 toggle-collapse" href="#info_task_collaboration"
                                        role="button"><i class="ti-info-alt text-themecontrast"></i></a> </label>
                                <div class="col-7 text-left p-t-5">
                                    <input type="checkbox" id="assignedperm_tasks_collaborate"
                                        name="assignedperm_tasks_collaborate" class="filled-in chk-col-light-blue"
                                        {{ runtimePrechecked($project['assignedperm_tasks_collaborate'] ?? '') }}>
                                    <label for="assignedperm_tasks_collaborate"></label>
                                </div>
                            </div>
                            <!--info: taskcollaborations-->
                            <div class="collapse" id="info_task_collaboration">
                                <div class="alert alert-info">{{ cleanLang(__('lang.task_collaboration_info')) }}
                                </div>
                            </div>
                        </div>
                        <!--option toggle-->


                        <!--spacer-->
                        <div class="spacer row">
                            <div class="col-sm-8">
                                <span class="title">{{ cleanLang(__('lang.client_project_permissions')) }}</span
                                    class="title">
                            </div>
                            <div class="col-sm-4">
                                <div class="switch text-right">
                                    <label>
                                        <input type="checkbox" name="show_more_settings_projects2"
                                            id="show_more_settings_projects2" class="js-switch-toggle-hidden-content"
                                            data-target="edit_project_permissions_tasks">
                                        <span class="lever switch-col-light-blue"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--spacer-->
                        <!--permissions-->
                        <div id="edit_project_permissions_tasks" class="hidden highlighted-panel">
                            <!--permission - view tasks-->
                            <div class="form-group form-group-checkbox row">
                                <label
                                    class="col-5 col-form-label text-left">{{ cleanLang(__('lang.view_tasks')) }}</label>
                                <div class="col-7 text-left p-t-5">
                                    <input type="checkbox" id="clientperm_tasks_view" name="clientperm_tasks_view"
                                        class="filled-in chk-col-light-blue"
                                        {{ runtimePrechecked($project['clientperm_tasks_view'] ?? '') }}>
                                    <label for="clientperm_tasks_view"></label>
                                </div>
                            </div>
                            <!--permission - task participation-->
                            <div class="form-group form-group-checkbox row">
                                <label
                                    class="col-5 col-form-label text-left required">{{ cleanLang(__('lang.task_participation')) }}**</label>
                                <div class="col-7 text-left p-t-5">
                                    <input type="checkbox" id="clientperm_tasks_collaborate"
                                        name="clientperm_tasks_collaborate" class="filled-in chk-col-light-blue"
                                        {{ runtimePrechecked($project['clientperm_tasks_collaborate'] ?? '') }}>
                                    <label for="clientperm_tasks_collaborate"></label>
                                </div>
                            </div>
                            <!--permission - create tasks-->
                            <div class="form-group form-group-checkbox row">
                                <label
                                    class="col-5 col-form-label text-left required">{{ cleanLang(__('lang.create_tasks')) }}**</label>
                                <div class="col-7 text-left p-t-5">
                                    <input type="checkbox" id="clientperm_tasks_create"
                                        name="clientperm_tasks_create" class="filled-in chk-col-light-blue"
                                        {{ runtimePrechecked($project['clientperm_tasks_create'] ?? '') }}>
                                    <label for="clientperm_tasks_create"></label>
                                </div>
                            </div>
                            <div class="line"></div>
                            <!--permission - view timesheets-->
                            <div class="form-group form-group-checkbox row">
                                <label
                                    class="col-5 col-form-label text-left">{{ cleanLang(__('lang.view_time_sheets')) }}</label>
                                <div class="col-7 text-left p-t-5">
                                    <input type="checkbox" id="clientperm_timesheets_view"
                                        name="clientperm_timesheets_view" class="filled-in chk-col-light-blue"
                                        {{ runtimePrechecked($project['clientperm_timesheets_view'] ?? '') }}>
                                    <label for="clientperm_timesheets_view"></label>
                                </div>
                            </div>
                            <!--permission - view expenses-->
                            <div class="form-group form-group-checkbox row">
                                <label
                                    class="col-5 col-form-label text-left">{{ cleanLang(__('lang.view_expenses')) }}</label>
                                <div class="col-7 text-left p-t-5">
                                    <input type="checkbox" id="clientperm_expenses_view"
                                        name="clientperm_expenses_view" class="filled-in chk-col-light-blue"
                                        {{ runtimePrechecked($project['clientperm_expenses_view'] ?? '') }}>
                                    <label for="clientperm_expenses_view"></label>
                                </div>
                            </div>

                            <div><small class="text-bold">**
                                    {{ cleanLang(__('lang.if_items_selected_then_viewing_perm')) }}</small></div>
                        </div>
                        <!--permissions-->
                    @endif



                    <!--project progress-->
                    <div class="spacer row">
                        <div class="col-sm-8">
                            <span class="title">{{ cleanLang(__('lang.progress')) }}</span class="title">
                        </div>
                        <div class="col-sm-4 text-right">
                            <div class="switch">
                                <label>
                                    <input type="checkbox" class="js-switch-toggle-hidden-content"
                                        data-target="edit_project_progress">
                                    <span class="lever switch-col-light-blue"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!--project progress-->
                    <div class="hidden" id="edit_project_progress">

                        <div class="form-group form-group-checkbox row">
                            <label
                                class="col-4 col-form-label text-left">{{ cleanLang(__('lang.set_progress_manually')) }}?</label>
                            <div class="col-7 text-left p-t-5">
                                <input type="checkbox" id="project_progress_manually"
                                    name="project_progress_manually" class="filled-in chk-col-light-blue"
                                    {{ runtimePrechecked($project['project_progress_manually'] ?? '') }}>
                                <label for="project_progress_manually"></label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 p-l-30">
                                <div id="edit_project_progress_bar"></div>
                            </div>
                            <div class="col-sm-2 text-right">
                                <strong>
                                    <span id="edit_project_progress_display">20</span>%</strong>
                            </div>
                        </div>
                        <input type="hidden" name="project_progress"
                            value="{{ $project->project_progress ?? '' }}" id="project_progress" />
                    </div>


                    <!--project options-->
                    <div class="spacer row">
                        <div class="col-sm-8">
                            <span class="title">{{ cleanLang(__('lang.options')) }}</span class="title">
                        </div>
                        <div class="col-sm-4 text-right">
                            <div class="switch">
                                <label>
                                    <input type="checkbox" class="js-switch-toggle-hidden-content"
                                        data-target="edit_project_options">
                                    <span class="lever switch-col-light-blue"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!--project options-->
                    <div class="hidden" id="edit_project_options">
                        <div class="form-group row">
                            <label for="example-month-input"
                                class="col-sm-12 col-lg-3 col-form-label text-left required">{{ cleanLang(__('lang.category')) }}</label>
                            <div class="col-sm-12 col-lg-9">
                                <select class="select2-basic form-control form-control-sm" id="project_categoryid"
                                    name="project_categoryid">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}"
                                            {{ runtimePreselected($project->project_categoryid ?? '', $category->category_id) }}>
                                            {{ runtimeLang($category->category_name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <!--tags-->
                        <div class="form-group row">
                            <label
                                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
                            <div class="col-sm-12 col-lg-9">
                                <select name="tags" id="tags"
                                    class="form-control form-control-sm select2-multiple {{ runtimeAllowUserTags() }} select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <!--array of selected tags-->
                                    @if (isset($page['section']) && $page['section'] == 'edit')
                                        @foreach ($project->tags as $tag)
                                            @php $selected_tags[] = $tag->tag_title ; @endphp
                                        @endforeach
                                    @endif
                                    <!--/#array of selected tags-->
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->tag_title }}"
                                            {{ runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags ?? []) }}>
                                            {{ $tag->tag_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--/#tags-->
                    </div>





                    <!--pass source-->
                    <input type="hidden" name="source" value="{{ request('source') }}">

                </div>


                <!--redirect to project-->
                @if (config('visibility.project_show_project_option'))
                    <div class="form-group form-group-checkbox row">
                        <div class="col-12 text-left p-t-5">
                            <input type="checkbox" id="show_after_adding" name="show_after_adding"
                                class="filled-in chk-col-light-blue" checked="checked">
                            <label
                                for="show_after_adding">{{ cleanLang(__('lang.show_project_after_its_created')) }}</label>
                        </div>
                    </div>
                @endif
                <!--notes-->
                <div class="row">
                    <div class="col-12">
                        <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
                    </div>
                </div>
            </div>
    </div>


    @if (isset($page['section']) && $page['section'] == 'edit')
        <!--dynamic inline - set dynamic project progress-->
        <script>
            NX.varInitialProjectProgress = "{{ $project['project_progress'] }}";
        </script>
    @endif


    <script>
        $(document).ready(function() {

            $(document).on('change', '.project_date_due', function() {

                var deadline = $(this).val();
                if (deadline !== '') {

                    var deadlineParts = deadline.split('-');
                    var day = parseInt(deadlineParts[0], 10);
                    var month = parseInt(deadlineParts[1], 10);
                    var year = parseInt(deadlineParts[2], 10);

                    var deadlineDate = new Date(year, month - 1, day);

                    deadlineDate.setMonth(deadlineDate.getMonth() + 12);

                    var newDay = deadlineDate.getDate();
                    var newMonth = deadlineDate.getMonth() + 1;
                    var newYear = deadlineDate.getFullYear();
                    var newDate = newDay + '-' + newMonth + '-' + newYear;
                    // console.log(newDate);

                    $('.defects_liability_period').val(newDate);
                }
            });
        });
    </script>

    <script>
        $("#employee_postal").on("change", function(e) {
            e.preventDefault();
            let number = e.target.value;
            let data = async (key) => {
                let d = await fetch("https://www.onemap.gov.sg/api/common/elastic/search?searchVal=" + key +
                    "&returnGeom=Y&getAddrDetails=Y&pageNum=1");
                let g = await d.json();
                console.log(g);

                let res = g.results[0].ADDRESS;
                let LATITUDE = g.results[0].LATITUDE;
                let LONGITUDE = g.results[0].LONGITUDE;

                if (res) {
                    $("#project_address").val(res);
                    $("#latitude").val(LATITUDE);
                    $("#longitude").val(LONGITUDE);

                } else {

                    $("#project_address").val("Address Not Found");
                }


            }

            data(number);

        });
        $(document).ready(function() {

            // Add new row
            $('#addRow').click(function() {

                let rowIndex = $('.chiled_multi_field').length + 1; // start from existing rows
                // console.log($('.chiled_multi_field').length);

                $('#multiple_addre').append(`
    <div class="chiled_multi_field " id="chiled_multi_field_${rowIndex}" style="border: 2px solid #d5b9b9; padding: 8px;">
      <div class="form-group row ">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Site</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" name="project_site[${rowIndex}]" id="project_site_${rowIndex}"
                    class="form-control form-control-sm project_site" placeholder="project site...">
            </div>
        </div>
<div class="form-group row ">
<label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Postal code</label>
<div class="col-sm-12 col-lg-9">
<input type="number" class="form-control form-control-sm employee_postal" data-id="${rowIndex}" id="employee_postal_${rowIndex}" name="employee_postal[${rowIndex}]"
    placeholder="">
</div>
</div>
<div class="form-group row ">
<label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Address</label>
<div class="col-sm-12 col-lg-9">
<input type="text" class="form-control form-control-sm project_address" id="project_address_${rowIndex}"
    name="project_address[${rowIndex}]" placeholder="">
 <input type="hidden" name="latitude[${rowIndex}]" id="latitude_${rowIndex}" class="form-control form-control-sm latitude">
<input type="hidden" name="longitude[${rowIndex}]" id="longitude_${rowIndex}" class="form-control form-control-sm longitude">

</div>
</div>
<div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.assigned')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <select name="assigned[${rowIndex}]" id="assigned_${rowIndex}" data-id="assigned_${rowIndex}" class="assigned form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible" multiple="multiple" tabindex="-1" aria-hidden="true">
                    <option value="ALL">Select All</option>
                    @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $user)
                    <option value="{{ $user->user_id }}"
                        {{ runtimePreselectedInArray($user->user_id ?? '', $assigned ?? []) }}>
                        {{ $user->first_name }} {{ $user->last_name }} {{ $user->employee_id }}
                    </option>
                    @endforeach
                    <!--/#employee list-->
                </select>

            </div>
        </div>
 <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Supervisor</label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="supervisor[${rowIndex}]" id="Supervisor_${rowIndex}" class="form-control form-control-sm select2-basic">
                            @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $s)
                            @if ($page['section'] == 'edit')
                            <option value="{{ $s->user_id }}"
                                {{ $project->Supervisor == $s->user_id ? 'selected' : '' }}>{{ $s->first_name }}
                                {{ $s->last_name }}
                            </option>
                            @else
                            <option value="{{ $s->user_id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Engineer</label>
                    <div class="col-sm-12 col-lg-9">
                        <select name="engineer[${rowIndex}]" id="Engineer_${rowIndex}" class="form-control form-control-sm select2-basic">
                            @foreach (DB::table('xin_employees')->where('user_id', '!=', 1)->get() as $e)
                            @if ($page['section'] == 'edit')
                            <option value="{{ $e->user_id }}"
                                {{ $project->Engineer == $e->user_id ? 'selected' : '' }}>{{ $e->first_name }}
                                {{ $e->last_name }}
                            </option>
                            @else
                            <option value="{{ $e->user_id }}">{{ $e->first_name }} {{ $e->last_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

<div class="form-group row ">
<div class="col-sm-12 col-lg-12 col-12 text-end" style="justify-content: normal;text-align: end;">
<button  class="remove-row bg-danger" id="remove-row_${rowIndex}" data-id="${rowIndex}">Remove</button>
</div>
</div>
</div>
    `);
                rowIndex++;
                // Reinitialize select2 for dynamically added elements
                $('.select2-basic').select2();
            });
        });
        $(document).on('click', '.remove-row', function() {
            let id = $(this).data('id');

            $('#chiled_multi_field_' + id).remove();


        });
        $(document).on('change', 'select.assigned', function() {
            let selectedVal = $(this).val();

            // Check if "ALL" is selected
            if (selectedVal.includes("ALL")) {
                // Select all values except "ALL"
                let allValues = [];
                $(this).find('option').each(function() {
                    let val = $(this).val();
                    if (val !== "ALL") {
                        allValues.push(val);
                    }
                });

                // Set the selected values
                $(this).val(allValues).trigger('change');
            }
        });
    </script>

    <script>
        $("#project_clientid").on("change", function(e) {
            let id = $(this).val();
            $.ajax({
                url: "{{ route('getDefaultAddress') }}",
                type: "get",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(res) {
                    $("#project_address").val(res.street);
                    $("#employee_postal").val(res.zipcode);

                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }

            });
        });
        $('body').on('change', '.employee_postal', function(e) {
            let id = $(this).data('id');


            e.preventDefault();

            let number = e.target.value;
            let data = async (key) => {
                let d = await fetch("https://www.onemap.gov.sg/api/common/elastic/search?searchVal=" + key +
                    "&returnGeom=Y&getAddrDetails=Y&pageNum=1");
                let g = await d.json();
                console.log(g);

                let res = g.results[0].ADDRESS;
                let LATITUDE = g.results[0].LATITUDE;
                let LONGITUDE = g.results[0].LONGITUDE;

                if (res) {
                    $("#project_address_" + id).val(res);
                    $("#latitude_" + id).val(LATITUDE);
                    $("#longitude_" + id).val(LONGITUDE);
                } else {
                    $(this).parents('tr').find(".project_address").val("Address Not Found");
                }


            }

            data(number);

        });
    </script>
