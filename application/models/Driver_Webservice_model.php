<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Driver_Webservice_model extends CI_Model
{
    /**
     * This function will check mobile number existance in database
     * @param $phone : mobile no which we want to check
     * @param $userId : This is optional, if want to check except any id this need to pass
     * @return number $count : This is row count
     */
    function checkMobileExists($phone,$vehicleRegistrationNumber = ''){
        $this->db->select("*");
        $this->db->from("tbl_driver");
        if($vehicleRegistrationNumber != '')
        {
            $this->db->where("vehicleNumber", $vehicleRegistrationNumber);  
           
        }
          $this->db->where("phone", $phone);   
        // $this->db->where("isDeleted", 0);
        
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }

    function validateOTP($mobile,$otp){
        $this->db->select("*");
        $this->db->from("tbl_driver");
       
        $this->db->where("phone", $mobile);
        $this->db->where("phoneOtp", $otp);
        $query = $this->db->get();
        return $query->result();
    }

     function insert($table,$data){
        $this->db->trans_start();
        $this->db->insert($table, $data);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    
    function checkEmailToken($userId,$code)
    {
        $this->db->where('user_id',$userId);
        $this->db->where('emailToken',$code);
        $ob = $this->db->get('registration');
        return $ob->row();
    }

      function update($table,$data,$where){
        $this->db->where($where);
        $this->db->update($table, $data);
       return $this->db->last_query();
       
    }


    function checkLoginUser($phone,$pass)
    {
        $this->db->where('phone',$phone);
        $this->db->where('password',$pass);
        $ob = $this->db->get('tbl_driver');
        return $ob->row();
    }

     function getUserProfile($driverId){
        $this->db->select("d.id as driverId,d.email,d.name,d.vehicleNumber,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,v.vehicle_name,d.walletBalance,d.profilepic");
        $this->db->from("tbl_driver as d");
        $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
        $this->db->where('d.id',$driverId);
        $query = $this->db->get();
        return $query->row_array(); 
    }

    function checkPassword($password,$userId){
        $this->db->select("user_id");
        $this->db->from("registration");
        $this->db->where("password", $password); 
        $this->db->where("user_id", $userId);
        $query = $this->db->get();
       // echo $this->db->last_query();
        return $query->row(); 
    }

     function getDataById($table,$where='',$column = '' ){
         if($column != '')
         {  
            $this->db->select($column);
         }else{
            $this->db->select('*');
         }
        
        $this->db->from($table);
        if($where != ''){
        $this->db->where($where);
        }
        $result = $this->db->get();
        if($result){
            return $result->row_array();
        }else{
            return '';
        }
    }

    public function getDataByjoinId($table,$where){
$this->db->select("d.*,driver.vehicleNumber,driver.profilepic as driverpic,user.profilepic,user.socialImageUrl");
        $this->db->from("tbl_booking as d");
        $this->db->join('tbl_driver as driver', 'd.driverId = driver.id', 'inner');
         $this->db->join('tbl_users as user', 'user.id = d.userId', 'inner');
        $this->db->where("driverId",$where);
        $this->db->where("rideStatus",4);
        $this->db->order_by("d.id","DESC");
        $query = $this->db->get();
        return $query->result_array(); 
    }
        public function getDataByjoinRidesId($table,$driverId,$rideId){
$this->db->select("d.*,driver.vehicleNumber,driver.profilepic as driverpic,user.profilepic,user.socialImageUrl");
        $this->db->from("tbl_booking as d");
        $this->db->join('tbl_driver as driver', 'd.driverId = driver.id', 'inner');
         $this->db->join('tbl_users as user', 'user.id = d.userId', 'inner');
        $this->db->where("d.driverId",$driverId);
        $this->db->where("d.id",$rideId);
        $this->db->where("d.rideStatus=",4);
        $query = $this->db->get();
        return $query->result_array(); 
    }

    
     function getData($table,$where=''){
        $this->db->select('*');
        $this->db->from($table);
        if($where != ''){
        $this->db->where($where);
        }
        $result = $this->db->get()->result_array();
        
        if($result){
            return $result;
        }else{
            return '';
        }
    }

    function getChatData($where = '')
    {
        $this->db->select('c.id , c.userId, c.msg, c.dateTime, u.username,u.userProfile');
        $this->db->from('chat as c');
        $this->db->join('registration as u','u.user_id = c.userId');
        $this->db->order_by('c.id','ASC');
        if($where != '')
        {
            $this->db->where('c.id >', $where);

        }

        $data = $this->db->get();
        
        return $data->result_array();
    }

    public function findFirebaseid($id){
     return $data =  $this->db->select("*")->from("tbl_users")->where("id",$id)->get()->result_array();
    }

    public function insertArray($table, $data){

        $query  =$this->db->insert_batch($table,$data);
         return true; 
    }

        function searchDriver($lat,$long,$distance,$vehicleId,$driverId)
        {
            // $sql = $this->db->query("SELECT *, ( '3959' * acos(cos(radians($lat)) * cos( radians(`tbl_driver`.`lat`)) * cos( radians(`tbl_driver`.`lng`) - radians($long)) + sin(radians($lat)) * sin( radians(`tbl_driver`.`lat`)))) AS distance FROMgetUserProfile `tbl_driver` WHERE  ( '3959' * acos( cos( radians($lat) ) * cos( radians(`tbl_driver`.`lng`)) * cos( radians(`tbl_driver`.`lng`) - radians($long)) + sin(radians($lat)) * sin( radians(`tbl_driver`.`lat`)))) < $distance AND `insuranceStatus`= 1 AND `vehicleImageStatus` = 1 AND `RCStatus` = 1 AND `isDeleted`= 0 AND `phoneVerifyStatus` = 1 AND `vehicleCategoryId`= $vehicleId AND `isBooked` = 0");

           $sql = $this->db->query(" SELECT * FROM tbl_booking  where id='68'");

            // $this->db->where(array('status'=>0,'driverId'=>0));
                $data = $sql->result_array();
    
                // print_r($data);
                return $data;
        }

        function searchDriver1($lat,$long,$distance,$vehicleId,$driverId)
        {
            // $sql = $this->db->query("SELECT *, ( '3959' * acos(cos(radians($lat)) * cos( radians(`tbl_driver`.`lat`)) * cos( radians(`tbl_driver`.`lng`) - radians($long)) + sin(radians($lat)) * sin( radians(`tbl_driver`.`lat`)))) AS distance FROMgetUserProfile `tbl_driver` WHERE  ( '3959' * acos( cos( radians($lat) ) * cos( radians(`tbl_driver`.`lng`)) * cos( radians(`tbl_driver`.`lng`) - radians($long)) + sin(radians($lat)) * sin( radians(`tbl_driver`.`lat`)))) < $distance AND `insuranceStatus`= 1 AND `vehicleImageStatus` = 1 AND `RCStatus` = 1 AND `isDeleted`= 0 AND `phoneVerifyStatus` = 1 AND `vehicleCategoryId`= $vehicleId AND `isBooked` = 0");

           $sql = $this->db->query(" SELECT *, ( 3959 * acos( cos( radians($lat) ) * cos( radians( pickup_lat ) ) * cos( radians( pickup_lng ) - radians($long) ) + sin( radians($lat) ) * sin( radians( pickup_lat ) ) ) ) AS distance FROM tbl_booking  HAVING distance < 3  and `rideStatus`= 0   AND `vehicleId`= $vehicleId and CONCAT(',', cancelbydriverid, ',') not like '%,".$driverId.",%'");

            // $this->db->where(array('status'=>0,'driverId'=>0));
                $data = $sql->result_array();
    
                // print_r($data);
                return $data;
        }


        function distance($lat1, $lon1, $lat2, $lon2) { 
        $pi80 = M_PI / 180; 
        $lat1 *= $pi80; 
        $lon1 *= $pi80; 
        $lat2 *= $pi80; 
        $lon2 *= $pi80; 
        $r = 6372.797; // mean radius of Earth in km 
        $dlat = $lat2 - $lat1; 
        $dlon = $lon2 - $lon1; 
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2); 
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a)); 
        $km = $r * $c; 
        //echo ' '.$km; 
        return round($km); 
        }
     function checkRideBookingStatus($rideId)
        {
            $this->db->where('id',$rideId);
           // $this->db->where('driverId',$driverId);

            $this->db->where('rideStatus',0);
             $this->db->or_where('rideStatus',1);
 
            $query = $this->db->get('tbl_booking');
            return $query->row_array();
        } 

        function updateRideStatus($rideStatus,$rideId,$cancelReasone,$cancelbydriverid)
        {
            $this->db->where('id',$rideId);
           
            $query = $this->db->update('tbl_booking',array("rideStatus"=>$rideStatus,"cancelReason"=>$cancelReasone,"canceledBy"=>2,"driverId"=>0,"cancelbydriverid"=>$cancelbydriverid));
            return $this->db->affected_rows();
        }


        public function insertNotification($data){
            $this->db->insert("tbl_notification",$data);
        }

        public function FindNotification($driverid){
                    $this->db->select('note.*,booking.drop_address,booking.pickup_address,booking.totalCharge,booking.totalDistance');
        $this->db->from('tbl_booking as booking');
        $this->db->join('tbl_notification as note','note.booking_id = booking.id');
        $this->db->where('note.driver_id',$driverid);
        // $this->db->or_where('u.social_image_url !=',"");
        $this->db->order_by('id','desc');
       // $this->db->limit(5);
        $like = $this->db->get();
        // echo $this->db->last_query();die;
       return  $likeData = $like->result_array();

        }
    
         function FindDashboardData($driverId,$startdate,$enddate){
            $sql = $this->db->query("SELECT * FROM `tbl_booking` WHERE created_at BETWEEN '$enddate' and '$startdate' AND rideStatus=4 and driverId='$driverId'");
               return $data = $sql->result_array();

         }

         function FindDashboardDataAll($driverId){
            $sql = $this->db->query("SELECT totalCharge,id FROM `tbl_booking` WHERE  rideStatus=4 and driverId='$driverId'");
               return $data = $sql->result_array();

         }


         function FindOnRide($driverId){
            $sql = $this->db->query("SELECT * FROM `tbl_booking` WHERE  rideStatus=2 and driverId='$driverId'");
               return $data = $sql->result_array();

         }

         function getDataByjoinRideId($driverId){
         $this->db->select("v.id,v.pickup_address,v.drop_address,v.totalCharge,d.create_at,v.booking_no,v.totalDistance,v.userId,d.driverId,v.vehicleId");
        $this->db->from("driver_cancel_history as d");
        $this->db->join("tbl_booking as v",'v.id = d.rideId');
        $this->db->where('d.driverId',$driverId);
        $this->db->where('d.status',2);
        $this->db->order_by("d.id","DESC");
       return  $query = $this->db->get()->result_array();
        // $query->result(); 

         }
                  function getDataByjoinRideIddriverId($driverId,$rideId){
         $this->db->select("v.id,v.pickup_address,v.drop_address,v.totalCharge,d.create_at,v.booking_no,v.totalDistance,v.pickup_lat,v.pickup_lng,v.drop_lat,v.drop_lat,v.userId,d.driverId,v.vehicleId");
        $this->db->from("driver_cancel_history as d");
        $this->db->join("tbl_booking as v",'v.id = d.rideId');
        $this->db->where('d.driverId',$driverId);
        $this->db->where('v.id',$rideId);
        $this->db->where('d.status',2);
        $this->db->order_by("d.id","DESC");
       return  $query = $this->db->get()->row_array();
        // $query->result(); 

         }


}

?>