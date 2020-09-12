 <script type="text/javascript" src="<?php echo base_url()?>assets/js/ckeditor/ckeditor.js"></script>
  <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Auto Load  #Article List </h1>
<div class="row">
            <div class="col-md-8">

        <form class="user" method="post" action="<?=base_url('editarticledata')?>" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="title" aria-describedby="emailHelp" required placeholder="Enter title" value="<?php echo $article[0]["title"]?>">
                      <input type="hidden" name="id" value="<?php echo $article[0]["id"]?>">
                    </div>
                    <div class="form-group">
                      <textarea name="desc" class="form-control " id="editor2" style='border-radius: 10rem !important;' width="400" height="400"><?php echo $article[0]["desc"]?></textarea>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" style='border-radius: 10rem !important;' name="link" aria-describedby="emailHelp"  placeholder="Enter link" value="<?php echo $article[0]["link"]?>">
                    </div>  
                    <input type= 'submit' href="#" class="btn btn-primary btn-user btn-block" value="Edit Article">
                      
                   
                  </form>
</div>
</div>
</div>
</div>
<script>
</script>
