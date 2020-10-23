<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
 
            <div class="panel-heading no-print">
                <div class="btn-group"> 
                    <a class="btn btn-success" href="<?php echo base_url("dashboard_patient/appointment/appointment/create") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_appointment') ?> </a>  
                </div>
            </div> 
            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('serial') ?></th>
                            <th><?php echo display('appointment_id') ?></th>
                            <th><?php echo display('patient_id') ?></th>
                            <th><?php echo display('department') ?></th>
                            <th><?php echo display('doctor_name') ?></th>
                            <th><?php echo display('start_time').' - '.display('end_time') ?></th>
                            <th><?php echo display('schedule_type') ?></th>
                            <!-- <th><?php echo display('problem') ?></th> -->
                            <th><?php echo display('appointment_date') ?></th>
                            <th><?php echo display('status') ?></th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)) { ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($appointments as $appointment) { ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td>
                                      <a href="<?php echo base_url("appointment/view/$appointment->appointment_id") ?>" class=""><?php echo $appointment->appointment_id; ?></i></a> 
                                      
                                    </td>
                                    <td><?php echo $appointment->patient_id; ?></td>
                                    <td><?php echo $appointment->name; ?></td>
                                    <td><?php echo $appointment->firstname.' '.$appointment->lastname; ?></td>
                                    <td><?php echo $appointment->start_time.' - '.$appointment->end_time; ?></td>
                                    <td><?php echo ($appointment->schedule_type == 1) ? 'Inperson' : 'Online'; ?></td>
                                    <!-- <td><?php echo $appointment->problem; ?></td> -->
                                    <td><?php echo print_date($appointment, 'date'); ?></td>
                                    <td><?php echo get_appointment_status($appointment->status); ?></td>
                                    <td class="center">
                                        <a href="<?php echo base_url("dashboard_patient/appointment/appointment/view/$appointment->appointment_id") ?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>
                                        <?php
                                         if($appointment->status == 2 || $appointment->status == 1){
                                        ?>
                                          <a href="<?php echo base_url("dashboard_patient/appointment/appointment/cancell/$appointment->appointment_id") ?>" onclick="return confirm('<?php echo display('are_you_sure') ?>')" class="btn btn-xs btn-danger"><i class="fa fa-times-circle"></i></a> 
                                        <?php } ?>
                                        <?php
                                         if($appointment->status == 2 ){
                                        ?>
                                          <a href="<?php echo base_url("dashboard_patient/appointment/appointment/confirm/$appointment->appointment_id") ?>" onclick="return confirm('<?php echo display('are_you_sure') ?>')" class="btn btn-xs btn-danger"><i class="fa fa-check-circle"></i></a> 
                                        <?php } ?>
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