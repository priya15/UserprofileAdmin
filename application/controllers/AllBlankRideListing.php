<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllBlankRideListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllBlankRideModel');
        $this->isLoggedIn();
        $this->load->library('session');
        $this->load->helper('date');

   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        echo "driver List";die;
    }
    
    /**
     * This function is used to load the user list
     */
    function BlankRideListing()
    {

        if($this->isAdmin() == 1)
        {
            $this->loadThis();
        }
        else
        {
                   // print_r($this->input->post());
            if($this->session->userdata("dropvaldriver")!=""){
                    $this->session->unset_userdata("dropvaldriver");
            } 
            if($this->session->userdata("dropval")!=""){
                    $this->session->unset_userdata("dropval");
            }   
  
        
            $searchText = $this->input->post('searchText');
            $dropdownText = $this->input->post('dropdownText');
            /*if($dropdownText!=""){
                if($this->session->userdata("dropvalbooking")!=""){
                    $this->session->unset_userdata("dropvalbooking");
                    $this->session->set_userdata("dropvalbooking",$dropdownText);
                }
                else{
                   $this->session->set_userdata("dropvalbooking",$dropdownText); 
                }
            }
            elseif(($this->session->userdata("dropvalbooking")!="")&&($dropdownText == ""))
            {
               $dropdownText = $this->session->userdata("dropvalbooking");
            }
            else
            {
                $dropdownText=$dropdownText;
            }*/

           
            $data['searchText'] = $searchText;
            $data['dropdownText'] = $dropdownText;
            
            $this->load->library('pagination');
            
            $count = $this->AllBlankRideModel->RidesBlankListingCount($searchText,$dropdownText);
            //echo $count;die();

            $returns = $this->paginationCompress ( "RideListing/", $count, 10);
            
            $data['RideRecords'] = $this->AllBlankRideModel->RidesBlankListing($searchText, $returns["page"], $returns["segment"],$dropdownText);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : RideBooking Listing';
            //print_r($data);die();
            $this->loadViews("ride/rideBlank", $this->global, $data, NULL);
        }
    }




    
    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>