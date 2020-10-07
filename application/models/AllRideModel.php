<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AllRideModel extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function RidesListingCount($searchText = '',$dropdownText = '')
    {
        $this->db->select("d.id,d.name,d.phone,book.pickup_address,book.drop_address,book.id as bookid,book.userId,book.rideStatus as status,book.created_at,book.totalCharge,book.totalDistance,book.canceledBy,book.cancelReason");
        $this->db->from("tbl_users as d");
        $this->db->join("tbl_booking as book",'d.id=book.userId');
         
        if(!empty($searchText)) {
            $likeCriteria = "(book.pickup_address  LIKE '%".$searchText."%'
                            OR  book.drop_address  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%'
                             OR  d.name  LIKE '%".$searchText."%'
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
    function RidesListing($searchText = '', $page, $segment,$dropdownText = '')
    {
        
        $this->db->select("d.id,d.name,d.phone,book.pickup_address,book.drop_address,book.id as bookid,book.userId,book.rideStatus as status,book.created_at,book.totalCharge,book.totalDistance,book.canceledBy,book.cancelReason,book.vehicleId,book.driverId,d.email");
        $this->db->from("tbl_users as d");
        $this->db->join("tbl_booking as book",'d.id=book.userId');
       // $this->db->join("tbl_vehicle_category as v",'v.id = d.vehicleCategoryId','left');
         
        if(!empty($searchText)) {
            $likeCriteria = "(book.pickup_address  LIKE '%".$searchText."%'
                            OR  book.drop_address  LIKE '%".$searchText."%'
                            OR  d.phone  LIKE '%".$searchText."%'
                             OR  d.name  LIKE '%".$searchText."%'
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
        $this->db->order_by("book.id", "DESC");
      
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }


    public function getRideInfo($id){

         $this->db->select("d.id,d.email,d.city,d.state,d.name,d.phone,book.pickup_address,book.drop_address,book.id as bookid,book.userId,book.rideStatus as status,book.created_at,book.totalCharge,book.totalDistance,book.canceledBy,d.isDeleted,d.phoneVerifyStatus,vc.vehicle_name,d.profilepic,book.driverId,d.profilepic");
        $this->db->from("tbl_users as d");
        $this->db->join("tbl_booking as book",'d.id=book.userId');
        $this->db->join("tbl_vehicle_category as vc",'vc.id=book.vehicleId');
        $this->db->where("book.id",$id);
       return $this->db->get()->result_array();
    }

    
  
    
 

  }

  