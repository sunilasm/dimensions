<section>
    <div class="container">
        <!--Grid row-->
        <div class="row">
            <div class="col-md-12" style="margin-bottom:10px; margin-top:20px;">
                <h4 class="mb-3"><?php echo display('thank_you'); ?></h4>

                <?php if (isset($message) && $message != '') { ?>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <strong><?php echo $message; ?></strong>
                    </div>
                <?php } ?>
                <?php if(isset($redirect_url)) : ?>
                    <?php $redirect_url = (isset($redirect_url)) ? $redirect_url : ''; ?>
                    <a href="<?php echo base_url($redirect_url);?>" class="btn btn-block btn-primary">View Details</a>
                <?php endif; ?>
            </div>
        </div>
         <!--Grid row-->
    </div>
</section>