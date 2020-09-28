<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllNotificationModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function NotificationListingCount($searchText = '')
    {
        $this->db->select("*");
        $this->db->from("tbl_notification as d");
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.msg  LIKE '%".$searchText."%'
                            
                        )";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('d.isDeleted', 0);

                    $this->db->where("status","1");
                   $this->db->where("is_deleted","0");

       
         
        $query = $this->db->order_by('id',"DESC")->get();
      
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function NotificationListing($searchText = '', $page, $segment)
    {
        
        $this->db->select("*");
        $this->db->from("tbl_notification as d");
       // $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         
        if(!empty($searchText)) {
            $likeCriteria = "(d.msg  LIKE '%".$searchText."%'
                            
                        )";
            $this->db->where($likeCriteria);
        }
            $this->db->where("status","1");
                   $this->db->where("is_deleted","0");

        //$this->db->where('d.isDeleted', 0);

        $this->db->limit($page, $segment);
        $query = $this->db->order_by('id',"DESC")->get();
        
        $result = $query->result();        
        return $result;
    }


    public function deletenotification($id){
         return $this->db->delete('tbl_notification', array('id' => $id)); 

    }



   

    



    
  
    
 

  }

  