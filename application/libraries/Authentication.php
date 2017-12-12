<?php

/*
 * Class: Authentication
 * Functionality: To verify user is login or not and also check access permissions
 * Access: Public
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'common'));
        $this->load->library(array('session'));
        $this->session_info = $this->session->userdata('user_login');
        if ($this->session_info == '') {
            redirect("merchant/login");
        }
        $this->access = getAccessRights($this->session_info['user_id']);
        $this->session->unset_userdata('permission');
        $this->session->set_userdata('permission', $this->access);
        $match = getAccessMatchValue();
        if(!empty($match) && (!(is_int(array_search($match, array_column($this->access, 'access_code')))))){
            redirect("merchant/dashboard");
        }
    }
}