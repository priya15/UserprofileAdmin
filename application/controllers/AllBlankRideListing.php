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

            $returns = $this->paginationCompress ( "BlankRideListing/", $count, 10);
            
            $data['RideRecords'] = $this->AllBlankRideModel->RidesBlankListing($searchText, $returns["page"], $returns["segment"],$dropdownText);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : RideBooking Listing';
            //print_r($data);die();
            $this->loadViews("ride/rideBlank", $this->global, $data, NULL);
        }
    }

    public function RideBlankDetail($id){
         $id = $this->uri->segment(2);
         $data = $this->AllBlankRideModel->getRideInfo($id);
         $data['rideData'] = $data[0];
      //  print_r($data);die();
       // $driverImage = $this->db->get_where('driverTranspostImages',array("driverId"=>$id))->result_array();
        //$data['driverData']['driverDocument'] = $driverImage;
        $this->global['pageTitle'] = 'Auto Load : Blank Ride Detail';
         
        $this->loadViews("ride/rideBlankDetail", $this->global, $data, NULL);



    }


    public function createBlankXLS(){
                        $fileName = 'BlankRide.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllBlankRideModel->getRideInfodata();
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'PickupAddress');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'DropAddress');
         $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'TotalCharge');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'TotalDistance');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'vechicleName');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'DriverName');



        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($val);die();
          //  echo  ;die();
            $vechicleInfo = $this->db->get_where('tbl_vehicle_category',array('id'=>$data['userRecords'][$i]["vehicleId"]))->row_array();
            //print_r($vechicleInfo);die();
         $driverInfo = $this->db->get_where('tbl_driver',array('id'=>$data['userRecords'][$i]["driverId"]))->row_array();

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]["name"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]["pickup_address"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['userRecords'][$i]["drop_address"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['userRecords'][$i]["totalCharge"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['userRecords'][$i]["totalDistance"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $vechicleInfo["vehicle_name"]);
         if(!empty($driverInfo)){
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $driverInfo["name"]);

         }
         else
         {
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "Driver Not Found");

         }

      

             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        header("Content-Type: application/vnd.ms-excel");
header("Content-DispositiovechicleInfon: attachment; filename=\"$fileName\"");
header("Cache-Control: max-age=0");

$objWriter->save("php://output");

    }


    
    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>