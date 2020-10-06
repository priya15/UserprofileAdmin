<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */

class AllTrascationDriverListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllDriverTrsactionModel');
        $this->isLoggedIn();
        $this->load->library('session');
   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        echo "Setting List";die;
    }
    
    /**
     * This function is used to load the user list
     */
    function driverTrascationDetails()
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
            
            $count = $this->AllDriverTrsactionModel->driverTrascationDetailsCount($searchText);
            //echo $count;die();

            $returns = $this->paginationCompress ( "driverTrascationDetails/", $count, 10);
            
            $data['TrasnscationRecords'] = $this->AllDriverTrsactionModel->driverTrascationDetails($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : Driver Trascation Listing';
            //print_r($data);die();
            $this->loadViews("trascation/driverTrascation", $this->global, $data, NULL);
        }
    }


    public function driverTrsactionDetailData(){
                $id = $this->uri->segment(2);
        $data['driverData'] = $this->AllDriverTrsactionModel->getDriverInfo($id);
        $this->global['pageTitle'] = 'Auto Load : Driver Transaction  Detail';
         
        $this->loadViews("trascation/driverTrascationDetail", $this->global, $data, NULL);


    }

        public function createDriverTrascationXLS(){
$fileName = 'DriverAmount.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllDriverTrsactionModel->getDriverInfofull();
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DriverName.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'BookingNo');

        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PickupAddress');

        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'DropAddress');

        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'TotalAmount');

        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'TotalCharge');
         $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'DriverCreditAmount');

        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'PaymentMode.');

    //print_r($data);die();

        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($data['userRecords']);die();
          //  echo  ;die();
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]["drivername"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]["booking_no"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['userRecords'][$i]["pickup_address"]);

            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['userRecords'][$i]["drop_address"]);

            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['userRecords'][$i]["totalCharge"]);

            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $data['userRecords'][$i]["totalDistance"]);

            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $data['userRecords'][$i]["amount"]);
   if($data['userRecords'][$i]["payment_mode"] == 1){
            $objPHPExcel->getActiveSheet()->SetCellValue('h' . $rowCount, "Cash");
        }
           if($data['userRecords'][$i]["payment_mode"] == 2){
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, "Bank");
        }
           if($data['userRecords'][$i]["payment_mode"] == 3){
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, "UserWallet");
        }


             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Cache-Control: max-age=0");

$objWriter->save("php://output");
        // download file

          //redirect(site_url().$fileName);              

    }



    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

