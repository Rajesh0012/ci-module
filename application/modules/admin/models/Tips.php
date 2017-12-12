<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'View_Manager.php';

class Tips extends View_Manager {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('Api_model');
        $this->load->helper(array('cookie', 'url', 'language'));
        $this->load->model('Admin_model');

        $this->load->library('session');

    }

    /**
     * @Name authCheck
     * @Param No
     * @date 03/11/2017
     * @Desc check user is login or not
     * @Retrun no
     * */


    public function _authCheck() {
        if ($this->session->userdata('admin_id') == "") {
            redirect(SITE_URL . '/admin');
        }
    }

    /**
     * @Name index
     * @Param $htmlView,$data
     * @Date 05/11/2017
     * @Desc defulat load free tips with html and data
     * @Return country code to views
     * */

    public function index() {


        $data['list']=$this->Admin_model->feature_game_list();

        $this->html('free_tips', $data);

        
    }

    /**
     *
     * @Name add_feature_match
     * @Param $htmlView,$data
     * @Date 05/11/2017
     * @Desc "still to work"
     * */


    public function add_feature_match() {
        
        $this->_authCheck();
        $data = $this->input->post();
        $value = array();
        
        if (!empty($message)) {
            $value['message'] = $message;
        }
        
        $upComingEventArr=$this->Admin_model->get_upcoming_events();
        
        //echo "<pre>";
        //print_r($upComingEventArr); die;
       if(!empty($upComingEventArr))
        {
            foreach($upComingEventArr as $event)
            {
                $event['start_date']=date('d M,Y',strtotime($event['start_date']));
                $event['day']=date('l',strtotime($event['start_date']));
                
                //$event['outc_event_arr']=$this->Admin_model->get_sport_outcomes($event['tournament_id']);
                
                          $sportEventArr=$this->Admin_model->get_sport_events($event['tournament_id']);
                        //  echo '<pre>';
                         // print_r($sportEventArr); //die;
                if(!empty($sportEventArr))
                {
                    foreach($sportEventArr as $sevent)
                    {
                        
                        $outComeArr=$this->Admin_model->get_sport_outcomes($sevent['event_id']);
                        //echo "<pre>";
                        //print_r($outComeArr); die;
                        $sevent['outcome_arr']=$outComeArr;
                      
                        $newOutArr[]=$sevent;
                    }
                  
                    $event['outc_event_arr']=$newOutArr;
                }
                
                $newArr[]=$event;
            }
            
        }
        
        //echo "<pre>";
        //print_r($newArr); die;
        $value['sport_event_arr']=$newArr;


        $this->html('free_tips_featured_match', $value);

        
    }

    /**
     *
     * @Name add_tips_feature_match
     * @Param $htmlView,$data
     * @Date 05/11/2017
     * @Desc add features tips inot web
     * @Return No
     * */


    public function add_tips_feature_match() {
        
        $this->_authCheck();
        $data = $this->input->post();
        $get = $this->input->get();
        $value = array();
        //print_r($data); die;
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';
        $value = array();
        //$message = $this->session->flashdata('message');
        if(!empty($message)) {
            $value['message'] = $message;
        }
        
                  $outComeArr=$this->Admin_model->fetch_data('tbl_outcome','',array('where'=>array('tournament_id'=>base64_decode($get['tid']),'event_id'=>base64_decode($get['eid']))),false);
        if(!empty($outComeArr)){
                  $value['outcome_arr']=$outComeArr;
        }
                  $getComptetior=$this->Admin_model->get_Team_Detail(base64_decode($get['eid']));
        
                  $value['comptetior_arr']=$getComptetior;
             // echo "<pre>";
             // print_r($value); die;

        $this->html('free_tips_featured_match_add', $value);

        
    }

    /**
     * @Name add_game_match
     * @Param formdata/multipart
     * @Date 24/11/2017
     * @Desc add game match tips in web "still to work"
     * @Retrun country code
     * */


    public function add_game_match()
    {


        $this->_authCheck();
        try {

            $config = array(
                array(

                    'field' => 'category_id',
                    'label' => 'Game Category',
                    'rules' => 'trim|required'
                ),
                array(

                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required'
                ),

                array(

                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|max_length[100]|required'
                ),
                array(

                    'field' => 'description',
                    'label' => 'Tips Content',
                    'rules' => 'trim|required|min_length[5]'
                ),

            );

            $this->form_validation->set_rules($config);

            $data['category'] = $this->Admin_model->getSearchCategories();

            if ($this->input->server('REQUEST_METHOD') === 'POST') {

                $formdata = $this->input->post();

                /*if adding user  then $data['tempdata'] is active for temporary storing data until submit */

                if (empty($userId)) {
                    $data['tempdata'] = $formdata;
                }

                if ($this->form_validation->run() == TRUE) {

                    $data = $this->input->post();


                    try {

                        $checkimg = $_FILES['tips_image']['name'];

                        if (!empty($checkimg)) {
                            $img = $this->uploadImage('tips_image');
                        }

                        if (!isset($img['error']) && empty($img['error'])) {


                            $formdata['image'] = SITE_URL . '/public/tips_images/' . $img;
                            $formdata['tip_type'] = 1;
                            $formdata['tip_position'] = 0;
                            $getmsg = $this->Admin_model->add_user('free_tips', $formdata);

                            if ($getmsg === TRUE) {

                                $this->session->set_flashdata('msg', $this->lang->line('form_success'));
                                unset($data['tempdata']);

                                redirect(site_url() . 'admin/freetips');

                            } else {

                                $data['tempdata'] = $formdata;
                                throw new Exception($getmsg);

                            }
                        } else {

                            $data['exception'] = $img['error'];

                        }
                    } catch (Exception $ex) {

                        $data['tempdata'] = $formdata;
                        $data['exception'] = $ex->getMessage();
                    }


                }
            }


        }catch (Exception $ex){

            $data['exception'] = $ex->getMessage();
        }

        $this->html('free_tips_game_add', $data);
        
    }


    public function tips_list(){






    }

    public function feature_list(){

        $get = $this->input->get();
        $value=array();
        $newArr=array();
        $newDetailArr = [];
        $sportslist=$this->Admin_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        //print_r($value); exit;

        $id=!empty($get['id'])?base64_decode(trim($get['id'])):'';
        $tour_id=!empty($get['tour_id'])?base64_decode(trim($get['tour_id'])):'';

        if($tour_id=='all')
            $tour_id='';

        $tournament_arr=$this->Admin_model->get_week_tournament($id);
        $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
        $event_arr=$this->Admin_model->get_sports_odds($id,'','',$tour_id);
        // print_r($event_arr); die;

        if(!empty($event_arr['result_arr']))
        {

            foreach($event_arr['result_arr'] as $sportr)
            {
                $msportr['schedule_date']=strtotime($sportr['scheduled']);
                $msportr['schedule_r_date']=$sportr['scheduled'];
                $detailArr=$this->Admin_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);

                if(!empty($detailArr))
                {
                    $newDetailArr=array();
                    foreach($detailArr as $detail)
                    {

                        $bookDetail=$this->Admin_model->get_bookie_odds($detail['id']);
                        //print_r($bookDetail);
                        $book=!empty($bookDetail['book_id'])?$bookDetail['book_id']:'';
                        $type=!empty($bookDetail['type'])?$bookDetail['type']:'';
                        $odds=!empty($bookDetail['odds'])?$bookDetail['odds']:'';

                        $detail['book_id']=$book;
                        $detail['type']=$type;
                        $detail['odds']=$odds;
                        $detail['match_time']=  date('h:i:s',strtotime($detail['match_time']));

                        //print_r($detail);
                        //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                        //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';

                        $newDetailArr[]=$detail;

                    }
                }



                $msportr['detail_arr']=$newDetailArr;
                $newArr[]=$msportr;
                //print_r($newDetailArr); exit;

            }

        }


        if(!empty($newDetailArr))
            $value['res_arr']=!empty($newArr)?$newArr:array();
        else
            $value['res_arr']=array();


    }

    
}
