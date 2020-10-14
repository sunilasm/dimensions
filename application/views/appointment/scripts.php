<script type="text/javascript">
$(document).ready(function() {
    //main_department_id
    $("#id").change(function(){
        var output = $('.doctor_error'); 
        var department_list = $('#department_id');
        //var available_day = $('#available_day');

        $.ajax({
            url  : '<?= base_url('website/appointment/sub_department/') ?>',
            type : 'post',
            dataType : 'JSON',
            data : {
                '<?= $this->security->get_csrf_token_name(); ?>' : '<?= $this->security->get_csrf_hash(); ?>',
                main_department_id : $(this).val()
            },
            success : function(data) 
            {
                if (data.status == true) 
                {
                    department_list.html(data.message);
                    // available_day.html(data.available_days);
                    output.html('');
                } 
                else if (data.status == false) 
                {
                    department_list.html('');
                    output.html(data.message).addClass('text-danger').removeClass('text-success');
                } 
                else 
                {
                    department_list.html('');
                    output.html(data.message).addClass('text-danger').removeClass('text-success');
                }
            }, 
            error : function(xhr, status, error)
            {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err);
                alert(err.Message);
                //alert('failed');
            }
        });
    }); 
    //department_id
    $("#department_id").change(function(){
        var output = $('.doctor_error'); 
        var doctor_list = $('#doctor_id');
        var available_day = $('#available_day');

        $.ajax({
            url  : '<?= base_url('website/appointment/doctor_by_department/') ?>',
            type : 'post',
            dataType : 'JSON',
            data : {
                '<?= $this->security->get_csrf_token_name(); ?>' : '<?= $this->security->get_csrf_hash(); ?>',
                department_id : $(this).val()
            },
            success : function(data) 
            {
                //console.log(data)
                $('#price').val(data.price);
                $('#price_code').val(data.price_code);
                if (data.status == true) {
                    doctor_list.html(data.message);
                    available_day.html(data.available_days);
                    output.html('');
                } else if (data.status == false) {
                    doctor_list.html('');
                    output.html(data.message).addClass('text-danger').removeClass('text-success');
                } else {
                    doctor_list.html('');
                    output.html(data.message).addClass('text-danger').removeClass('text-success');
                }
            }, 
            error : function(xhr, status, error)
            {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err);
                alert(err.Message);
            }
        });
    }); 
    //doctor_id
    $("#doctor_id").change(function(){
        var doctor_id = $('#doctor_id'); 
        var output = $('#available_days');
        //console.log($('#appointment_type_id').val());
        <?php if(isset($appointment_type)) : ?>
        var schedule_type = $('input[name="appointment_type_id"]:checked').val();
        <?php else: ?>
        var schedule_type = $('#appointment_type_id').val();
        <?php endif; ?>
        console.log("Schedule type:"+schedule_type);

        $.ajax({
            url  : '<?= base_url('website/appointment/schedule_day_by_doctor/') ?>',
            type : 'post',
            dataType : 'JSON',
            data : {
                '<?= $this->security->get_csrf_token_name(); ?>' : '<?= $this->security->get_csrf_hash(); ?>',
                doctor_id : $(this).val(),
                schedule_type : schedule_type
            },
            success : function(data) 
            {
                //console.log(data);
                if (data.status == true) {
                    output.html(data.message).addClass('text-success').removeClass('text-danger');
                } else if (data.status == false) {
                    output.html(data.message).addClass('text-danger').removeClass('text-success');
                } else {
                    output.html(data.message).addClass('text-danger').removeClass('text-success');
                }
            }, 
            error : function(xhr, status, error)
            {
                console.log(error);
                alert('failed');
                // var err = eval("(" + xhr.responseText + ")");
                // console.log(err);
                //alert(err.Message);
            }
        });
    });
    
    $(".appointment_type_id").change(function(){
        //console.log( "ID:" + $(".appointment_type_id").val());
        console.log("appointment_type:"+this.value);
        // if (this.value == '2') {
            
        //     alert("Allot Thai Gayo Bhai");
        // }
        // else if (this.value == '1') {
        //     alert("Transfer Thai Gayo");
        // }
    });
    //date
    $("#date").change(function(){
        var date            = $('#date'); 
        var serial_preview  = $('#serial_preview'); 
        var doctor_id       = $('#doctor_id'); 
        var schedule_id     = $("#schedule_id"); 
        var patient_id      = $("#patient_id"); 
 
        $.ajax({
            url  : '<?= base_url('website/appointment/serial_by_date/') ?>',
            type : 'post',
            dataType : 'JSON',
            data : {
                '<?= $this->security->get_csrf_token_name(); ?>' : '<?= $this->security->get_csrf_hash(); ?>',
                doctor_id  : doctor_id.val(),
                patient_id : patient_id.val(), 
                date : $(this).val()
            },
            success : function(data) 
            { 
                if (data.status == true) {
                    //set schedule id
                    schedule_id.val(data.schedule_id); 
                    serial_preview.html(data.message);
                } else if (data.status == false) {
                    schedule_id.val('');
                    serial_preview.html(data.message).addClass('text-danger').removeClass('text-success');
                } else {
                    schedule_id.val('');
                    serial_preview.html(data.message).addClass('text-danger').removeClass('text-success');
                }
            }, 
            error : function(xhr, status, error)
            {
                alert('failed');
            }
        });
    });
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
    $(".datepicker1").datepicker({
        minDate: 0,
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,        
        todayHighlight: true,
        startDate: today, 
        //beforeShowDay: DisableDays 
     });
});
</script>