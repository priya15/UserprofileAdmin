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
            if($this->session->userdata("dropvaldriver")!=""){
                    $this->session->unset_userdata("dropvaldriver");
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



    public function createUserXLS(){
        $fileName = 'User.xlsx'; 
        $dropdownval  = $this->session->userdata("dropval"); 
        $searchText="";
        $dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllUserModel->UsersListing($searchText, "", "",$dropdownText);
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Phone');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Email');
         $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'City');

        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'State');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'PhoneVerfiyStatus');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'emailVerfiyStatus');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'lat');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'lng');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'languageType');






        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($val);die();
          //  echo  ;die();
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]->phone);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['userRecords'][$i]->email);

            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['userRecords'][$i]->city);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['userRecords'][$i]->state);
            if($data['userRecords'][$i]->phoneVerifyStatus == 0){
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "NotVerified");
        }
            if($data['userRecords'][$i]->phoneVerifyStatus == 1){
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "Verified");
        }
        if($data['userRecords'][$i]->emailstatus ==0){
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "Not Verified");
        }
        if($data['userRecords'][$i]->emailstatus ==1){
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "Verified");
        }
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $data['userRecords'][$i]->lat);
        
        
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $data['userRecords'][$i]->long);
            if($data['userRecords'][$i]->languageType == 1){
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, "English");
        }
    if($data['userRecords'][$i]->languageType == 2){
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, "Hindi");
        }

      

             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Cache-Control: max-age=0");

$objWriter->save("php://output");

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

        public function userdelete(){
        $id = $this->uri->segment(2);
        $this->db->where('id', $id);
       // $this->db->where('isDeleted', 1);

        $this->db->update('tbl_users', array('isDeleted' => 1)); 

        if($data){
            //redirect("DriversListing");
        }
    }


    public function userDetail(){
        $id = $this->uri->segment(2);
         $data = $this->AllUserModel->getUserInfo($id);
         $data['userData'] = $data[0];
      //  print_r($data);die();
       // $driverImage = $this->db->get_where('driverTranspostImages',array("driverId"=>$id))->result_array();
        //$data['driverData']['driverDocument'] = $driverImage;
        $this->global['pageTitle'] = 'Auto Load : User Detail';
         
        $this->loadViews("user/userDetail", $this->global, $data, NULL);

    }

}

?>