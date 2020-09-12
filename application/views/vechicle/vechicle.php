<!-- Begin Page Content -->
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Vechicle List 
<a href="<?php echo base_url() ?>AddVechicle" class="btn btn-sm bg-gradient-primary" style="float:right;font-weight:bold;">Add Vechicle</a></h1> 

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
    <h6 class="m-0 font-weight-bold text-primary" style="float:left;font-size: 24px;">Vechicle List</h6>
    <form action="<?php echo base_url() ?>VechicleListing" method="POST" id="searchList" style="float:right">
                           
                       
                           <div class="input-group">


                                   
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
            <th>PricePerKm</th>
            <th>minPrice</th>
            <th>maxPrice</th>

            <th>CreatedAt</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tfoot>
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>PricePerKm</th>
            <th>minPrice</th>
            <th>maxPrice</th>

            <th>CreatedAt</th>
            <th class="text-center">Actions</th>
          </tr>
        </tfoot>
        <tbody>
        <?php
                    if(!empty($VechicleRecords))
                    {
                      $i = 1;
                      
                        foreach($VechicleRecords as $record)
                        {
                    ?>
                    <tr>
          
          <td><?php echo "#".$record->id ?></td>
          <td><?php if($record->vehicle_name) echo $record->vehicle_name; else echo "Not Updated Yet"; ?></td>
          <td><?php if($record->pricePerKM) echo $record->pricePerKM; else echo "-----"; ?></td>
           <td><?php if($record->minPrice) echo $record->minPrice; else echo "-----"; ?></td>
            <td><?php if($record->maxPrice) echo $record->maxPrice; else echo "-----"; ?></td>
            <?php $cretedate = explode(" ",$record->createdAt);?>
          <td><?php echo $cretedate[0] ?></td>
                  
                <td  class="text-center">
                 <a class="btn btn-sm bg-gradient-success" href="<?php echo base_url('vehicleDetails/').$record->id; ?>">
                        <i class="fa fa-eye"></i>
                    </a>

                  <a class="btn btn-sm bg-gradient-success" href="<?php echo base_url('vehicleEditDetails/').$record->id; ?>">
                        <i class="fa fa-edit"></i>
                    </a>
                             <a href="void:main(0)" class="btn btn-sm bg-gradient-danger" onclick="deleteFunction(<?php echo $record->id; ?>)" title="Delete"><i class="fa fa-trash-o"></i>
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
       function deleteFunction($id){
        var confirmation = confirm("are you sure ? You want to delete Vechicle?");
        if(confirmation) { 
                var serial_no = $id;
                $.ajax({
                     url:"<?php echo base_url('deletevechicle/');?>"+serial_no+"",
                     type:"post",
                    // dataType: "JSON",
                     success: function(data){
                        location.reload();
                     }
                });
        }
    }

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