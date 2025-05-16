<?php 

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
?>
<table id="invoices-list-table" class="table m-t-0 m-b-0 table-hover no-wrap invoice-list"
                data-page-size="10">
<tr>
<th>Claim Receivable</th>
    <th>Invoice No.</th>
    <th>Amount</th>
    <th>Status</th>
    

</tr>
<?php 
$i = 1 ;
$total_amount1=array();
?>
<?php $__currentLoopData = $invoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo ordinal($i).' claim'; ?></td>
    <td><?php echo e($items->invoice_no); ?></td>
    <td>$<?php echo e($items->total); ?></td>
    <td><?php echo e($items->bill_status); ?></td>

</tr>
<?php 
    $i++; 
    $total_amount1[]= floatval($items->total);
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<tr>
    <td></td>
    <td></td>
    
    <td><b>Total:</b></td>
    <td><b>$<?php echo array_sum($total_amount1); ?></b></td>

</tr>
</table>
<?php 
$i = 1 ;
$total_amount=array();
?>
<table id="invoices-list-table" class="table m-t-0 m-b-0 table-hover no-wrap invoice-list"
                data-page-size="10">
<tr>
    <th>Item Name</th>
    <th>Invoice No.</th>
    <th>Amount</th>
    

</tr>
<?php $__currentLoopData = $invoice_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo e($items->job_description); ?></td>
    <td><?php echo e($items->invoice_no); ?></td>
    <td>$<?php echo e($items->total); ?></td>
   

</tr>
<?php 
    $i++; 
    $total_amount[]= floatval($items->total);
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<tr>
    
    <td></td>
    <td><b>Total:</b></td>
    <td><b>$<?php echo array_sum($total_amount); ?></b></td>

</tr>
</table>
<div><h3>Employee Salary Details</h3></div>
<table id="invoices-list-table" class="table m-t-0 m-b-0 table-hover no-wrap invoice-list"
                data-page-size="10">
    <tr>
        <!-- <th>Employee Name</th> -->
        <th>Salary Month</th>
        <th>Net Salary</th>

    </tr>
    <?php 
        $total_salary_amount=array();
    ?>
    <?php $__currentLoopData = $employee_total_salary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salary_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
     <!-- <td><?php echo e($salary_detail->first_name .' '. $salary_detail->last_name); ?></td> -->
        <td><?php echo e($salary_detail->salary_month); ?></td>
        <td>$<?php echo e($salary_detail->total_salary); ?></td>

    </tr>
    <?php 
        $total_salary_amount[]=$salary_detail->total_salary;
    ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <tr>
   
    <td><b>Total:</b></td>
    <td><b>$<?php echo array_sum($total_salary_amount); ?></b></td>
    </tr>
</table>
<!-- <div><h3>Employee Paid Salary</h3></div>

<table id="invoices-list-table" class="table m-t-0 m-b-0 table-hover no-wrap invoice-list"
                data-page-size="10">

<tr>
        <th>Employee Name</th>
        <th>Net Salary</th>

    </tr>
    <?php $__currentLoopData = $employee_total_salary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total_salary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
    <td><?php echo e($total_salary->first_name .' '. $total_salary->last_name); ?></td>
    <td>$<?php echo e($total_salary->total_salary); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table> -->

<div>
   <b> Total Cost:</b><?php echo "$".(array_sum($total_amount1)-((array_sum($total_amount) + array_sum($total_salary_amount))));?>
</div><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/reports/expense/components/ajax.blade.php ENDPATH**/ ?>