<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Auth extends MX_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('Api_model');
        $this->load->helper(array('cookie', 'url', 'language'));
        $this->load->model('Admin_model');
        $this->load->library('session');

        
        //echo $this->Admin_model->encrypt_decrypt('encrypt','12345678'); die;
    }

    public function authCheck() {
        if ($this->session->userdata('admin_id') == "") {
            redirect(SITE_URL . '/admin');
        }
    }

    public function index() {
        
        //$this->checkSession();
        $data = $this->input->post();
        $value = array();
        //print_r($data); die;
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';
        $value = array();
        //$message = $this->session->flashdata('message');
        if (!empty($message)) {
            $value['message'] = $message;
        }
        if (!empty(get_cookie('remember_me'))) {

            $getCookie = get_cookie('remember_me');
            $explodedata = explode(',', $getCookie);
            $email = $this->Admin_model->demcrypt_data($explodedata[0]);
            $password = $this->Admin_model->demcrypt_data($explodedata[1]);
            $value['cookie_check'] = 'checked';
            $value['email'] = $email;
            $value['password'] = $explodedata[1];
            $value['remember_me'] = 'on';
        }

        if (!empty($data)) {

            if (!empty($email) && !empty($password)) {

                 $password=$this->Admin_model->encrypt_decrypt('encrypt',$password); 
                 $user_detail = $this->Admin_model->fetch_data('bet_admin', '', array('where' => array('admin_email' => $email, 'admin_password' => $password, 'status' => '0')), true);
                 
                if (!empty($user_detail)) {

                    //Set data in session
                    $this->session->set_userdata(
                            array(
                                    "admin_id" => $user_detail['id'],
                                    "status" => $user_detail['status'],
                                    "admin_image" => $user_detail['admin_image']
                                )
                    );
                    
                    // set data in cookie
                    if (!empty($data['remember_me']) && ($data['remember_me'] == 'on') && (empty(get_cookie('remember_me')))) {
                        $cookieData = array(
                            'name' => 'remember_me',
                            'value' => $this->Admin_model->mcrypt_data($data['email']) . ',' . $this->Admin_model->mcrypt_data($data['password']),
                            'expire' => '7200',
                            'httponly'=>true
                        );
                        $this->input->set_cookie($cookieData);
                    } else if ((empty($data['remember_me']) || $data['remember_me'] == 'off')) {// && empty(get_cookie('remember_me'))
                        delete_cookie("remember_me");
                    }
                    

                    redirect(SITE_URL . '/admin/Home/homePage/');
                } else {
                             $value['message'] = 'Invalid Credentials.';
                }
            } else {
                             $value['message'] = "Please fill all required fields.";
            }
        }
        
        $this->load->view('external_header');
        $this->load->view('login', $value);
        $this->load->view('external_footer');
        
    }

    public function forgotpassword() {

        $data = $this->input->post();
        $value = array();
        if (!empty($data)) {

            $email = isset($data['email']) ? $data['email'] : '';

            if (!empty($email)) {

                $adminData = $this->Admin_model->fetch_data('bet_admin', '', array('where' => array('admin_email' => $email)), true);

                if (!empty($adminData)) {

                    $hashvalue = $this->generateRandomString(30);

                    $subject = 'Reset Password Request';
                    $message = ' ';
                    $postDataArr['full_name'] = $adminData['admin_name'];
                    $postDataArr['hash_value'] = base64_encode($hashvalue);
                    $param = $postDataArr;
                    $this->Admin_model->sendmail($adminData['admin_email'], $subject, $message, true, $param, 'forgotadmin.php');

                    $updateArr['admin_hash'] = $hashvalue;
                    $updateArr['hash_time'] = date('Y-m-d H:i:s');

                    $this->Admin_model->update_single('bet_admin', $updateArr, array('where' => array('id' => $adminData['id'])));

                    $value['message'] = "Link has been sent on your registered mail for reset password.Please check.";
                } else {

                    $value['message'] = "Email address is not registered with us.";
                }
            } else {

                $value['message'] = "Please enter a valid email.";
            }
        }


        $this->load->view('external_header');
        $this->load->view('forgot_password', $value);
        $this->load->view('external_footer');
    }

    public function resetpassword() {

        $data = $this->input->post();
        $value = array();

        $new_password = isset($data['new_password']) ? $data['new_password'] : '';
        $con_password = isset($data['con_password']) ? $data['con_password'] : '';
        $hash = isset($_GET['check']) ? $_GET['check'] : '';

        if (!empty($hash)) {

            $adminData = $this->Admin_model->fetch_data('bet_admin', '', array('where' => array('admin_hash' => base64_decode($hash))), true);

            $hash_time = strtotime($adminData['hash_time']);
            $current_time = strtotime(date('Y-m-d H:i:s'));


            if ($current_time > ($hash_time + (24 * 60 * 60))) {

                $value['message'] = 'Your link has been expired.Proceed again.';
            } else if (!empty($data)) {

                if (!empty($new_password) && !empty($con_password)) {
                  
                    if ($new_password != $con_password) {

                        $value['message'] = 'New password and confirm password not matched.';
                    } else {
			
                        $new_password=$this->Admin_model->encrypt_decrypt('encrypt',$new_password);
                        $updateArr['admin_password'] = $new_password;
                        $this->Admin_model->update_single('bet_admin', $updateArr, array('where' => array('id' => $adminData['id'])));


                        $this->session->set_flashdata('message', ' Password reset successfully.');
                        redirect(SITE_URL . '/admin');
                    }
                } else {
                    $value['message'] = 'Please fill all required fields.';
                }
            }
        } else {
            $value['message'] = 'Something wrong with url.';
        }


        $this->load->view('external_header');
        $this->load->view('reset_password', $value);
        $this->load->view('external_footer');
    }

    public function logout() {
        
        $this->session->sess_destroy();
        redirect(SITE_URL . '/admin');
    }

    public function checkSession() {

        $user_id = $this->session->userdata('admin_id');
        if (!empty($user_id)) {

            redirect(SITE_URL . '/admin/users');
        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Function: dashboard
     * @access	public
     * Description: Total counts of users,cards,events.folders,shoutouts,organisation,
     * activ users,total messages and room.
     * @return array 
     * Date: 19/12/2016
     */
    function dashboard() {
        echo "success"; die;
    }

     

}
