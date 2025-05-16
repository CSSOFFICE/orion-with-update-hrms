<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (isset($_GET['jd']) && isset($_GET['supplier_id']) && $_GET['data'] == 'supplier') {

?>
  <?php $system = $this->Xin_model->read_setting_info(1); ?>
  <?php $session = $this->session->userdata('username'); ?>
  <?php $user_info = $this->Xin_model->read_user_info($session['user_id']); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    <h4 class="modal-title" id="edit-modal-data">Edit <?php echo $this->lang->line('xin_suppliers'); ?></h4>
  </div>
  <?php $attributes = array('name' => 'edit_supplier', 'id' => 'edit_supplier', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
  <?php $hidden = array('_method' => 'EDIT', '_token' => $_GET['supplier_id'], 'ext_name' => $supplier_name); ?>
  <?php echo form_open('admin/supplier/update/' . $_GET['supplier_id'], $attributes, $hidden); ?>
  <div class="modal-body">
    <div class="row">
      <input type="hidden" name="supplier_id" value="<?php echo $_GET['supplier_id']; ?>">
      <div id="details">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="supplier_name" value="<?php echo $supplier_name?>" placeholder="Name of Supplier">
              <input type="hidden" name="old_name" value="<?php echo $supplier_name?>">
              <input type="hidden" name="old_code" value="<?php echo $code?>">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="address_1">Address 1</label>
              <input type="text" class="form-control" name="address_1" value="<?php echo $address_1?>" placeholder="Address 1">
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="address_2">Address 2</label>
              <input type="text" class="form-control" name="address_2" value="<?php echo $address_2?>" placeholder="Address 2">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="address_3">Address 3</label>
              <input type="text" class="form-control" name="address_3" value="<?php echo $address_3?>" placeholder="Address 3">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="address_4">Address 4</label>
              <input type="text" class="form-control" name="address_4" value="<?php echo $address_4?>" placeholder="Address 4">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="contact">Contact</label>
              <input type="text" class="form-control" name="contact_person" value="<?php echo $contact_person?>" placeholder="Contact">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="tel_no_1">Phone 1</label>
              <input type="text" class="form-control" name="tel_no_1" value="<?php echo $tel_no_1?>" placeholder="Phone 1">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label for="tel_no_2">Phone 2</label>
              <input type="text" class="form-control" name="tel_no_2" value="<?php echo $tel_no_2?>" placeholder="Phone 2">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label for="fax">Fax</label>
              <input type="text" class="form-control" name="fax1" value="<?php echo $fax1?>" placeholder="Fax">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="email_id" value="<?php echo $email_id?>" placeholder="Email">
            </div>
          </div>
        </div>


      </div>




      <div class="">
        <table class="table">
          <thead>
            <tr>
              <th>Sl</th>
              <th>Item</th>
              <th>Description</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody class="AddItem1" id="vendor_items_table1">
            <?php $i = 0;
            foreach ($all_items as $items) {
              $i++; ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td>
                  <select class="packing_dropdown form-control select22" name="u_item[]" id="u_item<?php echo $i; ?>" onchange="getProductDetail(<?php echo $i; ?> )">
                    <option value="">Select product</option>
                    <?php foreach ($all_products as $product) { ?>
                      <option value="<?php echo $product->product_id ?>" <?php if ($product->product_id == $items->supplier_item_name) {
                                                                            echo "selected";
                                                                          } ?>><?php echo $product->product_name ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td>
                  <textarea class="form-control" name="u_description[]" id="u_description<?php echo $i; ?>"><?= $items->supplier_item_description ?></textarea>
                </td>
                <td>
                  <input class="form-control" type="text" value="<?= $items->supplier_item_price ?>" name="u_price[]" id="u_price_<?php echo $i; ?>">
                </td>
                <td>
                  <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <th style="border: none !important;">
                <a href="javascript:void(0)" class="btn-sm btn-success addButton" id="addButton2">Add</a>
              </th>
            </tr>
          </tfoot>
        </table>
      </div>




    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update'); ?></button>
  </div>
  <?php echo form_close(); ?>

  <script type="text/javascript">
    $(document).ready(function() {

      $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
      $('[data-plugin="select_hrm"]').select2({
        width: '100%'
      });


      /* Edit data */
      $("#edit_supplier").submit(function(e) {
        e.preventDefault();
        var obj = $(this),
          action = obj.attr('name');
        $('.save').prop('disabled', true);

        $.ajax({
          type: "POST",
          url: e.target.action,
          data: obj.serialize() + "&is_ajax=1&edit_type=supplier&form=" + action,
          cache: false,
          success: function(JSON) {
            if (JSON.error != '') {
              toastr.error(JSON.error);
              $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
              $('.save').prop('disabled', false);
            } else {
              // On page load: datatable
              var xin_table = $('#xin_table').dataTable({
                "bDestroy": true,
                "ajax": {
                  url: "<?php echo site_url("admin/supplier/supplier_list") ?>",
                  type: 'GET'
                },
                dom: 'lBfrtip',
                "buttons": ['excel'], // colvis > if needed
                "fnDrawCallback": function(settings) {
                  $('[data-toggle="tooltip"]').tooltip();
                }
              });
              xin_table.api().ajax.reload(function() {
                toastr.success(JSON.result);
              }, true);
              $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
              $('.edit-modal-data').modal('toggle');
              $('.save').prop('disabled', false);
            }
          }
        });
      });



      $(document).on('click', '.remove-input-field', function() {
        $(this).parents('tr').remove();
      });
      $('#addButton2').on('click', function() {

        var number = $('.AddItem1 tr').length;
        var item = number + 1;
        $('.AddItem1').append(`
                <tr>
                    <td style="min-width:130px">
                            <label>` + item + `<label>
                        </td>
                        <td>
                        <select class="packing_dropdown form-control select22" name="u_item[]" id="u_item` + item + `"  data-placeholder="Select Products"  onchange="getProductDetail(` + item + `)">
                            <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                  echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                        </select>  
                          </td>
                        <td><textarea id="u_description` + item + `"  class="form-control" name="u_description[]"></textarea></td>
                        <td><input type="text" id="u_price` + item + `"  class="form-control" name="u_price[]" placeholder="Price"></td>
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                    </tr>
                `);

      });

      $(document).on("click", "#addButton2", function() {
        var number = $('.AddItem1 tr').length;
        var item = number + 1;
        // alert(item);
        $('.AddItem1').append(`
                <tr>
                <td><label>` + item + `</label></td>
                <td>
                <select class="packing_dropdown form-control select22" name="u_item[]" id="u_item` + item + `" onchange="getProductDetail(` + item + `)">
                        <option value="">Select product</option>
                                <?php foreach ($all_products as $product) {
                                  echo '<option value="' . $product->product_id . '">' . $product->product_name . '</option>';
                                } ?>
                    </select>  
                 
                </td>
                        <td><textarea id="u_description` + item + `"  class="form-control" name="u_description[]"></textarea></td>
                        <td><input type="text" id="u_price` + item + `"  class="form-control" name="u_price[]" placeholder="Price"></td>
                        <td>
                            <button type="button" name="clear" id="clear" class="btn btn-danger remove-input-field"><i class="ti-trash"></i></button>
                        </td>
                </tr>`);
      });
    });
  </script>
  <script>
    function getProductDetail(number) {
      var prd_id = $('#u_item' + number).val();
      // console.log(supplier_id);

      $.ajax({
        type: "POST",
        url: "<?php echo base_url() . 'admin/purchase/get_product_details/'; ?>" + prd_id,
        data: JSON,
        success: function(data) {
          var product_data = jQuery.parseJSON(data);
          console.log(data);
          $("#u_description" + number).val(product_data[0].description);
          $("#u_price" + number).val(product_data[0].sell_p);

        },
        error: function() {
          toastr.error("Description Not Found");
        }
      });
    }
  </script>
<?php } else if (isset($_GET['jd']) && isset($_GET['supplier_id']) && $_GET['data'] == 'view_supplier') {
?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title" id="edit-modal-data">View <?php echo $this->lang->line('xin_suppliers'); ?></h4>
  </div>
  <form class="m-b-1">
    <div class="modal-body">
      <h3>Supplier</h3>
      <table class="footable-details table table-hover toggle-circle">
        <tbody>

          <tr>
            <th><?php echo $this->lang->line('xin_supplier_name'); ?></th>
            <td style="display: table-cell;">
              <?php echo $supplier_name; ?>
            </td>
          </tr>
          <tr>
            <th>Address 1</th>
            <td style="display: table-cell;">
              <?php echo $address_1; ?>
            </td>
          </tr>
          <tr>
            <th>Address 2</th>
            <td style="display: table-cell;">
              <?php echo $address_2; ?>
            </td>
          </tr>
          <tr>
            <th>Address 3</th>
            <td style="display: table-cell;">
              <?php echo $address_3; ?>
            </td>
          </tr>
          <tr>
            <th>Address 4</th>
            <td style="display: table-cell;">
              <?php echo $address_4; ?>
            </td>
          </tr>
          <tr>
            <th>Contact Person</th>
            <td style="display: table-cell;">
              <?php echo $contact_person; ?>
            </td>
          </tr>
          <tr>
            <th>Phone 1</th>
            <td style="display: table-cell;">
              <?php echo $tel_no_1; ?>
            </td>
          </tr>
          <tr>
            <th>Phone 2</th>
            <td style="display: table-cell;">
              <?php echo $tel_no_2; ?>
            </td>
          </tr>        
          <tr>
            <th>Fax</th>
            <td style="display: table-cell;"><?php echo $fax1; ?></td>
          </tr>
          <tr>
            <th>Email</th>
            <td style="display: table-cell;"><?php echo $email_id; ?></td>
          </tr>


        </tbody>
      </table>
      <h3>Products</h3>
      <table class="footable-details table table-hover ">
        <thead class="table-primary">
          <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Product Size [Std UOM]</th>
            <th>Cost Price</th>
          </tr>
        </thead>
        <tbody class="table-warning">
          <?php $supplier_item = $this->db->select('xin_supplier_item_mapping.*, product.product_name,product.size,product.std_uom')->from('xin_supplier_item_mapping')->join('product', 'xin_supplier_item_mapping.supplier_item_name=product.product_id')->where('supplier_id', $_GET['supplier_id'])->get()->result();
          foreach ($supplier_item as $item) { ?>
            <tr>
              <td><?php echo $item->product_name; ?></td>
              <td><?php echo $item->supplier_item_description; ?></td>
              <td><?php echo $item->size . " " . $item->std_uom; ?></td>
              <td><?php echo $item->supplier_item_price; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <h3>Purchase Orders</h3>
      <table class="footable-details table table-hover ">
        <thead class="table-primary">
          <tr>
            <th>PO Number</th>
            <th></th>
          </tr>
        </thead>
        <tbody class="table-warning">
          <?php $po = $this->db->select('purchase_order.porder_id as pid,purchase_order.purchase_order_id,purchase_order_item_mapping.porder_id,purchase_order_item_mapping.supplier_id')->from('purchase_order_item_mapping')->join('purchase_order', 'purchase_order_item_mapping.porder_id=purchase_order.purchase_order_id')->where('purchase_order_item_mapping.supplier_id', $_GET['supplier_id'])->group_by('purchase_order_item_mapping.supplier_id', $_GET['supplier_id'])->get()->result();
          foreach ($po as $item) { ?>
            <tr>
              <td><?php echo $item->pid; ?></td>
              <td><a href="<?php echo base_url('admin/purchase/view_po/' . $item->purchase_order_id) ?>" target="_blank">Click here to View PO</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close'); ?></button>
    </div>
    <?php echo form_close(); ?>
  <?php }
  ?>