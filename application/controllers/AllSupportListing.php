<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllSupportListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllSupportModel');
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
    function SupportListing()
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
            
            $count = $this->AllSupportModel->SupportListingCount($searchText);
            //echo $count;die();

			$returns = $this->paginationCompress ( "SupportListing/", $count, 10);
            
            $data['SupportRecords'] = $this->AllSupportModel->SupportListing($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : Support Listing';
            //print_r($data);die();
            $this->loadViews("support/support", $this->global, $data, NULL);
        }
    }


    public function supportDetail($id){
         $id = $this->uri->segment(2);
         $data = $this->AllSupportModel->getSupportInfo($id);
         $data['supportData'] = $data[0];
      //  print_r($data);die();
       // $driverImage = $this->db->get_where('driverTranspostImages',array("driverId"=>$id))->result_array();
        //$data['driverData']['driverDocument'] = $driverImage;
        $this->global['pageTitle'] = 'Auto Load : Support Detail';
         
        $this->loadViews("support/supportDetail", $this->global, $data, NULL);

    }


        public function createSupportXLS(){
        $fileName = 'Support-'.time().'.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
         $data['supportRecords'] = $this->AllSupportModel->getSupportInfod();
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name.');
       $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'DriverName.');
       $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'UserName.');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'BookingNumber.');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Email.');

       $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Message.');




    

        // set Row
        $rowCount = 2;
        //print_r($data["supportRecords"]);die();
        for($i=0; $i<count($data['supportRecords']); $i++) 
        {
            //print_r($val);die();
//  echo  ;die();
            //echo $data['supportRecords'][$i]["driverId"];die();
                            $driverInfo = $this->db->get_where('tbl_driver',array('id'=>$data['supportRecords'][$i]["driverId"]))->row_array();
                           // print_r($driverInfo);
                             $userInfo = $this->db->get_where('tbl_users',array('id'=>$data['supportRecords'][$i]["userId"]))->row_array();
                             $rideInfo = $this->db->get_where('tbl_booking',array('id'=>$data['supportRecords'][$i]["rideId"]))->row_array();

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['supportRecords'][$i]["name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $driverInfo["name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $userInfo["name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $rideInfo["booking_no"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['supportRecords'][$i]["email"]);
           $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $data['supportRecords'][$i]["msg"]);






             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($fileName);
        // download file
        header("Content-Type: application/vnd.ms-excel");
         redirect(site_url().$fileName);              

    }






    

    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

