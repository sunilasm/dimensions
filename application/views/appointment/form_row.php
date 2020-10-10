<div class="form-group row">
    <label class="col-xs-3 col-form-label"> <?= display('branch_name')?> *</label>
    <div class="col-xs-9">
        <?php echo form_dropdown('id',$main_department_list,$appointment->id,'class="form-control basic-single" id="id"') ?>
        <span class="main_department_error"></span>
    </div>
</div>
<div class="form-group row">
    <label for="department_id" class="col-xs-3 col-form-label"><?php echo display('department_name') ?> <i class="text-danger">*</i></label>
    <div class="col-xs-9">
        <?php echo form_dropdown('department_id',$department_list,$appointment->department_id,'class="form-control" id="department_id"') ?>
        <span class="doctor_error"></span>
    </div>
</div>
<div class="form-group row">
    <label class="col-xs-3 col-form-label"> <?= display('appointment_type')?> *</label>
    <div class="col-xs-9">
        <?php echo form_dropdown('schedule_type',$appointment_type,$appointment->appointment_type_id,'class="form-control basic-single" id="appointment_type_id"') ?>
    </div>
</div>
<div class="form-group row">
    <label for="doctor_id" class="col-xs-3 col-form-label"><?php echo display('doctor_name') ?> <i class="text-danger">*</i></label>
    <div class="col-xs-9">
        <?php echo form_dropdown('doctor_id','','','class="form-control" id="doctor_id"') ?>
        <div id="available_days"></div>
    </div>
</div> 

<div class="form-group row">
    <label for="date" class="col-xs-3 col-form-label"><?php echo display('appointment_date') ?> <i class="text-danger">*</i></label>
    <div class="col-xs-9"> 
        <input name="date" type="text" class="datepicker-avaiable-days form-control" id="date" placeholder="<?php echo display('appointment_date') ?>" >
    </div>
</div>


<div class="form-group row">
    <label for="serial_no" class="col-xs-3 col-form-label"><?php echo display('serial_no') ?> <i class="text-danger">*</i></label>
    <div class="col-xs-9">
        <div id="serial_preview">
            <div type="button" class="btn btn-success disabled btn-sm"> 01</div>
            <div type="button" class="btn btn-success disabled btn-sm"> 02</div>
            <div type="button" class="btn btn-success disabled btn-sm"> 03</div>...
            <div type="button" class="slbtn btn btn-success disabled btn-sm"> N</div>

        </div>
        <input type="hidden" name="schedule_id" id="schedule_id"/>
        <input type="hidden" name="serial_no" id="serial_no"/>
    </div>
</div> 

<div class="form-group row">
    <label for="problem" class="col-xs-3 col-form-label"><?php echo display('problem') ?></label>
    <div class="col-xs-9">
        <textarea name="problem" class="form-control" placeholder="<?php echo display('problem') ?>" maxlength="140" rows="7"></textarea>
    </div>
</div>
<?php if(isset($payment_type_list)) : ?>
<div class="form-group row">
    <label class="col-xs-3 col-form-label"> <?= display('payment_type')?> *</label>
    <div class="col-xs-9">
        <?php echo form_dropdown('payment_type_id',$payment_type_list,$appointment->payment_type_id,'class="form-control basic-single" id="payment_type_id"') ?>
        <span class="doctor_error"></span>
    </div>
</div>
<?php endif; ?>