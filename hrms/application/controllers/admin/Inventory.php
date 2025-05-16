<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Inventory extends MY_Controller
{



    public function __construct()
    {
        parent::__construct();

        //load the model
        $this->load->model("product_model");
        $this->load->model("Xin_model");
        // $this->load->model("Custom_fields_model");
        // $this->load->model("Project_model");
        // $this->load->model("Payable_model");
        // $this->load->model("Receivable_model");

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
        $data['title'] = "Inventory" . ' | ' . $this->Xin_model->site_title();
        $data['breadcrumbs'] = "Inventory";
        $data['path_url'] = 'inventory';

        $data['get_all_projects'] = $this->Xin_model->get_all_project();
        $data['all_units'] = $this->Xin_model->get_unit();
        $data['get_gst'] = $this->Xin_model->get_gst();


        $role_resources_ids = $this->Xin_model->user_role_resource();

        if (in_array('9007', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/product/inventory_list", $data, TRUE);
                $this->load->view('admin/layout/pms/layout_pms', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    ///////////Inventory Tracking//////////
    public function tracklist()
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {
            $this->load->view("admin/product/inventory_list", $data, TRUE);
        } else {
            redirect('admin/');
        }

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $inventory_list = $this->product_model->inventory_list();

        // Check if query result is valid and has data
        // if (!$inventory_list || $inventory_list->num_rows() === 0) {
        //     echo json_encode([
        //         "draw" => $draw,
        //         "recordsTotal" => 0,
        //         "recordsFiltered" => 0,
        //         "data" => []
        //     ]);
        //     exit();
        // }

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

                // Determine movement type output
                $movement_type_output = '';
                if (isset($movement_types[$r->movement_type])) {
                    $movement_type_output = "<span style='color: {$movement_types[$r->movement_type]['color']};'>{$movement_types[$r->movement_type]['label']}</span>";

                    // Add special note for manually added items
                    if ($r->movement_type == 'Receive' && $r->remark == 'This Item and Quantity Added Manually') {
                        $movement_type_output .= " (This Item and Quantity Added Manually)";
                    } elseif ($r->movement_type == 'Receive' && $r->remark == 'This Item and Quantity Returned to Warehouse Through QR Code') {
                        $movement_type_output .= " (This Item and Quantity Returned to Warehouse Through QR Code)";
                    } elseif ($r->movement_type == 'Issue' && $r->remark == 'This Item and Quantity Withdrawn from Warehouse Through QR Code') {
                        $movement_type_output .= " (This Item and Quantity Withdrawn from Warehouse Through QR Code)";
                    }elseif($r->movement_type == 'Transfer' && $r->remark == 'This Item and Quantity Transfer from Project Site Through QR Code') {
                        $movement_type_output .= " (This Item and Quantity Transfer from Project Site Through QR Code)";
                    }
                }

                // Add data to the array
                // Add data to the array
                $data[] = [
                    $i,
                    $r->product_name,
                    $r->from_description,
                    $r->to_description,
                    ($r->trans_type == 'INBOUND') ? "<span style='color: green;'>{$r->qtn}</span>" : "<span style='color: red;'>{$r->qtn}</span>",
                    $movement_type_output,
                    date('d-F-Y H:i:s', strtotime($r->created_date)),
                    $added_by,
                ];
            }
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

    public function get_stock_return_data()
    {
        $data = $this->product_model->stock_return_list($this->uri->segment(4));
        // print_r($data->result());exit();
        echo json_encode($data);
    }

    public function get_stock_out_data()
    {
        $data = $this->product_model->stock_out_list($this->uri->segment(4));
        // print_r($data->result());exit();
        echo json_encode($data);
    }

    public function get_stock_purchase_data()
    {
        $data = $this->product_model->stock_purchase_list($this->uri->segment(4));
        // print_r($data->result());exit();
        echo json_encode($data);
    }
    // public function export_stock_out_data()
    // {
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Headers with styling
    //     $headers = [
    //         "#",
    //         "BAR CODE",
    //         "ITEM NAME",
    //         "QTY",
    //         "UOM",
    //         "NAME OF WORKER (TAKEN)",
    //         "DATE TAKEN",
    //         "TO SITE"
    //     ];

    //     $headerStyle = [
    //         'font' => [
    //             'bold' => true,
    //         ],
    //         'fill' => [
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => ['rgb' => 'FFFF00']
    //         ],
    //         'alignment' => [
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //         ],
    //     ];

    //     // Add headers
    //     $column = 'A';
    //     foreach ($headers as $header) {
    //         $sheet->setCellValue("{$column}1", $header);
    //         $sheet->getStyle("{$column}1")->applyFromArray($headerStyle);
    //         $column++;
    //     }

    //     // Fetch data
    //     $data = $this->product_model->stock_out_list();
    //     $row = 2;

    //     foreach ($data as $index => $item) {
    //         $sheet->setCellValue("A{$row}", $index + 1); // Auto-generate serial number
    //         $sheet->setCellValue("B{$row}", $item->product_name); // BAR CODE (example)
    //         $sheet->setCellValue("C{$row}", $item->product_name); // ITEM NAME
    //         $sheet->setCellValue("D{$row}", $item->qtn); // Quantity
    //         $sheet->setCellValue("E{$row}", $item->std_uom); // UOM
    //         $sheet->setCellValue("F{$row}", $item->first_name . ' ' . $item->last_name); // Worker name
    //         $sheet->setCellValue("G{$row}", $item->created_date); // Date taken
    //         $sheet->setCellValue("H{$row}", $item->to_description); // To site
    //         $row++;
    //     }

    //     // Set headers for file download
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="stock_out_report.xlsx"');
    //     header('Cache-Control: max-age=0');

    //     // Save the file to output
    //     $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //     $writer->save('php://output');

    //     exit; // Terminate script execution after file output
    // }



    ///////////Inventory Tracking//////////

}
