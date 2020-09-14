<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllVechicleListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllVechicleModel');
        $this->isLoggedIn();
        $this->load->library('session');
        $this->load->library('form_validation');
       // $this->load->hepler('url');


   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        echo "Vehicle List";die;
    }
    
    /**
     * This function is used to load the user list
     */
    function VechicleListing()
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
        
            $searchText = $this->input->post('searchText');
            //$dropdownText = $this->input->post('dropdownText');
                      
            $data['searchText'] = $searchText;
           // $data['dropdownText'] = $dropdownText;
            
            $this->load->library('pagination');
            
            $count = $this->AllVechicleModel->VechicleListingCount($searchText);
            //echo $count;die();

			$returns = $this->paginationCompress ( "VechicleListing/", $count, 10);
            
            $data['VechicleRecords'] = $this->AllVechicleModel->VechicleListing($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : Vechicle Listing';
            //print_r($data);die();
            $this->loadViews("vechicle/vechicle", $this->global, $data, NULL);
        }
    }


    public function AddVechicle(){
    	 $this->global['pageTitle'] = 'Auto Load : Add Vechicle ';
           $this->load->view("includes/header",$this->global);
          $this->load->view("vechicle/add_vechicle", $this->global);
              $this->load->view("includes/footer");
    }


    public function AddVechicledata(){
    	$vehicle_name = $this->input->post("vehicle_name");
        $pricePerKM  = $this->input->post("pricePerKM");
        $minPrice  = $this->input->post("minPrice");
        $maxPrice  = $this->input->post("maxPrice");
        $vehiDesc  = $this->input->post("vehiDesc");
        $publish_status  = $this->input->post("publish_status");
        /* add registers table validation */
        $this->form_validation->set_rules('vehicle_name', 'vehicle_name', 'trim|required');
       $this->form_validation->set_rules('pricePerKM', 'pricePerKM ', 'trim|numeric|required');
        $this->form_validation->set_rules('minPrice', 'minPrice ', 'trim|numeric|required');
         $this->form_validation->set_rules('maxPrice', 'maxPrice ', 'trim|numeric|required');
          $this->form_validation->set_rules('vehiDesc', 'vehiDesc ', 'trim|required');
       // $this->form_validation->set_rules('skills', 'skills ', 'trim|required');
       
       
        if ($this->form_validation->run() == FALSE)
        {
             $this->load->view("includes/header",$this->global);
             $this->load->view("vechicle/add_vechicle", $this->global);
              $this->load->view("includes/footer");
        }
        else
        {  

        $filename="";
       if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
            {
                    
                $filename = explode('.', $_FILES['image']['name']);
                $filename = 'vechicle_' .time().rand(100,999).'.'. $filename[count($filename)-1];
                $_FILES['image']['name'] = $filename;
                

                $config['upload_path'] = 'assets/vehicleImages/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                  if($this->upload->do_upload('image')){
                    $uploadData = $this->upload->data();
                    $data1 = $this->upload->data();
                    $arr['profilePic']  = $filename; 
                  }
              }
              $data = array("vehicle_name"=>$vehicle_name,"minPrice"=>$minPrice,"maxPrice"=>$maxPrice,"pricePerKM"=>$pricePerKM,"image"=>$filename,"vehiDesc"=>$vehiDesc,"publish_status"=>$publish_status,"createdAt"=>date("Y-m-d h:i:s"));
              $in = $this->db->insert("tbl_vehicle_category",$data);
              if($in){
              //	echo "dd";
              redirect("VechicleListing");
                 }
}
    }


    public function deletevechicle(){
    	        $id = $this->uri->segment(2);
        $data = $this->db->delete("tbl_vehicle_category",array("id"=>$id));
        if($data){
            //redirect("DriversListing");
        }

    }


    public function vehicleDetails(){
    	 $id = $this->uri->segment(2);
         $data = $this->AllVechicleModel->getVechicleInfo($id);
         $data['vechicleData'] = $data[0];
      //  print_r($data);die();
       // $driverImage = $this->db->get_where('driverTranspostImages',array("driverId"=>$id))->result_array();
        //$data['driverData']['driverDocument'] = $driverImage;
        $this->global['pageTitle'] = 'Auto Load : vechicle Detail';
         
        $this->loadViews("vechicle/vechicleDetail", $this->global, $data, NULL);


    }


    public function vehicleEditDetails(){
    	        $id = $this->uri->segment(2);
        $data = $this->AllVechicleModel->getVechicleInfo($id);
        $data["vechicle"] =$data[0];
        $this->global['pageTitle'] = 'Auto Load : Edit Vechicle ';

        if($data){
            $this->load->view("includes/header",$this->global,$data);

            $this->load->view("vechicle/editvechicle",$data);
             $this->load->view("includes/footer",$data);

        }

    }


    public function editvechicledata(){
           	$vehicle_name = $this->input->post("vehicle_name");
        $pricePerKM  = $this->input->post("pricePerKM");
        $minPrice  = $this->input->post("minPrice");
        $maxPrice  = $this->input->post("maxPrice");
        $vehiDesc  = $this->input->post("vehiDesc");
        $publish_status  = $this->input->post("publish_status");
       $image  = $this->input->post("image");

        $id  = $this->input->post("id");
        /* add registers table validation */
        $this->form_validation->set_rules('vehicle_name', 'vehicle_name', 'trim|required');
       $this->form_validation->set_rules('pricePerKM', 'pricePerKM ', 'trim|numeric|required');
        $this->form_validation->set_rules('minPrice', 'minPrice ', 'trim|numeric|required');
         $this->form_validation->set_rules('maxPrice', 'maxPrice ', 'trim|numeric|required');
          $this->form_validation->set_rules('vehiDesc', 'vehiDesc ', 'trim|required');
       // $this->form_validation->set_rules('skills', 'skills ', 'trim|required');
       
       
        if ($this->form_validation->run() == FALSE)
        {
             $this->load->view("includes/header",$this->global);
             $this->load->view("vechicle/add_vechicle", $this->global);
              $this->load->view("includes/footer");
        }
        else
        {  

        $filename="";
       if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
            {
                    
                $filename = explode('.', $_FILES['image']['name']);
                $filename = 'vechicle_' .time().rand(100,999).'.'. $filename[count($filename)-1];
                $_FILES['image']['name'] = $filename;
                

                $config['upload_path'] = 'assets/vehicleImages/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                  if($this->upload->do_upload('image')){
                    $uploadData = $this->upload->data();
                    $data1 = $this->upload->data();
                    $arr['profilePic']  = $filename; 
                  }
              }
              if($filename == ""){
              	$filename = $image;
              }
              $data = array("vehicle_name"=>$vehicle_name,"minPrice"=>$minPrice,"maxPrice"=>$maxPrice,"pricePerKM"=>$pricePerKM,"image"=>$filename,"vehiDesc"=>$vehiDesc,"publish_status"=>$publish_status);
                            $where["id"]=$id;
              $this->db->update("tbl_vehicle_category",$data,$where);

              redirect("VechicleListing");
}
    }


    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

