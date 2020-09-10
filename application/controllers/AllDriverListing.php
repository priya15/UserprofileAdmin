<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllDriverListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllDriverModel');
        $this->isLoggedIn();   
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
    function DriversListing()
    {
       
        if($this->isAdmin() == 1)
        {
            $this->loadThis();
        }
        else
        {
            
        
            $searchText = $this->input->post('searchText');
            $dropdownText = $this->input->post('dropdownText');
            $data['searchText'] = $searchText;
            $data['dropdownText'] = $dropdownText;
         if($this->session->userdata("dropval")!=""){
                    $this->session->unset_userdata("dropval");
           }
           if($this->session->userdata("dropvalbooking")!=""){
                    $this->session->unset_userdata("dropvalbooking");
            }      
            $this->load->library('pagination');
            
            $count = $this->AllDriverModel->DriversListingCount($searchText,$dropdownText);

			$returns = $this->paginationCompress ( "DriversListing/", $count, 10 );
            
            $data['userRecords'] = $this->AllDriverModel->DriversListing($searchText, $returns["page"], $returns["segment"],$dropdownText);
            // echo "<pre>";
            // print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : Driver Listing';
            
            $this->loadViews("driver/driver", $this->global, $data, NULL);
        }
    }


    function transferMoneyToDriver()
    {
        $driverId = $this->input->post('driverId');
        $amount = $this->input->post('amount');

       $getDriverData =  $this->db->select('walletBalance')->get_where('tbl_driver',array('id'=>$driverId))->row_array();
        if($getDriverData){


            // Driver Transfer Amount History
            $arr = array(
                        "driverId"=>$driverId,
                        "amount"=>$amount,
                        "transferAt"=>date('Y-m-d H:i:s')
            );
            $insert = $this->db->insert('tbl_driver_amount_transfer',$arr);

       $total = $getDriverData['walletBalance'] + $amount;
       

        $update = $this->db->update('tbl_driver',array('walletBalance'=>$total),array('id'=>$driverId));
        // echo $this->db->last_query();die;
        if($update)
                {
                    $this->session->set_flashdata('success', 'Amount Transfer to Wallet Successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Somthing Went Wrong! Please try Again Later');
                }

            }
            else{
                $this->session->set_flashdata('error', 'Somthing Went Wrong! Please try Again Later');
            }

            redirect($_SERVER['HTTP_REFERER']);
    }

    function driverDetail()
    {
        $id = $this->uri->segment(2);
        $data['driverData'] = $this->AllDriverModel->getDriverInfo($id);
        $driverImage = $this->db->get_where('driverTranspostImages',array("driverId"=>$id))->result_array();
        $data['driverData']['driverDocument'] = $driverImage;
        $this->global['pageTitle'] = 'Auto Load : Driver Detail';
         
        $this->loadViews("driver/driverDetail", $this->global, $data, NULL);

    }

    function submitDocuments()
    {
        $data = $_POST;
        // print_r($data);die;
        if($data['flag'] == 1)
        {
           
            $update = $this->db->update('driverTranspostImages',array("acceptStatus"=>1),array("driverId"=>$data['driverId'],"imageType"=>$data['imageType']));
           
            if($data['imageType'] == 1)
            {
                $arr = array("RCStatus"=>2);
            }
            elseif($data['imageType'] == 2)
            {
                $arr = array("insuranceStatus"=>2);
            }
            elseif($data['imageType'] ==3)
            {
                $arr = array("vehicleImageStatus"=>2);
            }

            $update = $this->db->update('tbl_driver',$arr,array("id"=>$data['driverId']));




            if($update)
            {
                $this->session->set_flashdata('success','Document Successfully Approved.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            else{
                $this->session->set_flashdata('error','Somthing Went Wrong.');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        if($data['flag'] == 2)
        {


                $getImage = $this->db->get_where('driverTranspostImages',array('driverId'=>$data['driverId'],"imageType"=>$data['imageType']))->result_array();

                foreach ($getImage as $key => $value) {
                    $unlink = '././assets/driverDocument/'.$value['imageUrl'];
                    unlink($unlink);
                }


            $delete = $this->db->delete('driverTranspostImages',array('driverId'=>$data['driverId'],"imageType"=>$data['imageType']));

              


           
            $arr = array();
            if($data['imageType'] == 1)
            {
                $arr = array("RCStatus"=>3,"RCRejectReason"=>$data['rejectReason']);
            }
            if($data['imageType'] == 2)
            {
                $arr = array("insuranceStatus"=>3,"insuranceRejectReason"=>$data['rejectReason']);
            }
            if($data['imageType'] == 3)
            {
                $arr = array("vehicleImageStatus"=>3,"vehicleImageRejectReason"=>$data['rejectReason']);
            }




            $update = $this->db->update('tbl_driver',$arr,array("id"=>$data['driverId']));
            if($update)
            {
                $this->session->set_flashdata('success','Document Rejected.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            else{
                $this->session->set_flashdata('error','Somthing Went Wrong.');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    function notifyDriver()
    {
        $driverId = $this->uri->segment(2);
        $documentData = $this->db->order_by('acceptStatus','DESC')->group_by('acceptStatus,imageType')->get_where("driverTranspostImages",array("driverId"=>$driverId))->result_array();
        


        if($documentData)
        {
             
            $flag = 0;
            $flag1 = 0;
            $msg = "Your ";
            $msg1 = "Your ";
            foreach ($documentData as $key => $value) {
               
                if($value['acceptStatus'] == 2)
                {
                    $type = $value['imageType'];
                    $msg .= "$type (".$value['cancelReason']."), ";
                    $flag = 1;
                
                }
                
               
            }
            if($flag!= 0){
                
            $msg .= "Are Rejected. Please Upload Again.";
            $msg = str_replace('1','RC',$msg);
            $msg = str_replace('2','Insurance',$msg);
            $msg = str_replace('3','Vehicle Images',$msg);

          
            }
            else{

            }
            
             
        }
       
    }


    public function driverdelete(){
        $id = $this->uri->segment(2);
        $data = $this->AllDriverModel->deleteDriverInfo($id);
        if($data){
            //redirect("DriversListing");
        }
    }


    function sendSMS($mobile,$smsBody,$otp)
    {


                //Your authentication key
                $authKey = "335274AMZU0jIJo95f085039P1";

                //Multiple mobiles numbers separated by comma
                $mobileNumber = $mobile;

                //Sender ID,While using route4 sender id should be 6 characters long.
                $senderId = "AutoLo";

                //Your message to send, Add URL encoding here.
                $message = urlencode($smsBody);

                //Define route 
                
                //Prepare you post parameters
                $postData = array(
                    'authkey' => $authKey,
                    'mobile' => $mobileNumber,
                    'message' => $message,
                    'sender' => $senderId,
                    'country' => '91',
                    'otp' => $otp,
                     
                );

                //API URL
                $url="https://control.msg91.com/api/sendotp.php";

                // init the resource
                $ch = curl_init($url);
                curl_setopt_array($ch, array(
                    // CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postData
                    //,CURLOPT_FOLLOWLOCATION => true
                ));


                //Ignore SSL certificate verification
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


                //get response
                $output = curl_exec($ch);

                //Print error if any
                if(curl_errno($ch))
                {
                    echo 'error:' . curl_error($ch);
                }
                // echo $output;die;
                curl_close($ch);

                

    }

    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>