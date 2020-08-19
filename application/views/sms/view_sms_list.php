<?php
if($this->permission->method('user_sms_list','read')->access()){
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="sms">
                    <thead>
                        <tr>
                            <th class=""><?php echo display('reciver');?></th>
                            <th class=""><?php echo display('provider');?></th>
                            <th class=""><?php echo display('date_time');?></th>
                            <th class=""><?php echo display('message');?></th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach($coustom_sms as $value){?>
                        <tr>
                            <td><?php echo $value->reciver;?></td>
                            <td><?php echo $value->gateway;?></td>
                            <td><?php echo $value->sms_date_time;?></td>
                            <td><?php echo $value->message;?></td>
                        </tr>
                        <?php
                            }
                        ?>

                         <?php foreach($auto_sms as $value){?>
                        <tr>
                            <td><?php echo $value->reciver;?></td>
                            <td><?php echo $value->gateway;?></td>
                            <td><?php echo $value->sms_date_time;?></td>
                            <td><?php echo $value->message;?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
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