<?php
class Delivery extends My_Controller{
    public function __construct() {
        parent::__construct();
		$this->load->model("Xin_model");
		
		$this->load->model("Payable_model");
		$this->load->model("Receivable_model");



    }
    public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
    
    public function index(){
        $session = $this->session->userdata('username');
		$data['title'] = 'Delivery Order | '.$this->Xin_model->site_title();
        $data['breadcrumbs'] = 'Delivery Order';
		$data['path_url'] = 'delivery';
		$role_resources_ids = $this->Xin_model->user_role_resource();
       
        // if(in_array('3301',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/delivery/do_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		// } else {
		// 	redirect('admin/dashboard');
		// }
    }



}?>