<!-- Begin Page Content -->
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Ride List
<a href="<?php echo base_url()?>createRideXLS" class="btn btn-sm bg-gradient-primary" style="float:right;">Export Ride Record</a> </h1>
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

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary" style="float:left;font-size: 24px;">Ride List</h6>

    <form action="<?php echo base_url() ?>RideListing" method="POST" id="searchList" style="float:right">
                           
                       
                           <div class="input-group">


                           <select class="form-control input-sm" name="dropdownText" id='dropdownText' style="width: 185px;" onchange="this.form.submit()"  >
                                      <option <?php if($dropdownText == "" || $dropdownText == 'allRides') echo "selected";?> value="allRides" selected>All Ride</option>
                                      <option <?php if($dropdownText == 'Pending') echo "selected";?> value="Pending" >Pending Ride</option>
                                      <option <?php if($dropdownText == 'ConfirmedRide') echo "selected";?> value="ConfirmedRide" >Confirmed  Ride</option>
                                       <option <?php if($dropdownText == 'pickup') echo "selected";?> value="pickup" >pickup</option>
                                        <option <?php if($dropdownText == 'cancel') echo "selected";?> value="cancel" >cancel Ride</option>
                                         <option <?php if($dropdownText == 'dropped') echo "selected";?> value="dropped" >Dropped</option>
                                    </select>
                                   
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm " style="width: 150px;" placeholder="Search"/>
                                    <div class="input-group-btn" >
                                      <button class="btn btn-sm btn-info searchList" style='font-size: 19px;    padding: 4px 9px;'><i class="fa fa-search"></i></button>
                                    </div>

                                  

                                    

                                     
                                   

                                  </div>
                    
                          
                           
       </form>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>id</th>
            <th>Name</th>
            <th>Phone</th>
            <th>PickupAddress</th>
            <th>DropAddress</th>
            <th>RideStatus</th>

            <th>CreatedAt</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tfoot>
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Phone</th>
            <th>PickupAddress</th>
            <th>DropAddress</th>
            <th>RideStatus</th>

            <th>CreatedAt</th>
            <th class="text-center">Actions</th>
          </tr>
        </tfoot>
        <tbody>
        <?php
        //print_r($RideRecords);die();
                    if(!empty($RideRecords))
                    {
                      $i = 1;
                      
                        foreach($RideRecords as $record)
                        {
                    ?>
                    <tr>
          
          <td><?php echo "#".$i++; ?></td>
          <td><?php if($record->name) echo $record->name; else echo "Not Updated Yet"; ?></td>
          <td><?php if($record->phone) echo $record->phone; else echo "Not Updated Yet"; ?></td>
          <td><?php echo $record->pickup_address ?></td>
          <td><?php  echo $record->drop_address; ?></td>
         
          <td><?php if($record->status == 0) echo "Pending";
                  if($record->status == 1) echo "<span style='color:green'>Confirm</span>";
                  if($record->status == 2) echo "<span style='color:green'>Pickup</span>";
                    if($record->status == 3) echo "<span style='color:green'>Cancel</span>";
                      if($record->status == 4) echo "<span style='color:green'>Drop</span>";
                   
                                 ?></td>
          <td><?php if($record->created_at) echo date('d-m-y h:ia',strtotime($record->created_at));
                       ?></td>
        

                     
                      
                      
                <td  class="text-center">
                  
                    <a class="btn btn-sm bg-gradient-success" href="<?php echo base_url('RideDetail/').$record->bookid; ?>">
                        <i class="fa fa-eye"></i>
                    </a>
                            
                </td>
                    </tr>
                    <?php
                        }
                    } else { ?>
                      <tr><td colspan='8' class='text-center'>
                      <figcaption><b>Empty List</b></figcaption> 
                        <!-- <img style="width: 80%;height: 20%;" src="<?php echo base_url('assets/attachment.jpg'); ?>"> -->

                     </td></tr>
                 <?php   }
                    ?>
        </tbody>
      </table>
    </div>
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->

<div class="modal fade" id="transferMoney" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Transfer A Money To Wallet</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form class="user" method="post" action="<?=base_url('transferMoneyToDriver')?>">
                    <div class="form-group">
                      <input type="number" class="form-control form-control-user" style='border-radius: 10rem !important;' name="amount" aria-describedby="emailHelp" required placeholder="Enter Amount">
                      <input type="hidden" id="driverId" name="driverId" >
                    </div>
                    <input type= 'submit' href="#" class="btn btn-primary btn-user btn-block" value="Transfer To Wallet">
                      
                   
                  </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          
        </div>
      </div>
    </div>
  </div>



</div>
<!-- End of Main Content -->
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();    

            
            var i = $("#dropdownText").val();        
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "UsersListing/" + value + "/" + i);
           
            // jQuery("#filter").attr("action", baseURL + "DriversListing/" + value);
            // jQuery("#filter").submit();
            jQuery("#searchList").submit();
        });
    });
</script>



<script>
    function transferWalletBalance(driverId)
    {
        $('#driverId').val(driverId);
        $("#transferMoney").modal('show');
    }
</script>