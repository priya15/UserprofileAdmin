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
    <span>Driver Details</span>

</div>
<?php
         // $driverInfo = $this->db->get_where('tbl_driver',array('id'=>$rideData["id"]))->row_array();
          //print_r($driverData);

 ?>
<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-3">
            <div class="card">
                <img src="<?php echo base_url('assets/user.jpg') ?>" alt="John" style="width:100%;height: 250px">
                <h1 style="font-size: 22px;"><?=$driverData[0]['drivername']?></h1>
                <h1 style="font-size: 22px;">Payment Mode:
                    <? if($driverData[0]['payment_mode'] == 1){?>
                        Cash
                        <?php }?>
                        <? if($driverData[0]['payment_mode'] == 2){?>
                        BankOnline
                        <?php }?>
                        <? if($driverData[0]['payment_mode'] == 3){?>
                        Wallet
                        <?php }?>
                    </h1>


                
                </div>
            </div>




            <!-- Tabs Section -->
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-9"  >
                 <?php include "driverTrascationTabs.php" ?>
            </div>
        </div>
        <!-- Main Section Profile Picture -->

