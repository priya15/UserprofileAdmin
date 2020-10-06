<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllDriverTrsactionModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function driverTrascationDetailsCount($searchText = '',$dropdownText = '')
    {
        $this->db->select("d.id,d.name,d.vehicleNumber,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,d.walletBalance,d.created_at");
        $this->db->from("tbl_driver as d");
        $this->db->join("tbl_driver_amount_transfer as v",'d.id = v.driverId');
         
        if(!empty($searchText)) {
            $likeCriteria = "(  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('d.isDeleted', 0);
        $this->db->group_by('v.driverId'); 
       $this->db->order_by('v.id',"DESC"); 

         
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
    function driverTrascationDetails($searchText = '', $page, $segment,$dropdownText = '')
    {
        $this->db->select("d.id,d.name,d.vehicleNumber,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,d.walletBalance,d.created_at,v.payment_mode");
        $this->db->from("tbl_driver as d");
        $this->db->join("tbl_driver_amount_transfer as v",'d.id = v.driverId');
         
        if(!empty($searchText)) {
            $likeCriteria = "(  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('d.isDeleted', 0);
        $this->db->group_by('v.driverId'); 

         
        $query = $this->db->get();
      
        
        return ($query->result());
    }


    public function getDriverInfo($id){
                $this->db->select("d.id,d.name as drivername,d.vehicleNumber,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,d.walletBalance,d.created_at,v.*,b.*");
        $this->db->from("tbl_driver as d");
        $this->db->join("tbl_driver_amount_transfer as v",'d.id = v.driverId');
       $this->db->join("tbl_booking as b",'b.id = v.rideId');
        $this->db->where('d.id', $id);

       // $this->db->group_by('v.driverId'); 
 
         
        $query = $this->db->get();
      
        
        return ($query->result_array());

    }



    public function getDriverInfofull(){
                $this->db->select("d.id,d.name as drivername,d.vehicleNumber,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,d.walletBalance,d.created_at,v.*,b.*");
        $this->db->from("tbl_driver as d");
        $this->db->join("tbl_driver_amount_transfer as v",'d.id = v.driverId');
       $this->db->join("tbl_booking as b",'b.id = v.rideId');
       // $this->db->where('d.id', $id);

       // $this->db->group_by('v.driverId'); 
 
         
        $query = $this->db->get();
      
        
        return ($query->result_array());

    }

}

  

  