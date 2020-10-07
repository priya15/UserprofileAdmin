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
               <?php if($rideData["status"] == 2){?>
             <p class="title">Ride Cancel By Driver</p>

             <?php }?>
             <?php if($rideData["status"] == 3){?>
             <p class="title">Ride Cancel By User</p>

             <?php }?>
             <?php $date = $rideData["created_at"];
                $exdate = explode(" ", $date);
             ?>
               <p>Booking Date:<?php echo $exdate[0];?></p>
                
                </div>
            </div>




            <!-- Tabs Section -->
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-9"  >
                 <?php include "rideCancelTabs.php" ?>
            </div>
        </div>
        <!-- Main Section Profile Picture -->

