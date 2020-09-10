<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{
    
    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($email, $password)
    {
        // $this->db->select('BaseTbl.userId, BaseTbl.password, BaseTbl.name, BaseTbl.roleId, Roles.role');
        $this->db->from('tbl_admin');
        // $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('email', $email);
        $this->db->where('password', md5($password));
        $query = $this->db->get();
        
        $user = $query->result();
        // print_r($user);die;
        if(!empty($user)){
           
                return $user;
          
        } else {
            return array();
        }
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email)
    {
        $this->db->select('userId');
        $this->db->where('email', $email);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_admin');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
        $result = $this->db->update('tbl_admin',array("emailToken"=>$data['emailToken']),array("email"=>$data['email']));

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $this->db->select('userId, email, name');
        $this->db->from('tbl_admin');
        $this->db->where('isDeleted', 0);
        $this->db->where('email', $email);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        $this->db->select('userId');
        $this->db->from('tbl_admin');
        $this->db->where('email', $email);
        $this->db->where('emailToken', $activation_id);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        return $query->num_rows();
    }

    // This function used to create new password by reset link
    function createPasswordUser($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_admin', array('password'=>md5($password)));
        // echo $this->db->last_query();die;
        // $this->db->delete('tbl_reset_password', array('email'=>$email));
    }
}

?>
