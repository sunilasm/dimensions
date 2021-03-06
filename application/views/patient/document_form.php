<div class="row">
    <!--  form area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail"> 
            <?php
             if($this->permission->method('document_list','read')->access() || $this->permission->method('document_list','delete')->access()){
            ?>
            <div class="panel-heading">
                <div class="btn-group">
                    <a class="btn btn-success" href="<?php echo base_url("patient/document") ?>"> <i class="fa fa-list"></i>  <?php echo display('document_list') ?> </a>  
                </div>
            </div>
            <?php } ?>


             <?php
              if($this->permission->method('add_document','create')->access() ){
             ?>
              <div class="panel-body">
                <div class="row">
                    <div id="output" class="hide alert"></div>
                    <div class="col-md-9 col-sm-12">
                        <?php echo form_open_multipart('patient/document_form','class="form-inner" id="mailForm" ') ?>

                            <div class="form-group row">
                                <label for="patient_id" class="col-xs-3 col-form-label"><?php echo display('patient_id')?> <i class="text-danger">*</i></label>
                                <div class="col-xs-9">
                                    <input name="patient_id"  type="text" class="form-control" id="patient_id" placeholder="<?php echo display('patient_id')?>" value="<?php echo (($this->uri->segment(3)!=null)?$this->uri->segment(3):$document->patient_id) ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="attach_file" class="col-xs-3 col-form-label"><?php echo display('attach_file') ?> <i class="text-danger">*</i></label>
                                <div class="col-xs-9">
                                    <input type="file" name="attach_file" id="attach_file">

                                    <input type="hidden" name="hidden_attach_file" id="hidden_attach_file" value="<?php echo $document->hidden_attach_file ?>">

                                    <p id="upload-progress" class="hide alert"></p>
                                </div>
                            </div> 

                            <?php if($this->session->userdata('user_role')!=2){ ?>
                            <div class="form-group row">
                                <label for="doctor_id" class="col-xs-3 col-form-label"><?php echo display('doctor_name') ?> <i class="text-danger">*</i></label>
                                <div class="col-xs-9">
                                    <?php echo form_dropdown('doctor_id',$doctor_list,$document->doctor_id,'class="form-control" id="doctor_id"') ?>
                                    <div id="available_days"></div>
                                </div>
                            </div> 
                            <?php }?>

                            <div class="form-group row">
                                <label for="description" class="col-xs-3 col-form-label"><?php echo display('document_title') ?> <i class="text-danger">*</i></label>
                                <div class="col-xs-9">
                                    <input name="description" class="form-control"  placeholder="<?php echo display('document_title') ?>" value="<?= $document->description?>">
                                </div>
                            </div>  

                            <div class="form-group row">
                                <div class="col-sm-offset-3 col-sm-6">
                                    <div class="ui buttons">
                                        <button type="reset" class="ui button"><?php echo display('reset') ?></button>
                                        <div class="or"></div>
                                        <button class="ui positive button"><?php echo display('send') ?></button>
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


<script type="text/javascript">
$(function(){
    var browseFile = $('#attach_file');
    var form       = $('#mailForm');
    var progress   = $("#upload-progress");
    var hiddenFile = $("#hidden_attach_file");
    var output     = $("#output");
    browseFile.on('change',function(e)
    {
        e.preventDefault(); 
        uploadData = new FormData(form[0]);

        $.ajax({
            url      : '<?php echo base_url('patient/do_upload') ?>',
            type     : form.attr('method'),
            dataType : 'json',
            cache    : false,
            contentType : false,
            processData : false,
            data     : uploadData, 
            beforeSend  : function() 
            {
                hiddenFile.val('');
                progress.removeClass('hide').html('<i class="fa fa-cog fa-spin"></i> Loading..');
            },
            success  : function(data) 
            { 
                progress.addClass('hide');
                if (data.status == false) {
                    output.html(data.exception).addClass('alert-danger').removeClass('hide').removeClass('alert-info');
                } else if (data.status == true ) {
                    output.html(data.message).addClass('alert-info').removeClass('hide').removeClass('alert-danger');
                    hiddenFile.val(data.filepath);
                }  
            }, 
            error    : function() 
            {
                progress.addClass('hide');
                output.addClass('hide');
                alert('failed!');
            }   
        });
    });



});
</script>