<table>
    <tr>
        <td><strong>ORION INTEGRATED SERVICES PTE LTD</strong></td>
    </tr>
    <tr>

    </tr>
    <tr>
        <td colspan="3" style="text-align:left!important; background-color:yellow;height:50px;vertical-align:top;">
            <strong><?php echo e(strtoupper($project_name)??'No Project Attached'); ?></strong>
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th></th>
            <th>DESCRIPTION</th>
            <th style="width:100px">AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $letterIndex = 0; // Start with 0 (for 'A')

        ?>
        <?php $__currentLoopData = $summary_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            


            <?php if($item->description == "NETT MAIN CONTRACTOR'S PRICE"): ?>
                <tr>
                    <td style="text-align:right;"><b><?php echo e($item->letter); ?></b></td>
                    <td style="width:350px"><b><?php echo e($item->description); ?></b></td>
                    <td><b>$ <?php echo e(number_format($item->amount, 2)); ?></b></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php elseif($item->description == 'Others'): ?>
                <tr>
                    <td style="text-align:right;"><?php echo e($item->letter); ?></td>
                    <td style="width:350px"><?php echo e($item->description); ?></td>
                    <td>$ <?php echo e(number_format($item->amount, 2)); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php elseif($item->description == "TOTAL TENDER / QUOTATION AMOUNT"): ?>
            <tr>
                <td style="text-align:right;"><b><?php echo e($item->letter); ?></b></td>
                <td style="width:350px"><b><?php echo e($item->description); ?></b></td>
                <td><b>$ <?php echo e(number_format($item->amount, 2)); ?></b></td>
            </tr>
            <?php elseif($item->description == " "): ?>
            <tr>
                <td style="text-align:right;"><b><?php echo e($item->letter); ?></b></td>
                <td style="width:350px"><b><?php echo e($item->description); ?></b></td>
                <td style="border:1px solid black;"><b>$ <?php echo e(number_format($item->amount, 2)); ?></b></td>
            </tr>
            <?php else: ?>
                <tr>
                    <td style="text-align:right;"><?php echo e($item->letter); ?></td>
                    <td style="width:350px"><?php echo e($item->description); ?></td>
                    <td>$ <?php echo e(number_format($item->amount, 2)); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/bill/components/export/summary.blade.php ENDPATH**/ ?>