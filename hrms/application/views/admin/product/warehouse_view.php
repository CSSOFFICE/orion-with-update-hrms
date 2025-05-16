<div class="nav-tabs-custom mb-4">
    <ul class="nav nav-tabs">
        <?php foreach ($get_category as $category) { ?>
            <li class="nav-item active"> <a class="nav-link show" data-toggle="tab" href="#category_<?php echo $category->category_id; ?>"><?php echo $category->category; ?></a> </li>
       <?php } ?>
        <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_profile_picture">Profile Picture</a>
                </li>
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#xin_employee_set_salary">Set Salary</a>
                </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_leaves">Leaves</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_core_hr">Core HR</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_projects">Projects &amp; Tasks</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_payslips">Payslips</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#xin_claims">Claims</a> </li> -->
    </ul>
    
    <div class="tab-content">
    <?php 
        $i=0;
        foreach ($get_category as $categories) { ?>
        <div class="tab-pane animated fadeInRight" id="category_<?php echo $categories->category_id; ?>">
            <div class="card-body">
                <table class="datatables-demo table table-striped table-bordered" id="xin_table">
                    <tr>
                        
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>UOM</th>
                        <th>Location</th>
                        <th>Price</th>

                    </tr>
                    <tr>
                        <td><?php echo $categories->product_name; ?></td>
                        <td><?php echo $categories->stock_qtn; ?></td>
                        <td><?php echo $categories->std_uom; ?></td>
                        <td><?php echo $categories->location; ?></td>
                        <td><?php echo $categories->cost_price; ?></td>

                    </tr>
                </table>
            </div>



        </div>
        <?php 
        $i++;
        } 
        ?>
    </div>
   