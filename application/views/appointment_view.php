<div class="row">
   <?php
     if($this->permission->method('appointment_list','read')->access()){
    ?>
    <div class="col-sm-12" id="PrintMe">
        <div  class="panel panel-info"> 
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12 pT10">
                        <?php @$this->load->view('appointment/top_buttons');?>
                    </div>
                </div>
                <div class="row">
                    <?php @$this->load->view('appointment/doctor_details');?> 
                </div>
            </div>
            <div class="panel-body">  
                <div class="row">
                    <?php @$this->load->view('appointment/patient_details');?>
                </div>  
            </div> 
            <div class="panel-footer">
                <div class="text-center">
                    <strong><?php echo $this->session->userdata('title') ?></strong>
                    <p class="text-center"><?php echo $this->session->userdata('address') ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
         <div class="btn-group">
            <button type="button" onclick="printContent('PrintMe')" class="btn btn-danger" ><i class="fa fa-print"></i></button> 
        </div>
    </div>

<?php 
}
 else{
 ?>
    <div class="col-sm-12">
       <div class="panel panel-bd lobidrag">
        <div class="panel-heading">
          <div class="panel-title">
            <h4><?php echo display('you_do_not_have_permission_to_access_please_contact_with_administrator');?>.</h4>
           </div>
           </div>
         </div>
        </div>
 <?php
 }
 ?>



</div>
