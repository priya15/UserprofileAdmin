<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('API_KEY','SID]O.YI0j2z=Ba)7s,!IW`~IanI{m');
class Driver_Webservice extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');
		$this->load->library('email');
		$this->load->model('Driver_Webservice_model');
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


	function index()
	{
		echo "hii";
		// $this->sendSMS('918770461607','Hello This is my Test code 0143','0143');
	}
	/**
	* this function will update device information in user table
	* @param $deviceId firebase id of device
	* @param $deviceType type of device 1-Android & 2-IOS
	* @param $userId id of user for which these information need to update
	*/
	public function updateDeviceInfo($deviceId,$deviceType,$fireBaseToken,$phone){
		$tableData['deviceid'] = $deviceId;
		$tableData['devicetype'] = $deviceType;
		$tableData['fireBaseToken'] = $fireBaseToken;
		$where['phone'] = $phone;
		if($this->Driver_Webservice_model->update('tbl_driver',$tableData,$where)){
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
		$password = trim($this->input->get_post('password', TRUE));
		$state = trim($this->input->get_post('state', TRUE));
		$vehicleTypeId = trim($this->input->get_post('vehicleTypeId', TRUE));
		$vehicleRegistrationNumber = trim($this->input->get_post('vehicleRegistrationNumber', TRUE));
		$deviceId = trim($this->input->get_post('deviceId', TRUE));
		$deviceType = trim($this->input->get_post('deviceType', TRUE));
		$fireBaseToken =trim($this->input->get_post('fireBaseToken', TRUE)); 
		$phone = trim($this->input->get_post('phoneNumber', TRUE));
        $languageType = trim($this->input->get_post('languageType', TRUE)); 
        //  1-> english 2->hindi 
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
// 		echo API_KEY;die;
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		
			$data = $this->Driver_Webservice_model->checkMobileExists($phone,$vehicleRegistrationNumber);
			// print_r($data);die;
			//var_dump($flag); die;
			if($data == ""){
				// $code = '1111';
				$code=rand(1000,9999);
				$phoneCoun = '91'.$phone;
				$msg = "".$code." is your Verification OTP. Do not share this code with anyone else.";
				$this->sendSMS($phoneCoun,$msg,$code);
				// $code = '1111';
				$tableData = array('name'=>$name,"vehicleCategoryId"=>$vehicleTypeId,'city'=>$city,'state'=>$state,'phone'=>$phone,"phoneOtp"=>$code,'vehicleNumber'=>$vehicleRegistrationNumber,"languageType"=>$languageType,'password'=>md5($password),'created_at'=>date('Y-m-d H:i:s'));
				$insert_id = $this->Driver_Webservice_model->insert('tbl_driver',$tableData);
				if($insert_id){
				    $this->updateDeviceInfo($deviceId,$deviceType,$fireBaseToken,$phone);
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
					$result['responseMessage'] = "Mobile number or Vehicle Already Exits";
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
		
			$data = $this->Driver_Webservice_model->checkMobileExists($phone);
                 if($data != ""){
				// $code = '1111';
				$code=rand(1000,9999);
				$phoneCoun = '91'.$phone;
				$msg = "".$code." is your Verification OTP. Do not share this code with anyone else.";
				$this->sendSMS($phoneCoun,$msg,$code);
               $this->db->update("tbl_driver",array("phoneOtp"=>$code),array("phone"=>$phone,"id"=>$data->id));
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
	
		$phone   = trim($this->input->get_post('phone', TRUE));
		$password  = md5(trim($this->input->get_post('password', TRUE)));
		
		$apiKey   = trim($this->input->get_post('apiKey', TRUE));
		$deviceId   = trim($this->input->get_post('deviceId', TRUE));
		$deviceType = trim($this->input->get_post('deviceType', TRUE));
		$firebasetoken =trim($this->input->get_post('fireBaseToken', TRUE)); 

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$checkLoginUser = $this->Driver_Webservice_model->checkLoginUser($phone,$password);
		 
		if($checkLoginUser){
			$this->updateDeviceInfo($deviceId,$deviceType,$firebasetoken,$phone);
		$profiledata = 	$this->db->select("*")->from("tbl_driver")->where("id",$checkLoginUser->id)->get()->result_array();
		//print_r($profiledata);die();
		$document_status=0;
	if(($profiledata[0]["RCStatus"]==1)&&($profiledata[0]["insuranceStatus"]==1)&&($profiledata[0]["vehicleImageStatus"]==1)){
		$document_status=1;
	}
		//print_r($profiledata);die();
		
			$result['status'] = 1;
			$result['responseMessage'] = "Login Successfully";
			$result['driverData'] =  $this->Driver_Webservice_model->getUserProfile($checkLoginUser->id);
			$result['document_status'] =$document_status;
			// if(!empty($result['userData']->userProfile))
			// 	$result['userData']->userProfile = base_url('assets/profileImage/'.$result['userData']->userProfile);
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
			$data = $this->Driver_Webservice_model->checkMobileExists($phone);
			if($data){
				
				// $code = '1111';
				$code=rand(1000,9999);
				$phoneCoun = '91'.$phone;
				$msg = "".$code." is your forgot password Verification OTP. Do not share this code with anyone else.";
				$this->sendSMS($phoneCoun,$msg,$code);
				$tableData['phoneOtp'] = $code;
				$condition['phone'] = $phone;

				if($this->Driver_Webservice_model->update('tbl_driver',$tableData,$condition)){
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
			$data = $this->Driver_Webservice_model->validateOTP($mobile,$OTP);
			
			if($data){

				$this->db->update('tbl_driver',array('phoneVerifyStatus'=>1),array('phone'=>$mobile));

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
		$driverId = trim($this->input->get_post('driverId', TRUE));
		$password = trim($this->input->get_post('newPassword', TRUE));
		$apiKey = trim($this->input->get_post('apiKey', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		if($driverId){
			$tableData['password'] = md5($password);
			$condition['id'] = $driverId;
			if($this->Driver_Webservice_model->update('tbl_driver',$tableData,$condition)){
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
	// 		$data = $this->Driver_Webservice_model->checkPassword($oldPassword,$userId);
	// 		//print_r($data);
	// 		if($data){
	// 			$tableData['password'] = md5($password);
	// 			$condition['user_id'] = $userId;
	// 			if($this->Driver_Webservice_model->update('registration',$tableData,$condition)){
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
		$driverId = trim($this->input->get_post('driverId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$updateData = array('deviceid'=>'','devicetype'=>'');
		$where = array('id'=>$driverId);
		$update = $this->Driver_Webservice_model->update('tbl_driver',$updateData,$where);
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
		$driverId = trim($this->input->get_post('driverId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		
		// $where = array('id'=>$driverId);
		$data = $this->Driver_Webservice_model->getUserProfile($driverId);
		if($data)
		{
			// if(!empty($data['userProfile'])){
			// 		$data['userProfile'] = base_url('assets/profileImage/'.$data['userProfile']);
			// 		}
			$result['status'] = 1;
			$result['responseMessage'] = "All Data";
			$result['AllData'] = $data;
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
	 

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		
		// $where = array('id'=>$driverId);
		$data = $this->Driver_Webservice_model->getData('tbl_vehicle_category');
		if($data)
		{
			// if(!empty($data['userProfile'])){
			// 		$data['userProfile'] = base_url('assets/profileImage/'.$data['userProfile']);
			//}
			$result['status'] = 1;
			$result['responseMessage'] = "All Data";
			$result['AllData'] = $data;
		}
		else
		{
			$result['status'] = 0;
			$result['responseMessage'] = "No Data Found";
		}
		echo json_encode($result);

	}


function uploadDocuments()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$documentFlag = trim($this->input->get_post('documentFlag', TRUE));// 1-> RC 2->Insuranse 3->Vehicle Photos
		$driverId = trim($this->input->get_post('driverId', TRUE));
		 

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		if($documentFlag ==  ""){
			echo json_encode(array('status' => 0, 'responseMessage' => 'Image Type can not be empty.'));die;
		}

	
					$tableData = array();
					if (($_FILES['image']['name']) != "")
					{    
					$filename = explode('.', $_FILES['image']['name']);
						$filename = 'document_' .time().rand(100,999).'.'. $filename[count($filename)-1];
					// print_r($_FILES);die;
					$_FILES['image']['name'] = $filename;
						$config['upload_path'] ='assets/driverDocument/tempImages/';
						$config['allowed_types'] = 'jpg|jpeg|png|gif';
						$this->load->library('upload',$config);
						$this->upload->initialize($config);
						  if($this->upload->do_upload('image')){
							  
							$uploadData = $this->upload->data();
							$data = $this->upload->data();
							$tableData = array("imageUrl"=>$data['raw_name'].$data['file_ext'],"driverId"=>$driverId,"imageType"=>$documentFlag,"createdAt"=>date("Y-m-d H:i:s"));
							 
						  }
						  else{
							$result['status'] = 0;
							$result['responseMessage'] = $this->upload->display_errors();
							echo json_encode($result);die;
						  }
						  if($tableData){

						  $insert = $this->Driver_Webservice_model->insert('tbl_temp_images',$tableData);
						 
						  }
						  else{
							$result['status'] = 0;
							$result['responseMessage'] = "Images Not uploaded";
							echo json_encode($result);die;
						  }
					}
		if($insert)
		{
			$result['status'] = 1;
			$result['responseMessage'] = "Image Successfully Uploaded.";
			$result['imageId'] = $insert;
			$result['documentFlag'] = $documentFlag;
		}
		else{
			$result['status'] = 0;
			$result['responseMessage'] = "Image Not uploaded";
		}

		echo json_encode($result);
		 
	}

	function deleteDocument()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		 
		$imageId = trim($this->input->get_post('imageId', TRUE));
		 

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$delete = $this->db->delete("tbl_temp_images",array("imageId"=>$imageId));
		if($delete)
		{
			$result['status'] = 1;
			$result['responseMessage'] = "Image Deleted";
		}
		else{
			$result['status'] = 0;
			$result['responseMessage'] = "Image Not deleted";
		}

		echo json_encode($result);
	}

	function updateDocuments()
	{
	
			$apiKey = trim($this->input->get_post('apiKey', TRUE));
			$driverId = trim($this->input->get_post('driverId', TRUE));
			$uploadedImagesIds = trim($this->input->get_post('uploadedImagesIds', TRUE));


			if($apiKey != API_KEY){
				echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
			}

			$Ids = 	explode(',',$uploadedImagesIds);
		
			if($Ids)
			{
				foreach ($Ids as $key => $value) {
					$getId = $this->db->get_where('tbl_temp_images',array("imageId"=>$value))->row_array();
					$arr = array(
								"driverId"=>$driverId,
								"imageUrl"=>$getId['imageUrl'],
								"imageType"=>$getId['imageType'],
								"createdAt"=>date('Y-m-d H:i:s'),
					);

						$base = "assets/driverDocument/";
						$imagePath = '././assets/driverDocument/tempImages/'.$getId['imageUrl'].'';
						$newPath = '././'.$base;
					 	$newName  = $newPath.$getId['imageUrl'];
						$copied = copy($imagePath , $newName);

						if ((!$copied)) 
						{
							$result['status'] = 0;
							$result['responseMessage'] = "Somthing went wrong with images processing";
							echo json_encode($result);die;
						}
						else
						{ 
							$insert = $this->db->insert('driverTranspostImages',$arr);

							$this->db->delete('tbl_temp_images',array('imageId'=>$value));
							if($getId['imageType'] == '1')
							{
								$updateData = array('RCStatus'=>1);
								
							}
							else if($getId['imageType'] == '2')
							{
								$updateData = array("insuranceStatus"=>1);
							}
							else{
								$updateData = array("vehicleImageStatus"=>1);
							}
                            $this->db->update('tbl_driver',$updateData,array("id"=>$driverId));
				
							$unlink = "././assets/driverDocument/tempImages/".$getId['imageUrl'];
							unlink($unlink);
						}

				}

					$result['status'] = 1;
					$result['responseMessage'] = "Update Document Successfully";
					echo json_encode($result);die;


			}
			else{
					$result['status'] = 0;
					$result['responseMessage'] = "Somthing went wrong, Please try again";
					echo json_encode($result);die;
			}
	}


    
	function uploadBankDetail()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$accountHolderName = trim($this->input->get_post('accountHolderName', TRUE)); 
		$accountNumber = trim($this->input->get_post('accountNumber', TRUE)); 
		$IFSCNumber = trim($this->input->get_post('IFSCNumber', TRUE)); 
		$BankName = trim($this->input->get_post('BankName', TRUE)); 
		$branchName = trim($this->input->get_post('branchName', TRUE)); 
		 
		$driverId = trim($this->input->get_post('driverId', TRUE));
		$uploadedImagesIds = trim($this->input->get_post('uploadedImagesIds', TRUE));
		 

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

			// All Documents Upload at Once

			$Ids = 	explode(',',$uploadedImagesIds);
		
			if($Ids)
			{
				foreach ($Ids as $key => $value) {
					$getId = $this->db->get_where('tbl_temp_images',array("imageId"=>$value))->row_array();
					$arr = array(
								"driverId"=>$driverId,
								"imageUrl"=>$getId['imageUrl'],
								"imageType"=>$getId['imageType'],
								"createdAt"=>date('Y-m-d H:i:s'),
					);

						$base = "assets/driverDocument/";
						$imagePath = '././assets/driverDocument/tempImages/'.$getId['imageUrl'].'';
						$newPath = '././'.$base;
						$ext = '.jpg';
						$newName  = $newPath.$getId['imageUrl'];

						$copied = copy($imagePath , $newName);

						if ((!$copied)) 
						{
							$result['status'] = 0;
							$result['responseMessage'] = "Somthing went wrong with images processing";
							echo json_encode($result);die;
						}
						else
						{ 
							$insert = $this->db->insert('driverTranspostImages',$arr);

							$this->db->delete('tbl_temp_images',array('imageId'=>$value));
							if($getId['imageType'] == '1')
							{
								$updateData = array('RCStatus'=>1);
								
								
							}
							else if($getId['imageType'] == '2')
							{
								$updateData = array("insuranceStatus"=>1);
							}
							else{
								$updateData = array("vehicleImageStatus"=>1);
							}
                            $this->db->update('tbl_driver',$updateData,array("id"=>$driverId));
				
						$unlink = "././assets/driverDocument/tempImages/".$getId['imageUrl'];
				
							unlink($unlink);
						}

				}

				
			}
			else{
							$result['status'] = 0;
							$result['responseMessage'] = "Somthing went wrong, Please try again";
							echo json_encode($result);die;
			}
			//  End 

			// Bank Details Uploaded

			$getOldData = $this->db->get_where('tbl_bank_info',array('driverId'=>$driverId))->row_array();

			if(!empty($getOldData))
			{
				$this->db->delete('tbl_bank_info',array('driverId'=>$driverId));
			}

			$arr = array(
						"accountHolderName"=>$accountHolderName,
						"accountNumber"=>$accountNumber,
						"IFSCNumber"=>$IFSCNumber,
						"BankName"=>$BankName,
						"branchName"=>$branchName,
						"driverId"=>$driverId,
						"createdAt"=>date('Y-m-d H:i:s')
					);
					$insert = $this->Driver_Webservice_model->insert('tbl_bank_info',$arr);
					if($insert)
						{
							$result['status'] = 1;
							$result['responseMessage'] = "Bank Detail Uploaded";
						}
						else{
							$result['status'] = 0;
							$result['responseMessage'] = "Somthing went worng, please try again later.";
						}

		echo json_encode($result);
	}

    
    function getAllState() 
    {
        	$apiKey = trim($this->input->get_post('apiKey', TRUE));
        	
        	
        	if($apiKey != API_KEY){
        			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
        		}
        		
        		
        		$getState = $this->Driver_Webservice_model->getData('tbl_all_states');
        			if($getState)
						{
							$result['status'] = 1;
							$result['responseMessage'] = "All State";
							$result['AllData'] = $getState;
						}
						else{
							$result['status'] = 0;
							$result['responseMessage'] = "No State Found";
						}

		echo json_encode($result);
    
    }


		function getCities()
    	{
        	$apiKey = trim($this->input->get_post('apiKey', TRUE));
        	$state_code = trim($this->input->get_post('state_code', TRUE));
        	
        	
        	if($apiKey != API_KEY){
        			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
        		}
        		
        		
        		$getCities = $this->Driver_Webservice_model->getData('tbl_all_cities',array("state_code"=>$state_code));
        			if($getCities)
						{
							$result['status'] = 1;
							$result['responseMessage'] = "City By State";
							$result['AllData'] = $getCities;
						}
						else{
							$result['status'] = 0;
							$result['responseMessage'] = "No Cities Found";
						}

		echo json_encode($result);
    
	}
	
	function driverDashboard()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$driverId = trim($this->input->get_post('driverId', TRUE));

		if($apiKey != API_KEY){
				echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$where = array("id"=>$driverId);
		$getDriverDetails = $this->Driver_Webservice_model->getDataById('tbl_driver',$where);
		if($getDriverDetails)
		{
			
			
			if($getDriverDetails['RCStatus'] != 0 && $getDriverDetails['insuranceStatus'] != 0 && $getDriverDetails['vehicleImageStatus'] != 0)
			{
				$result['status'] = 1;
				$result['responseMessage'] = "Documents Already Uploaded";
				$result['uploadDocument'] = 1;
				$result['walletBalance'] = $getDriverDetails['walletBalance'];
			}
			else{
				$result['status'] = 1;
				$result['responseMessage'] = "Documents Not Uploaded";
				$result['uploadDocument'] = 0;
				$result['walletBalance'] = $getDriverDetails['walletBalance'];
			}
		}
		else{
				$result['status'] = 0;
				$result['responseMessage'] = "Driver Not Exits";
				 
		}
		echo json_encode($result);
			
	}

	function showDocument()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
		$driverId = trim($this->input->get_post('driverId', TRUE));

		if($apiKey != API_KEY){
				echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$where = array("id"=>$driverId);
		$column = "id as driverId,RCStatus,insuranceStatus,vehicleImageStatus";
		$getDriverDetails = $this->Driver_Webservice_model->getDataById('tbl_driver',$where,$column);
		if($getDriverDetails)
		{
			
			
				$result['status'] = 1;
				$result['responseMessage'] = "Driver Documents Details";
				$result['allData'] = $getDriverDetails;
				
		
				
			
		}
		else{
				$result['status'] = 0;
				$result['responseMessage'] = "No data Found";
				 
		}
		echo json_encode($result);
			
	}




	 

	// function updateProfile()
	// {
	// 	$apiKey = trim($this->input->get_post('apiKey', TRUE));
	// 	$userId = trim($this->input->get_post('userId', TRUE));
	// 	$name = trim($this->input->get_post('name', TRUE));
	// 	$phone = trim($this->input->get_post('phone', TRUE));

	// 	if($apiKey != API_KEY){
	// 		echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
	// 	}
	// 	$arr = array();
	// 	$arr = array('username'=>$name,"phone"=>$phone);
	// 	if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
    //         {
                    
    //             $filename = explode('.', $_FILES['image']['name']);
    //             $filename = 'profile_' .time().rand(100,999).'.'. $filename[count($filename)-1];
    //             $_FILES['image']['name'] = $filename;
                

    //             $config['upload_path'] = 'assets/profileImage/';
    //             $config['allowed_types'] = 'jpg|jpeg|png';
    //             $this->load->library('upload',$config);
    //             $this->upload->initialize($config);
    //               if($this->upload->do_upload('image')){
    //                 $uploadData = $this->upload->data();
    //                 $data1 = $this->upload->data();
    //                 $arr['userProfile']  = $filename; 
    //               }
    //               else
    //               {
    //                 	$result['status'] = 0;
	// 					$result['responseMessage'] = "Somthing Went Wrong";die;
						
    //               }
    //         }

		
	// 	$where = array('user_id'=>$userId);
	// 	$data = $this->Driver_Webservice_model->update('registration',$arr,$where);
	// 	if($data)
	// 	{
			
	// 		$result['status'] = 1;
	// 		$result['responseMessage'] = "User Profile Update Successfully ";
			
	// 	}
	// 	else
	// 	{
	// 		$result['status'] = 0;
	// 		$result['responseMessage'] = "Profile Not Updated";
	// 	}
	// 	echo json_encode($result);

	// }





	function getNewRideDetail()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		$rideData =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId));


		if($rideData)
		{
			$driverinfo = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
			//print_r($rideData);die();

			$distance =  $this->Driver_Webservice_model->distance($rideData["pickup_lat"],$rideData["pickup_lng"],$rideData["drop_lat"],$rideData["drop_lng"]);
				if($distance == 0)
				{
					$rideData["Customer_distance"]  = 1;
				}
				else
				{
					$rideData["Customer_distance"]  = $distance;

				}


			
					if($rideData['vehicleId'] != 0){
						$vehicleData = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$rideData['vehicleId'],"publish_status"=>1),"id as vehicleId, vehicle_name,image,vehiDesc,publish_status");

						if($vehicleData != '' ):
							$vehicleData['image'] = base_url()."assets/vehicleImages/".$vehicleData['image'];
							$rideData['vehicleData'] = $vehicleData;

						else:
							$rideData['vehicleData'] = "";	
						endif;

						if($rideData['driverId'] != 0):

							$rideData['driverData'] = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData['driverId'],"id as driverId,*"));

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




	public function MyRidesHistory(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        //$rideId = trim($this->input->get_post('rideId', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		$where["driverId"]=$driverId;
		//$where["rideStatus"]!=0;
		$rideData =  $this->Driver_Webservice_model->getDataByjoinId('tbl_booking',$driverId);


		if($rideData)
		{
			$driverinfo = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
			//print_r($rideData);die();

			


			

					$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $rideData;
				
		
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "No ride details found";
		}

		echo json_encode($result);

	}

	function MyNewRidedDetail(){
	$apiKey = trim($this->input->get_post('apiKey', TRUE));
    $driverId = trim($this->input->get_post('driverId', TRUE));
  //  $deviceId = trim($this->input->get_post('deviceId', TRUE));
	//$userId = trim($this->input->get_post('userId', TRUE));
	

	if($apiKey != API_KEY){
		echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
	}


	// $this->checkDeviceToken($userId,$deviceId);
	$driverData =  $this->Driver_Webservice_model->getDataById('tbl_driver',array("id"=>$driverId));
	if(!empty($driverData)){

	$vechicleData =  $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array("id"=>$driverData["vehicleCategoryId"]));
	//print_r($vechicleData);die();
//	print_r($driverData);die();
	$lat = $driverData["lat"];
	$lng = $driverData["lng"];
	$distance="5";
	if(($lat!=NULL)&&($lng!=NULL)){
		$vechicleid = $driverData["vehicleCategoryId"];
		$searchData = $this->Driver_Webservice_model->searchDriver($lat,$lng,$distance,$vechicleid,$driverId);

		$newarray= array();
		if(!empty($searchData)){
          foreach($searchData as $key=>$cat_fam) {
	          //$this->Driver_Webservice_model->VechicleName($searchData[$key]["vechicle_id"]);
				 $searchData[$key]["vechicle_name"]=$vechicleData["vehicle_name"];
		}
		$searchmaindata = $searchData[0];
		$distance =  $this->Driver_Webservice_model->distance($lat,$lng,$searchmaindata["pickup_lat"],$searchmaindata["pickup_lng"]);
				if($distance == 0)
				{
					$searchmaindata["Customer_distance"]  = 1;
				}
				else
				{
					$searchmaindata["Customer_distance"]  = $distance;

				}
			     $booking_id = $searchmaindata["id"];

				$notcheck = $this->Driver_Webservice_model->getDataById('tbl_notification',array("booking_id"=>$booking_id,"title"=>"NewBooking"));
				//print_r($notcheck);die();
				if(($notcheck == "")){
                 $title = "NewBooking";
                 $msg="";
                 $date = date('Y-m-d H:i:s');
                 $insert = array("booking_id"=>$booking_id,"title"=>$title,"msg"=>$msg,"driver_id"=>$driverId,"created_at"=>$date);
                $this->Driver_Webservice_model->insertNotification($insert);
                  }
                  $userid=$searchmaindata["userId"];
                $fireBaseToken = $this->Driver_Webservice_model->findFirebaseid($userid);
            	//print_r($fireBaseToken);die();
            	if(!empty($firebasetoken)){
            		$firetokend = $fireBaseToken["fireBaseToken"];
            	}
            	else
            	{
            		$firetokend="";
            	}
            	$msg = array(
								"title"=>"New Booking",
								"body" => "You have a new ride Request accepted by User.",
								"userId" => $userid,
								"rideId" => $booking_id,
								"driverId"=>$driverId
						);
						$tokend = $firetokend;
						$this->send($tokend,$msg);

					$result['status'] = 1;
					$result['responseMessage'] = "All Ride Data";
					$result['AllData'] = $searchmaindata;


	   }
	   else
	   {

					$result['status'] = 0;
					$result['responseMessage'] = "No New Ride found";


	   }
	}
	else
	{ 
		             $result['status'] = 0;
					$result['responseMessage'] = "Driver lat lng notfound";

	}
}
else
{
	            $result['status'] = 0;
				$result['responseMessage'] = "No driver  found";

}
	   		echo json_encode($result);


}





   		function getRideDetail()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
      //  $deviceId = trim($this->input->get_post('deviceId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		$rideData =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId));

		if($rideData)
		{
			
					if($rideData['vehicleId'] != 0){
						$vehicleData = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$rideData['vehicleId'],"publish_status"=>1),"id as vehicleId, vehicle_name,image,vehiDesc,publish_status");

						if($vehicleData != '' ):
							$vehicleData['image'] = base_url()."assets/vehicleImages/".$vehicleData['image'];
							$rideData['vehicleData'] = $vehicleData;

						else:
							$rideData['vehicleData'] = "";	
						endif;

						if($rideData['driverId'] != 0):

							$rideData['driverData'] = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData['driverId'],"id as driverId,*"));

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

		public function acceptRideByDriver(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $driverId = trim($this->input->get_post('driverId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
		if(!empty($driverdata)){
            $rideData =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId));
           // print_r($rideData);die();
            $userid = $rideData["userId"];
            if($rideData){
            	$token = $six_digit_random_number = mt_rand(100000, 999999); 
            	$data  = array("driverId"=>$driverId,"rideStatus"=>1,"token"=>$token);
            	$rideDatas =  $this->Driver_Webservice_model->update('tbl_booking',$data,array("id"=>$rideId));
            	$rideDatanew =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId));

            	//$userid = $rideData["userId"];
            	$fireBaseToken = $this->Driver_Webservice_model->findFirebaseid($userid);
            	//print_r($fireBaseToken);die();
            	if(!empty($firebasetoken)){
            		$firetokend = $fireBaseToken["fireBaseToken"];
            	}
            	else
            	{
            		$firetokend="";
            	}
            	$msg = array(
								"title"=>"Drive Accepted",
								"body" => "You have a new ride Request accepted by Driver.",
								"userId" => $userid,
								"rideId" => $rideId,
								"driverId"=>$driverId
						);
						$tokend = $firetokend;
						$this->send($tokend,$msg);
		
            	echo json_encode(array('status' => 1, 'responseMessage' => 'Ride accept by driver','token'=>$token,'data'=>$rideDatanew));die();

            }

		}
		else
		{
         echo json_encode(array('status' => 0, 'responseMessage' => 'Driver not found'));die;
		}

	}

	public function StartOTPRideVerfication(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $token = trim($this->input->get_post('token', TRUE));
        $driverId = trim($this->input->get_post('driverId', TRUE));

		//$userId = trim($this->input->get_post('userId', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
		if (!empty($driverdata)) {
			# code...
		
		$rideData =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId,"token"=>$token,"driverId"=>$driverId));
		//print_r($rideData);die();
		if(!empty($rideData)){
			$time = date("Y-m-d h:i:s a");
			$data = array("rideStatus"=>2,"startRideTime"=>$time);
			$rideDatas =  $this->Driver_Webservice_model->update('tbl_booking',$data,array("id"=>$rideId));
			$rideDatanew =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId,"token"=>$token));
			echo json_encode(array('status' => 1, 'responseMessage' => 'Otp verfied','data'=>$rideDatanew));die;
		}
		else
		{
			echo json_encode(array('status' => 0, 'responseMessage' => 'Otp not correct Please try again'));die;
		}
	}
	else
	{
		echo json_encode(array('status' => 0, 'responseMessage' => 'Driver not found'));die;

	}
	}


	    function cancelRideByDriver()
	{
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        //$rideStatus = trim($this->input->get_post('rideStatus', TRUE));
        $driverId = trim($this->input->get_post('driverId', TRUE));
      //  $deviceId = trim($this->input->get_post('deviceId', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        $cancelReasone = trim($this->input->get_post('cancelReasone', TRUE));
        
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		 // $this->checkDeviceToken($userId,$deviceId);
    		$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
		if (!empty($driverdata)) {
		 $checkRideBookingStatus = $this->Driver_Webservice_model->checkRideBookingStatus($rideId,$driverId);
		 $userid = $checkRideBookingStatus["userId"];
		 if($checkRideBookingStatus)
		 {
		 	$rideStatus=3;
		 	$updateStatus = $this->Driver_Webservice_model->updateRideStatus($rideStatus,$rideId,$cancelReasone);
		 	if($updateStatus)
		 	{
		 		$fireBaseToken = $this->Driver_Webservice_model->findFirebaseid($userid);
            	if(!empty($firebasetoken)){
            		$firetokend = $fireBaseToken["fireBaseToken"];
            	}
            	else
            	{
            		$firetokend="";
            	}
            	$msg = array(
								"title"=>"Drive Cancelled",
								"body" => "You have a new ride Request Rejected by Driver.",
								"userId" => $userid,
								"rideId" => $rideId,
								"driverId"=>$driverId
						);
						$tokend = $firetokend;
						$this->send($tokend,$msg);

					$booking_id = $rideId;

                    $driverId = $driverId;
				
                 $title = "System";
                 $msg="Booking #".$rideId." hasebeen cancelled";
                 $date = date('Y-m-d H:i:s');
                 $insert = array("booking_id"=>$booking_id,"title"=>$title,"msg"=>$msg,"driver_id"=>$driverId,"created_at"=>$date);
                $this->Driver_Webservice_model->insertNotification($insert);
                  


		         
		 			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = array("rideId"=>$rideId,"cancelReasone"=>$cancelReasone,"rideStatus"=>$rideStatus,"driverId"=>$driverId);
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
		}
		else
		 {
		 		$result['status'] = 0;
				$result['responseMessage'] = "Driver not found";
		 }

		echo json_encode($result);
	}


    	public function StopRide(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
        
        $driverId = trim($this->input->get_post('driverId', TRUE));

		$drop_lat = trim($this->input->get_post('drop_lat', TRUE));
		$drop_lng = trim($this->input->get_post('drop_lng', TRUE));
		$drop_address = trim($this->input->get_post('drop_address', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
		$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
		if (!empty($driverdata)) {
			# code...
		
		$rideData =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId,"driverId"=>$driverId));

		//print_r($rideData);die();
		if(!empty($rideData)){
          		  $time = date("Y-m-d h:i:s a");
		  		$distance =  $this->Driver_Webservice_model->distance($rideData["pickup_lat"],$rideData["pickup_lng"] ,$drop_lat,$drop_lng);


       $vehicleId = $rideData["vehicleId"];

		if($distance == 0)
		{
			$distance  = 1;
		}

		$getPerKmPrice = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array("id"=>$vehicleId));
		//echo $getPerKmPrice['pricePerKM'];die();
		if($getPerKmPrice['pricePerKM'] != 0)
		{
			$totalFair = round($distance*$getPerKmPrice['pricePerKM']);
			if($totalFair != 0)
			{
				
				
			}
			else
			{
				$result['status'] = 0;
				$result['responseMessage'] = "Please give correct lat lng.";die();

			}
		}
		$newarray = array("booking_id"=>$rideData["id"],"pickup_lat"=>$rideData["pickup_lat"],"pickup_lng"=>$rideData["pickup_lng"],"drop_lat"=>$rideData["drop_lat"],"drop_lng"=>$rideData["drop_lng"],"total_cost"=>$rideData["totalCharge"],"total_distance"=>$rideData["totalDistance"],"drop_address"=>$rideData["drop_address"]);
          $this->db->insert("tbl_booking_old",$newarray);

         $userid = $rideData["userId"];

			$data = array("rideStatus"=>4,"endRideTime"=>$time,"drop_lat"=>$drop_lat,"drop_lng"=>$drop_lng,"drop_address"=>$drop_address,"totalCharge"=>$totalFair,"totalDistance"=>$distance);
			$rideDatas =  $this->Driver_Webservice_model->update('tbl_booking',$data,array("id"=>$rideId));
			$rideDatanew =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId));
			$fireBaseToken = $this->Driver_Webservice_model->findFirebaseid($userid);
            	if(!empty($firebasetoken)){
            		$firetokend = $fireBaseToken["fireBaseToken"];
            	}
            	else
            	{
            		$firetokend="";
            	}
            	$msg = array(
								"title"=>"Ride Stop",
								"body" => "Your Ride stop now.",
								"userId" => $userid,
								"rideId" => $rideId,
								"driverId"=>$driverId
						);
						$tokend = $firetokend;
						$this->send($tokend,$msg);
		
			echo json_encode(array('status' => 1, 'responseMessage' => 'Your Ride is Stop','data'=>$rideDatanew));die;
		}
		else
		{
			echo json_encode(array('status' => 0, 'responseMessage' => 'Ride not found'));die;
		}
	}
	else
	{
		echo json_encode(array('status' => 0, 'responseMessage' => 'Driver not found'));die;

	}
	}


	function RideCost(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));
		

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		$rideData =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId,"driverId"=>$driverId));

		if($rideData)
		{
			if($rideData['driverId'] != 0){

				$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData['driverId'],"id as driverId,*"));
				$rideDatas =array();
				if(!empty($driverdata)){
					$rideDatas["drivername"]=$driverdata["name"];
					$rideDatas["driver_pic"]=$driverdata["profilepic"];
				}
				else
				{
				    $rideDatas["drivername"]="";
					$rideDatas["driver_pic"]="";	
				}
				$rideDatas["toal_cost"]=$rideData["totalCharge"];
				$rideDatas["totalDistance"]=$rideData["totalDistance"];
				$rideDatas["startRideTime"]=$rideData["startRideTime"];
				$rideDatas["endRideTime"]=$rideData["endRideTime"];
				$rideDatas["pickup_address"]=$rideData["pickup_address"];
				$rideDatas["drop_address"]=$rideData["drop_address"];
			}



					$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $rideDatas;
				
		
		}
		else{

				$result['status'] = 0;
				$result['responseMessage'] = "Can't find the ride Details, please check again.";
		}

		echo json_encode($result);

	}



	public function Notification(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));

     if(!empty($driverdata)){
		$notdata = $this->Driver_Webservice_model->FindNotification($driverId);
		if(!empty($notdata)){
           			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $notdata;

		}
		else
		{
                     $result['status'] = 0;
					$result['responseMessage'] = "No Data Found";
 
		}
	}
	else
	{
                     $result['status'] = 0;
					$result['responseMessage'] = "No Driver Found";
	}
	   echo json_encode($result);
 	}


 	public function dashboard(){
      	$apiKey = trim($this->input->get_post('apiKey', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
      // $start_date = trim($this->input->get_post('start_date', TRUE));
      // $end_date = trim($this->input->get_post('end_date', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
	    $start_date_new = date('Y-m-d');
	    $end_date_new   = date('Y-m-d', strtotime(' -30 days'));
	  //  echo $start_date_new.$end_date_new;die();


     if(!empty($driverdata)){
		$dashdata = $this->Driver_Webservice_model->FindDashboardData($driverId,$start_date_new,$end_date_new);
		/*find if it on ride*/
		$onride  =  $this->Driver_Webservice_model->FindOnRide($driverId);
		$dashboard =array();
		if(!empty($dashdata)){
            $totalearning =0;
            $totalrides=0;
            $j=1;
			for($i=0;$i<count($dashdata);$i++){
				$totalrides+=$j;
				$totalearning+=$dashdata[$i]["totalCharge"];
			}
			$dashboard["totalearning"] = $totalearning;
			$dashboard["totalride"]    = $totalrides;
			$dashboard["cashcollected"] = 0;
			if(!empty($onride)){
				$dashboard["onride"]=1;
			}
			else{
				$dashboard["onride"]=0;
			}
           			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $dashboard;

		}
		else
		{
			if(!empty($onride)){
				$dashboard["onride"]=1;
			}
			else{
				$dashboard["onride"]=0;
			}
			$dashboard["totalearning"] = 0;
			$dashboard["totalride"]    = 0;
			$dashboard["cashcollected"] = 0;
                    $result['status'] = 1;
					$result['responseMessage'] = " Data Found";
			$result['AllData'] = $dashboard;

 
		}
	}
	else
	{
                     $result['status'] = 0;
					$result['responseMessage'] = "No Driver Found";
	}
	   echo json_encode($result);

 	}


 	public function viewEarning($driverId){
        $apiKey = trim($this->input->get_post('apiKey', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
      // $start_date = trim($this->input->get_post('start_date', TRUE));
      // $end_date = trim($this->input->get_post('end_date', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
	  //  echo $start_date_new.$end_date_new;die();


     if(!empty($driverdata)){
		$dashdata = $this->Driver_Webservice_model->FindDashboardDataAll($driverId);
		$dashboard =array();
		if(!empty($dashdata)){
			foreach($dashdata as $key=>$cat_fam) {
                    $dashdata[$key]['cashcollected']  = '0';
             }

           			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['AllData'] = $dashdata;

		}
		else
		{
			        $result['status'] = 0;
					$result['responseMessage'] = " No Data Found";
			    //   $result['AllData'] = $dashboard;

 
		}
	}
	else
	{
                     $result['status'] = 0;
					$result['responseMessage'] = "No Driver Found";
	}
	   echo json_encode($result);


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
                'rideId'=> $msg['rideId'],
                'driverId'=> $msg['driverId'],
               
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


	


}


?>
