<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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
    <?php $session = $this->session->userdata('username'); ?>
    <?php $get_animate = $this->Xin_model->get_content_animate(); ?>
    <?php $role_resources_ids = $this->Xin_model->user_role_resource(); ?>
    <div class="invoice-container" style="background-color:white;padding:5rem;border:1px solid black; max-width:1000px; margin:0 auto;">


        <?php if (in_array('2913', $role_resources_ids)) { ?>
            <div style="text-align: end;">
                <?php if ($status == "Draft" || $status == "Waiting for Confirmation") { ?>
                    <button type="button" class="btn btn-primary waves-effect waves-light icon-btn btn-xs" onclick="conPO()">Confirm</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light icon-btn btn-xs" onclick="rejPO()">Reject</button>
                <?php } else if ($status == "Approved") { ?>
                    <label class="text-success"><?php echo $status; ?></label>
                <?php } else if ($status == "Rejected") { ?>
                    <label class="text-danger"><?php echo $status; ?></label>
                <?php } ?>
            </div>
        <?php } ?>
        <h2 style="margin: 0; color: #333399; text-align: left;">
            <center>PURCHASE ORDER
            </center>
        </h2>
        <br>
        <table style="width: 100%;  font-family: Arial, sans-serif;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <!-- <h2 style="margin: 0; color: #333399; text-align: left;">PURCHASE ORDER</h2> -->
                    <img src="<?php echo base_url('uploads/logo/logo_120_final.png') ?>" alt="" style="width: 200px; height: 100px; float: left;">


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
                    <td style="text-align: left;" class="editable-text" data-field="quote_reference">
                        <b id="quote_text">
                            <?php echo !empty($amd_line) ? htmlspecialchars($amd_line) : "Click Here to Edit"; ?>
                        </b>
                    </td>

                    <script>
                        $(document).ready(function() {
                            $(".editable-text").on("click", function() {
                                var tdElement = $(this);

                                // Prevent multiple input fields
                                if (tdElement.find("input").length > 0) {
                                    return;
                                }

                                var currentText = $("#quote_text").text().trim();
                                var inputField = `<input type='text' id='edit_input' value='${currentText}' style='width: 100%;'>`;
                                tdElement.html(inputField);
                                $("#edit_input").focus();
                            });

                            $(document).on("blur", "#edit_input", function() {
                                var newValue = $(this).val().trim();
                                var tdElement = $(".editable-text");
                                var defaultText = "Click Here to Edit"; // Default text

                                // If blank, set to default text
                                if (newValue === "") {
                                    newValue = defaultText;
                                }

                                $.ajax({
                                    url: base_url + "/save_amd_line/" + <?php echo $this->uri->segment(4) ?>,
                                    type: "POST",
                                    data: {
                                        quote_text: newValue
                                    },
                                    success: function(response) {
                                        tdElement.html(`<b id="quote_text">${newValue}</b>`);
                                    },
                                    error: function() {
                                        tdElement.html(`<b id="quote_text">${defaultText}</b>`); // Restore default text on error
                                    }
                                });
                            });
                        });
                    </script>
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
                        <b>Milestone: </b>
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
                        <b><?php echo $description_name ?></b><br><br>
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
                            <td style="border: 1px solid #000;"><?php echo ($items->prd_uom_from_prq) ? $items->prd_uom_from_prq : $items->std_uom; ?></td>
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
                            <td style="border: 1px solid #000;"><?php echo $i; ?></td>
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
                                        <?php echo number_format($gst_amount, 2); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000;"></td>


                    <td style="border: 1px solid #000; text-align: left;">
                        Contact:
                        <b id="contact_text" class="editable-text1" data-field="contact_name">
                            <?php echo !empty($cantactperson) ? htmlspecialchars($cantactperson) : "Click Here to Edit"; ?>
                        </b>
                    </td>

                    <script>
                        $(document).ready(function() {
                            $(".editable-text1").on("click", function() {
                                var bElement = $(this); // Store reference to the clicked element

                                // Prevent multiple input fields
                                if (bElement.find("input").length > 0) {
                                    return;
                                }

                                var currentText = bElement.text().trim();
                                if (currentText === "Click Here to Edit") {
                                    currentText = ""; // Show empty input if default text
                                }

                                var inputField = `<input type='text' id='edit_contact' value='${currentText}' style='width: 100%;'>`;
                                bElement.html(inputField);
                                $("#edit_contact").focus();
                            });

                            $(document).on("blur", "#edit_contact", function() {
                                var newValue = $(this).val().trim();
                                var bElement = $(this).parent(); // Selects the parent <b> element
                                var defaultText = "Click Here to Edit"; // Default text

                                if (newValue === "") {
                                    newValue = defaultText; // Set default text if blank
                                }

                                $.ajax({
                                    url: base_url + "/save_contact_name/" + <?php echo $this->uri->segment(4) ?>,
                                    type: "POST",
                                    data: {
                                        contact_text: newValue
                                    },
                                    success: function(response) {
                                        bElement.html(newValue);
                                    },
                                    error: function() {
                                        bElement.html(defaultText); // Restore default text on error
                                    }
                                });
                            });
                        });
                    </script>




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
                        <!-- Office Address: <?php //echo $company_info[0]->address_1; 
                                                ?><br>
                        <?php //echo $company_info[0]->address_2; 
                        ?> <br>
                        <?php //echo $company_info[0]->city 
                        ?><br>
                        <?php //echo $company_info[0]->state . " " . $company_info[0]->zipcode; 
                        ?><br>
                        Email Address:<?php //echo $company_info[0]->email; 
                                        ?><br>
                        Company Registration No: 200809511K <br>
                        GST Reg. No.: 2008-09511-K <br> -->
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
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
        <p>This is a computer generated document. No signature is required.</p>
        <footer>
            <center>

                <?php
                $query = $this->db->get('xin_quo')->result();

                $logoUrl = base_url('uploads/quo/' . $query[0]->logo1);
                $logoUrl2 = base_url('uploads/quo/' . $query[0]->logo2);
                $logoUrl3 = base_url('uploads/quo/' . $query[0]->logo3);
             
                ?>
                <span> <img src="<?php echo $logoUrl; ?>" alt="" width="70px"></span>
                <span> <img src="<?php echo $logoUrl2; ?>" alt="" width="70px"></span>
                <span> <img src="<?php echo $logoUrl3; ?>" alt="" width="70px"></span>
               

            </center>
        </footer>

    </div>
    <script>
        function conPO() {
            $.ajax({
                url: base_url + "/con_po/",
                type: "POST",
                data: 'jd=1&is_ajax=1&purchase_order_id=' + <?php echo $this->uri->segment(4) ?>,
                success: function(response) {
                    console.log(response);
                    if (response) {

                        toastr.success(response.result)
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);

                    } else {
                        toastr.error(response.error)

                    }
                }
            });
        }

        function rejPO() {
            $.ajax({
                url: base_url + "/rej_po/",
                type: "POST",
                data: 'jd=1&is_ajax=1&purchase_order_id=' + <?php echo $this->uri->segment(4) ?>,
                success: function(response) {
                    console.log(response);
                    if (response) {

                        toastr.success(response.result)
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);

                    } else {
                        toastr.error(response.error)

                    }
                }
            });
        }
    </script>
</body>

</html>