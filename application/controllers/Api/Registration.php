<?php

require APPPATH . 'libraries/REST_Controller.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Api-signup
 *
 * @author 
 */
class Registration extends REST_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->helper('bet');
        $this->load->model('Common_model');
        $this->load->language('common');
        $this->load->library('session');

        //ob_start('ob_gzhandler');
    }

    public function index_get() {
        echo "<h1 style='margin-left:41%; margin-top:20%; font-color:#641F11;'><span style='font-size: 50px;'>PAPR</span></h1>";
    }

    public function check_auth() {
        //$headerreq = $this->head();
        //print_r($headerreq);
        //mod_php
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

            // most other servers
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']), 'basic') === 0)
                list($username, $password) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        }
        
        //print_r($_SERVER['HTTP_AUTHORIZATION']); die;
        
        if (empty($username) || empty($password)) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = FAILURE_CODE;
            $errorMsgArr['APICODERESULT'] = $this->lang->line('no_authentication');
            $errorMsgArr['MESSAGE'] = $this->lang->line('invalid_token');

            $this->response($errorMsgArr);
        }

        //$authHeader = $access_key;
        if ($username != $this->config->item('access_user') || $password != $this->config->item('access_password')) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = FAILURE_CODE;
            $errorMsgArr['APICODERESULT'] = $this->lang->line('no_authentication');
            $errorMsgArr['MESSAGE'] = $this->lang->line('invalid_token');

            $this->response($errorMsgArr);
        }
    }

    public function index_post() {

        try {

            $postRequestArr = $this->post();
            //print_r($postRequestArr); die;
            if (!empty($postRequestArr)) {
                $postDataArr = $postRequestArr;
            } else {
                $jsonRequestArr = json_decode(file_get_contents('php://input'), true);
                $postDataArr = $jsonRequestArr;
            }

            $this->check_auth();

            if ((!is_null($postDataArr)) && sizeof($postDataArr) > 0 && checkparam($postDataArr, 'array')) {

                switch ($postDataArr['method']) {
                    case 'signup' :
                        $response = $this->signup($postDataArr);
                        break;
                    case 'login' :
                        $response = $this->login($postDataArr);
                        break;
                    case 'verify_otp' :
                        $response = $this->verify_otp($postDataArr);
                        break;
                    case 'send_otp' :
                        $response = $this->send_otp($postDataArr);
                        break;
                    default :
                        $response = $this->response(array('CODE' => FAILURE_CODE, 'MESSAGE' => $this->lang->line('method_not_found')));
                        break;
                }

                $this->response($response);
            } else {
                $errorMsgArr = array();
                $errorMsgArr['CODE'] = FAILURE_CODE;
                $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
                $errorMsgArr['MESSAGE'] = $this->lang->line('params_error');

                $this->response($errorMsgArr);
            }
        } catch (Exception $e) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = $e->getCode();
            $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
            $errorMsgArr['MESSAGE'] = $e->getMessage();

            $this->response($errorMsgArr);
        }
    }

    /**
     * Function: signup
     * @access	public
     * Description: normal signup by user.
     * @return array 
     * Date: 6/11/2017
     */
    public function signup($postDataArr) {

        try {

            $full_name = isset($postDataArr['full_name']) ? $postDataArr['full_name'] : '';
            $email = isset($postDataArr['email']) ? $postDataArr['email'] : '';
            $country_code = isset($postDataArr['country_code']) ? $postDataArr['country_code'] : '';
            $phone_no = isset($postDataArr['phone_number']) ? $postDataArr['phone_number'] : '';
            $password = isset($postDataArr['password']) ? $postDataArr['password'] : '';
            $image = isset($postDataArr['image']) ? $postDataArr['image'] : '';
            $device_token = isset($postDataArr['device_token']) ? $postDataArr['device_token'] : '';
            $device_type = isset($postDataArr['device_type']) ? $postDataArr['device_type'] : '';
            $udid = isset($postDataArr['udid']) ? $postDataArr['udid'] : '';
            $signup_type = isset($postDataArr['signup_type']) ? $postDataArr['signup_type'] : '1';//1 for normal,5 for guest

            if (((!empty($email) || !empty($phone_no)) && !empty($password)) || ($signup_type=='5')) {

                //print_r($postDataArr); die;
                /**
                 * start transaction
                 */
                $UserArr=array();
                
                if($signup_type=='1')
                   $UserArr = $this->Common_model->check_exists_user($email, $phone_no);
                if($signup_type=='5')
                    $UserArr=$this->Common_model->fetch_data('tbl_users','',array('where'=>array('udid'=>$udid,'status'=>'0')),true);

                if ((empty($UserArr)) || ($signup_type=='5' && empty($UserArr))) {
                    $otp = 0;
                    if (!empty($phone_no)) {
                        $digitsU = 4;
                        $otp = rand(pow(10, $digitsU - 1), pow(10, $digitsU) - 1);
                    }

                    if (!empty($email)) {
                        if (!validate_email($email)) {
                            //not valid email
                            throw new Exception($this->lang->line('invalid_email_format'), INVALID_FORMAT);
                        }
                    }
                    
                    
                    if(!empty($_FILES['image'])){
      
                        
                                $file_name = $_FILES['image']['name']; 
                                $file_size =$_FILES['image']['size'];
                                $file_tmp =$_FILES['image']['tmp_name'];
                                $file_type=$_FILES['image']['type'];
                                $file_ext=pathinfo($file_name,PATHINFO_EXTENSION);
                               
      
                                $expensions= array("jpeg","jpg","png");
                               
                                 if(in_array($file_ext,$expensions)=== false){
                                    
                                      throw new Exception($this->lang->line('allowed_files'), FAILURE_CODE);
                                 }
                                 else if($file_size > 2097152){
                                              
                                      throw new Exception($this->lang->line('file_size'), FAILURE_CODE);
                               }
                               else{
                                   
                                       if(move_uploaded_file($file_tmp,FCPATH."public/user_images/".$file_name)){
                                          
                                           $image=BASE_URL."/user_images/".$file_name;
                                       }
                                       else{
                                           throw new Exception($this->lang->line('upload_error'), FAILURE_CODE);
                                       }
                                       
                                }
                     }


                    $this->db->trans_begin();

                    $insertArr['email'] = $email;
                    $insertArr['full_name'] = $full_name;
                    $insertArr['country_code'] = $country_code;
                    $insertArr['phone_number'] = $phone_no;
                    $insertArr['password'] = $this->Common_model->encrypt_decrypt('encrypt', $password);
                    $insertArr['otp'] = !empty($otp) ? $otp : '0';
                    $insertArr['image'] = $image;
                    $insertArr['signup_type'] = $signup_type;
                    $insertArr['device_token'] = $device_token;
                    $insertArr['device_type'] = $device_type;
                    $insertArr['udid']=$udid;
                    $insertArr['is_verified'] = '0';
                    $insertArr['create_date'] = CURRENT_DATE;


                    $user_id = $this->Common_model->insert_single('tbl_users', $insertArr);

                    if (($this->db->trans_status() == TRUE) && (!empty($user_id))) {
                        // commit
                        $this->db->trans_commit();

                        $access_token = $this->Common_model->mcrypt_data($user_id);
                        $updateArr['user_access_token'] = $access_token;
                        $this->Common_model->update_single('tbl_users', $updateArr, array('where' => array('id' => $user_id)));

                        $resultArr = $this->Common_model->fetch_data('tbl_users', 'full_name,email,country_code,phone_number,otp,is_verified,image,user_access_token', array('where' => array('id' => $user_id)), true);

                        $errorMsgArr = array();
                        $errorMsgArr['CODE'] = SUCCESS_CODE;
                        $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                        $errorMsgArr['MESSAGE'] = $this->lang->line('registration_success');
                        $errorMsgArr['VALUE'] = array('result_arr' => $resultArr);

                        $this->response($errorMsgArr);
                    } else {
                        //roolback
                        $this->db->trans_rollback();

                        $errorMsgArr = array();
                        $errorMsgArr['CODE'] = FAILURE_CODE;
                        $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
                        $errorMsgArr['MESSAGE'] = $this->lang->line('try_again');
                        //$errorMsgArr['VALUE'] = $resultArr;

                        $this->response($errorMsgArr);
                    }
                }else if(!empty($UserArr) && $signup_type=='5'){
                    
                        $resultArr = $this->Common_model->fetch_data('tbl_users', 'full_name,email,country_code,phone_number,otp,is_verified,image,user_access_token', array('where' => array('id' => $UserArr['id'])), true);
                        
                        $errorMsgArr = array();
                        $errorMsgArr['CODE'] = SUCCESS_CODE;
                        $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                        $errorMsgArr['MESSAGE'] = $this->lang->line('registration_success');
                        $errorMsgArr['VALUE'] = array('result_arr' => $resultArr);

                        $this->response($errorMsgArr);
                    
                } else {

                    throw new Exception($this->lang->line('user_already'), USER_ALREADY);
                }
            } else {

                throw new Exception($this->lang->line('params_error'), MISSING_PARAMETER);
            }
        } catch (Exception $e) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = $e->getCode();
            $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
            $errorMsgArr['MESSAGE'] = $e->getMessage();

            $this->response($errorMsgArr);
        }
    }

    /**
     * Function: login
     * @access	public
     * Description: User normal login/signup via social media(facebook,google plus,twitter).
     * @return array 
     * Date: 6/11/2017
     */
    public function login($data) {

        try {

            $email = isset($data['email']) ? $data['email'] : ''; 
            $facebook_id = isset($data['facebook_id']) ? $data['facebook_id'] : '';
            $google_id = isset($data['google_id']) ? $data['google_id'] : '';
            $twitter_id = isset($data['twitter_id']) ? $data['twitter_id'] : '';
            $twitter_name = isset($data['twitter_name']) ? $data['twitter_name'] : '';
            $password = isset($data['password']) ? $data['password'] : '';
            $login_type = isset($data['login_type']) ? $data['login_type'] : '';
            $image = isset($data['image']) ? $data['image'] : '';
            $phone_no = isset($data['phone_number']) ? $data['phone_number'] : '';
            $device_token = isset($data['device_token']) ? $data['device_token'] : '';
            $device_type = isset($data['device_type']) ? $data['device_type'] : '';
            $country_code = isset($data['country_code']) ? $data['country_code'] : '';
            $full_name = isset($data['full_name']) ? $data['full_name'] : '';
            $udid = isset($postDataArr['udid']) ? $postDataArr['udid'] : '';
            
            //print_r($data); die;

            if (!empty($login_type)) {

                if ($login_type == 'normal') {
                    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $email) && !empty($email)) {
                        $email = trim($email);
                    } else {
                        $phone_no = $phone_no;
                    }

                    //$pass = $this->Common_model->encrypt_decrypt('encrypt', $password);
                    $userArr = $this->Common_model->fetch_login_data('tbl_users', '', $email, $phone_no, $password);
                    //echo $this->db->last_query(); die;
                    //print_r($userArr); die;
                }

                if ($login_type == 'social' && !empty($facebook_id)) {
                    $email = $email;
                    $userArr = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('facebook_id' => $facebook_id, 'status' => '0')), true);
                    $updateArr['facebook_id'] = $facebook_id;
                    $updateArr['email'] = $email;
                    $updateArr['phone_number'] = $phone_no;
                    $updateArr['is_verified'] = '1';
                } else if ($login_type == 'social' && !empty($google_id)) {
                    $email = $email;
                    $userArr = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('google_id' => $google_id, 'status' => '0')), true);
                    $updateArr['google_id'] = $google_id;
                    $updateArr['email'] = $email;
                    $updateArr['phone_number'] = $phone_no;
                    $updateArr['is_verified'] = '1';
                } else if ($login_type == 'social' && !empty($twitter_id)) {
                    $email = $email;
                    $userArr = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('twitter_id' => $twitter_id, 'status' => '0')), true);
                    $updateArr['twitter_name'] = $twitter_name;
                    $updateArr['twitter_id'] = $twitter_id;
                    $updateArr['phone_number'] = $phone_no;
                    $updateArr['email'] = $email;
                    $updateArr['is_verified'] = '1';
                }


                if (!empty($userArr)) {


                    $updateArr['device_token'] = $device_token;
                    $updateArr['device_type'] = $device_type;
                    $updateArr['udid']=$udid;

                    $this->Common_model->update_single('tbl_users', $updateArr, array('where' => array('id' => $userArr['id'])));


                    $loginArr = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('id' => $userArr['id'])), true);



                    $loginDetail = array(
                        'user_id' => $loginArr['id'],
                        'full_name' => isset($loginArr['full_name']) ? $loginArr['full_name'] : " ",
                        'email' => $loginArr['email'],
                        'phone_number' => $loginArr['phone_number'],
                        'country_code' => $loginArr['country_code'],
                        'image' => $loginArr['image'],
                        'status' => $loginArr['status'],
                        'facebook_id' => $loginArr['facebook_id'],
                        'google_id' => $loginArr['google_id'],
                        'twitter_id' => $loginArr['twitter_id'],
                        'twitter_name' => $loginArr['twitter_name'],
                        'is_verified' => $loginArr['is_verified'],
                        'user_access_token' => isset($loginArr['user_access_token']) ? $loginArr['user_access_token'] : '',
                    );



                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('login_success');
                    $errorMsgArr['VALUE'] = $loginDetail;

                    $this->response($errorMsgArr);
                } else if (empty($userArr) && !empty($data['facebook_id'])) {

                    $passlen = '7';
                    $password = $this->generate_string($passlen);

                    $pass = $this->Common_model->encrypt_decrypt('encrypt', $password);

                    $insertArr = array(
                        'facebook_id' => isset($data['facebook_id']) ? $data['facebook_id'] : '',
                        'email' => isset($data['email']) ? $data['email'] : '',
                        'phone_number' => isset($data['phone_number']) ? $data['phone_number'] : '',
                        'country_code' => isset($data['country_code']) ? $data['country_code'] : '',
                        'full_name' => isset($data['full_name']) ? $data['full_name'] : '',
                        'password' => isset($pass) ? $pass : '',
                        "device_type" => isset($data['device_type']) ? $data['device_type'] : '',
                        "signup_type" => '2',
                        "device_token" => isset($data['device_token']) ? $data['device_token'] : '',
                        'image' => isset($data['image']) ? $data['image'] : '',
                        'is_verified' => '1',
                        "create_date" => date('Y-m-d H:i:s')
                    );

                    $user_id = $this->Common_model->insert_single('tbl_users', $insertArr);

                    $user_access_token = $this->Common_model->mcrypt_data($user_id);
                    $updateProArr['user_access_token'] = $user_access_token;
                    $this->Common_model->update_single('tbl_users', $updateProArr, array('where' => array('id' => $user_id)));

                    $userDetail = array(
                        'facebook_id' => $data['facebook_id'],
                        'email' => isset($data['email']) ? $data['email'] : '',
                        'country_code' => isset($data['country_code']) ? $data['country_code'] : '',
                        'phone_number' => isset($data['phone_number']) ? $data['phone_number'] : '',
                        'country_code' => isset($data['country_code']) ? $data['country_code'] : '',
                        'full_name' => isset($data['full_name']) ? $data['full_name'] : '',
                        'image' => isset($data['image']) ? $data['image'] : '',
                        'user_id' => $user_id,
                        'is_verified' => '1',
                        'user_access_token' => isset($user_access_token) ? $user_access_token : '',
                    );

                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('login_success');
                    $errorMsgArr['VALUE'] = $userDetail;

                    $this->response($errorMsgArr);
                } else if (empty($userArr) && !empty($data['google_id'])) {

                    $passlen = '7';
                    $password = $this->generate_string($passlen);

                    $pass = $this->Common_model->encrypt_decrypt('encrypt', $password);

                    $insertArr = array(
                        'google_id' => isset($data['google_id']) ? $data['google_id'] : '',
                        'email' => isset($data['email']) ? $data['email'] : '',
                        'country_code' => isset($data['country_code']) ? $data['country_code'] : '',
                        'phone_number' => isset($data['phone_number']) ? $data['phone_number'] : '',
                        'full_name' => isset($data['full_name']) ? $data['full_name'] : '',
                        'password' => isset($pass) ? $pass : '',
                        "signup_type" => '3',
                        "device_type" => isset($data['device_type']) ? $data['device_type'] : '',
                        "device_token" => isset($data['device_token']) ? $data['device_token'] : '',
                        'image' => isset($data['image']) ? $data['image'] : '',
                        'is_verified' => '1',
                        "create_date" => date('Y-m-d H:i:s')
                    );

                    $user_id = $this->Common_model->insert_single('tbl_users', $insertArr);

                    $user_access_token = $this->Common_model->mcrypt_data($user_id);
                    $updateProArr['user_access_token'] = $user_access_token;
                    $this->Common_model->update_single('tbl_users', $updateProArr, array('where' => array('id' => $user_id)));

                    $userDetail = array(
                        'google_id' => $data['google_id'],
                        'email' => isset($data['email']) ? $data['email'] : '',
                        'phone_number' => isset($data['phone_number']) ? $data['phone_number'] : '',
                        'country_code' => isset($data['country_code']) ? $data['country_code'] : '',
                        'full_name' => isset($data['full_name']) ? $data['full_name'] : '',
                        'image' => isset($data['image']) ? $data['image'] : '',
                        'user_id' => $user_id,
                        'is_verified' => '1',
                        'user_access_token' => isset($user_access_token) ? $user_access_token : '',
                    );

                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('login_success');
                    $errorMsgArr['VALUE'] = $userDetail;

                    $this->response($errorMsgArr);
                } else if (empty($userArr) && !empty($data['twitter_id'])) {

                    $passlen = '7';
                    $password = $this->generate_string($passlen);
                    $pass = $this->Common_model->encrypt_decrypt('encrypt', $password);

                    $insertArr = array(
                        'twitter_id' => isset($data['twitter_id']) ? $data['twitter_id'] : '',
                        'twitter_name' => isset($data['twitter_name']) ? $data['twitter_name'] : '',
                        'email' => isset($data['email']) ? $data['email'] : '',
                        'phone_number' => isset($data['phone_number']) ? $data['phone_number'] : '',
                        'country_code' => isset($data['country_code']) ? $data['country_code'] : '',
                        'full_name' => isset($data['full_name']) ? $data['full_name'] : '',
                        'password' => isset($pass) ? $pass : '',
                        "signup_type" => '4',
                        "device_type" => isset($data['device_type']) ? $data['device_type'] : '',
                        "device_token" => isset($data['device_token']) ? $data['device_token'] : '',
                        'image' => isset($data['image']) ? $data['image'] : '',
                        'is_verified' => '1',
                        "create_date" => date('Y-m-d H:i:s')
                    );

                    $user_id = $this->Common_model->insert_single('tbl_users', $insertArr);


                    $user_access_token = $this->Common_model->mcrypt_data($user_id);
                    $updateProArr['user_access_token'] = $user_access_token;
                    $this->Common_model->update_single('tbl_users', $updateProArr, array('where' => array('id' => $user_id)));



                    $userDetail = array
                        (
                        'twitter_name' => $data['twitter_name'],
                        'twitter_id' => $data['twitter_id'],
                        'email' => isset($data['email']) ? $data['email'] : '',
                        'phone_number' => isset($data['phone_number']) ? $data['phone_number'] : '',
                        'country_code' => isset($data['country_code']) ? $data['country_code'] : '',
                        'full_name' => isset($data['full_name']) ? $data['full_name'] : '',
                        'image' => isset($data['image']) ? $data['image'] : '',
                        'user_id' => $user_id,
                        'is_verified' => '1',
                        'user_access_token' => isset($user_access_token) ? $user_access_token : '',
                    );

                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('login_success');
                    $errorMsgArr['VALUE'] = $userDetail;

                    $this->response($errorMsgArr);
                } else {

                    throw new Exception($this->lang->line('invalid_credential'), FAILURE_CODE);
                }
            } else {

                throw new Exception($this->lang->line('params_error'), MISSING_PARAMETER);
            }
        } catch (Exception $e) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = $e->getCode();
            $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
            $errorMsgArr['MESSAGE'] = $e->getMessage();

            $this->response($errorMsgArr);
        }
        //print_r($data); die;
    }

    /**
     * Function: verify_otp
     * @access	public
     * Description: This api is use for verify otp.
     * @return success 
     * Date: 6/11/2017
     */
    public function verify_otp($data) {

        try{
            
            $otp = isset($data['otp']) ? $data['otp'] : '';
            $user_access_token = isset($data['user_access_token']) ? $data['user_access_token'] : '';


        if (!empty($otp) && !empty($user_access_token)) {

            $getUserData = $this->check_login_user($user_access_token);
            $user_id = $getUserData['id'];


            if (($getUserData['otp'] == $otp)) {

                $updatedata = array(
                    "is_verified" => '1'
                );

                $this->db->trans_begin();

                $this->Common_model->update_single('tbl_users', $updatedata, array('where' => array('id' => $user_id)));

                if (($this->db->trans_status() == TRUE)) {
                    // commit
                    $this->db->trans_commit();
                    
                    $loginArr = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('id' => $user_id)), true);



                    $loginDetail = array(
                        'user_id' => $loginArr['id'],
                        'full_name' => isset($loginArr['full_name']) ? $loginArr['full_name'] : " ",
                        'email' => $loginArr['email'],
                        'phone_number' => $loginArr['phone_number'],
                        'country_code' => $loginArr['country_code'],
                        'image' => $loginArr['image'],
                        'status' => $loginArr['status'],
                        'facebook_id' => $loginArr['facebook_id'],
                        'google_id' => $loginArr['google_id'],
                        'twitter_name' => $loginArr['twitter_name'],
                        'is_verified' => $loginArr['is_verified'],
                        'user_access_token' => isset($loginArr['user_access_token']) ? $loginArr['user_access_token'] : '',
                    );

                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('otp_verify_success');
                    $errorMsgArr['VALUE']=$loginDetail;

                    $this->response($errorMsgArr);
                } else {
                    //roolback
                    $this->db->trans_rollback();
                    
                    throw new Exception($this->lang->line('try_again'), FAILURE_CODE);

                }
            } else {
                
                throw new Exception($this->lang->line('otp_verify_failure'), FAILURE_CODE);
               
            }
        } else {
            
            throw new Exception($this->lang->line('params_error'), FAILURE_CODE);
           
        }
            
        }
        catch(Exception $e){
            
            $errorMsgArr = array();
            $errorMsgArr['CODE'] = $e->getCode();
            $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
            $errorMsgArr['MESSAGE'] = $e->getMessage();

            $this->response($errorMsgArr);
        }
        
    }
    
    
    /**
     * Function: send_otp
     * @access	public
     * Description: This funstion is use for send otp on mobile.
     * @return array 
     * Date: 6/11/2016
     */
    public function send_otp($data) {

        try{
            
                    $phone_no = isset($data['phone_number']) ? $data['phone_number'] : '';
                    $user_access_token = isset($data['user_access_token']) ? $data['user_access_token'] : '';
        

        if (!empty($phone_no)) {


            if (!empty($phone_no))
                       $whereArr['phone_number'] = $phone_no;

                if (!empty($user_access_token))
                           $whereArr['user_access_token'] = $user_access_token;
                           $whereArr['status'] = '0';

                           $userArr = $this->Common_model->fetch_data('tbl_users', '', array('where' => $whereArr), true);
            //echo $this->db->last_query(); die;
            

            if (empty($userArr)) {
                    
                throw new Exception($this->lang->line('number_unregister'), FAILURE_CODE);
                
            } else {

                $digits = 4;
                $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                    $sms_message = "Your BetCompare otp for verification is " . $otp;

                    //$success = $this->sendSms($phone_no, $sms_message);

                   // if ($success) {
                        $updatedata = array(
                            "otp" => isset($otp) ? $otp : ''
                        );
                        
                        $this->db->trans_begin();

                        $finalArr['user_id'] = $userArr['id'];
                        $finalArr['otp'] = $otp;
                        $finalArr['user_access_token'] = $userArr['user_access_token'];

                        $this->Common_model->update_single('tbl_users', $updatedata, array('where' => $whereArr));

                   if (($this->db->trans_status() == TRUE)) {
                    
                         // commit
                           $this->db->trans_commit();

                           $errorMsgArr = array();
                           $errorMsgArr['CODE'] = SUCCESS_CODE;
                           $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                           $errorMsgArr['MESSAGE'] = $this->lang->line('otp_send_success');
                           $errorMsgArr['VALUE'] = $finalArr;

                           $this->response($errorMsgArr);
                     
                   } else {
                             //roolback
                                 $this->db->trans_rollback();
                                throw new Exception($this->lang->line('try_again'), FAILURE_CODE);

                          }

                        
                   /* } else {
                        $errorMsgArr = array();
                        $errorMsgArr['CODE'] = FAILURE_CODE;
                        $errorMsgArr['APICODERESULT'] = APIRESULT_FAILURE;
                        $errorMsgArr['MESSAGE'] = SOMETHING_WENT_WRONG;

                        $this->response($errorMsgArr);
                    }*/
                
            }
        } else {

            throw new Exception($this->lang->line('params_error'), FAILURE_CODE);
        }
            
        }
        catch(Exception $e){
            
            $errorMsgArr = array();
            $errorMsgArr['CODE'] = $e->getCode();
            $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
            $errorMsgArr['MESSAGE'] = $e->getMessage();

            $this->response($errorMsgArr);
        }
       
    }
    
  public  function generate_string($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    
    /**
     * Function: check_login_user
     * @access	public
     * Description: Check user login using access token.
     * @return success 
     * Date: 25/03/2017
     */
    public function check_login_user($user_access_token) {
        $userDetail = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('user_access_token' => $user_access_token, 'status' => '0')), true);

        if (empty($userDetail)) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = FAILURE_CODE;
            $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
            $errorMsgArr['MESSAGE'] = $this->lang->line('unregister_user');

            $this->response($errorMsgArr);
        } else {

            return $userDetail;
        }
    }

}
