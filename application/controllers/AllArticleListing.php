<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class AllArticleListing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AllArticleModel');
        $this->isLoggedIn();
        $this->load->library('session');
   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        echo "Article List";die;
    }
    
    /**
     * This function is used to load the user list
     */
    function ArticleListing()
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
            
            $count = $this->AllArticleModel->ArticleListingCount($searchText);
            //echo $count;die();

			$returns = $this->paginationCompress ( "ArticleListing/", $count, 10);
            
            $data['ArticleRecords'] = $this->AllArticleModel->ArticleListing($searchText, $returns["page"], $returns["segment"]);
            // echo "<pre>";
            //print_r($data);die;
            $this->global['pageTitle'] = 'Auto Load : Article Listing';
            //print_r($data);die();
            $this->loadViews("article/article", $this->global, $data, NULL);
        }
    }


    public function AddArticle(){
        $this->global['pageTitle'] = 'Auto Load : Add Article ';
           $this->load->view("includes/header",$this->global);
          $this->load->view("article/add_article", $this->global);
              $this->load->view("includes/footer");

    }

    public function addarticledata(){
        $title = $this->input->post("title");
        $desc  = $this->input->post("desc");
        $link  = $this->input->post("link");
        $filename="";
       /* if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
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
              }*/
              $data = array("title"=>$title,"desc"=>$desc,"image"=>$filename,"link"=>$link,"created_at"=>date("Y-m-d h:i:s"));
              $this->db->insert("tbl_article",$data);
              redirect("ArticleListing");

    }

    public function createArticleXLS(){
                $fileName = 'Article.xlsx'; 
        $searchText="";
        //$dropdownText = $dropdownval;
       // echo $dropdownval;die();
        $data['userRecords'] = $this->AllArticleModel->ArticleListing($searchText, "", "");
       //print_r($data['userRecords']);die();

        // load excel library
        $this->load->library('excel');
       // $mobiledata = $this->admin_database->emp_record();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Title.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Description');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Link');
         $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Created_at');


        // set Row
        $rowCount = 2;
        for($i=0; $i<count($data['userRecords']); $i++) 
        {
            //print_r($val);die();
          //  echo  ;die();
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $data['userRecords'][$i]->title);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $data['userRecords'][$i]->desc);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $data['userRecords'][$i]->link);
          $datefinal = explode(" ",$data['userRecords'][$i]->created_at);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $datefinal[0]);
      

             $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Cache-Control: max-age=0");

$objWriter->save("php://output");
        // download file

    }

    public function editarticledata(){
        $title = $this->input->post("title");
        $desc  = $this->input->post("desc");
        $link  = $this->input->post("link");
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
*/              $data = array("title"=>$title,"desc"=>$desc,"image"=>$filename,"link"=>$link,"created_at"=>date("Y-m-d h:i:s"));
              $where["id"]=$id;
              $this->db->update("tbl_article",$data,$where);
                            redirect("ArticleListing");


    }

    public function deleteArticle(){
        $id = $this->uri->segment(2);
        $data = $this->AllArticleModel->deleteArticleInfo($id);
        if($data){
            //redirect("DriversListing");
        }

    }

    public function articleEditDetail(){
        $id = $this->uri->segment(2);
        $data["article"] = $this->AllArticleModel->getArticleInfo($id);
                $this->global['pageTitle'] = 'Auto Load : Edit Article ';

        if($data){
            $this->load->view("includes/header",$this->global,$data);

            $this->load->view("article/editarticle",$data);
             $this->load->view("includes/footer",$data);

        }

    }

    
    

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Auto Load : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

