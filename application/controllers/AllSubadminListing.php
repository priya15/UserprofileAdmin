<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllSubadminListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllSubadminModel');
        $this->isLoggedIn();
        $this->load->library('session');
        $this->load->library('form_validation');

   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        echo "Subadmin List";die;
    }
    
    /**
     * This function is used to load the user list
     */
    function SubadminListing()
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
            
            $count = $this->AllSubadminModel->SubadminListingCount($searchText);
            //echo $count;die();

			$returns = $this->paginationCompress ( "SubadminListing/", $count, 10);
            
            $data['adminRecords'] = $this->AllSubadminModel->SubadminListing($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : Subadmin Listing';
            //print_r($data);die();
            $this->loadViews("subadmin/subadmin", $this->global, $data, NULL);
        }
    }


    public function AddSubadmin(){
              $this->global['pageTitle'] = 'Auto Load : Add Subadmin ';
           $this->load->view("includes/header",$this->global);
          $this->load->view("subadmin/add_Subadmin", $this->global);
              $this->load->view("includes/footer");

    }


    public function addsubadmindata(){
        $name        = $this->input->post("name");
        $email       = $this->input->post("email");
        $mobile      = $this->input->post("mobile");
        $password    = ($this->input->post("password"));
       // $vehiDesc  = $this->input->post("vehiDesc");
        //$publish_status  = $this->input->post("publish_status");
        $emaildata = $this->db->select("*")->from("tbl_admin")->where("email",$email)->get()->result_array();

        $mobdata = $this->db->select("*")->from("tbl_admin")->where("mobile",$mobile)->get()->result_array();
        if(!empty($emaildata)){
          $this->session->set_flashdata('error', 'Email id already exist');
             redirect("AddSubadmin");

        }
        else if(!empty($mobdata)){
          $this->session->set_flashdata('error', 'MobileNo already exist');
             redirect("AddSubadmin");

        }

        else{
        /* add registers table validation */
       $this->form_validation->set_rules('mobile', 'mobile ', 'trim|numeric|required|regex_match[/^[0-9]{10}$/]');
       // $this->form_validation->set_rules('skills', 'skills ', 'trim|required');
       
       
        if ($this->form_validation->run() == FALSE)
        {
             $this->load->view("includes/header",$this->global);
             $this->load->view("subadmin/add_Subadmin", $this->global);
              $this->load->view("includes/footer");
        }
        else
        {  

              $data = array("name"=>$name,"password"=>md5($password),"original_password"=>$password,"email"=>$email,"mobile"=>$mobile,"roleId"=>"2","createdBy"=>1,"createdDtm"=>date("Y-m-d h:i:s"));
              $in = $this->db->insert("tbl_admin",$data);
              $lastid = $this->AllSubadminModel->FindLastid();
              //print_r($lastid);die();
              if($in){
                $moduledata  = array("user"=>0,"driver"=>0,"ride"=>0,"article"=>0,"vechicle"=>0,"setting"=>0,"feedback"=>0,"subadmin"=>0,"user_id"=>$lastid[0]["userId"]);
                $this->db->insert("tbl_modules_permission",$moduledata);
              //  echo "dd";
              redirect("SubadminListing");
                 }
}

    }

}

function ModulePermission(){
          $id = $this->uri->segment(2);
          $data["permission"] =  $this->AllSubadminModel->find_module_permission($id);
          $this->global['pageTitle'] = 'Auto Load : Permission Modules ';
           $this->load->view("includes/header",$this->global);
          $this->load->view("subadmin/add_Permission", $data);
              $this->load->view("includes/footer");



}

public function addsubadminpermissiondata(){
        $user         = $this->input->post("user");
        $driver       = $this->input->post("driver");
        $ride         = $this->input->post("ride");
        $article     = $this->input->post("article");
        $vechicle     = $this->input->post("vechicle");
        $setting     = $this->input->post("setting");
        $subadmin     = $this->input->post("subadmin");
        $city     = $this->input->post("city");
        $support     = $this->input->post("support");
        $trascation     = $this->input->post("trascation");

        $aboutus     = $this->input->post("aboutus");

        $id           = $this->input->post("id");

        $userd=0;$driverd=0;$rided=0;$articled=0;$vechicled=0;$settingd=0;$subadmind=0;$cityd=0;$aboutusd=0;$trascationd=0;$supportd=0;
        if($user == "on"){
          $userd=1;
        }
        if($driver == "on"){
          $driverd=1;
        }
        if($ride == "on"){
          $rided=1;
        }
        if($article == "on"){
          $articled=1;
        }
        if($vechicle == "on"){
          $vechicled=1;
        }
        if($setting == "on"){
          $settingd=1;
        }
       if($subadmin == "on"){
          $subadmind=1;
        }
        if($city == "on"){
          $cityd=1;
        }
        if($aboutus == "on"){
          $aboutusd=1;
        }
         if($trascation == "on"){
          $trascationd=1;
        }
         if($support == "on"){
          $supportd=1;
        }
        $feedbackd=0;

                $moduledata  = array("user"=>$userd,"driver"=>$driverd,"ride"=>$rided,"article"=>$articled,"vechicle"=>$vechicled,"setting"=>$settingd,"feedback"=>$feedbackd,"subadmin"=>$subadmind,"city"=>$cityd,"aboutus"=>$aboutusd,"support"=>$supportd,"trascation"=>$trascationd);
                $this->db->update("tbl_modules_permission",$moduledata,array("user_id"=>$id));
                              redirect("SubadminListing");


}

public function SubadminEditDetail(){
        $id = $this->uri->segment(2);
        $data = $this->AllSubadminModel->getSubadminInfo($id);
        $data["subadmin"] =$data[0];
        $this->global['pageTitle'] = 'Auto Load : Edit Subadmin ';

        if($data){
            $this->load->view("includes/header",$this->global,$data);

            $this->load->view("subadmin/editsubadmin",$data);
             $this->load->view("includes/footer",$data);

        }

}


public function editsubadmindata(){
        $name        = $this->input->post("name");
        $email       = $this->input->post("email");
        $mobile      = $this->input->post("mobile");
        $password    = ($this->input->post("password"));
        $id           = ($this->input->post("id"));

       // $vehiDesc  = $this->input->post("vehiDesc");
        //$publish_status  = $this->input->post("publish_status");
        $emaildata = $this->db->select("*")->from("tbl_admin")->where("email",$email)->where("userId!=",$id)->get()->result_array();
                $modata = $this->db->select("*")->from("tbl_admin")->where("mobile",$mobile)->where("userId!=",$id)->get()->result_array();

        if(!empty($emaildata)){
          $this->session->set_flashdata('error', 'Email id already exist');
             redirect("SubadminEditDetail/".$id);

        }
        else if(!empty($modata)){
          $this->session->set_flashdata('error', 'MobileNo  already exist');
             redirect("SubadminEditDetail/".$id);

        }

        else{
        /* add registers table validation */
       $this->form_validation->set_rules('mobile', 'mobile ', 'trim|numeric|required|regex_match[/^[0-9]{10}$/]');
       // $this->form_validation->set_rules('skills', 'skills ', 'trim|required');
      $data = $this->AllSubadminModel->getSubadminInfo($id);
        $data["subadmin"] =$data[0];

       
        if ($this->form_validation->run() == FALSE)
        {
             $this->load->view("includes/header",$this->global);
             $this->load->view("subadmin/editsubadmin", $data);
              $this->load->view("includes/footer");
        }
        else
        {  
            if($password == ""){
              $data = array("name"=>$name,"email"=>$email,"mobile"=>$mobile,"roleId"=>"2","createdBy"=>1,"createdDtm"=>date("Y-m-d h:i:s"));
            }
            else
            {
                 $data = array("name"=>$name,"email"=>$email,"password"=>md5($password),"original_password"=>$password,"mobile"=>$mobile,"roleId"=>"2","updatedBy"=>1,"updatedDtm"=>date("Y-m-d h:i:s"));

            }
              $in = $this->db->update("tbl_admin",$data,array("userId"=>$id));
              if($in){
              //  echo "dd";
              redirect("SubadminListing");
                 }
}
}
    
}
  public function deleteSubadmin(){
            $id = $this->uri->segment(2);
         $data = $this->db->delete("tbl_admin",array("userId"=>$id));

        if($data){
            //redirect("DriversListing");
        }

  }

  public function createSubadminXLS(){
         $fileName = 'Subadmin.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllSubadminModel->SubadminListing($searchText, "", "");
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Email');
       $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Mobile');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Password');



        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($val);die();
          //  echo  ;die();
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]->email);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['userRecords'][$i]->mobile);
             $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $data['userRecords'][$i]->original_password);


             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Cache-Control: max-age=0");

$objWriter->save("php://output");
        // download file


  }

    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

