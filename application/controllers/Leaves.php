<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 |----Packages for BackEnd-------|
*/
class Leaves extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'leave_model',
			'department_model'			
			//'setting_model'
		));
		
		if ($this->session->userdata('isLogIn') == false) 
		redirect('login');

	}
	public function index(){
		if ($this->session->userdata('isLogIn') == false) 
		// redirect('login'); 
		$data['module'] = display("leaves");
		$data['title'] = display('leave_list');
		#-------------------------------#
		$data['leaves'] = $this->leave_model->read();
		$data['content'] = $this->load->view('leaves/index',$data,true);
		$this->load->view('layout/main_wrapper',$data);
		//echo "<pre>".print_r($data, true); exit;
	}
	public function managers(){
		if ($this->session->userdata('isLogIn') == false) 
		// redirect('login'); 
		$data['module'] = display("leaves");
		$data['title'] = display('managers');
		#-------------------------------#
		$data['content'] = $this->load->view('leaves/managers',$data,true);
		$this->load->view('layout/main_wrapper',$data);
		//echo "<pre>".print_r($data, true); exit;
	}

	public function create(){
		if ($this->session->userdata('isLogIn') == false) 
		redirect('login'); 
		$data['module'] = display("leaves");
		$data['title'] = display('add_leave');

		$current_logged_user=$this->session->userid;
		#-------------------------------#
		
		$this->form_validation->set_rules('leave_type', display('leave_type'),'required|max_length[8]');
		//$this->form_validation->set_rules('from_date', display('from_date'),'required|max_length[10]');
		//$this->form_validation->set_rules('to_date', display('to_date'),'required|max_length[10]');
		$this->form_validation->set_rules('leave_description', display('leave_description'),'trim');
		
		#-------------------------------#
		$data['leave'] = (object)$postData = [
			'leave_id' 	 => $this->input->post('leave_id',true),
			/*'emp_id' 	      => $this->input->post('emp_id',true),
			'department_names' 	      => $this->input->post('department_names',true),
			'first_name' 	      => $this->input->post('first_name',true),
			'last_name' 	      => $this->input->post('last_name',true),
			'email' 	      => $this->input->post('email',true),*/
			'leave_type' => $this->input->post('leave_type',true),		 	
			'from_date' => date('Y-m-d', strtotime(($this->input->post('from_date') != null)? $this->input->post('from_date'): date('Y-m-d'))),
			'to_date' => date('Y-m-d', strtotime(($this->input->post('to_date') != null)? $this->input->post('to_date'): date('Y-m-d'))),
			'leave_description' => $this->input->post('leave_description', true),
		]; 
		
        if ($this->form_validation->run() === true) 
        {
			if (empty($postData['leave_id'])) {
				
				if ($this->leave_model->create($postData)) {
					#set success message
					$this->session->set_flashdata('message',display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
				redirect('leaves/index');
			} else {
				if ($this->leave_model->update($postData)) {
					#set success message
					$this->session->set_flashdata('message',display('update_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
				//redirect('leave/edit/'.$postData['leave_id']);
				redirect('leaves/index');
			}

		} else {
			$data['department_list'] = $this->department_model->department_list();
			$data['content'] = $this->load->view('leaves/apply_leave',$data,true);
			$this->load->view('layout/main_wrapper',$data);
		} 
	}
	public function edit($id = null){
		if ($this->session->userdata('isLogIn') == false) 
		redirect('login'); 
		$data['module'] = display("packages");
		$data['title'] = display('packages');
		#-------------------------------#
		$data['package'] = $this->package_model->read_by_id($id);
		$data['content'] = $this->load->view('packages/package_form',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}

	public function delete($id = null) 
	{
		if ($this->session->userdata('isLogIn') == false) 
		redirect('login'); 
		if ($this->package_model->delete($id)) 
		{
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('packages');
	}

	public function list()
	{
		$this->load->model(array(
			'website/home_model',
			'setting_model',
			'website/menu_model',
		));
		$config = array();
		$config["base_url"] = base_url() . "packages/list";
		//$config["total_rows"] = $this->package_model->record_count();
		$config["per_page"] = 9;
		$config["uri_segment"] = 3;
		  //css styling
		  $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		  $config['full_tag_close'] = '</ul>';
		  $config['attributes'] = ['class' => 'page-link'];
		  $config['first_link'] = false;
		  $config['last_link'] = false;
		  $config['first_tag_open'] = '<li class="page-item">';
		  $config['first_tag_close'] = '</li>';
		  $config['prev_link'] = '&laquo Previous';
		  $config['prev_tag_open'] = '<li class="page-item">';
		  $config['prev_tag_close'] = '</li>';
		  $config['next_link'] = 'Next &raquo';
		  $config['next_tag_open'] = '<li class="page-item">';
		  $config['next_tag_close'] = '</li>';
		  $config['last_tag_open'] = '<li class="page-item">';
		  $config['last_tag_close'] = '</li>';
		  $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
		  $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
		  $config['num_tag_open'] = '<li class="page-item">';
		  $config['num_tag_close'] = '</li>';
  
		$this->pagination->initialize($config);
	
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['title'] = display('packages_list');
		$data['setting'] = $this->home_model->setting(); 
		// redirect if website status is disabled
		if ($data['setting']->status == 0) 
		redirect(base_url('login'));
		$data['basics'] = $this->home_model->basic_setting();  
		$data['section'] = $this->home_model->section('packages');
		//echo "<pre>".print_r($data, true); exit;
		// get banner slider
		$data['banner'] = $this->db->select("image")->from('ws_banner')->where('status', 1)->limit(3)->order_by('id', 'DESC')->get()->result();
		$data['languageList'] = $this->home_model->languageList(); 
		$data['packages'] = $this->package_model->read();
		//$data['about'] = $this->about_model->read();
		//$data['deptsFooter'] = $this->department_model->read_footer();
		$data['parent_menu'] = $this->menu_model->get_parent_menu();
		$data['packages'] = $this->package_model->read($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['content'] = $this->load->view('website/packages_list',$data,true);
		$this->load->view('website/plain_index', $data);
	}
}
