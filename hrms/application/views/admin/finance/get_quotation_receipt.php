<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Invoice    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@900&display=swap" rel="stylesheet">
    <style>
        .invoice-box {

            max-width: 800px;
            margin: auto;
            padding: 0px 120px 0px;
            border: 1px solid #eee;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
            /* line-height: 24px; */
            /* font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; */
            color: #555;
        }

        .fw-6 {
            font-weight: 600 !important;
        }

        .text-kreative {
            color: #bf5000;
        }

        .fs-12 {
            font-size: 12px;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fw-8 {
            font-weight: 800;
        }

        .fw-9 {
            font-weight: 900;
        }

        .empty-header,
        .empty-footer {
            height: 138px
        }

        .header,
        .footer {
           
            position: fixed;
            height: 138px !important;
            top: 0;
        }

        .header,
        .header-space,
        .footer,
        .footer-space {
            height: 130px;
        }

        .header {
            position: fixed;
            top: 0;
        }

        .footer {
            position: fixed;
            bottom: 0;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table>
           
            <tbody>
                <tr>
                    <td>
                        <div class="content">


                            <div style="text-align:center;">
                            <img src="<?php echo site_url('uploads/logo/picotech_logo.png')?>" class="img-fluid" width="300px">

                            </div>
<br>
<br>
                            <div class="d-flex justify-content-between">
                                <div class="text-black">
                                    <span class="fs-12">
                                    <p class="text-dark mb-2">

                                        Quotation Created Date: <a href="#" class="text-decoration-none"><?php echo $created_datetime; ?></a>
                                    </p>
                                    </span>
                                </div>
                                <div class="text-end text-black">
                                    <span class="fs-12">Our Ref:<?php echo $quotation_no; ?></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p class="text-dark mb-0">                                        
                                        <strong><?php if($client_company_name){echo $f_name;}else if($client_company_name){echo $client_company_name;} ?></strong>
                                    </p>
                                    
                                    <p class="mb-0" style="line-height: 1rem;"><?php echo $address; ?> </p>
                                    <!-- <p class="mb-0" style="line-height: 1rem;">#02-07 Entrepreneur Business center </p>
                                    <p class="" style="line-height: 1rem;"> Singapore</p> -->


                                </div>

                            </div>
                            <div class="row">

                                <p class="text-dark mb-2">
                                    Person In Charge : <?php echo $quote_pic; ?>
                                </p>


                                <p class="text-dark mb-2">
                                    Email : <a href="#" class="text-decoration-none"><?php echo $quote_email; ?></a>
                                </p>

                                <p class="text-dark mb-2">
                                    Person In Charge Phone : <a href="#" class="text-decoration-none"><?php echo $quote_phone; ?></a>
                                </p>

                            </div>
                            <div class="d-flex">

                                <p class="text-dark">
                                    Dear Sir / Mdm :
                                </p>

                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-black text-decoration-underline fw-6" style="font-size: 17px;">
                                       <?php echo $project_name; ?></h4>
                                       <h5 class="text-danger">Validity Upto: <?php echo $q_date;?></h5>
                                       
                                       
                                       <?php if(!empty($get_all_task)){?>
                                       <table class="table table-stripe">
                                            <tr>
                                                <th>Task Name</th>
                                                <th>Task Description</th>
                                            </tr>
                                            <?php foreach($get_all_task as $tasks){ ?>
                                            <tr>
                                                <td>
                                                    <?php echo $tasks->task; ?>
                                                </td>
                                                <td>
                                                    <?php echo $tasks->task_description; ?>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            </table>
                                            <?php }?>

                                            <table class="table table-stripe">
                                            <tr>
                                                <th>Product</th>
                                                <th>Description</th>
                                                <th>Detail</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Unit Rate</th>
                                                <th>Gross Amount</th>
                                            </tr>
                                            <?php foreach($get_all_subtasks as $subtasks){?>
                                            <tr>                                                
                                                <td><?php echo $subtasks->product; ?></td>
                                                <td><?php echo $subtasks->description; ?></td>
                                                <td><?php echo $subtasks->detail; ?></td>
                                                <?php foreach($all_units->result() as $units){?>
                                                <td>
                                                    <?php if($units->unit_id == $subtasks->unit_id){
                                                    $unit= $units->unit;
                                                    echo $unit;}?>
                                                </td>
                                                <?php }?>
                                                
                                                <td> <?php echo $subtasks->qtn;?></td>
                                                <td> <?php echo $subtasks->unit_rate.'/'.$unit;?></td>
                                                <td> <?php echo $subtasks->unit_rate * $subtasks->qtn;?></td>
                                            </tr>
                                            <?php }?>
                                            <tr colspan="6">
                                                <td>Total Ammount</td>
                                                <td><?php echo $total_item_amount?></td>
                                                <td>GST <?php echo $gst."(%)";?></td>
                                                <td>Grand Total <?php echo $total_item_amount+$gst_value?></td>
                                            </tr>
                                            
                                       </table>
                                      
                                    
                                    

                                    <h4 class="text-black fw-6" style="font-size: 18px;">
                                        Terms and Conditions</h4>
                                        <p class="text-black" style="font-size: 16px;">
                                        <?php echo $term_condition; ?></p>
                                    <!-- <p class="text-black" style="font-size: 16px;">
                                        We trust that the above is in order and look forward to being of service to you. Thank you
                                    </p>   -->
                                    <p class="text-black" style="font-size: 16px;">
                                        Yours faithfully
                                    </p>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="sign" style="height: 50px;">

                                            </div>
                                            <div class="for">
                                               <span class="text-black">Chen XinXin</span>
                                            </div>
                                        </div>
                                        <div class="col-5">

                                        </div>
                                        <div class="col-4">
                                            <div class="sign" style="height: 50px; border-bottom: 1px solid #000;">

                                            </div>
                                            <div class="for d-flex align-items-center justify-content-center">
                                               <span class="text-black">Confirm, chop & sign</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <div class="footer-space">&nbsp;</div>
                    </td>
                </tr>
            </tfoot>
        </table>
        
    </div>


    <script>
        window.onload = function () {
    window.print();
}
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>