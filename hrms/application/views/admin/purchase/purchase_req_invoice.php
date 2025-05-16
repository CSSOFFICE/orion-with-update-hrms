<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>MRF <?php echo $porder_id ?></title>
    <!-- <link rel="stylesheet" href="xin_custom.css">
    <link rel="stylesheet" href="xin_hrsale_custom.css"> -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
        @page{
            margin: 1rem;
        }
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
            /* border: 1px solid #000; */
            border-collapse: collapse;
            text-wrap: nowrap;
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
</head>

<body>
    <div class="form-body">
        <input type="hidden" name="purchase_requistion_id" value="<?php echo $purchase_requistion_id; ?>">
        <div class="invoice">
            <!-- <div class="invoice-header">
                <div class="logo">
                <img src="data:image/jpeg;base64,<?php echo $image_base64; ?>" class="img-fluid" width="100px" alt="">
                </div>
                <div class="detail">
                    <h4>Store Requisition /
                        Issue Form</h4>
                </div>

            </div> -->
            <div class="invoice-main">

                <!-- <div class="left">

                    <b>PROJECT NAME / No: </b>
                    <b><?php echo $project_name ?></b>
                </div> -->

                <?php
                $loc = explode(',', $location);
                ?>

            </div>
            <?php
            $arr_site = explode(',', $site);
            ?>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Material Requisition Form (MRF)(MRF)</b></td>
                    <!-- <td style="width: 33.33%;text-align:left;"><b>MRF No.</b>:<input type="text" name="mrf_no" class="form-control"></td> -->
                    <td style="width: 33.33%;text-align:left;border-right:none;"><b>Form No. <?php echo $porder_id ?></b></td>
                    <!-- <td rowspan="3" style="width: 10%;border-right:none;"></td> -->
                    <td  style="width: 33.33%;border-right:none;border-top:none;border-bottom:none;" rowspan="" rowspan="3">
                        <img src="data:image/jpeg;base64,<?php echo $image_base641; ?>" width="300px" alt="">
                    </td>
                </tr>
                <tr>
                    <td style="width: 33.33%;text-align:left;border:1px solid;"><b>Project Department-Purchasing Department</b></td>
                    <td style="width: 33.33%;text-align:left; border:1px solid;" colspan="1"><b>MRF Date: <?php echo date('d-m-Y', strtotime($order_date)) ?></b></td>
                    <!-- <td style="width: 33.33%"></td> -->
           

                </tr>
                <tr>
                    <td style="text-align:left;border-top:none;border-right:none;border-bottom:none;" colspan="3" >
                        <b>Site: <?php echo $site_address ?></b>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <!-- <td style="width:30%;text-align:left;">
                    <input type="checkbox" id="site4" name="chk_site1[]" value="office">
                    <label for="site4"> OFFICE</label>
                    </td> -->
                    <td style="width:30%;text-align:left;">
                        <?php if (in_array("Storeroom No Stock.I have checked with Storeman", $arr_site)) { ?>
                            <span style="border: 1px solid #000; height:15%; width: 15% !important; text-align:center;font-family: DejaVu Sans, sans-serif;">✔</span>
                            <label for="u_site4"><b> Storeroom <u>No</u> Stock.I have checked with Storeman.</b></label>

                        <?php } else { ?>
                            <span style="border: none; width: 15%; text-align:center;font-family: DejaVu Sans, sans-serif;">☐</span>
                            <label for="u_site4"><b>Storeroom <u>No</u> Stock.I have checked with Storeman.</b></label>
                        <?php } ?>
                    </td>

                    <td style="width:30%;text-align:left;">
                        <?php if (in_array("Supervisor has checked with Engineer before ordering", $arr_site)) { ?>
                            <span style="border: 1px solid #000; width: 15%; text-align:center;font-family: DejaVu Sans, sans-serif;">✔</span>
                            <label for="u_site4"><b> Supervisor has checked with <u>Engineer</u> before ordering.</b></label>

                        <?php } else { ?>
                            <span style="border: none; width: 50%; text-align:center;font-family: DejaVu Sans, sans-serif;">☐</span>
                            <label for="u_site4"><b> Supervisor has checked with <u>Engineer</u> before ordering.</b></label>

                        <?php } ?>
                    </td>
                    <td style="width:20%;text-align:left;border:none"></td>

                </tr>
                <tr>
                    <td style="width:30%;text-align:left;">
                        <?php if (in_array("Please check Yishun Storeroom before you order", $arr_site)) { ?>
                            <span style="border: 1px solid #000; width: 15%; text-align:center;font-family: DejaVu Sans, sans-serif;">✔</span>
                            <label for="u_site4"><b> Please check Yishun <u>Storeroom</u> before you order.</b></label>

                        <?php } else { ?>
                            <span style="border: none; width: 15%; text-align:center;font-family: DejaVu Sans, sans-serif;">☐</span>

                            <label for="u_site4"><b> Please check Yishun <u>Storeroom</u> before you order.</b></label>
                        <?php } ?>
                    </td>
                    <td style="width:30%;text-align:left;">
                        <?php if (in_array("We have already checked with Boss to order", $arr_site)) { ?>
                            <span style="border: 1px solid #000; width: 15%; text-align:center;font-family: DejaVu Sans, sans-serif;">✔</span>
                            <label for="u_site4"><b> We have already checked with <u>Boss</u> to order.</b></label>

                        <?php } else { ?>
                            <span style="border: none; width: 15%; text-align:center;font-family: DejaVu Sans, sans-serif;">☐</span>
                            <label for="u_site4"><b> We have already checked with <u>Boss</u> to order.</b></label>
                        <?php } ?>
                    </td>
                    <td style="width:20%;text-align:left;border:none"></td>
                </tr>

                <!-- <tr>
                    <td style="width:30%;text-align:left;"><input type="checkbox" id="u_site8" name="u_check1[]" value="Book Crane Lorry" <?php echo (in_array("Book Crane Lorry", $arr_site) ? 'checked' : ''); ?>><label for="u_site8">Book Crane Lorry</label></td>
                </tr> -->
            </table>
            <!-- <div style="text-align: right;">
                                <a href="javascript:void(0)" class="btn-sm btn-success addButton" id="addButton2">Add</a>
                            </div> -->
            <table style="margin-top: 10px;">
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
                        <!-- <th>Action</th> -->

                    </tr>
                </thead>
                <tbody class="AddItem1" id="vendor_items_table2">
                    <?php
                    $i = 1;
                    // print_r($all_items);exit();
                    foreach ($all_items as $item) { ?>
                        <tr>
                            <td><label><?php echo $i; ?><label></td>
                            <td>
                                <?php echo $item->product_name ?>

                            </td>
                            <td>
                                <?php echo $item->qty; ?>
                            </td>
                            <td>
                                <?php echo $item->level; ?>
                            </td>

                            <td>
                                <?php echo $item->where_use; ?>
                            </td>
                            <td>
                                <?php echo $item->sub_con; ?>
                            </td>
                            <td>
                                <?php echo $item->po_no; ?>
                            </td>
                            <td>
                                <?php echo $item->do_no; ?>
                            </td>

                        </tr>

                    <?php
                        $i++;
                    }
                    ?>
                </tbody>

            </table>
            <table>
                <tr>
                    <td style="width:70%;text-align:left;">
                        <label>Name of Supervisor who order:</label>
                        <b><u><?php echo $supervisor; ?></u></b>
                        <br />
                        <label>Name of Sub-contractor who order:</label>
                        <b><u><?php echo $sub_contractor; ?></u></b>

                    </td>
                    <td style="width:30%;text-align:left;">
                        <label>Date,Name & Signature of Engineer who check this order: </label><br /><br />
                    </td>

                </tr>
                <tr>
                    <td style="width:70%;text-align:left;">
                        <?php if ($approvers_name > 0) { ?>
                            <label><u>Signature:<img src="data:image/jpeg;base64,<?php echo $signature; ?>" class="img-fluid" width="70px" alt=""></u></label><br />
                            <label>Requested by site Supervisor <u><?php echo $approvers_name ?></u></label>
                        <?php } ?>
                    </td>
                    <td style="width:30%;text-align:left;">
                        <label>Date of Materials required: </label>
                        <p>Earliest Date:<?php echo date('d/m/Y l', strtotime($earliest_date)); ?></p>
                        <p>Latest Date:<?php echo date('d/m/Y l', strtotime($latest_date)); ?></p>
                       
                    </td>

                </tr>
            </table>
            <div>
                <!-- <label>Status:</label>
                <label><?php echo $status; ?></label> -->
                <!-- <select name="u_status" id="u_status" class="form-control">
                                <option value="">Select status</option>
                                <option value="Pending Engineer Verification" <?php echo ($status == "Pending Engineer Verification" ? "selected" : ""); ?>>Pending Engineer Verification</option>
                                <option value="Pending Project Manager Approval" <?php echo ($status == "Pending Project Manager Approval" ? "selected" : ""); ?>>Pending Project Manager Approval</option>
                                <option value="Pending Management Approval" <?php echo ($status == "Pending Management Approval" ? "selected" : ""); ?>>Pending Management Approval</option>
                                <option value="Approved" <?php echo ($status == "Approved" ? "selected" : ""); ?>>Approved</option>
                                <option value="Rejected" <?php echo ($status == "Rejected" ? "selected" : ""); ?>>Rejected</option>

                            </select> -->
                <?php if ($status == "Rejected") { ?>
                    <textarea class="form-control" id="u_status_reason" name="u_status_reason"><?php echo $status_reason; ?></textarea>
                <?php } ?>
            </div>
        </div>



    </div>
</body>

</html>