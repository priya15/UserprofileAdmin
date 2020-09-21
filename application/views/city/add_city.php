 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #City List </h1>
<div class="row">
            <div class="col-md-8">

        <form class="user" method="post" action="<?=base_url('addcitydata')?>" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="title" aria-describedby="emailHelp" required placeholder="Enter city">
                    </div>
                     
                    <input type= 'submit' href="#" class="btn btn-primary btn-user btn-block" value="Add City">
                      
                   
                  </form>
</div>
</div>
</div>
</div>
<script>
  CKEDITOR.replace( 'editor1' );
</script>
