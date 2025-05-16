<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (isset($_GET['jd']) && isset($_GET['product_id']) && $_GET['data'] == 'product') {
?>
    <?php $session = $this->session->userdata('username'); ?>
    <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>

    <div class="modal-header">
        <?php echo form_button(array('aria-label' => 'Close', 'data-dismiss' => 'modal', 'type' => 'button', 'class' => 'close', 'content' => '<span aria-hidden="true">×</span>')); ?>
        <h4 class="modal-title" id="edit-modal-data">Details</h4>
    </div>
    <?php $attributes = array('name' => 'edit_product', 'id' => 'edit_product', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
    <?php $hidden = array('_method' => 'EDIT', '_token' => $product_id, 'ext_name' => $product_id); ?>
    <?php echo form_open_multipart('admin/product/update/' . $product_id, $attributes, $hidden); ?>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <div class="col-md-12">
                <div class="form-group">
                    <center><label>Name: <?php echo $product_name; ?></label></center>

                </div>
                <div class="row">
                    <label>Image</label>
                 <div class="form-group">
                    <img src="<?php echo (($product_img != '') ? site_url('uploads/product/') . $product_img : site_url('uploads/product/default.jpg')) ?>" class="img-fluid" width="200px">
                    <!-- <img class="rounded-circle" width="50px" src="' . (
					(!empty($r->prd_img) && file_exists(FCPATH . 'uploads/product/' . $r->prd_img))
					? site_url('uploads/product/') . htmlspecialchars($r->prd_img)
					: site_url('uploads/product/default.jpg')
				) . '"> -->

                </div>
                <label>QR Code</label>
                <div class="form-group">
                    <?php if ($product_barcode): ?>
                        <img id="barcode-image" src="<?php echo site_url($product_barcode); ?>" class="img-fluid" width="200px">
                        <a href="<?php echo site_url($product_barcode); ?>" class="btn btn-default" download><span class="fa fa-download"></span></a>
                        <button type="button" class="btn btn-default" id="print-barcode"><span class="fa fa-print"></span></button>
                    <?php endif; ?>
                </div>   
                </div>
                <script>
document.getElementById('print-barcode').addEventListener('click', function () {
    const barcodeImage = document.getElementById('barcode-image');

    // Create a print window
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    // Write the content to the new window
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Barcodes</title>
                <style>
                    @media print {
                        @page {
                            size: A4;
                            margin: 0; /* Remove margins */
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            width: 100%;
                            height: 100vh;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        .page {
                            display: grid;
                            grid-template-columns: repeat(5, 1fr); /* 5 columns */
                            grid-template-rows: repeat(10, 1fr); /* 10 rows */
                            width: 100vw;
                            height: 100vh;
                        }
                        .logo-container {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            width: 100%;
                            height: 100%;
                        }
                        .logo-container img {
                            width: 100%;
                            height: 100%;
                            object-fit: contain;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="page">
    `);

    // Generate 50 logos in a 5x10 grid
    for (let i = 0; i < 50; i++) {
        printWindow.document.write(`
            <div class="logo-container">
                <img src="${barcodeImage.src}" alt="Barcode">
            </div>
        `);
    }

    printWindow.document.write(`
                </div>
            </body>
        </html>
    `);

    // Close the document to signal it is ready
    printWindow.document.close();

    // Add a small delay to ensure content is rendered before printing
    setTimeout(() => {
        printWindow.print();
        printWindow.close(); // Optional
    }, 250);
});


                </script>






                <div class="form-group">
                    <label>Description</label>
                    <p><?php echo $product_des; ?></p>
                </div>
                <br>
                <h3>Supplier list</h3>
                <table class="datatables-demo table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Supplier Number</th>
                            <th>Supplier Name</th>
                            <th>Supplier Phone</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($suplier_with_price as $sp) {
                            $i++; ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $sp->code ?></td>
                                <td><?php echo $sp->supplier_name ?></td>
                                <td><?php echo $sp->phone1 ?></td>
                                <td><?php echo $sp->supplier_item_price ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>

        </div>



    </div>
    <!--</div>-->
    <div class="modal-footer">
        <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => 'btn btn-secondary', 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_close'))); ?>

    </div>
    <?php echo form_close(); ?>
<script>
        $(document).ready(function() {
        $("#u_status_reason").hide();
        $('.modal-dialog').addClass('modal-xl');
        });
</script>
<?php }
?>