<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->language('common');
    }

    public function index() {
        try {

            $this->load->view('about');
            
        } catch (Exception $e) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = $e->getCode();
            $errorMsgArr['MESSAGE'] = $e->getMessage();

            echo json_encode($errorMsgArr);
            die;
        }
    }

}
