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
class Sport extends REST_Controller {

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

    public function index_get() {

        try {

            $getRequestArr = $this->get();
            //print_r($postRequestArr); die;
            if (!empty($getRequestArr)) {
                $getDataArr = $getRequestArr;
            } else {
                $jsonRequestArr = json_decode(file_get_contents('php://input'), true);
                $getDataArr = $jsonRequestArr;
            }

            $this->check_auth();

            if ((!is_null($getDataArr)) && sizeof($getDataArr) > 0 && checkparam($getDataArr, 'array')) {

                switch ($getDataArr['method']) {
                    case 'sport_list' :
                        $response = $this->sport_list($getDataArr);
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

    public function index_post() {

        echo "<h1 style='margin-left:41%; margin-top:20%; font-color:#641F11;'><span style='font-size: 50px;'>BetCompare</span></h1>";
    }

    /**
     * Function: sport_list
     * @access	public
     * Description: get user all sport list.
     * @return success and array of sport list 
     * Date: 10/11/2017
     */
    public function sport_list($getDataArr) {

        $user_access_token = isset($getDataArr['user_access_token']) ? $getDataArr['user_access_token'] : '';

        try {

            if (!empty($user_access_token)) {
                
                $user = $this->check_login_user($user_access_token);
                $user_id = $user['id'];

                $sportArr = $this->Common_model->fetch_data('tbl_sports', '', array('where' => array('status' => '1')), false);

                if (!empty($sportArr)) {


                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('record_found');
                    $errorMsgArr['VALUE'] = $sportArr;

                    $this->response($errorMsgArr);
                } else {

                    throw new Exception($this->lang->line('record_not_found'), FAILURE_CODE);
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
