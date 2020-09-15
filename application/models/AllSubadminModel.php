<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllSubadminModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function SubadminListingCount($searchText = '')
    {
        $this->db->select("*");
        $this->db->from("tbl_admin as d");
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.email  LIKE '%".$searchText."%'
                            OR  d.name  LIKE '%".$searchText."%'
                            OR  d.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('d.isDeleted', 0);
       $this->db->where('d.roleId', 2);

        
       
         
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
    function SubadminListing($searchText = '', $page, $segment)
    {
        
        $this->db->select("*");
        $this->db->from("tbl_admin as d");
       // $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.email  LIKE '%".$searchText."%'
                            OR  d.name  LIKE '%".$searchText."%'
                            OR  d.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }

        $this->db->where('d.isDeleted', 0);
                $this->db->where('d.roleId', 2);

        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    public function getSubadminInfo($id){
    return $this->db->select("*")->from("tbl_admin")->where("userId",$id)->get()->result_array();

    }




    
  
    
 

  }

  