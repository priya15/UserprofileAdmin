        <div class="row">
           <div class="col-md-12" style="margin-left: 20px;">

            <h1 class="h3 mb-4 text-gray-800">
            <span>Support Details</span></h1>
           </div>
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                <thead>
                </thead>        
                <tbody>
                            <tr>
                              <td>Name</td>
                              <td>UserName</td>
                                <td>DriverName</td>
                                <td>BookingNo</td>
                                <td>Email</td>
                                <td>Message</td>

                              </tr>
                              <tr>
                                
                                 <td><?php echo $supportData["name"]; ?></td>
                                 <?php
                            $driverInfo = $this->db->get_where('tbl_driver',array('id'=>$supportData["driverId"]))->row_array();
                           // print_r($driverInfo);
                             $userInfo = $this->db->get_where('tbl_users',array('id'=>$supportData["userId"]))->row_array();
                             $rideInfo = $this->db->get_where('tbl_booking',array('id'=>$supportData["rideId"]))->row_array();

                                 ?>
                                 <td><?php echo $driverInfo["name"];?></td>

                                <td><?php echo $userInfo["name"];?></td>
                           
                                
                                <td><?php echo $rideInfo["booking_no"];?></td>

                                <td><?php echo $supportData['email']?></td>
                                <td><?php echo $supportData['msg']?></td>
                                


                            </tr>    
                        </tbody>
                </table>
            </div>
        </div>
</div>
<style type="text/css">
    .table-bordered{
     background-color:#fff;
     margin: 5px;
    }
</style>