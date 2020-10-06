<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllDriverModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function DriversListingCount($searchText = '',$dropdownText = '')
    {
        $this->db->select("d.id,d.name,d.vehicleNumber,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,v.vehicle_name,d.walletBalance,d.created_at");
        $this->db->from("tbl_driver as d");
        $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.email  LIKE '%".$searchText."%'
                            OR  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('d.isDeleted', 0);
        if($dropdownText != '' || $dropdownText !='allUser' )
        {
            if($dropdownText == 'verifyUser'){
                $this->db->where('d.RCStatus',2);
                $this->db->where('d.insuranceStatus',2);
                $this->db->where('d.vehicleImageStatus',2);
            }
          if($dropdownText == 'unVerifyUser'){
                 $this->db->where('d.RCStatus',1);
                $this->db->where('d.insuranceStatus',1);
                $this->db->where('d.vehicleImageStatus',1);
}

            

        }
       
         
        $query = $this->db->get();
      
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function DriversListing($searchText = '', $page, $segment,$dropdownText = '')
    {
        $this->db->select("d.id,d.RCStatus,d.insuranceStatus,d.vehicleImageStatus,d.name,d.vehicleNumber,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,v.vehicle_name,d.walletBalance,d.created_at,v.minPrice,v.maxPrice,v.pricePerKM,v.vehiDesc,d.lat,d.lng");
        $this->db->from("tbl_driver as d");
        $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.email  LIKE '%".$searchText."%'
                            OR  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('d.isDeleted', 0);
        if($dropdownText != '' || $dropdownText !='allUser' )
        {
            if($dropdownText == 'verifyUser'){
                $this->db->where('d.RCStatus',2);
                $this->db->where('d.insuranceStatus',2);
                $this->db->where('d.vehicleImageStatus',2);
            }
            if($dropdownText == 'unVerifyUser'){
                 $this->db->where('d.RCStatus',1);
                $this->db->where('d.insuranceStatus',1);
                $this->db->where('d.vehicleImageStatus',1);
}

            if($dropdownText == 'reviewUser'){
                 $this->db->where('d.RCStatus',1);
                $this->db->where('d.insuranceStatus',1);
                $this->db->where('d.vehicleImageStatus',1);

                
               
            }
        }
              $this->db->order_by("d.id","desc");

         
       
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function deleteDriverInfo($id){
       return $this->db->delete('tbl_driver', array('id' => $id)); 

    }
    
   
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        if($userId != 0){
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
		$this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    function getDriverInfo($driverId)
    {
        
        $this->db->from('tbl_driver');
        $this->db->where('isDeleted', 0);
	 
        $this->db->where('id', $driverId);
        $query = $this->db->get();
        
        return $query->row_array();
    }
    
    
    
 

    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);        
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }
}

  