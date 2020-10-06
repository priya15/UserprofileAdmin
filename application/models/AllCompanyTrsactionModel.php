<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllCompanyTrsactionModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function companyTrascationDetailsCount($searchText = '',$dropdownText = '')
    {
        $this->db->select("d.*,v.*");
        $this->db->from("tbl_booking as d");
        $this->db->join("tbl_company_amount_transfer as v",'d.id = v.ride_id');
         
        if(!empty($searchText)) {
            $likeCriteria = "(  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('d.isDeleted', 0);
       // $this->db->group_by('v.driverId'); 
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
    function comapnyTrascationDetails($searchText = '', $page, $segment,$dropdownText = '')
    {
$this->db->select("d.*,v.*");
        $this->db->from("tbl_booking as d");
        $this->db->join("tbl_company_amount_transfer as v",'d.id = v.ride_id');
         
        if(!empty($searchText)) {
            $likeCriteria = "(  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('d.isDeleted', 0);
       // $this->db->group_by('v.driverId'); 
       $this->db->order_by('v.id',"DESC"); 

                  
        $query = $this->db->get();
      
        
        return ($query->result());
    }


   
    public function getCompanyInfo($id){
                $this->db->select("d.*,v.*,b.*");
        $this->db->from("tbl_booking as d");
        $this->db->join("tbl_company_amount_transfer as v",'d.id = v.ride_id');
       $this->db->join("tbl_users as b",'b.id = d.userId');
        $this->db->where('v.id', $id);

       // $this->db->group_by('v.driverId'); 
 
         
        $query = $this->db->get();
      
        
        return ($query->result_array());

    }



    public function getCompanyInfofull(){
                $this->db->select("d.*,v.*,b.*");
        $this->db->from("tbl_booking as d");
        $this->db->join("tbl_company_amount_transfer as v",'d.id = v.ride_id');
       $this->db->join("tbl_users as b",'b.id = d.userId');
       // $this->db->where('d.id', $id);

       // $this->db->group_by('v.driverId'); 
 
         
        $query = $this->db->get();
      
        
        return ($query->result_array());

    }




}

  

  