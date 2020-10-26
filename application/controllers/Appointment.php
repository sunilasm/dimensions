<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'appointment_model',
            'department_model', 'patient_model', 'main_department_model'
        ));
        
        
    }
   
    public function index(){
        if ($this->session->userdata('isLogIn') == false) 
        redirect('login'); 
        $data['module'] = display("appointment");
        $data['title'] = display('appointment'); 
        /* ------------------------------- */
        $role = $this->session->userdata('user_role');
        if($role==1){
            $data['appointments'] = $this->appointment_model->read_admin();
        }else{
            $data['appointments'] = $this->appointment_model->read();
        }
        //echo "<pre>".print_r($data,true)."</pre>"; exit;
        $data['content'] = $this->load->view('appointment',$data,true);
        $this->load->view('layout/main_wrapper',$data);
    } 

    public function create(){
        if ($this->session->userdata('isLogIn') == false) 
        redirect('login'); 
        $data['module'] = display("appointment");
        $data['title'] = display('add_appointment');
        /* ------------------------------- */
        $this->form_validation->set_rules('patient_id', display('patient_id'),'required|max_length[20]');
        $this->form_validation->set_rules('department_id', display('department_name'),'required|max_length[50]');
        $this->form_validation->set_rules('doctor_id', display('doctor_name') ,'required|max_length[50]');
        $this->form_validation->set_rules('schedule_id', display('appointment_date') ,'required|max_length[10]'); 
        $this->form_validation->set_rules('serial_no', display('serial_no') ,'required|max_length[10]');
        $this->form_validation->set_rules('problem', display('problem'),'max_length[255]');
        $this->form_validation->set_rules('status',display('status'),'required');
        /* ------------------------------- */
        $data['appointment'] = (object)$postData = [
            'appointment_id' => 'A'.$this->randStrGen(2, 7),
            'patient_id'     => $this->input->post('patient_id',true), 
            'department_id'  => $this->input->post('department_id',true), 
            'doctor_id'      => $this->input->post('doctor_id',true), 
            'schedule_id'    => $this->input->post('schedule_id',true), 
            'serial_no'      => $this->input->post('serial_no',true), 
            'problem'        => $this->input->post('problem',true), 
            'date'           => date('Y-m-d',strtotime($this->input->post('date',true))),
            'created_by'     => $this->session->userdata('user_id'), 
            'create_date'    => date('Y-m-d'),
            'status'         => $this->input->post('status',true)
        ]; 
        /* ------------------------------- */
        //check patient id
        $check_patient_id = json_decode($this->check_patient(true));

        //check appointment exists
        $check_appointment_exists = $this->check_appointment_exists(
            $this->input->post('patient_id',true), 
            $this->input->post('doctor_id',true), 
            $this->input->post('schedule_id',true), 
            date('Y-m-d',strtotime($this->input->post('date',true)))
        );
        if ($check_appointment_exists === false) {
            $this->session->set_flashdata('exception',display('you_are_already_registered')); 
        } 
        /* ------------------------------- */
        if ($this->form_validation->run() === true && $check_patient_id->status === true && $check_appointment_exists === true) {
            
            if($this->input->post('payment_type_id',true) == 'Online')
            {
                $postData['payment_mode'] = 'Online';
                $payment_id = $this->input->post('receipt_id', true);
                $amount = $this->input->post('price', true);
                $data = array(
                    'amount' => ($amount * 100),
                    'currency' => CURRENCY,
                );
                $this->load->model('transaction_model');
                $response = $this->transaction_model->index($payment_id, $data);
                if($response['status'])
                {
                    $postData['payment_mode'] = 'Online';
                    $postData['status'] = 1;
                    $postData['payment_id'] = $response['payment_id'];
                }
                else
                {
                    $message['exception'] = $response['error']; 
                    $this->session->set_flashdata($message);
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
            else
            {
                $postData['payment_mode'] = 'Cash';
                $postData['status'] = 2;
                $postData['payment_id'] = $this->input->post('receipt_id',true);
            }

            /*if empty $id then insert data*/
            if ($this->appointment_model->create($postData)) {

                #-------------------------------------------------------#
            #-------------------------SMS SEND -----------------------------#
                #-------------------------------------------------------#
                # SMS Setting
                $setting = $this->db->select('appointment')
                   ->from('sms_setting')
                   ->get()
                   ->row();

                if (!empty($setting) && ($setting->appointment==1))
                { 

                    #-----------------------------------#
                    # SMS Gateway Setting
                    $gateway = $this->db->select('*')
                       ->from('sms_gateway')
                       ->where('default_status', 1)
                       ->get()
                       ->row();

                    #-----------------------------------#
                    # schedules list
                    $sms_teamplate = $this->db->select('teamplate')
                        ->from('sms_teamplate')
                        ->where('status', 1)
                        ->where('default_status', 1)
                        ->like('type', 'Appointment', 'both')
                        ->get()
                        ->row();  


                    #-----------------------------------#
                    # sms
                    $sms = $this->db->select("
                            a.*,
                            CONCAT_WS(' ', d.firstname, d.lastname) AS doctor_name,
                            CONCAT_WS(' ', p.firstname, p.lastname) AS patient_name,
                            p.mobile
                        ")
                        ->from('appointment AS a')
                        ->join('user AS d', 'd.user_id=a.doctor_id', 'left')
                        ->join('patient AS p', 'p.patient_id=a.patient_id', 'left')
                        ->where('a.appointment_id', $postData['appointment_id'])
                        ->get()
                        ->row();


                    if(!empty($gateway) && !empty($sms_teamplate)) 
                    {
                        $this->load->library('smsgateway');
                        $template = $this->smsgateway->template([
                            'doctor_name'    => $sms->doctor_name,
                            'appointment_id' => $sms->appointment_id,
                            'patient_name'   => $sms->patient_name,
                            'patient_id'     => $sms->patient_id,
                            'sequence'       => $sms->serial_no, 
                            'appointment_date' => date('d F Y',strtotime($sms->date)),
                            'message'        => $sms_teamplate->teamplate
                        ]); 

                        $this->smsgateway->send([
                            'apiProvider' => $gateway->provider_name,
                            'username'    => $gateway->user,
                            'password'    => $gateway->password,
                            'from'        => $gateway->authentication,
                            'to'          => $sms->mobile,
                            'message'     => $template
                        ]);

                        // save data to sms info
                        $this->db->insert('sms_info', array(
                            'doctor_id'   => $sms->doctor_id,
                            'patient_id'  => $sms->patient_id,
                            'phone_no'    => $sms->mobile,
                            'appointment_id'   => $sms->appointment_id,
                            'appointment_date' => $sms->date,
                            'status'      => 0,
                            'sms_counter' => 0
                        ));                        

                        // save delivary data
                        $this->db->insert('custom_sms_info', array(
                           'gateway' => $gateway->provider_name,
                           'reciver'          => $sms->mobile,
                           'message'          => $template ,
                           'sms_date_time'    => date("Y-m-d h:i:s")
                        ));
                    }
                }
                #-------------------------------------------------------#
            #-------------------------SMS SEND -----------------------------#
                #-------------------------------------------------------#

                # unset the patient id session
                $this->session->unset_userdata('patientID');

                /*set success message*/
                $this->session->set_flashdata('message',display('save_successfully'));
            } else {
                /*set exception message*/
                $this->session->set_flashdata('exception',display('please_try_again'));
            }
            redirect('appointment/view/'.$postData['appointment_id']);

        } else {
            //$data['department_list'] = $this->department_model->department_list();
            $data['appointment_type'] = $this->main_department_model->appointment_type();
            $data['payment_type_list'] = $this->main_department_model->payment_type_list();
            $data['main_department_list'] = $this->main_department_model->main_department_list();
            $data['content'] = $this->load->view('appointment_form',$data,true);
            $this->load->view('layout/main_wrapper',$data);
        } 
    }
 

    public function view($appointment_id = null)
    {  
        $data['module'] = display("appointment");
        $data['title'] = display('appointment');
        /* ------------------------------- */
        $data['appointment'] = $this->appointment_model->read_by_id($appointment_id);
        //echo "<pre>".print_r($data,true)."</pre>"; exit;
        $data['content'] = $this->load->view('appointment_view',$data,true);
        $this->load->view('layout/main_wrapper',$data);
    } 

 

    public function delete($appointment_id = null) 
    {
        $data = array();
        $data['status'] = 4;
        if($this->appointment_model->update($appointment_id, $data)) 
        {
            /*set success message*/
            $this->session->set_flashdata('message', display('delete_successfully'));
        } 
        else 
        {
            /*set exception message*/
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function cancell($appointment_id = null) 
    {
        $data = array();
        $data['status'] = 3;
        $refund_status  = false;

        $appointment = $this->appointment_model->read_by_id($appointment_id);
        //echo "<pre>".print_r($appointment,true);
        if(isset($appointment->payment_mode) && trim($appointment->payment_mode) == 'Online')
        {
            $this->load->model('transaction_model');
            $request_data = array();
            $request_data['amount'] = $appointment->price;
            $response = $this->transaction_model->refund($appointment->payment_id, $request_data);
            //echo "<pre>".print_r($response,true);exit;
            if($response['status'])
            {
                $refund_data = array();

                $refund_data['payment_id']  = $appointment->payment_id;
                $refund_data['refund_id']   = $response['result']->id;
                $refund_data['amount']      = $response['result']->amount;
                $refund_data['receipt']     = $response['result']->receipt;
                $refund_data['status']      = $response['result']->status;
                $refund_data['speed_processed']      = $response['result']->speed_processed;
                //$refund_data['created_date'] = date()

                $status = $this->transaction_model->insert_refund_data($refund_data);

                if($status)
                {
                    $refund_status = true;
                }
            }
            else
            {
                $refund_status = false;
            }
        }
        else
        {
            //echo "ref"; 
            $refund_status = true;
        }
        //exit;
        //echo "<pre>".print_r($appointment,true); exit;
        if($refund_status)
        {
            if($this->appointment_model->update($appointment_id, $data)) 
            {
                /*set success message*/
                $this->session->set_flashdata('message', display('cancell_successfully'));
            } 
            else 
            {
                /*set exception message*/
                $this->session->set_flashdata('exception', display('please_try_again')." : database error!");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else
        {
            $this->session->set_flashdata('exception', display('please_try_again')." :refund error!");
            redirect($_SERVER['HTTP_REFERER']);
        }
        
    }
    public function confirm($appointment_id = null) 
    {
        $data = array();
        $data['status'] = 1;
        if($this->appointment_model->update($appointment_id, $data)) 
        {
            /*set success message*/
            $this->session->set_flashdata('message', display('confirm_successfully'));
        } 
        else 
        {
            /*set exception message*/
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    // create new patient
    public function create_patient(){
        if ($this->session->userdata('isLogIn') == false) 
        redirect('login'); 
        $data['module'] = display("appointment");
        $this->form_validation->set_rules('firstname', display('first_name'),'required|max_length[50]');
        $this->form_validation->set_rules('lastname', display('last_name'),'required|max_length[50]');
        $this->form_validation->set_rules('phone', display('phone'),'max_length[20]');
        $this->form_validation->set_rules('mobile', display('mobile'),'required|max_length[20]');
        $this->form_validation->set_rules('sex', display('sex'),'required|max_length[10]');
        $this->form_validation->set_rules('date_of_birth', display('date_of_birth'),'required|max_length[10]');
        $this->form_validation->set_rules('address', display('address'),'required|max_length[255]');
        #-------------------------------#
        
        //create a patient
        $data['patient'] = (object)$postData = [
            'patient_id'   => "P".$this->randStrGen(2,7),
            'firstname'    => $this->input->post('firstname'),
            'lastname'     => $this->input->post('lastname'),
            'email'        => $this->input->post('email'),
            'password'     => md5($this->input->post('password')),
            'phone'        => $this->input->post('phone'),
            'mobile'       => $this->input->post('mobile'),
            'blood_group'  => $this->input->post('blood_group'),
            'sex'          => $this->input->post('sex'), 
            'date_of_birth' => date('Y-m-d', strtotime(($this->input->post('date_of_birth') != null)? $this->input->post('date_of_birth'): date('Y-m-d'))),
            'address'      => $this->input->post('address'),
            'create_date'  => date('Y-m-d'),
            'created_by'   => $this->session->userdata('user_id'),
            'status'       => 1,
        ]; 
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            // check exist patient ID
            $rows = $this->db->select("patient_id")
                            ->from("patient")
                            ->where("patient_id", $postData['patient_id'])
                            ->get()
                            ->row();
            if(empty($rows)){
                if ($this->patient_model->create($postData)) {
                    $patient_id = $this->db->insert_id();
                    
                    $this->session->set_userdata(['patientID'=> $postData['patient_id']]);
                    #-------------------------------------------------------#
                #-------------------------SMS SEND -----------------------------#
                    #-------------------------------------------------------#
                    # SMS Setting
                    $setting = $this->db->select('registration')
                       ->from('sms_setting')
                       ->get()
                       ->row();

                    if (!empty($setting) && ($setting->registration==1))
                    { 
                        #-----------------------------------#
                        # SMS Gateway Setting
                        $gateway = $this->db->select('*')
                           ->from('sms_gateway')
                           ->where('default_status', 1)
                           ->get()
                           ->row();

                        #-----------------------------------#
                        # schedules list
                        $sms_teamplate = $this->db->select('teamplate')
                            ->from('sms_teamplate')
                            ->where('status', 1)
                            ->where('default_status', 1)
                            ->like('type', 'Registration', 'both')
                            ->get()
                            ->row();  
                            
                        #-----------------------------------#
                        # sms  

                        if(!empty($gateway) && !empty($sms_teamplate)) 
                        {
                            $this->load->library('smsgateway');
                            $template = $this->smsgateway->template([
                                'patient_name'   => $postData['firstname'].' '.$postData['lastname'],
                                'patient_id'     => $postData['patient_id'],
                                'message'        => $sms_teamplate->teamplate
                            ]); 

                            $this->smsgateway->send([
                                'apiProvider' => $gateway->provider_name,
                                'username'    => $gateway->user,
                                'password'    => $gateway->password,
                                'from'        => $gateway->authentication,
                                'to'          => $postData['mobile'],
                                'message'     => $template
                            ]);

                            // save delivary data
                            $this->db->insert('custom_sms_info', array(
                               'gateway' => $gateway->provider_name,
                               'reciver'          => $postData['mobile'],
                               'message'          => $template ,
                               'sms_date_time'    => date("Y-m-d h:i:s")
                            ));
                        }
                    }
                    #-------------------------------------------------------#
                #-------------------------SMS SEND -----------------------------#
                    #-------------------------------------------------------#


                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    #set exception message
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('appointment/create');
            }else{
                #set exception message
                $this->session->set_flashdata('exception', display('patient_id').' '.display('already_exists').' '.display('please_try_again'));
                redirect('appointment/create');
            }

        } else {
            $data['department_list'] = $this->department_model->department_list(); 
            $data['content'] = $this->load->view('appointment_form',$data,true);
            $this->load->view('layout/main_wrapper',$data);
        } 
    }

 

    /*
    |----------------------------------------------
    |        id genaretor
    |----------------------------------------------     
    */
    public function randStrGen($mode = null, $len = null)
    {
        $result = "";
        if($mode == 1):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 2):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif($mode == 3):
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 4):
            $chars = "0123456789";
        endif;

        $charArray = str_split($chars);
        for($i = 0; $i < $len; $i++) {
                $randItem = array_rand($charArray);
                $result .="".$charArray[$randItem];
        }
        return $result;
    }
    /*
    |----------------------------------------------
    |         Ends of id genaretor
    |----------------------------------------------
    */


    public function check_patient($mode = null){
        $patient_id = $this->input->post('patient_id');

        if (!empty($patient_id)) {
            $query = $this->db->select('firstname,lastname, mobile')
                ->from('patient')
                ->where('patient_id',$patient_id)
                ->where('status',1)
                ->get();

            if ($query->num_rows() > 0) {
                $result = $query->row();
                $data['message'] = $result->firstname . ' ' . $result->lastname .'-'.$result->mobile;
                $data['status'] = true;
            } else {
                $data['message'] = display('invalid_patient_id');
                $data['status'] = false;
            }
        } else {
            $data['message'] = display('invlid_input');
            $data['status'] = null;
        }

        //return data
        if ($mode === true) {
            return json_encode($data);
        } else {
            echo json_encode($data);
        }

    }
 

    public function doctor_by_department(){
        $department_id = $this->input->post('department_id');

        if (!empty($department_id)) {
            $query = $this->db->select('user_id,firstname,lastname')
                ->from('user')
                ->where('department_id',$department_id)
                ->where('user_role',2)
                ->where('status',1)
                ->get();

            $option = "<option value=\"\">".display('select_option')."</option>"; 
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $doctor) {
                    $option .= "<option value=\"$doctor->user_id\">$doctor->firstname $doctor->lastname</option>";
                } 
                $data['message'] = $option;
                $data['status'] = true;
            } else {
                $data['message'] = display('no_doctor_available');
                $data['status'] = false;
            }
        } else {
            $data['message'] = display('invalid_department');
            $data['status'] = null;
        }

        echo json_encode($data);
    }


    public function schedule_day_by_doctor()
    {
        $doctor_id = $this->input->post('doctor_id');
        $appointment_type = '1';       

        if (!empty($doctor_id)) {
            $query = $this->db->select('available_days,start_time,end_time')
                ->from('schedule')
                ->where('doctor_id',$doctor_id) 
                ->where('schedule_type',$appointment_type)
                ->where('status',1)
                ->order_by('available_days','desc')
                ->get();

            $list = null;
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $value) {
                    $list .= "<span><i class='fa fa-calendar'></i> $value->available_days [$value->start_time - $value->end_time]</span><br>";
                } 
                $data['message'] = $list;
                $data['status'] = true;
            } else {
                $data['message'] = display('no_schedule_available');
                $data['status'] = false;
            } 
        } else { 
            $data['status']  = null;
        }
        echo json_encode($data);
    }


    public function serial_by_date()
    {
        $patient_id = $this->input->post('patient_id');
        $doctor_id  = $this->input->post('doctor_id');
        $date       = date("Y-m-d", strtotime($this->input->post('date'))); 
        $day        = date("l", strtotime($this->input->post('date'))); 

        if (!empty($doctor_id) && !empty($patient_id) && !empty($day)) {
            $query = $this->db->select('*')
                ->from('schedule')
                ->where('doctor_id',$doctor_id) 
                ->where('available_days',$day) 
                ->where('status',1)
                ->order_by('available_days','desc')
                ->get();
 
            if ($query->num_rows() > 0) {
                $result = $query->row();
                /*--------- ------------------------------- */
                /*get start and end time*/
                $start_time   = strtotime($result->start_time);
                $end_time     = strtotime($result->end_time);

                /*convert per patient time to minute*/
                $time_parse = date_parse($result->per_patient_time);
                $minute = $time_parse['hour'] * 60 + $time_parse['minute'];

                /*count total minute*/
                $total_minute = round(abs($end_time - $start_time) / 60,2); 
                /*total serial*/  
                $total_serial = round(abs($total_minute / $minute));

                /*--------- ------------------------------- */ 
                $serial = null; 

                if ($result->serial_visibility_type == 2) {
                    /*set sequential */
                    $seq = 1;
                    $timestamp = strtotime($result->start_time);
                    while ($seq <= $total_serial) {
                        $time_from = date('H:i',$timestamp); 
                        $timestamp = strtotime("+$minute minutes" , $timestamp); 
                        $time_to   = date('H:i',$timestamp);

                        /*check time sequence*/
                        if ($this->check_time_sequence($doctor_id, $result->schedule_id, $seq, $date) === true) {
                            //store time sequential
                            $serial .= "<button type=\"button\" data-item=\"$seq\" class=\"serial_no slbtn btn btn-success btn-sm\">$time_from - $time_to</button>";
                        } else {
                            /*store time sequential*/
                            $serial .= "<div class=\"slbtn btn btn-danger disabled btn-sm\">$time_from - $time_to</div>";
                        }

                        $seq++;
                    } 
                    $data['type'] = display('sequential');
                } else {
                    /*set timestamp*/
                    $ts = 1;   
                    while ($ts <= $total_serial) {

                        /*check time sequence*/
                        if ($this->check_time_sequence($doctor_id, $result->schedule_id, $ts, $date) === true) {
                            //store timestamp
                            $serial .= "<button type=\"button\" data-item=\"$ts\" class=\"serial_no slbtn btn btn-success btn-sm\">".(($ts<=9)?"0$ts":$ts)."</button>";
                        } else {
                            /*store timestamp*/
                            $serial .= "<div class=\"slbtn btn btn-danger disabled btn-sm\">".(($ts<=9)?"0$ts":$ts)."</div>";
                        }

                        $ts++;
                    }
                    $data['type'] = display('timestamp');
                } 
                $data['schedule_id'] = $result->schedule_id;
                $data['message']     = $serial;
                $data['status']      = true;
                /*--------- ------------------------------- */  
            } else {
                $data['message'] = display('no_schedule_available');
                $data['status'] = false;
            } 
        } else { 
            $data['message'] = display('please_fillup_all_required_fields');
            $data['status']  = null;
        }

        echo json_encode($data);
    }
 

    public function check_time_sequence(
        $doctor_id  = null,
        $schedule_id  = null,
        $serial_no  = null,
        $date  = null
    ) {
        $num_rows = $this->db->select('*')
            ->from('appointment')
            ->where('doctor_id', $doctor_id)
            ->where('schedule_id', $schedule_id)
            ->where('serial_no', $serial_no)
            ->where('date', $date)
            ->get()
            ->num_rows();
            
        if ($num_rows == 0) {
            return true;
        } else {
            return false; 
        }
    }


    public function check_appointment_exists(
        $patient_id  = null,
        $doctor_id  = null,
        $schedule_id  = null,
        $date  = null 
    ) {
        if (!empty($patient_id) && !empty($doctor_id) && !empty($schedule_id)) {
            $num_rows = $this->db->select('*')
                ->from('appointment')
                ->where('patient_id', $patient_id)
                ->where('doctor_id', $doctor_id)
                ->where('schedule_id', $schedule_id) 
                ->where('date', $date)
                ->where('status', 1)
                ->or_where('status', 2)
                ->get()
                ->num_rows();
                
            if ($num_rows > 0) {
                return false;
            } else {
                return true; 
            }
        } else {
            return null; 
        }
    }

    // search patient by name and mobile number
    public function search_patient()
    {
        $output = '';
        $query = '';
        #------if query string true-----#
        if($this->input->post('query'))
        {
            $query = $this->input->post('query');
        }
        $data = $this->appointment_model->fetch_patient_data($query);
        
        if(!empty($data)){
            $output .= '
                  <div class="table-responsive">
                    <table style="cursor:pointer;font-size:12px" id="table1" class="table table-bordered table-striped">';

            if($data->num_rows() > 0){
                foreach($data->result() as $row){
                    $output .= '
                            <tbody>
                                <tr onclick="patientInfo(\''.$row->patient_id.'\')">
                                    <td id="pid">'.$row->patient_id.'</td>
                                    <td>'.$row->firstname.' '.$row->lastname.'</td>
                                    <td>'.$row->mobile.'</td>
                                    <td>'.$row->address.'</td>
                                </tr>
                            </tbody>
                    ';
                }
            }
            else
            {
                $output .= '<tr>
                                <td colspan="5">No Data Found</td>
                            </tr>';
            }
            $output .= '</table>';
            echo $output;
        }
    }

}
