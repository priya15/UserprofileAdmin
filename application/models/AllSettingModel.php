<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllSettingModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function SettingListingCount($searchText = '')
    {
        $this->db->select("*");
        $this->db->from("tbl_settings as d");
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.title  LIKE '%".$searchText."%'
                            
                        )";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('d.isDeleted', 0);

        
       
         
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
    function SettingListing($searchText = '', $page, $segment)
    {
        
        $this->db->select("*");
        $this->db->from("tbl_settings as d");
       // $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.title  LIKE '%".$searchText."%'
                            
                        )";
            $this->db->where($likeCriteria);
        }

        //$this->db->where('d.isDeleted', 0);

        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }


    public function getSettingInfo($id){
        return $this->db->select("*")->from("tbl_settings")->where("id",$id)->get()->result_array();

    }

    



    
  
    
 

  }

  