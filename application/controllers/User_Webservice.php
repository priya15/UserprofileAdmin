<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('API_KEY','SID]O.YI0j2z=Ba)7s,!IW`~IanI{m');
class User_Webservice extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');
		$this->load->library('email');
		$this->load->model('User_Webservice_model');
		ini_set('date.timezone', 'Asia/Calcutta');
		$this->load->helper(array('form', 'url'));
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
				
                curl_close($ch);
				// echo "Error :  ".$output;die;
                

    }
    
    // Check Device is login only one screen
     function checkDeviceToken($userId,$deviceId)
    {
        $result = array();
		$check = $this->db->get_where('tbl_users',array("id"=>$userId,"deviceId"=>$deviceId))->row_array();
		// echo $this->db->last_query();die;
        if(!empty($check))
        {
            return true;
        }
        else
        {
            $result['status'] = 5;
			$result['responseMessage'] = "Invalid Device Login";
				echo json_encode($result);die;
        }
    }



	function index()
	{
		echo "hii";
		// $this->sendSMS('918770461607','Hello This is my Test code 0143','0143');
	}
 
	public function updateDeviceInfo($deviceId,$deviceType,$fireBaseToken,$phone){
		$tableData['deviceid'] = $deviceId;
		$tableData['devicetype'] = $deviceType;
		$tableData['fireBaseToken'] = $fireBaseToken;
		$where['phone'] = $phone;
		if($this->User_Webservice_model->update('tbl_users',$tableData,$where)){
			return TRUE;
		}else{
			return FALSE;
		}
	}



	public function singupStep1(){
		// echo "hidsi";die;
		$result = array();
		$name = trim($this->input->get_post('name', TRUE));
		$city = trim($this->input->get_post('city', TRUE));
		$state = trim($this->input->get_post('state', TRUE));
		$password = trim($this->input->get_post('password', TRUE));
		 
		$deviceId = trim($this->input->get_post('deviceId', TRUE));
		$deviceType = trim($this->input->get_post('deviceType', TRUE));
		$phone = trim($this->input->get_post('mobile', TRUE));
        $languageType = trim($this->input->get_post('languageType', TRUE)); 
        $firebasetoken =trim($this->input->get_post('fireBaseToken', TRUE)); 
        //  1-> english 2->hindi 
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
// 		echo API_KEY;die;
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		
			$data = $this->User_Webservice_model->checkMobileExists($phone);
			// print_r($data);die;
			//var_dump($flag); die;
			if($data == ""){
				$code = '1111';
				// $code=rand(1000,9999);
				// $phoneCoun = '91'.$phone;
				// $msg = "".$code." is your Verification OTP. Do not share this code with anyone else.";
				// $this->sendSMS($phoneCoun,$msg,$code);
				// $code = '1111';
				$tableData = array('name'=>$name,'city'=>$city,'state'=>$state,'phone'=>$phone,"phoneOtp"=>$code,"languageType"=>$languageType,'password'=>md5($password),'created_at'=>date('Y-m-d H:i:s'));
				$insert_id = $this->User_Webservice_model->insert('tbl_users',$tableData);
				if($insert_id){

                    $this->updateDeviceInfo($deviceId,$deviceType,$firebasetoken,$phone);
				    $tableData['userId'] = $insert_id;
				    $tableData['otp'] = $code;
				    $tableData['deviceId'] = $deviceId;
                    // $arr = array("userId"=>$insert_id,"otp"=>$code);
                    $result['status'] = 1;
                    $result['responseMessage'] = "User Register Successfully";
                    $result['AllData'] = $tableData; 
					

					
					
				
				}else{
					$result['status'] = 0;
					$result['responseMessage'] = "Registration failed!! Please try later.";
				}
			}else{
					$result['status'] = 0;
					$result['responseMessage'] = "Mobile number Already Exits";
			}
	
		echo json_encode($result);
    }
    
     


    public function userLogin(){
		$result = array();
	
		$phone   = trim($this->input->get_post('mobile', TRUE));
		$password  = md5(trim($this->input->get_post('password', TRUE)));
		$firebasetoken =trim($this->input->get_post('fireBaseToken', TRUE)); 
		
		$apiKey   = trim($this->input->get_post('apiKey', TRUE));
		$deviceId   = trim($this->input->get_post('deviceId', TRUE));
		$deviceType = trim($this->input->get_post('deviceType', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$checkLoginUser = $this->User_Webservice_model->checkLoginUser($phone,$password);
		 
		if($checkLoginUser){
			$this->updateDeviceInfo($deviceId,$deviceType,$firebasetoken,$phone);
		
			$result['status'] = 1;
            $result['responseMessage'] = "Login Successfully";
            
            $result['userData'] =  $this->User_Webservice_model->getUserProfile($checkLoginUser->id);
             
			if(!empty($result['userData']['profilepic'])){
                $result['userData']['profilepic'] = base_url('assets/profileImage/'.$result['userData']['profilepic']);
            }
            else{
                $result['userData']['profilepic'] = "";
            }
 		}else{
			$result['status'] = 0;
			$result['responseMessage'] = "Phone Or Password Didn't Match.";
		}

		echo json_encode($result);
	}


	public function forgotPasswordStep1(){
		$result = array();
		
		$phone = trim($this->input->get_post('mobile', TRUE)); //Email 
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		if($phone != ''){
			$data = $this->User_Webservice_model->checkMobileExists($phone);
			if($data){
				
				$code = '1111';
				// $code=rand(1000,9999);
				// $phoneCoun = '91'.$phone;
				// $msg = "".$code." is your forgot password Verification OTP. Do not share this code with anyone else.";
				// $this->sendSMS($phoneCoun,$msg,$code);
				$tableData['phoneOtp'] = $code;
				$condition['phone'] = $phone;

				if($this->User_Webservice_model->update('tbl_users',$tableData,$condition)){
					/*Code to send Email*/
						
                    $result['status'] = 1;
					$result['responseMessage'] = "OTP Sent.";
					$result['Otp'] = $code;
				}else{
					$result['status'] = 0;
					$result['responseMessage'] = "Some error with OTP, Try Again Later.";
				}
			}else{
				$result['status'] = 0;
				$result['responseMessage'] = "Mobile Not registered";
			}
		}else{
			$result['status'] = 0;
			$result['responseMessage'] = "Phone Cannot be Empty";
		}
		echo json_encode($result);
	}

	public function verifyOTP(){
		$result = array();
		
		$mobile = trim($this->input->get_post('mobile', TRUE));
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$OTP = trim($this->input->get_post('otp', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		if($mobile!= ''){
			$data = $this->User_Webservice_model->validateOTP($mobile,$OTP);
			
			if($data){

				$this->db->update('tbl_users',array('phoneVerifyStatus'=>1),array('phone'=>$mobile));

				$result['status'] = 1;
				$result['responseMessage'] = "OTP matched";
				$result['userId'] = $data[0]->id;
			 
			}else{
				$result['status'] = 0;
				$result['responseMessage'] = "OTP mismatched!!";
			}
		}else{
			$result['status'] = 0;
			$result['responseMessage'] = "Phone Cannot be Empty!";
		}
		echo json_encode($result);
	}

	public function forgotPasswordStep2(){
		$result = array();
		$userId = trim($this->input->get_post('userId', TRUE));
		$password = trim($this->input->get_post('newPassword', TRUE));
		$apiKey = trim($this->input->get_post('apiKey', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		if($userId){
			$tableData['password'] = md5($password);
			$condition['id'] = $userId;
			if($this->User_Webservice_model->update('tbl_users',$tableData,$condition)){
				$result['status'] = 1;
				$result['responseMessage'] = "Password updated successfully";
			}else{
				$result['status'] = 0;
				$result['responseMessage'] = "Oops some error with operation";
			}
		}else{
			$result['status'] = 0;
			$result['responseMessage'] = "Invalid user";
		}	

		echo json_encode($result);

	}

	// public function changePassword(){
	// 	$result = array();
	// 	$userId = trim($this->input->get_post('userId', TRUE));
	// 	$oldPassword = sha1(trim($this->input->get_post('oldPassword', TRUE)));
	// 	$password = trim($this->input->get_post('newPassword', TRUE));
	// 	$apiKey = trim($this->input->get_post('apiKey', TRUE));

	// 	if($apiKey != API_KEY){
	// 		echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
	// 	}

	// 	if(is_numeric($userId)){
	// 		$data = $this->User_Webservice_model->checkPassword($oldPassword,$userId);
	// 		//print_r($data);
	// 		if($data){
	// 			$tableData['password'] = md5($password);
	// 			$condition['user_id'] = $userId;
	// 			if($this->User_Webservice_model->update('registration',$tableData,$condition)){
	// 				$result['status'] = 1;
	// 				$result['responseMessage'] = "Password updated successfully";
	// 			}else{
	// 				$result['status'] = 0;
	// 				$result['responseMessage'] = "Oops some error with operation";
	// 			}
	// 		}else{
	// 			$result['status'] = 0;
	// 			$result['responseMessage'] = "Password mismatched!";
	// 		}	
	// 	}else{
	// 		$result['status'] = 0;
	// 		$result['responseMessage'] = "Invalid user";
	// 	}
	// 	echo json_encode($result);
	// }




	function logout()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$updateData = array('deviceid'=>'','devicetype'=>'');
		$where = array('id'=>$userId);
         $this->User_Webservice_model->update('tbl_users',$updateData,$where);
         $update = $this->db->affected_rows();
		if($update)
		{
			$result['status'] = 1;
			$result['responseMessage'] = "Logout Successfully";
		}
		else
		{
			$result['status'] = 0;
			$result['responseMessage'] = "Somthing Went Wrong";
		}
		echo json_encode($result);

	}

	function getProfileDetails()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));
        
		$deviceId = trim($this->input->get_post('deviceId', TRUE));
        
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 $this->checkDeviceToken($userId,$deviceId);
		// $where = array('id'=>$userId);
		$data = $this->User_Webservice_model->getUserProfile($userId);
		if($data)
		{
			if(!empty($data['profilepic'])){
					$data['profilepic'] = base_url('assets/profileImage/'.$data['profilepic']);
                    }
                    else{
                        $data['profilepic'] = "";
                    }
			$result['status'] = 1;
			$result['responseMessage'] = "All Data";
			$result['AllData'] = $data;
		}
		else
		{
			$result['status'] = 0;
			$result['responseMessage'] = "No User Found";
		}
		echo json_encode($result);

	}
	
	
	function getVehicleCategory()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
	     $pickup_lat = trim($this->input->get_post('pickup_lat', TRUE));
        $pickup_lng = trim($this->input->get_post('pickup_lng', TRUE));
        $drop_lat = trim($this->input->get_post('drop_lat', TRUE));
        $drop_lng = trim($this->input->get_post('drop_lng', TRUE));
		//$vehicleId = trim($this->input->get_post('vehicleId', TRUE));
		$deviceId = trim($this->input->get_post('deviceId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$distance =  $this->User_Webservice_model->distance($pickup_lat,$pickup_lng ,$drop_lat,$drop_lng);


		if($distance == 0)
		{
			$distance  = 1;
		}

		
		// $where = array('id'=>$driverId);
		$data = $this->User_Webservice_model->getData('tbl_vehicle_category');
		if($data)
		{

            foreach ($data as $key => $value) {
            	//print_r($data);
			     //echo $data[$key]['pricePerKM'];die();
            	$pricekm = $data[$key]['pricePerKM'];
			    $totalFair = round($distance * $pricekm);
			     $totalFairmax = round($distance*3*$pricekm);
			   $data[$key]["minPrice"]=$totalFair;
			   $data[$key]["maxPrice"]=$totalFairmax;

//price = array("minprice"=>$totalFair,"maxprice"=>$totalFairmax,"distance"=>$distance);

			    
			    
                if(!empty($value['image'])){
					$data[$key]['image'] = base_url('assets/vehicleImages/'.$value['image']);
                }
                else{
                    $data[$key]['image'] = "";
                }
            }
			if(!empty($data['image'])){
					$data['image'] = base_url('assets/vehicleImages/'.$data['image']);
			}
			$result['status'] = 1;
			$result['responseMessage'] = "All Data";
			$result['AllData'] = $data;
            //$result["vechicleData"]=$price;
		}
		else
		{
			$result['status'] = 0;
			$result['responseMessage'] = "No Data Found";
		}
		echo json_encode($result);

	}
	
	function saveUserAddress()
    {
        $apiKey = trim($this->input->get_post('apiKey', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
        $address1 = trim($this->input->get_post('address1', TRUE));
        $address2 = trim($this->input->get_post('address2', TRUE));
        $addressType = trim($this->input->get_post('addressType', TRUE));
        $lat = trim($this->input->get_post('lat', TRUE));
        $lng = trim($this->input->get_post('lng', TRUE));
	    $deviceId = trim($this->input->get_post('deviceId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
        }
        
    $this->checkDeviceToken($userId,$deviceId);
        $tableData = array("userId"=>$userId,"address1"=>$address1,"address2"=>$address2,"addressType"=>$addressType,'lat'=>$lat,'lng'=>$lng,'createdAt'=>date('Y-m-d H:i:s'));

        $insert = $this->User_Webservice_model->insert('tbl_address',$tableData);
        if($insert)
        {
            $result['status'] = 1;
			$result['responseMessage'] = "Address Inserted Successfully";
			$result['AllData'] = $tableData;
        }
        else{
            $result['status'] = 0;
			$result['responseMessage'] = "Somthing Went Wrong";
			
        }

        echo json_encode($result);
    }


    function getSaveAddress()
    {
        $apiKey = trim($this->input->get_post('apiKey', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
        $deviceId = trim($this->input->get_post('deviceId', TRUE));
	 

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
        }
        

       $this->checkDeviceToken($userId,$deviceId);

        $data = $this->User_Webservice_model->getData('tbl_address',array('userId'=>$userId));
        if($data)
        {
            $result['status'] = 1;
			$result['responseMessage'] = "All Data";
			$result['AllData'] = $data;
        }
        else{
            $result['status'] = 0;
			$result['responseMessage'] = "No Address Found";
			
        }

        echo json_encode($result);
    }

    
	function getEstimateAmount()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $pickup_lat = trim($this->input->get_post('pickup_lat', TRUE));
        $pickup_lng = trim($this->input->get_post('pickup_lng', TRUE));
        $drop_lat = trim($this->input->get_post('drop_lat', TRUE));
        $drop_lng = trim($this->input->get_post('drop_lng', TRUE));
		$vehicleId = trim($this->input->get_post('vehicleId', TRUE));
		$deviceId = trim($this->input->get_post('deviceId', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));

			if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

 $this->checkDeviceToken($userId,$deviceId);
		$distance =  $this->User_Webservice_model->distance($pickup_lat,$pickup_lng ,$drop_lat,$drop_lng);




		if($distance == 0)
		{
			$distance  = 1;
		}

		$getPerKmPrice = $this->User_Webservice_model->getDataById('tbl_vehicle_category',array("id"=>$vehicleId));
		if($getPerKmPrice['pricePerKM'] != 0)
		{
			$totalFair = round($distance*$getPerKmPrice['pricePerKM']);
			if($totalFair != 0)
			{
				
					$result['status'] = 1;
					$result['responseMessage'] = "Total Fair";
					$result['AllData'] = array("totalFair"=>$totalFair,"distance"=>$distance);
				
			}
			else{

				$result['status'] = 0;
				$result['responseMessage'] = "Sorry! Can't calculate this Vehicel, please choose another one.";
			}
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "Sorry! Can't calculate this Vehicel, please choose another one.";
		}

		echo json_encode($result);

	}
	
	
	
	
	function confirmRide()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$data['userId'] = trim($this->input->get_post('userId', TRUE));
		$data['name'] = trim($this->input->get_post('name', TRUE));
		$data['phone'] = trim($this->input->get_post('phone', TRUE));
		$data['pickup_address'] = trim($this->input->get_post('pickup_address', TRUE));
        $data['pickup_lat'] = trim($this->input->get_post('pickup_lat', TRUE));
        $data['pickup_lng'] = trim($this->input->get_post('pickup_lng', TRUE));
        $data['drop_lat'] = trim($this->input->get_post('drop_lat', TRUE));
        $data['drop_lng'] = trim($this->input->get_post('drop_lng', TRUE));
		$data['drop_address'] = trim($this->input->get_post('drop_address', TRUE));
		$data['vehicleId'] = trim($this->input->get_post('vehicleId', TRUE));
		$data['totalCharge'] = trim($this->input->get_post('totalCharge', TRUE));
		$data['totalDistance'] = trim($this->input->get_post('totalDistance', TRUE));
		$data['created_at'] = date("Y-m-d H:i:s");
		   $deviceId = trim($this->input->get_post('deviceId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		  $this->checkDeviceToken($data['userId'],$deviceId);

			
		$insert = $this->User_Webservice_model->insert('tbl_booking',$data);

		if($insert)
		{

			$distance = '25';
			$searchData = $this->User_Webservice_model->searchDriver($data['pickup_lat'],$data['pickup_lng'],$distance,$data['vehicleId']);
			// echo "<pre>";
			// print_r($searchData);die;
			 
			if($searchData)
			{

				foreach ($searchData as $key => $value) {
					$msg = array(
								"title"=>"Auto Load",
								"body" => "You have a new ride Request.",
								"userId" => $data['userId'],
								"rideId" => $insert,
						);
						$token = $value['deviceToken'];
						$this->send($token,$msg);
				}

				
					$result['status'] = 1;
					$result['responseMessage'] = "Request sent to Driver";
					$result['rideId'] = $insert;
				
			}
			else{

				$result['status'] = 1;
				$result['responseMessage'] = "Ride Confirmed";
				$result['rideId'] = $insert;
			}
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "Somthing Went Wrong, please try Again.";
		}

		echo json_encode($result);

	}

	function getRideDetail()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $deviceId = trim($this->input->get_post('deviceId', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 $this->checkDeviceToken($userId,$deviceId);
		$rideData =  $this->User_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId));

		if($rideData)
		{
			
					if($rideData['vehicleId'] != 0){
						$vehicleData = $this->User_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$rideData['vehicleId'],"publish_status"=>1),"id as vehicleId, vehicle_name,image,vehiDesc,publish_status");

						if($vehicleData != '' ):
							$vehicleData['image'] = base_url()."assets/vehicleImages/".$vehicleData['image'];
							$rideData['vehicleData'] = $vehicleData;

						else:
							$rideData['vehicleData'] = "";	
						endif;

						if($rideData['driverId'] != 0):

							$rideData['driverData'] = $this->User_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData['driverId'],"id as driverId,*"));

						else:
							$rideData['driverData'] = "";
						endif;
					}
					else{
						$rideData['vehicleData'] = "";
					}
					



					$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $rideData;
				
		
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "Can't find the ride Details, please check again.";
		}

		echo json_encode($result);

	}


	function getDriverDetails()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $driverId = trim($this->input->get_post('driverId', TRUE));
         $deviceId = trim($this->input->get_post('deviceId', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 $this->checkDeviceToken($userId,$deviceId);
		$driverData =  $this->User_Webservice_model->getDataById('tbl_driver',array("id"=>$driverId));

		if($driverData)
		{
			
					if($driverData['vehicleCategoryId'] != 0){
						$vehicleData = $this->User_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$driverData['vehicleCategoryId'],"publish_status"=>1),"id as vehicleCategoryId, vehicle_name,image,vehiDesc,publish_status");

						if($vehicleData != '' ):
							$vehicleData['image'] = base_url()."assets/vehicleImages/".$vehicleData['image'];
							$driverData['vehicleData'] = $vehicleData;

						else:
							$driverData['vehicleData'] = "";	
						endif;

					}
					else{
						$driverData['vehicleData'] = "";
					}
					



					$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $driverData;
				
		
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "Can't find the Driver Details, please check again.";
		}

		echo json_encode($result);

	}
	
	
	function updateProfile()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));
		$name = trim($this->input->get_post('name', TRUE));
		$email = trim($this->input->get_post('email', TRUE));
		$deviceId = trim($this->input->get_post('deviceId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		 $this->checkDeviceToken($userId,$deviceId);

		 // Check Duplicate Email
		 $where_email = ["email"=>$email];
		 // $checkEmail = $this->User_Webservice_model->getData('tbl_users',$where_email);
		 // if($checkEmail)
		 // {
		 // 	echo json_encode(array('status' => 0, 'responseMessage' => 'This Email Address Already Register with Us.'));die;
		 // }



		$arr = array();
		$arr = array('name'=>$name,"email"=>$email);
		if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
            {
                    
                $filename = explode('.', $_FILES['image']['name']);
                $filename = 'profile_' .time().rand(100,999).'.'. $filename[count($filename)-1];
                $_FILES['image']['name'] = $filename;
                

                $config['upload_path'] = 'assets/profileImage/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                  if($this->upload->do_upload('image')){
                    $uploadData = $this->upload->data();
                    $data1 = $this->upload->data();
                    $arr['profilePic']  = $filename; 
                  }
                  else
                  {
                    	$result['status'] = 0;
						$result['responseMessage'] = "Somthing Went Wrong";die;
						
                  }
            }

		
		$where = array('id'=>$userId);
		$data = $this->User_Webservice_model->update('tbl_users',$arr,$where);
		if($data)
		{
			if($arr['profilePic']!= '')
			{
			    $arr[profilePic] = base_url('assets/profileImage/').$arr[profilePic];
			}
			else
			 $arr[profilePic] = "";
			$result['status'] = 1;
			$result['responseMessage'] = "User Profile Update Successfully ";
			$result['AllData'] = $arr;
			
		}
		else
		{
			$result['status'] = 0;
			$result['responseMessage'] = "Profile Not Updated";
		}
		echo json_encode($result);

	}

    function cancelRideByUser()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideStatus = trim($this->input->get_post('rideStatus', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
        $deviceId = trim($this->input->get_post('deviceId', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $cancelReasone = trim($this->input->get_post('cancelReasone', TRUE));
        
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 // $this->checkDeviceToken($userId,$deviceId);

		 $checkRideBookingStatus = $this->User_Webservice_model->checkRideBookingStatus($rideId);
		 if($checkRideBookingStatus)
		 {
		 	$updateStatus = $this->User_Webservice_model->updateRideStatus($rideStatus,$rideId,$cancelReasone);
		 	          $booking_id = $checkRideBookingStatus["id"];
		 	          $driverId = $checkRideBookingStatus["driverId"];

				//print_r($notcheck);die();
                 $title = "System";
                 $msg="Booking #".$rideId." has been cancelled";
                 $date = date('Y-m-d H:i:s');
                 $insert = array("booking_id"=>$booking_id,"title"=>$title,"msg"=>$msg,"driver_id"=>$driverId,"created_at"=>$date);
                            $this->db->insert("tbl_notification",$insert);

                  

		 	if($updateStatus)
		 	{

		 			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = array("rideId"=>$rideId,"cancelReasone"=>$cancelReasone,"rideStatus"=>$rideStatus,"userId"=>$userId);

		 	}
		 	else
		 	{
		 		$result['status'] = 0;
				$result['responseMessage'] = "Can't Update the Status";
		 	}
		 }
		 else
		 {
		 		$result['status'] = 0;
				$result['responseMessage'] = "Your ride is confirmed, you cannot cancel this ride.";
		 }

		echo json_encode($result);
	}
	
	


	 public function send($token,$msg)
    {
        // echo $tokens;die;
        
          $api_key = "AAAAPm3Nuww:APA91bHYnRcrgJS_Odb1PspvI54BYaJuovfCDuJF2p4zQ1WKLLcveYJ97oQJejwHpwOzGhOOxKmZAA9endxUEkYuiEEuLAcYKCrfeHRsjDOT7AmGjluB1X9YDE5ozfQDWyvZSzOGuH4d";
          $fcmUrl= "https://fcm.googleapis.com/fcm/send";
            
           
                
        //   print_r($value);
        
        if (!empty($token)) {

            $notificationData = [
                'title'  => $msg['title'],
                'body'   => $msg['body'],
                'userId'=> $msg['userId'],
               
                'sound' => 'mySound'
            ];

            $fcmNotification = [

                'to'          => $token, //single token
                'collapseKey' => "{$token}",
                'data'        => $notificationData,

            ];
            $headers = [
                'Authorization: key=' . $api_key,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            
            //  print_r($result);die;
            if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
            // print_r($result);die;
                }
            curl_close($ch);
            return $result;
            
        }
        
          
            
            
    }

     	public function FindArticle(){
 	  $apiKey = trim($this->input->get_post('apiKey', TRUE));
      // $start_date = trim($this->input->get_post('start_date', TRUE));
      // $end_date = trim($this->input->get_post('end_date', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$articledata = $this->db->select("*")->from("tbl_article")->get()->result_array();
	  //  echo $start_date_new.$end_date_new;die();

		if(!empty($articledata)){
			
                   foreach($articledata as $key=>$cat_fam) {
          if($articledata[$key]["image"]!=""){
          	$articledata[$key]["ar_image"]=base_url()."assets/articleimg/".$articledata[$key]["image"];
          	            	unset($articledata[$key]["image"]);

            }
            else
            {
            	$articledata[$key]["ar_image"]="";
            	unset($articledata[$key]["image"]);
            }
           }

           			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $articledata;

		}
		else
		{
			        $result['status'] = 0;
					$result['responseMessage'] = " No Data Found";
			    //   $result['AllData'] = $dashboard;

 
		}
	
	
	   echo json_encode($result);


 	}

	


}


?>
