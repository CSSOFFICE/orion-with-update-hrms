<div class="card count-{{ @count($subtasks) }}" id="subtasks-view-wrapper">

    <div class="card-body">
        <div class="table-responsive list-table-wrapper">

            @if (@count($subtasks) > 0)
            <table id="tasks-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10"
                data-url="{{ url('/') }}/subtasks/timer-poll/" data-type="form" data-ajax-type="post"
                data-form-id="tasks-list-table">
                <thead>
                    <tr>

                    <th class="tasks_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.tasks')) }}</a></th>

                        <th class="tasks_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.task_description')) }}</a></th>
                        <th class="tasks_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.task_detail')) }}</a></th>
                        <th class="tasks_col_action hidden"><a href="javascript:void(0)">{{ cleanLang(__('lang.unit')) }}</a></th>
                        <th class="tasks_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.unit_rate')) }}</a></th>

                        <th class="tasks_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                    </tr>
                </thead>
                <tbody id="tasks-td-container">
                    <!--ajax content here-->
                     @include('pages.subtasks.components.table.ajax')
                    <!--ajax content here-->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20">
                            <!--load more button-->
                             @include('misc.load-more-button')
                            <!--load more button-->
                        </td>
                    </tr>
                </tfoot>
            </table>
            @endif
            @if (@count($subtasks) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>
