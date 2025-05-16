<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> PURCHASE ORDER || <?php echo $porder_id; ?></title>
    <style>
        @page {
            margin-top: 0.5cm;
            /* Adjust as needed */
            margin-bottom: 0.5cm;
            /* Adjust as needed */
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0;
            right: 0;
            height: 5cm;
            /* Adjust based on your header height */

        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3cm;
            /* Adjust based on your footer height */
        }


        table {
            width: 100%;
            font-size: 14px;
        }

        td {
            vertical-align: top;
        }

        td,
        th {
            text-align: center;
            height: 15px;
        }
    </style>
</head>

<body>
<h2 style="margin: 0; color: #333399; text-align: left;">
            <center>PURCHASE ORDER
            </center>
        </h2>
        <br>
    <table style="width: 100%;  font-family: Arial, sans-serif;">
        <tr>
            <td style="width: 50%; vertical-align: top;">                
                <img src="data:image/jpeg;base64,<?php echo $logo; ?>" alt="Company Logo" style="width: 200px; height: 100px; float: left;">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <table style="width: 100%; font-size: 14px;">
                    <tr>
                        <td style="padding-top:100px; width: 20%; font-weight: bold; vertical-align: top;">VENDOR:</td>
                        <td style="padding-top:100px; text-align: left;">
                            <b><?php echo $supplier_name; ?></b><br>
                            <?php echo $supplier_address; ?><br><br>
                            <strong>Tel:</strong> <?php echo $supplier_phone; ?><br>
                            <strong>Fax:</strong> <?php echo $supplier_fax ?? 'N/A'; ?><br>
                            <strong>Email:</strong> <?php echo $email_address; ?><br>
                            <strong>Attention:</strong> <?php echo $contact_person . " (M: " . $supplier_phone . ")"; ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; text-align: right; font-size: 12px;">

                <h4 style="font-weight:bold;">ORION INTEGRATED SERVICES PTE LTD</h4>
                1 YISHUN INDUSTRIAL STREET 1<br>
                #08-15 A'POSH BIZHUB
                SINGAPORE 768160<br>
                TEL: 6734 0032&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FAX: 6734 0728<br>
                E-MAIL: accounts@ois.com.sg<br>
                GST REG NO.: 200809511K<br>
                CO REG NO.: 200809511K
                <?php if ($send_by) { ?>
                    <div style="padding-top:210px ; font-size: 14px; vertical-align: bottom;">
                        <p style="margin: 0;"><b><?php echo $send_by; ?>:</b> <?php echo date('d/m/Y', strtotime($send_date)); ?></p>
                    </div>
                <?php } ?>
            </td>
        </tr>

    </table>



    <table style="border-collapse: collapse;">
        <thead style="border-top: 3px solid #333399; background:#CCFFFF;">
            <tr>
                <th style="border: 1px solid #000;">DATE</th>
                <th style="border: 1px solid #000;">P.O NO</th>
                <th style="border: 1px solid #000;">CUSTOMER ID</th>
                <th style="border: 1px solid #000;">PAYMENT TERMS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #000;">
                    <?php echo $created_date; ?>
                </td>
                <td style="border: 1px solid #000;">
                    <?php echo $porder_id; ?>
                    <!-- (R2) -->
                </td>
                <td style="border: 1px solid #000;">

                </td>
                <td style="border: 1px solid #000;"><?php echo $payment_term; ?></td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr>
                <td style="text-align: left;">
                    <!-- <?php //echo ($get_purchse_items[0]->remark) ?? ''; 
                            ?> -->
                    <b><?php echo " ".$amd_line; ?></b>

                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse;">
        <thead style="border-top: 3px solid #333399; background:#CCFFFF;">
            <th style="border: 1px solid #000;">ITEM #</th>
            <th style="border: 1px solid #000; ">DESCRIPTION</th>
            <th style="border: 1px solid #000;">UNIT</th>
            <th style="border: 1px solid #000;">QTY</th>
            <th style="border: 1px solid #000;">UNIT PRICE</th>
            <th style="border: 1px solid #000;">TOTAL</th>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-decoration:underline; text-align:left;"><?php echo "PROJECT: " . strtoupper($project_title); ?><br>
                    <!-- <b>Milestone: </b>
                    <b><?php if ($milestone == '1') {
                            $name = "PRELIMINARIES";
                        } elseif ($milestone == '2') {
                            $name = "INSURANCE";
                        } elseif ($milestone == '3') {
                            $name = "SCHEDULE OF WORKS";
                        } elseif ($milestone == '4') {
                            $name = "Plumbing & Sanitary";
                        } elseif ($milestone == '5') {
                            $name = "ELEC & ACMV";
                        } elseif ($milestone == '6') {
                            $name = "EXTERNAL WORKS";
                        } elseif ($milestone == '7') {
                            $name = "PC & PS SUMS";
                        }
                        echo ($name) ?? ''; ?></b><br><br>
                    <b>Task: </b>
                    <b><?php echo $description_name ?></b><br><br> -->
                </td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;text-align:left; "><?php echo $amendable; ?></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
            </tr>
            <?php
            $i = 1;
            $imageIndex = 1; // Counter for image index
            $sub_total = 0;

            foreach ($get_purchse_items as $items) { ?>
                <?php if ($items->type == 'product') { ?>
                    <tr>
                        <td style="border: 1px solid #000;"><?php echo $i; ?></td>
                        <td style="border: 1px solid #000;text-align:left;">
                            <?php echo $items->description; ?>
                            <br>
                        </td>
                        <td style="border: 1px solid #000;"><?php echo $items->std_uom; ?></td>
                        <td style="border: 1px solid #000;"><?php echo $items->prd_qtn; ?></td>
                        <td style="border: 1px solid #000;"><?php echo "$ " . $items->prd_price; ?></td>
                        <td style="border: 1px solid #000;"><?php echo "$ " . number_format((float)($items->prd_price * $items->prd_qtn), 2); ?></td>
                    </tr>


                    <tr>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                    </tr>
                <?php } ?>
                <?php if ($items->type == 'blank') { ?>
                    <tr>
                        <td style="border: 1px solid #000;"><?php echo $i; ?></td>
                        <td style="border: 1px solid #000;text-align:left;"><?php echo $items->blank; ?></td>
                        <td style="border: 1px solid #000;"><?php echo $items->unit; ?></td>
                        <td style="border: 1px solid #000;"><?php echo $items->prd_qtn; ?></td>
                        <td style="border: 1px solid #000;"><?php echo "$ " . $items->prd_price; ?></td>
                        <td style="border: 1px solid #000;"><?php echo "$ " . number_format((float)($items->prd_price * ($items->prd_qtn) ?? 0), 2); ?></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                    </tr>
                <?php } ?>
                <?php if ($items->type == 'image') { ?>
                    <tr>
                        <td style="border: 1px solid #000;"><?php echo "1DJR Image " . $imageIndex; ?></td>
                        <td style="border: 1px solid #000; padding: 10px; text-align: center;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between;">
                                <!-- First Image -->
                                <?php if (!empty($items->b_img)) { ?>
                                    <img src="<?php echo base_url('uploads/purchase_order/' . $items->b_img); ?>"
                                        style="width:100px; height:100px; margin-right: 10px;">
                                <?php } ?>

                                <!-- Description -->
                                <span style="flex: 1; text-align: center; font-weight: bold;">
                                    <?php echo $items->img_description; ?>
                                </span>

                                <!-- Second Image -->
                                <?php if (!empty($items->a_img)) { ?>
                                    <img src="<?php echo base_url('uploads/purchase_order/' . $items->a_img); ?>"
                                        style="width:100px; height:100px; margin-left: 10px;">
                                <?php } ?>
                            </div>

                            <!-- Color Section -->
                            <!-- <div style="margin-top: 15px; text-align: left;">
                                    <strong>Colour:</strong> <?php //echo $items->prd_color_name; 
                                                                ?>
                                    <div style="width: 60px; height: 60px; background-color: <?php //echo $items->prd_color; 
                                                                                                ?>; border: 1px solid #000; display: inline-block; margin-left: 10px;"></div>
                                </div> -->
                        </td>

                        <td style="border: 1px solid #000;"><?php echo $items->unit; ?></td>
                        <td style="border: 1px solid #000;"><?php echo $items->prd_qtn; ?></td>
                        <td style="border: 1px solid #000;"><?php echo $items->prd_price; ?></td>
                        <td style="border: 1px solid #000;"><?php echo $items->prd_total; ?></td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                        <td style="border: 1px solid #000;"></td>
                    </tr>
                    <?php $imageIndex++; // Increment only the image index 
                    ?>
                <?php } ?>
            <?php
                $sub_total += $items->prd_price * $items->prd_qtn; // Proper subtotal calculation
                $i++; // Increment the general index for each item
            }
            ?>

            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-align: left;width:400px">Note:-<br />
                    <?php echo $note; ?>
                </td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-align: left;">

                    <?php if ($delivery_type == "delivery") {
                        echo "Delivery Date: " . date('d/m/Y (l)', strtotime($delivery_date)) . " " . ($delivery_time) ?? '';
                    } else if ($delivery_type == "self_collection") {
                        echo "Self Collection: " . date('d/m/Y (l)', strtotime($delivery_date)) . " " . ($delivery_time) ?? '';
                    }
                    ?>
                </td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-align: left;">Sub total</td>
                <td style="border: 1px solid #000;">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td><?php echo '$ ' ?></td>
                                <td>
                                    <?php echo ($sub_total); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-align: left;">Deliver to: <?php echo $site_add; ?></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-align: left;">GST <?php echo $gst1; ?>%</td>
                <td style="border: 1px solid #000;">
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td><?php echo '$ ' ?></td>
                                <td>

                                    <?php echo number_format($gst_amount,2); ?>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-align: left;">
                    <?php echo 'Contact: '. $cantactperson; ?></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000; text-align: left;">total</td>
                <td style="border: 1px solid #000;">
                    <table style="width: 100%;">
                        <tbody style="border:none;background:#00CCFF;">
                            <tr>
                                <td><?php echo '$ ' ?></td>
                                <td>
                                    <?php if ($inclusive_gst == 'on') { ?>
                                        <?php echo number_format(((float)$sub_total), 2); ?>

                                    <?php } else if ($inclusive_gst == 'off') { ?>
                                        <?php echo number_format(((float)$sub_total > 0 ? (float)$sub_total + (float)$sub_total * (float)$gst1 / 100 : 0), 2); ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; font-size: 12px;" colspan="2">
                    Office Address: <?php echo $company_info[0]->address_1; ?><br>
                    <?php echo $company_info[0]->address_2; ?> <br>
                    <?php echo $company_info[0]->city ?><br>
                    <?php echo $company_info[0]->state . " " . $company_info[0]->zipcode; ?><br>
                    Email Address:<?php echo $company_info[0]->email; ?><br>
                    Company Registration No: 200809511K <br>
                    GST Reg. No.: 2008-09511-K <br>
                </td>
                <td>
                    <img src="" alt="">
                </td>
                <td colspan="3" style="vertical-align: bottom;">
                    <div class="sign-block" style="border-bottom: 1px solid #000; text-align: left;">
                        <?php echo $po_for; ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <br /><br />

    <br>
    <p>This is a computer generated document. No signature is required.</p>
    <footer>
        <center>
        <span>
    <?php if (!empty($logoUrl)) : ?>
        <img src="data:image/jpeg;base64,<?php echo $logoUrl; ?>" alt="" width="50px">
    <?php endif; ?>
</span>
<span>
    <?php if (!empty($logoUrl2)) : ?>
        <img src="data:image/jpeg;base64,<?php echo $logoUrl2; ?>" alt="" width="50px">
    <?php endif; ?>
</span>
<span>
    <?php if (!empty($logoUrl3)) : ?>
        <img src="data:image/jpeg;base64,<?php echo $logoUrl3; ?>" alt="" width="50px">
    <?php endif; ?>
</span>


        </center>
    </footer>
</body>


</html>