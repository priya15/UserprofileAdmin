 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Subadmin List </h1>
<div class="row">
            <div class="col-md-8">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                  <h6 style="color:red;"><?php echo validation_errors(); ?></h6>


        <form class="user" method="post" action="<?=base_url('addsubadmindata')?>" >
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="name" aria-describedby="emailHelp" required placeholder="Enter name">
                    </div>
                      <div class="form-group">
                      <input type="email" class="form-control form-control-user" style='border-radius: 10rem !important;' name="email" aria-describedby="emailHelp" required placeholder="Enter Email">
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="mobile" aria-describedby="emailHelp" required placeholder="Enter mobile">
                    </div>

              <!---      <div class="form-group">
                      <input type="file"  style='border-radius: 10rem !important;' name="image" aria-describedby="emailHelp"  class="btn btn-default btn-file">
                    </div>-->
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" style='border-radius: 10rem !important;' name="password" aria-describedby="emailHelp"  placeholder="Enter password" id="password" required>
                    </div> 

                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" style='border-radius: 10rem !important;' name="cppassword" aria-describedby="emailHelp"  placeholder="Enter Confirm password" id="cpassword" required>
                      <span id='message'></span>

                      </div> 
                    <input type= 'submit' id="submit" href="#" class="btn btn-primary btn-user btn-block" value="Add Subadmin">
                      
                   
                  </form>
</div>
</div>
</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script type="text/javascript">
jQuery('#password, #cpassword').on('keyup', function () {
  if (jQuery('#password').val() == $('#cpassword').val()) {
     jQuery('#message').html('Matching').css('color', 'green');
     jQuery("#submit").attr("disabled", false);
     return true;
  } else 
    jQuery('#message').html('Not Matching').css('color', 'red');
     jQuery("#submit").attr("disabled", true);
  }); 

</script>
