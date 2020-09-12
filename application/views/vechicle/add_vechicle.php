 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Vechicle List </h1>
<div class="row">

            <div class="col-md-8">
              <?php if(isset($success)){?>
              data addedd success
              <?php }?>
  <h6 style="color:red;"><?php echo validation_errors(); ?></h6>

        <form class="user" method="post" action="<?=base_url('AddVechicledata')?>" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="vehicle_name" aria-describedby="emailHelp" required placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="pricePerKM" aria-describedby="emailHelp" required placeholder="Enter pricePerKM">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="minPrice" aria-describedby="emailHelp" required placeholder="Enter minPrice">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="maxPrice" aria-describedby="emailHelp" required placeholder="Enter maxPrice">
                    </div>
                    <div class="form-group">
                      <textarea name="vehiDesc" class="form-control " style='border-radius: 10rem !important;' id="editor11" required></textarea>
                    </div>
              <div class="form-group">
                      <input type="file"  style='border-radius: 10rem !important;' name="image" aria-describedby="emailHelp"  class="btn btn-default btn-file" required>
                    </div>
                    <div class="form-group">
                      <select class="form-control input-sm" style='border-radius: 10rem !important;' name="publish_status" aria-describedby="emailHelp"  placeholder="Enter Status">
                        <option value="1">Publish</option>
                        <option value="2">NotPublish</option>
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
