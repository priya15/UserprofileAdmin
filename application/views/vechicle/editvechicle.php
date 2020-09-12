 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Vechicle Edit List </h1>
<div class="row">

            <div class="col-md-8">
  <h6 style="color:red;"><?php echo validation_errors(); ?></h6>
        <form class="user" method="post" action="<?=base_url('editvechicledata')?>" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="vehicle_name" aria-describedby="emailHelp" value="<?php echo $vechicle["vehicle_name"];?>" required placeholder="Enter Name">
                      <input type="hidden" name="id" value="<?php echo $vechicle["id"]; ?>">
                       <input type="hidden" name="image" value="<?php echo $vechicle["image"]; ?>">

                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="pricePerKM" aria-describedby="emailHelp" required placeholder="Enter pricePerKM" value="<?php echo $vechicle["pricePerKM"];?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="minPrice" aria-describedby="emailHelp" required placeholder="Enter minPrice" value="<?php echo $vechicle["minPrice"];?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="maxPrice" aria-describedby="emailHelp" required placeholder="Enter maxPrice" value="<?php echo $vechicle["maxPrice"];?>">
                    </div>
                    <div class="form-group">
                      <textarea name="vehiDesc" class="form-control " style='border-radius: 10rem !important;' id="editor11" required><?php echo $vechicle["vehiDesc"];?>"</textarea>
                    </div>
              <div class="form-group">
                      <input type="file"  style='border-radius: 10rem !important;' name="image" aria-describedby="emailHelp"  class="btn btn-default btn-file" ><img src="<?php echo base_url()."assets/vehicleImages/".$vechicle['image']?>" height="100" width="100">

                    </div>
                    <div class="form-group">
                      <select class="form-control input-sm" style='border-radius: 10rem !important;' name="publish_status" aria-describedby="emailHelp"  placeholder="Enter Status">
                        <?php if($vechicle["publish_status"]==1){?>
                        <option value="1" selected>Publish</option>
                        <option value="2">NotPublish</option>
                      <?php }?>
                        <?php if($vechicle["publish_status"]==2){?>
                        <option value="1" >Publish</option>
                        <option value="2" selected>NotPublish</option>
                      <?php }?>

                      </select>
                    </div>  
                    <input type= 'submit' href="#" class="btn btn-primary btn-user btn-block" value="Add Vechicle">
                      
                   
                  </form>
</div>
</div>
</div>
</div>
<script>
  CKEDITOR.replace( 'editor1' );
</script>
