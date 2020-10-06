<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllRatingRideModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function RidesRatingListingCount($searchText = '',$dropdownText = '')
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
    function RidesRatingListing($searchText = '', $page, $segment,$dropdownText = '')
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
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }


        function getRideInfo($driverId)
    {
         $this->db->select("tbl_driver_rating.*,v.name,v.id");
        $this->db->from("tbl_driver_rating");
        $this->db->join("tbl_driver as v",'v.id = tbl_driver_rating.driverId','left');

     
        $this->db->where('v.id', $driverId);
        $query = $this->db->get();
        
        return $query->row_array();
    }
    





  
    
 

  }

  