<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class category_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function add_category($data){
        $this->db->insert('category', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
    }
    public function get_category(){
        return $this->db->get('category');
    }
    public function read_category($id){
        $query = $this->db->where('category_id',$id)->get('category');
        return $query->result();
    }
    public function update($data,$id){
		$this->db->where('category_id', $id);
		if( $this->db->update('category',$data)) {
			return true;
		} else {
			return false;
		}		
	}
    public function delete_record($id){
        $this->db->where('category_id', $id);
		$this->db->delete('category'); 
		return true;
    }
}