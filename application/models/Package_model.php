<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Package_model extends CI_Model {

	private $table = 'package';

	public function __construct()
	{
		parent::__construct();
		$this->language = $this->input->cookie('Lng', true);
		$this->defualt = $this->db->get('setting')->row()->language;
	}

	public function create($data = [])
	{	 
		return $this->db->insert($this->table,$data);
	}

	public function create_lang($data = [])
	{	 
		return $this->db->insert('package_lang',$data);
	}
 
	public function read()
	{
		return $this->db->select("*") 
			->from($this->table)
			->order_by('package_sort_order','asc')
			->get()
			->result();
	} 

	public function read_lang_department()
	{
		return $this->db->select("main_department.name as dname, mdlang.*") 
			->from('main_department_lang as mdlang')
			->join($this->table, 'main_department.id=mdlang.main_id', 'left')
			->order_by('dname','asc')
			->get()
			->result();
	}

	public function read_all()
	{
		return $this->db->select("main_department.id as mid, mdlang.*") 
			->from('main_department_lang as mdlang')
			->join($this->table, 'main_department.id=mdlang.main_id', 'left')
			->where('mdlang.language', (!empty($this->language)?$this->language:$this->defualt))
			->order_by('mdlang.name','asc')
			->get()
			->result();
	}
  
	public function read_by_id($id = null)
	{
		return $this->db->select("*")
			->from($this->table)
			->where('package_id',$id)
			->get()
			->row();
	} 

	public function read_lang_by_id($id = null)
	{
		return $this->db->select("*")
			->from('main_department_lang')
			->where('id',$id)
			->get()
			->row();
	} 
 
	public function update($data = [])
	{
		return $this->db->where('package_id',$data['package_id'])
			->update($this->table,$data); 
	} 

	public function update_lang($data = [])
	{
		return $this->db->where('id',$data['id'])
			->update('main_department_lang',$data); 
	} 
 
	public function delete($id = null)
	{
		$this->db->where('package_id',$id)
			->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 

	public function delete_lang($id = null)
	{
		$this->db->where('id',$id)
			->delete('main_department_lang');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 

	
	
 }
