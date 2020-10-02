<div class="row">
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
            <?php
            if($this->permission->method('leaves','read')->access()  || $this->permission->method('leaves','update')->access() || $this->permission->method('leaves','delete')->access()){
            ?>
            <div class="panel-heading no-print">
                <div class="btn-group"> 
                    <a class="btn btn-primary" href="<?php echo base_url("leaves") ?>"> <i class="fa fa-list"></i>  <?php echo display('leaves') ?> </a>  
                </div>
            </div> 
            <?php } ?>


            <?php
            if($this->permission->method('leaves','create')->access()){
            ?>
            <div class="panel-body panel-form">
                <div class="row">
                  
                </div>
            </div>
            <?php 
            }
            else{
                ?>
                <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-bd lobidrag">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                      <h4><?php echo display('you_do_not_have_permission_to_access_please_contact_with_administrator');?>.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
            }
             ?>
            
        </div>
    </div>

</div>