<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="panel panel-default thumbnail">

            <?php
            if($this->permission->method('report','create')->access()){
            ?>
            <div class="panel-heading">
                <div class="btn-group">
                    <a class="btn btn-success" href="<?php echo base_url("noticeboard/notice/form") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_notice') ?> </a>  
                </div>
            </div>
            <?php } ?>


       <?php
        if($this->permission->method('notice_list','read')->access() || $this->permission->method('notice_list','update')->access() || $this->permission->method('notice_list','delete')->access()){
        ?>
            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('serial') ?></th>
                            <th><?php echo display('title') ?></th>
                            <th><?php echo display('description') ?></th>
                            <th><?php echo display('start_date') ?></th>
                            <th><?php echo display('end_date') ?></th>
                            <th><?php echo display('assign_by') ?></th>
                            <th><?php echo display('status') ?></th> 
                            <?php
                            if($this->permission->method('notice_list','read')->access() || $this->permission->method('notice_list','update')->access() || $this->permission->method('notice_list','delete')->access()){
                            ?>
                            <th><?php echo display('action') ?></th> 
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($notices)) { ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($notices as $notice) { ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo $notice->title; ?></td>
                                    <td><?php echo character_limiter(strip_tags($notice->description),50); ?></td>
                                    <td><?php echo $notice->start_date; ?></td> 
                                    <td><?php echo $notice->end_date; ?></td> 
                                    <td><?php echo $notice->assign_by; ?></td> 
                                    <td><?php echo (($notice->status==1)?display('active'):display('inactive')); ?></td>


                                    <?php
                                    if($this->permission->method('notice_list','read')->access() || $this->permission->method('notice_list','update')->access() || $this->permission->method('notice_list','delete')->access()){
                                    ?>
                                    <td class="center" width="80">

                                     <?php
                                     if($this->permission->method('notice_list','read')->access()){
                                     ?>
                                        <a href="<?php echo base_url("noticeboard/notice/details/$notice->id") ?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a> 
                                     <?php } ?>

                                     <?php
                                     if($this->permission->method('notice_list','update')->access()){
                                     ?>
                                        <a href="<?php echo base_url("noticeboard/notice/form/$notice->id") ?>" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a> 
                                     <?php } ?>

                                     <?php
                                     if($this->permission->method('notice_list','delete')->access()){
                                     ?>
                                        <a href="<?php echo base_url("noticeboard/notice/delete/$notice->id") ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') "><i class="fa fa-trash"></i></a> 
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
 
 