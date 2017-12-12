<?php

/**
 * Custom Authentication and error handler Controller.
 * 
 * @package         Libraries
 * @category        Libraries
 * @author          AppInventiv
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;
class AutheticationLib extends REST_Controller {

    /**
     * Constructor for the AutheticationLib
     *
     * @access public
     * @param string $config (do not change)
     * @return void , EXIT_USER_INPUT on error
     */
    public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->language('common');
    }

    /**
     * @name checkLogin
     * @description checkes for login with accesstoken from header.
     * @param string $accessToken
     * @return int or error array
     */
    public function checkLogin($accessToken) {
        if (!empty($accessToken)) {
            $this->load->model('Common_model');
            $this->load->helper('common');
            $params = array();

            $loginUserData = $this->Common_model->fetch_data('n', 'User', array('user_status'), array('where' => array("access_token" => $accessToken)));
    
            if (!empty($loginUserData)) {
                $loginUserData = array_flatten($loginUserData);
                if ($loginUserData['user_status'] != ACTIVE) {
                    $this->response(['CODE' => USER_NOT_ACTIVE, 'MESSAGE' => $this->lang->line('USER_NOT_ACTIVE')]);
                }
                return $loginUserData['user_id'];
            }
        }
        $this->response(['CODE' => TOKEN_MISMATCH, 'MESSAGE' => $this->lang->line('TOKEN_MISMATCH')]);
    }

    /**
     * @name getAccessToken
     * @description get token value from header.
     * @return string
     */
    public function getAccessToken() {
        $data = $this->input->request_headers();
        if (!empty($this->input->request_headers()['access_token'])) {
            return $this->input->request_headers()['access_token'];
        } else {
            $this->response(['CODE' => EMPTY_HEADER, 'MESSAGE' => $this->lang->line('EMPTY_HEADER')]);
        }
    }

}
