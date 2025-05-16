<style>
    .invoice table th,
    .invoice table td {
        border: 1px solid #000;
        text-align: center;
    }


    .invoice-header {
        display: flex;
        gap: 10px;
        border-bottom: 1px solid #000;
    }

    .logo {
        width: 20%;
    }

    .detail {
        width: 60%;
        text-align: center;
    }

    .detail h4 {
        text-transform: uppercase;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .prv-status {
        float: right;
        width: 20%;
        text-align: right;
    }

    .invoice-main {
        display: flex;
        gap: 10px;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .invoice-main .left {
        width: 40%;
    }

    .invoice-main .center {
        width: 20%;
        text-align: center;
    }

    .invoice-main .right {
        width: 10%;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .gap-1 {
        gap: 5px;
    }

    table {
        width: 100%;
        border: 1px solid #000;
        border-collapse: collapse;
        text-wrap: wrap;
    }

    .table-dece {
        width: 500px;
    }

    .tfoot {
        border-top: 1px solid #000;
    }

    @media screen and (max-width:768px) {
        .invoice-header {
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            width: 100%;
            order: 2;
        }

        .detail {
            width: 100%;
            order: 3;
        }

        .prv-status {
            text-align: end;
            width: 100%;
            order: 1;
        }

        .invoice-main {
            flex-direction: column;
            justify-content: center;
        }

        .invoice-main .left {
            width: 100%;
        }

        .invoice-main .center {
            width: 100%;
        }

        .invoice-main .right {
            width: 100%;
        }

    }

    .k-checkbox [type="checkbox"]:not(:checked),
    [type="checkbox"]:checked {
        position: fixed;
        left: 0;
        opacity: 1;
    }
</style>
<?php
$all_items=DB::table('purchase_requistion_item_mapping')
->join('product','product.product_id','=','purchase_requistion_item_mapping.product_id')
->where('purchase_requistion_id',$note->purchase_requistion_id)->get();
?>

<div class="form-body">
    <input type="hidden" name="purchase_requistion_id" value="<?php echo e($note->purchase_requistion_id); ?>">
    <div class="invoice">
        <div class="invoice-header">
            <div class="logo">
            </div>
            <div class="detail">
                <h4>Store Requisition / Issue Form</h4>
            </div>
            <div class="prv-status">
                <?php if($note->status === "Approved"): ?>
                <label class="text-success"><?php echo e($status); ?></label>
                <?php elseif($note->status === "Rejected"): ?>
                <label class="text-danger"><?php echo e($status); ?></label>
                <?php else: ?>

                <button class="btn btn-danger btn-xs" name="rej" id="rej" onclick="rejectPR()">Reject</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="invoice-main">
            <div class="left">
                <b>PROJECT NAME / No: </b>
                <b><?php echo e($budgtrepo[0]->q_title??''); ?></b><br><br>
                <b>Milestone: </b>
                <b>
                    <?php switch($note->mile_stone):
                    case (1): ?> PRELIMINARIES <?php break; ?>
                    <?php case (2): ?> INSURANCE <?php break; ?>
                    <?php case (3): ?> SCHEDULE OF WORKS <?php break; ?>
                    <?php case (4): ?> Plumbing & Sanitary <?php break; ?>
                    <?php case (5): ?> ELEC & ACMV <?php break; ?>
                    <?php case (6): ?> EXTERNAL WORKS <?php break; ?>
                    <?php case (7): ?> PC & PS SUMS <?php break; ?>
                    <?php endswitch; ?>
                </b><br><br>
                <b>Task: </b>
                <b><?php echo e($note->task); ?></b><br><br>
            </div>
        </div>

        <table>
            <tr>
                <td style="width: 33.33%; text-align:left; border: 1px solid;"><b>Material Requisition Form (MRF)</b></td>
                <td style="width: 33.33%; text-align:left; border: 1px solid;"><b>Form No. <?php echo e($note->porder_id); ?></b></td>
                <td rowspan="6" style="width: 50%; border-right:none;">
                    <img src="<?php echo e(asset('uploads/logo/logo-with-bizsafe.png')); ?>" class="img-fluid" width="200px" alt="">
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid;"><b>Project Department-Purchasing Department</b></td>
                <td style="border: 1px solid;"><b>MRF Date: <?php echo e(date('d-m-Y', strtotime($note->order_date))); ?></b></td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid;"><b>Site: <?php echo e($note->site_address??''); ?></b></td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Material/Tool</th>
                    <th>Qty</th>
                    <th>Which Level?</th>
                    <th>Where did you use?</th>
                    <th>Which Sub Con used</th>
                    <th>Purchase Order No.</th>
                    <th>Delivery Order No.</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $all_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($item->product_name); ?></td>
                    <td><?php echo e($item->qty); ?></td>
                    <td><?php echo e($item->level); ?></td>
                    <td><?php echo e($item->where_use); ?></td>
                    <td><?php echo e($item->sub_con); ?></td>
                    <td><?php echo e($item->po_no); ?></td>
                    <td><?php echo e($item->do_no); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <table>
            <tr>
                <td style="width:70%;text-align:left;">
                    <label>Name of Supervisor who order:</label>
                    <b><?php echo e(get_designation_detail($project_d[0]->Supervisor)); ?></b>
                    <br />
                    <label>Name of Sub-contractor who order:</label>
                    <b><?php echo e(get_designation_detail($project_d[0]->Supervisor)); ?></b>

                </td>
                <td style="width:30%;text-align:left;">
                    <label>Date,Name & Signature of Engineer who check this order: </label><br /><br />
                    <?php echo e(get_designation_detail($project_d[0]->Engineer)); ?>

                </td>

            </tr>
            <tr>
                <td style="width:70%;text-align:left;">

                    <label><u>Signature:</u><img src="" height="100px" width="150px"></label><br />
                    <label>Requested by site Supervisor <u></u></label>

                </td>
                <td style="width:30%;text-align:left;">
                    <label>Date of Materials required: </label><br />
                    <p>Earliest Date:<?php echo date('d/m/Y l', strtotime($note->earliest_date)); ?></p><br />
                    <p>Latest Date:<?php echo date('d/m/Y l', strtotime($note->latest_date)); ?></p>
                    <br />
                </td>

            </tr>
        </table>
        <div>
            <label>Status:</label>
            <label><?php echo $note->status; ?></label>

            <?php if ($note->status == "Rejected") { ?>
                <textarea class="form-control" id="u_status_reason" name="u_status_reason"><?php echo $note->status_reason; ?></textarea>
            <?php } ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/prq/components/modals/add-edit-inc_view.blade.php ENDPATH**/ ?>