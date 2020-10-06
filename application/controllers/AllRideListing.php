<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllRideListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllRideModel');
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
    function RideListing()
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
            if($dropdownText!=""){
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
            }

           
            $data['searchText'] = $searchText;
            $data['dropdownText'] = $dropdownText;
            
            $this->load->library('pagination');
            
            $count = $this->AllRideModel->RidesListingCount($searchText,$dropdownText);
            //echo $count;die();

			$returns = $this->paginationCompress ( "RideListing/", $count, 10);
            
            $data['RideRecords'] = $this->AllRideModel->RidesListing($searchText, $returns["page"], $returns["segment"],$dropdownText);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : RideBooking Listing';
            //print_r($data);die();
            $this->loadViews("ride/ride", $this->global, $data, NULL);
        }
    }



    function createRideXLS(){
         $fileName = 'Ride.xlsx'; 
        $dropdownval  = $this->session->userdata("dropvalbooking"); 
        $searchText="";
        $dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllRideModel->RidesListing($searchText, "", "",$dropdownText);
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
         $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Pickup address');

        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Drop address');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'RideStatus');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'VechicleName');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Total Charge');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Total Distance');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'DriverName');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'CancelBy');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'CancelReason');
        $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'BookingDate');







        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($val);die();
          //  echo  ;die();
           $vechicledata =  $this->db->select("*")->From("tbl_vehicle_category")->where("id",$data['userRecords'][$i]->vehicleId)->get()->result_array();
          $driverdata =  $this->db->select("*")->From("tbl_driver")->where("id",$data['userRecords'][$i]->driverId)->get()->result_array();

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]->phone);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['userRecords'][$i]->email);

            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['userRecords'][$i]->pickup_address);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $data['userRecords'][$i]->drop_address);
            if($data['userRecords'][$i]->status == 0){
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "Pending");
        }
            if($data['userRecords'][$i]->status == 1){
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "Ride Confirm");
        }
        if($data['userRecords'][$i]->status == 2){
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "Pickup");
        }
            if($data['userRecords'][$i]->status == 3){
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "Cancel");
        }

            if($data['userRecords'][$i]->status == 4){
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, "Drop");
        }
        
       
        if(empty($vechicledata)){
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, "-----");
        }
        if(!empty($vechicledata)){
        if($vechicledata[0]["vehicle_name"] !=""){
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $vechicledata[0]["vehicle_name"]);
        }
      }
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $data['userRecords'][$i]->totalDistance);
        
        
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $data['userRecords'][$i]->totalCharge);
            if(!empty($driverdata)){
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $driverdata[0]["name"]);
            }
    if(empty($driverdata)){
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, "----");
        }
    if($data['userRecords'][$i]->canceledBy == 1){
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, "User");
        }
    if($data['userRecords'][$i]->canceledBy == 2){
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, "Driver");
        }
    if($data['userRecords'][$i]->canceledBy == ""){
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, "----");
        }
        if($data['userRecords'][$i]->cancelReason == ""){
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, "---");
        }
    if($data['userRecords'][$i]->cancelReason != "" ){
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $data['userRecords'][$i]->cancelReason);
        }

        $bookingdatefinal  =explode(" ",$data['userRecords'][$i]->created_at);
     $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $bookingdatefinal[0]);




      

             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Cache-Control: max-age=0");

$objWriter->save("php://output");
        // download file

    }

        function RideDetail()
    {
        $id = $this->uri->segment(2);
         $data = $this->AllRideModel->getRideInfo($id);
         $data['rideData'] = $data[0];
      //  print_r($data);die();
       // $driverImage = $this->db->get_where('driverTranspostImages',array("driverId"=>$id))->result_array();
        //$data['driverData']['driverDocument'] = $driverImage;
        $this->global['pageTitle'] = 'Auto Load : Ride Detail';
         
        $this->loadViews("ride/rideDetail", $this->global, $data, NULL);

    }

    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>