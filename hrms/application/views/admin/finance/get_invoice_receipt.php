<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>INVOICE | <?= $invoice_no ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            margin-top: 1cm;
            /* margin-left: 0.5cm; */
            /* margin-right: 0.5cm; */
            margin-bottom: 0.1cm;
        }

        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 15px;
            margin: 0;
            padding: 0;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3cm;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        td,
        th {
            padding: 1px;
            text-align: left;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <header style="height: 5cm; font-size: 10px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; text-align: left; vertical-align: top;">
                    <img src="data:image/jpeg;base64,<?php echo $logoUrl4; ?>" alt="" width="250px">
                </td>
                <td style="width: 50%; text-align: right; vertical-align: top;font-family:Arial, sans-serif;">
                    <?php echo $logoUrl5; ?>
                </td>
            </tr>
        </table>

        <?php $query = $this->db->where('client_id', $client_id)->where('d_address', 1)->get('billing_addresses')->result() ?>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 45%; vertical-align: top;">
                    <table style="width: 100%;font-size:13px;" cellpadding=0 cellspacing=0>
                        <tr>
                            <td style="width: 5%;vertical-align: top;"><strong>TO</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td style="width: 75%;"><?php echo ($client_company_name) ?? $f_name ?><br>
                                <?php if (!empty($query)) { ?>
                                    <?php echo $query[0]->state ?><br>
                                    <?php echo $query[0]->city ?><br>
                                    <?php echo $query[0]->street ?><br>
                                    <?php echo $query[0]->p_unit ?><br>
                                    <?php echo $query[0]->country . " " . $query[0]->zipcode ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td style="width: 20%;vertical-align: bottom;"><strong>TEL/FAX</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: bottom;">:</td>
                            <td> <?php if (!empty($query)) {
                                        echo $query[0]->p_contact;
                                    } ?></td>
                        </tr>
                    </table>


                </td>
                <td style="width: 10%; vertical-align: top;"></td>
                <td style="width: 45%; vertical-align: top;">
                    <table style="width: 100%;font-weight:bold;font-size:13px;" cellpadding=0 cellspacing=0>
                        <tr>
                            <td style="width: 40%; font-size:20px;"><strong>TAX INVOICE</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td style="width: 40%; font-size:20px;"><strong><?= $invoice_no ?></strong></td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td style="width: 20%;vertical-align: top;"><strong>DATE</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td><?= $invoice_date ?></td>
                        </tr>
                        <tr>
                            <td style="width: 20%;vertical-align: top;"><strong>P/O</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td><?php echo $m_order_no ?></td>
                        </tr>
                        <tr>
                            <td style="width: 20%;vertical-align: top;"><strong>D/O</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td><?php echo $m_do_no ?></td>
                        </tr>
                        <tr>
                            <td style="width: 20%;vertical-align: top;"><strong>TERMS</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td><?php echo  $terms ?></td>
                        </tr>
                        <!-- <tr>
                            <td style="width: 20%;vertical-align: top;"><strong>SALESMAN CODE</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td></td>
                        </tr> -->
                        <tr>
                            <td style="width: 20%;vertical-align: top;"><strong>PAGE NO.</strong></td>
                            <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                            <td class="page"></td>
                        </tr>
                    </table>
                </td>

            </tr>
        </table>
    </header>
    <footer style="height: 1.5cm;">
        <center>
            <span> <img src="data:image/jpeg;base64,<?php echo $logoUrl; ?>" alt="" width="50px"></span>
            <span> <img src="data:image/jpeg;base64,<?php echo $logoUrl2; ?>" alt="" width="50px"></span>
            <span> <img src="data:image/jpeg;base64,<?php echo $logoUrl3; ?>" alt="" width="50px"></span>
        </center>
        <!-- <p style="font-weight:bold;padding-top:10px;width:100%;text-align:center;">E. & O.E</p> -->

    </footer>
    <main>
        <?php
        $items_per_page = 15; // Number of rows per page
        $chunks = array_chunk($get_all_items, $items_per_page); // Divide items into chunks of 6
        $total = 0;
        ?>

        <?php foreach ($chunks as $page_index => $chunk): ?>
            <table style="width: 100%;
                          border-collapse: collapse;
                          min-height:380px; 
                          padding-top:5px;
                          margin-bottom:0px;
                          font-weight:bold;
                          font-size: 14px;
                          text-transform: uppercase; <?php if (strlen($terms) > 12) {
                                                            echo "margin-top: 10.8cm";
                                                        } else {
                                                            echo "margin-top:9.5cm";
                                                        } ?>">
                <thead>
                    <tr style="border-top: dashed solid;border-bottom: dashed solid;">
                        <th style="padding-top:5px;padding-bottom:5px">NO.</th>
                        <th style="width:350px">DESCRIPTION</th>
                        <th style="text-align:center !important;">QTY</th>
                        <th style="text-align:center !important;">UOM</th>
                        <th style="text-align:center !important;">UNIT-PRICE</th>
                        <th style="text-align:center !important;">AMOUNT</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: dashed solid;">
                    <?php if ($page_index === 0): ?>
                        <!-- Content specific to the first page -->
                        <tr>
                            <td></td>
                            <td style="width: 350px;"></td>
                            <td style="text-align: center;"></td>
                            <td></td>
                            <td style="text-align: center; vertical-align: top;">SGD</td>
                            <td style="text-align: center; vertical-align: top;">SGD</td>
                        </tr>
                        <tr>
                            <td></td>
                            <!-- <td style="width: 350px;"><?php echo $tasks; ?></td> -->
                            <td style="text-align: center;"></td>
                            <td></td>
                            <td style="text-align: center; vertical-align: top;"></td>
                            <td style="text-align: center; vertical-align: top;"></td>
                        </tr>
                    <?php endif; ?>
                    <?php
                    $i = $page_index * $items_per_page; // Adjust row numbering per page
                    foreach ($chunk as $item):
                        $i++;
                        $row_total = ((float)$item->item_qtn ?? 0) * ((float)$item->rate ?? 0);
                        $total += $row_total;
                    ?>
                        <tr>
                            <td><?= str_pad($i, 3, '0', STR_PAD_LEFT) ?></td>
                            <td style="width:350px;"><?= ($item->item_type == 'product') ? $item->product_name : $item->job_description ?></td>
                            <td style="text-align:center"><?= $item->item_qtn ?></td>
                            <td style="text-align:center !important;"><?= ($item->item_type == 'product' && empty($item->unit)) ? $item->std_uom : $item->unit ?></td>
                            <td style="text-align:center"><?= $item->rate ?></td>
                            <td style="text-align:center"><?= $row_total ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($page_index + 1 === count($chunks)): ?>
                        <!-- Content specific to the last page inside -->
                    <?php endif; ?>
                </tbody>

                <?php if ($page_index + 1 === count($chunks)): ?>
                    <tfoot style="border-bottom: dashed solid;font-weight:bold;">
                        <tr>
                            <td></td>
                            <td style="width:350px"></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">SUB-TOTAL</td>
                            <td style="text-align:center"><?php echo $sub_total1 ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="width:350px"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="width:350px"></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">ADD <?php echo $gst ?>% GST</td>
                            <td style="text-align:center"><?php echo $gst_value ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="width:350px"></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">TOTAL</td>
                            <td style="text-align:center"><?php echo $sub_total1 ?></td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>

            <?php if ($page_index + 1 < count($chunks)): ?>
                <!-- Page break only between pages, not after the last page -->
                <div style="page-break-inside: avoid;"></div>
            <?php endif; ?>
        <?php endforeach; ?>

        <p style="font-weight:bold; text-align:left;">
            SINGAPORE DOLLARS <?php echo strtoupper(ucwords($this->Xin_model->convertNumberToWord($total1))) ?>
        </p>

        <table style="text-transform: normal!important;font-weight:bold; font-size: 12px !important;  font-family: Arial !important; width: 100%;">
            <tr>
                <td style="width:50%; vertical-align:top; font-size: 11.3px !important; line-height: 1.2;"><?php echo $cterm ?></td>
                <td style="width:50%; vertical-align:top; text-align:right; padding-top: 200px;">This is a computer-generated document. No signature required.</td>
            </tr>
        </table>

    </main>
</body>

</html>