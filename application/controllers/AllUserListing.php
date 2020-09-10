<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllUserListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllUserModel');
        $this->isLoggedIn();
        $this->load->library('session');
   
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
    function UsersListing()
    {

        if($this->isAdmin() == 1)
        {
            $this->loadThis();
        }
        else
        {
                   // print_r($this->input->post());
          if($this->session->userdata("dropvalbooking")!=""){
                    $this->session->unset_userdata("dropvalbooking");
            }   
        
            $searchText = $this->input->post('searchText');
            $dropdownText = $this->input->post('dropdownText');
            if($dropdownText!=""){
                if($this->session->userdata("dropval")!=""){
                    $this->session->unset_userdata("dropval");
                    $this->session->set_userdata("dropval",$dropdownText);
                }
                else{
                   $this->session->set_userdata("dropval",$dropdownText); 
                }
            }
            elseif(($this->session->userdata("dropval")!="")&&($dropdownText == ""))
            {
               $dropdownText = $this->session->userdata("dropval");
            }
            else
            {
                $dropdownText=$dropdownText;
            }

           
            $data['searchText'] = $searchText;
            $data['dropdownText'] = $dropdownText;
            
            $this->load->library('pagination');
            
            $count = $this->AllUserModel->UsersListingCount($searchText,$dropdownText);

			$returns = $this->paginationCompress ( "UsersListing/", $count, 10);
            
            $data['userRecords'] = $this->AllUserModel->UsersListing($searchText, $returns["page"], $returns["segment"],$dropdownText);
            // echo "<pre>";
            // print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : User Listing';
            //print_r($data);die();
            $this->loadViews("user/user", $this->global, $data, NULL);
        }
    }


    
  public function userstatus(){
    $status = $this->uri->segment(2);
   $data['userRecords'] = $this->AllUserModel->getStatusInfo($status); 
    $this->global['pageTitle'] = 'Auto Load : User Listing';
            //print_r($data);die();
      redirect("UsersListing");
 

  }
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>