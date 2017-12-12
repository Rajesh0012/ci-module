<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Otp_verify extends CI_Controller {

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
            $otp = isset($post['otp']) ? trim($post['otp']) : '';
            $check = isset($get['check']) ? $get['check'] : '';
            
            
            //print_r($post); die;
            if (!empty($check)) {

                 $user = $this->Common_model->fetch_data('tbl_users', '', array('where' => array('user_hash' => $check)), true);
                 
                  // print_r($user); die;
                if (empty($user)) {

                    $value['msg']='Your link is expired.Try again';
                } else {

                    $hash_time = strtotime($user['hash_time']);
                    $current_time = strtotime(date('Y-m-d H:i:s'));

                    if ($current_time > ($hash_time + (24 * 60 * 60))) {

                        throw new Exception($this->lang->line('link_expire'), FAILURE_CODE);
                    } else {
                        
                        $updateArr['is_verified'] = '1';
                        $updateArr['user_hash'] = '';
                        $updateArr['hash_time'] = '';
                        
                        $this->Common_model->update_single('tbl_users', $updateArr, array('where' => array('id' => $user['id'])));
                        
                        $value['msg']='Thanks.You have successfully verified your account.';

                    /*    if (!empty($post)) {
                            
                            $otp1=!empty($post['otp1'])?$post['otp1']:'';
                            $otp2=!empty($post['otp2'])?$post['otp2']:'';
                            $otp3=!empty($post['otp3'])?$post['otp3']:'';
                            $otp4=!empty($post['otp4'])?$post['otp4']:'';
                            
                            if (!empty($otp1) && !empty($otp2) && !empty($otp3) && !empty($otp4)) {

                                    $otp=$otp1.$otp2.$otp3.$otp4;
                              
                                if ($user['otp'] != $otp) {

                                    $value['message_error'] = 'Otp not matched.';
                                    redirect(SITE_URL.'/Otp_verify?check='.$checkotp);
                                } else {
                                    $updateArr['is_verified'] = '1';
                                    $this->Common_model->update_single('tbl_users', $updateArr, array('where' => array('id' => $user['id'])));

                                    $value['message_success']='Otp verified successfully.Please proceed to login.';
                                }
                            } else {

                                     $value['message_error'] = 'Please enter otp';
                                     redirect(SITE_URL.'/Otp_verify?check='.$checkotp);
                            }
                        }*/
                    }
                }


                $this->load->view('otp_verification', $value);
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
