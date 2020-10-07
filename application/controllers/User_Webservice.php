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
		 $email = trim($this->input->get_post('email', TRUE));
		$deviceId = trim($this->input->get_post('deviceId', TRUE));
		$deviceType = trim($this->input->get_post('deviceType', TRUE));
		$phone = trim($this->input->get_post('mobile', TRUE));
        $languageType = trim($this->input->get_post('languageType', TRUE)); 
        $firebasetoken =trim($this->input->get_post('fireBaseToken', TRUE)); 
        //  1-> english 2->hindi 
        
        $socialMedia =trim($this->input->get_post('socialMedia', TRUE)); 
        $socialId =trim($this->input->get_post('socialId', TRUE)); 
        $socialImageUrl =trim($this->input->get_post('socialImageUrl', TRUE)); 
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
// 		echo API_KEY;die;
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		
			$data = $this->User_Webservice_model->checkMobileExists($phone);
			if($email!=""){
			$dataemail = $this->User_Webservice_model->checkEmailExists($email);
		}
		//print_r($dataemail);die();
		if($dataemail!=""){
		if($dataemail->isDeleted==1){
					$result['status'] = 0;
					$result['responseMessage'] = "Your Profile is Inactive.Please Contact to Administration";
					echo json_encode($result);die();

			}

			$result['status'] = 0;
			$result['responseMessage'] = "Email number Already Exits";
			echo json_encode($result);die();
		}
   if($data!=""){
		if($data->isDeleted==1){
					$result['status'] = 0;
					$result['responseMessage'] = "Your Profile is Inactive.Please Contact to Administration";
					echo json_encode($result);die();

			}
	}


			// print_r($data);die;
			//var_dump($flag); die;
			if($data == ""){
				$code = '1111';
				// $code=rand(1000,9999);
				// $phoneCoun = '91'.$phone;
				// $msg = "".$code." is your Verification OTP. Do not share this code with anyone else.";
				// $this->sendSMS($phoneCoun,$msg,$code);
				// $code = '1111';
				$tableData = array('name'=>$name,'city'=>$city,'state'=>$state,'phone'=>$phone,"phoneOtp"=>$code,"languageType"=>$languageType,"email"=>$email,'password'=>md5($password),'created_at'=>date('Y-m-d H:i:s'),"socialId"=>$socialId,"socialMedia"=>$socialMedia,"socialImageUrl"=>$socialImageUrl);
				
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
    
    function socialLogin()
    {
        $result = array();
	
		$data['email'] = trim($this->input->get_post('email', TRUE));
		$data['deviceId'] = trim($this->input->get_post('deviceId', TRUE));
		$data['deviceType'] = trim($this->input->get_post('deviceType', TRUE));
		$data['phone'] = trim($this->input->get_post('mobile', TRUE));
        $data['languageType'] = trim($this->input->get_post('languageType', TRUE)); 
        $data['firebasetoken'] =trim($this->input->get_post('fireBaseToken', TRUE)); 
        $data['socialMedia'] =trim($this->input->get_post('socialMedia', TRUE)); 
        $data['socialId'] =trim($this->input->get_post('socialId', TRUE)); 
        $data['socialImageUrl'] =trim($this->input->get_post('socialImageUrl', TRUE)); 
        //  1-> english 2->hindi 
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
// 		echo API_KEY;die;
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		
		if($data['email'] != "")
		$where['email'] = $data['email'];
		else
		$where['phone'] = $data['phone'];
		//$where["isDeleted"]=0;
	
		    $getUser = $this->db->get_where("tbl_users",$where)->row_array();
		    if(!empty($getUser))
		    {
		             $update = $this->db->update("tbl_users",array("socialMedia"=>$data['socialMedia'],"socialId"=>$data['socialId'],"socialImageUrl"=>$data['socialImageUrl']),$where);
		             if($update){
    		           
    		            
    		            
    		        	
    		        	    $tableData['deviceid'] = $data['deviceId'];
                    		$tableData['devicetype'] = $data['deviceType'];
                    		$tableData['fireBaseToken'] = $data['firebasetoken'];
                    		
                            $this->User_Webservice_model->update('tbl_users',$tableData,$where);
    		        	
    		        	     $result['userData'] =  $this->User_Webservice_model->getUserProfile($getUser['id']);
                        	if(!empty($result['userData']['profilepic'])){
                                    $result['userData']['profilepic'] = base_url('assets/profileImage/'.$result['userData']['profilepic']);
                                }
                                else{
                                    $result['userData']['profilepic'] =  "";
                                }
		             }
		             if($getUser["isDeleted"] == 0){
                       $result['status'] = 1;
                       $result['responseMessage']="Login Successfully";
                      }
                      if($getUser["isDeleted"] == 1){
                       $result['status'] = 0;
                       $result['responseMessage']="Your Profile is Inactive";
                      }
                           
		        
		    }
		    else
		    {
		               $result['status'] = 0;
                       $result['responseMessage']="Data Not Found";
                        $result['userData'] = $data;
		    }
		   
		  
		    
	echo json_encode($result);
	
		
		
    }

    public function resendOtp(){
    	$phone = trim($this->input->get_post('mobile', TRUE));
      
        //  1-> english 2->hindi 
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
// 		echo API_KEY;die;
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		
			$data = $this->User_Webservice_model->checkMobileExists($phone);
			if($data!=""){
		      if($data->isDeleted==1){
					$result['status'] = 0;
					$result['responseMessage'] = "Your Profile is Inactive.Please Contact to Administration";
					echo json_encode($result);die();

			}
	

				$code = '1111';
				// $code=rand(1000,9999);
				// $phoneCoun = '91'.$phone;
				// $msg = "".$code." is your Verification OTP. Do not share this code with anyone else.";
				// $this->sendSMS($phoneCoun,$msg,$code);
			    $result['status'] = 1;
					$result['responseMessage'] = "Otp resend";
					$result["otp"]=$code;
			}
			else
			{
				$result['status'] = 0;
					$result['responseMessage'] = "Mobie not found.";
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
		$deviceType = trim($this->input->get_post('deviceType', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$checkLoginUser = $this->User_Webservice_model->checkLoginUser($phone,$password);
		 
		if($checkLoginUser){
			$this->updateDeviceInfo($deviceId,$deviceType,$firebasetoken,$phone);
		if($checkLoginUser->isDeleted == 0){
			$result['status'] = 1;
            $result['responseMessage'] = "Login Successfully";
            
            $result['userData'] =  $this->User_Webservice_model->getUserProfile($checkLoginUser->id);
             
			if(!empty($result['userData']['profilepic'])){
                $result['userData']['profilepic'] = base_url('assets/profileImage/'.$result['userData']['profilepic']);
            }
            else{
                $result['userData']['profilepic'] = "";
            }
        }
        if($checkLoginUser->isDeleted == 1){
        	$result['status'] = 0;
			$result['responseMessage'] = "Your Profile Is Inactive";

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
			      if($data->isDeleted==1){
					$result['status'] = 0;
					$result['responseMessage'] = "Your Profile is Inactive.Please Contact to Administration";
					echo json_encode($result);die();

			}

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
			//print_r($data);die();
			if(!empty($data['profilepic'])){
					 $data['profilepic'] = base_url('assets/profileImage/'.$data['profilepic']);
                    }
            if(!empty($data['socialImageUrl'])){
					$data['socialImageUrl'] = $data['socialImageUrl'];
                    }
                    if(empty($data['profilepic'])){
					echo $data['profilepic'] = "";
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
	
	
	function getVehicleCategory1()
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

		
		$wherev = array('publish_status'=>1);
		$data = $this->User_Webservice_model->getData('tbl_vehicle_category',$wherev);
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

		
		$wherev = array('publish_status'=>1);
		$data = $this->User_Webservice_model->getData('tbl_vehicle_category',$wherev);
		if($data)
		{

            foreach ($data as $key => $value) {
            	//print_r($data);
			     //echo $data[$key]['pricePerKM'];die();
            	$distance1="25";
              $searchData = $this->User_Webservice_model->searchDriverfortime($pickup_lat,$pickup_lng,$distance1,$data[$key]["id"]);
             // print_r($searchData);
             if(!empty($searchData)){
             	$lat = $searchData[0]["lat"];
             	$lng = $searchData[0]["lng"];
             	    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat.",".$lng."&destinations=".$pickup_lat.",".$pickup_lng."&mode=driving&language=pl-PL&key=AIzaSyDu09OTRUuqUanO3wkqiD6kC9waAd46oK4";
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, $url);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			    $response = curl_exec($ch);
			    curl_close($ch);
			    $response_a = json_decode($response, true);
			    //print_r($response_a);die();
			    //echo $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
			     $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
            	$data[$key]['time']=$time;
            	$pricekm = $data[$key]['pricePerKM'];
			    $totalFair = round($distance * $pricekm);
			    $totalFairmax = round($distance*3*$pricekm);
			 // $data[$key]["minPrice"]=$totalFair;
			 //  $data[$key]["maxPrice"]=$totalFairmax;
             $data[$key]["driver_avilable"]=1;
             $data[$key]["totalCharge"]=$totalFair;
            // $data[$key]["distance"]=$distance;


             }
             else
             {
             	 $pricekm = $data[$key]['pricePerKM'];
			    $totalFair = round($distance * $pricekm);
			    $totalFairmax = round($distance*3*$pricekm);
               $data[$key]["driver_avilable"]=0;
               $data[$key]["time"]="";
               $data[$key]["totalCharge"]=$totalFair;
             }
           
//price = array("minprice"=>$totalFair,"maxprice"=>$totalFairmax,"distance"=>$distance);

			    ($data[$key]["minPrice"]);
			    ($data[$key]["maxPrice"]);
			    unset($data[$key]["pricePerKM"]);
			    unset($data[$key]["createdAt"]);
			    unset($data[$key]["publish_status"]);
//$data[$key]["distance"]=$distance;
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


    function delete_address(){
    	 $apiKey   = trim($this->input->get_post('apiKey', TRUE));
        $userId    = trim($this->input->get_post('userId', TRUE));
        $addressId = trim($this->input->get_post('addressId', TRUE));
        $deviceId  = trim($this->input->get_post('deviceId', TRUE));
	 

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
        }
        

       $this->checkDeviceToken($userId,$deviceId);

        $data = $this->User_Webservice_model->getData('tbl_address',array('userId'=>$userId,"addressId"=>$addressId));
        //print_r($data);die();
        if($data)
        {
        	$this->db->delete('tbl_address',array('userId'=>$userId,"addressId"=>$addressId));
            $result['status'] = 1;
			$result['responseMessage'] = "Delete Data success";
			$result['addressId'] = $addressId;
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

		$getPerKmPrice = $this->User_Webservice_model->getDataById('tbl_vehicle_category',array("id"=>$vehicleId,"publish_status"=>1));
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

$data["booking_no"]="CRN".mt_rand(100000, 999999);

		  $this->checkDeviceToken($data['userId'],$deviceId);

			
		$insert = $this->User_Webservice_model->insert('tbl_booking',$data);

		if($insert)
		{

			$distance = '25';
			$searchData = $this->User_Webservice_model->searchDriver($data['pickup_lat'],$data['pickup_lng'],$distance,$data['vehicleId']);
			// echo "<pre>";
			//print_r($searchData);die;
			 
			if($searchData)
			{

				foreach ($searchData as $key => $value) {
		         $title = "NewBooking";
                 $msg="";
                 $date = date('Y-m-d H:i:s');
                 $insert = array("booking_id"=>$insert,"title"=>$title,"msg"=>$msg,"driver_id"=>$value["id"],"created_at"=>$date);
                $this->db->insert("tbl_notification",$insert);
                 
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
				$result['responseMessage'] = "Your Ride is Confirmed";
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
        //$driverId = trim($this->input->get_post('driverId', TRUE));
         //$deviceId = trim($this->input->get_post('deviceId', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));
		$rideId = trim($this->input->get_post('rideId', TRUE));
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		$checkride = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$rideId,"userId"=>$userId));
		//print_r($checkride);die();
	if(!empty($checkride)){
		$driverData =  $this->User_Webservice_model->getDataById('tbl_driver',array("id"=>$checkride["driverId"]));
		$checkrating = $this->User_Webservice_model->getDataById("tbl_driver_rating",array("driverId"=>$checkride["driverId"]));
		$avgrating="0.0";
		if (!empty($checkrating)) {
			$max=0;
			for ($i=0; $i <=count($checkrating) ; $i++) { 
				$max=$max+$checkrating["rating"];
			}
			$avgrating = $max/count($checkrating);
			$avgrating = number_format((float)$avgrating, 2, '.', '');
		}

      $ridedata = array();

		if($driverData)
		{
			$ridedata["Name"]=$driverData["name"];
			$ridedata["VechicleNumber"]=$driverData["vehicleNumber"];
			if($driverData["profilepic"]!=""){
			$ridedata["DriverImage"]= base_url()."assets/profileImage/".$driverData["profilepic"];
		   }
		   else
		   {
		   	$ridedata["DriverImage"]="";
		   }
		   if($checkride["token"]!=""){
		   	$ridedata["token"]=$checkride["token"];
		   }
		   if($checkride["token"]==""){
		   	$ridedata["token"]="";
		   }
		   	$ridedata["pickup_address"]=$checkride["pickup_address"];
		   	$ridedata["drop_address"]=$checkride["drop_address"];
		   	$ridedata["drop_address"]=$checkride["drop_address"];
		   	$ridedata["pickup_lat"]=$checkride["pickup_lat"];
		   	$ridedata["pickup_lng"]=$checkride["pickup_lng"];
		   $ridedata["drop_lat"]=$checkride["drop_lat"];
		   $ridedata["drop_lng"]=$checkride["drop_lng"];
           $ridedata["rating"]=$avgrating;
		   $ridedata["driverPhone"]=$driverData["phone"];
           $ridedata["driverId"]=$checkride["driverId"];

			
					if($driverData['vehicleCategoryId'] != 0){
						$vehicleData = $this->User_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$driverData['vehicleCategoryId'],"publish_status"=>1),"id as vehicleCategoryId, vehicle_name,image,vehiDesc,publish_status");

						if($vehicleData != '' ):
							$vehicleData['image'] = base_url()."assets/vehicleImages/".$vehicleData['image'];
							$driverData['vehicleData'] = $vehicleData;

						else:
							$driverData['vehicleData'] = "";	
						endif;
                      $ridedata["VechicleImage"]=$vehicleData['image'];
                      $ridedata["VechicleName"]=$vehicleData['vehicle_name'];
					}
					else{
						$driverData['vehicleData'] = "";
					}
					



					$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $ridedata;
				
		
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "Can't find the Driver Details, please check again.";
		}
	}
	else
	{
		            $result['status'] = 0;
					$result['responseMessage'] = "No Ride Found";
					//$result['AllData'] = $ridedata;

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
		// $this->checkDeviceToken($userId,$deviceId);

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
		//print_r($data);
		$datar = $this->User_Webservice_model->getDataById("tbl_users",array("id"=>$userId,"isDeleted"=>0));
		//print_r($datar);
		if($datar)
		{
			if($datar['profilepic']!= '')
			{
			    $datar["profilepic"] = base_url('assets/profileImage/').$datar["profilepic"];
			}
           else if(!empty($datar['socialImageUrl'])){
					$datar['socialImageUrl'] = $datar['socialImageUrl'];
                    }
			else {
			 $datar["profilepic"] = "";
			}
			$result['status'] = 1;
			$result['responseMessage'] = "User Profile Update Successfully ";
			$result['AllData'] = $datar;
			
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
                 $insert = array("booking_id"=>$booking_id,"title"=>$title,"msg"=>$msg,"driver_id"=>$driverId,"created_at"=>$date,"status"=>1);
                            $this->db->insert("tbl_notification",$insert);
		    $canceldata = array("rideId"=>$rideId,"driverId"=>$driverId,"status"=>3,"create_at"=> date('Y-m-d H:i:s'));
		  
		 	$this->db->insert("driver_cancel_history",$canceldata);

                  

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


	function send_feedback(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
        $deviceId = trim($this->input->get_post('deviceId', TRUE));
		$feedback = trim($this->input->get_post('feedback', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 // $this->checkDeviceToken($userId,$deviceId);
$checkride = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$rideId,"userId"=>$userId));
if(!empty($checkride)){
		 $checkRideBookingStatus = $this->User_Webservice_model->checkRideBookingStatusdrop($rideId);
		// print_r($checkRideBookingStatus);die();
//echo $checkRideBookingStatus["rideStatus"];die();
		 if($checkRideBookingStatus["rideStatus"]==4)
		 {
		 	$in  = array("ride_id"=>$rideId,"user_id"=>$userId,"feedback"=>"feedback");
		 	$updateStatus = $this->User_Webservice_model->insert("tbl_feedback",$in);

		 	if($updateStatus)
		 	{

		 			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = array("rideId"=>$rideId,"feedbackid"=>$updateStatus);

		 	}
		 	else
		 	{
		 		$result['status'] = 0;
				$result['responseMessage'] = "Can't insert the Feedback";
		 	}
		 }
		 else
		 {
		 		$result['status'] = 0;
				$result['responseMessage'] = "Your ride is not complete, you cannot send feeedback for this ride.";
		 }
		}
		else
		{
           		 $result['status'] = 0;
				$result['responseMessage'] = "Ride Not Found";

		}

		echo json_encode($result);

	}

	public function Payment_trascation_detail(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
       // $deviceId = trim($this->input->get_post('deviceId', TRUE));
		$trascation_id = trim($this->input->get_post('trascation_id', TRUE));
       	$amount = round(trim($this->input->get_post('amount', TRUE)));
       	$payment_mode = trim($this->input->get_post('payment_mode', TRUE));
       	$payment_status = trim($this->input->get_post('payment_status', TRUE));
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 // $this->checkDeviceToken($userId,$deviceId);
     $checkride = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$rideId,"userId"=>$userId));
    // $trascation_id ="";
if(!empty($checkride)){
		  if($trascation_id == ""){
		  	$trascation_id == "";
		  }
		  else
		  {
		  	$trascation_id = $trascation_id;
		  }
		  $payment_modes="";
		  if($payment_mode == 1){
		  	$payment_modes="Cash";
		  	$payment_status=1;
		  }
		  if($payment_mode == 2){
		  	$payment_modes="paymetMethod";
		  	if($payment_status == 0){
	  		   $result['status'] = 0;
				$result['responseMessage'] = "Your Trascation hasbeen Failure.Please Try Again";
				//$result['AllData'] = $paymentdata;
              		echo json_encode($result);die();


		  	}
		  }
		  if($payment_mode == 3){
		  	$payment_modes="Wallet";
		  	$payment_status=1;
		  	$amountuser = $this->User_Webservice_model->getDataById("user_amount_transfer",array("user_id"=>$userId));
		  	//echo($amountuser);die();
		  	if(($amountuser!="")){
		  		//echo $amountuser["amount"].$amount;
		  		if($amount > $amountuser["amount"]){
                       $result['status'] = 0;
				        $result['responseMessage'] = "Your wallet not have amount for payment";
				        echo json_encode($result);die();

		  		}
		  	}
		      if(($amountuser == ""))
		  		{
		  			//echo "sds";die();
                       $result['status'] = 0;
				        $result['responseMessage'] = "Your wallet not have amount for payment";
				        echo json_encode($result);die();

	

		  		}


		  }
		 	$in  = array("ride_id"=>$rideId,"user_id"=>$userId,"trascation_id"=>"$trascation_id","amount"=>$amount,"payment_mode"=>"$payment_mode","payment_status"=>$payment_status,"created_at"=>date("Y-m-d h:i:s a"));
		 	$updateStatus = $this->User_Webservice_model->insert("tbl_ride_payment",$in);
		 	$paymentdata  = $this->User_Webservice_model->getDataById("tbl_ride_payment",array("id"=>$updateStatus));
		 $taxinfo = $this->User_Webservice_model->getDataById('tbl_settings',array('id'=>1));

			$taxRate=round($taxinfo["percent"]);

		 	$number = $taxRate."%";
            $amount_transfer =round($number / ($amount / 100),2);
                 $taxrate= ($taxRate / 100) * $amount;

            $driver_amount = round($amount-$taxrate);
		 	$company_transfer = $taxrate;	
		 	//echo $amount_transfer;die();



		 	if($updateStatus)
		 	{//print_r($checkride);die();
		 		if($payment_mode == 1)  ///cash//
		 		{
		 		  $this->User_Webservice_model->insert("tbl_driver_amount_transfer",array("driverId"=>$checkride["driverId"],"amount"=>$driver_amount,"payment_mode"=>$payment_mode,"transferAt"=>date("Y-m-d h:i:s a"),"rideId"=>$rideId));
		 		 $this->User_Webservice_model->insert("tbl_company_amount_transfer",array("driver_id"=>$checkride["driverId"],"payment_mode"=>$payment_mode,"user_id"=>$userId,"amount"=>$taxrate,"transferAt"=>date("Y-m-d h:i:s a"),"ride_id"=>$rideId));
		 		}
		 		if($payment_mode == 2)  ///paymeny methd//
		 		{
		 			if($payment_status == 1){
		 		  $this->User_Webservice_model->insert("tbl_driver_amount_transfer",array("driverId"=>$checkride["driverId"],"amount"=>$driver_amount,"payment_mode"=>$payment_mode,"transferAt"=>date("Y-m-d h:i:s a"),"rideId"=>$rideId));
		 		 $this->User_Webservice_model->insert("tbl_company_amount_transfer",array("driver_id"=>$checkride["driverId"],"payment_mode"=>$payment_mode,"user_id"=>$userId,"amount"=>$taxrate,"transferAt"=>date("Y-m-d h:i:s a"),"ride_id"=>$rideId));

		 		}
		 		}
		 		if($payment_mode == 3)  ///wallet//
		 		{
		 			if($payment_status == 1){

		 		  $this->User_Webservice_model->insert("tbl_driver_amount_transfer",array("driverId"=>$checkride["driverId"],"amount"=>$driver_amount,"payment_mode"=>$payment_mode,"transferAt"=>date("Y-m-d h:i:s a"),"rideId"=>$rideId));
		 		 $this->User_Webservice_model->insert("tbl_company_amount_transfer",array("driver_id"=>$checkride["driverId"],"payment_mode"=>$payment_mode,"user_id"=>$userId,"amount"=>$taxrate,"transferAt"=>date("Y-m-d h:i:s a"),"ride_id"=>$rideId));
              $mainamount = $amountuser["amount"]-$amount;
	        	$this->User_Webservice_model->update("user_amount_transfer",array("amount"=>$mainamount),array("user_id"=>$userId));
	           $paymentdata  = $this->User_Webservice_model->getDataById("user_amount_transfer",array("user_id"=>$userId));
	          $wallet = array("amount"=>$amount,"rideId"=>$rideId,"userId"=>$userId,"trascationId"=>$trascation_id,"payment_status"=>$payment_status,"payment_mode"=>0,"created_at"=>date("Y-m-d h:i:s a"));
           	$updateStatus1 = $this->User_Webservice_model->insert("user_wallet_histroy",$wallet);



		 		}
		 		}
                /*notification add*/
		 		$daten = date('Y-m-d H:i:s');
		 		$title = "System";

		 		$msg="Thank you! Your Transaction is Completed";
                 $insert = array("booking_id"=>$rideId,"title"=>$title,"msg"=>$msg,"driver_id"=>$checkride["driverId"],"created_at"=>$daten,"status"=>2);
                 $this->db->insert("tbl_notification",$insert);
                $firebasetoken =  $this->db->select("*")->from("tbl_users")->where("id",$userId)->get()->result_array();
               // print_r($firebasetoken);die();
            	if(!empty($firebasetoken)){
            		$firetokend = $firebasetoken[0]["fireBaseToken"];
            	}
            	else
            	{
            		$firetokend="";
            	}

        					$msg = array(
						"title"=>"Auto Load",
						"body" => "You Trascation is Completed.",
						"userId" => $userId,
						"rideId" => $rideId,
				);
						$token = $firetokend;
						$this->send($token,$msg);


		 			$result['status'] = 1;
					$result['responseMessage'] = "Your Payment Successfully Done";
					$result['AllData'] = $paymentdata;

		 	}
		 	else
		 	{
		 		$result['status'] = 0;
				$result['responseMessage'] = "Can't insert the PaymentInfo";
		 	}
		 }
		 
		
		else
		{
           		 $result['status'] = 0;
				$result['responseMessage'] = "Ride Not Found";

		}

		echo json_encode($result);

	}









	public function wallet_balance(){
	    $apiKey = trim($this->input->get_post('apiKey', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $checkuser = $this->User_Webservice_model->getDataById("tbl_users",array("id"=>$userId,"isDeleted"=>0));
      if(!empty($checkuser)){
		 $checkride = $this->User_Webservice_model->getDataById("user_amount_transfer",array("user_id"=>$userId));
		$mainamount=0;
        if(!empty($checkride)){
        	$mainamount = $checkride["amount"];
        	$result['status'] = 1;
			$result['responseMessage'] = "All Data";
			$result['Balance'] = $mainamount;

        }
        else
        {
            $result['status'] = 1;
			$result['responseMessage'] = "All Data";
			$result['Balance'] = $mainamount;

        }
    }
    else {
    	      $result['status'] = 0;
			  $result['responseMessage'] = "User Not Found";
    }
        		echo json_encode($result);


	}


	public function add_money(){
	  	$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
       // $deviceId = trim($this->input->get_post('deviceId', TRUE));
       $trascation_id = trim($this->input->get_post('trascation_id', TRUE));
       $payment_status = trim($this->input->get_post('payment_status', TRUE));

       	$amount = trim($this->input->get_post('amount', TRUE));
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $checkuser = $this->User_Webservice_model->getDataById("tbl_users",array("id"=>$userId,"isDeleted"=>0));
     if(!Empty($checkuser)){
		if($payment_status == 1){

		 // $this->checkDeviceToken($userId,$deviceId);
		  $amountdata  = $this->User_Webservice_model->getDataById("user_amount_transfer",array("user_id"=>$userId));
		  $mainamount=0;
        if(!empty($amountdata)){
        	if($payment_status ==1){
        	$mainamount = $amountdata["amount"]+$amount;
        	$this->User_Webservice_model->update("user_amount_transfer",array("amount"=>$mainamount),array("user_id"=>$userId));
           $paymentdata  = $this->User_Webservice_model->getDataById("user_amount_transfer",array("user_id"=>$userId));
       }
           $wallet = array("amount"=>$amount,"rideId"=>0,"userId"=>$userId,"trascationId"=>$trascation_id,"payment_status"=>$payment_status,"payment_mode"=>1,"created_at"=>date("Y-m-d h:i:s a"));
           	$updateStatus1 = $this->User_Webservice_model->insert("user_wallet_histroy",$wallet);


        }
        else
        {
        	if($payment_status == 1){
        	$mainamount = $amount;
		 	$in  = array("user_id"=>$userId,"amount"=>$mainamount,"status"=>0,"created_at"=>date("Y-m-d h:i:s a"));
		 	$updateStatus = $this->User_Webservice_model->insert("user_amount_transfer",$in);
		 }
		 	$paymentdata  = $this->User_Webservice_model->getDataById("user_amount_transfer",array("id"=>$updateStatus));
		 	 $wallet = array("amount"=>$amount,"rideId"=>0,"userId"=>$userId,"trascationId"=>$trascation_id,"payment_status"=>$payment_status,"payment_mode"=>1,"created_at"=>date("Y-m-d h:i:s a"));
           	$updateStatus1 = $this->User_Webservice_model->insert("user_wallet_histroy",$wallet);


       }

		 	if($payment_status ==1)
		 	{//print_r($checkride);die();
		 			$result['status'] = 1;
					$result['responseMessage'] = "Recharge Wallet Successfully";
					$result['Balance'] = $paymentdata["amount"];

		 	}
		 	else
		 	{
		 		$result['status'] = 0;
				$result['responseMessage'] = "Can't Recharge The WalletAmount";
		 	}
		}
		else
		{
			    $result['status'] = 0;
				$result['responseMessage'] = "Can't Recharge The WalletAmount.Your Trascation Failure";


		}
	}
	else{
		      $result['status'] = 0;
			  $result['responseMessage'] = "User Not Found";

	}
		

		echo json_encode($result);
	
	}

	public function Trascation_histroy(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
       // $deviceId = trim($this->input->get_post('deviceId', TRUE));
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $checkuser = $this->User_Webservice_model->getDataById("tbl_users",array("id"=>$userId,"isDeleted"=>0));

        if(!empty($checkuser)){
		 // $this->checkDeviceToken($userId,$deviceId);
		  $amountdata  = $this->User_Webservice_model->getData("user_wallet_histroy",array("userId"=>$userId,"payment_status"=>1));
		  $mainamount=0;
		 // print_r($amountdata);die();
		  $trascation_Data=array();
        if(!empty($amountdata)){
        	for($i=0;$i<count($amountdata);$i++){
        		$trascation_Data[$i]["status"]=$amountdata[$i]["payment_mode"];
        		$trascation_Data[$i]["amount"]=$amountdata[$i]["amount"];
        		$explode_Date = explode(" ", $amountdata[$i]["created_at"]);
        		$dateFormat = date("m-d-Y h:i A", strtotime($amountdata[$i]["created_at"]));

        		$trascation_Data[$i]["date"]=$dateFormat;
        		if($amountdata[$i]["payment_mode"]==1){
        		 $trascation_Data[$i]["title"]="Money added to Wallet";
        	   }
        	   if($amountdata[$i]["payment_mode"]==0){
		           $ridedata  = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$amountdata[$i]["rideId"]));

        		 $trascation_Data[$i]["title"]="Paid For Ride".$ridedata["booking_no"]."";
        	   }
        	   }

        	         $result['status'] = 1;
					$result['responseMessage'] = "AllData";
					$result['AllData'] = $trascation_Data;
        }
        else
        {
        	    $result['status'] = 0;
				$result['responseMessage'] = "No Wallet Trascation Histroy Found";

        	
       }
      }
      else{
      		  $result['status'] = 0;
			  $result['responseMessage'] = "User Not Found";

      }


		echo json_encode($result);
	
	}


   	public function Deduct_user_wallet(){
	  	$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
        $deviceId = trim($this->input->get_post('deviceId', TRUE));
        $id = trim($this->input->get_post('id', TRUE));

       	$amount = trim($this->input->get_post('amount', TRUE));
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 // $this->checkDeviceToken($userId,$deviceId);
     $checkride = $this->User_Webservice_model->getDataById("tbl_ride_payment",array("user_id"=>$userId,"id"=>$id,"payment_mode"=>3));
if(!empty($checkride)){
		  $amountdata  = $this->User_Webservice_model->getDataById("user_amount_transfer",array("user_id"=>$userId));
		  $mainamount=0;
        if(!empty($amountdata)){
        	if($amount>$amountdata["amount"]){
                    $result['status'] = 0;
				    $result['responseMessage'] = "Your wallet not have amount for payment";
				        echo json_encode($result);die();


        	}
        	else
        	{
        	$mainamount = $amountdata["amount"]-$amount;
        	$this->User_Webservice_model->update("user_amount_transfer",array("amount"=>$mainamount),array("user_id"=>$userId));
           $paymentdata  = $this->User_Webservice_model->getDataById("user_amount_transfer",array("user_id"=>$userId));
         }
        }
        else
        {

        }

		 	if($paymentdata)
		 	{//print_r($checkride);die();
		 			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $paymentdata;

		 	}
		 	else
		 	{
		 		$result['status'] = 0;
				$result['responseMessage'] = "Can't insert the paymentifo";
		 	}
		 }
		 
		
		else
		{
           		 $result['status'] = 0;
				$result['responseMessage'] = "RidePayment Not Found";

		}


		echo json_encode($result);
	
	}

	public function FindCity(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $city = trim($this->input->get_post('city', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 // $this->checkDeviceToken($userId,$deviceId);
$cityfind = $this->User_Webservice_model->getDataById("tbl_city",array("title"=>strtolower($city)));
if(!empty($cityfind)){
		 			$result['status'] = 1;
					$result['responseMessage'] = "City FindCity";
					$result['AllData'] = $city;

		 	}
		 	else
		 	{
		 		$result['status'] = 0;
				$result['responseMessage'] = "City Not Found";
		 	}
		
		echo json_encode($result);


	}

	 	public function AboutUs(){
 	   	  $apiKey = trim($this->input->get_post('apiKey', TRUE));
      // $start_date = trim($this->input->get_post('start_date', TRUE));
      // $end_date = trim($this->input->get_post('end_date', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$aboutusdata = $this->db->select("*")->from("tbl_aboutus")->get()->row_array();
	  //  echo $start_date_new.$end_date_new;die();

		if(!empty($aboutusdata)){
			
           			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $aboutusdata;

		}
		else
		{
			        $result['status'] = 0;
					$result['responseMessage'] = " No Data Found";
			    //   $result['AllData'] = $dashboard;

 
		}
	
	
	   echo json_encode($result);


	
 	}


 	public function add_Rating(){
 		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $driverId = trim($this->input->get_post('driverId', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
        $rating = trim($this->input->get_post('rating', TRUE));
        $msg = trim($this->input->get_post('msg', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$checkride = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$rideId,"userId"=>$userId));

if(!empty($checkride)){
		 // $this->checkDeviceToken($userId,$deviceId);
		$data = array("userId"=>$userId,"rideId"=>$rideId,"driverId"=>$driverId,"msg"=>$msg,"rating"=>$rating,"created_at"=>date('Y-m-d H:i:s'));
      $ratingdata = $this->User_Webservice_model->insert("tbl_driver_rating",$data);

		 			$result['status'] = 1;
					$result['responseMessage'] = "ThankYou for Adding Rating";
					//$result['AllData'] = $city;

		 	
}
else
{
	            $result['status'] = 0;
				$result['responseMessage'] = "Ride Not Found";

}
		
		echo json_encode($result);



 	}




 	public function updateStatus(){
 		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        //$driverId = trim($this->input->get_post('driverId', TRUE));
         //$deviceId = trim($this->input->get_post('deviceId', TRUE));
		$userId = trim($this->input->get_post('userId', TRUE));
		$rideId = trim($this->input->get_post('rideId', TRUE));
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		$checkride = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$rideId,"userId"=>$userId));
		//print_r($checkride);die();
	if(!empty($checkride)){
		            $result['status'] = 1;
		            $result['AllData'] = array("rideStatus"=>$checkride["rideStatus"],"driverId"=>$checkride["driverId"]);
		           // $result['driverId'] = $checkride["driverId"];
		           // $result['status'] = 1;
					$result['responseMessage'] = "AllData";
	}
	else
	{
		            $result['status'] = 0;
					$result['responseMessage'] = "No Ride Found";
					//$result['AllData'] = $ridedata;

	}

		echo json_encode($result);

 	}

 	public function MyRidesHistory(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        //$rideId = trim($this->input->get_post('rideId', TRUE));
       $userId = trim($this->input->get_post('userId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		//$where["driverId"]=$driverId;
		//$where["rideStatus"]!=0;
		$rideData =  $this->User_Webservice_model->getDataByjoinId('tbl_booking',$userId);
//print_r($rideData);die();

		if($rideData)
		{
          foreach($rideData as $key=>$cat_fam) {
          	$driverinfo = $this->User_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData[$key]["driverId"]));

          	$vechicleinfo = $this->User_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$rideData[$key]["vehicleId"]));
			      if($rideData[$key]["profilepic"]!=""){
					$rideData[$key]["user_pic"]=base_url('assets/profileImage/').$rideData[$key]["profilepic"];
				    }
				    if($rideData[$key]["socialImageUrl"]!=""){
					$rideData[$key]["user_pic"]=$rideData[$key]["socialImageUrl"];
				    }
				    if($rideData[$key]["profilepic"]==""){
					$rideData[$key]["user_pic"]="";
				    }
				    if($rideData[$key]["driverpic"]!=""){
					$rideData[$key]["driver_pic"]=base_url('assets/profileImage/').$rideData[$key]["driverpic"];
				    }
				    
				    if($rideData[$key]["driverpic"]==""){
					$rideData[$key]["driver_pic"]="";
				    }
				    $rideData[$key]["vechicleimage"]=base_url('assets/vehicleImages/').$vechicleinfo["image"];
				    $rideData[$key]["driverPhoneno"]=$driverinfo["phone"];
				    $rideData[$key]["VechicleName"]=$vechicleinfo["vehicle_name"];
				      $date = explode(" ",$rideData[$key]["created_at"]);

				      if($date[0] == (date("Y-m-d"))){
                     
                     $new_date =date("h:i a", strtotime($date[1]));
                     $rideData[$key]["booking_date"]=$new_date;



                    }
                    else
                    {

                     $new_date =date("d-m-Y h:i a", strtotime($date[1]));
                    }
                       $rideData[$key]["booking_date"]=$new_date;

				    
   }

			//print_r($rideData);die();

			


			

					$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $rideData;
				
		
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "No Ride Details Found";
		}

		echo json_encode($result);

	}

		public function MyRidesHistoryDetails(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
       $userId = trim($this->input->get_post('userId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		//$where["driverId"]=$driverId;
		$where["id"]      =$rideId;
		//$where["rideStatus"]!=0;
		$rideData =  $this->User_Webservice_model->getDataByjoinRidesId('tbl_booking',$userId,$rideId);
//print_r($rideData);die();
$ridedatadetail =array();
		if($rideData)
		{
			$driverinfo = $this->User_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData[0]["driverId"]));
			$rideinfo = $this->User_Webservice_model->getDataById('tbl_ride_payment',array('ride_id'=>$rideId));
			//print_r($rideinfo);die();
			if(!empty($rideinfo)){
				if($rideinfo["payment_mode"]==1){
                  $ridedatadetail["payment_mode"]="Cash";
				}
				if($rideinfo["payment_mode"]==2){
				  $ridedatadetail["payment_mode"]="Online";

				}
				if($rideinfo["payment_mode"]==3){
				 $ridedatadetail["payment_mode"]="Wallet";

				}

				
			}
			if(empty($rideinfo))
			{
				     $ridedatadetail["payment_mode"]="";

			}
			

			$rating = $this->User_Webservice_model->getDataById('tbl_driver_rating',array('driverId'=>$rideData[0]["driverId"],"rideId"=>$rideId));
            if(!empty($rating)){
            	if(is_float($rating["rating"])){
            	$ridedatadetail["rating"]=$rating["rating"];
                }
                else
                {
                	$ridedatadetail["rating"]=$rating["rating"];
                }

            }
            else
            {
            	$ridedatadetail["rating"]="0.0";
            }
			//print_r($rideData);die();
			$bookdate = explode(" ",$rideData[0]["created_at"]);
			$Monthname = date("M",strtotime($bookdate[0]));
		    $Weekname = date("D",strtotime($bookdate[0]));
            $datename = date("d",strtotime($bookdate[0]));
            $end      =date("h:i A", strtotime($bookdate[1]));
          $bookingdate =$Weekname.","." ".$Monthname." ".$datename." ".$end;
			//echo $Monthname.$Weekname.$datename.$end;die();
			$ridedatadetail["booking_date"]=$bookingdate;
			$ridedatadetail["booking_no"]=$rideData[0]["booking_no"];
			$ridedatadetail["pickup_address"]=$rideData[0]["pickup_address"];
			$ridedatadetail["drop_address"]=$rideData[0]["drop_address"];
			$ridedatadetail["pickup_lat"]=$rideData[0]["pickup_lat"];
			$ridedatadetail["pickup_lng"]=$rideData[0]["pickup_lng"];
			$ridedatadetail["drop_lat"]=$rideData[0]["drop_lat"];
			$ridedatadetail["drop_lng"]=$rideData[0]["drop_lng"];
			$startridetime = explode(" ",$rideData[0]["startRideTime"]);
			$startridetimenew = date("h:i A", strtotime($startridetime[1]));
			$ridedatadetail["startRideTime"]=$startridetimenew;
			$date1 = strtotime($rideData[0]["startRideTime"]);  
$date2 = strtotime($rideData[0]["endRideTime"]);  
  
			// Formulate the Difference between two dates 
			$diff = abs($date2 - $date1);  
			  
			  
			// To get the year divide the resultant date into 
			// total seconds in a year (365*60*60*24) 
			$years = floor($diff / (365*60*60*24));  
			  
			  
			// To get the month, subtract it with years and 
			// divide the resultant date into 
			// total seconds in a month (30*60*60*24) 
			$months = floor(($diff - $years * 365*60*60*24) 
			                               / (30*60*60*24));  
			  
			  
			// To get the day, subtract it with years and  
			// months and divide the resultant date into 
			// total seconds in a days (60*60*24) 
			$days = floor(($diff - $years * 365*60*60*24 -  
			             $months*30*60*60*24)/ (60*60*24)); 
			  
			  
			// To get the hour, subtract it with years,  
			// months & seconds and divide the resultant 
			// date into total seconds in a hours (60*60) 
			$hours = floor(($diff - $years * 365*60*60*24  
			       - $months*30*60*60*24 - $days*60*60*24) 
			                                   / (60*60));  
			  
			  
			// To get the minutes, subtract it with years, 
			// months, seconds and hours and divide the  
			// resultant date into total seconds i.e. 60 
			$minutes = floor(($diff - $years * 365*60*60*24  
			         - $months*30*60*60*24 - $days*60*60*24  
			                          - $hours*60*60)/ 60); 
          $seconds = floor(($diff - $years * 365*60*60*24  
         - $months*30*60*60*24 - $days*60*60*24 
                - $hours*60*60 - $minutes*60));  
  
		 if($minutes!=0){
		 	$totalTime = $minutes." "."MIN";
		 }
		 else{
		 	$totalTime = $seconds." "."SEC";
		 }
  

			$endridetime = explode(" ",$rideData[0]["endRideTime"]);
			$endridetimenew = date("h:i A", strtotime($endridetime[1]));
          //  $minute = (strtotime($endridetime[1]) - $strtotime($startridetime[1]))/60;
//echo $minute;die();
			$ridedatadetail["endRideTime"]=$endridetimenew;
			$ridedatadetail["TotalCost"]=$rideData[0]["totalCharge"];
			$ridedatadetail["TotalTime"]=$totalTime;

			$ridedatadetail["totalDistance"]=$rideData[0]["totalDistance"];
			$ridedatadetail["TotalCostWithoutTax"]=round($rideData[0]["totalCharge"]);
		$taxinfo = $this->User_Webservice_model->getDataById('tbl_settings',array('id'=>2));

			$taxRate=$taxinfo["percent"];
            $tax=$rideData[0]["totalCharge"]*$taxRate/100;
             $totalprice = $rideData[0]["totalCharge"]+$tax;
			$ridedatadetail["TotalCostIncludedTax"]=round($totalprice);






			if($rideData[0]["driverpic"]!=""){
					$ridedatadetail["driver_pic"]=base_url('assets/profileImage/').$rideData[0]["driverpic"];
				    }

				    if($rideData[0]["driverpic"]==""){
					$ridedatadetail["driver_pic"]="";
				    }

			
          	$vechicleinfo = $this->User_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$rideData[0]["vehicleId"]));
			      //if($rideData[0]["profilepic"]!=""){
					/*$rideData[0]["user_pic"]=base_url('assets/profileImage/').$rideData[0]["profilepic"];
				    }
				    if($rideData[0]["socialImageUrl"]!=""){
					$rideData[0]["user_pic"]=$rideData[0]["socialImageUrl"];
				    }
				    if($rideData[0]["profilepic"]==""){
					$$rideData[0]["user_pic"]="";
				    }
				    if($rideData[0]["driverpic"]!=""){
					$rideData[0]["driver_pic"]=base_url('assets/profileImage/').$rideData[$key]["driverpic"];
				    }
				    
				    if($rideData[0]["driverpic"]==""){
					$rideData[0]["driver_pic"]="";
				    }*/
				    $ridedatadetail["vechicleimage"]=base_url('assets/vehicleImages/').$vechicleinfo["image"];
				     $ridedatadetail["VechicleName"]=$vechicleinfo["vehicle_name"];

				    
   

			//print_r($rideData);die();

			


			

					$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $ridedatadetail;
				
		
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "No Ride Details Found";
		}

		echo json_encode($result);

	}



	 	public function Support(){
 		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $driverId = trim($this->input->get_post('driverId', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));
        $name = trim($this->input->get_post('name', TRUE));
        $msg = trim($this->input->get_post('msg', TRUE));
        $email = trim($this->input->get_post('email', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$checkride = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$rideId,"userId"=>$userId));

if(!empty($checkride)){
		 // $this->checkDeviceToken($userId,$deviceId);
		$data = array("userId"=>$userId,"rideId"=>$rideId,"driverId"=>$driverId,"msg"=>$msg,"name"=>$name,"email"=>$email,"created_at"=>date('Y-m-d H:i:s'));
      $ratingdata = $this->User_Webservice_model->insert("tbl_support",$data);

		 			$result['status'] = 1;
					$result['responseMessage'] = "Thank you for contacting us.We will give you response soon!";
					//$result['AllData'] = $city;

		 	
}
else
{
	            $result['status'] = 0;
				$result['responseMessage'] = "Ride Not Found";

}
		
		echo json_encode($result);



 	}


 	 public function getBill(){
 		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $userId = trim($this->input->get_post('userId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$checkride = $this->User_Webservice_model->getDataById("tbl_booking",array("id"=>$rideId,"userId"=>$userId));
//print_r($checkride);die();
if(!empty($checkride)){
		 // $this->checkDeviceToken($userId,$deviceId);
		$ridedetail["pickup_address"] = $checkride["pickup_address"];
		$ridedetail["drop_address"] = $checkride["drop_address"];
		$taxinfo = $this->User_Webservice_model->getDataById('tbl_settings',array('id'=>2));

			$taxRate=$taxinfo["percent"];
            $tax=$checkride["totalCharge"]*$taxRate/100;
             $totalprice = $checkride["totalCharge"]+$tax;

	    $ridedetail["total_charge"] = round($totalprice);
        $ridedetail["ride_status"] = $checkride["rideStatus"];
    	$bookdate = explode(" ",$checkride["created_at"]);
		$Monthname = date("M",strtotime($bookdate[0]));
	    $Weekname = date("D",strtotime($bookdate[0]));
        $datename = date("d",strtotime($bookdate[0]));
        $end      = date("h:i A", strtotime($bookdate[1]));
          $bookingdate =$Weekname.","." ".$Monthname." ".$datename." "."at"." ".$end;
        $ridedetail["booking_date"] = $bookingdate;
        $ridedetail["driver_id"] = $checkride["driverId"];
        $ridedetail["ride_id"] = $checkride["id"];



		 			$result['status'] = 1;
					$result['responseMessage'] = "AllData";
					$result['AllData'] = $ridedetail;

		 	
	}
	else
	{
		            $result['status'] = 0;
					$result['responseMessage'] = "Ride Not Found";

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
