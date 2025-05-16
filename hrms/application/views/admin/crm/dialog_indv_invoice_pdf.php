<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="xin_custom.css">
    <link rel="stylesheet" href="xin_hrsale_custom.css"> -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<style type="text/css">
     .invoice-box{
    max-width:800px;
    margin:auto;
    padding:30px;
    border:1px solid #eee;
    box-shadow:0 0 10px rgba(0, 0, 0, .15);
    font-size:16px;
    line-height:24px;
    font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    color:#555;
}

.invoice-box table{
    width:100%;
    line-height:inherit;
    text-align:left;
}

.invoice-box table td{
    padding:5px;
    vertical-align:top;
}

.invoice-box table tr td:nth-child(2){
    text-align:right;
}

.invoice-box table tr.top table td{
    padding-bottom:20px;
}

.invoice-box table tr.top table td.title{
    font-size:45px;
    line-height:45px;
    color:#333;
}

.invoice-box table tr.information table td{
    padding-bottom:40px;
}

.invoice-box table tr.heading td{
    background:#eee;
    border-bottom:1px solid #ddd;
    font-weight:bold;
}

.invoice-box table tr.details td{
    padding-bottom:20px;
}

.invoice-box table tr.item td{
    border-bottom:1px solid #eee;
}

.invoice-box table tr.item.last td{
    border-bottom:none;
}

.invoice-box table tr.total td:nth-child(2){
    border-top:2px solid #eee;
    font-weight:bold;
}

@media only screen and (max-width: 600px) {
    .invoice-box table tr.top table td{
        width:100%;
        display:block;
        text-align:center;
    }
    
    .invoice-box table tr.information table td{
        width:100%;
        display:block;
        text-align:center;
    }
}

/*==================================================
=            Bootstrap 3 Media Queries             =
==================================================*/




/*==========  Mobile First Method  ==========*/

/* Custom, iPhone Retina */ 
@media only screen and (min-width : 320px) {
    
}

/* Extra Small Devices, Phones */ 
@media only screen and (min-width : 480px) {

}

/* Small Devices, Tablets */
@media only screen and (min-width : 768px) {

}

/* Medium Devices, Desktops */
@media only screen and (min-width : 992px) {

}

/* Large Devices, Wide Screens */
@media only screen and (min-width : 1200px) {

}



/*==========  Non-Mobile First Method  ==========*/

/* Large Devices, Wide Screens */
@media only screen and (max-width : 1200px) {

}

/* Medium Devices, Desktops */
@media only screen and (max-width : 992px) {

}

/* Small Devices, Tablets */
@media only screen and (max-width : 768px) {

}

/* Extra Small Devices, Phones */ 
@media only screen and (max-width : 480px) {

}

/* Custom, iPhone Retina */ 
@media only screen and (max-width : 320px) {
    
}



/*=====================================================
=            Bootstrap 2.3.2 Media Queries            =
=====================================================*/
@media only screen and (max-width : 1200px) {

}

@media only screen and (max-width : 979px) {

}

@media only screen and (max-width : 767px) {

}

@media only screen and (max-width : 480px) {

}

@media only screen and (max-width : 320px) {

}


/* default styles here for older browsers. 
   I tend to go for a 600px - 960px width max but using percentages
*/
@media only screen and (min-width:960px){
    /* styles for browsers larger than 960px; */
}
@media only screen and (min-width:1440px){
    /* styles for browsers larger than 1440px; */
}
@media only screen and (min-width:2000px){
    /* for sumo sized (mac) screens */
}
@media only screen and (max-device-width:480px){
   /* styles for mobile browsers smaller than 480px; (iPhone) */
}
@media only screen and (device-width:768px){
   /* default iPad screens */
}
/* different techniques for iPad screening */
@media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait) {
  /* For portrait layouts only */
}

@media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape) {
  /* For landscape layouts only */
}

    </style>
    </head>

<body>
    <div class="invoice-box">
        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td class="text-center">
                    <!-- <h2 class="m-0 mb-1" style="font-weight: 600;">
                        <span style="color: #1463a5;">P</span>
                        <span style="color: #1463a5;">i</span>
                        <span style="color: #1463a5;">c</span>
                        <span style="color: #1463a5;">o</span>
                        <span style="color: #1463a5;">t</span>
                        <span style="color: #1463a5;">e</span>
                        <span style="color: #1463a5;">c</span>
                        <span style="color: #1463a5;">h</span> &nbsp;
                        <span style="color: #1463a5;">S</span>
                        <span style="color: #1463a5;">o</span>
                        <span style="color: #1463a5;">l</span>
                        <span style="color: #1463a5;">u</span>
                        <span style="color: #1463a5;">t</span>
                        <span style="color: #1463a5 ;">i</span>
                        <span style="color: #1463a5;">o</span>&nbsp;
                        <span style="color: #1463a5;">n</span> 
                        <span style="color: #1463a5;">s</span>
                    </h2> -->
                    <img src="<?php echo site_url('uploads/logo/picotech_logo.png')?>" class="img-fluid" width="300px">
                    <p class="m-0" style="font-size: 12px;"><?php echo $settings[0]->address_1;?> <?php echo $settings[0]->address_2;?> <?php echo $settings[0]->state;?> <?php echo $settings[0]->city;?> <?php echo $settings[0]->zipcode;?> <?php echo $settings[0]->phone;?></p>
                    <p class="mb-1" style="font-size: 12px;">REG.NO: <?php echo $invoice_settings[0]->invoice_reg_no;?></p>
                    <p class="mb-1" style="font-size: 12px; font-weight: 800; color: #000;">(GST REG.NO: <?php echo $invoice_settings[0]->invoice_gst_no;?>)</p>


                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 40%; padding: 0;">
                    <h6 style="font-weight: 800; color: #000;">TAX INVOICE</h6>
                </td>
                <td style="width: 60%; text-align: left;  padding: 0;">
                    <div class="row">
                        <div class="col-4 p-0 text-center">
                            <h6><span style="font-weight: 800; color: #000;">GST REG.NO:</span></h6>
                        </div>
                        <div class="col-8 p-0">
                            <h6><span style="font-weight:600;  text-decoration: underline;"><?php echo $invoice_settings[0]->invoice_gst_no;?></span></h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 p-0 text-center">
                            <h6><span style="font-weight: 800; color: #000;">INVOICE NO:</span></h6>
                        </div>
                        <div class="col-8 p-0">
                            <h6><span style="font-weight:600;  text-decoration: underline;"><?php echo "INV00".$invoice_no; ?></span></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 p-0 text-center">
                            <h6><span style="font-weight: 800; color: #000;">INVOICE DATE:</span></h6>
                        </div>
                        <div class="col-8 p-0">
                            <h6><span style="font-weight:600;  text-decoration: underline;"><?php echo $invoice_date; ?></span></h6>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-4 p-0 text-center">
                            <h6><span style="font-weight: 800; color: #000;">TERMS:</span></h6>
                        </div>
                        <div class="col-8 p-0">
                            <h6><span style="font-weight:600;  text-decoration: underline;"><?php //echo $terms; ?></span></h6>
                        </div>
                    </div> -->

                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2" style="padding: 0;">
                    <table>
                        <tr>
                            <td>
                                <span style="color: #010100;">To : </span>


                                <div class="content text-black" style="margin-left: 3.5rem;">
                                    <b class="text-black">M/S</b><br>
                                    <b class="text-black"><?php echo $client_company_name; ?></b><br>
                                   <?php echo $email; ?> <br>
                                   <!-- <?php //echo $client_phone; ?><br> <br> -->

                                    ATTN : <?php echo $attn_name; ?>
                                </div>
                            </td>

                            <td>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        <table class="table-bordered border-black">
            <thead class="text-center text-black fs-5">
                <th>
                    S/NO
                </th>
                <th>
                    PROJECT NAME
                </th>
                <th>
                    DESCRIPTION
                </th>
                <th>
                    AMOUNT
                </th>
            </thead>
            <tbody>
                <?php 
                $i=1;
               // foreach($get_all_items as $items){ ?>
                <tr>
                    <td style="text-align: center; padding: 0; position: relative;">
                    <span class="text-black" style="font-weight: 600;">
                           <?php echo "1"; ?>
                        </span>
                    </td>
                    <td style="text-align: center; padding: 0; position: relative;">
                        <span class="text-black" style="font-weight: 600; text-decoration: underline;">
                            <?php echo $project_name?>
                        </span>
                    </td>
                    <td style="text-align: center; padding: 0; position: relative;">
                        <span class="text-black" style="font-weight: 600; text-decoration: underline;">
                           <?php echo $project_des; ?>
                        </span>

                    </td>
                    <td style="text-align: center; padding: 0; position: relative;">
                    <?php echo number_format($quote_amnt,2); ?>

                    </td>
                </tr>
                <?php
                $i++;
             //}
              ?>
            </tbody>
        </table>
        <table class="border-top-0">
            <tr>
                <td style="width: 61%; padding: 0;">

                </td>
                <td style="width: 39%; padding: 0;">
                    <table class="table-bordered border-black border-top-0" style="border: 2px solid;">
                        <!-- <tr>
                            <td>
                                <span class="text-black">
                                    <span style="font-weight: 600; text-decoration: underline;">ADD</span>GST (<?php //echo $gst;?>)
                                </span>
                            </td>
                            <td>
                                <div class="amount d-flex justify-content-between" style="font-weight: 600;">
                                    <span class="text-dark">$</span> <span class="text-dark"><?php //echo $gst_value; ?></span>
                                </div>
                            </td>
                        </tr> -->
                        <tr>
                            <td>
                                <span class="text-black">
                                    <span style="font-weight: 600;">Total Claim :
                                    </span>
                            </td>
                            <td>
                                <div class="amount d-flex justify-content-between" style="font-weight: 600;">
                                    <span class="text-dark">$</span> <span class="text-dark"><?php echo number_format($quote_amnt,2); ?></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="col-12">
                <span style="font-weight: 600; color: #000;">SINGAPORE DOLLARS :</span> <br>
                <span style="font-weight: 600; color: #000;">( <?php echo ucwords($this->Xin_model->convertNumberToWord($quote_amnt)); ?> Only )</span>
            </div>

        </div>
        <div class="row mb-2">
            <div class="col-5">
                <div class="sign" style="height: 150px; border-bottom: 1px solid #000;">

                </div>
                <div class="for d-flex justify-content-between">
                   <span>FOR</span>&nbsp;<span style="font-weight: 600; color: #000 ; font-size: 16px; font-style: italic;">PICOTECH SOLUTIONS</span>
                </div>
                
            </div>
        </div>
        <div class="list">
            <ul class="ps-3" style="font-size: 12px;">
                <li> <span>AMOUNT PAYABLE INCLUDES GST</span></li>
               <li>
                <span>
                    PAYMENT SHOULD BE MADE BY CROSSED CHEQUE TO : <span style="font-weight: 600; color: #000 ; font-size: 16px; font-style: italic;">PICOTECH SOLUTIONS</span> 
                </span></li>
            </ul>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
        <script>
        window.onload = function () {
    window.print();
}
    </script>
</body>

</html>