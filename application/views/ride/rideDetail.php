<link rel="stylesheet" href="<?=base_url('assets/css/driverprofile.css')?>" >
<style>
    .modal-backdrop{
        display: none;
    }
    .close {
    position: absolute;
    top: 11px;
    right: 16px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}
</style>
<div class="container-fluid">

<!-- Page Heading -->
<div>
<h1 class="h3 mb-4 text-gray-800">
    <span>Ride Details</span>

</div>
<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-3">
            <div class="card">
                <?php if($rideData["profilepic"]!=""){?>
                <img src="<?php echo base_url('assets/profileImage/'.$rideData["profilepic"].'') ?>" alt="John" style="width:100%;height: 250px">
            <?php }?>
             <?php if($rideData["profilepic"]==""){?>
                <img src="<?php echo base_url('assets/user.jpg') ?>" alt="John" style="width:100%;height: 250px">
            <?php }?>
                <h1 style="font-size: 22px;"><?=$rideData['name']?></h1>
                <p class="title"><?=$rideData['city']?>,  <?=$rideData['state']?></p>
                <p><?=date('d F Y h:ia',strtotime($rideData['created_at']))?></p>

                <p class="status01">
                    <?php if($rideData['isDeleted']== 0) { ?>
                        <span class="label label-success">Active</span>
                    <?php } else{ ?>
                        <span class="label label-danger">Deleted</span>

                    <?php } ?>
                    
                    <?php if($rideData['phoneVerifyStatus']== 0) { ?>
                        <span class="label label-danger">Unverify Mobile</span>
                    <?php } else{ ?>
                        <span class="label label-success">Verify Mobile</span>

                    <?php } ?>
                </p>
               
                
                </div>
            </div>




            <!-- Tabs Section -->
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-9"  >
                 <?php include "rideTabs.php" ?>
            </div>
        </div>
        <!-- Main Section Profile Picture -->

