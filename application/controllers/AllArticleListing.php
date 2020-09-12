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
        if($data){
            $this->load->view("includes/header",$data,$this->global);

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

