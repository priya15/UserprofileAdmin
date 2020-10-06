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
	if(($profiledata[0]["RCStatus"]==2)&&($profiledata[0]["insuranceStatus"]==2)&&($profiledata[0]["vehicleImageStatus"]==2)){
		$document_status=1;
	}
		//print_r($profiledata);die();
		
			$result['status'] = 1;
			$result['responseMessage'] = "Login Successfully";
			$result['driverData'] =  $this->Driver_Webservice_model->getUserProfile($checkLoginUser->id);
			//print_r($result["driverData"]);die();
			if(($result['driverData']["profilepic"]!=NULL)){
				$result["profilepic"]=base_url('assets/profileImage/').$result['driverData']["profilepic"];
			}
			else
			{
				$result["profilepic"]="";
			}
			if(($result['driverData']["email"]!=NULL)){
				$result["email"]=$result['driverData']["email"];
			}
			if(($result['driverData']["email"]=="")){
				$result["email"]="";
			}
			//print_r($result['driverData']);die();
			$result['document_status'] =$document_status;
			if(!empty($result['driverData']->profilepic)){
			 	$result['driverData']->profilepic = base_url('assets/profileImage/'.$result['driverData']->profilepic);
			}
			//$result["profile_pic"]=$result['driverData']->profilepic;

 		}else{
			$result['status'] = 0;
			$result['responseMessage'] = "Phone Or Password Didn't Match.";
		}

		echo json_encode($result);
	}



	    public function documentStatus(){
		$result = array();
	
		
		$apiKey   = trim($this->input->get_post('apiKey', TRUE));
		$driverId   = trim($this->input->get_post('driverId', TRUE));

		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		$profiledata = 	$this->db->select("*")->from("tbl_driver")->where("id",$driverId)->get()->result_array();
		if(!empty($profiledata)){
		$document_status=0;
	if(($profiledata[0]["RCStatus"]==2)&&($profiledata[0]["insuranceStatus"]==2)&&($profiledata[0]["vehicleImageStatus"]==2)){
		$document_status=1;
	}
		//print_r($profiledata);die();
		
			$result['status'] = 1;
			$result['responseMessage'] = "Document data";
			$result['document_status'] =$document_status;
			// if(!empty($result['userData']->userProfile))
			// 	$result['userData']->userProfile = base_url('assets/profileImage/'.$result['userData']->userProfile);
	}
	else
	{
          $result['status'] = 0;
			$result['responseMessage'] = "No driver found";
			//$result['document_status'] =$document_status;
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
				$result['responseMessage'] = "OTP Matched";
				$result['userId'] = $data[0]->id;
			 
			}else{
				$result['status'] = 0;
				$result['responseMessage'] = "OTP Mismatched!!";
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
			 if(!empty($data['profilepic'])){
					$data['profilepic'] = base_url('assets/profileImage/'.$data['profilepic']);
			 }
			 else
			 {
			 	$data['profilepic'] = "";
			 }
			 if(!empty($data['email'])){
					$data['email'] = $data["email"];
			 }
			 else
			 {
			 	$data['email'] = "";
			 }
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

		
		$wherev = array('publish_status'=>1);
		$data = $this->Driver_Webservice_model->getData('tbl_vehicle_category',$wherev);
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




	 

	function updateProfile()
   {
	 	$apiKey = trim($this->input->get_post('apiKey', TRUE));
	    $driverId = trim($this->input->get_post('driverId', TRUE));
         $name = trim($this->input->get_post('name', TRUE));
	     $email = trim($this->input->get_post('email', TRUE));

        if($apiKey != API_KEY){
		echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
	 	}
	 	$where = array("email"=>$email,"id!="=>$driverId);
	 	$getDriverDetails = $this->Driver_Webservice_model->getDataById('tbl_driver',$where);
	 	//print_r($getDriverDetails);die();
      if(empty($getDriverDetails)){
 	   $arr = array();
 	   $arr['userProfile'] ="";
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
                     $arr['userProfile']  = $filename; 
                   }
                   else
                   {
                     	$result['status'] = 0;
	 					$result['responseMessage'] = "Somthing Went Wrong";die;
						
                   }
             }
            $getDriverDetails1 = $this->Driver_Webservice_model->getDataById('tbl_driver',array("id"=>$driverId));
//print_r($getDriverDetails1);die();

             if($name == ""){
             	$name = $getDriverDetails1["name"];
             }
             if($email == ""){
             	$email = $getDriverDetails1["email"];
             }
             $imaged="";
            // echo $name;die();
        if($arr['userProfile']!=""){
        	$imagmain = $arr['userProfile'];
	      $arr = array('name'=>$name,"email"=>$email,"profilepic"=>$arr["userProfile"]);
	              $imaged = base_url().'assets/profileImage/'.$imagmain;

        }
        else
        {
           $arr = array('name'=>$name,"email"=>$email);
                   $imaged = "";


        }
        $updatedata = array("name"=>$name,"email"=>$email,"profilepic"=>$imaged);
	 	$where = array('id'=>$driverId);
	 	$data = $this->Driver_Webservice_model->update('tbl_driver',$arr,$where);
	 	if($data)
	 	{
			
	 		$result['status'] = 1;
	 		$result['responseMessage'] = "Driver Profile Update Successfully ";
	 		$result["Alldata"]=$updatedata;
			
	 	}
	 	else
	 	{
	 		$result['status'] = 0;
	 		$result['responseMessage'] = "Profile Not Updated";
	 	}
	 }
	 else
	 {
	 	    $result['status'] = 0;
	 		$result['responseMessage'] = "EmailId already exist";
	 }
	 	echo json_encode($result);

	 }





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
//print_r($rideData);die();

		if($rideData)
		{
			$driverinfo = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
          foreach($rideData as $key=>$cat_fam) {
          	$vechicleinfo = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$rideData[$key]["vehicleId"]));
			      if($rideData[$key]["profilepic"]!=""){
					$rideData[$key]["user_pic"]=base_url('assets/profileImage/').$rideData[$key]["profilepic"];
				    }
				    if($rideData[$key]["socialImageUrl"]!=""){
					$rideData[$key]["user_pic"]=$rideData[$key]["socialImageUrl"];
				    }
				    if($rideData[$key]["profilepic"]==""){
					$$rideData[$key]["user_pic"]="";
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
				    
   }

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


	public function MyRidesHistoryDetails(){
		$apiKey = trim($this->input->get_post('apiKey', TRUE));
        $rideId = trim($this->input->get_post('rideId', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
		//$userId = trim($this->input->get_post('userId', TRUE));


		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}

		// $this->checkDeviceToken($userId,$deviceId);
		$where["driverId"]=$driverId;
		$where["id"]      =$rideId;
		//$where["rideStatus"]!=0;
		$rideData =  $this->Driver_Webservice_model->getDataByjoinRidesId('tbl_booking',$driverId,$rideId);
//print_r($rideData);die();
$ridedatadetail =array();
		if($rideData)
		{
			$driverinfo = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
			$rideinfo = $this->Driver_Webservice_model->getDataById('tbl_ride_payment',array('ride_id'=>$rideId));
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
			else
			{
				     $ridedatadetail["payment_mode"]="";

			}
			

			$rating = $this->Driver_Webservice_model->getDataById('tbl_driver_rating',array('driverId'=>$driverId,"rideId"=>$rideId));
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
		$taxinfo = $this->Driver_Webservice_model->getDataById('tbl_settings',array('id'=>2));

			$taxRate=$taxinfo["percent"];
            $tax=$rideData[0]["totalCharge"]*$taxRate/100;
             $totalprice = $rideData[0]["totalCharge"]+$tax;
			$ridedatadetail["TotalCostIncludedTax"]=round($totalprice);






			if($rideData[0]["profilepic"]!=""){
					$ridedatadetail["user_pic"]=base_url('assets/profileImage/').$rideData[0]["profilepic"];
				    }

				    if($rideData[0]["profilepic"]==""){
					$ridedatadetail["user_pic"]="";
				    }

			
          	$vechicleinfo = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$rideData[0]["vehicleId"]));
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
	$rideDatae =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("driverId"=>$driverId,"rideStatus"=>1));
	$rideDataed =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("driverId"=>$driverId,"rideStatus"=>2));
	//print_r($rideDataed);die();
if((empty($rideDatae))){
	if(empty($rideDataed)){
	$vechicleData =  $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array("id"=>$driverData["vehicleCategoryId"],"publish_status"=>1));
	//print_r($vechicleData);die();
//	print_r($driverData);die();
	$lat = $driverData["lat"];
	$lng = $driverData["lng"];
	$distance="5";
	if(($lat!=NULL)&&($lng!=NULL)){
		$vechicleid = $driverData["vehicleCategoryId"];
		$searchData = $this->Driver_Webservice_model->searchDriver1($lat,$lng,$distance,$vechicleid,$driverId);

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
					$searchmaindata["Customer_distance"] = number_format((float)$distance, 2, '.', '');

				}
			   $searchmaindata["distance"] = number_format((float)$searchmaindata["distance"], 2, '.', '');

				$searchmaindata["vechicle_number"]=$driverData["vehicleNumber"];
				$vehicleData['image'] = base_url()."assets/vehicleImages/".$vechicleData['image'];
							$searchmaindata['vechicle_image'] = $vehicleData['image'];

			     $booking_id = $searchmaindata["id"];

				$notcheck = $this->Driver_Webservice_model->getDataById('tbl_notification',array("booking_id"=>$booking_id,"title"=>"NewBooking"));
				//print_r($notcheck);die();
				if(($notcheck == "")){
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
					$result['responseMessage'] = "Driver Already Running Ride";
}
}
else
{
	                 $result['status'] = 0;
					$result['responseMessage'] = "Current OTP is not verified, please verify it or cancel the ride to accept the new ride";

}
}
else
{
	            $result['status'] = 0;
				$result['responseMessage'] = "No driver  found";

}
	   		echo json_encode($result);


}



	function MyNewRidedDetail1(){
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

	$vechicleData =  $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array("id"=>$driverData["vehicleCategoryId"],"publish_status"=>1));
	//print_r($vechicleData);die();
//	print_r($driverData);die();
	$lat = $driverData["lat"];
	$lng = $driverData["lng"];
	$distance="5";
	if(($lat!=NULL)&&($lng!=NULL)){
		$vechicleid = $driverData["vehicleCategoryId"];
		$searchData = $this->Driver_Webservice_model->searchDriver1($lat,$lng,$distance,$vechicleid,$driverId);

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
      $driverId = trim($this->input->get_post('driverId', TRUE));
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
						$driverdatas = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData["driverId"],"id as driverId,*"));
$rideData["vechicle_number"] = $driverdatas["vehicleNumber"];

						if($vehicleData != '' ):
							$vehicleData['image'] = base_url()."assets/vehicleImages/".$vehicleData['image'];
							$rideData['vechicle_image'] = $vehicleData['image'];

						else:
							$rideData['vehicleData'] = "";	
						endif;

						if($rideData['driverId'] != 0):

							$rideData['driverData'] = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$rideData['driverId'],"id as driverId,*"));

						else:
						//	$rideData['driverData'] = "";
						endif;
					}
					else{
						//$rideData['vehicleData'] = "";
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
            	$token = $six_digit_random_number = mt_rand(1000, 9999); 
            	$data  = array("driverId"=>$driverId,"rideStatus"=>1,"token"=>$token);
            	$rideDatas =  $this->Driver_Webservice_model->update('tbl_booking',$data,array("id"=>$rideId));
                 $notdata= array("status"=>4);
            	$rideDatas =  $this->Driver_Webservice_model->update('tbl_notification',$notdata,array("booking_id"=>$rideId,"status"=>0));
            	
                $driverdatas = array("isBooked"=>1);
            	 $driverdatass =  $this->Driver_Webservice_model->update('tbl_driver',$driverdatas,array("id"=>$driverId));

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
		
            	echo json_encode(array('status' => 1, 'responseMessage' => 'Ride accept by driver','rideId'=>$rideId));die();

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
		if($token == ""){
			echo json_encode(array('status' => 0, 'responseMessage' => 'Please enter Verification Code'));die;
		}
		$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
		if (!empty($driverdata)) {
			# code...
		
		$rideData =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId,"token"=>$token,"driverId"=>$driverId));
		//print_r($rideData);die();
		if(!empty($rideData)){
			$time = date("Y-m-d H:i:s a");
			$data = array("rideStatus"=>2,"startRideTime"=>$time);
			$rideDatas =  $this->Driver_Webservice_model->update('tbl_booking',$data,array("id"=>$rideId));
			$driverdatas = array("isBooked"=>1);
            	 $driverdatass =  $this->Driver_Webservice_model->update('tbl_driver',$driverdatas,array("id"=>$driverId));
			$rideDatanew =  $this->Driver_Webservice_model->getDataById('tbl_booking',array("id"=>$rideId,"token"=>$token));
			echo json_encode(array('status' => 1, 'responseMessage' => 'OTP Matched','data'=>$rideId));die;
		}
		else
		{
			echo json_encode(array('status' => 0, 'responseMessage' => 'OTP Mismatched!!'));die;
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
		$checkRideBookingStatus = $this->Driver_Webservice_model->checkRideBookingStatus($rideId);


		 // $this->checkDeviceToken($userId,$deviceId);
    		//$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
		if (!empty($checkRideBookingStatus)) {
		 $canceldriverid = $checkRideBookingStatus["cancelbydriverid"];
		 $cancelbydriverid="";
		 if($canceldriverid!=""){
		 	$cancelbydriverid = $cancelbydriverid.",".$driverId;
		 }
		 if($canceldriverid==""){
		 	$cancelbydriverid = $driverId;
		 }

		 $userid = $checkRideBookingStatus["userId"];
		 if($checkRideBookingStatus)
		 {
		 	$driverrideid =$checkRideBookingStatus["driverId"]; 
		 	$rideStatus=0;
		 	if($driverrideid==0){
		    	$canceldata = array("rideId"=>$rideId,"driverId"=>$driverId,"status"=>1,"create_at"=> date('Y-m-d H:i:s'));
		   }
		   if($driverrideid!=0){
		    $canceldata = array("rideId"=>$rideId,"driverId"=>$driverId,"status"=>2,"create_at"=> date('Y-m-d H:i:s'));
		  }
		 //	$this->db->insert("driver_cancel_history",$canceldata);
		 $updateStatus = $this->Driver_Webservice_model->updateRideStatus($rideStatus,$rideId,$cancelReasone,$cancelbydriverid);

		 	if($updateStatus)
		 	{
		 		$driverdatas = array("isBooked"=>0);
            	 $driverdatass =  $this->Driver_Webservice_model->update('tbl_driver',$driverdatas,array("id"=>$driverId));
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
                 $insert = array("booking_id"=>$booking_id,"title"=>$title,"msg"=>$msg,"driver_id"=>$driverId,"created_at"=>$date,"status"=>1);
                 if($driverrideid!=0){
               // $this->Driver_Webservice_model->insertNotification($insert);
                  }


		         
		 			$result['status'] = 1;
					$result['responseMessage'] = "Ride Cancel Successfully";
					//$result['AllData'] = array("rideId"=>$rideId,"cancelReasone"=>$cancelReasone,"rideStatus"=>$rideStatus,"driverId"=>$driverId);

					$result['AllData'] = $rideId;
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
				$result['responseMessage'] = "Ride not found.";
		 }
		}
		else
		 {
		 		$result['status'] = 0;
				$result['responseMessage'] = "Ride not found";
		 }

		echo json_encode($result);
	}


		    function cancelAcceptRideByDriver()
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
		$checkRideBookingStatus = $this->Driver_Webservice_model->checkRideBookingStatus($rideId);


		 // $this->checkDeviceToken($userId,$deviceId);
    		//$driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
		if (!empty($checkRideBookingStatus)) {
		 $canceldriverid = $checkRideBookingStatus["cancelbydriverid"];
		 $cancelbydriverid="";
		 if($canceldriverid!=""){
		 	$cancelbydriverid = $cancelbydriverid.",".$driverId;
		 }
		 if($canceldriverid==""){
		 	$cancelbydriverid = $driverId;
		 }

		 $userid = $checkRideBookingStatus["userId"];
		 if($checkRideBookingStatus)
		 {
		 	$driverrideid =$checkRideBookingStatus["driverId"]; 
		 	$rideStatus=0;
		   $canceldata = array("rideId"=>$rideId,"driverId"=>$driverId,"status"=>2,"create_at"=> date('Y-m-d H:i:s'));
		  
		 	$this->db->insert("driver_cancel_history",$canceldata);
		 $updateStatus = $this->Driver_Webservice_model->updateRideStatus($rideStatus,$rideId,$cancelReasone,$cancelbydriverid);

		 	if($updateStatus)
		 	{
		 		$driverdatas = array("isBooked"=>0);
            	 $driverdatass =  $this->Driver_Webservice_model->update('tbl_driver',$driverdatas,array("id"=>$driverId));
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
                 $insert = array("booking_id"=>$booking_id,"title"=>$title,"msg"=>$msg,"driver_id"=>$driverId,"created_at"=>$date,"status"=>1);
                 if($driverrideid!=0){
                $this->Driver_Webservice_model->insertNotification($insert);
                  }


		         
		 			$result['status'] = 1;
					$result['responseMessage'] = "Ride Cancel Successfully";
					//$result['AllData'] = array("rideId"=>$rideId,"cancelReasone"=>$cancelReasone,"rideStatus"=>$rideStatus,"driverId"=>$driverId);

					$result['AllData'] = $rideId;
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
				$result['responseMessage'] = "Ride not found.";
		 }
		}
		else
		 {
		 		$result['status'] = 0;
				$result['responseMessage'] = "Ride not found";
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
          		  $time = date("Y-m-d H:i:s a");
		  		$distance =  $this->Driver_Webservice_model->distance($rideData["pickup_lat"],$rideData["pickup_lng"] ,$drop_lat,$drop_lng);


       $vehicleId = $rideData["vehicleId"];

		if($distance == 0)
		{
			$distance  = 1;
		}

		$getPerKmPrice = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array("id"=>$vehicleId,"publish_status"=>1));
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
		
			echo json_encode(array('status' => 1, 'responseMessage' => 'Your Ride is Stop','data'=>$rideId));die;
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

				$driverdata = $this->Driver_Webservice_model->getDataById('tbl_users',array('id'=>$rideData['userId']));
				$rideDatas =array();
				if(!empty($driverdata)){
					$rideDatas["username"]=$driverdata["name"];
					if($driverdata["profilepic"]!=""){
					$rideDatas["user_pic"]=base_url('assets/profileImage/').$driverdata["profilepic"];
				    }
				    if($driverdata["socialImageUrl"]!=""){
					$rideDatas["user_pic"]=$driverdata["socialImageUrl"];
				    }
				    if($driverdata["profilepic"]==""){
					$rideDatas["user_pic"]="";
				    }
				}
				else
				{
				    $rideDatas["username"]="";
					$rideDatas["user_pic"]="";	
				}
				$rideDatas["toal_cost"]=$rideData["totalCharge"];
				$rideDatas["totalDistance"]=$rideData["totalDistance"];
				$startridetime = explode(" ",$rideData["startRideTime"]);
				$start =date("h:i a", strtotime($startridetime[1]));

				$endridetime = explode(" ",$rideData["endRideTime"]);
				$end =date("h:i a", strtotime($endridetime[1]));
				$rideDatas["startRideTime"]=$start;
				$rideDatas["endRideTime"]=$end;
				$rideDatas["pickup_address"]=$rideData["pickup_address"];
				$rideDatas["drop_address"]=$rideData["drop_address"];
				$rideDatas["start_date"]=$startridetime[0];
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
	    //print_r($driverdata);die();
     if(!empty($driverdata)){
		$notdata = $this->Driver_Webservice_model->FindNotification($driverId);
			    foreach($notdata as $key=>$cat_fam) {

	    	if($notdata[$key]['title']  == 'NewBooking'){
	    		$notdata[$key]['notification_status']=0;
	    	}
	    	if($notdata[$key]['status']  == 1){
	    		//$dataa = explode("/r/n", $notdata[$key]['title']);
			    	$notdata[$key]["title"]=trim($notdata[$key]['title']);
	    		$notdata[$key]['notification_status']=1;
	    	}
	    	if($notdata[$key]['title']  == 3){
	    		$notdata[$key]['notification_status']=2;
	    	}
                    
             }



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
 		//echo "sds";die();
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
	    $start_date_new = date('Y-m-d', strtotime(' +1 days'));
	    $end_date_new   = date('Y-m-d', strtotime(' -30 days'));
	    $start_date = date("d M Y",strtotime($start_date_new));
	    $end_date = date("d M Y",strtotime($end_date_new));
	    //echo $start_date.$end_date;
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
		$amountuser = $this->db->select("*")->from("tbl_driver_amount_transfer")->where("driverId",$driverId)->get()->result_array();
       /*currentvbalance*/
        $sum=0;
		  	if(!empty($amountuser)){
		  		for ($i=0; $i <count($amountuser) ; $i++) { 
		  		$sum = $sum+$amountuser[$i]["amount"];	# code...
		  		}
		  		//$sum = $sum+$amountuser["amount"];
		  	}
		  	/*cashcollected online ride in month*/

		  	$amountusercash = $this->db->query("SELECT * FROM `tbl_driver_amount_transfer` WHERE transferAt BETWEEN '$end_date_new' and '$start_date_new'  and driverId='$driverId'")->result_array();
            $sumcash=0;
            $sumtotaleraning =0;
            $sumtotalonline=0;
		  	if(!empty($amountusercash)){
		  		for ($i=0; $i <count($amountusercash) ; $i++) { 
		  		 if($amountusercash[$i]["payment_mode"] ==1){
		  		  $sumcash = $sumcash+$amountusercash[$i]["amount"];	# code...
		  		 }
		  		 if($amountusercash[$i]["payment_mode"] !=1){
		  		  $sumtotalonline = $sumtotalonline+$amountusercash[$i]["amount"];	# code...
		  		 }
		  		 $sumtotaleraning = $sumtotaleraning+$amountusercash[$i]["amount"];
		  		}
		  		//$sum = $sum+$amountuser["amount"];
		  	}

		  $start_date_new_today = date('Y-m-d');
	      $end_date_new_today   = date('Y-m-d', strtotime('+1 day'));

		  		/*cashcollected online ride in today*/
		  	$amountusercashtoday = $this->db->query("SELECT * FROM `tbl_driver_amount_transfer` WHERE transferAt BETWEEN '$start_date_new_today' and '$end_date_new_today'  and driverId='$driverId'")->result_array();
           // $sumcash_today=0;
            $sumcash_today =0;
            $sum_cash_ride=0;
            $sumtotalonline_today=0;
            $sum_online_ride=0;
		  	if(!empty($amountusercashtoday)){
		  		for ($i=0; $i <count($amountusercashtoday) ; $i++) { 
		  		 if($amountusercashtoday[$i]["payment_mode"] ==1){
		  		  $sumcash_today = $sumcash_today+$amountusercashtoday[$i]["amount"];	# code...
		  		  $sum_cash_ride = $sum_cash_ride+1;
		  		 }
		  		 if($amountusercashtoday[$i]["payment_mode"] !=1){
		  		  $sumtotalonline_today = $sumtotalonline_today+$amountusercashtoday[$i]["amount"];	# code...
		  		  $sum_online_ride = $sum_online_ride+1;
		  		 }
		  		// $sumtotaleraning = $sumtotaleraning+$amountusercash[$i]["amount"];
		  		}
		  		//$sum = $sum+$amountuser["amount"];
		  	}
			//print_r($cashtotal);die(); 
            $dashboard["current_balance"] = round($sum);
			$dashboard["totalearning"] = round($sumtotaleraning);
			$dashboard["totalride"]    = $totalrides;
			$dashboard["cashcollected"] = round($sumcash);
			$dashboard["start_date"]=$end_date;
			$dashboard["end_date"]=$start_date;
			//$dashboard["cash_ride"] = $sumcash;
			//$dashboard["online_ride"] = $sumtotalonline;
            $dashboard["cash_ride"] = $sum_cash_ride;
			$dashboard["online_ride"] = $sum_online_ride;

			if(!empty($onride)){
				$dashboard["onride"]=1;
			}
			else{
				$dashbthoard["onride"]=0;
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
			$amountuser = $this->db->select("*")->from("tbl_driver_amount_transfer")->where("driverId",$driverId)->get()->result_array();
          /*currentvbalance*/
          $sumc=0;
		  	if(!empty($amountuser)){
		  		for ($i=0; $i <count($amountuser) ; $i++) { 
		  		$sumc = $sum+$amountuser[$i]["amount"];	# code...
		  		}
		  		//$sum = $sum+$amountuser["amount"];
		  	}
			$dashboard["totalearning"] = 0;
			$dashboard["totalride"]    = 0;
			$dashboard["cashcollected"] = 0;
			//$dashboard["cash_ride"] = 0;
			//$dashboard["online_ride"] = 0;
		    $dashboard["start_date"]=$end_date;
			$dashboard["end_date"]=$start_date;
		    $dashboard["cash_ride"] = 0;
			$dashboard["online_ride"] = 0;
            $dashboard["current_balance"] =$sumc ;


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


 	public function viewEarning(){
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
				$rideid =$dashdata[$key]["id"];
				$amountusercash = $this->db->query("SELECT * FROM `tbl_driver_amount_transfer` WHERE  driverId='$driverId' and rideId='$rideid'")->result_array();
				//print_r($amountusercash);die();
				if(!Empty($amountusercash)){
				if($amountusercash[0]["payment_mode"]==1){
                    $dashdata[$key]['cashcollected']  = round($amountusercash[0]["amount"]);
				}
				else
				{
				    $dashdata[$key]['cashcollected']  = 0;	
				}
				if($amountusercash[0]["payment_mode"]!=1){
                    $dashdata[$key]['onlinecollected']  = round($amountusercash[0]["amount"]);
				}
				else
				{
				    $dashdata[$key]['onlinecollected']  = 0;	
				}

             }
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

 	public function updateLocation(){
 	    $apiKey = trim($this->input->get_post('apiKey', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
      $lat = trim($this->input->get_post('lat', TRUE));
      $lng = trim($this->input->get_post('lng', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $driverdata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverId));
	  //  echo $start_date_new.$end_date_new;die();


     if(!empty($driverdata)){
     	$data = array("lat"=>$lat,"lng"=>$lng);
     	$where=array("id"=>$driverId);
		$dashdata = $this->Driver_Webservice_model->update("tbl_driver",$data,$where);
		$dashboard =array();
		if(($dashdata)){
			
           			$result['status'] = 1;
					$result['responseMessage'] = "update success";
					$result['driverId'] = $driverId;

		}
		else
		{
			        $result['status'] = 0;
					$result['responseMessage'] = "Some error occured Please try again";
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

 	public function RideCancellationHistory(){
      $apiKey = trim($this->input->get_post('apiKey', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $driverdata = $this->Driver_Webservice_model->getDataByjoinRideId($driverId);



	  //  echo $start_date_new.$end_date_new;die();
//print_r($driverdata);die();

     if(!empty($driverdata)){
     	          foreach($driverdata as $key=>$cat_fam) {
                    $date = explode(" ",$driverdata[$key]["create_at"]);
        $userdata = $this->Driver_Webservice_model->getDataById('tbl_users',array('id'=>$driverdata[$key]["userId"]));
           $drivermaindata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverdata[$key]["driverId"]));

	   	$vechicledata = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$driverdata[$key]["vehicleId"]));
			      if($userdata["profilepic"]!=""){
					$driverdata[$key]["user_pic"]=base_url('assets/profileImage/').$userdata["profilepic"];
				    }
				    if($userdata["socialImageUrl"]!=""){
					$driverdata[$key]["user_pic"]=$userdata["socialImageUrl"];
				    }
				    if($userdata["profilepic"]==""){
					$driverdata[$key]["user_pic"]="";
				    }
				    $driverdata[$key]["vechicle_name"]=$vechicledata["vehicle_name"];
				     if($vechicledata["image"]!=""){
					$driverdata[$key]["vechicle_image"]=base_url('assets/vehicleImages/').$vechicledata["image"];
				    }
				    $driverdata[$key]["vechicle_number"] = $drivermaindata["vehicleNumber"];

                    if($date[0] == (date("Y-m-d"))){
                     
                     $new_date =date("h:i a", strtotime($date[1]));
                     $driverdata[$key]["booking_date"]=$new_date;



                    }
                    else
                    {

                     $new_date =date("d-m-Y h:i a", strtotime($date[1]));
                     $driverdata[$key]["booking_date"]=$new_date;
                    }

			     }
           			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['histroyData'] = $driverdata;

		}
		else
		{
			        $result['status'] = 0;
					$result['responseMessage'] = "No cancel ride found";
			    //   $result['AllData'] = $dashboard;

 
		}
	
	   echo json_encode($result);

 	}


 	public function RideCancellationHistoryDetail(){
 	     $apiKey = trim($this->input->get_post('apiKey', TRUE));
       $driverId = trim($this->input->get_post('driverId', TRUE));
       $rideId = trim($this->input->get_post('rideId', TRUE));
		
        $result = array();
		if($apiKey != API_KEY){
			echo json_encode(array('status' => 0, 'responseMessage' => 'API Key mismatched'));die;
		}
	    $driverdata = $this->Driver_Webservice_model->getDataByjoinRideIddriverId($driverId,$rideId);
	  //  echo $start_date_new.$end_date_new;die();
//print_r($driverdata);die();

     if(!empty($driverdata)){
     	          //foreach($driverdata as $key=>$cat_fam) {
                    $date = explode(" ",$driverdata["create_at"]);
           $userdata = $this->Driver_Webservice_model->getDataById('tbl_users',array('id'=>$driverdata["userId"]));
         $drivermaindata = $this->Driver_Webservice_model->getDataById('tbl_driver',array('id'=>$driverdata["driverId"]));
         //print_r($drivermaindata);die()

	   	$vechicledata = $this->Driver_Webservice_model->getDataById('tbl_vehicle_category',array('id'=>$driverdata["vehicleId"]));
			      if($userdata["profilepic"]!=""){
					$driverdata["user_pic"]=base_url('assets/profileImage/').$userdata["profilepic"];
				    }
				    if($userdata["socialImageUrl"]!=""){
					$driverdata["user_pic"]=$userdata["socialImageUrl"];
				    }
				    if($userdata["profilepic"]==""){
					$driverdata["user_pic"]="";
				    }
				    $driverdata["vechicle_name"]=$vechicledata["vehicle_name"];
				     //$driverdata["vechicle_name"]=$driverdata["vehicle_name"];
				     $driverdata["vechicle_number"] = $drivermaindata["vehicleNumber"];
				     if($vechicledata["image"]!=""){
					$driverdata["vechicle_image"]=base_url('assets/vehicleImages/').$vechicledata["image"];
				    }
                    if($date[0] == (date("Y-m-d"))){
                     
                     $new_date =date("h:i a", strtotime($date[1]));
                     $driverdata["booking_date"]=$new_date;


                    }
                    else
                    {

                     $new_date =date("d-m-Y h:i a", strtotime($date[1]));
                     $driverdata["booking_date"]=$new_date;
                    }

			     //}
           			$result['status'] = 1;
					$result['responseMessage'] = "All Data";
					$result['histroyData'] = $driverdata;

		}
		else
		{
			        $result['status'] = 0;
					$result['responseMessage'] = "No cancel ride found";
			    //   $result['AllData'] = $dashboard;

 
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
