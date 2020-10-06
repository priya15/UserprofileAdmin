<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */

class AllTrascationCompanyListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllCompanyTrsactionModel');
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
    function companyTrascationDetails()
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
            
            $count = $this->AllCompanyTrsactionModel->companyTrascationDetailsCount($searchText);
            //echo $count;die();

            $returns = $this->paginationCompress ( "companyTrascationDetails/", $count, 10);
            
            $data['TrasnscationRecords'] = $this->AllCompanyTrsactionModel->comapnyTrascationDetails($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : AutoLoad Trascation Listing';
            //print_r($data);die();
            $this->loadViews("trascation/comapnyTrascation", $this->global, $data, NULL);
        }
    }


    public function companyTrsactionDetailData($id){
        $id = $this->uri->segment(2);
        $data['companyData'] = $this->AllCompanyTrsactionModel->getCompanyInfo($id);
        $this->global['pageTitle'] = 'Auto Load :  Transaction  Detail';
         
        $this->loadViews("trascation/companyTrascationDetail", $this->global, $data, NULL);

    }


            public function createCompanyTrascationXLS(){
$fileName = 'AutoLoadAmount.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllCompanyTrsactionModel->getCompanyInfofull();
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Amount');
       $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PaymnetMode');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'BookingNo');

        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'PickupAddress');

        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'DropAddress');
                $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'TotalCharge');


        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'TotalDistance');


    //print_r($data);die();

        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($data['userRecords']);die();
          //  echo  ;die();
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]["name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]["amount"]);
           if($data['userRecords'][$i]["payment_mode"] == 1){
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "Cash");
        }
           if($data['userRecords'][$i]["payment_mode"] == 2){
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "Bank");
        }
           if($data['userRecords'][$i]["payment_mode"] == 3){
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "UserWallet");
        }
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['userRecords'][$i]["booking_no"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['userRecords'][$i]["pickup_address"]);

            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $data['userRecords'][$i]["drop_address"]);

            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $data['userRecords'][$i]["totalCharge"]);

            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $data['userRecords'][$i]["totalDistance"]);




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

