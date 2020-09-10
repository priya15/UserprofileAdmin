 <style>
     form.user .form-control-user {
    font-size: .8rem;
    border-radius: 10rem !important;
    padding: 1.5rem 1rem;
}
 </style>
<div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
 <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            
            <div class="col-lg-7">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Change Password</h1>
                    </div>
                    <?php
        $this->load->helper('form');
        $error = $this->session->flashdata('error');
        if($error)
        {
            ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error; ?>                    
            </div>
        <?php }
        $success = $this->session->flashdata('success');
        if($success)
        {
            ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $success; ?>                    
            </div>
        <?php } ?>
        <form class="user" method="post" action="<?php echo base_url('changePassword'); ?>" onsubmit="if($('#one').val() == $('#two').val()) { return true; } else { $('#msg').show(); return false;}">
                        
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" name="oldPassword" id="" placeholder="Enter Old Password...">
                        </div>
                        <div class="form-group">
                            <input type="password" name="newPassword" class="form-control form-control-user" id="one" placeholder="Enter New Password...">
                        </div>
                        <div class="form-group">
                            <input type="password" name="cNewPassword" class="form-control form-control-user" id="two" placeholder="Enter Confirm Password...">
                            <span id="msg" style='color:red;display:none;'>Password and Confirm Password Not Match</span>
                        </div>
                       
                        <input type="submit"  class="btn btn-primary btn-user btn-block" value="Change Password">
         
        </a>
                      
                        
                    </form>
                   
                </div>
            </div>

            <!--<div class="col-lg-5 d-none d-lg-block bg-register-image"></div>-->
            <div class="col-lg-5 d-none d-lg-block" style= 'background: url(<?=base_url('assets/logo.png')?>);background-size:368px;background-repeat: no-repeat;background-origin: content-box;padding-top: 78px;'></div>
        </div>
 </div>
        </div>
</div>

 