<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllCityListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllCityModel');
        $this->isLoggedIn();
        $this->load->library('session');
   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        echo "City List";die;
    }
    
    /**
     * This function is used to load the user list
     */
    function CityListing()
    {

        if($this->isAdmin() == 1)
        {
            $this->loadThis();
        }
        else
        {
                   // print_r($this->input->post());
            if($this->session->userdata("dropval")!=""){
                    $this->session->unset_userdata("dropval");
            }   
            if($this->session->userdata("dropvalbooking")!=""){
                    $this->session->unset_userdata("dropvalbooking");
            }
            if($this->session->userdata("dropvaldriver")!=""){
                    $this->session->unset_userdata("dropvaldriver");
            } 
        
            $searchText = $this->input->post('searchText');
            //$dropdownText = $this->input->post('dropdownText');
                      
            $data['searchText'] = $searchText;
           // $data['dropdownText'] = $dropdownText;
            
            $this->load->library('pagination');
            
            $count = $this->AllCityModel->CityListingCount($searchText);
            //echo $count;die();

			$returns = $this->paginationCompress ( "CityListing/", $count, 10);
            
            $data['CityRecords'] = $this->AllCityModel->CityListing($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : City Listing';
            //print_r($data);die();
            $this->loadViews("city/city", $this->global, $data, NULL);
        }
    }


    public function createCityXLS(){
                        $fileName = 'City-'.time().'.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['cityRecords'] = $this->AllCityModel->CityListing($searchText, "", "");
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name.');
    

        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['cityRecords']); $i++) 
        {
            //print_r($val);die();
          //  echo  ;die();
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['cityRecords'][$i]->title);

             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($fileName);
        // download file
        header("Content-Type: application/vnd.ms-excel");
         redirect(site_url().$fileName);              

    }

    public function deletecity(){
                $id = $this->uri->segment(2);
        $data = $this->AllCityModel->deleteCityInfo($id);
        if($data){
            //redirect("DriversListing");
        }

    }

    public function AddCity(){
        $this->global['pageTitle'] = 'Auto Load : Add City ';
           $this->load->view("includes/header",$this->global);
          $this->load->view("city/add_city", $this->global);
              $this->load->view("includes/footer");

    }


    public function addcitydata(){
                $title  = $this->input->post("title");
        $titles = strtolower($title);
              $data = array("title"=>$titles,"created_at"=>date("Y-m-d h:i:s"));
              $this->db->insert("tbl_city",$data);
              redirect("CityListing");


    }




    

    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

