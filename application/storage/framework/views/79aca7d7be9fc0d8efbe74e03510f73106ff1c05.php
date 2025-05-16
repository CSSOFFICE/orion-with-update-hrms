<div class="card count-<?php echo e(@count($invoice)); ?>" id="invoice-table-wrapper">
    <div class="card-body">

        <div><h2>Project Cost Report</h2></div>
        <div class="table-responsive list-table-wrapper">
            

            <div style="text-align:right;margin-bottom:10px;">
            

            
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

            
            
            <!--nothing found-->
            
            <!--nothing found-->
            
        </div>
    </div>
</div>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/reports/expense/components/table.blade.php ENDPATH**/ ?>