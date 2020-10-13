<div class="col-sm-6 col-md-8">
    <dl class="dl-horizontal">
        <dt><?php echo display('doctor') ?></dt> <dd><?php echo "$appointment->firstname $appointment->lastname"?></dd>
        <dt><?php echo display('branch_name') ?></dt> <dd><?php echo $appointment->branch_name ?></dd> 
        <dt><?php echo display('department') ?></dt> <dd><?php echo $appointment->department ?></dd>
        <dt><?php echo display('education_degree') ?></dt> <dd><?php echo $appointment->degree ?></dd>
        <dt><?php echo display('available_days') ?></dt> <dd><?php echo $appointment->available_days ?></dd>
        <dt><?php echo display('schedule_type') ?></dt> <dd><?php echo print_schedule_type($appointment->schedule_type); ?></dd>
        <dt><?php echo display('schedule_time') ?></dt> <dd><?php echo "$appointment->start_time - $appointment->end_time" ?></dd>
    </dl>
</div>
<div class="col-sm-6 col-md-4">
    <dl class="dl-horizontal">
        <dt><?php echo display('serial_no') ?></dt> <dd>#<?php echo ($appointment->serial_no<=9)?"0$appointment->serial_no":$appointment->serial_no ?></dd>
        <dt><?php echo display('appointment_date') ?></dt> <dd><?php echo print_date($appointment,'date') ?></dd>
    </dl>
</div>