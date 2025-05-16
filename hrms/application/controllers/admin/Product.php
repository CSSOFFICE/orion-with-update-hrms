<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		//load the model
		$this->load->model("product_model");
		$this->load->model("Xin_model");
	}
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	public function index()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$system = $this->Xin_model->read_setting_info(1);
		if ($system[0]->module_awards != 'true') {
			redirect('admin/dashboard');
		}
		$data['title'] = $this->lang->line('xin_products') . ' | ' . $this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_products');
		$data['path_url'] = 'product';
		$data['get_suppliers'] = $this->product_model->get_supplier_name();
		$data['get_categories'] = $this->product_model->get_categories();


		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (in_array('1701', $role_resources_ids)) {
			if (!empty($session)) {

				$data['subview'] = $this->load->view("admin/product/product_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function product_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		$data['get_categories'] = $this->product_model->get_categories();  // Fetch categories
		$data['get_suppliers'] = $this->product_model->get_supplier_name();

		if (!empty($session)) {
			$this->load->view("admin/product/product_list", $data);
		} else {
			redirect('admin/');
		}
	}

	public function get_products_by_category($category_id)
	{
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$role_resources_ids = $this->Xin_model->user_role_resource();

		// Fetch products by category
		$cat = $this->product_model->get_categories();
		// print_r($cat);exit();
		$get_products = $this->product_model->get_products_using_cat(($category_id) ?? $cat[0]->category_id);

		$data = array();
		$i = 0;
		foreach ($get_products->result() as $r) {
			$i++;
			$edit = '';
			$delete = '';
			$copyProduct = '';
			if (in_array('1703', $role_resources_ids)) {
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-product_id="' . $r->product_id . '"><span class="fa fa-pencil"></span></button></span>';
			}
			if (in_array('1704', $role_resources_ids)) {
				$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->product_id . '"><span class="fa fa-trash"></span></button></span>';
			}
			if (in_array('1706', $role_resources_ids)) {
				$copyProduct = '<span data-toggle="tooltip" data-placement="top" title="Copy Product"><button type="button" class="btn icon-btn btn-xs btn-primary waves-effect waves-light" onclick="CopyProduct(' . $r->product_id . ')" ><span class="fa fa-copy"></span></button></span>';
			}
			$combhr = $edit . $delete . $copyProduct;

			$data[] = array(
				$i,
				$combhr,
				'<img class="rounded-circle" width="50px" src="' . (
					(!empty($r->prd_img) && file_exists(FCPATH . 'uploads/product/' . $r->prd_img))
					? site_url('uploads/product/') . htmlspecialchars($r->prd_img)
					: site_url('uploads/product/default.jpg')
				) . '">',
				'<a href="#" data-toggle="modal" data-target=".view-modal-data" data-product_id="' . htmlspecialchars($r->product_id) . '">' . htmlspecialchars($r->product_name) . '</a>',
				(!empty($r->barcode)) ? '<img height="100px" width="100px" src="' . site_url() . htmlspecialchars($r->barcode) . '">' : '',
				// htmlspecialchars($r->location),
				htmlspecialchars($r->sell_p),
				htmlspecialchars($r->stock_qtn)
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $get_products->num_rows(),
			"recordsFiltered" => $get_products->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function product_suppliers()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('product_id');

		$result = $this->db->select('product.*')
			->from('product')
			->where('product.product_id', $id)
			->get()->result();
		//  print_r($result);exit;
		$data = array(
			'product_id' => $result[0]->product_id,
			'product_name' => $result[0]->product_name,
			'product_img' => $result[0]->prd_img,
			'product_barcode' => $result[0]->barcode,

			'product_des' => $result[0]->description,
			'suplier_with_price' => $this->db->select('s.*,xin_suppliers.supplier_name,xin_suppliers.code,xin_suppliers.phone1,product.product_name')
				->from('xin_supplier_item_mapping as s')
				->join('product', 's.supplier_item_name=product.product_id')
				->join('xin_suppliers', 's.supplier_id=xin_suppliers.supplier_id')
				->where('product.product_id', $id)
				->get()->result(),

		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/product/suppliers_of_product', $data);
		} else {
			redirect('admin/');
		}
	}
	public function add_product()
	{
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		// print_r($_POST);
		// exit;
		$no_of_record = count($this->product_model->check_product_detail(
			trim($this->input->post('product_name'))
		));

		if ($no_of_record > 0) {
			$Return['error'] = "Product Name and Location already exist";
		} else if ($this->input->post('category_name') == '') {
			$Return['error'] = "Category Required";
		} else if ($this->input->post('product_name') == '') {
			$Return['error'] = "Product Name Required";
		}
		// else if ($this->input->post('sell_p') == '') {
		// 	$Return['error'] = "Price Required";
		// }

		if ($Return['error'] != '') {
			$this->output($Return);
			exit;
		}

		$product_name = trim($this->input->post('product_name', FALSE));

		$data = array(
			'category_id' => $this->input->post('category_name'),
			'product_name' => $product_name,
			'location' => trim($this->input->post('location')),
			'cost_price' => $this->input->post('cost_price'),
			'sell_p' => $this->input->post('sell_p'),
			'std_uom' => $this->input->post('std_uom')??'',
			'size' => $this->input->post('size'),
			'base_uom' => $this->input->post('base_uom')??'',
			'stock_qtn' => $this->input->post('stock_qtn')??'',
			'safety_limit' => $this->input->post('safety_limit')??'',
			'description' => $this->input->post('description'),
			'created_by' => $_SESSION['username']['user_id'],
			'created_datetime' => date('Y-m-d h:i:s'),
		);




		// Handle product image upload
		if (!empty($_FILES['prd_img']['name'])) {
			$config['upload_path'] = './uploads/product/';
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('prd_img')) {
				$Return['error'] = $this->upload->display_errors();
				$this->output($Return);
				exit;
			} else {
				$post_image = $this->upload->data();
				$data['prd_img'] = $post_image['file_name'];
			}
		}

		// Insert product data and get product ID
		$product_id = $this->product_model->add_product($data);

		if (isset($_POST['supplier']) && isset($_POST['price'])) {
			if ((count($this->input->post('supplier')) > 0) && (count($this->input->post('price')) > 0)) {

				for ($i = 0; $i < count($this->input->post('supplier')); $i++) {
					$data_opt = array(
						'supplier_id' => $this->input->post('supplier')[$i],
						'supplier_item_name' =>  $product_id,
						'supplier_item_description' => $product_name,
						'supplier_item_price' => $this->input->post('price')[$i],
					);
					$this->db->insert('xin_supplier_item_mapping', $data_opt);
				}
			}
		}

		if ($product_id) {
			// Generate QR with product name and product ID
			include(APPPATH . 'third_party/phpqrcode/qrlib.php');
			$SERVERFILEPATH = "uploads/qrcodes/";
			if (!file_exists($SERVERFILEPATH)) {
				mkdir($SERVERFILEPATH, 0777, true);
			}

			// $qrtext = $product_name . " | ID: " . $product_id;
			$qr_code_data = [
				'product_name' => $product_name,
				'product_id' => $product_id,
			];


			$sanitized_product_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $product_name);
			$output_image = $SERVERFILEPATH . $sanitized_product_name . "_" . $product_id . ".png";

			QRcode::png(json_encode($qr_code_data), $output_image, QR_ECLEVEL_H, 10);
			$this->createQrCodeWithText($output_image, $output_image, $product_name . " (ID: " . $product_id . ")");

			// Update product with QR code image path
			// $this->product_model->update_product_barcode($product_id, ['barcode' => $output_image]);
			$this->db->update('product', ['barcode' => $output_image], ['product_id' => $product_id]);

			$Return['result'] = $this->lang->line('xin_success_product_added');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}

		$this->output($Return);
		exit;
	}

	/**
	 * Helper function to add text below QR Code and save as a new image
	 */
	public function createQrCodeWithText($qr_image, $output_image, $text)
	{
		if (!file_exists($qr_image)) {
			throw new Exception("QR code image not found.");
		}

		$qr = imagecreatefrompng($qr_image);
		$width = imagesx($qr);
		$height = imagesy($qr);

		// Font setup
		$font_size = 12;
		$font_path = APPPATH . 'third_party/fonts/arial.ttf';
		if (!file_exists($font_path)) {
			$font_path = null;
		}

		// Sanitize text and break lines if needed
		$text = str_replace("\\n", "\n", $text); // Convert \n to actual newline
		$text_lines = explode("\n", wordwrap($text, 35, "\n"));
		$line_height = 22;

		$new_height = $height + (count($text_lines) * $line_height) + 20;
		$combined_image = imagecreatetruecolor($width, $new_height);

		$white = imagecolorallocate($combined_image, 255, 255, 255);
		imagefilledrectangle($combined_image, 0, 0, $width, $new_height, $white);

		imagecopy($combined_image, $qr, 0, 0, 0, 0, $width, $height);

		$black = imagecolorallocate($combined_image, 0, 0, 0);
		$y_pos = $height + 30;

		foreach ($text_lines as $line) {
			if ($font_path) {
				$bbox = imagettfbbox($font_size, 0, $font_path, $line);
				$text_width = $bbox[2] - $bbox[0];
				$text_x = ($width - $text_width) / 2;
				imagettftext($combined_image, $font_size, 0, $text_x, $y_pos, $black, $font_path, $line);
			} else {
				$gd_font_size = 4;
				$text_x = ($width - (strlen($line) * imagefontwidth($gd_font_size))) / 2;
				imagestring($combined_image, $gd_font_size, $text_x, $y_pos - 10, $line, $black);
			}
			$y_pos += $line_height;
		}

		imagepng($combined_image, $output_image);
		imagedestroy($qr);
		imagedestroy($combined_image);
		return true;
	}


	public function copy_product($product_id)
	{
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		include(APPPATH . 'third_party/phpqrcode/qrlib.php');

		// Get original product data
		$product = $this->product_model->read_product($product_id);
		if (!$product) {
			$Return['error'] = "Original product not found.";
			$this->output($Return);
			exit;
		}
		$SERVERFILEPATH = "uploads/qrcodes/";

		if (!file_exists($SERVERFILEPATH)) {
			mkdir($SERVERFILEPATH, 0777, true);
		}

		$new_product_name = $product[0]->product_name . ' (Copy)';

		// Copy product image
		$original_img_path = './uploads/product/' . $product[0]->prd_img;
		if (file_exists($original_img_path)) {
			$img_ext = pathinfo($original_img_path, PATHINFO_EXTENSION);
			$new_img_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $new_product_name) . '_' . uniqid() . '.' . $img_ext;
			$new_img_path = './uploads/product/' . $new_img_name;
			copy($original_img_path, $new_img_path);
		} else {
			$new_img_name = null;
		}

		// ✅ Generate a new QR code (don't copy old one)
		// $new_qr_name = 'uploads/qrcodes/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $new_product_name) . '_' . uniqid() . '.png';
		// $this->createQrCodeWithText($new_qr_name,$new_product_name, $new_qr_name);
		$sanitized_product_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $new_product_name);
		$output_image = $SERVERFILEPATH . $sanitized_product_name . ".png";


		// $output_image = $SERVERFILEPATH . $sanitized_product_name . '_' . $unique_code . ".png";
		// $output_image = $SERVERFILEPATH . $product_name . ".png";

		QRcode::png($new_product_name, $output_image, QR_ECLEVEL_H, 10);

		$this->createQrCodeWithText($output_image, $output_image, $new_product_name);

		// Insert duplicated product
		$data = array(
			'category_id' => $product[0]->category_id,
			'product_name' => $new_product_name,
			'location' => $product[0]->location,
			'cost_price' => $product[0]->cost_price,
			'sell_p' => $product[0]->sell_p,
			'std_uom' => $product[0]->std_uom,
			'size' => $product[0]->size,
			'base_uom' => $product[0]->base_uom,
			'stock_qtn' => $product[0]->stock_qtn,
			'safety_limit' => $product[0]->safety_limit,
			'description' => $product[0]->description,
			'barcode' => $output_image,
			'prd_img' => $new_img_name,
			'created_by' => $_SESSION['username']['user_id'],
			'created_datetime' => date('Y-m-d h:i:s'),
		);

		$result = $this->product_model->add_product($data);
		if ($result) {
			$Return['result'] = "Product copied successfully with new QR code!";
		} else {
			$Return['error'] = "Error copying product.";
		}

		$this->output($Return);
		exit;
	}

	// Helper function to generate a random alphanumeric string
	// First two characters will be alphabet, followed by numbers
	private function generateRandomAlphanumeric($min_length, $max_length)
	{
		// Ensure the minimum length is at least 2 since we need two alphabetic characters
		if ($min_length < 2) {
			$min_length = 2;
		}

		$alphabets = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numbers = '0123456789';

		// Generate the first two alphabetic characters
		$random_string = '';
		for ($i = 0; $i < 2; $i++) {
			$random_string .= $alphabets[rand(0, strlen($alphabets) - 1)];
		}

		// Generate the remaining part as numbers
		$remaining_length = rand($min_length, $max_length) - 2;
		for ($i = 0; $i < $remaining_length; $i++) {
			$random_string .= $numbers[rand(0, strlen($numbers) - 1)];
		}

		return $random_string;
	}

	public function read()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('product_id');

		$result = $this->product_model->read_product($id);
		$data = array(
			'category_id' => $result[0]->category_id,
			'product_id' => $result[0]->product_id,
			'product_name' => $result[0]->product_name,
			// 'grn_qtn' => $result[0]->grn_qtn,
			'cost_price' => ($result[0]->cost_price) ?? '',
			'sell_p' => $result[0]->sell_p,
			'std_uom' => $result[0]->std_uom,
			'prd_img' => $result[0]->prd_img,
			'old_qr_code' => $result[0]->barcode,
			'size' => $result[0]->size,
			'base_uom' => $result[0]->base_uom,
			'stock_qtn' => $result[0]->stock_qtn,
			'description' => $result[0]->description,
			'safety_limit' => $result[0]->safety_limit,
			'location' => $result[0]->location,
			'get_categories' => $this->product_model->get_categories(),
			'get_suppliers' => $this->product_model->get_supplier_name()

		);

		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/product/dialog_product', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update()
	{

		if ($this->input->post('edit_type') == 'product') {
			$id = $_POST['product_id'];

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if ($this->input->post('category_name') == '') {
				$Return['error'] = $this->lang->line('error_category_name_required_field');
			} else if ($this->input->post('product_name') == '') {
				$Return['error'] = $this->lang->line('error_product_name_required_field');
			} else if ($this->input->post('cost_price') == '') {
				$Return['error'] = $this->lang->line('error_cost_price_required_field');
			} else if ($this->input->post('location') == '') {
				$Return['error'] = 'Location Required';
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			$product_name = trim($this->input->post('product_name', FALSE));
			$data = array(
				// 'supplier_id' =>$this->input->post('supplier_name'),
				'category_id' => $this->input->post('category_name'),
				'product_name' => $product_name,
				// 'grn_qtn' => $this->input->post('grn_qtn'),
				'cost_price' => ($this->input->post('cost_price')) ?? '',
				'sell_p' => $this->input->post('sell_p'),
				'std_uom' => $this->input->post('std_uom'),
				'size' => $this->input->post('size'),
				'base_uom' => $this->input->post('std_uom'),
				'stock_qtn' => $this->input->post('stock_qtn'),
				'description' => $this->input->post('description'),
				'location' => $this->input->post('location'),
				'safety_limit' => $this->input->post('safety_limit'),
				'modified_by' => $_SESSION['username']['user_id'],
				'modified_datetime' => date('Y-m-d h:i:s'),
			);

			if (!empty($_FILES['prd_img']['name'])) {
				$config['upload_path']   = './uploads/product/';
				$config['allowed_types'] = 'gif|jpg|png';
				$this->load->library('upload');
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('prd_img')) {
					$Return['error'] = $this->upload->display_errors();
					$this->output($Return);
					exit;
				} else {
					//unlink('uploads/' . $delete_logo);
					unlink('uploads/product/' . $this->input->post('old_prd_img'));
					$post_image        = $this->upload->data();
					$data['prd_img'] = $post_image['file_name'];
				}
			} else {
			}


			//  $this->product_model->update($data,$id);
			$result = $this->db->update('product', $data, ['product_id' => $id]);
			$check_mapped_data = $this->db->where('supplier_item_name', $id)->get('xin_supplier_item_mapping')->result();

			if (isset($_POST['supplier_name'])) {
				if (count($this->input->post('supplier_name')) > 0) {
					$this->db->delete('xin_supplier_item_mapping', ['supplier_item_name' => $id]);
					foreach ($this->input->post('supplier_name') as $i => $supplier_id) {
						$existing = $this->db->where(['supplier_item_name' => $id, 'supplier_id' => $supplier_id])
							->get('xin_supplier_item_mapping')
							->row();

						$data_opt = [
							'supplier_id' => $supplier_id,
							'supplier_item_name' => $id,
							'supplier_item_description' => $product_name,
							'supplier_item_price' => $this->input->post('supplier_price')[$i],
						];

						if ($existing) {
							$this->db->update('xin_supplier_item_mapping', $data_opt, ['supplier_item_name' => $id, 'supplier_id' => $supplier_id]);
						} else {
							$this->db->insert('xin_supplier_item_mapping', $data_opt);
						}
					}
				}
			}

			if ($id) {
				// Generate QR with product name and product ID
				include(APPPATH . 'third_party/phpqrcode/qrlib.php');
				unlink($this->input->post('old_qr_code'));
				$SERVERFILEPATH = "uploads/qrcodes/";
				if (!file_exists($SERVERFILEPATH)) {
					mkdir($SERVERFILEPATH, 0777, true);
				}

				// $qrtext = $product_name . " | ID: " . $product_id;
				$qr_code_data = [
					'product_name' => $product_name,
					'product_id' => $id,
				];

				$sanitized_product_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $product_name);
				$output_image = $SERVERFILEPATH . $sanitized_product_name . "_" . $id . ".png";

				QRcode::png(json_encode($qr_code_data), $output_image, QR_ECLEVEL_H, 10);
				$this->createQrCodeWithText($output_image, $output_image, $product_name . " (ID: " . $id . ")");


				// Update product with QR code image path
				// $this->product_model->update_product_barcode($product_id, ['barcode' => $output_image]);
				$this->db->update('product', ['barcode' => $output_image], ['product_id' => $id]);
			}

			if ($result) {
				$Return['result'] = $this->lang->line('xin_success_product_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	public function delete()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$id = $this->uri->segment(4);

		$product = $this->db->where('product_id', $id)->get('product')->row();
		if ($product) {
			// Delete the product image
			unlink('uploads/product/' . $product->prd_img);
			unlink($product->barcode);
		}

		$result = $this->product_model->delete_record($id);
		$this->db->delete('xin_supplier_item_mapping', ['supplier_item_name' => $id]);
		if ($result) {
			$Return['result'] = $this->lang->line('xin_success_product_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
	}

	public function get_product_detail()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(4);

		$result = $this->product_model->read_product($id);
		//echo "<pre>";print_r($result);exit;
		echo json_encode($result);
	}
	private function get_barcode($product_name)
	{
		$data = [];
		$code = rand(10000, 99999);

		//load library
		$this->load->library('zend');
		//load in folder Zend
		$this->zend->load('Zend/Barcode');
		//generate barcode
		$imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $product_name), array())->draw();
		imagepng($imageResource, 'uploads/purchase_order/' . $code . '.png');

		$data['barcode'] = 'uploads/barcodes/' . $product_name . '_' . $code . '.png';
		$this->load->view('demo', $data);
	}
}
