<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllSettingListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllSettingModel');
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
    function SettingListing()
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
            
            $count = $this->AllSettingModel->SettingListingCount($searchText);
            //echo $count;die();

			$returns = $this->paginationCompress ( "SettingListing/", $count, 10);
            
            $data['SettingsRecords'] = $this->AllSettingModel->SettingListing($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : Settings Listing';
            //print_r($data);die();
            $this->loadViews("setting/setting", $this->global, $data, NULL);
        }
    }


    public function createSettingXLS(){
                        $fileName = 'Setting-'.time().'.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllSettingModel->SettingListing($searchText, "", "");
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Title.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Percentage');
    

        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($val);die();
          //  echo  ;die();
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]->title);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]->percent);

             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($fileName);
        // download file
        header("Content-Type: application/vnd.ms-excel");
         redirect(site_url().$fileName);              

    }


    public function settingEditDetail(){
        $id = $this->uri->segment(2);
        $data["setting"] = $this->AllSettingModel->getSettingInfo($id);
        $this->global['pageTitle'] = 'Auto Load : Edit Setting ';

        if($data){
            $this->load->view("includes/header",$this->global,$data);

            $this->load->view("setting/editsetting",$data);
             $this->load->view("includes/footer",$data);


    }}


    public function editsettingdata(){
                $title = $this->input->post("title");
        $percent  = $this->input->post("percent");
         $id  = $this->input->post("id");

        $filename="";
        /*if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
            {
                    
                $filename = explode('.', $_FILES['image']['name']);
                $filename = 'profile_' .time().rand(100,999).'.'. $filename[count($filename)-1];
                $_FILES['image']['name'] = $filename;
                

                $config['upload_path'] = 'assets/articleimg/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                  if($this->upload->do_upload('image')){
                    $uploadData = $this->upload->data();
                    $data1 = $this->upload->data();
                    $arr['profilePic']  = $filename; 
                  }
              }
*/              $data = array("title"=>$title,"percent"=>$percent);
              $where["id"]=$id;
              $this->db->update("tbl_settings",$data,$where);
                            redirect("SettingListing");



    }


    

    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

