<div class="card" id="notes-table-wrapper">
    <div class="card-body">
        <div class="table-responsive">
            
                <table id="note-foo-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                    <thead>
                        <tr>

                            <th class="notes_col_added">Description</th>
                            <th class="notes_col_title">Itemized Surplus (a-b-c)</th>
                            <th class="notes_col_title">Budget Amount </th>
                            <th class="notes_col_tags">Contract Amount (a)</th>
                            <th class="notes_col_date">Material cost (b)</th>
                            <th class="notes_col_date">Petty Cash (c)</th>
                            <th class="notes_col_date">Action</th>

                        </tr>
                    </thead>
                    <tbody id="notes-td-container">
                        <!--ajax content here-->
                        <?php echo $__env->make('pages.budget.components.table.ajax', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <!--ajax content here-->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="20">
                                <!--load more button-->
                                <?php echo $__env->make('misc.load-more-button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <!--load more button-->
                            </td>
                        </tr>
                    </tfoot>
                </table>
            
            <!--nothing found-->
            
            <!--nothing found-->
            
        </div>
    </div>
</div>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/budget/components/table/table.blade.php ENDPATH**/ ?>