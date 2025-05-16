<!--main table gg view-->

<?php echo $__env->make('pages.budget.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    // Listen for input in the .budget (BUDGET / WORKING AMOUNT) field
    // $('body').on('input', '.budget', function() {
    //     // Get the closest table row
    //     var $tr = $(this).closest('tr');

    //     // Retrieve values from the relevant cells
    //     var budget_amount = $(this).val().trim(); // Input value from BUDGET/WORKING AMOUNT
    //     var contract_amount = $tr.find('.contract').text().trim(); // Contract Amount
    //     var purchase_order_amount = $tr.find('.purchase_order').text().trim(); // Purchase Order Amount
    //     var petty_cash_amount = $tr.find('.petty_cash').text().trim(); // Petty Cash Amount
    //     var invoice_amount = $tr.find('.invoice').text().trim(); // Invoice Amount

    //     // Convert values to floats, default to 0 if invalid or empty
    //     budget_amount = parseFloat(budget_amount) || 0;
    //     contract_amount = parseFloat(contract_amount) || 0;
    //     purchase_order_amount = parseFloat(purchase_order_amount) || 0;
    //     petty_cash_amount = parseFloat(petty_cash_amount) || 0;
    //     invoice_amount = parseFloat(invoice_amount) || 0;

    //     // Perform the calculation:
    //     // Surplus/Deficit = Contract + Purchase Order + Petty Cash + Budget - Invoice
    //     var surplus_deficit =
    //         contract_amount + purchase_order_amount + petty_cash_amount + budget_amount - invoice_amount;

    //     // Update the surplus/deficit column (Itemized Surplus/Deficit)
    //     $tr.find('.itemized').text(surplus_deficit.toFixed(2)); // Format to 2 decimal places

    //     // Recalculate the overall totals at the bottom
    //     calculateOverallTotals();
    // });

    $('body').on('input', '.budget', function() {
        // Get the closest table row
        var $tr = $(this).closest('tr');

        // Retrieve values from the relevant cells
        var budget_amount = $(this).val().trim(); // Input value from BUDGET/WORKING AMOUNT
        // var surplus_deficit = $tr.find('.surplus_deficit').data('surplus_deficit'); // Contract Amount
        var surplus_deficit = $tr.find('.surplus_deficit').val().trim(); // Contract Amount
        var petty_cash_amount = parseFloat($tr.find('.pettycase').text().trim()) || 0;

        var contract_amount = parseFloat($tr.find('.contract').text().trim()) || 0;
        var subcontractor_amount = parseFloat($tr.find('.subcontractor_amount').text().trim()) || 0;

        // Convert values to floats, default to 0 if invalid or empty
        budget_amount = parseFloat(budget_amount) || 0;
        surplus_deficit = parseFloat(surplus_deficit) || 0;


        // Perform the calculation:
        // Surplus/Deficit = Contract + Purchase Order + Petty Cash + Budget - Invoice
        var new_surplus_deficit =
            budget_amount -contract_amount- subcontractor_amount-petty_cash_amount;

        // Update the surplus/deficit column (Itemized Surplus/Deficit)
        console.log(new_surplus_deficit);

        $tr.find('.surplus_deficit').text(new_surplus_deficit.toFixed(2)); // Format to 2 decimal places

        // Recalculate the overall totals at the bottom
        calculateOverallTotals();
    });

    // Function to calculate and update the totals at the bottom
    function calculateOverallTotals() {
        var total_budget = 0;
        var total_contract = 0;
        var total_purchase_order = 0;
        var total_petty_cash = 0;
        var total_invoice = 0;
        var total_surplus_deficit = 0;

        // Iterate over each row to sum up the totals
        $('table tr').each(function() {
            var $row = $(this);
            var budget_amount = parseFloat($row.find('.budget').val()) || 0;
            var contract_amount = parseFloat($row.find('.contract').text().trim()) || 0;
            var purchase_order_amount = parseFloat($row.find('.purchase_order').text().trim()) || 0;
            var petty_cash_amount = parseFloat($row.find('.petty_cash').text().trim()) || 0;
            var invoice_amount = parseFloat($row.find('.invoice').text().trim()) || 0;
            var rowTotal_surplus_deficit = parseFloat($row.find('.surplus_deficit').text().trim()) || 0;

            total_budget += budget_amount;
            total_contract += contract_amount;
            total_purchase_order += purchase_order_amount;
            total_petty_cash += petty_cash_amount;
            total_invoice += invoice_amount;

            total_surplus_deficit +=
                rowTotal_surplus_deficit
        });

        // Update the totals in the footer row
        // $('#total_budget').text(total_budget.toFixed(2));
        $('#budget').text(total_budget.toFixed(2));
        // $('#total_contract').text(total_contract.toFixed(2));
        // $('#total_purchase_order').text(total_purchase_order.toFixed(2));
        // $('#total_petty_cash').text(total_petty_cash.toFixed(2));
        // $('#total_invoice').text(total_invoice.toFixed(2));
        $('#total_surplus_deficit').text(total_surplus_deficit.toFixed(2));
    }
    $('body').on('dblclick', '.budget', function() {
        var $tr = $(this).closest('tr');
        var budget_amount = $(this).val().trim();
        var category_id = $(this).data('c_id');
        var Task = $(this).data('t_id');

        const csrfToken = "<?php echo e(csrf_token()); ?>";


        let id = $(this).data('id');

        $.ajax({
            url: "<?php echo e(route('store_data_budget')); ?>",
            type: "post",
            data: {
                amount: budget_amount,
                category_id: category_id,
                Task: Task,
                _token: csrfToken
            },
            success: function(re) {
                alert("data Saved")
                window.location.reload(1)
            }
        })





    });
</script>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/budget/components/table/wrapper.blade.php ENDPATH**/ ?>