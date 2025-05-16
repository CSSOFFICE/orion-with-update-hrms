<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .invoice-container {

            position: relative;
            max-width: 1080px;
            margin-inline: auto;
            padding: 50px;
            /* border: solid #000;  */
            background-color: white;
        }

        .header {
            position: relative;
            height: 85px;
        }

        #logo {
            float: left;
            position: absolute;

        }

        #company-name {
            float: right;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #company-name p {
            margin-top: auto;
            font-size: 0.9rem;
        }

        .underline {
            clear: both;
            border-bottom: 1px solid #000;
            margin-bottom: 20px;
        }

        #purchase-order {
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
        }

        #form {
            float: left;
            width: 49%;
        }

        #form img {
            border: 5px solid #000;
            border-radius: 20px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
        }

        th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        th,
        td {

            padding: 10px;
            text-align: left;
        }

        td {
            font-size: 12px;
        }

        ul {
            font-size: 12px;
        }

        #totals {
            width: 100%;
            text-align: right;
            margin-bottom: 20px;
        }

        #signature-blocks {
            clear: both;
            margin-top: 20px;
        }

        .signature {
            width: 40%;
            display: inline-block;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 10px;
            margin-top: 10px;
        }

        .overlay {
            position: relative;
            width: 430px;
            height: 200px;
            --border-style: 1px solid rgb(0, 0, 0);
            --border-space: 20px;
        }

        .overlay:before {
            display: none;
        }

        .overlay-element {
            position: absolute;
            width: 50px;
            height: 50px;
        }

        .overlay .top-left {
            border-left: var(--border-style);
            border-top: var(--border-style);
            top: var(--border-space);
            left: var(--border-space);
        }

        .overlay .top-right {
            border-right: var(--border-style);
            border-top: var(--border-style);
            top: var(--border-space);
            right: var(--border-space);
        }

        .overlay .bottom-left {
            border-left: var(--border-style);
            border-bottom: var(--border-style);
            bottom: var(--border-space);
            left: var(--border-space);
        }

        .overlay .bottom-right {
            border-right: var(--border-style);
            border-bottom: var(--border-style);
            bottom: var(--border-space);
            right: var(--border-space);
        }

        .footer {
            position: relative;
        }
    </style>
</head>

<body>


    <div class="invoice-container">

        <div class="footer" style="margin-top: 1.5rem;">
            <img src="data:image/jpeg;base64,<?php echo $image_base64; ?>" alt="Company Logo" style="width: 100%; height: auto;">

        </div>




        <!-- <div class="underline"></div> -->

        <div id="purchase-order" style="text-transform: uppercase;">
            Purchase Order
        </div>
        <div class="info" style="display: flex; justify-content: space-between;">
            <div id="content">
                <div class="overlay">
                    <div id="from" style="position: absolute;left: 30px ;top: 30px ;">
                        <p style="margin-top: 0; margin-bottom: 0.5rem;"><strong> <?php echo $supplier_name; ?></strong></p>
                        <p style="margin-top: 0; margin-bottom: 0.5rem;"><?php echo $supplier_address ?></p>
                        <!-- <p style="margin-top: 0; margin-bottom: 0.5rem;">ANG MO KIO INDUSTRIAL PARK 2</p>
                    <p style="margin-top: 0; margin-bottom: 0.5rem;">SINGAPORE 569525</p> -->
                        <br>
                        <div style="display: flex; justify-content: space-between;">
                            <span>TEL : <?php echo $supplier_phone ?></span>
                            <!-- <span>FAX : 64833825</span> -->
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>SITE ADDRESS : <?php echo $address ?></span>
                        </div>
                    </div>
                    <div class="overlay-element top-left"></div>
                    <div class="overlay-element top-right"></div>
                    <div class="overlay-element bottom-left"></div>
                    <div class="overlay-element bottom-right"></div>
                </div>
            </div>

            <div id="to" style="margin-top: 1.5rem; width: 40%;">
                <p style="margin-top: 0; margin-bottom: 0.5rem;"><strong>P/O NO. : <?php echo $porder_id; ?></strong></p>
                <!-- <p style="margin-top: 0; margin-bottom: 0.5rem;">INVOICE NO. :</p> -->
                <p style="margin-top: 0; margin-bottom: 0.5rem;">YOUR REF NO. :</p>
                <!-- <p style="margin-top: 0; margin-bottom: 0.5rem;">TERMS : Net 60 days</p> -->
                <p style="margin-top: 0; margin-bottom: 0.5rem;">DATE : <?php echo $created_date; ?></p>
                <!-- <p style="margin-top: 0; margin-bottom: 0.5rem;">PAGE : 1 of 1</p> -->
            </div>
        </div>

        <table>
            <!-- Your table content here -->
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Item</th>
                    <th>DESCRIPTION</th>
                    <th>UOM</th>
                    <th>QTY</th>
                    <th>UT/PRICE</th>
                    <!-- <th>DISC.</th> -->
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($get_all_item as $items) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $items->product_name; ?></td>
                        <td style="width:500px;"><?php echo $items->description; ?></td>
                        <td><?php echo $items->std_uom; ?></td>
                        <td> <?php echo $items->prd_qtn; ?></td>
                        <td> <?php echo $items->prd_price; ?></td>
                        <!-- <td></td> -->
                        <td><?php echo $items->prd_total; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


        <div style="display: flex;">
            <div id="total_in_words" style="width: 100%;"></div>
            <div id="totals">
                <div style="display: flex;gap: 5px;width: 100%;justify-content: end; margin-bottom: 0.5rem;">
                    <div style="font-size: 12px;"><strong>Less Discount(<?php echo $items->discount; ?>%) @ <?php echo number_format($items->total_prd_total, 2) ?></strong></div>
                    <div style="border: 2px solid #000;height: 25px;width: 30%; "><?php $discount_price = ($items->total_prd_total * $items->discount) / 100; ?><?php echo '$' . number_format($discount_price, 2); ?></div>
                </div>
                <div style="display: flex;gap: 5px;width: 100%;justify-content: end; margin-bottom: 0.5rem;">
                    <div style="font-size: 12px;"><strong>Subtotal TOTAL(EXCLUDING GST)</strong></div>
                    <div style="border: 2px solid #000;height: 25px;width: 30%; "><?php $sub_t = $items->total_prd_total - $discount_price;
                                                                                    echo number_format($sub_t, 2); ?></div>
                </div>
                <div style="display: flex;gap: 5px;width: 100%;justify-content: end; margin-bottom: 0.5rem;">
                    <div style="font-size: 12px;"><strong>GST payable @ <?php echo $gst1  ?>% on <?php echo number_format($sub_t, 2); ?></strong></div>
                    <div style="border: 2px solid #000;height: 25px;width: 30%;"><?php
                                                                                    // Check if $sub_t and $gst1 are numeric before performing the calculation
                                                                                    if (is_numeric($sub_t) && is_numeric($gst1)) {
                                                                                        // Perform the calculation and assign the value to $gt
                                                                                        $gt = ($sub_t * (float)$gst1) / 100;
                                                                                        // Output the result
                                                                                        echo number_format($gt, 2);
                                                                                    } else {
                                                                                        // Handle the case where $sub_t or $gst1 is not numeric
                                                                                        echo "Error: Non-numeric value encountered.";
                                                                                    }
                                                                                    ?></div>
                </div>
                <div style="display: flex;gap: 5px;width: 100%;justify-content: end; margin-bottom: 0.5rem;">
                    <div style="font-size: 12px;"><strong>TOTAL</strong></div>
                    <div style="border: 2px solid #000;height: 25px;width: 30%;"><?php
                                                                                    if (is_numeric($sub_t) && is_numeric($items->discount) && is_numeric($gt)) {
                                                                                        echo number_format(($sub_t - ($sub_t * $items->discount) / 100) + $gt, 2);
                                                                                    } else {
                                                                                        echo "Error: Non-numeric value encountered.";
                                                                                    }
                                                                                    ?></div>
                </div>
            </div>





        </div>
        <div id="total_in_words" style="width: 100%;">
            <p style="margin-top: 0; margin-bottom: 0; font-size: 14px;"><?php echo ucwords($this->Xin_model->convertNumberToWord(($sub_t - ($sub_t * $items->discount) / 100) + $gt)); ?> ONLY</p>
        </div>


        <div style="border: 1px solid #000;"></div>

        <?php echo $term; ?>

        <div id="signature-blocks" style="display: flex; justify-content: space-between; height: 100px; align-items: end;">
            <div class="signature">
                PREPARED BY
                <br />
                <b><?php echo $app_by ?></b>
            </div>
            <div class="signature">
                AUTHORISED BY
            </div>
        </div>
        <div class="footer" style="margin-top: 1.5rem;">
            <img src="data:image/jpeg;base64,<?php echo $image_base64F; ?>" alt="Footer Logo" style="width: 100%; ">

        </div>
    </div>

</body>

</html>