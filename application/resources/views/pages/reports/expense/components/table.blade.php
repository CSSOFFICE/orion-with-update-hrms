<div class="card count-{{ @count($invoice) }}" id="invoice-table-wrapper">
    <div class="card-body">

        <div><h2>Project Cost Report</h2></div>
        <div class="table-responsive list-table-wrapper">
            {{-- @if (@count($invoice) > 0) --}}

            <div style="text-align:right;margin-bottom:10px;">
            {{-- <a href="{{url('reports/exportCSV?project_id=')}}{{$invoice[0]->project_id}}" class="btn btn-xs btn-success" > <span class="ion ion-md-add"></span>
                        Export</a></div> --}}

            {{-- <table id="expenses-list-table" class="table m-t-0 m-b-0 table-hover no-wrap expense-list"
                data-page-size="10">
                <!-- <thead>
                    <tr>
                    <th class="expenses_col_date">Product
                        </th>
                        <th class="expenses_col_date">Cost
                        </th>

                    </tr>
                </thead> -->
                <tbody id="expenses-td-container">
                    <!--ajax content here-->

                    @include('pages.reports.expense.components.ajax')
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
            </table> --}}
            <table  class="table m-t-0 m-b-0 table-hover no-wrap expense-list">
                <tr>
                  <th>S/N</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Amount</th>
                </tr>
                <tr>
                  <td>1</td>
                  <td>10-04-2024</td>
                  <td>Paid</td>
                  <td>S$5,000.00</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>29-04-2024</td>
                  <td>Paid</td>
                  <td>S$12,607.65</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>15-05-2024</td>
                  <td>Pending</td>
                  <td>S$25,215.30</td>
                </tr>
                <tr>
                  <td colspan="3">Total:</td>
                  <td>S$48,822.95</td>
                </tr>
              </table>
              <h3>Additional Expenses</h3>
              <table  class="table m-t-0 m-b-0 table-hover no-wrap expense-list">
                <tr>
                  <th>S/N</th>
                  <th>Date</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Amount</th>
                </tr>
                <tr>
                  <td>1</td>
                  <td>10-04-2024</td>
                  <td>Additional paint that was not included in the quotation</td>
                  <td>Not billed</td>
                  <td>S$165.00</td>
                </tr>
                <tr>
                  <td colspan="4">Total:</td>
                  <td>S$165.00</td>
                </tr>
              </table>

            {{-- @endif --}}
            {{-- @if (@count($invoice) == 0) --}}
            <!--nothing found-->
            {{-- @include('notifications.no-results-found') --}}
            <!--nothing found-->
            {{-- @endif --}}
        </div>
    </div>
</div>
