
<div class="row">
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
            <?php
             if($this->permission->method('leaves','create')->access()){
            ?>
            <div class="panel-heading no-print">
                <div class="btn-group"> 
                    <a class="btn btn-success" href="<?php echo base_url("leaves/create") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_leave') ?> </a>  
                </div>
            </div>
            <?php } ?>

            <?php
            if($this->permission->method('leaves','read')->access()  || $this->permission->method('leaves','update')->access() || $this->permission->method('leaves','delete')->access()){
            ?>
            <div class="panel-body">
                <!-- Nav tabs --> 
                <ul class="col-xs-12 nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"> 
                        <a href="#home" aria-controls="home" role="tab" data-toggle="tab"> <i class="fa fa-list"></i> <?php echo display('leaves') ?></a>
                    </li>
                </ul>  

                <!-- Tab panes --> 
                <div class="col-xs-12 tab-content">
                      <div role="tabpanel" class="tab-pane active" id="home">
                        <div class="row">
                            <div class="col-md-12"> 
                                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('serial') ?></th>
                                           <!-- <th><?php echo display('emp_id') ?></th>
                                            <th><?php echo display('department_names') ?></th>-->
                                            <th><?php echo display('leave_type') ?></th>
                                            <th><?php echo display('from_date') ?></th>
                                            <th><?php echo display('to_date') ?></th>
                                            <th><?php echo display('leave_description') ?></th>
                                            <th><?php echo display('manager_description') ?></th>
                                            <th><?php echo display('status') ?></th>
                                            <th><?php echo display('Action') ?></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($leaves)) { ?>
                                            <?php $sl = 1; ?>
                                            <?php foreach ($leaves as $leave) { ?>
                                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                                    <td><?php echo $sl; ?></td>
                                                    <!--<td><?php echo $leave->emp_id; ?></td>
                                                     <td><?php echo $leave->department_names; ?></td>-->
                                                    <td><?php echo $leave->leave_type; ?></td>
                                                    <td><?php echo $leave->from_date; ?></td>
                                                    <td><?php echo $leave->to_date; ?></td>
                                                    <td><?php echo $leave->leave_description; ?></td>
                                                    <td><?php echo $leave->manager_description; ?></td>
                                                    <td><?php echo $leave->status; ?></td>
                                                    <!--<td><?php echo (($employee->status==1)?display('active'):display('inactive')); ?></td>-->
                                                    <td class="center">                                                   
                                                        <a href="<?php echo base_url("leaves/view/$leave->user_id") ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>  
                                                    </td>                                                   
                                                   
                                                </tr>
                                                <?php $sl++; ?>
                                            <?php } ?> 
                                        <?php } ?> 
                                    </tbody>
                                </table>  <!-- /.table-responsive -->
                            </div>
                        </div>
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

