<style>
    /* body {
        font-family: "Courier New", Courier, monospace !important;
        font-size: 15px;
        align-items: center;
        color: black !important;
    } */

    .content {
        width: 50% !important;
    }

    .invoice {
        width: 100%;
        font-family: "Courier New", Courier, monospace !important;
        font-size: 15px;
        align-items: center;
        color: black !important;
        /* height: 100%; */
        /* margin: 80px auto; */
    }

    .invoice-header {
        text-align: center;
        padding: 10px 0px 0px 5px;
        margin-bottom: 20px;

        /* border-bottom: 2px solid #1B60A5; */
    }

    .logo {
        width: 160px;
    }

    .images {
        display: flex;
        justify-content: space-between;
        gap: 6px;

    }

    .image {
        width: 45px;
        height: 100px;
        object-fit: contain;
    }

    /* table {
        width: 100%;
        border-collapse: collapse;
        border:none ;
    }

    th,
    td {
        text-align: center;
        border: 1px solid #000;
    }

    th {
        font-size: 12px;
    } */

    /* .main-table th,
    .main-table td {
        border: 2px solid #000;
    }

    .main-table tfoot td {
        padding: 10px;
        border-left: none;
        border-right: none;
    } */

    .sender-receiver {
        display: flex;
        padding-left: 3px;
        padding-right: 3px;
        margin-bottom: 1rem;
    }

    address {
        font-style: normal;
    }

    .signature-block {
        width: 30%;
        margin-left: auto;
    }
</style>
<div class="invoice">

        <!-- <h3>INVOICE</h3> -->

        <div class="invoice-header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="float:left">
                    <img src="<?php echo base_url('uploads/logo/' . $invoice_settings[0]->invoice_logo) ?>" alt="Company Logo" class="img-fluid" style="width:200px;">
                </td>
                <td style="float:right;text-align:right;font-family:Arial, sans-serif;">
                  <?= $invoice_settings[0]->invoice_address_logo;?>
                </td>
            </tr>
        </table>

            <br>
            <?php $query = $this->db->where('client_id', $client_id)->where('d_address', 1)->get('billing_addresses')->result() ?>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 45%; vertical-align: top;">
                        <table style="width: 100%;" cellpadding=0 cellspacing=0>
                            <tr>
                                <td style="width: 5%;vertical-align: top;"><strong>TO</strong></td>
                                <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                                <td style="width: 75%;text-align: left;"><?php echo ($client_company_name) ?? $f_name ?><br>
                                    <?php if (!empty($query)) { ?>
                                        <?php echo $query[0]->state ?><br>
                                        <?php echo $query[0]->city ?><br>
                                        <?php echo $query[0]->street ?><br>
                                        <?php echo $query[0]->p_unit ?><br>
                                        <?php echo $query[0]->country . " " . $query[0]->zipcode ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <!-- <tr>
                            <td><br></td>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><br></td>
                        </tr> -->
                            <tr>
                                <td><br></td>
                                <td><br></td>
                            </tr>



                            <tr>
                                <td style="width: 20%;vertical-align: top;"><strong>TEL/FAX</strong></td>
                                <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                                <td style="text-align: left;"> <?php if (!empty($query)) {
                                                                    echo $query[0]->p_contact;
                                                                } ?></td>
                            </tr>

                        </table>
                    </td>
                    <td style="width: 10%; vertical-align: top;"></td>
                    <td style="width: 45%; vertical-align: top;">
                        <table style="width: 100%;font-weight:bold;" cellpadding=0 cellspacing=0>
                            <tr>
                                <td style="width: 40%; font-size:20px;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"><strong>TAX INVOICE</strong></td>
                                <td style="width: 5%; text-align: center;vertical-align: top;">:</td>
                                <td style="width: 40%; font-size:20px;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"><strong><?= $invoice_no ?></strong></td>

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
        </div>

        <table class="main-table" style="width: 100%;border-collapse: collapse;font-weight:bold;font-size: 15px !important;text-transform: uppercase; ">
            <thead style="color:black !important;font-weight:bold !important;">
                <tr style="border-top:3px dotted #000;border-bottom: 3px dotted #000;font-weight:bold;">
                    <th style="font-weight:bold;">NO.</th>
                    <th style="font-weight:bold;">DESCRIPTION</th>
                    <th style="text-align:center !important; font-weight:bold;">QTY</th>
                    <th style="text-align:center !important; font-weight:bold;">UOM</th>
                    <th style="text-align:center !important; font-weight:bold;">UNIT-PRICE</th>
                    <th style="text-align:center !important; font-weight:bold;">AMOUNT</th>
                </tr>
            </thead>
            <tbody style="border-bottom: 3px dotted #000;">
                <tr style="page-break-inside: avoid;">
                    <td></td>
                    <td style="width:350px"></td>
                    <td style="text-align:center"></td>
                    <td></td>
                    <td style="text-align:center">SGD</td>
                    <td style="text-align:center">SGD</td>
                </tr>
                <tr style="page-break-inside: avoid;">
                    <td></td>
                    <!-- <td style="width:350px"><?php echo $tasks ?></td> -->
                    <td style="text-align:center"></td>
                    <td></td>
                    <td style="text-align:center"></td>
                    <td style="text-align:center"></td>
                </tr>
                <?php $i = 0;

                foreach ($get_all_items as $item) {
                    $i++; ?>

                    <tr>
                        <td><?php echo str_pad($i, 3, '0', STR_PAD_LEFT); ?></td>
                        <td style="text-align: left;width:350px;"><?= ($item->item_type == 'product') ? $item->product_name : $item->job_description ?></td>

                        <td style="text-align:center !important;"><?= $item->item_qtn ?></td>
                        <td style="text-align:center !important;"><?= ($item->item_type == 'product' && empty($item->unit)) ? $item->std_uom : $item->unit ?></td>
                        <td style="text-align:center !important;"><?= $item->rate ?></td>
                        <td style="text-align:center !important;"><?= $item->total ?></td>
                    </tr>
                <?php } ?>
                <!-- <tr style="page-break-inside: avoid;">
                <td></td>
                <td style="width:350px">
                    bank account details <br />
                    bank name : ocbc bank <br />
                    bank account: 716928001 <br />
                    bank /branch code: 7339 / 539 <br />
                    swift code : ocbcsgsg <br />
                    paynow uen : 200809511k <br />
                </td>
                <td style="text-align:center"></td>
                <td></td>
                <td style="text-align:center;vertical-align:top;"></td>
                <td style="text-align:center;vertical-align:top;"></td>
            </tr> -->

            </tbody>
            <tfoot style="border-bottom: 3px dotted #000;font-weight:bold;">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right">SUB-TOTAL</td>
                    <td style="text-align:center"><?php echo $sub_total1 ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right">ADD <?php echo $gst ?>% GST</td>
                    <td style="text-align:center"><?php echo $gst_value ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right">TOTAL</td>
                    <td style="text-align:center"><?php echo $sub_total1 ?></td>
                </tr>
            </tfoot>
        </table>


        <p style="font-weight:bold; text-align:left;">
            SINGAPORE DOLLARS <?php echo strtoupper(ucwords($this->Xin_model->convertNumberToWord($total1))) ?>
        </p>
        <br>
        <?php $query = $this->db->get('xin_system_setting')->result(); ?>

        <table style="font-weight:bold; font-size: 12px !important; font-family: Arial !important; width: 100%;">
            <tr>
            <td style="width:50%; vertical-align:top; font-size: 11.3px !important; "><?php echo $cterm ?></td>
            <td style="width:50%; vertical-align:top; text-align:right; padding-top: 250px;">This is a computer-generated document. No signature required.</td>
            </tr>
        </table>
        <!-- <p style="font-weight:bold;width:100%;text-align:left;">E. & O.E</p> -->
        <div style="text-align: center;">

            <?php
            $query = $this->db->get('xin_quo')->result();

            $logoUrl = base_url('uploads/quo/' . $query[0]->logo1);
            $logoUrl2 = base_url('uploads/quo/' . $query[0]->logo2);
            $logoUrl3 = base_url('uploads/quo/' . $query[0]->logo3);

            ?>
            <span> <img src="<?php echo $logoUrl; ?>" alt="" width="70px"></span>
            <span> <img src="<?php echo $logoUrl2; ?>" alt="" width="70px"></span>
            <span> <img src="<?php echo $logoUrl3 ?? ''; ?>" alt="" width="70px"></span>

        </div>


</div>