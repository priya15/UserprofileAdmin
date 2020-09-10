<style>

/*#myImg:hover {opacity: 0.7;}*/

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 50px;
  right: 16px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>
<div class="card shadow " style="margin: 0;max-width: 100%;padding: 15px;">

<ul class="nav nav-tabs" id="myTab" role="tablist">

    
    <li class="nav-item">
       <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Booking Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab" aria-controls="bank" aria-selected="false">Driver Infomation</a>
    </li>
    

</ul>





<div class="tab-content" id="myTabContent">

<div class="tab-pane fade show active row" id="home" role="tabpanel" aria-labelledby="home-tab ">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <td colspan="3" style='background-color: #3a5fcf;color: white;'>Ride Information</td>
                    </tr>
                </thead>        
                <tbody>
                            <tr>
                                <td>Pickup Addrss</td>
                                <td><?=$rideData['pickup_address']?></td>
                            </tr>    
                            <tr>
                                <td>Drop Address</td>
                                <td><?=$rideData['drop_address']?></td>
                            </tr>    
                            <tr>
                                <td>Ride Status</td>
                                <td><?php if($rideData["status"]==0){?>Pending<?php }?>
                                  <?php if($rideData["status"]==1){?>Confirmed<?php }?>
                                  <?php if($rideData["status"]==2){?>Pickup<?php }?>
                                  <?php if($rideData["status"]==3){?>Cancelled<?php }?>
                                  <?php if($rideData["status"]==4){?>Dropped<?php }?>
                                </td>
                            </tr>    
                            <tr>
                                <td>Vehicle Name</td>
                                <td><?php  echo $rideData['vehicle_name']?></td>
                            </tr>    
                            <tr>
                                <td>Total Charges</td>
                                <td><?php echo "₹".$rideData['totalCharge']."/-"?></td>

                            </tr>  
                            <tr>
                                <td>Total Distance</td>
                                <td><?php echo $rideData['totalDistance']."KM"?></td>
                                
                            </tr>    
  
                        </tbody>
                </table>
            </div>
        </div>
</div>

<div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
    <?php
          $driverInfo = $this->db->get_where('tbl_driver',array('id'=>$rideData["driverId"]))->row_array();
         // print_r($driverInfo);?>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <td colspan="3" style='background-color: #3a5fcf;color: white;'>Driver Information</td>
                    </tr>
                </thead>   
                <?php if($rideData["driverId"]!=0){ ?>     
                <tbody>
                            <tr>
                                <td>Driver Name</td>
                              
                                <td><?php echo $driverInfo["name"]; ?></td>
                    
                            </tr>
                            <tr><td><a href="<?php echo base_url('driverDetail/').$driverInfo["id"]; ?>" class="btn btn-primary">View Driver Details</a></td></tr>    
                                                  </tbody>
                <?php } else { ?>

                    <tr class="text-center">
                        <td colspan="3">
                            No Driver Details Found
                        </td>
                    </tr>

                <?php } ?>
                </table>
            </div>
        </div>
</div>





</div>


</div>
<div id="myModal1" class="modal" style='z-index: 99999;'>
  <span class="close close-1">&times;  </span>
  <img class="modal-content" id="img01" style='width: 470px;height:500px'>
  <!-- <div id="caption"></div> -->
</div>

