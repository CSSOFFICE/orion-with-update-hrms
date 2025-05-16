<?php $get_animate = $this->Xin_model->get_content_animate(); ?>

<!-- Include required libraries -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    #movement_type {
        color: black;
        /* Default color */
        background-color: #f0f0f0;
        /* Custom background */
        font-weight: bold;
    }

    #movement_type option[value="Take"] {
        color: blue;
    }

    #movement_type option[value="Return"] {
        color: green;
    }

    #movement_type option[value="Transfer"] {
        color: red;
    }

    #movement_type option[value=""] {
        color: gray;
    }
</style>

<style>
    /* Scanner Container */
    #qr-video-container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 320px;
        height: 260px;
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
        background: #000;
        overflow: hidden;
    }

    /* Video Styling */
    #qr-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Corner Borders */
    .corner-border {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 3px solid #4CAF50;
        /* Green for scanner effect */
    }

    .corner-border.top-left {
        top: 10px;
        left: 10px;
        border-right: none;
        border-bottom: none;
    }

    .corner-border.top-right {
        top: 10px;
        right: 10px;
        border-left: none;
        border-bottom: none;
    }

    .corner-border.bottom-left {
        bottom: 10px;
        left: 10px;
        border-right: none;
        border-top: none;
    }

    .corner-border.bottom-right {
        bottom: 10px;
        right: 10px;
        border-left: none;
        border-top: none;
    }

    /* Scanner Animation */
    .scanner-line {
        position: absolute;
        top: 10px;
        left: 50%;
        width: 80%;
        height: 4px;
        background: rgba(76, 175, 80, 0.8);
        border-radius: 2px;
        animation: scanning 2s linear infinite;
        transform: translateX(-50%);
    }

    @keyframes scanning {
        0% {
            top: 10px;
        }

        50% {
            top: 85%;
        }

        100% {
            top: 10px;
        }
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        #qr-video-container {
            width: 100%;
            height: auto;
        }

        .corner-border {
            width: 20px;
            height: 20px;
        }

        .scanner-line {
            width: 90%;
        }
    }

    @media (max-width: 480px) {
        #qr-video-container {
            width: 100%;
            height: 200px;
        }

        .corner-border {
            width: 15px;
            height: 15px;
        }

        .scanner-line {
            width: 95%;
        }
    }
</style>

<div class="box mb-4 <?php echo $get_animate; ?>">
    <div id="accordion">
        <div class="box-header with-border">
            <?php $get_animate = $this->Xin_model->get_content_animate(); ?>
            <h3 class="box-title">Scan QR Code</h3>
            <div class="box-tools pull-right">
                <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
                    <button type="button" class="btn btn-xs btn-primary">
                        <span class="ion ion-md-add"></span> Scan New
                    </button>
                </a>
            </div>
        </div>

        <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion">
            <div class="box-body">
                <div class="container mt-4 text-center">
                    <h4>Scan Tool/Machinery QR Code</h4>
                    <div id="qr-video-container">
                        <video id="qr-video"></video>
                        <div class="corner-border top-left"></div>
                        <div class="corner-border top-right"></div>
                        <div class="corner-border bottom-left"></div>
                        <div class="corner-border bottom-right"></div>
                        <div class="scanner-line"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title">QR Data</h3>

    </div>


    <div class="box-body">
        <!-- <button type="button" class="btn btn-info" id="export_btn">Stock Out Report</button>
        <button type="button" class="btn btn-info" id="purchase_btn">Stock Purchase Report</button> -->
        <div class="box-datatable table-responsive">
            <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                <thead>
                    <tr>
                        <th><?php echo "Sl No."; ?></th>
                        <th>Product Name</th>
                        <th>From</th>
                        <th>To</th>
                        <th style="color:green">Quantity</th>
                        <th>Movement Type</th>
                        <th>Date</th>
                        <th>Action By</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Movement Entry -->
<div class="modal fadeInRight edit-modal-data animated " id="edit-modal-data" role="dialog" aria-labelledby="edit-modal-data" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tools/Machinery Entry</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h3>
                    <p id="product_name"></p>
                    <input type="hidden" id="product_pk">
                </h3>
                <!-- Load Choices.js -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
                <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

                <!-- Dropdown -->
                <select id="movement_type" class="form-control">
                    <option value="" data-custom-properties="gray">Select Type</option>
                    <option value="Take" data-custom-properties="blue">Withdraw</option>
                    <option value="Return" data-custom-properties="green">Return</option>
                    <option value="Transfer" data-custom-properties="red">Transfer</option>
                </select>

                <!-- Style for the dropdown items -->
                <style>
                    .choices__item[data-custom-properties="blue"] {
                        color: blue;
                    }

                    .choices__item[data-custom-properties="green"] {
                        color: green;
                    }

                    .choices__item[data-custom-properties="red"] {
                        color: red;
                    }

                    .choices__item[data-custom-properties="gray"] {
                        color: gray;
                    }
                </style>

                <!-- JS to activate Choices -->
                <script>
                    const element = document.getElementById('movement_type');
                    const choices = new Choices(element, {
                        allowHTML: true,
                        itemSelectText: '',
                        shouldSort: false,
                    });

                    // Apply custom classes to dropdown list items
                    element.addEventListener('addItem', function(event) {
                        const selectedOption = event.detail.choice;
                        const items = document.querySelectorAll('.choices__item--choice');

                        items.forEach(item => {
                            item.setAttribute('data-custom-properties', selectedOption.customProperties);
                        });
                    });
                </script>


                <div class="form-group">
                    <label>From</label>
                    <select id="from_location" class="form-control">
                        <option value="">Select From</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>To</label>
                    <select id="to_location" class="form-control">
                        <option value="">Select To</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" id="quantity" class="form-control" min="1">
                </div>

                <div class="form-group">
                    <label>Worker Name</label>
                    <p id="employee_name"></p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let scanner;
        let cameras = [];
        let selectedCameraIndex = 0;

        // Function to start scanner
        function startScanner() {
            if (scanner) {
                scanner.stop(); // Ensure any previous instance is stopped
            }

            scanner = new Instascan.Scanner({
                video: document.getElementById('qr-video'),
                mirror: false // Disable mirroring of the video feed
            });

            scanner.addListener('scan', function(content) {
                scanner.stop(); // Stop scanning after scan success
                processScannedData(content);
            });

            Instascan.Camera.getCameras().then(function(availableCameras) {
                if (availableCameras.length > 0) {
                    let backCamera = availableCameras.find(cam => cam.name.toLowerCase().includes('back') || cam.facing === "environment");
                    let frontCamera = availableCameras.find(cam => cam.name.toLowerCase().includes('front') || cam.facing === "user");

                    if (backCamera) {
                        scanner.start(backCamera);
                    } else if (frontCamera) {
                        scanner.start(frontCamera);
                    } else {
                        scanner.start(availableCameras[0]); // Fallback to first available camera
                    }
                } else {
                    alert("No camera found.");
                }
            }).catch(function(e) {
                console.error("Camera access error:", e);
                alert("Error accessing camera: " + e.message);
            });
        }



        // Start scanning only when "Scan New" button is clicked
        $('.btn-primary').click(function() {
            startScanner();
        });

        function processScannedData(content) {
            try {
                let data = JSON.parse(content);
                let product_id = data.product_id;
                console.log(content);

                $.ajax({
                    url: '<?= base_url("admin/tools/check_product_exist") ?>',
                    type: 'GET',
                    data: {
                        product_id
                    },
                    success: function(response) {
                        try {
                            let data = JSON.parse(response);
                            if (data.status === 'success') {
                                $('#edit-modal-data').modal('show');
                                $('#product_name').text(data.product[0].product_name);
                                $('#product_pk').val(data.product[0].product_id);
                                $('#employee_name').text(data.employee[0].first_name + ' ' + data.employee[0].last_name);

                                let warehouseOptions = '<option value="">Select Warehouse</option>';
                                let projectOptions = '<option value="">Select Project</option>';

                                data.warehouse.forEach(w => {
                                    warehouseOptions += `<option value="${w.w_id}">${w.w_name} (Qty: ${w.quantity})</option>`;
                                });

                                data.myprojects.forEach(project => {
                                    let projectCode = project.project_code || "";
                                    projectOptions += `<option value="${project.project_id}">${project.project_title} (Code: ${projectCode})</option>`;
                                });

                                $('#movement_type').off('change').on('change', function() {
                                    let type = $(this).val();
                                    if (type === 'Take') {
                                        $('#from_location').html(warehouseOptions);
                                        $('#to_location').html(projectOptions);
                                    } else if (type === 'Return') {
                                        $('#from_location').html(projectOptions);
                                        $('#to_location').html(warehouseOptions);
                                    } else if (type === 'Transfer') {
                                        $('#from_location').html(projectOptions);
                                        $('#to_location').html(projectOptions);
                                    }
                                });

                                // Remove selected "from" project from "to" dropdown when Transfer is selected
                                $('#from_location').off('change').on('change', function() {
                                    let type = $('#movement_type').val();
                                    if (type === 'Transfer') {
                                        let selectedFromProject = $(this).val();
                                        let filteredProjectOptions = '<option value="">Select Project</option>';

                                        data.myprojects.forEach(project => {
                                            if (project.project_id !== selectedFromProject) {
                                                let projectCode = project.project_code || "";
                                                filteredProjectOptions += `<option value="${project.project_id}">${project.project_title} (Code: ${projectCode})</option>`;
                                            }
                                        });

                                        $('#to_location').html(filteredProjectOptions);
                                    }
                                });

                            } else {
                                alert(data.message);
                            }
                        } catch (e) {
                            console.error("Error parsing response:", response);
                            alert("Unexpected response format.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Submission Error:", error);
                        alert("Error while submitting.");
                    }
                });
            } catch (e) {
                console.error("Invalid QR code format", content);
                alert("Invalid QR code. Please try again.");
            }
        }


        // Submit movement data
        $('#submitBtn').click(function() {
            let product_id = $('#product_pk').val();
            let from_location = $('#from_location').val();
            let to_location = $('#to_location').val();
            let quantity = parseInt($('#quantity').val(), 10) || 0;
            let movement_type = $('#movement_type').val();

            if (!movement_type || !from_location || !to_location || !quantity) {
                alert('Please fill all fields.');
                return;
            }

            $.ajax({
                url: '<?= base_url("admin/tools/save_tool_movement") ?>',
                type: 'POST',
                data: {
                    product_id,
                    from_location,
                    to_location,
                    quantity,
                    movement_type
                },
                success: function(response) {
                    try {
                        let data = JSON.parse(response);
                        alert(data.message);
                        $('#edit-modal-data').modal('hide');
                        location.reload();
                    } catch (e) {
                        console.error("Error parsing response:", response);
                        alert("Unexpected response format.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Submission Error:", error);
                    alert("Error while submitting.");
                }
            });
        });


        // Restart scanner when modal is hidden
        $('#edit-modal-data').on('hidden.bs.modal', function() {
            $('#edit-modal-data').find('input, select').val('');
            $('#product_name').text('');
            $('#employee_name').text('');
        });
    });
</script>