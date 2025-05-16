@extends('layout.wrapper') @section('content')
    <style>
        .selected-option {
            background-color: #6c757d !important;
            color: white !important;
        }
    </style>
    <!-- jQuery (Latest version) -->

    <!-- main content -->
    <div class="container-fluid {{ $page['mode'] ?? '' }}" id="invoice-container">

        <!--HEADER SECTION-->

        <div class="row page-titles">

            <!--BREAD CRUMBS & TITLE-->
            <div class="col-md-12 col-lg-7 align-self-center {{ $page['crumbs_special_class'] ?? '' }}" id="breadcrumbs">
                <!--attached to project-->
                <a id="InvoiceTitleAttached"
                    class="{{ runtimeInvoiceAttachedProject('project-title', $bill->bill_projectid) }}"
                    href="{{ url('projects/' . $bill->bill_projectid) }}">
                    <h3 class="text-themecolor" id="InvoiceTitleProject">{{ $page['heading'] ?? '' }}</h3>
                </a>
                <!--not attached to project-->
                <h4 id="InvoiceTitleNotAttached"
                    class="muted {{ runtimeInvoiceAttachedProject('alternative-title', $bill->bill_projectid) }}">
                    {{ cleanLang(__('lang.not_attached_to_project')) }}
                </h4>
                <!--crumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">{{ cleanLang(__('lang.app')) }}</li>
                    @if (isset($page['crumbs']))
                        @foreach ($page['crumbs'] as $title)
                            <li class="breadcrumb-item @if ($loop->last) active active-bread-crumb @endif">
                                {{ $title ?? '' }}
                            </li>
                        @endforeach
                    @endif
                </ol>
                <!--crumbs-->
            </div>

            <!--ACTIONS-->
            @if ($bill->bill_type == 'invoice')
                @include('pages.variation_bill.components.misc.invoice.actions')
            @endif
            @if ($bill->bill_type == 'estimate')
                @include('pages.variation_bill.components.misc.estimate.actions')
            @endif

        </div>
        <!--/#HEADER SECTION-->
        <!-- BILL CONTENT -->
        @php
            $options = explode(',', $bill->quotation_options);
        @endphp

        {{-- If "summary" is not in the options, add it manually --}}
        <!-- Input Field -->

        <div style="margin: 0 auto;">
            @if (!in_array('summary', $options))
                <button class="btn btn-primary m-r-10 option-button selected-option" data-template="summary">
                    {{ strtoupper('summary') }}
                </button>
            @endif

            {{-- Loop through all options --}}
            @foreach ($options as $k => $option)
                @if (!empty($option))
                    @if ($option === 'summary')
                        <button class="btn btn-primary m-r-10 option-button selected-option"
                            data-template="{{ $option }}">
                            {{ strtoupper($option) }}
                        </button>
                    @else
                        <button class="btn btn-primary  m-r-10 option-button" data-template="{{ $option }}"
                            data-id="{{ $k + 1 }}">
                            @if ($option == 'preliminaries')
                                {{ strtoupper('preliminaries') }}
                            @elseif ($option == 'insurance')
                                {{ strtoupper('insurance') }}
                            @elseif ($option == 'schedule_of_works')
                                {{ strtoupper('schedule of works') }}
                            @elseif($option == 'plumbing_sanity')
                                {{ strtoupper('plumbing & sanity') }}
                            @elseif($option == 'elec_acme')
                                {{ strtoupper('elec & acme') }}
                            @elseif($option == 'external_works')
                                {{ strtoupper('external works') }}
                            @elseif($option == 'pc_ps_sums')
                                {{ strtoupper('pc & ps sums') }}
                            @elseif($option == 'others')
                                {{ strtoupper('others') }}
                            @else
                                {{ strtoupper($option) }}
                            @endif
                        </button>
                    @endif
                @endif
            @endforeach
        </div>




        <!--BILL CONTENT-->
        <div class="row">
            <div class="col-md-12 p-t-30">
                @include('pages.variation_bill.bill-web')
            </div>
        </div>
    </div>
    <!--main content -->

@endsection
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.option-button');

        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Remove the 'selected-option' class from all buttons
                buttons.forEach(function(btn) {
                    btn.classList.remove('selected-option');
                });
                // Add the 'selected-option' class to the clicked button
                this.classList.add('selected-option');
            });
        });

        // document.querySelector('#download-button').addEventListener('click', function() {
        //     const estimateId = '{{ $bill->bill_estimateid }}'; // Pass estimate ID dynamically

        //     // Fetch data from the backend API
        //     fetch(`{{ url('/') }}/estimates/export-quotation/${estimateId}`)
        //         .then(response => response.json())
        //         .then(results => {
        //             const organizedData = {};
        //             const categorySums = {};
        //             const summaryData = [
        //                 ['S/N', 'DESCRIPTION', 'AMOUNT']
        //             ];

        //             // Process data
        //             results.forEach(result => {
        //                 if (!result.template_id) return;

        //                 // Clean up unwanted characters in the description
        //                 const cleanedDescription = result.description.replace(/_x000d_/g, '').trim();

        //                 // Initialize category sums and organize data by category
        //                 categorySums[result.milestonecategory_title] = (categorySums[result.milestonecategory_title] || 0) + result.amount;
        //                 if (!organizedData[result.milestonecategory_title]) {
        //                     organizedData[result.milestonecategory_title] = [
        //                         ['S/N', 'Description', 'Unit', 'Quantity', 'Total', 'Amount']
        //                     ];
        //                 }

        //                 organizedData[result.milestonecategory_title].push([
        //                     organizedData[result.milestonecategory_title].length, // S/N column
        //                     cleanedDescription, // Use the cleaned description
        //                     result.unit,
        //                     (Number(result.qty) || 0).toFixed(2), // Ensure qty is a valid number, default to 0
        //                     (Number(result.total) || 0).toFixed(2), // Remove $ sign from total
        //                     (Number(result.amount) || 0).toFixed(2) // Remove $ sign from amount
        //                 ]);

        //             });

        //             // Prepare summary data with serial numbers and calculations
        //             let serialNo = 'A';
        //             for (const category in categorySums) {
        //                 const categorySum = Number(categorySums[category]) || 0; // Ensure it's a number
        //                 summaryData.push([serialNo, category, categorySum.toFixed(2)]); // Remove $ sign
        //                 serialNo = String.fromCharCode(serialNo.charCodeAt(0) + 1);
        //             }

        //             // Safely calculate totalSum
        //             const totalSum = Object.values(categorySums)
        //                 .reduce((a, b) => (Number(a) || 0) + (Number(b) || 0), 0);
        //             summaryData.push(['', '', totalSum.toFixed(2)]); // Remove $ sign

        //             // Calculate Profit & Attendance Allowance
        //             const profitAllowance = totalSum * 0.05;
        //             summaryData.push(['', 'Profit & Attendance Allowance (%)', profitAllowance.toFixed(2)]); // Remove $ sign

        //             // Calculate NETT MAIN CONTRACTOR PRICE
        //             const nettPrice = totalSum + profitAllowance;
        //             summaryData.push(['', 'NETT MAIN CONTRACTOR PRICE', nettPrice.toFixed(2)]); // Remove $ sign
        //             summaryData[summaryData.length - 1].bold = true; // Add bold style

        //             // Add Contingency Sums
        //             const contingencySums = Number(50); // Ensure this is a valid number
        //             summaryData.push(['', 'Contingency Sums', contingencySums.toFixed(2)]); // Remove $ sign

        //             // Calculate TOTAL TENDER / QUOTATION AMOUNT
        //             const totalTenderAmount = nettPrice + contingencySums;
        //             summaryData.push(['', 'TOTAL TENDER / QUOTATION AMOUNT', totalTenderAmount.toFixed(2)]); // Remove $ sign
        //             summaryData[summaryData.length - 1].bold = true; // Add bold style
        //             summaryData[summaryData.length - 1].border = {
        //                 top: { style: 'thick' },
        //                 bottom: { style: 'thick' }
        //             };

        //             // Generate Excel workbook
        //             const workbook = XLSX.utils.book_new();
        //             const summaryWorksheet = XLSX.utils.aoa_to_sheet(summaryData);

        //             // Apply styles to specific rows and columns
        //             summaryWorksheet['!cols'] = [
        //                 { wpx: 50 }, // S/N
        //                 { wpx: 350 }, // Description
        //                 { wpx: 100 } // Amount
        //             ];

        //             // Add bold styling to specific rows
        //             summaryData.forEach((row, rowIndex) => {
        //                 const amountCellRef = XLSX.utils.encode_cell({ r: rowIndex, c: 2 });
        //                 if (summaryWorksheet[amountCellRef]) {
        //                     if (row.bold) {
        //                         summaryWorksheet[amountCellRef].s = { font: { bold: true } };
        //                     }
        //                 }

        //                 // Add borders to "TOTAL TENDER / QUOTATION AMOUNT" row
        //                 if (row.border) {
        //                     summaryWorksheet[amountCellRef].s = {
        //                         ...summaryWorksheet[amountCellRef].s,
        //                         border: {
        //                             top: { style: 'thin' },
        //                             bottom: { style: 'thin' }
        //                         }
        //                     };
        //                 }
        //             });

        //             XLSX.utils.book_append_sheet(workbook, summaryWorksheet, 'SUMMARY');

        //             // Add other sheets
        //             for (const [sheetName, data] of Object.entries(organizedData)) {
        //                 if (sheetName === 'SUMMARY') continue; // Skip, already added

        //                 const worksheet = XLSX.utils.aoa_to_sheet(data);

        //                 // Set column widths
        //                 worksheet['!cols'] = data[0].map((_, index) =>
        //                     index === 1 ? { wpx: 350 } : { wpx: 60 }
        //                 );

        //                 XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
        //             }

        //             // Generate and download the file
        //             XLSX.writeFile(workbook, `{{ $bill->quotation_no }}.xlsx`);
        //         })
        //         .catch(error => console.error('Error fetching data:', error));
        // });
    });
</script>
