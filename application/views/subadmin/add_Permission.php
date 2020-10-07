 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Subadmin Module Permission </h1>
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


        <form class="user" method="post" action="<?=base_url('addsubadminpermissiondata')?>" >
          <?php 
            $user=0;$driver=0;$ride=0;$article=0;$vechicle=0;$subadmin=0;$setting=0;$feedback=0;$city=0;$aboutus=0;$trascation=0;$support=0;
            if($permission[0]["user"]==1){
              $user =1;
            }
            if($permission[0]["driver"]==1){
              $driver =1;
            }
            if($permission[0]["ride"]==1){
              $ride =1;
            }
            if($permission[0]["article"]==1){
              $article =1;
            }
            if($permission[0]["vechicle"]==1){
              $vechicle =1;
            }
            if($permission[0]["setting"]==1){
              $setting =1;
            }
            if($permission[0]["feedback"]==1){
              $feedback =1;
            }
            if($permission[0]["subadmin"]==1){
              $subadmin =1;
            }
            if($permission[0]["city"]==1){
              $city =1;
            }

            if($permission[0]["aboutus"]==1){
              $aboutus =1;
            }
             if($permission[0]["support"]==1){
              $support =1;
            }
             if($permission[0]["trascation"]==1){
              $trascation =1;
            }
           // print_r($permission);
            //echo $aboutus;die();


          ?>      <?php if($user ==1){?>
                    <div class="form-group">
                      <input type="checkbox"  style='border-radius: 10rem !important;' name="user" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                      <label>User</label>
                    </div>
                    <?php } ?>
<input type="hidden" name="id" value="<?php echo $permission[0]["user_id"];?>">
                                    <?php if($user ==0){?>
                    <div class="form-group">
                      <input type="checkbox"  style='border-radius: 10rem !important;' name="user" aria-describedby="emailHelp"  placeholder="Enter name">
                      <label>User</label>
                    </div>
                  <?php }?>
                  <?php if($driver ==0){?>

                    <div class="form-group">
                      <input type="checkbox"  style='border-radius: 10rem !important;' name="driver" aria-describedby="emailHelp"  placeholder="Enter name">
                       <label>Driver</label>
                    </div>
                    <?php }?>
                   <?php if($driver ==1){?>

                    <div class="form-group">
                      <input type="checkbox"  style='border-radius: 10rem !important;' name="driver" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                       <label>Driver</label>
                    </div>
                    <?php }?>
                    <?php if($ride ==1){?>

                    <div class="form-group">
                     <input type="checkbox"  style='border-radius: 10rem !important;' name="ride" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                      <label>Ride</label>
                    </div>
                    <?php }?>
                   <?php if($ride ==0){?>

                    <div class="form-group">
                     <input type="checkbox"  style='border-radius: 10rem !important;' name="ride" aria-describedby="emailHelp"  placeholder="Enter name">
                      <label>Ride</label>
                    </div>
                    <?php }?>
                    <?php if($article ==0){?>


                    <div class="form-group">
                     <input type="checkbox"  style='border-radius: 10rem !important;' name="article" aria-describedby="emailHelp"  placeholder="Enter name">
                     <label>Article</label>
                   </div>
                   <?php }?>
                  <?php if($article ==1){?>


                    <div class="form-group">
                     <input type="checkbox"  style='border-radius: 10rem !important;' name="article" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                     <label>Article</label>
                   </div>
                   <?php }?>
                    <?php if($vechicle ==0){?>

                   <div class="form-group">
                   <input type="checkbox"  style='border-radius: 10rem !important;' name="vechicle" aria-describedby="emailHelp"  placeholder="Enter name">
                   <label>Vechicle</label>
                 </div>
                 <?php }?>
                  <?php if($vechicle ==1){?>

                   <div class="form-group">
                   <input type="checkbox"  style='border-radius: 10rem !important;' name="vechicle" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                   <label>Vechicle</label>
                 </div>
                 <?php }?>
                 <?php if($feedback ==0){?>

              <!---   <div class="form-group">
                  <input type="checkbox"  style='border-radius: 10rem !important;' name="feedback" aria-describedby="emailHelp"  placeholder="Enter name">
                  <label>Feedback</label>
                </div>-->
                <?php }?>
                 <?php if($feedback ==1){?>

            <!--     <div class="form-group">
                  <input type="checkbox"  style='border-radius: 10rem !important;' name="feedback" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                  <label>Feedback</label>
                </div>-->
                <?php }?>
                <?php if($setting ==1){?>

                <div class="form-group">
                 <input type="checkbox"  style='border-radius: 10rem !important;' name="setting" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                  <label>Setting</label>
                </div>
                 <?php }?>
                <?php if($setting ==0){?>

                <div class="form-group">
                 <input type="checkbox"  style='border-radius: 10rem !important;' name="setting" aria-describedby="emailHelp"  placeholder="Enter name">
                  <label>Setting</label>
                </div>
                 <?php }?>
                <?php if($subadmin ==0){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="subadmin" aria-describedby="emailHelp"  placeholder="Enter name">
                <label>Subadmin</label>
                </div>
                <?php }?>
                <?php if($subadmin ==1){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="subadmin" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                <label>Subadmin</label>
                </div>
                <?php }?>

                                <?php if($city ==0){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="city" aria-describedby="emailHelp"  placeholder="Enter name">
                <label>City</label>
                </div>
                <?php }?>
                <?php if($city ==1){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="city" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                <label>City</label>
                </div>
                <?php }?>


                <?php if($aboutus ==0){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="aboutus" aria-describedby="emailHelp"  placeholder="Enter name">
                <label>AboutUs</label>
                </div>
                <?php }?>
                <?php if($aboutus == 1){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="aboutus" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                <label>Aboutus</label>
                </div>
                <?php }?>
                                <?php if($support ==0){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="support" aria-describedby="emailHelp"  placeholder="Enter name">
                <label>Support</label>
                </div>
                <?php }?>
                <?php if($support == 1){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="support" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                <label>Support</label>
                </div>
                <?php }?>

                <?php if($trascation ==0){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="trascation" aria-describedby="emailHelp"  placeholder="Enter name">
                <label>Trascation</label>
                </div>
                <?php }?>
                <?php if($trascation == 1){?>

                <div class="form-group">

                <input type="checkbox"  style='border-radius: 10rem !important;' name="trascation" aria-describedby="emailHelp"  placeholder="Enter name" checked>
                <label>Trascation</label>
                </div>
                <?php }?>


                      
                    <input type= 'submit' id="submit" href="#" class="btn btn-primary btn-user btn-block" value="Add Permission">
                      
                   
                  </form>
</div>
</div>
</div>
</div>
<style type="text/css">
  .row{
    background-color: #fff;
    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
}
</style>

