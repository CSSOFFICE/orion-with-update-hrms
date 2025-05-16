 <?php $__env->startSection('content'); ?>
    <style>
        .selected-option {
            background-color: #6c757d !important;
            color: white !important;
        }
    </style>
    <!-- jQuery (Latest version) -->

    <!-- main content -->
    <div class="container-fluid <?php echo e($page['mode'] ?? ''); ?>" id="invoice-container">

        <!--HEADER SECTION-->

        <div class="row page-titles">

            <!--BREAD CRUMBS & TITLE-->
            <div class="col-md-12 col-lg-7 align-self-center <?php echo e($page['crumbs_special_class'] ?? ''); ?>" id="breadcrumbs">
                <!--attached to project-->
                <a id="InvoiceTitleAttached"
                    class="<?php echo e(runtimeInvoiceAttachedProject('project-title', $bill->bill_projectid)); ?>"
                    href="<?php echo e(url('projects/' . $bill->bill_projectid)); ?>">
                    <h3 class="text-themecolor" id="InvoiceTitleProject"><?php echo e($page['heading'] ?? ''); ?></h3>
                </a>
                <!--not attached to project-->
                <h4 id="InvoiceTitleNotAttached"
                    class="muted <?php echo e(runtimeInvoiceAttachedProject('alternative-title', $bill->bill_projectid)); ?>">
                    <?php echo e(cleanLang(__('lang.not_attached_to_project'))); ?>

                </h4>
                <!--crumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><?php echo e(cleanLang(__('lang.app'))); ?></li>
                    <?php if(isset($page['crumbs'])): ?>
                        <?php $__currentLoopData = $page['crumbs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="breadcrumb-item <?php if($loop->last): ?> active active-bread-crumb <?php endif; ?>">
                                <?php echo e($title ?? ''); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </ol>
                <!--crumbs-->
            </div>

            <!--ACTIONS-->
            <?php if($bill->bill_type == 'invoice'): ?>
                <?php echo $__env->make('pages.variation_bill.components.misc.invoice.actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
            <?php if($bill->bill_type == 'estimate'): ?>
                <?php echo $__env->make('pages.variation_bill.components.misc.estimate.actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

        </div>
        <!--/#HEADER SECTION-->
        <!-- BILL CONTENT -->
        <?php
            $options = explode(',', $bill->quotation_options);
        ?>

        
        <!-- Input Field -->

        <div style="margin: 0 auto;">
            <?php if(!in_array('summary', $options)): ?>
                <button class="btn btn-primary m-r-10 option-button selected-option" data-template="summary">
                    <?php echo e(strtoupper('summary')); ?>

                </button>
            <?php endif; ?>

            
            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($option)): ?>
                    <?php if($option === 'summary'): ?>
                        <button class="btn btn-primary m-r-10 option-button selected-option"
                            data-template="<?php echo e($option); ?>">
                            <?php echo e(strtoupper($option)); ?>

                        </button>
                    <?php else: ?>
                        <button class="btn btn-primary  m-r-10 option-button" data-template="<?php echo e($option); ?>"
                            data-id="<?php echo e($k + 1); ?>">
                            <?php if($option == 'preliminaries'): ?>
                                <?php echo e(strtoupper('preliminaries')); ?>

                            <?php elseif($option == 'insurance'): ?>
                                <?php echo e(strtoupper('insurance')); ?>

                            <?php elseif($option == 'schedule_of_works'): ?>
                                <?php echo e(strtoupper('schedule of works')); ?>

                            <?php elseif($option == 'plumbing_sanity'): ?>
                                <?php echo e(strtoupper('plumbing & sanity')); ?>

                            <?php elseif($option == 'elec_acme'): ?>
                                <?php echo e(strtoupper('elec & acme')); ?>

                            <?php elseif($option == 'external_works'): ?>
                                <?php echo e(strtoupper('external works')); ?>

                            <?php elseif($option == 'pc_ps_sums'): ?>
                                <?php echo e(strtoupper('pc & ps sums')); ?>

                            <?php elseif($option == 'others'): ?>
                                <?php echo e(strtoupper('others')); ?>

                            <?php else: ?>
                                <?php echo e(strtoupper($option)); ?>

                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>




        <!--BILL CONTENT-->
        <div class="row">
            <div class="col-md-12 p-t-30">
                <?php echo $__env->make('pages.variation_bill.bill-web', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
    <!--main content -->

<?php $__env->stopSection(); ?>


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
        //     const estimateId = '<?php echo e($bill->bill_estimateid); ?>'; // Pass estimate ID dynamically

        //     // Fetch data from the backend API
        //     fetch(`<?php echo e(url('/')); ?>/estimates/export-quotation/${estimateId}`)
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
        //             XLSX.writeFile(workbook, `<?php echo e($bill->quotation_no); ?>.xlsx`);
        //         })
        //         .catch(error => console.error('Error fetching data:', error));
        // });
    });
</script>

<?php echo $__env->make('layout.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/variation_bill/wrapper.blade.php ENDPATH**/ ?>