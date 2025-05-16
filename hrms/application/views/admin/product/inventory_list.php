<?php $session = $this->session->userdata('username'); ?>
<?php $get_animate = $this->Xin_model->get_content_animate(); ?>
<div id="filter_hrsale" class="collapse add-formd <?php echo $get_animate; ?>" data-parent="#accordion" style="">
    <div class="box mb-4 <?php echo $get_animate; ?>">
        <div class="box-header  with-border">
            <h3 class="box-title">Inventory</h3>

        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <?php $attributes = array('name' => 'ihr_report', 'id' => 'ihr_report', 'autocomplete' => 'off', 'class' => 'add form-hrm'); ?>
                    <?php $hidden = array('user_id' => $session['user_id']); ?>
                    <?php echo form_open('admin/employees/employees_list', $attributes, $hidden); ?>
                    <?php
                    $data = array(
                        'type'        => 'hidden',
                        'name'        => 'date_format',
                        'id'          => 'date_format',
                        'value'       => $this->Xin_model->set_date_format(date('Y-m-d')),
                        'class'       => 'form-control',
                    );
                    echo form_input($data);
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="first_name">Projects</label>
                            <?php $get_all_Projects = $this->db->get('projects')->result() 
                            ?>
                            <select class="form-control" name="filter_project" id="filter_project"
                                data-plugin="select_hrm"
                                data-placeholder="Projects">
                                <option value="">Select</option>
                                <?php foreach ($get_all_Projects as $project) { 
                                ?>
                                    <option value="<?php echo $project->project_id ?>"><?php echo $project->project_title ?>
                                    </option>
                                <?php } 
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_type">Type</label>
                            <select class="form-control" name="type" id="filter_type" data-plugin="select_hrm" data-placeholder="Type">
                                <option value="">Select</option>
                                <option value="Stock Out">Stock Out</option>
                                <option value="Stock Purchase">Stock Purchase</option>
                                <option value="Stock Return">Stock Return</option>
                            </select>
                        </div>

                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                        <div class="col-md-1"><label
                                for="designation">&nbsp;</label><?php echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_get'))); ?>
                        </div>
                    </div>
                    <!--<div class="form-actions box-footer"> <?php //echo form_button(array('name' => 'hrsale_form', 'type' => 'submit', 'class' => $this->Xin_model->form_button_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> ' . $this->lang->line('xin_get'))); 
                                                                ?> </div>-->
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box <?php echo $get_animate; ?>">
    <div class="box-header with-border">
        <h3 class="box-title"> <?php echo $this->lang->line('xin_list_all'); ?>
            <?php echo "Inventory Data"; ?> </h3>
        <div class="box-tools pull-right">

            <button type="button" class="btn btn-xs btn-primary" id="stockfilter"> <span class="fa fa-filter"></span>
                <?php echo $this->lang->line('xin_filter'); ?></button>

        </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    $(document).ready(function() {
        // Initially hide the filter form
        $('#filter_hrsale').hide();

        // Handle click event to toggle visibility and update button text
        $('#stockfilter').on('click', function() {
            $('#filter_hrsale').toggle();
            const isVisible = $('#filter_hrsale').is(':visible');
            $(this).text(isVisible ? 'Hide Filter' : 'Show Filter');
        });
    });

    // Add event listener for form submission
    document.getElementById('ihr_report').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get the selected type value
        const selectedType = document.getElementById('filter_type').value;
        const filter_project = document.getElementById('filter_project').value;
        console.log(selectedType);
        // Determine which function to call based on the selected type
        if (selectedType === "Stock Out") {
            stockOut(filter_project); // Call the Stock Out function
        } else if (selectedType === "Stock Purchase") {
            stockPurchase(filter_project); // Call the Stock Purchase function
        } else if (selectedType === "Stock Return") {
            stockReturn(filter_project); // Call the Stock Purchase function
        } 
        else {
            alert('Please select a valid type before submitting.');
        }
    });

    // Stock Out function
    function stockOut(filter_project) {
        fetch(base_url + '/get_stock_out_data/'+filter_project, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                // Your existing Stock Out data handling logic here
                console.log("Stock Out Data", data);
                generateExcelReport(data, "STOCK OUT TO PROJECT", "stock_out_report.xlsx");
            })
            .catch(error => console.error("Error fetching stock out data:", error));
    }

    // Stock Return function
    function stockReturn(filter_project) {
        fetch(base_url + '/get_stock_return_data/'+filter_project, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                // Your existing Stock Return data handling logic here
                console.log("Stock Return Data", data);
                generateExcelReport(data, "STOCK RETURN FROM PROJECT", "stock_return_report.xlsx");
            })
            .catch(error => console.error("Error fetching stock return data:", error));
    }

    // Stock Purchase function
    function stockPurchase(filter_project) {
        fetch(base_url + '/get_stock_purchase_data/'+filter_project, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                // Your existing Stock Purchase data handling logic here
                console.log("Stock Purchase Data", data);
                generateExcelReport(data, "PURCHASES FOR PROJECT", "stock_purchase_report.xlsx");
            })
            .catch(error => console.error("Error fetching stock purchase data:", error));
    }

    // Utility function to sanitize sheet names
    function sanitizeSheetName(name) {
        return name.replace(/[:\\\/\?\*\[\]]/g, " "); // Replace invalid characters with a space
    }

    // Generalized Excel report generation function
    function generateExcelReport(data, mainHeader, filename) {
        const subHeaders = [
            "#",
            "BAR CODE",
            "ITEM NAME",
            "QTY",
            "UOM",
            "NAME OF WORKER (TAKEN)",
            "DATE TAKEN",
            "TO SITE"
        ];

        const excelData = [
            [mainHeader].concat(new Array(subHeaders.length - 1).fill("")), // Merge first row
            subHeaders, // Second row with actual column headers
            ...data.map((item, index) => [
                index + 1,
                item.product_name,
                item.product_name,
                item.qtn,
                item.std_uom,
                `${item.first_name} ${item.last_name}`,
                item.created_date,
                item.to_description
            ])
        ];

        const ws = XLSX.utils.aoa_to_sheet(excelData);
        ws['!merges'] = [{
            s: {
                r: 0,
                c: 0
            }, // Start of merged cells
            e: {
                r: 0,
                c: subHeaders.length - 1
            } // End of merged cells
        }];
        ws['!cols'] = [30, 100, 200, 50, 50, 150, 100, 150].map(width => ({
            wpx: width
        }));

        // Sanitize sheet name before appending it
        const sanitizedSheetName = sanitizeSheetName(mainHeader);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, sanitizedSheetName); // Use sanitized name
        XLSX.writeFile(wb, filename);
    }
</script>