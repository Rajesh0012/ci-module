<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reset_Password extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('Common_model');
        $this->load->language('common');
    }

    public function index() {
        try {

            $value = array();
            $get = $this->input->get();
            $post = $this->input->post();
            $new_password = isset($post['new_pass']) ? trim($post['new_pass']) : '';
            $con_password = isset($post['con_pass']) ? trim($post['con_pass']) : '';

            if (!empty($get['check'])) {

                $user = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('user_hash' => $get['check'])), true);

                if (empty($user)) {

                    throw new Exception($this->lang->line('invalid_request'), FAILURE_CODE);
                } else {

                    $hash_time = strtotime($user['hash_time']);
                    $current_time = strtotime(date('Y-m-d H:i:s'));

                    if ($current_time > ($hash_time + (24 * 60 * 60))) {

                        throw new Exception($this->lang->line('link_expire'), FAILURE_CODE);
                    } else {

                        if (!empty($post)) {
                            if (!empty($new_password) && !empty($con_password)) {


                                if ($new_password != $con_password) {

                                    $value['message_error'] = 'Password not matched.';
                                } else {
                                    $new_password = $this->Common_model->encrypt_decrypt('encrypt', $new_password);
                                    $updateArr['password'] = $new_password;
                                    $this->Common_model->update_single('tbl_users', $updateArr, array('where' => array('id' => $user['id'])));


                                    $value['message_success']='Password reset successfully.Please proceed to login.';
                                }
                            } else {

                                $value['message_error'] = 'Please fill all fields';
                            }
                        }
                    }
                }


                $this->load->view('reset_password', $value);
            } else {

                throw new Exception($this->lang->line('try_again'), FAILURE_CODE);
            }
        } catch (Exception $e) {

            $errorMsgArr = array();
            $errorMsgArr['CODE'] = $e->getCode();
            $errorMsgArr['MESSAGE'] = $e->getMessage();

            echo json_encode($errorMsgArr);
            die;
        }
    }

}
