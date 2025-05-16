
<div class="card count-{{ @count($timesheets) }}" id="timesheets-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            {{-- @if (@count($timesheets) > 0)
                <table id="timesheets-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                    data-page-size="10">
                    <thead>
                        <tr>
                            <th class="timesheets_col_user"><a class="js-ajax-ux-request js-list-sorting" id="sort_user"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/timesheets?action=sort&orderby=user&sortorder=asc') }}">{{ 'SR No' }}<span
                                        class="sorting-icons"></span></a>
                            </th>
                            <th class="timesheets_col_user"><a class="js-ajax-ux-request js-list-sorting" id="sort_user"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/timesheets?action=sort&orderby=user&sortorder=asc') }}">{{ 'FIN NO' }}<span
                                        class="sorting-icons"></span></a>
                            </th>
                            <th class="timesheets_col_user"><a class="js-ajax-ux-request js-list-sorting" id="sort_user"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/timesheets?action=sort&orderby=user&sortorder=asc') }}">{{ 'Employee' }}<span
                                        class="sorting-icons"></span></a>
                            </th>
                            @foreach ($dayy as $key => $d)
                                <th class="timesheets_col_user"><a class="js-ajax-ux-request js-list-sorting"
                                        id="sort_user" href="javascript:void(0)"
                                        data-url="{{ urlResource('/timesheets?action=sort&orderby=user&sortorder=asc') }}">{{ $W[$key] }}<span>
                                        </span><span> </span>{{ $d }}<span class="sorting-icons"></span></a>
                                </th>
                            @endforeach

                            <th class="timesheets_col_user"><a class="js-ajax-ux-request js-list-sorting" id=""
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/timesheets?action=sort&orderby=user&sortorder=asc') }}">{{ 'Total' }}<span
                                        class="sorting-icons"></span></a>
                            </th>
                            <th class="timesheets_col_user"><a class="js-ajax-ux-request js-list-sorting" id="sort_user"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/timesheets?action=sort&orderby=user&sortorder=asc') }}">{{ 'Action' }}<span
                                        class="sorting-icons"></span></a>
                            </th>


                        </tr>
                    </thead>
                    <tbody id="timesheets-td-container">
                        <!--ajax content here-->
                        @include('pages.timecard.components.table.ajax')
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
                @endif @if (@count($timesheets) == 0)
                    <!--nothing found-->
                    @include('notifications.no-results-found')
                    <!--nothing found-->
                @endif --}}
                <table class="table m-t-0 m-b-0 table-hover no-wrap contact-list">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Employee Id</th>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                        </tr>
                    </thead>
                    <tbody class="table m-t-0 m-b-0 table-hover no-wrap contact-list">
                        <tr>
                            <td>Aditya</td>
                            <td>EMP062</td>
                            <td>16-05-2024</td>
                            <td>09:01:35</td>
                            <td>09:01:35</td>
                        </tr>
                        <tr>
                            <td>Sourish</td>
                            <td>EMP054</td>
                            <td>16-05-2024</td>
                            <td>09:00:41</td>
                            <td>09:00:41</td>
                        </tr>
                        <tr>
                            <td>Aaryan</td>
                            <td>EMP042</td>
                            <td>16-05-2024</td>
                            <td>09:00:33</td>
                            <td>09:00:33</td>
                        </tr>
                        <tr>
                            <td>Krish</td>
                            <td>EMP052</td>
                            <td>16-05-2024</td>
                            <td>08:59:35</td>
                            <td>08:59:35</td>
                        </tr>
                        <tr>
                            <td>Aadesh</td>
                            <td>EMP086</td>
                            <td>16-05-2024</td>
                            <td>08:59:01</td>
                            <td>08:59:01</td>
                        </tr>
                        <tr>
                            <td>Amir</td>
                            <td>EMP049</td>
                            <td>16-05-2024</td>
                            <td>08:58:55</td>
                            <td>08:58:55</td>
                        </tr>
                        <tr>
                            <td>Chandan</td>
                            <td>EMP071</td>
                            <td>16-05-2024</td>
                            <td>08:58:32</td>
                            <td>08:58:32</td>
                        </tr>
                        <tr>
                            <td>Sourish</td>
                            <td>EMP054</td>
                            <td>15-05-2024</td>
                            <td>13:12:16</td>
                            <td>13:12:16</td>
                        </tr>
                        <tr>
                            <td>Krish</td>
                            <td>EMP052</td>
                            <td>15-05-2024</td>
                            <td>13:09:18</td>
                            <td>13:09:18</td>
                        </tr>
                        <tr>
                            <td>Aaryan</td>
                            <td>EMP042</td>
                            <td>15-05-2024</td>
                            <td>13:09:08</td>
                            <td>13:09:08</td>
                        </tr>
                        <tr>
                            <td>Aadesh</td>
                            <td>EMP086</td>
                            <td>15-05-2024</td>
                            <td>13:08:34</td>
                            <td>13:08:34</td>
                        </tr>
                    </tbody>
                </table>
        </div>
    </div>
</div>
