 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Aboutus List </h1>
<div class="row">
            <div class="col-md-8">

        <form class="user" method="post" action="<?=base_url('editaboutusdata')?>" enctype="multipart/form-data">
                    
                   <input type="hidden" name="id" value="<?php echo $setting[0]["id"]?>"> 
                    <div class="form-group">
                      <textarea class="form-control form-control-user" style='height:400px;width:128%' name="content" aria-describedby="emailHelp"  placeholder="Enter Content" required>  <?php echo $setting[0]["content"]?></textarea>
                    </div>  
                    <input type= 'submit' href="#" class="btn btn-primary btn-user btn-block" value="Edit Aboutus">
                      
                   
                  </form>
</div>
</div>
</div>
</div>
<script>
</script>
