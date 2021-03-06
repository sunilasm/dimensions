<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="panel panel-default thumbnail">
            <?php
            if($this->permission->method('add_document','create')->access() ){
            ?>
            <div class="panel-heading">
                <div class="btn-group">
                    <a class="btn btn-success" href="<?php echo base_url("patient/document_form") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_document') ?> </a>  
                </div>
            </div>
            <?php } ?>


            <?php
            if($this->permission->method('document_list','read')->access() || $this->permission->method('document_list','delete')->access()){
            ?>
            <div class="panel-body"> 
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('serial') ?></th>
                            <th><?php echo display('patient_id') ?></th>
                            <th><?php echo display('doctor_name') ?></th>
                            <th><?php echo display('document_title') ?></th>
                            <th><?php echo display('date') ?></th>
                            <th><?php echo display('upload_by') ?></th>
                            <?php
                            if($this->permission->method('document_list','read')->access() || $this->permission->method('document_list','delete')->access()){
                            ?>
                            <th><?php echo display('action') ?></th> 
                            <?php } ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($documents)) { ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($documents as $document) { ?>
                                <tr>
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo $document->patient_id; ?></td>
                                    <td><?php echo $document->doctor_name; ?></td>
                                    <td><?php echo character_limiter(strip_tags($document->description),50); ?></td>
                                    <td><?php echo $document->date; ?></td> 
                                    <td><?php echo $document->upload_by; ?></td>

                                    <?php
                                    if($this->permission->method('document_list','read')->access() || $this->permission->method('document_list','delete')->access()){
                                    ?>
                                    <td class="center" width="80">

                                        <?php
                                        if($this->permission->method('document_list','read')->access()){
                                        ?>
                                            <a target="_blank" href="<?php echo base_url($document->hidden_attach_file) ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                        <?php } ?>

                                        <?php
                                        if($this->permission->method('document_list','read')->access()){
                                        ?>
                                            <a download href="<?php echo base_url($document->hidden_attach_file) ?>" class="btn btn-xs btn-success"><i class="fa fa-download"></i></a>
                                        <?php } ?>

                                        <?php
                                        if($this->permission->method('document_list','delete')->access()){
                                        ?>
                                           <a href="<?php echo base_url("patient/document_delete/$document->id?file=$document->hidden_attach_file") ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') "><i class="fa fa-trash"></i></a> 
                                        <?php } ?>

                                    </td>
                                    <?php } ?>

                                </tr>
                                <?php $sl++; ?>
                            <?php } ?> 
                        <?php } ?> 
                    </tbody>
                </table>  <!-- /.table-responsive -->
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
 
 