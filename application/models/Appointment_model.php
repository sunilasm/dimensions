<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment_model extends CI_Model {

	private $table = 'appointment';
	public function __construct()
	{
		parent::__construct();
		$this->language = $this->input->cookie('Lng', true);
		$this->defualt = $this->db->get('setting')->row()->language;
	}

	public function create($data = [])
	{	 
		$this->db->insert($this->table,$data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	public function update($id = 0, $data = [])
	{	 
		$response_id = $id;
		if($id)
		{
			$response_id =  $this->db->update($this->table, $data, array('appointment_id' => $id));
			// $this->db->where('id', $id);
			// $this->db->update($this->table, $data);
		}
		return $response_id;
	}
 
	public function read()
	{
		return $this->db->select("
				appointment.*, 
				user.firstname, 
				user.lastname,  
				department.name,
				schedule.schedule_type,
				schedule.start_time,
				schedule.end_time 
			")
			->from($this->table)
			->join('user','user.user_id = appointment.doctor_id')
			->join('department','department.dprt_id = appointment.department_id')
			->join('schedule','schedule.schedule_id = appointment.schedule_id')
			->where('user.user_id', $this->session->userdata('user_id'))
			->where('appointment.status', 1)
			->or_where('appointment.status', 2)
			->or_where('appointment.status', 3)
			->order_by('appointment.id','desc')
			->get()
			->result();
	} 

	// for admin view
	public function read_admin()
	{
		return $this->db->select("
				appointment.*, 
				user.firstname, 
				user.lastname,  
				department.name,
				schedule.schedule_type,
				schedule.start_time,
				schedule.end_time
			")
			->from($this->table)
			->join('user','user.user_id = appointment.doctor_id')
			->join('department','department.dprt_id = appointment.department_id')
			->join('schedule','schedule.schedule_id = appointment.schedule_id')
			->order_by('appointment.id','desc')
			->get()
			->result();
	} 
	public function check_appointments($where = array())
	{
		return $this->db->select("*")
			->from($this->table)
			->where($where)
			->get()
			->result();
	}
	public function read_by_id($appointment_id = null)
	{ 
		return $this->db->select("
				appointment.*, 
				appointment.appointment_id, 
				appointment.serial_no, 
				appointment.problem, 
				appointment.date, 
				usrLn.firstname, 
				usrLn.lastname,  
				user.picture,
				user.meeting_url,  
				user.meeting_user_id,  
				user.meeting_password,  
				usrLn.degree,  
				transaction.transaction_id,  
				transaction.refund_id,  
				transaction.amount as refund_amount,  
				transaction.status as refund_status,  
				transaction.speed_processed as speed_processed,  
				transaction.created_date as refund_date,  
				department.name as department,
				department.price as price,
				main_department.name as branch_name,
				schedule.available_days,
				schedule.start_time,
				schedule.end_time,
				schedule.schedule_type,
				patient.firstname AS pfirstname,
				patient.lastname AS plastname,
				patient.date_of_birth,
				patient.sex,
				patient.mobile,
				patient.picture,
			")
			->from($this->table)
			->join('user','user.user_id = appointment.doctor_id','left')
			->join('user_lang as usrLn','usrLn.user_id = appointment.doctor_id')
			->join('department','department.dprt_id = appointment.department_id','left')
			->join('main_department', 'department.main_id=main_department.id', 'left')
			->join('patient','patient.patient_id = appointment.patient_id')
			->join('schedule','schedule.schedule_id = appointment.schedule_id','left')
			->join('transaction','transaction.payment_id = appointment.payment_id','left')
			->where('appointment.appointment_id',$appointment_id)
			->where('usrLn.language', (!empty($this->language)?$this->language:$this->defualt))
			->order_by('appointment.id','desc')
			->get()
			->row();
	}  
 
	public function delete($appointment_id = null)
	{
		$this->db->where('appointment_id',$appointment_id)
			->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 

	public function fetch_patient_data($query)
	{
		if($query != '')
		{
			$this->db->select('*');
			$this->db->from('patient');
			$this->db->like('firstname', $query);
			$this->db->or_like('mobile', $query);
			$this->db->or_like('patient_id', $query);
			$this->db->or_like('CONCAT(firstname, " ", lastname)', $query);
			$this->db->order_by('firstname', 'asc');
			$this->db->limit(2);
			return $this->db->get();
	    }
	}
 
	
 }
