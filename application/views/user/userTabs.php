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
       <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Ride Information</a>
    </li>
    
    

</ul>





<div class="tab-content" id="myTabContent">

<div class="tab-pane fade show active row" id="home" role="tabpanel" aria-labelledby="home-tab ">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                       <td>Pickup Addrss</td> 
                        <td>Drop Address</td>
                        <td>Ride Status</td>
                         <td>Total Charges</td>
                       <td>Total Distance</td>

                    </tr>
                </thead>        
                <tbody>
                  <?php
                   $ridedats = $this->db->select("*")->from("tbl_booking")->where("userId",$userData["id"])->get()->result_array();
                   if(!empty($ridedats)){
                    for($i=0;$i<count($ridedats);$i++){
                   ?>

                            <tr>
                                
                                <td><?=$ridedats[$i]['pickup_address']?></td>
                            
                                <td><?=$ridedats[$i]['drop_address']?></td>
                            
                                <td><?php if($ridedats[$i]["status"]==0){?>Pending<?php }?>
                                  <?php if($ridedats[$i]["status"]==1){?>Confirmed<?php }?>
                                  <?php if($ridedats[$i]["status"]==2){?>Pickup<?php }?>
                                  <?php if($rideData[$i]["status"]==3){?>Cancelled<?php }?>
                                  <?php if($ridedats[$i]["status"]==4){?>Dropped<?php }?>
                                </td>
                            
                           
                                <td><?php echo "₹".$ridedats[$i]['totalCharge']."/-"?></td>

                            
                                <td><?php echo $ridedats[$i]['totalDistance']."KM"?></td>
                                
                            </tr>    
  <?php }}?>
  <?php if(empty($ridedats)){?>
  <tr><td>No data found</td></tr>
  <?php }?>
                        </tbody>
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

