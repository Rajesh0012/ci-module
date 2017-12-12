<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'View_Manager.php';
require APPPATH . 'libraries/src/TwitterOAuth.php';
//require APPPATH . 'libraries/OAuth.php';
//require APPPATH . 'libraries/twitteroauth.php';

class Auth extends View_Manager {

    public function __construct() {
        parent::__construct();

        $this->load->model('Web_model');
        $this->load->helper(array('cookie', 'url', 'language'));
        $this->load->model('Home_model');
        $this->load->library('session');
    }

    protected function _authCheck() {

        if ($this->session->userdata('user_id') == "") {
            redirect(SITE_URL . '/web');
        }
    }

    public function index() {




        $data = $this->input->post();
        $get = $this->input->get();


        if (!empty($data) || !empty($get)) {

            if (!empty($data)) {

                if (($data['login_type'] == 'facebook')) {

                    if (!empty($data['user_data']['email']))
                        $user_detail = $this->Web_model->fetch_data('tbl_users', '', array('where' => array('email' => $data['user_data']['email'], 'status' => '0')), true);
                    else
                        $user_detail = $this->Web_model->fetch_data('tbl_users', '', array('where' => array('facebook_id' => $data['user_data']['id'], 'status' => '0')), true);

                    if (!empty($user_detail)) {

                        if (!empty($user_detail['full_name']))
                            $full_name = $user_detail['full_name'];
                        if (!empty($user_detail['id']))
                            $user_id = $user_detail['id'];

                        //$status=$user_detail['status'];


                        $this->session->set_userdata(
                                array(
                                    "full_name" => $full_name,
                                    "user_id" => $user_id
                                    //"status" =>$status
                                )
                        );


                        echo "1";
                        die;
                    } else {
                        $digits = 6;
                        $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                        $digitsU = 4;
                        $unique_id = rand(pow(10, $digitsU - 1), pow(10, $digitsU) - 1);

                        $password = '12345678';
                        $pass = $this->Web_model->encrypt_decrypt('encrypt', $password);
                        $insertArr = array();
                        $insertArr['email'] = isset($data['user_data']['email']) ? $data['user_data']['email'] : '';
                        $insertArr['password'] = isset($pass) ? $pass : '';
                        $insertArr['otp'] = isset($otp) ? $otp : '';
                        $insertArr['full_name'] = isset($data['user_data']['first_name']) ? $data['user_data']['first_name'] . ' ' . $data['user_data']['last_name'] : '';
                        $insertArr['image'] = isset($data['user_data']['picture']['data']['url']) ? $data['user_data']['picture']['data']['url'] : '';
                        $insertArr['facebook_id'] = isset($data['user_data']['id']) ? $data['user_data']['id'] : '';
                        $insertArr['is_verified'] = '1';
                        $insertArr['signup_type'] = '2';
                        $insertArr['update_date'] = date('Y-m-d H:i:s');
                        $insertArr['create_date'] = date('Y-m-d H:i:s');

                        $user_id = $this->Web_model->insert_single('tbl_users', $insertArr);


                        $this->session->set_userdata(
                                array(
                                    "full_name" => $data['user_data']['first_name'],
                                    "user_id" => $user_id
                                )
                        );

                        //$this->session->set_flashdata('message_success', 'Account verified successfully.');
                        //redirect(SITE_URL . '/web/card/edit_card?card='.base64_encode($cardDetail['card_id']));

                        echo 1;
                        die;
                    }
                }


                if ($data['login_type'] == 'google') {

                    if (!empty($data['user_data']['emails'][0]['value']))
                        $user_detail = $this->Web_model->fetch_data('tbl_users', '', array('where' => array('email' => $data['user_data']['emails'][0]['value'], 'status' => '0')), true);
                    else
                        $user_detail = $this->Web_model->fetch_data('tbl_users', '', array('where' => array('google_id' => $data['user_data']['id'], 'status' => '0')), true);

                    if (!empty($user_detail)) {

                        if (!empty($user_detail['full_name']))
                            $full_name = $user_detail['full_name'];
                        if (!empty($user_detail['id']))
                            $user_id = $user_detail['id'];


                        $this->session->set_userdata(
                                array(
                                    "full_name" => $full_name,
                                    "user_id" => $user_id
                                //"status" =>$status
                                )
                        );



                        echo "1";

                        die;
                    } else {
                        $digits = 6;
                        $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);


                        $password = '12345678';
                        $pass = $this->Web_model->encrypt_decrypt('encrypt', $password);

                        $insertArr = array();
                        $insertArr['email'] = isset($data['user_data']['emails'][0]['value']) ? $data['user_data']['emails'][0]['value'] : '';
                        $insertArr['password'] = isset($pass) ? $pass : '';
                        $insertArr['otp'] = isset($otp) ? $otp : '';
                        $insertArr['full_name'] = isset($data['user_data']['displayName']) ? $data['user_data']['displayName'] : '';
                        $insertArr['image'] = isset($data['user_data']['image']['url']) ? $data['user_data']['image']['url'] : '';
                        $insertArr['google_id'] = isset($data['user_data']['id']) ? $data['user_data']['id'] : '';
                        $insertArr['is_verified'] = '1';
                        $insertArr['signup_type'] = '3';
                        $insertArr['update_date'] = date('Y-m-d H:i:s');
                        $insertArr['create_date'] = date('Y-m-d H:i:s');

                        $user_id = $this->Web_model->insert_single('tbl_users', $insertArr);



                        $this->session->set_userdata(
                                array(
                                    "full_name" => $data['user_data']['displayName'],
                                    "user_id" => $user_id
                                )
                        );

                        echo 1;
                        die;
                    }
                }


                if ($data['login_type'] == 'normal') {

                    $email = isset($data['email']) ? $data['email'] : '';
                    $password = isset($data['password']) ? $data['password'] : '';
                    $phone_no = isset($data['phone_no']) ? $data['phone_no'] : '';
                    $email_id = isset($data['email_id']) ? $data['email_id'] : '';

                    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $email)) {
                        $email_id = $email;
                    } else {
                        $phone_no = $email;
                    }


                    $userArr = array();
                    $userArr = $this->Web_model->fetch_login_data('tbl_users', '', $email_id, $phone_no, $password);
                    echo $this->db->last_query(); die;
                    //print_r($userArr); die;
                    if (empty($userArr)) {
                        echo json_encode(array("message" => "Invalid credential", "code" => "101"));
                        die;
                    } else if ($userArr['is_verified'] == '0' && $userArr['signup_type'] == '1') {

                        echo json_encode(array("message" => "Please verify your account", "code" => "102"));
                        die;
                    } else {

                        $this->session->set_userdata(
                                array(
                                    "full_name" => !empty($userArr['full_name']) ? $userArr['full_name'] : '',
                                    "user_id" => !empty($userArr['id']) ? $userArr['id'] : ''
                                )
                        );

                        echo json_encode(array("message" => "User Login successfully", "code" => "200"));
                        die;
                    }
                }
            }

            if (!empty($get)) {
                if (!empty($get['oauth_token'])) {


                    $twClient = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'], $_SESSION['token_secret']);
                    $token = $twClient->oauth("oauth/access_token", array("oauth_verifier" => $get['oauth_verifier']));

                    //If returns success
                    if ($token['user_id']) {
                        
                        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token['oauth_token'], $token['oauth_token_secret']);
                        $userInfo = $connection->get("account/verify_credentials");
                        
                        
                        $user_detail = $this->Web_model->fetch_data('tbl_users', '', array('where' => array('twitter_id' => $userInfo->id, 'status' => '0')), true);
                        //print_r($user_detail); die;
                        if (!empty($user_detail)) {

                            if (!empty($user_detail['full_name']))
                                       $full_name = $user_detail['full_name'];
                            if (!empty($user_detail['id']))
                                       $user_id = $user_detail['id'];


                            $this->session->set_userdata(
                                    array(
                                        "full_name" => isset($full_name)?$full_name:'',
                                        "user_id" => isset($user_id)?$user_id:''
                                    )
                            );


                            redirect(SITE_URL . '/web');
                        } else {
                            $digits = 6;
                            $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                            $password = '12345678';
                            $pass = $this->Web_model->encrypt_decrypt('encrypt', $password);

                            $insertArr = array();

                            $insertArr['email'] = isset($userInfo->email) ? $userInfo->email : '';
                            $insertArr['password'] = isset($pass) ? $pass : '';
                            $insertArr['otp'] = isset($otp) ? $otp : '';
                            $insertArr['full_name'] = isset($userInfo->name) ? $userInfo->name : '';
                            $insertArr['image'] = isset($userInfo->profile_image_url) ? $userInfo->profile_image_url : '';
                            $insertArr['twitter_id'] = isset($userInfo->id) ? $userInfo->id : '';
                            $insertArr['twitter_name'] = isset($userInfo->screen_name) ? $userInfo->screen_name : '';
                            $insertArr['is_verified'] = '1';
                            $insertArr['signup_type'] = '4';
                            $insertArr['update_date'] = date('Y-m-d H:i:s');
                            $insertArr['create_date'] = date('Y-m-d H:i:s');
                            
                            //print_r($insertArr); die;

                            $user_id = $this->Web_model->insert_single('tbl_users', $insertArr);

                            $this->session->set_userdata(
                                    array(
                                        "full_name" => $userInfo->name,
                                        "user_id" => $user_id
                                    )
                            );

                            redirect(SITE_URL . '/web');
                        }


                        //Remove oauth token and secret from session
                        unset($_SESSION['token']);
                        unset($_SESSION['token_secret']);

                        //Redirect the user back to the same page
                        // header('Location: ./');
                    }
                }
            }
        }

        $get['id']=!empty($get['id'])?$get['id']:'';
        $sportslist=$this->Web_model->getSports();

        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;

        $value['country_code'] = $this->Web_model->countryCode();
        $value['allList'] = $this->Web_model->get_all_odds($get['id'],'','');

        $this->html('home', $value);

    }

    public function twitter_hand() {

        //Fresh authentication
        
        $connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
        //$content = $connection->get("account/verify_credentials");
        //print_r($request_token); die;
        //$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
        //print_r($request_token);
        

        //Any value other than 200 is failure, so continue only if http code is 200

         if(!empty($request_token['oauth_callback_confirmed']))

          {

          //$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
          


          //Received token info from twitter

          $_SESSION['token']          = $request_token['oauth_token'];

          $_SESSION['token_secret']   = $request_token['oauth_token_secret'];

          //redirect user to twitter
          $twitter_url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
          //$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);

          echo $twitter_url; die;

          //header('Location: ' . $twitter_url);

          }else{

          echo "0";

          }
    }

    protected function _login() {

        $post = $this->input->post();

        if (!empty($post)) {
            
        }
    }

    protected function _logout() {

        $this->session->sess_destroy();
        redirect(SITE_URL . '/web');
    }

    protected function _checkSession() {

        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id)) {

            redirect(SITE_URL . '/web/home');
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

    public function preValidation(){


        if ($this->input->is_ajax_request()) {

            try {
                $csrf = array(
                    'name' => $this->security->get_csrf_token_name(),
                    'hash' => $this->security->get_csrf_hash()
                );

                $config = array(
                    array(

                        'field' => 'name',
                        'label' => 'Name',
                        'rules' => 'trim|min_length[3]|max_length[50]|required|alpha_numeric_spaces'
                    ),
                    array(

                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'trim|required|valid_email'
                    ),

                    array(

                        'field' => 'phone',
                        'label' => 'Phone Number',
                        'rules' => 'trim|numeric|min_length[6]|max_length[15]|required'
                    ),


                );

                $this->form_validation->set_rules($config);


                if ($this->form_validation->run() == FALSE) {

                    print_r(validation_errors());


                } else {


                    $data = $this->input->post();

                    if (!empty($data['email'])) {

                        $exist = (object) array(
                            'email'=>'',
                            'phone'=>''

                        );

                        $exist = $this->Web_model->checkUser($data['email'], $data['phone']);

                        if(!empty($exist)){

                            if ($exist->email == $data['email']) {

                                echo $this->lang->line('email_already');

                            } elseif ($exist->phone_number == $data['phone']) {

                                echo $this->lang->line('phone_already');

                            } else {

                                echo $this->lang->line('success_code');


                            }

                        }else{

                            echo $this->lang->line('success_code');
                        }

                    }
                }
            }catch (Exception $ex){

                echo $ex->getMessage();

            }
        } else{

            return FALSE;
        }


    }

    public function SignUp()
    {




        try {

            if ($this->input->is_ajax_request()) {

                $csrf = array(
                    'name' => $this->security->get_csrf_token_name(),
                    'hash' => $this->security->get_csrf_hash()
                );

                $formdata=$this->input->post();

                /*validation rules*/

                $config =array(


                    array(

                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'trim|required|min_length[6]|max_length[20]'
                    ),
                    array(

                        'field' => 'confirmpass',
                        'label' => 'Confirm Pass',
                        'rules' => 'trim|required|min_length[6]|max_length[20]|matches[password]'
                    ),

                );



                /*check validation*/
                $this->form_validation->set_rules($config);


                if ($this->form_validation->run() == FALSE) {

                    print_r(validation_errors());


                } else {



                    if(isset($_FILES["userimage"]["name"]) && !empty($_FILES["userimage"]["name"])){

                        $img = $this->uploadImage('userimage');

                        $data['image'] = SITE_URL.'/public/user_images/'.$img;

                    }
                    $formdata=$this->input->post();

                    $pass = $this->Web_model->encrypt_decrypt('encrypt',$formdata['password']);

                    $data['email']=$formdata['email'];
                    $data['country_code']=$formdata['country_code'];
                    $data['password']=$pass;
                    $data['phone_number']=$formdata['phone'];
                    $data['full_name']=$formdata['name'];

                    $exist=$this->Web_model->checkUser($data['email'],$data['phone_number']);

                    if(!$exist){

                        $getmsg = $this->Web_model->add_user($data);

                        if ($getmsg === TRUE) {

                                 $this->Web_model->sendmail($data['email'],'Welcome '.$data['full_name'],'Please Verify Your Account');
                            echo $this->lang->line('success_code');

                        } else {

                            throw new Exception($getmsg);

                        }

                    }else{


                        $this->lang->line('user_already');

                    }



                }

            }else{


                return FALSE;
            }

        }catch (Exception $ex) {


            echo $ex->getMessage();
        }



    }

}
