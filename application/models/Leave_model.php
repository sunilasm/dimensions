<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends CI_Model {

	private $table = "user_leaves";
 
	public function create($data = []){	 
		return $this->db->insert($this->table,$data);
	}
   
	public function read()
	{
		return $this->db->select("user_leaves.*, user.firstname, user.lastname,user.email, department.name")
			->from($this->table)
			->join('user','user.user_id = user_leaves.user_id','left')
			->join('department','department.dprt_id = user.department_id','left')
			->order_by('user_leaves.leave_id','desc')
			->get()
			->result();
	}

	
	public function read_by_id($id = null)
	{
		return $this->db->select("*")
			->from($this->table)
			->where('leave_id',$id)
			->get()
			->row();
	} 

	public function user_list($user_id){	

		return $this->db->select("user.firstname, user.lastname,user.email, department.name")
			->from($this->table)			
			->join('department','department.dprt_id = user.department_id','left')
			->order_by('user.user_id','desc')
			->get()
			->result();
	  }

	
	public function update($data = [])
	{
		return $this->db->where('leave_id',$data['id'])
			->update($this->table,$data); 
	} 
 
	public function delete($id = null)
	{
		$this->db->where('leave_id',$id)
			->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}   
}
