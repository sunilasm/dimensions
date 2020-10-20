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
                <div class="col-md-9 col-sm-12">

                    <?php echo form_open_multipart('leaves/create','class="form-inner"') ?> 

                        <?php echo form_hidden('leave_id',$leave->leave_id) ?>

                       <div class="form-group row">
                            <label for="emp_id" class="col-xs-3 col-form-label"><?php echo display('emp_id') ?></label>
                            <div class="col-xs-9">
                                <input name="emp_id" readonly="true"  type="text" class="form-control" id="emp_id" placeholder="<?php echo display('emp_id') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="leave_id" class="col-xs-3 col-form-label"><?php echo display('department_names') ?> </label>
                                <div class="col-xs-9">
                                    <?php echo form_dropdown('leave_id', $department_list, $leave->leave_id, array('id'=>'leave_id', 'class'=>'form-control')) ?>
                                </div>
                            </div>

                        <div class="form-group row">
                                <label for="firstname" class="col-xs-3 col-form-label"><?php echo display('first_name') ?> </label>
                                <div class="col-xs-9">
                                    <input name="firstname" readonly="true" type="text" class="form-control" id="firstname" placeholder="<?php echo display('first_name') ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lastname" class="col-xs-3 col-form-label"><?php echo display('last_name') ?> </label>
                                <div class="col-xs-9">
                                    <input name="lastname" readonly="true" type="text" class="form-control" id="lastname" placeholder="<?php echo display('last_name') ?>">
                                </div>
                            </div>
                        <div class="form-group row">
                                <label for="email" class="col-xs-3 col-form-label"><?php echo display('email')?> </label>
                                <div class="col-xs-9">
                                    <input name="email" readonly="true" class="form-control" type="text" placeholder="<?php echo display('email')?>" id="email">
                                </div>
                            </div>

                        <div class="form-group row">
                            <label for="leave_type" class="col-xs-3 col-form-label"><?php echo display('leave_type') ?><i class="text-danger">*</i></label>
                            <div class="col-xs-9"> 
                                <?php 
                                    $leaveTypes = [
                                        '1' => display('casualleave'),
                                        '2' => display('sickleave'),
                                        '3' => display('medicalleave'),
                                    ];
                                    echo form_dropdown('leave_type',$leaveTypes,$leave->leave_type,'class="form-control" id="leave_type"'); 
                                ?>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="from_date" class="col-xs-3 col-form-label"><?php echo display('from_date') ?> <i class="text-danger">*</i></label>
                            <div class="col-xs-9">
                                <input name="from_date" class="form-control datepicker" type="text" placeholder="<?php echo display('from_date') ?>" id="from_date" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="to_date" class="col-xs-3 col-form-label"><?php echo display('to_date') ?> <i class="text-danger">*</i></label>
                            <div class="col-xs-9">
                                <input name="to_date" class="form-control datepicker" type="text" placeholder="<?php echo display('to_date') ?>" id="to_date" autocomplete="off">
                            </div>
                        </div>

                        <!--<div class="form-group row">
                            <label for="date" class="col-xs-3 col-form-label"><?php echo display('from_date') ?><i class="text-danger">*</i></label>
                            <div class="col-xs-9">
                                 <input type="text" class="form-control datepicker" name="date" id="from_Date" placeholder="<?php echo display('from_date')?>">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label for="date" class="col-xs-3 col-form-label"><?php echo display('to_date') ?><i class="text-danger">*</i></label>
                            <div class="col-xs-9">
                                 <input type="text" class="form-control datepicker" name="date" id="to_date" placeholder="<?php echo display('to_date')?>">
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label for="leave_description" class="col-xs-3 col-form-label"><?php echo display('leave_description') ?></label>
                            <div class="col-xs-9">
                                <textarea name="leave_description" class="form-control"  placeholder="<?php echo display('leave_description') ?>" rows="7"><?php echo $leave->leave_description ?></textarea>
                            </div>
                        </div>  
                        
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-6">
                                <div class="ui buttons">
                                    <button type="reset" class="ui button"><?php echo display('reset') ?></button>
                                    <div class="or"></div>
                                    <button class="ui positive button"><?php echo display('save') ?></button>
                                </div>
                            </div>
                        </div>

                    <?php echo form_close() ?>

                    </div>
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