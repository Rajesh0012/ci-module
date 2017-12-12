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
class Forgot_Pass extends REST_Controller {

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
                    case 'forgot_password' :
                        $response = $this->forgot_password($postDataArr);
                        break;
                    case 'reset_password' :
                        $response = $this->reset_password($postDataArr);
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
     * Function: forget_password
     * @access	public
     * Description: otp send to user on forget password action.
     * @return success and otp 
     * Date: 7/11/2017
     */
    public function forgot_password($postDataArr) {

        try {

            $email = !empty($postDataArr['email']) ? $postDataArr['email'] : '';
            $type = !empty($postDataArr['type']) ? $postDataArr['type'] : '1';//1 for forgot,2 for otp

            if (!empty($email)) {

                if (!empty($email)) {

                    if (!validate_email($email)) {
                        //not valid email
                        throw new Exception($this->lang->line('invalid_email_format'), INVALID_FORMAT);
                    }
                }

                $user = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('email' => $email, 'status' => '0')), true);

                if (!empty($user)) {

                    $hashvalue = generate_string(30);
                    

                    if($type=='1'){
                        
                        $subject = 'Forget Password';
                        $message = 'Hello ' . $user['full_name'];
                        
                        $postDataArr['full_name'] = $user['full_name'];
                        $postDataArr['email'] = $email;
                        $postDataArr['hash_value'] = $hashvalue;
                        $param = $postDataArr;
                        $this->Common_model->sendmail($email, $subject, $message, true, $param, 'forgot.php');
                    }
                             
                    if($type=='2'){
                        $subject = 'Otp verfication';
                        $message = 'Hello ' . $user['full_name'];
                        $digitsU = 4;
                        $otp = rand(pow(10, $digitsU - 1), pow(10, $digitsU) - 1);
                        
                        $postDataArr['full_name'] = $user['full_name'];
                        $postDataArr['email'] = $email;
                        $postDataArr['hash_value'] = $hashvalue;
                        $postDataArr['otp'] = $otp;
                        $param = $postDataArr;
                        $this->Common_model->sendmail($email, $subject, $message, true, $param, 'otp.php');
                        $updateHash['otp'] = $otp;
                    }
                             
                    
                    $updateHash['user_hash'] = $hashvalue;
                    $updateHash['hash_time'] = date('Y-m-d H:i:s');

                    $this->Common_model->update_single('tbl_users', $updateHash, array('where' => array('email' => $email)));


                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('link_send_success');
                    //$errorMsgArr['VALUE'] = array('user_id' => $user['id'], 'user_access_token' => $user['user_access_token']);
                    $this->response($errorMsgArr);
                } else {

                    throw new Exception($this->lang->line('unregister_user'), FAILURE_CODE);
                }
            } else {

                throw new Exception($this->lang->line('params_error'), FAILURE_CODE);
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
     * Function: reset_password
     * @access	public
     * Description: After verify otp user will reset his password.
     * @return success 
     * Date: 8/11/2017
     */
    public function reset_password($postDataArr) {

        try {

            $new_password = isset($postDataArr['new_password']) ? $postDataArr['new_password'] : '';
            $con_password = isset($postDataArr['con_password']) ? $postDataArr['con_password'] : '';
            $user_access_token = isset($postDataArr['user_access_token']) ? $postDataArr['user_access_token'] : '';


            if (!empty($new_password) && !empty($con_password) && !empty($user_access_token)) {

                if ($new_password != $con_password) {

                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = FAILURE_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_failure');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('pass_match_error');
                    $this->response($errorMsgArr);
                }

                $user = $this->check_login_user($user_access_token);
                $user_id = $user['id'];

                if (!empty($user)) {

                    $new_password = $this->Common_model->encrypt_decrypt('encrypt', $new_password);

                    $data = array(
                        "password" => isset($new_password) ? $new_password : ''
                    );

                    $this->Common_model->update_single('tbl_users', $data, array('where' => array('id' => $user_id)));


                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('pass_update_success');
                    //$errorMsgArr['VALUE'] = $userDetail;
                    $this->response($errorMsgArr);
                } else {

                    throw new Exception($this->lang->line('unregister_user'), FAILURE_CODE);
                }
            } else {

                throw new Exception($this->lang->line('params_error'), FAILURE_CODE);
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
