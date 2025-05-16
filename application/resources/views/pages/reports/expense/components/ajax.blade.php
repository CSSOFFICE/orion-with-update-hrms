<?php

function ordinal($number)
{
    $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    if ($number % 100 >= 11 && $number % 100 <= 13) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}
?>
<table id="invoices-list-table" class="table m-t-0 m-b-0 table-hover no-wrap invoice-list" data-page-size="10">
    <tr>
        <th>Claim Receivable</th>
        <th>Invoice No.</th>
        <th>Amount</th>
        <th>Status</th>
    </tr>
    <?php
    $i = 1;
    $total_amount1 = [];
    ?>
    @foreach ($invoice as $items)
        <tr>
            <td><?php echo ordinal($i) . ' claim'; ?></td>
            <td>{{ $items->invoice_no }}</td>
            <td>${{ $items->total }}</td>
            <td>{{ $items->bill_status }}</td>

        </tr>
        <?php
        $i++;
        $total_amount1[] = floatval($items->total);
        ?>
    @endforeach

    <tr>
        <td></td>
        <td></td>

        <td><b>Total:</b></td>
        <td><b>$<?php echo array_sum($total_amount1); ?></b></td>

    </tr>
</table>
<?php
$i = 1;
$total_amount = [];
?>
<table id="invoices-list-table" class="table m-t-0 m-b-0 table-hover no-wrap invoice-list" data-page-size="10">
    <tr>
        <th>Item Name</th>
        <th>Invoice No.</th>
        <th>Amount</th>


    </tr>
    @foreach ($invoice_items as $items)
        <tr>
            <td>{{ $items->job_description }}</td>
            <td>{{ $items->invoice_no }}</td>
            <td>${{ $items->total }}</td>


        </tr>
        <?php
        $i++;
        $total_amount[] = floatval($items->total);
        ?>
    @endforeach

    <tr>

        <td></td>
        <td><b>Total:</b></td>
        <td><b>$<?php echo array_sum($total_amount); ?></b></td>

    </tr>
</table>
<div>
    <h3>Employee Salary Details</h3>
</div>
<table id="invoices-list-table" class="table m-t-0 m-b-0 table-hover no-wrap invoice-list" data-page-size="10">
    <tr>
        <!-- <th>Employee Name</th> -->
        <th>Salary Month</th>
        <th>Net Salary</th>

    </tr>
    <?php
    $total_salary_amount = [];
    ?>
    @foreach ($employee_total_salary as $salary_detail)
        <tr>
            <!-- <td>{{ $salary_detail->first_name . ' ' . $salary_detail->last_name }}</td> -->
            <td>{{ $salary_detail->salary_month }}</td>
            <td>${{ $salary_detail->total_salary }}</td>

        </tr>
        <?php
        $total_salary_amount[] = $salary_detail->total_salary;
        ?>
    @endforeach
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
    @foreach ($employee_total_salary as $total_salary)
<tr>
    <td>{{ $total_salary->first_name . ' ' . $total_salary->last_name }}</td>
    <td>${{ $total_salary->total_salary }}</td>
    </tr>
@endforeach
</table> -->

<div>
    <b> Total Cost:</b><?php echo "$" . (array_sum($total_amount1) - (array_sum($total_amount) + array_sum($total_salary_amount))); ?>
</div>
