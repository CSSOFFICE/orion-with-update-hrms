

<?php $i=1 ?>
<?php $__currentLoopData = $lineitems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lineitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<tr>
    <!-- SR No -->
    <td class="x-quantity sr_no"> <?php echo e($i); ?></td>
    <td class="x-quantity sr_no"> <?php echo e($lineitem->item); ?></td>
    <!--description-->
    <td class="x-description text-center"><?php echo e($lineitem->lineitem_description); ?></td>
    <!--quantity-->
    <?php if($lineitem->lineitem_type == 'plain'||$lineitem->lineitem_type == 'product'): ?>
    <td class="x-quantity text-center"><?php echo e($lineitem->lineitem_quantity); ?></td>
    <?php else: ?>
    <td class="x-quantity text-center">
        <?php if($lineitem->lineitem_time_hours > 0): ?>
        <?php echo e($lineitem->lineitem_time_hours); ?><?php echo e(strtolower(__('lang.hrs'))); ?>&nbsp;
        <?php endif; ?>
        <?php if($lineitem->lineitem_time_minutes > 0): ?>
        <?php echo e($lineitem->lineitem_time_minutes); ?><?php echo e(strtolower(__('lang.mins'))); ?>

        <?php endif; ?>
    </td>
    <?php endif; ?>
    <!--unit price-->
    <td class="x-unit text-center"><?php echo e($lineitem->lineitem_unit); ?></td>
    <!--rate-->
    <td class="x-rate text-center"><?php echo e(runtimeNumberFormat($lineitem->lineitem_rate)); ?></td>
    <!--tax-->
    
    <!--total-->
    <td class="x-total text-center"><?php echo e(runtimeNumberFormat($lineitem->lineitem_total)); ?></td>
</tr>
<?php $i++

?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php
$total = $lineitems->sum('lineitem_total');
?>
<tr>
    <td class="x-quantity sr_no" colspan='5'> </td>
    <td class="billing-sums-total"  style="color:black; font-weight:600; font-size:15px;">Total:</td>
    <td id="billing-sums-total" style="color:black; font-weight:600; font-size:15px;">
        <span><?php echo e(runtimeMoneyFormat($total)); ?></span>
    </td>
    </tr>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/bill/components/elements/lineitems.blade.php ENDPATH**/ ?>