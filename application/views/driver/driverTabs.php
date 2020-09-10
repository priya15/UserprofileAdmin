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
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Driver Documents</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Vehicle Photos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Other Infomation</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab" aria-controls="bank" aria-selected="false">Bank Infomation</a>
    </li>
    

</ul>


<div class="tab-content" id="myTabContent">

<div class="tab-pane fade show active row" id="home" role="tabpanel" aria-labelledby="home-tab ">
    <div class="row" style= 'padding: 20px;'>

    <table class="table table-bordered table-striped">
                <thead>
                    <tr class="" style='background-color: #687bb7;color: white;'>
                           
                        <td style="    border: none; text-align: left;    vertical-align: middle" >RC Photos</td>
                        <td style="    border: none; text-align: right;">
                        <?php if($driverData['RCStatus'] == 1){ ?>
                        <a data-toggle="modal" data-target="#myModal" onclick="setValues(<?=$driverData['id']?>,1,1)"  class="btn btn-success">Accept</a>
                        <a data-toggle="modal" data-target="#rejectModal" onclick="setValues(<?=$driverData['id']?>,1,2)"  class="btn btn-danger">Reject</a>
                       

                         <?php } elseif($driverData['RCStatus'] == 2){ ?>

                            <a href="javascript:void(0)" class="btn text-white badge badge-info">Accepted</a>

                        <?php } elseif($driverData['RCStatus'] == 3){ ?> 
                            <a href="javascript:void(0)" class="btn text-white badge badge-danger">Rejected</a>
                        <?php } ?>
                        </td>
                    </tr>
                </thead> 
    </table> 
        <?php if(!empty($driverData['driverDocument'])){ 
            
                foreach ($driverData['driverDocument'] as $key => $value) {
                        $baseUrl = '';
                   if($value['imageType'] == 1  ){  ?>


        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " style="margin-bottom: 10px;padding-right: 0px;">
            <div class="hovereffect">
                    <img class="img-responsive" src="<?php echo base_url('assets/driverDocument/').$value['imageUrl']; ?>" alt="" style='width: 100%;height: 100%;'  >
                    <div class="overlay">
                    <h2>RC</h2>
                    <div class="info" href="#">
                        <a download="<?=$value['imageUrl'] ?>" href="<?php echo base_url('assets/driverDocument/').$value['imageUrl'] ?>" title="<?=$value['imageUrl'] ?>"><i class="fa fa-download"></i></a>
                        <a href="#" onclick='myfunction(<?=$value['imageId'];?> ,"<?php echo base_url('assets/driverDocument/').$value['imageUrl'];?>")'><i class="fa fa-eye"></i></a>
                    
                    </div>
                    <div class="action" style="position: relative; bottom: -11px;">
                    <?php if($value['acceptStatus'] == 0){ ?>
                       
                    <?php }elseif($value['acceptStatus'] == 1) { ?>
                      <a href="javascript:void(0)" class="btn">  <h2>Approved</h2></a>

                    <?php } else{ ?>
                        <a href="javascript:void(0)" class="btn"><h2>Rejected</h2></a>
                    <?php } ?>


                        
                    </div>
                </div>
            </div>
        </div>


        <?php }  }
       
    
    } else {
        echo "<h2 class='text-center' style='width:100%'>No Photo Uploaded</h2>";
    } ?>
        
        
         
    </div>


    <hr>
    <table class="table table-bordered table-striped">
                <thead>
                <tr class="" style='background-color: #687bb7;color: white;'>
                        <td style="    border: none; text-align: left;    vertical-align: middle" >Insurance Photos</td>
                        <td style="    border: none; text-align: right;">

                        <?php if($driverData['insuranceStatus'] == 1){ ?>


                        <a data-toggle="modal" data-target="#myModal" onclick="setValues(<?=$driverData['id']?>,2,1)"  class="btn btn-success">Accept</a>
                        <a data-toggle="modal" data-target="#rejectModal" onclick="setValues(<?=$driverData['id']?>,2,2)"  class="btn btn-danger">Reject</a>
                        <?php } elseif($driverData['insuranceStatus'] ==2){ ?>

                            <a href="javascript:void(0)" class="btn text-white badge badge-info">Accepted</a>

                        <?php } elseif($driverData['insuranceStatus'] ==3){ ?> 
                            <a href="javascript:void(0)" class="btn text-white badge badge-danger">Rejected</a>
                        <?php } ?>
                        </td>
                    </tr>
                </thead> 
    </table> 


    <div class="row" style= 'padding: 20px;'>

        <?php if(!empty($driverData['driverDocument'])){ 
                
                foreach ($driverData['driverDocument'] as $key => $value) {
                        $baseUrl = '';
                        
                   if($value['imageType'] == 2 ){ ?>


        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " style="margin-bottom: 10px;padding-right: 0px;">
            <div class="hovereffect">
                    <img class="img-responsive" src="<?php echo base_url('assets/driverDocument/').$value['imageUrl']; ?>" alt=""  style='width: 100%;height: 100%;'  >
                    <div class="overlay">
                    <h2>Insurance</h2>
                    <div class="info" href="#">
                        <a download="<?=$value['imageUrl'] ?>" href="<?php echo base_url('assets/driverDocument/').$value['imageUrl'] ?>" title="<?=$value['imageUrl'] ?>"><i class="fa fa-download"></i></a>
                        <a href="#" onclick='myfunction(<?=$value['imageId'];?> ,"<?php echo base_url('assets/driverDocument/').$value['imageUrl'];?>")'><i class="fa fa-eye"></i></a>
                    
                    </div>
                    <div class="action" style="position: relative; bottom: -11px;">
                    <?php if($value['acceptStatus'] == 0){ ?>
                       
                    <?php }elseif($value['acceptStatus'] == 1) { ?>
                      <a href="javascript:void(0)" class="btn">  <h2>Approved</h2></a>

                    <?php } else{ ?>
                        <a href="javascript:void(0)" class="btn"><h2>Rejected</h2></a>
                    <?php } ?>


                        
                    </div>
                </div>
            </div>
        </div>


        <?php }  }
       
    
    } else {
        echo "<h2 class='text-center' style='width:100%'>No Photo Uploaded</h2>";
    } ?>
        
        
         
    </div>











</div>

<div class="tab-pane fade row" id="profile" role="tabpanel" aria-labelledby="profile-tab">
 



<div class="row" style= 'padding: 20px;'>
<table class="table table-bordered table-striped">
                <thead>
                <tr class="" style='background-color: #687bb7;color: white;'>
                        <td style="    border: none; text-align: left;    vertical-align: middle" >Vehicles Photos</td>
                        <td style="    border: none; text-align: right;">

                        <?php if($driverData['vehicleImageStatus'] == 1){ ?>


                        <a data-toggle="modal" data-target="#myModal" onclick="setValues(<?=$driverData['id']?>,3,1)"  class="btn btn-success">Accept</a>
                        <a data-toggle="modal" data-target="#rejectModal" onclick="setValues(<?=$driverData['id']?>,3,2)"  class="btn btn-danger">Reject</a>
                        <?php } elseif($driverData['vehicleImageStatus'] ==2){ ?>

                            <a href="javascript:void(0)" class="btn text-white badge badge-info">Accepted</a>

                        <?php } elseif($driverData['vehicleImageStatus'] ==3){ ?> 
                            <a href="javascript:void(0)" class="btn text-white badge badge-danger">Rejected</a>
                        <?php } ?>
                        </td>
                    </tr>
                </thead> 
    </table> 
<?php if(!empty($driverData['driverDocument'])){ 
    
        foreach ($driverData['driverDocument'] as $key => $value) {
                $baseUrl = '';
           if(  $value['imageType'] == 3 ){ ?>


<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " style="margin-bottom: 10px;padding-right: 0px;">
    <div class="hovereffect">
            <img class="img-responsive" src="<?php echo base_url('assets/driverDocument/').$value['imageUrl']; ?>" alt="" style='width: 100%;height: 100%;'  >
            <div class="overlay">
            <h2>Vehicle Images</h2>
            <div class="info" href="#">
                <a download="<?=$value['imageUrl'] ?>" href="<?php echo base_url('assets/driverDocument/').$value['imageUrl'] ?>" title="<?=$value['imageUrl'] ?>"><i class="fa fa-download"></i></a>
                <a href="#" onclick='myfunction(<?=$value['imageId'];?> ,"<?php echo base_url('assets/driverDocument/').$value['imageUrl'];?>")'><i class="fa fa-eye"></i></a>
            
            </div>
           <div class="action" style="position: relative; bottom: -11px;">
            <?php if($value['acceptStatus'] == 0){ ?>
             
            <?php } if($value['acceptStatus'] == 1) { ?>
              <a href="javascript:void(0)" class="btn">  <h2>Approved</h2></a>

            <?php } else{ ?>
                <a href="javascript:void(0)" class="btn"><h2>Rejected</h2></a>
            <?php } ?>


                
            </div>
        </div>
    </div>
</div>


<?php }  }


} else {
echo "<h2 class='text-center' style='width:100%'>No Photo Uploaded</h2>";
} ?>


 
</div>
</div>

<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <td colspan="3" style='background-color: #3a5fcf;color: white;'>Driver Information</td>
                    </tr>
                </thead>        
                <tbody>
                            <tr>
                                <td>Name</td>
                                <td><?=$driverData['name']?></td>
                            </tr>    
                            <tr>
                                <td>Phone</td>
                                <td><?=$driverData['phone']?></td>
                            </tr>    
                            <tr>
                                <td>Vehicle Number</td>
                                <td><?=$driverData['vehicleNumber']?></td>
                            </tr>    
                            <tr>
                                <td>Vehicle Type</td>
                                <td><?php $type = $this->db->get_where('tbl_vehicle_category',array("id"=>$driverData['vehicleCategoryId']))->row_array(); echo $type['vehicle_name']?></td>
                            </tr>    
                            <tr>
                                <td>Wallet Balance</td>
                                <td><?php echo "₹".$driverData['walletBalance']."/-"?></td>
                            </tr>    
                        </tbody>
                </table>
            </div>
        </div>
</div>

<div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
    <?php
            $bankInfo = $this->db->get_where('tbl_bank_info',array('driverId'=>$driverData['id']))->row_array();

    ?>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <td colspan="3" style='background-color: #3a5fcf;color: white;'>Bank Information</td>
                    </tr>
                </thead>   
                <?php if($bankInfo){ ?>     
                <tbody>
                            <tr>
                                <td>Account Holder Name</td>
                                <td><?=$bankInfo['accountHolderName']?></td>
                            </tr>    
                            <tr>
                                <td>Account Number</td>
                                <td><?=$bankInfo['accountNumber']?></td>
                            </tr>    
                            <tr>
                                <td>IFSC Code</td>
                                <td><?=$bankInfo['IFSCNumber']?></td>
                            </tr>    
                            <tr>
                                <td>Bnak Name</td>
                                <td><?php echo $bankInfo['BankName']?></td>
                            </tr>    
                            <tr>
                                <td>Branch Name</td>
                                <td><?php echo $bankInfo['branchName'];?></td>
                            </tr>    
                        </tbody>
                <?php } else { ?>

                    <tr class="text-center">
                        <td colspan="3">
                            No Bank Details Found
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

<script>
// Get the modal
var modal = document.getElementById("myModal1");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
var span = document.getElementsByClassName("close-1")[0];
span.onclick = function() { 
  modal.style.display = "none";
}
img01.onclick = function() { 
  modal.style.display = "none";
}
function myfunction($id,$url){
  var url = $url;
  var id = $id;
  modal.style.display = "block";
  modalImg.src = url;
}
</script>