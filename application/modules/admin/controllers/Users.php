<?php

require 'View_Manager.php';

class Users extends View_Manager {


    public function __construct()
    {

        parent::__construct();
        $this->load->model('web/Web_model');

    }

    /**
     * @Name authCheck
     * @Param No
     * @date 03/11/2017
     * @Desc check user is login or not
     * @Retrun no
     * */

    public function _authCheck()
    {

        if ($this->session->userdata('admin_id') == "") {
            redirect(SITE_URL . '/admin');
        }
    }

    /**
     * @Name user_list
     * @Param $searchdata,$config['per_page'], $data['page']
     * @date 04/11/2017
     * @Desc cget all user list from database search filter name filter and pagination
     * @Return no
     * */

    public function user_list()
    {

        $this->_authCheck();

        $data=[];

        try {

            $searchdata=$this->input->get();

            /* get total no of user registered in database*/

            $total = $this->Admin_model->Userlist('','',$searchdata);


            $paged = $total['total_records'];

            /* below funcationality for pagination it work when user filter something*/

            $from_date=isset($searchdata['from_date'])?'?from_date='.trim($searchdata['from_date']):'';
            $to_date=isset($searchdata['to_date'])?'&to_date='.trim($searchdata['to_date']):'';
            $device_type=isset($searchdata['device_type'])?'&device_type='.trim($searchdata['device_type']):'';
            $registerd_via=isset($searchdata['registerd_via'])?'&registerd_via='.trim($searchdata['registerd_via']):'';
            $sdisplay=isset($searchdata['display'])?'&display='.trim($searchdata['display']):'';
            $sort=isset($searchdata['sort'])?'&sort='.trim($searchdata['sort']):'';
            $searchname=isset($searchdata['searchname'])?'&searchname='.trim($searchdata['searchname']):'';
            $display=isset($searchdata['display'])?trim($searchdata['display']):'';
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['device_type'] = $device_type;
            $data['registerd_via'] = $registerd_via;
            $data['sort'] = $sort;
            $data['searchname'] = $searchname;
            $data['display'] = $sdisplay;
            $config = array();
            $data['available_users'] = $paged;

            $config['base_url'] = site_url().'/admin/users/user_list'.$from_date.$to_date.$device_type.$registerd_via.$sdisplay.$sort.$searchname;
            $config['total_rows'] = $paged;

            if(isset($display) && !empty(trim($display))){

                $config['per_page'] = $display;

            }else{

                $config['per_page'] = RECORDS_PER_PAGE;

            }


            $config['use_page_numbers'] = TRUE;
            $config['num_links'] = NUM_LINKS;
            $config['enable_query_strings'] = true;
            $config['page_query_string']=true;
            $this->pagination->initialize($config);

            if(isset($_GET['per_page']) && is_numeric($this->input->get('per_page'))){
                $pageno=$this->input->get('per_page');
            }

            if (!empty($this->input->get('per_page'))) {


                $data['page'] = (($pageno-1) * (!empty($display)?$display:RECORDS_PER_PAGE));

            } else {

                $data['page'] = 0;
            }

            $data['user'] = $this->Admin_model->Userlist($config['per_page'], $data['page'], $searchdata,'');

            $data['pagination']  = $this->pagination->create_links();
            unset($searchdata['per_page']);
            $data['search']=$searchdata;

        }
        catch (Exception $ex){

            return $this->lang->line('error_occured').' '.$ex->getMessage();
        }

        View_Manager::html('user-list',$data);



    }

    /**
     * @Name blockAndDelete
     * @Param formdata
     * @date 04/11/2017
     * @Desc block user or delete
     * @Return no
     * */

    public function blockAndDelete(){

        $this->_authCheck();

        $config = array(

            array(

                'field' => 'delete[]',
                'label' => 'Delete',
                'rules' => 'trim|required|xss_clean'
            ),

        );

        $this->form_validation->set_rules($config);


        if ($this->input->server('REQUEST_METHOD') === 'POST') {


            if ($this->form_validation->run() == TRUE) {


                $formdata = $this->input->post();
                if(isset($formdata['decider']) && $formdata['decider'] == 'delete') {


                 foreach ($formdata['delete'] as $delvalues) {

                     $del = $this->Admin_model->deleteAndBlock('users', $delvalues,$formdata['decider']);

                 }
                 if ($del) {

                     $this->session->set_flashdata('msg', $this->lang->line('user_deleted'));

                     redirect(site_url() . 'admin/users/user_list');

                 } else {

                     $this->session->set_flashdata('msg', $del);

                     redirect(site_url() . 'admin/users/user_list');

                 }

                }
                if(isset($formdata['decider']) && $formdata['decider'] == 'block') {


                    foreach ($formdata['delete'] as $delvalues) {

                        $del = $this->Admin_model->deleteAndBlock('users', $delvalues,$formdata['decider']);

                    }
                    if ($del) {

                        $this->session->set_flashdata('msg', $this->lang->line('user_blocked'));

                        redirect(site_url() . 'admin/users/user_list');

                    } else {

                        $this->session->set_flashdata('msg', $del);

                        redirect(site_url() . 'admin/users/user_list');

                    }

                }
            }
        }


    }

    /**
     * @Name add_user
     * @Date 13/11/2017
     * @Param userId,$formdata,$img,pimage,multipart/formdata
     * @Desc add user and profile image from here edit also forming here .
     *
     * uploadImage is a function use to upload image in local databse form ci functionality
     * @Return Country code to views
     * */

    public function add_user()
    {

       $this->_authCheck();

        $data = [];
        $success = 0;

        try{
            $searchdata = $this->input->get();
            $data['from_date'] = isset($searchdata['from_date'])?'?from_date='.trim($searchdata['from_date']):'';
            $data['to_date'] = isset($searchdata['to_date'])?'&to_date='.trim($searchdata['to_date']):'';
            $data['device_type'] = isset($searchdata['device_type'])?'&device_type='.trim($searchdata['device_type']):'';
            $data['registerd_via'] = isset($searchdata['registerd_via'])?'&registerd_via='.trim($searchdata['registerd_via']):'';
            $data['display'] = isset($searchdata['display'])?'&display='.trim($searchdata['display']):'';
            $data['sort'] = isset($searchdata['sort'])?'&sort='.trim($searchdata['sort']):'';
            $data['searchname'] = isset($searchdata['searchname'])?'&searchname='.trim($searchdata['searchname']):'';

            $userId=$this->input->get('userId',TRUE);

            $data['country_code'] = $this->Web_model->countryCode();
            /*validation rules*/
            $config = array(
                array(

                    'field' => 'full_name',
                    'label' => 'Full Name',
                    'rules' => 'trim|required|min_length[3]|max_length[100]'
                ),
                array(

                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email'
                ),

                array(

                    'field' => 'phone_number',
                    'label' => 'Phone Number',
                    'rules' => 'trim|numeric|min_length[5]|max_length[15]|required'
                ),
                array(

                    'field' => 'country_code',
                    'label' => 'Country Code',
                    'rules' => 'trim|required'
                )

            );

            if(empty($userId)){
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|max_length[20]');
            }

            $data['userId']=$userId;
            /*check validation*/
            $this->form_validation->set_rules($config);

            $data['temp']=$this->Admin_model->Userlist('','','',$userId);

            /*check post method is called or not*/

            if ($this->input->server('REQUEST_METHOD') === 'POST') {

                $formdata = $this->input->post();
                foreach ($formdata as $key=>$values){

                    $formdata[$key] = trim($values);

                }
                /*if adding user  then $data['tempdata'] is active for temporary storing data until submit */

                if(empty($userId)){$data['tempdata'] = $formdata;}

                /*if validation is correct then proceed further*/

                if ($this->form_validation->run() == TRUE) {

                    $exist = $this->Web_model->checkUser($formdata['email'],$formdata['phone_number'],$formdata['country_code']);
                    if(isset($formdata['password']) && !empty($formdata['password'])){
                        $pass = $this->Web_model->encrypt_decrypt('encrypt',$formdata['password']);
                        $formdata['password'] = $pass;
                    }


                if(!empty($exist) && empty($userId)) {

                        if ($exist->email == $formdata['email']) {
                            $data['msg'] = $this->lang->line('email_already');

                        } elseif ($exist->country_code . $exist->phone_number == $formdata['country_code'] . $formdata['phone_number']) {

                            $data['msg'] = $this->lang->line('phone_already');

                        } else {

                            $success= 200;
                        }

                    }

                   else
                    {

                              $checkimg = $_FILES['pimage']['name'];

                            if (!empty($checkimg)) {
                                $img = $this->uploadImage('pimage');
                                $formdata['image'] = base_url().'public/user_images/'.$img;
                            }


                            if (!isset($img['error']) && empty($img['error'])) {

                                if (!empty($userId)) {


                                    /*check user choosed any file or not if choose $checkimg will be activated and call for upload image*/

                                        $success = $this->Admin_model->editUserList($formdata, $userId);

                                        if ($success === TRUE) {

                                            //$data['msg']=$this->lang->line('form_success');
                                            $this->session->set_flashdata('msg', $this->lang->line('user_updated'));
                                            redirect(site_url() . 'admin/users/user_list');


                                        } else {


                                            throw new Exception($success);

                                        }

                                } else {

                                    $getmsg = $this->Admin_model->addData('users',$formdata);

                                    if ($getmsg === TRUE) {

                                        $this->session->set_flashdata('msg', $this->lang->line('form_success'));
                                        unset($data['tempdata']);
                                        redirect(site_url() . 'admin/users/user_list');

                                    } else {

                                        $data['tempdata'] = $formdata;
                                        throw new Exception($getmsg);

                                    }

                                }
                            }else{

                                $data['exception'] = $img['error'];

                            }

                    }


                }
            }

        }catch (Exception $ex){

            $data['tempdata'] = $formdata;
            $data['exception'] = $ex->getMessage();
        }

        $data['country_code'] = $this->Web_model->countryCode();
        View_Manager::html('add-user',$data);

    }

}
