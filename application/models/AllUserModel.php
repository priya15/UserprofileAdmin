<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllUserModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function UsersListingCount($searchText = '',$dropdownText = '')
    {
        $this->db->select("d.id,d.name,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,d.created_at,d.isDeleted");
        $this->db->from("tbl_users as d");
        
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.email  LIKE '%".$searchText."%'
                            OR  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('d.isDeleted', 0);
        if($dropdownText != '' || $dropdownText !='allUser' )
        {
            if($dropdownText == 'verifyUser'){
                $this->db->where('d.phoneVerifyStatus',1);
               
            }
            if($dropdownText == 'unVerifyUser'){
                $this->db->where('d.phoneVerifyStatus',0);
            }
            if($dropdownText == 'Active'){
                $this->db->where('d.isDeleted',0);
            }
            if($dropdownText == 'InActive'){
                $this->db->where('d.isDeleted',1);
            }
            if($dropdownText == 'newuser'){
                $this->db->join("tbl_booking as v",'v.userId = d.id','left');
                $this->db->where('v.userId',NULL);
            }

        }
       $this->db->order_by("d.id","desc");
         
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
    function UsersListing($searchText = '', $page, $segment,$dropdownText = '')
    {
        $this->db->select("d.id,d.name,d.phone,d.city,d.state,d.languageType,d.phoneVerifyStatus,d.created_at,d.isDeleted,d.lat,d.long,d.email,d.emailstatus");
        $this->db->from("tbl_users as d");
       // $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.email  LIKE '%".$searchText."%'
                            OR  d.name  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('d.isDeleted', 0);
        if($dropdownText != '' || $dropdownText !='allUser' )
        {
            if($dropdownText == 'verifyUser'){
                $this->db->where('d.phoneVerifyStatus',1);
                
            }
            if($dropdownText == 'unVerifyUser'){
                $this->db->where('d.phoneVerifyStatus',0);
           }
            if($dropdownText == 'Active'){
                $this->db->where('d.isDeleted',0);
            }
            if($dropdownText == 'InActive'){
                $this->db->where('d.isDeleted',1);
            }
             if($dropdownText == 'newuser'){
                $this->db->join("tbl_booking as v",'v.userId = d.id','left');
                $this->db->where('v.userId',NULL);
            }

        }
       
               $this->db->order_by("d.id","desc");
 
       
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }


    public function getUserInfo($id){
                 $this->db->select("*");
        $this->db->from("tbl_users as d");
        $this->db->where("d.id",$id);
       return $this->db->get()->result_array();

    }

     function getStatusInfo($id){
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('id', $id);
        $query = $this->db->get()->result_array();
        
        $data = $query;
        //echo($data[0]["isDeleted"]);die();
        //print_r($data->isDeleted);die();
        if($data[0]["isDeleted"] ==0){
           $this->db->where("id",$id);
           $this->db->update("tbl_users", array("isDeleted"=>1));
  
         }
         else
         {
             $this->db->where("id",$id);
             $this->db->update("tbl_users", array("isDeleted"=>0));
        }
        $this->db->select('*');
        $this->db->from('tbl_users');
        
       $query = $this->db->get();
       return $data1 = $query->result();
 
        }
    
   
  
    
 

  }

  