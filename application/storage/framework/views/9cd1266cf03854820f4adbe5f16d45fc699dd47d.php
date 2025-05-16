<?php

use Illuminate\Support\Facades\DB;

// Fetch the grouped quotation templates
$quotation_templates = DB::table('quotation_templates')
    ->select('template_id', DB::raw('SUM(total) as total_amount'))
    ->where('estimates_id', request()->segment(2))
    ->groupBy('template_id')
    ->get();
?>

<table class="table table-bordered invoice-table summery-table" id="summery-table">
    <thead>
        <tr>
            <th></th>
            <th>DESCRIPTION</th>
            <th>AMOUNT</th>
        </tr>
    </thead>
    <tbody>

        <?php
            $letterIndex = 0; // Start with 0 (for 'A')
            $sum = 0; // Initialize the sum for template_id 1 to 6
            $sum2 = 0; // Initialize the sum for template_id 1 to 6
        ?>

        <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Generate the letter dynamically from the letter index
                $letter = chr(65 + $letterIndex); // 65 is the ASCII value for 'A'
                $letterIndex++; // Increment the index for the next row

                // Add to sum if template_id is between 1 and 6
                if ($template->template_id >= 1 && $template->template_id <= 6) {
                    $sum += $template->total_amount;
                }
            ?>

            <?php if($template->template_id == 1): ?>
                <tr>
                    <td><?php echo e($letter); ?></td>
                    <td>General Preliminaries</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php elseif($template->template_id == 2): ?>
                <tr>
                    <td><?php echo e($letter); ?></td>
                    <td>Insurances</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php elseif($template->template_id == 3): ?>
                <tr>
                    <td><?php echo e($letter); ?></td>
                    <td>Proposed Building Works</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php elseif($template->template_id == 4): ?>
                <tr>
                    <td><?php echo e($letter); ?></td>
                    <td>Proposed Electrical & ACMV Works</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php elseif($template->template_id == 5): ?>
                <tr>
                    <td><?php echo e($letter); ?></td>
                    <td>Proposed Plumbing & Sanitary Works</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php elseif($template->template_id == 6): ?>
                <tr>
                    <td><?php echo e($letter); ?></td>
                    <td>Proposed External Works</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Spacer Rows -->
        <tr style="height: 2.2em;">
            <td></td>
            <td></td>
            <td style="border-top: 2px solid #000;">$ <?php echo e(number_format($sum, 2)); ?></td>
        </tr>

        <!-- Contractor Profit Section -->
        <tr id="profit_uniq">
            <?php
                $letter = chr(65 + $letterIndex); // Continue with the next letter
                $letterIndex++; // Increment for next section
            ?>
            <td><?php echo e($letter); ?></td>
            <td>Profit & Attendance Allowance (%)</td>
            <td>$ <?php echo e(number_format(0.05 * $sum, 2)); ?></td>
        </tr>

        <!-- Nett Main Contractor's Price Section -->
        <tr class="fw-bold" id="nett_price_uniq">
            <?php
                $letter = chr(65 + $letterIndex); // Continue with the next letter
                $letterIndex++; // Increment for next section
            ?>
            <td><?php echo e($letter); ?></td>
            <td><strong>NETT MAIN CONTRACTOR'S PRICE</strong></td>
            <td style="border-top: 2px solid #000;"><strong>$ <?php echo e(number_format($sum + 0.05 * $sum, 2)); ?></strong></td>
        </tr>

        <!-- Spacer Rows -->
        <tr style="height: 2.2em;">
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <?php $__currentLoopData = $quotation_templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($template->template_id == 7): ?>
                <tr>
                    <?php
                        $letter = chr(65 + $letterIndex); // Continue with the next letter
                        $letterIndex++;
                        // Add to sum if template_id is between 1 and 6

                        $sum2 += $template->total_amount;

                    ?>
                    <td><?php echo e($letter); ?></td>
                    <td>PC & Provisional Sums</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php endif; ?>
            <?php if($template->template_id == 8): ?>
                <tr>
                    <?php
                        $letter = chr(65 + $letterIndex); // Continue with the next letter
                        $letterIndex++;
                        $sum2 += $template->total_amount;

                    ?>
                    <td><?php echo e($letter); ?></td>
                    <td>Others</td>
                    <td>$ <?php echo e(number_format($template->total_amount, 2)); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Final Spacer -->
        <tr style="height: 2.2em;">
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <!-- Total Tender / Quotation Amount -->
        <tr class="fw-bold">
            <td></td>
            <td><strong>TOTAL TENDER / QUOTATION AMOUNT</strong></td>
            <td style="border-top: 2px solid #000; border-bottom: 2px solid #000;"><strong>$
                    <?php echo e(number_format($sum2 + $sum + 0.05 * $sum, 2)); ?></strong></td>
        </tr>
    </tbody>
</table>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/bill/components/elements/templates/summary.blade.php ENDPATH**/ ?>