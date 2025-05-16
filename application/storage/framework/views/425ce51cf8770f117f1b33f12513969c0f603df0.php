<table>
    <tr>
        <td><strong>ORION INTEGRATED SERVICES PTE LTD</strong></td>
    </tr>
    <tr>

    </tr>
    <tr>
        <td colspan="6" style="text-align:left!important; background-color:yellow;height:50px;vertical-align:top;">
            <strong><?php echo e(strtoupper($project_name)); ?></strong>
        </td>
    </tr>
</table>
<table style="border: 1px solid black;!important;">
    <thead style="text-align:center;">
        <tr style="border: 1px solid black;;">
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Item</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Description</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Unit</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Qty</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Rate</b></th>
            <th style="text-align:center;vertical-align:middle;border:5px solid black;height:50px;"><b>Total</b></th>
        </tr>
        <tr>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;"></th>
        </tr>
        <tr>
            <th style="background-color:#FEF2CB"></th>
            <th
                style="text-align:left!important;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
                <b>BILL NO. 4 - PROPOSED PLUMBING &amp; SANITARY WORKS</b>
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
                <strong></strong>
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
            </th>
            <th
                style="text-align:center;background-color:#FEF2CB;border-right:5px solid black;border-left:5px solid black;">
                <strong>$ <?php echo e(number_format($quotation_data['PLUMBING & SANITARY']['total'], 2)); ?></strong>
            </th>
        </tr>
        <tr>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;border-left:1px solid black;"></th>
            <th style="border-right:5px solid black;"></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $letterCounter = 'A'; // Start for head as 'B'
            $rowIndex = 1;
        ?>
        <?php $__currentLoopData = $quotation_data['PLUMBING & SANITARY']['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($item->type == 'head'): ?>
                <tr>
                    <td style="border: 1px solid black;vertical-align:right;background-color:#E2EFD9;">
                        <?php echo e($letterCounter); ?></td>
                    <td
                        style="border: 1px solid black;width: 350px; word-wrap: break-word; white-space: normal;background-color:#E2EFD9;">
                        <?php echo e($item->description); ?></td>
                    <td
                        style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top; background-color:#E2EFD9;">
                    </td>
                    <td
                        style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;background-color:#E2EFD9;">
                    </td>
                    <td
                        style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;background-color:#E2EFD9;">
                    </td>
                    <td
                        style="border: 1px solid black;width: 100px;vertical-align:top;text-align:center;background-color:#E2EFD9;">
                    </td>
                </tr>

                <?php
                    $letterCounter++; // Increment alphabet for next head
                ?>
            <?php elseif($item->type == 'row'): ?>
                <tr>
                    <td style="border: 1px solid black;vertical-align:top;"><?php echo e($rowIndex); ?></td>
                    <td style="border: 1px solid black;width: 350px; word-wrap: break-word; white-space: normal;">
                        <?php echo e($item->description); ?></td>
                    <td style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;">
                        <?php echo e($item->unit); ?></td>
                    <td style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;">
                        <?php echo e(number_format($item->qty, 2)); ?></td>
                    <td style="border: 1px solid black;width: 50px;text-align:center;vertical-align:top;">
                        <?php echo e(number_format($item->rate, 2)); ?></td>
                    <td style="border: 1px solid black;width: 100px;vertical-align:top;text-align:center;">$
                        <?php echo e(number_format($item->total, 2)); ?></td>
                </tr>
                <?php
                    $rowIndex++;
                ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align:center;border:1px solid black;width: 80px;">
                <strong>Sub-Total</strong>
            </td>
            <td style="border:1px solid black;"></td>
            <td style="border:1px solid black;"></td>
            <td style="border:1px solid black;"></td>
            <td style="border:1px solid black;"></td>
            <td style="text-align:center;border:1px solid black;">
                $ <?php echo e(number_format($quotation_data['PLUMBING & SANITARY']['total'], 2)); ?></td>

        </tr>
        <tr>
            <td style="text-align:center;border:1px solid black;width: 80px;">
                <strong>TOTAL</strong>
            </td>
            <td style="border:1px solid black;"></td>
            <td style="border:1px solid black;"></td>
            <td style="border:1px solid black;"></td>
            <td style="border:1px solid black;"></td>
            <td style="text-align:center;border:1px solid black;">
                $ <?php echo e(number_format($quotation_data['PLUMBING & SANITARY']['total'], 2)); ?></td>
        </tr>
    </tfoot>
</table>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/bill/components/export/plumbing_sanity.blade.php ENDPATH**/ ?>