<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?=$pageTitle?></title>

  <!-- Custom fonts for this template-->
  <link href="<?=base_url('assets/')?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
  <link href="<?php echo base_url(); ?>assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  
  <!-- Custom styles for this template-->
  <link href="<?=base_url('assets/')?>/css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    .btn-group-sm>.btn, .btn-sm {
      padding: .25rem 9px;
    font-size: 12px;
    /* line-height: 1.5; */
    border-radius: 0;
    color: white;
}
    .pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: .35rem;
}
.pagination>li {
    display: inline;
}
.pagination>li>a {
    background: #fafafa;
    color: #666;
    border-radius: 0 !important;
}
.pagination>li>a, .pagination>li>span {
    position: relative;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
}

.pagination>li:first-child>a, .pagination>li:first-child>span {
    margin-left: 0;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}

.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    z-index: 2;
    color: #fff;
    cursor: default;
    background-color: #337ab7;
    border-color: #337ab7;
}

.pagination>li>a {
    background: #fafafa;
    color: #666;
    border-radius: 0 !important;
}

.pagination>li>a, .pagination>li>span {
    position: relative;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
}

.mb-2, .my-2 {
    margin-bottom: 1.5rem!important;
}
a.dropdown-item.view-all.text-center.small.text-gray-500{
  background-color: #4e73df;
  color: #fff !important;
  font-weight: bold;
  border:1px solid #4e73df;
}
</style> 




</head>

<body id="page-top">

<?php $d = $this->session->userdata("isLoggedIn");
//print_r($d);
      $userid = $d[0]->userId;
      $dataper =  $this->db->select("*")->from("tbl_modules_permission")->where("user_id",$userid)->get()->result_array();
          $data = $this->db->select('*')->from('tbl_notification')->where('status',1)->where('is_deleted',0)->limit(7,0)->order_by('id',"DESC")->get();
    $data = $data->result_array();
     $cdata = $this->db->select('*')->from('tbl_notification')->where('status',1)->where('read_status',0)->where('is_deleted',0)->get();
    $cdata = $cdata->result_array();

    //  print_r($dataper);
?>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url(); ?>">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">AutoLoad <sup>AL</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo base_url(); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        
      </div>
<?php if(!empty($dataper)) {?>
      <!-- Nav Item - Pages Collapse Menu -->
     <?php if(($dataper[0]["driver"] == 1)||($dataper[0]["user"] == 1)) { ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-users"></i>
          <span>All </span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Driver/User</h6>
           <?php if($dataper[0]["driver"] == 1) { ?>
            <a class="collapse-item" href="<?=base_url('DriversListing')?>">Driver</a>
            <?php }?>

            <?php if($dataper[0]["user"] == 1) { ?>

            <a class="collapse-item" href="<?=base_url('UsersListing')?>">User</a>
            <?php } ?>
          </div>
        </div>
      </li>
      <?php }?>
       <?php if($dataper[0]["ride"] == 1) { ?>

            <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo1" aria-expanded="true" aria-controls="collapseTwo">
<i class="fa fa-car" aria-hidden="true"></i>
          <span>All Rides</span>
        </a>
        <div id="collapseTwo1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Booking</h6>
              <a class="collapse-item" href="<?=base_url('RideListing')?>">Booking Ride</a>
              <a class="collapse-item" href="<?=base_url('BlankRideListing')?>">Blank Ride</a>
              <a class="collapse-item" href="<?=base_url('CancelRideListing')?>">Cancel Ride</a>
          </div>
        </div>
      </li>
      <?php } ?>
           <?php if($dataper[0]["rating"] == 1) { ?>

<!--
                  <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo161" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-users"></i>
          <span>All DriverRating</span>
        </a>
        <div id="collapseTwo161" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">DriverRating</h6>
              <a class="collapse-item" href="<?=base_url('DriverRatingListing')?>">DriverRating</a>
          </div>
        </div>
      </li>-->
      <?php }  ?>

     <?php if($dataper[0]["article"] == 1) { ?>


                  <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo11" aria-expanded="true" aria-controls="collapseTwo">
<i class="fa fa-newspaper-o" aria-hidden="true"></i>
          <span>All Articles</span>
        </a>
        <div id="collapseTwo11" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Articles</h6>
              <a class="collapse-item" href="<?=base_url('ArticleListing')?>">Add Articles</a>
          </div>
        </div>
      </li>
      <?php }  ?>
           <?php if($dataper[0]["vechicle"] == 1) { ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo1112" aria-expanded="true" aria-controls="collapseTwo">
<i class="fa fa-car" aria-hidden="true"></i>
          <span>All Vechicle</span>
        </a>
        <div id="collapseTwo1112" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Vechicle</h6>
              <a class="collapse-item" href="<?=base_url('VechicleListing')?>">Add Vechicle</a>
          </div>
        </div>
      </li>
      <?php } ?>
 <?php if($dataper[0]["city"] == 1) { ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo111111" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-city"></i>
          <span>All City</span>
        </a>
        <div id="collapseTwo111111" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">City</h6>
              <a class="collapse-item" href="<?=base_url('CityListing')?>">Add City</a>
          </div>
        </div>
      </li>
<?php } ?>
     <?php if($dataper[0]["subadmin"] == 1) { ?>

           <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo117111" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-users"></i>
          <span>All Subadmin</span>
        </a>
        <div id="collapseTwo117111" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Subadmin</h6>
              <a class="collapse-item" href="<?=base_url('SubadminListing')?>">Add Subadmin</a>
          </div>
        </div>
      </li>
<?php } ?>
           <?php if($dataper[0]["aboutus"] == 1) { ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo17111" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-info"></i>
          <span>AboutUs</span>
        </a>
        <div id="collapseTwo17111" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Aboutus</h6>
              <a class="collapse-item" href="<?=base_url('AboutUsEditDetail')?>">Add AboutUs</a>
          </div>
        </div>
      </li>
<?php } ?>
           <?php if($dataper[0]["trascation"] == 1) { ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo176111" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-money"></i>
          <span>Transaction Details</span>
        </a>
        <div id="collapseTwo176111" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Transaction</h6>
              <a class="collapse-item" href="<?=base_url('driverTrascationDetails')?>">Driver Transaction </a>
               <a class="collapse-item" href="<?=base_url('companyTrascationDetails')?>">Company Transaction</a>
          </div>
        </div>
      </li>
<?php } ?>
           <?php if($dataper[0]["support"] == 1) { ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo1756111" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-life-ring"></i>
          <span>Support Details</span>
        </a>
        <div id="collapseTwo1756111" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Support</h6>
              <a class="collapse-item" href="<?=base_url('SupportListing')?>">Support Details </a>
          </div>
        </div>
      </li>
<?php } ?>



           <?php if($dataper[0]["setting"] == 1) { ?>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo1111" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cogs"></i>
          <span>All Settings</span>
        </a>
        <div id="collapseTwo1111" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Settings</h6>
              <a class="collapse-item" href="<?=base_url('SettingListing')?>">Add Settings</a>
          </div>
        </div>
      </li>
<?php } ?>
          

<?php } ?>
     


     

      <!-- Divider -->
     

       

      <!-- Nav Item - Pages Collapse Menu -->
     
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
         

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter"><?php echo count($cdata)?></span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Auto Load
                </h6>
                 
                
                <?php if(empty($data)){?>
                <a class="dropdown-item text-center small text-gray-500" href="#">No Notification</a>
                  <?php }?>
                 <?php if(!empty($data)){?>

                  <?php foreach ($data as $key => $value) {
                   ?>
                     <a class="dropdown-item text-center small text-gray-500" href="<?php echo base_url('RideCancelDetailNotification/').$value["booking_id"];?>">
<?php if($value["read_status"]==1) { ?>
                      <b><?php echo $value["msg"];?></b> </a>
                      <?php }  ?>
                      <?php if($value["read_status"]!=1) { ?>
                      <?php echo $value["msg"];?> </a>
                      <?php } ?>
<?php }?>
 <a class="dropdown-item view-all text-center small text-gray-500" href="<?php echo base_url("AllNotification/viewall")?>">View All </a>

<?php } ?>
              </div>
            </li>


            <!-- Nav Item - Messages -->
         

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Auto Load</span> <i class="fas fa-laugh-wink"></i>
                <!-- <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60"> -->
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?php echo base_url('loadChangePass') ?>">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->
