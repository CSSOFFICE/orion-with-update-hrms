<style>
    tbody {
        background-color: blanchedalmond;
    }

    .firtt_back {
        background-color: coral;
    }

    .list-table-wrapper {
        border: 3px solid black;
        border-collapse: collapse;
        /* Ensures outer border collapse for clean look */
        /* overflow: hidden; */
    }

    .list-table-wrapper td,
    .list-table-wrapper th {
        border: 2px solid black;
    }

    #clients-list-table thead tr th {
        border: 2px solid black;

    }

    /* thiii */
    td:nth-child(1) {
        width: 100px;
    }

    td:nth-child(2) {
        width: 200px;
    }

    /* thiii */
</style>
<div class="card count-{{ @count($grn_data) }}" id="clients-table-wrapper">
    <div class="card-body">
        <button id="exportButton" type="button" class="btn-light shadow p-2 m-2">Export</button>
        <div class="table-responsive list-table-wrapper">
            @if (@count($grn_data) > 0)

            <table id="clients-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                data-page-size="10">
                <thead>
                    <tr>
                        <th class="clients_col_id">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_id" href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_id&sortorder=asc') }}">No<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_company">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_first_name" style="width: 150px !important;"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=first_name&sortorder=asc') }}">Description<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_company">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_address"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=address&sortorder=asc') }}">JOB SCOPE<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>


                        <th class="clients_col_invoices">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_sum_invoices"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=sum_invoices&sortorder=asc') }}">Unit<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_tags"><a href="javascript:void(0)">Qty</a></th>
                        <th class="clients_col_category" style="background-color: cornsilk;">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_category" href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=category&sortorder=asc') }}">BUDGET / WORKING AMOUNT(A)<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_status">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">Remeasured Qty<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_status">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">Contract Amount(B)<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_status">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">Purchase Order Amount<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_status">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">Petty Cash<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_status">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">INVOICE AMOUNT EXCL GST (C)<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_status" style="background-color: greenyellow;">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">Itemized Surplus/
                                Deficit
                                (d ) = a+b+c-d<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        <th class="clients_col_status">
                            <a class="js-ajax-ux-request js-list-sorting" id="sort_client_status"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/clients?action=sort&orderby=client_status&sortorder=asc') }}">Awarded Company<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>
                        @if(config('visibility.action_column'))
                        <th class="clients_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                        @endif
                    </tr>
                </thead>
                <tbody id="clients-td-container">
                    <!--ajax content here-->
                    @include('pages.budget.components.table.ajax')
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

{{-- <script>
    $("#exportButton").click(function() {

        let filename = 'Budget_Report.xlsx';
        // Get the HTML table data
        let table = document.getElementById('clients-list-table');
        let wb = XLSX.utils.table_to_book(table, {
            sheet: "Sheet1"
        });
        let ws = wb.Sheets["Sheet1"];
        let colWidths = [];
        let range = XLSX.utils.decode_range(ws["!ref"]); // Get cell range

        for (let col = range.s.c; col <= range.e.c; col++) {
            let maxWidth = 10; // Default minimum width
            for (let row = range.s.r; row <= range.e.r; row++) {
                let cellAddress = {
                    c: col,
                    r: row
                };
                let cellRef = XLSX.utils.encode_cell(cellAddress);
                let cell = ws[cellRef];

                if (cell && cell.v) {
                    let cellText = cell.v.toString();
                    maxWidth = Math.max(maxWidth, cellText.length * 7); // Approximate pixel width
                }
            }
            colWidths.push({
                wpx: maxWidth
            });
        }

        ws["!cols"] = colWidths; // Set calculated widths to the sheet


        // Write the Excel file
        XLSX.writeFile(wb, filename);


    });
</script> --}}

<script>
    $("#exportButton").click(function() {
        let filename = 'Budget_Report.xlsx';

        // Get the HTML table data
        let table = document.getElementById('clients-list-table');
        let wb = XLSX.utils.book_new();
        let data = [];
        
        // Extract header row
        let headers = [];
        $(table).find('thead tr th').each(function(index, th) {
            headers.push($(th).text().trim());
        });

        // Add headers to data
        if (headers.length > 0) {
            data.push(headers);
        }

        // Extract body rows
        $(table).find('tbody tr').each(function(rowIndex, row) {
            let rowData = [];
            $(row).find('td').each(function(colIndex, col) {
                let cellValue = $(col).text().trim(); // Default to text content
                
                // Check if there's an input[type="number"] inside the <td>
                let input = $(col).find('input[type="number"]');
                if (input.length > 0) {
                    cellValue = input.val(); // Get input value if it exists
                }
                
                rowData.push(cellValue);
            });

            data.push(rowData);
        });

        // Create a worksheet
        let ws = XLSX.utils.aoa_to_sheet(data);

        // Set column widths
        let colWidths = data[0].map(header => ({ wpx: Math.max(100, header.length * 10) })); // Adjust width based on header
        ws["!cols"] = colWidths;

        // Set row heights (optional)
        ws["!rows"] = data.map(() => ({ hpx: 25 })); // Adjust row height as needed

        // Append worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

        // Write the Excel file
        XLSX.writeFile(wb, filename);
    });
</script>

