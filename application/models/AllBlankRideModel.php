<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllBlankRideModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function RidesBlankListingCount($searchText = '',$dropdownText = '')
    {
        $this->db->select("book.*,d.name");
        $this->db->from("tbl_booking as book");
                 $this->db->join("tbl_users as d",'d.id=book.userId');
            $this->db->where("book.rideStatus",0);
            $this->db->where("book.created_at<",GETDATE());

        if(!empty($searchText)) {
            $likeCriteria = "(book.pickup_address  LIKE '%".$searchText."%'
                            OR  book.drop_address  LIKE '%".$searchText."%'
                            OR  book.booking_no  LIKE '%".$searchText."%'
                        )";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('d.isDeleted', 0);
       if($dropdownText != '' || $dropdownText !='allRides' )
        {
            if($dropdownText == 'Pending'){
                $this->db->where('book.rideStatus',0);
               
            }
            if($dropdownText == 'ConfirmedRide'){
                $this->db->where('book.rideStatus',1);
            }
            if($dropdownText == 'pickup'){
                $this->db->where('book.rideStatus',2);
            }
            if($dropdownText == 'cancel'){
                $this->db->where('book.rideStatus',3);
            }
            if($dropdownText == 'dropped'){
               
                $this->db->where('book.rideStatus',4);
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
    function RidesBlankListing($searchText = '', $page, $segment,$dropdownText = '')
    {
        
        $this->db->select("book.*,d.name");
        $this->db->from("tbl_booking as book");
       // $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         $this->db->join("tbl_users as d",'d.id=book.userId');
                     $this->db->where("book.rideStatus",0);
            $this->db->where("book.created_at<",GETDATE());


        if(!empty($searchText)) {
            $likeCriteria = "(book.pickup_address  LIKE '%".$searchText."%'
                            OR  book.drop_address  LIKE '%".$searchText."%'
                            OR  book.booking_no  LIKE '%".$searchText."%'
                        )";
            $this->db->where($likeCriteria);
        }

        //$this->db->where('d.isDeleted', 0);
if($dropdownText != '' || $dropdownText !='allRides' )
        {
            if($dropdownText == 'Pending'){
                $this->db->where('book.rideStatus',0);
               
            }
            if($dropdownText == 'ConfirmedRide'){
                $this->db->where('book.rideStatus',1);
            }
            if($dropdownText == 'pickup'){
                $this->db->where('book.rideStatus',2);
            }
            if($dropdownText == 'cancel'){
                $this->db->where('book.rideStatus',3);
            }
            if($dropdownText == 'dropped'){
               
                $this->db->where('book.rideStatus',4);
            }

        }       
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
 



  
    
 

  }

  