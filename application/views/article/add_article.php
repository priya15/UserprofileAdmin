 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Article List </h1>
<div class="row">
            <div class="col-md-8">

        <form class="user" method="post" action="<?=base_url('addarticledata')?>" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="title" aria-describedby="emailHelp" required placeholder="Enter title">
                    </div>
                    <div class="form-group">
                      <textarea name="desc" class="form-control " style='border-radius: 10rem !important;' id="editor11"></textarea>
                    </div>
              <!---      <div class="form-group">
                      <input type="file"  style='border-radius: 10rem !important;' name="image" aria-describedby="emailHelp"  class="btn btn-default btn-file">
                    </div>-->
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="link" aria-describedby="emailHelp"  placeholder="Enter link">
                    </div>  
                    <input type= 'submit' href="#" class="btn btn-primary btn-user btn-block" value="Add Article">
                      
                   
                  </form>
</div>
</div>
</div>
</div>
<script>
  CKEDITOR.replace( 'editor1' );
</script>
