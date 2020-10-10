<div class="form-group">
    <label> <?= display('branch_name')?> *</label>
        <?php echo form_dropdown('id',$main_department_list,$appointment->id,'class="form-control basic-single" id="id"') ?>
    <span class="main_department_error"></span>
</div>
<div class="form-group">
    <label> <?= display('department_name')?> *</label>
        <?php echo form_dropdown('department_id',$department_list,$appointment->department_id,'class="form-control basic-single" id="department_id"') ?>
    <span class="doctor_error"></span>
</div>
<div class="form-group">
    <label> <?= display('appointment_type')?> *</label>
        <?php echo form_dropdown('schedule_type',$appointment_type,$appointment->appointment_type_id,'class="form-control basic-single" id="appointment_type_id"') ?>
</div>
<div class="form-group">
    <label> <?= display('doctor_name')?>*</label>
    <?php echo form_dropdown('doctor_id','','','class="form-control basic-single" id="doctor_id"') ?>
            <p class="help-block" id="available_days"></p>
</div>
<div class="form-group">
    <label><?= display('appointment_date')?> *</label>
    <input type="text" class="form-control datepicker" name="date" id="date" placeholder="<?= display('appointment_date')?>" autocomplete="off">
</div>
    <div class="form-group">
    <label><?php echo display('serial_no') ?> <i class="text-danger">*</i></label>
    <div id="serial_preview">
        <div class="btn btn-success disabled btn-sm"> 01</div>
        <div class="btn btn-success disabled btn-sm"> 02</div>
        <div class="btn btn-success disabled btn-sm"> 03</div>...
        <div class="slbtn btn btn-success disabled btn-sm"> N</div>
    </div>
    <input type="hidden" name="schedule_id" id="schedule_id"/>
    <input type="hidden" name="serial_no" id="serial_no"/>
</div>
<div class="form-group">
    <label><?= display('problem')?></label>
    <textarea class="form-control" name="problem" id="problem2" rows="3"></textarea>
</div>
<div class="form-group">
    <label> <?= display('payment_type')?> *</label>
        <?php echo form_dropdown('payment_type_id',$payment_type_list,$appointment->payment_type_id,'class="form-control basic-single" id="payment_type_id"') ?>
    <span class="doctor_error"></span>
</div>