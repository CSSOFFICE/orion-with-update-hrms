<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tools extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
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

        $data['title'] = 'Tools/Machinery | ' . $this->Xin_model->site_title();
        $data['breadcrumbs'] = 'Tools/Machinery';
        $data['path_url'] = 'tools_machinery';
        $role_resources_ids = $this->Xin_model->user_role_resource();

        if (in_array('1707', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/tools/tools_machinery", $data, TRUE);
                $this->load->view('admin/layout/pms/layout_pms', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function tools_machinery_data()
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {
            $this->load->view("admin/tools/tools_machinery", $data, TRUE);
        } else {
            redirect('admin/');
        }

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $inventory_list = $this->product_model->QRscan();

        $data = [];
        $i = 0;

        $movement_types = [
            'Receive' => ['color' => 'green', 'label' => 'Receive'],
            'Issue' => ['color' => 'blue', 'label' => 'Withdraw'],
            'Return' => ['color' => 'green', 'label' => 'Return'],
            'Transfer' => ['color' => 'red', 'label' => 'Transfer']
        ];

        foreach ($inventory_list->result() as $r) {
            if ($r->qtn > 0) {
            $i++;
            $added_by = $r->first_name . " " . $r->last_name;
            $movement_type_output = '';
            $colored_quantity = '';
            if (isset($movement_types[$r->movement_type])) {
                $movement_type_output = "<span style='color: {$movement_types[$r->movement_type]['color']};'>{$movement_types[$r->movement_type]['label']}</span>";
                $colored_quantity = "<span style='color: {$movement_types[$r->movement_type]['color']};'>{$r->qtn}</span>";
            } else {
                $movement_type_output = "<span style='color: gray;'>Unknown</span>";
                $colored_quantity = $r->qtn; // Default to plain quantity if movement type is unknown
            }
            }

            // Add data to the array
            $data[] = [
            $i,
            $r->product_name,
            $r->from_description,
            $r->to_description,
            $colored_quantity,
            $movement_type_output,
            date('d-F-Y H:i:s', strtotime($r->created_date)),
            $added_by,
            ];
        }


        $output = [
            "draw" => $draw,
            "recordsTotal" => $inventory_list->num_rows(),
            "recordsFiltered" => $inventory_list->num_rows(),
            "data" => $data
        ];

        echo json_encode($output);
        exit();
    }
    public function save_tool_movement()
    {
        $product_id = $this->input->post('product_id');
        $from_location = $this->input->post('from_location');
        $to_location = $this->input->post('to_location');
        $movement_type = $this->input->post('movement_type'); // 'Take' or 'Return'
        $quantity = (int)$this->input->post('quantity');
        $user_id = $_SESSION['username']['user_id'];
        $current_date = date('Y-m-d  H:i:s');

        if ($movement_type == 'Take') {
            $stock_move_data = [
                'product_id' => $product_id,
                'remark' => "This Item and Quantity Withdrawn from Warehouse Through QR Code",
                'qtn' => $quantity,
                'prj_id' => $to_location,
                'wh_id' => $from_location,
                'trans_type' => 'OUTBOUND',
                'movement_type' => 'Issue',
                'stock_from' => $from_location,
                'stock_to' => $to_location,
                'from_to_type' => "warehouse to project",
                'created_date' => $current_date,
                'by_whome' => $user_id
            ];

            // Decrease stock from warehouse
            $this->db->set('quantity', 'quantity - ' . $quantity, FALSE)
                ->where('prd_id', $product_id)
                ->where('warehouse_id', $from_location)
                ->update('stock_management');
        } elseif ($movement_type == 'Return') {
            $stock_move_data = [
                'product_id' => $product_id,
                'remark' => "This Item and Quantity Returned to Warehouse Through QR Code",
                'qtn' => $quantity,
                'prj_id' => $from_location,
                'wh_id' => $to_location,
                'trans_type' => 'INBOUND',
                'movement_type' => 'Receive',
                'stock_from' => $from_location,
                'stock_to' => $to_location,
                'from_to_type' => "project to warehouse",
                'created_date' => $current_date,
                'by_whome' => $user_id
            ];

            // Increase stock in warehouse
            $this->db->set('quantity', 'quantity + ' . $quantity, FALSE)
                ->where('prd_id', $product_id)
                ->where('warehouse_id', $to_location)
                ->update('stock_management');
        } elseif ($movement_type == 'Transfer') {
            $stock_move_data = [
                'product_id' => $product_id,
                'remark' => "This Item and Quantity Transfer from Project Site Through QR Code",
                'qtn' => $quantity,
                'prj_id' => $to_location,
                'wh_id' => 0,
                'trans_type' => 'OUTBOUND',
                'movement_type' => 'Transfer',
                'stock_from' => $from_location,
                'stock_to' => $to_location,
                'from_to_type' => "project to project",
                'created_date' => $current_date,
                'by_whome' => $user_id
            ];
        }

        // Insert into stock_move_log
        $this->db->insert('stock_move_log', $stock_move_data);

        echo json_encode(['status' => 'success', 'message' => 'Movement recorded successfully']);
    }

    function check_product_exist()
    {
        $product_id = $this->input->get('product_id');
        $product = $this->product_model->read_product($product_id);

        if ($product) {
            $warehouse = $this->db->select('warehouse.w_id, warehouse.w_name, stock_management.quantity')
                ->from('stock_management')
                ->join('warehouse', 'warehouse.w_id = stock_management.warehouse_id', 'left')
                ->where('prd_id', $product_id)
                ->where('stock_management.quantity >', 0) // Only include warehouses with quantity > 0
                ->get()
                ->result();


            $myproject = $this->db->select('projects.project_id, projects.project_code, projects.project_title')
                ->from('projects')
                ->join('projects_assigned', 'projects_assigned.projectsassigned_projectid = projects.project_id')
                ->join('projects_manager', 'projects_manager.projectsmanager_projectid = projects.project_id')
                ->group_start()
                ->where('projects_assigned.projectsassigned_userid', $_SESSION['username']['user_id'])
                ->or_where('projects_manager.projectsmanager_userid', $_SESSION['username']['user_id'])
                ->or_where('projects.supervisor', $_SESSION['username']['user_id'])
                ->or_where('projects.engineer', $_SESSION['username']['user_id'])
                ->group_end()
                ->get()
                ->result();


            $mynamewithid = $this->db->select('user_id, first_name, last_name')
                ->from('xin_employees')
                ->where('user_id', $_SESSION['username']['user_id'])
                ->get()
                ->result();

            // ✅ Ensure `project_code` is never null
            foreach ($myproject as &$project) {
                $project->project_code = $project->project_code ?? ""; // Replace null with empty string
            }
            unset($project); // Prevent accidental modification

            $response = [
                'status'   => 'success',
                'product'  => $product ?: [],
                'warehouse' => $warehouse ?: [],
                'myprojects' => $myproject ?: [],
                'employee' => $mynamewithid ?: []
            ];

            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Product not found']);
        }
    }
}
