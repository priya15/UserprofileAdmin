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
    <span style='float:right'>
        <a href="<?=base_url('notifyDriver/').$driverData['id'];?>" class="btn btn-info" ><i class="fa fa-send-o"> </i> Notify Driver</a>
    </span>
</h1>

</div>
<div class="row">
            <div class="col-md-12">
              
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
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            
            </div>
        </div>

        <!-- Main Section Profile Picture -->

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-3">
            <div class="card">
                <?php if($driverData["profilepic"]!=""){?>
                <img src="<?php echo base_url('assets/profileImage/'.$driverData["profilepic"].'') ?>" alt="John" style="width:100%;height: 250px">
            <?php }?>
             <?php if($driverData["profilepic"]==""){?>
                <img src="<?php echo base_url('assets/user.jpg') ?>" alt="John" style="width:100%;height: 250px">
            <?php }?>

                <h1 style="font-size: 22px;"><?=$driverData['name']?></h1>
                <p class="title"><?=$driverData['city']?>,  <?=$driverData['state']?></p>
                <p><?=date('d F Y h:ia',strtotime($driverData['created_at']))?></p>

                <p class="status01">
                    <?php if($driverData['isDeleted']== 0) { ?>
                        <span class="label label-success">Active</span>
                    <?php } else{ ?>
                        <span class="label label-danger">Deleted</span>

                    <?php } ?>
                    
                    <?php if($driverData['phoneVerifyStatus']== 0) { ?>
                        <span class="label label-danger">Unverify Mobile</span>
                    <?php } else{ ?>
                        <span class="label label-success">Verify Mobile</span>

                    <?php } ?>
                </p>
               
                
                </div>
            </div>




            <!-- Tabs Section -->
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-9"  >
                 <?php include "driverTabs.php" ?>
            </div>
        </div>





</div>
<!-- /.container-fluid -->

</div>


<!-- Modal HTML For accpet Document -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Do you want to Accept this Document ?</p>
                 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action='<?=base_url('submitDocuments')?>' method="post">
                    <input type="hidden" name="driverId" id="driverId">
                    <input type="hidden" name="imageType" id="imageType">
                    <input type="hidden" name="imageId" id="imageId">
                    <input type="hidden" name="flag" value="1">
                <button type="submit" class="btn btn-primary">Accept</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal HTML For reject Document -->
<div id="rejectModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="form-group" action='<?=base_url('submitDocuments')?>' method="post">
                <div class="modal-body">
                    <p>Please enter Reason for Reject Driver Document!</p>
                    <input type="hidden" name="driverId" id="driverId0">
                    <input type="hidden" name="imageType" id="imageType0">
                    <input type="hidden" name="imageId" id="imageId0">
                    <input type="hidden" name="flag" value="2">
                    <textarea class="form-control" name="rejectReason" ></textarea>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setValues(driverId,imageType,flag,id = '')
    {
       
        if(flag == 1){
        $("#driverId").val(driverId);
        $("#imageType").val(imageType);
      
        }
        else{
            $("#driverId0").val(driverId);
             $("#imageType0").val(imageType);
           
        }
    }
</script>