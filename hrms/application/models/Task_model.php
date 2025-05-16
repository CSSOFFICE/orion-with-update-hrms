<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model {

    public function get_task_list($id) {
		
		$sql = 'SELECT * FROM tasks WHERE task_projectid = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
	}
    public function get_task_detail($task_id)
    {
        $sql = 'SELECT * FROM tasks WHERE task_id = ?';
		$binds = array($task_id);
		$query = $this->db->query($sql, $binds);
		
		return $query->result();
    }

	public function get_all_task_using_user_id($id){
		$sql = 'SELECT tasks.*,tasks_assigned.* FROM `tasks` join tasks_assigned on tasks_assigned.tasksassigned_taskid = tasks.task_id where tasks_assigned.tasksassigned_userid= ?';
		$binds = array($id);
		return $query = $this->db->query($sql, $binds);
	}
}