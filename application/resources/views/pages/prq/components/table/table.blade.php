<div class="card count-{{ @count($grn_data) }}" id="notes-table-wrapper">
    <div class="card-body">
        <div class="table-responsive">
            @if (@count($grn_data) > 0)
            <table id="note-foo-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        @if(config('visibility.notes_col_checkboxes'))
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-notes" name="listcheckbox-notes"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="notes-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-notes">
                                <label for="listcheckbox-notes"></label>
                            </span>
                        </th>
                        @endif

                        <th class="notes_col_added">ID</th>
                        <th class="notes_col_title">ReDate</th>
                        <th class="notes_col_tags">Create Date</th>
                        <th class="notes_col_date">Status</th>
                        <th class="notes_col_date">Action</th>
                        <!-- <th class="notes_col_date">Button</th> -->
                        <!-- <th class="notes_col_date">Purchase</th>
                        <th class="notes_col_date">Site Address</th> -->

                    </tr>
                </thead>
                <tbody id="notes-td-container">
                    <!--ajax content here-->
                    @include('pages.prq.components.table.ajax')
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
            @endif @if (@count($grn_data) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>
