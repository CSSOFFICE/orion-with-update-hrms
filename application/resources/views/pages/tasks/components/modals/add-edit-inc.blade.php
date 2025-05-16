<div class="row">
    <div class="col-lg-12">

        @if(config('visibility.task_modal_project_option'))
        <!--project-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.project')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select name="task_projectid" id="task_projectid"
                    class="projects_assigned_toggle form-control form-control-sm js-select2-basic-search-modal select2-hidden-accessible"
                    data-assigned-dropdown="assigned"
                    data-ajax--url="{{ url('/') }}/feed/projects?ref=general"></select>
            </div>
        </div>
        @endif

        <!--title-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.title')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="task_title" name="task_title"
                    placeholder="">
            </div>
        </div>



        @if(config('visibility.task_modal_milestone_option'))
        {{-- @php print_r($milestones);exit;@endphp --}}
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label required">{{ cleanLang(__('lang.milestone')) }}*</label>
            <div class="col-sm-12 col-lg-9">
                <select name="task_milestoneid" id="task_milestoneid"
                    class="select2-basic form-control form-control-sm">
                    @if(isset($milestones))
                    @foreach($milestones as $milestone)
                    <option value="{{ $milestone->milestone_id }}">
                        {{ runtimeLang($milestone->milestone_title, 'task_milestone') }}</option>
                    @endforeach
                    @endif
                    <!--/#users list-->
                </select>
            </div>
        </div>
        @endif


        <!--due date-->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.start_date')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" name="task_date_start"
                    autocomplete="off" placeholder="">
                <input class="mysql-date" type="hidden" name="task_date_start" id="task_date_start">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.end_date')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm pickadate" name="task_date_due"
                    autocomplete="off" placeholder="">
                <input class="mysql-date" type="hidden" name="task_date_due" id="task_date_due">
            </div>
        </div>
        <!--assigned [roles]-->
        @if(auth()->user()->role->role_assign_tasks == 'yes')
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.assign_users')) }} <a
                    class="align-middle font-16 toggle-collapse" href="#assigning_info" role="button"
                    ><i class="ti-info-alt text-themecontrast"></i></a></label>
            <div class="col-sm-12 col-lg-9">
                @if(config('visibility.projects_assigned_users'))
                <select name="assigned" id="assigned"
                    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                    multiple="multiple" tabindex="-1" aria-hidden="true">
                    @foreach(config('project.assigned') as $user)
                    <option value="{{ $user->id}}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
                @else
                <select name="assigned" id="assigned"
                    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                    multiple="multiple" tabindex="-1" aria-hidden="true" disabled>
                </select>
                @endif
            </div>
        </div>

        <!--Sub con-->
        <div class="form-group row">
            <label
                class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.sub_con')) }}</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="sub_con" name="sub_con"
                    placeholder="">
            </div>
        </div>

        <div class="collapse" id="assigning_info">
            <div class="alert alert-info">
                {{ cleanLang(__('lang.assigning_users_to_a_task_info')) }}
            </div>
        </div>
        @endif

        <div class="line"></div>
        <!--spacer-->
        <div class="spacer row">
            <div class="col-sm-12 col-lg-8">
                <span class="title">{{ cleanLang(__('lang.description')) }}</span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" name="show_more_settings_tasks" id="show_more_settings_tasks"
                            class="js-switch-toggle-hidden-content" data-target="task_description_section">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>
        <!--spacer-->

        <!--description-->
        <div class="hidden" id="task_description_section">
            <div class="form-group row">
                <div class="col-sm-12">
                    <textarea id="project_description" name="task_description"
                        class="tinymce-textarea">{{ $task->task_description ?? '' }}</textarea>
                </div>
            </div>
        </div>

        @if(config('visibility.task_modal_additional_options'))
        <!--spacer-->
        <div class="spacer row">
            <div class="col-sm-12 col-lg-8">
                <span class="title">{{ cleanLang(__('lang.options')) }}</span class="title">
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="switch  text-right">
                    <label>
                        <input type="checkbox" name="show_more_settings_tasks2" id="show_more_settings_tasks2"
                            class="js-switch-toggle-hidden-content" data-target="additional_information_section">
                        <span class="lever switch-col-light-blue"></span>
                    </label>
                </div>
            </div>
        </div>
        <!--spacer-->
        <!--option toggle-->
        <div class="hidden" id="additional_information_section">


            <!--task status-->
            @if(request('status') != '' && array_key_exists(request('status'), config('settings.task_statuses')))
            <input type="hidden" name="task_status" value="{{ request('status') }}">
            @else
            <div class="form-group row">
                <label for="example-month-input"
                    class="col-sm-12 col-lg-3 col-form-label text-left  required">{{ cleanLang(__('lang.status')) }}</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="select2-basic form-control form-control-sm" id="task_status" name="task_status">
                        @foreach(config('settings.task_statuses') as $key => $value)
                        <option value="{{ $key }}">{{ runtimeLang($key) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <!--tags-->
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">{{ cleanLang(__('lang.tags')) }}</label>
                <div class="col-sm-12 col-lg-9">
                    <select name="tags" id="tags"
                        class="form-control form-control-sm select2-multiple {{ runtimeAllowUserTags() }} select2-hidden-accessible"
                        multiple="multiple" tabindex="-1" aria-hidden="true">
                        <!--array of selected tags-->
                        @if(isset($page['section']) && $page['section'] == 'edit')
                        @foreach($lead->tags as $tag)
                        @php $selected_tags[] = $tag->tag_title ; @endphp
                        @endforeach
                        @endif
                        <!--/#array of selected tags-->
                        @foreach($tags as $tag)
                        <option value="{{ $tag->tag_title }}"
                            {{ runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags  ?? []) }}>{{ $tag->tag_title }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <!--task priority-->
            <div class="form-group row">
                <label for="example-month-input"
                    class="col-sm-12 col-lg-3 col-form-label text-left">{{ cleanLang(__('lang.priority')) }}</label>
                <div class="col-sm-12 col-lg-9">
                    <select class="select2-basic form-control form-control-sm" id="task_priority" name="task_priority">
                        <option value="normal">{{ cleanLang(__('lang.normal')) }}</option>
                        <option value="high">{{ cleanLang(__('lang.high')) }}</option>
                        <option value="urgent">{{ cleanLang(__('lang.urgent')) }}</option>
                        <option value="low">{{ cleanLang(__('lang.low')) }}</option>
                    </select>
                </div>
            </div>

            <!--[toggled] project options-->
            <div class="toggle_task_type add_task_toggle_container_project">
                <div class="form-group form-group-checkbox row">
                    <div class="col-12 p-t-5">
                        <div class="pull-left min-w-200">
                            <label>{{ cleanLang(__('lang.visible_to_client')) }}</label>
                        </div>
                        <div class="pull-left p-l-10">
                            @if(isset($page['section']) && $page['section'] == 'create')
                            <input type="checkbox" id="task_client_visibility" name="task_client_visibility"
                                {{ runtimeTasksDefaults('task_client_visibility') }}
                                class="filled-in chk-col-light-blue">
                            @endif
                            @if(isset($page['section']) && $page['section'] == 'edit')
                            <input type="checkbox" id="task_client_visibility" name="task_client_visibility"
                                class="filled-in chk-col-light-blue">
                            @endif
                            <label for="task_client_visibility"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-checkbox row">
                    <div class="col-12 p-t-5">
                        <div class="pull-left min-w-200">
                            <label>{{ cleanLang(__('lang.billable')) }}</label>
                        </div>
                        <div class="pull-left p-l-10">
                            @if(isset($page['section']) && $page['section'] == 'create')
                            <input type="checkbox" id="task_billable" name="task_billable"
                                {{ runtimeTasksDefaults('task_billable') }} class="filled-in chk-col-light-blue">
                            @endif
                            @if(isset($page['section']) && $page['section'] == 'edit')
                            <input type="checkbox" id="task_billable" name="task_billable"
                                class="filled-in chk-col-light-blue">
                            @endif
                            <label for="task_billable"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--option toggle-->
        @endif

        <!--redirect to project-->
        @if(config('visibility.task_show_task_option'))
        <div class="line"></div>
        <div class="form-group form-group-checkbox row">
            <div class="col-12 text-left p-t-5">
                <input type="checkbox" id="show_after_adding" name="show_after_adding"
                    class="filled-in chk-col-light-blue" checked="checked">
                <label for="show_after_adding">{{ cleanLang(__('lang.show_task_after_adding')) }}</label>
            </div>
        </div>
        @endif

        <!--pass source-->
        <input type="hidden" name="source" value="{{ request('source') }}">
        <input type="hidden" name="ref" value="{{ request('ref') }}">

        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* {{ cleanLang(__('lang.required')) }}</strong></small></div>
            </div>
        </div>
    </div>
</div>
