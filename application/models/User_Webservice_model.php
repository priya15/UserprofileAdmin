<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_Webservice_model extends CI_Model
{
    /**
     * This function will check mobile number existance in database
     * @param $phone : mobile no which we want to check
     * @param $userId : This is optional, if want to check except any id this need to pass
     * @return number $count : This is row count
     */
    function checkMobileExists($phone){
        $this->db->select("*");
        $this->db->from("tbl_users");
       
        $this->db->where("phone", $phone);   
        $this->db->where("isDeleted", 0);
        
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }
function checkEmailExists($email){
        $this->db->select("*");
        $this->db->from("tbl_users");
       
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->row();
    }


    function validateOTP($mobile,$otp){
        $this->db->select("*");
        $this->db->from("tbl_users");
       
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
        $ob = $this->db->get('tbl_users');
        return $ob->row();
    }

     function getUserProfile($userId){
        $this->db->select("d.id as userId,d.name,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,d.profilepic,d.email,d.deviceid");
        $this->db->from("tbl_users as d");
        
        $this->db->where('d.id',$userId);
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

    function searchDriver($lat,$long,$distance,$vehicleId)
        {
            // $sql = $this->db->query("SELECT *, ( '3959' * acos(cos(radians($lat)) * cos( radians(`tbl_driver`.`lat`)) * cos( radians(`tbl_driver`.`lng`) - radians($long)) + sin(radians($lat)) * sin( radians(`tbl_driver`.`lat`)))) AS distance FROMgetUserProfile `tbl_driver` WHERE  ( '3959' * acos( cos( radians($lat) ) * cos( radians(`tbl_driver`.`lng`)) * cos( radians(`tbl_driver`.`lng`) - radians($long)) + sin(radians($lat)) * sin( radians(`tbl_driver`.`lat`)))) < $distance AND `insuranceStatus`= 1 AND `vehicleImageStatus` = 1 AND `RCStatus` = 1 AND `isDeleted`= 0 AND `phoneVerifyStatus` = 1 AND `vehicleCategoryId`= $vehicleId AND `isBooked` = 0");

           $sql = $this->db->query(" SELECT *, ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($long) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance FROM tbl_driver HAVING distance < 3 AND `insuranceStatus`= 1 AND `vehicleImageStatus` = 1 AND `RCStatus` = 1 AND `isDeleted`= 0 AND `phoneVerifyStatus` = 1 AND `vehicleCategoryId`= $vehicleId AND `isBooked` = 0");

            // $this->db->where(array('status'=>0,'driverId'=>0));
                $data = $sql->result_array();
    
                // print_r($data);
                return $data;
        }

     function checkRideBookingStatus($rideId)
        {
            $this->db->where('id',$rideId);
            $this->db->where('rideStatus',0);
            $query = $this->db->get('tbl_booking');
            return $query->row_array();
        } 

        function updateRideStatus($rideStatus,$rideId,$cancelReasone)
        {
            $this->db->where('id',$rideId);
           
            $query = $this->db->update('tbl_booking',array("rideStatus"=>$rideStatus,"cancelReason"=>$cancelReasone,"canceledBy"=>1));
            return $this->db->affected_rows();
        }
    public function insertArray($table, $data){

        $query  =$this->db->insert_batch($table,$data);
         return true; 
    }
}

?>