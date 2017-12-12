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
     * @Return no
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

        $this->_authCheck();

        $searchdata = $this->input->get();
        $total_rows = $this->Admin_model->feature_game_list('', '', $searchdata);
        $paged = $total_rows['total_rows'];
        $data['records_found'] = $total_rows['total_rows'];
        $data['available_users'] = $paged;

        $from_date=isset($searchdata['from_date'])?'&from_date='.trim($searchdata['from_date']):'';
        $to_date=isset($searchdata['to_date'])?'&to_date='.trim($searchdata['to_date']):'';
        $game_type=isset($searchdata['game_type'])?'&game_type='.trim($searchdata['game_type']):'';
        $sports=isset($searchdata['sports'])?'&sports='.trim($searchdata['sports']):'';
        $sdisplay=isset($searchdata['display'])?'&display='.trim($searchdata['display']):'';
        $sort=isset($searchdata['sort'])?'&sort='.trim($searchdata['sort']):'';
        $search=isset($searchdata['search'])?'&search='.trim($searchdata['search']):'';
        $display=isset($searchdata['display'])?trim($searchdata['display']):'';
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['game_type'] = $game_type;
        $data['sports'] = $sports;
        $data['display'] = $sdisplay;
        $data['sort'] = $sort;
        $data['searchname'] = $search;

        $data['display'] = $this->input->get('display');

        $config['base_url'] = site_url() . '/admin/freetips?'.$from_date.$to_date.$search.$game_type.$sports;
        $config['total_rows'] = $paged;
        $data['search'] = $this->input->get('search');
        $data['sports'] = $this->Admin_model->getData('sports', 'sport_id,sport_name', '');

        if (isset($data['display']) && !empty(trim($data['display']))) {

            $config['per_page'] = $data['display'];
        } else {

            $config['per_page'] = RECORDS_PER_PAGE;
        }

        

        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = NUM_LINKS;
        $config['enable_query_strings'] = true;
        $config['page_query_string'] = true;
        $this->pagination->initialize($config);

        if (isset($_GET['per_page']) && is_numeric($this->input->get('per_page'))) {
            $pageno = $this->input->get('per_page');
        }

        if (!empty($this->input->get('per_page'))) {


            $data['page'] = (($pageno - 1) * (!empty($display) ? $display : RECORDS_PER_PAGE));
        } else {

            $data['page'] = 0;
        }


        $data['list'] = $this->Admin_model->feature_game_list($config['per_page'], $data['page'], $searchdata);


        $data['pagination'] = $this->pagination->create_links();


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


        $newArr=array();
        $newDetailArr=array();
        $event_arr = $this->Admin_model->get_weekly_sports('', '');
        
        //echo "<pre>";
                //print_r($event_arr); die;
        if (!empty($event_arr['result_arr'])) {

            foreach ($event_arr['result_arr'] as $sportr) {
                $msportr['schedule_date'] = $sportr['scheduled'];
                $detailArr = $this->Admin_model->get_event_detail(explode(',', $sportr['events']), $sportr['scheduled']);
                //die;

                if (!empty($detailArr)) {
                    foreach ($detailArr as $detail) {

                        $bookDetail = $this->Admin_model->get_book_odds_market($detail['id']);
                        //print_r($bookDetail);
                        $book = !empty($bookDetail['book_id']) ? $bookDetail['book_id'] : '';
                        $type = !empty($bookDetail['type']) ? $bookDetail['type'] : '';
                        $odds = !empty($bookDetail['odds']) ? $bookDetail['odds'] : '';

                        $detail['book_id'] = $book;
                        $detail['type'] = $type;
                        $detail['odds'] = $odds;
                        $detail['match_time'] = date('h:i:s', strtotime($detail['match_time']));


                        $comptetorArr = $this->Admin_model->fetch_data('tbl_competitors', 'competitor_name,qualifier', array('where' => array('event_id' => $detail['match_id'])), false);
                        $nameArr = array_column($comptetorArr, 'competitor_name');
                        $qualirr = array_column($comptetorArr, 'qualifier');
                        $comQualArr = array_combine($qualirr, $nameArr);
                        $detail['qualifiers'] = !empty($comQualArr) ? $comQualArr : '';

                        $newDetailArr[] = $detail;
                    }
                }

               if(!empty($newDetailArr))
               {
                   $msportr['detail_arr'] = $newDetailArr;
                   $newArr[] = $msportr;
               }
            }
        }

        //echo "<pre>";
        //print_r($newArr); die;
        $value['sport_event_arr'] = $newArr;


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
        $newDesc=array();

        if (empty($data)) {
            $mid = !empty($get['mid']) ? base64_decode($get['mid']) : '';
            $book = !empty($get['book']) ? base64_decode($get['book']) : '';

            if (!empty($mid) && !empty($book)) {

                  $dataRes=$this->Admin_model->get_outcome_detail($mid,$book);
                  //print_r($dataRes); die;
                  if(!empty($dataRes)){
                      
                         $typaArr=explode(',',$dataRes['type']);
                         $oddsArr=explode(',',$dataRes['odds']);
                        
                         
                         if(!empty($typaArr)){
                             
                             foreach($typaArr as $type){
                                 
                                 if(trim($type)=='home' || trim($type)=='away')
                                 {
                                     
                                   $comInfo = $this->Admin_model->fetch_data('tbl_competitors', 'competitor_name', array('where' => array('event_id' => $dataRes['match_id'], 'qualifier' => trim($type))), true);
                                   $detail=$comInfo['competitor_name'];
                                 }
                                 
                                 if(trim($type)=='draw'){
                                
                                   $comptitorDetail = $this->Admin_model->fetch_data('tbl_competitors', '', array('where' => array('event_id' => $dataRes['match_id'])), false);
                                   $comNameArr=array_column($comptitorDetail,'competitor_name');
                                   $compitetorName=!empty($comNameArr)?implode(' Vs ',$comNameArr):'';
                                   $detail='Draw ('.$compitetorName.')';
                               }
                               
                               $newDetail[]=$detail;
                             }
                         }
                         
                         $dataArr=array_combine($oddsArr, $newDetail);
                         
                  }
                
                $matchDetailArr = $this->Admin_model->get_match_detail($mid);
                $value['data_arr'] = !empty($dataArr) ? $dataArr : array();
                $value['detail_arr'] = !empty($matchDetailArr) ? $matchDetailArr : array();
                //echo "<pre>";
                //print_r($matchDetailArr); die;
            } else {
                return show_404();
            }
        } else {



            if ($this->input->server('REQUEST_METHOD') === 'POST') {

                $title = !empty($data['title']) ? $data['title'] : '';
                $short_desc = !empty($data['short_desc']) ? $data['short_desc'] : '';
                $status = !empty($data['status']) ? $data['status'] : '';
                $positionArr = !empty($data['position']) ? $data['position'] : '';
                $oddArr = !empty($data['odd']) ? $data['odd'] : '';
                $descArr = !empty($data['desc']) ? $data['desc'] : '';
                $market_id = !empty($data['match_id']) ? $data['match_id'] : '';
                $book_id = !empty($data['book_id']) ? $data['book_id'] : '';
                
                
                if(!empty($descArr)){
                    foreach($descArr as $cdesc){
                      
                        $newDesc[]= htmlentities(trim(strip_tags($cdesc)));
                    }
                    
                    $newDesc=array_filter($newDesc);
                 }

                 if(!empty($newDesc))
                 {
                     
                      $resultArr = $this->array_zip_combine(['odd', 'desc', 'position'], $oddArr, $descArr, $positionArr);
                $matchArr = $this->Admin_model->fetch_data('tbl_markets', '', array('where' => array('id' => $market_id)), true);
                //print_r($matchArr); die;

                //print_r($_FILES); die;
                if (!empty($resultArr)) {
                    
                    $insertTipsArr['type'] = '2';
                    $insertTipsArr['status'] = !empty($status) ? $status : '';
                    $insertTipsArr['event_id'] = !empty($matchArr['match_id']) ? $matchArr['match_id'] : '';
                    $insertTipsArr['sports'] = !empty($matchArr['sport_id']) ? $matchArr['sport_id'] : '';
                    $insertTipsArr['tournament_id'] = !empty($matchArr['tournament_id']) ? $matchArr['tournament_id'] : '';
                    $insertTipsArr['title'] = $title;
                    //$insertTipsArr['priorities'] = 1;
                    $insertTipsArr['description'] = $short_desc;
                    $insertTipsArr['market_id'] = $market_id;
                    $insertTipsArr['book_id'] = $book_id;
                    $insertTipsArr['create_date'] = date('Y-m-d H:i:s');
                    $insertTipsArr['update_date'] = date('Y-m-d H:i:s');
                    
                    $insert_id=$this->Admin_model->insert_single('tbl_free_tips', $insertTipsArr);
                    
                    if(!empty($insert_id))
                    {
                        
                        foreach ($resultArr as $desc) {

                        $description = htmlentities(trim(strip_tags($desc['desc'])));
                        if (!empty($description)) {
                            
                            $insertTipsOddsArr['tip_id'] = !empty($insert_id) ? $insert_id : '';
                            $insertTipsOddsArr['odd'] = !empty($desc['odd']) ? $desc['odd'] : '';
                            $insertTipsOddsArr['web_odd_desc'] = $desc['desc'];
                            $insertTipsOddsArr['odd_desc'] = $description;
                            $insertTipsOddsArr['position'] = !empty($desc['position']) ? $desc['position'] : '';
                            $insertTipsOddsArr['match_id'] = !empty($matchArr['match_id']) ? $matchArr['match_id'] : '';
                            $insertTipsOddsArr['create_date'] = date('Y-m-d H:i:s');
                            $insertTipsOddsArr['update_date'] = date('Y-m-d H:i:s');

                            $this->Admin_model->insert_single('tbl_tips_odds', $insertTipsOddsArr);

                       
                        }
                    }
                    
                    $tip_count = $this->Admin_model->fetch_count('tbl_free_tips', array());
                    
                    $updateLastPrior['priorities']=$tip_count;
                    $this->Admin_model->update_single('tbl_free_tips',$updateLastPrior,array('where' => array('priorities' => '1')));
                    $updateFirstPrior['priorities']=1;
                    $this->Admin_model->update_single('tbl_free_tips',$updateFirstPrior,array('where' => array('id' => $insert_id)));
                    
                             if (!empty($_FILES['image']) && $_FILES['image']['error']!='4') {


                                $file_name = $_FILES['image']['name'];
                                $file_size = $_FILES['image']['size'];
                                $file_tmp = $_FILES['image']['tmp_name'];
                                $file_type = $_FILES['image']['type'];
                                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);


                                $expensions = array("jpeg","jpg","png");

                                if (in_array($file_ext, $expensions) === false) {


                                    $this->session->set_flashdata('msg', $this->lang->line('allowed_files'));
                                    redirect(SITE_URL . '/admin/tips/add_tips_feature_match?mid=' . base64_encode($market_id) . '&book=' . base64_encode($book_id));
                                } else if ($file_size > 2097152) {


                                    $this->session->set_flashdata('msg', $this->lang->line('file_size'));

                                    redirect(SITE_URL . '/admin/tips/add_tips_feature_match?mid=' . base64_encode($market_id) . '&book=' . base64_encode($book_id));
                                } else {

                                    if (move_uploaded_file($file_tmp, FCPATH . "public/tips_images/" . $file_name)) {

                                        $image = BASE_URL . "/tips_images/" . $file_name;
                                        $updateImage['image']=$image;
                                        
                                        $this->Admin_model->update_single('tbl_free_tips',$updateImage,array('where' => array('id' => $insert_id)));
                                    } else {

                                        $this->session->set_flashdata('msg', $this->lang->line('upload_error'));

                                        redirect(SITE_URL . '/admin/tips/add_tips_feature_match?mid=' . base64_encode($market_id) . '&book=' . base64_encode($book_id));
                                    }
                                }
                            }
                        
                    }
                     
                }


                 $this->session->set_flashdata('msg', $this->lang->line('tips_added'));
                 redirect(SITE_URL . '/admin/featurematch');
                     
                     
                 }
                 else{
                     
                     $this->session->set_flashdata('msg', $this->lang->line('req_desc'));
                     redirect(SITE_URL . '/admin/tips/add_tips_feature_match?mid=' . base64_encode($market_id) . '&book=' . base64_encode($book_id));
                     
                 }

               
            }
        }

        $this->html('free_tips_featured_match_add', $value);
    }

    public function array_zip_combine(array $keys /* args */) {
        $f = function () use ($keys) {
            return array_combine($keys, func_get_args());
        };
        return call_user_func_array(
                'array_map', array_merge([$f], array_slice(func_get_args(), 1))
        );
    }
    
    
    public function edit_feature_tips(){
        
        $this->_authCheck();
        $get = $this->input->get();

        
        try {

            $id=!empty($get['id'])?base64_decode($get['id']):'';
            $tipDetail = $this->Admin_model->fetch_data('tbl_free_tips', '',array('where' => array('id' => $id)),true);
            $data['tip_detail']=$tipDetail;
            
            $comptetorArr = $this->Admin_model->fetch_data('tbl_competitors', 'competitor_name,qualifier', array('where' => array('event_id' => $tipDetail['event_id'])), false);
            $nameArr = array_column($comptetorArr, 'competitor_name');
            $data['competitor_name']  =implode(' Vs ',$nameArr);
            $data['tip_id']=$id;
            $data['match_id']=$tipDetail['event_id'];
            $data['Admin_model'] = $this->Admin_model;
            
            
                           $dataRes=$this->Admin_model->get_outcome_detail($tipDetail['market_id'],$tipDetail['book_id']);
                  if(!empty($dataRes)){
                      
                         $typaArr=explode(',',$dataRes['type']);
                         $oddsArr=explode(',',$dataRes['odds']);
                        
                         
                         if(!empty($typaArr)){
                             
                             foreach($typaArr as $type){
                                 
                                 if(trim($type)=='home' || trim($type)=='away')
                                 {
                                     
                                   $comInfo = $this->Admin_model->fetch_data('tbl_competitors', 'competitor_name', array('where' => array('event_id' => $dataRes['match_id'], 'qualifier' => trim($type))), true);
                                   $detail=$comInfo['competitor_name'];
                                 }
                                 
                                 if(trim($type)=='draw'){
                                
                                   $comptitorDetail = $this->Admin_model->fetch_data('tbl_competitors', '', array('where' => array('event_id' => $dataRes['match_id'])), false);
                                   $comNameArr=array_column($comptitorDetail,'competitor_name');
                                   $compitetorName=!empty($comNameArr)?implode(' Vs ',$comNameArr):'';
                                   $detail='Draw ('.$compitetorName.')';
                               }
                               
                               
                               
                               $newDetail[]=$detail;
                             }
                         }
                         
                         $dataArr=array_combine($oddsArr, $newDetail);
                         
                  }
                        
                    $data['data_arr']=!empty($dataArr)?$dataArr:array();
            if ($this->input->server('REQUEST_METHOD') === 'POST') {


                $newDesc=array();
                $formdata = $this->input->post();
                $positionArr = !empty($formdata['position']) ? $formdata['position'] : '';
                $oddArr = !empty($formdata['odd']) ? $formdata['odd'] : '';
                $descArr = !empty($formdata['desc']) ? $formdata['desc'] : '';
                $tip_id = !empty($formdata['tip_id']) ? $formdata['tip_id'] : '';
                $title = !empty($formdata['title']) ? $formdata['title'] : '';
                $short_desc = !empty($formdata['short_desc']) ? $formdata['short_desc'] : '';
                $status = !empty($formdata['status']) ? $formdata['status'] : '';
                
                
                if(!empty($descArr)){
                    foreach($descArr as $cdesc){
                      
                        $newDesc[]= htmlentities(trim(strip_tags($cdesc)));
                    }
                    
                    $newDesc=array_filter($newDesc);
                 }
                 
                 if(!empty($newDesc))
                 {
                     
                     if(!empty($title))
                    $updateTipArr['title']=$title;
                if(!empty($title))
                    $updateTipArr['description']=$short_desc;
                if(!empty($title))
                    $updateTipArr['status']=$status;
                
                if(!empty($updateTipArr))
                    $this->Admin_model->update_single('tbl_free_tips',$updateTipArr,array('where' => array('id' => $tip_id)));
                
                
                    
                
                if (!empty($_FILES['image']) && $_FILES['image']['error']!='4') {


                                $file_name = $_FILES['image']['name'];
                                $file_size = $_FILES['image']['size'];
                                $file_tmp = $_FILES['image']['tmp_name'];
                                $file_type = $_FILES['image']['type'];
                                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);


                                $expensions = array("jpeg","jpg","png");

                                if (in_array($file_ext, $expensions) === false) {


                                    $this->session->set_flashdata('msg', $this->lang->line('allowed_files'));
                                    redirect(SITE_URL . '/admin/tips/edit_feature_tips?id=' . base64_encode($tip_id));
                                } else if ($file_size > 2097152) {


                                    $this->session->set_flashdata('msg', $this->lang->line('file_size'));

                                    redirect(SITE_URL . '/admin/tips/edit_feature_tips?id=' . base64_encode($tip_id));
                                } else {

                                    if (move_uploaded_file($file_tmp, FCPATH . "public/tips_images/" . $file_name)) {

                                        $image = BASE_URL . "/tips_images/" . $file_name;
                                        $updateImage['image']=$image;
                                        
                                        $this->Admin_model->update_single('tbl_free_tips',$updateImage,array('where' => array('id' => $tip_id)));
                                    } else {

                                        $this->session->set_flashdata('msg', $this->lang->line('upload_error'));

                                        redirect(SITE_URL . '/admin/tips/edit_feature_tips?id=' . base64_encode($tip_id));
                                    }
                                }
                            }
                
                $resultArr = $this->array_zip_combine(['odd', 'desc', 'position'], $oddArr, $descArr, $positionArr);
                
                 foreach ($resultArr as $desc) {

                        $description = htmlentities(trim(strip_tags($desc['desc'])));
                        if (!empty($description)) {
                            
                            $oddExists=$this->Admin_model->fetch_data('tbl_tips_odds','web_odd_desc,position',array('where'=>array('tip_id'=>$tip_id,'odd'=>trim($desc['odd']))),true);
                            
                            if(!empty($oddExists)){
                                
                                $updateTipsOddsArr['web_odd_desc'] = $desc['desc'];
                                $updateTipsOddsArr['odd_desc'] = $description;
                                $updateTipsOddsArr['position'] = !empty($desc['position']) ? $desc['position'] : '';
                                $updateTipsOddsArr['update_date'] = date('Y-m-d H:i:s');

                                $this->Admin_model->update_single('tbl_tips_odds',$updateTipsOddsArr,array('where'=>array('tip_id'=>$tip_id,'odd'=>trim($desc['odd']))));
                            }
                            else{
                                
                                $insertTipsOddsArr['tip_id'] = !empty($tip_id) ? $tip_id : '';
                                $insertTipsOddsArr['odd'] = !empty($desc['odd']) ? trim($desc['odd']) : '';
                                $insertTipsOddsArr['web_odd_desc'] = $desc['desc'];
                                $insertTipsOddsArr['odd_desc'] = $description;
                                $insertTipsOddsArr['position'] = !empty($desc['position']) ? $desc['position'] : '';
                                $insertTipsOddsArr['match_id'] = !empty($tipDetail['event_id']) ? $tipDetail['event_id'] : '';
                                $insertTipsOddsArr['create_date'] = date('Y-m-d H:i:s');
                                $insertTipsOddsArr['update_date'] = date('Y-m-d H:i:s');

                                $this->Admin_model->insert_single('tbl_tips_odds', $insertTipsOddsArr);
                                
                            }
                        
                        }
                    }
                
                
                       $this->session->set_flashdata('msg', $this->lang->line('tips_edited'));
                       redirect(SITE_URL . '/admin/freetips');
                     
                 }
                 else{
                     
                     $this->session->set_flashdata('msg', $this->lang->line('req_desc'));
                     redirect(SITE_URL . '/admin/tips/edit_feature_tips?id=' . base64_encode($tip_id));
                     
                 }
                
                
                
            }
        } catch (Exception $ex) {

            $data['exception'] = $ex->getMessage();
        }

        $this->html('edit_feature_tip', $data);
        
    }
    
    public function feature_tips_details(){

        $this->_authCheck();

       $data = [];
       $detail=array();
       $newDetail=array();

        try {

            $get=$this->input->get();
            $id = !empty($get['id'])?base64_decode($get['id']):'';
            $tipDetail = $this->Admin_model->fetch_data('tbl_free_tips', '',array('where' => array('id' => $id)),true);
            
            $data['tip_detail']=$tipDetail;
            
            $comptetorArr = $this->Admin_model->fetch_data('tbl_competitors', 'competitor_name,qualifier', array('where' => array('event_id' => $tipDetail['event_id'])), false);
            $nameArr = array_column($comptetorArr, 'competitor_name');
            $qualifierArr = array_column($comptetorArr, 'qualifier');
            $data['competitor_name']  =implode(' Vs ',$nameArr);
            $data['tip_id']=$id;
            $data['match_id']=$tipDetail['event_id'];
            $data['comptitior_detail']=array_combine($qualifierArr, $nameArr);
            $data['Admin_model'] = $this->Admin_model;
            
            
            $dataRes=$this->Admin_model->get_outcome_detail($tipDetail['market_id'],$tipDetail['book_id']);
            
                  if(!empty($dataRes)){
                      
                         $typaArr=explode(',',$dataRes['type']);
                         $oddsArr=explode(',',$dataRes['odds']);
                        
                         
                         if(!empty($typaArr)){
                             
                             foreach($typaArr as $type){
                                 
                                 if(trim($type)=='home' || trim($type)=='away')
                                 {
                                     
                                   $comInfo = $this->Admin_model->fetch_data('tbl_competitors', 'competitor_name', array('where' => array('event_id' => $dataRes['match_id'], 'qualifier' => trim($type))), true);
                                   $detail=$comInfo['competitor_name'];
                                 }
                                 
                                 if(trim($type)=='draw'){
                                
                                   $comptitorDetail = $this->Admin_model->fetch_data('tbl_competitors', '', array('where' => array('event_id' => $dataRes['match_id'])), false);
                                   $comNameArr=array_column($comptitorDetail,'competitor_name');
                                   $compitetorName=!empty($comNameArr)?implode(' Vs ',$comNameArr):'';
                                   $detail='Draw ('.$compitetorName.')';
                               }
                               
                               
                               
                               $newDetail[]=$detail;
                               
                             }
                         }
                         
                         $dataArr=array_combine($oddsArr, $newDetail);
                         
                  }
                        
                    $data['data_arr']=!empty($dataArr)?$dataArr:array();
                    
            
        } catch (Exception $ex) {

            $data['exception'] = $ex->getMessage();
        }

        $this->html('feature_tip_detail', $data); 
        
    }

    /**
     * @Name add_game_match
     * @Param formdata/multipart
     * @Date 24/11/2017
     * @Desc add game match tips in web "still to work"
     * @Return country code
     * */
    public function add_game_match() {


        $this->_authCheck();

        $id = !empty($this->input->get('id'))?base64_decode(trim($this->input->get('id'))):'';
        $countdata = $this->Admin_model->feature_game_list('', '', '', '');
        $count = $countdata['total_rows'] + 1; 
            if(!empty(trim($id))){


                $data['editdata'] = $this->Admin_model->feature_game_list('', '', '', $id);

               
                if(count($data['editdata'])<1){

                    redirect(base_url('admin/games'));
                }
            }


        try {

            $config = array(
                array(
                    'field' => 'sports',
                    'label' => 'Sports',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required|numeric'
                ),
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|min_length[3]|max_length[100]|required'
                )
               
            );
            //print_r($_FILES); exit;

            $this->form_validation->set_rules($config);

            $data['sports'] = $this->Admin_model->getData('sports', 'sport_id,sport_name', '');

            if ($this->input->server('REQUEST_METHOD') === 'POST') {


                $formdata = $this->input->post();
                $updateId = trim($formdata['updateId'])?$formdata['updateId']:'';
                $formdata['web_description'] = $formdata['description'];
                $formdata['description'] = htmlentities(trim(strip_tags($formdata['description'])));

                /* if adding user  then $data['tempdata'] is active for temporary storing data until submit */

                if (empty($updateId)) {
                    $data['tempdata'] = $formdata;
                }

                if ($this->form_validation->run() == TRUE) {


                    $checkimg = $_FILES['tips_image']['name'];

                    if (!empty($checkimg)) {

                        $img = $this->uploadImage('tips_image');
                        $formdata['image'] = SITE_URL . '/public/tips_images/' . $img;
                    }

                    if (!isset($img['error']) && empty($img['error'])) {

                        $formdata['type'] = 1;

                        if (isset($updateId) && !empty($updateId)) {
                            unset($formdata['updateId']);

                            $succ = $this->Admin_model->updateData('free_tips', $formdata, $updateId);

                            if ($succ) {

                                $this->session->set_flashdata('msg', $this->lang->line('tips_updated'));
                                redirect(site_url() . 'admin/freetips');
                            } else {

                                throw new Exception($succ);
                            }
                        } else {

                            unset($formdata['updateId']);
                                
                            $formdata['priorities'] = 1;
                            
                            $this->Admin_model->updateData('free_tips', array('priorities'=>$count ), array('priorities'=> 1));
                            $getmsg = $this->Admin_model->addData('free_tips', $formdata);
                            
                            

                            if ($getmsg === TRUE) {

                                $this->session->set_flashdata('msg', $this->lang->line('tips_added'));
                                unset($data['tempdata']);

                                redirect(site_url() . 'admin/freetips');
                            } else {

                                $data['tempdata'] = $formdata;
                                throw new Exception($getmsg);
                            }
                        }
                    } else {

                        $data['exception'] = $img['error'];
                    }
                }
            }
        } catch (Exception $ex) {

            $data['exception'] = $ex->getMessage();
        }

        $this->html('free_tips_game_add', $data);
    }

    /**
     * @Name feature_list
     * @Param No
     * @Date 28/11/2017
     * @Desc add game match tips in web "still to work"
     * @Return country code
     * */
    public function feature_list() {

        $this->_authCheck();

        $get = $this->input->get();
        $value = array();
        $newArr = array();
        $newDetailArr = [];
        $sportslist = $this->Admin_model->getSports();
        foreach ($sportslist as $key => $sportvalues) {

            $sportvalues->sport_id = base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        //print_r($value); exit;

        $id = !empty($get['id']) ? base64_decode(trim($get['id'])) : '';
        $tour_id = !empty($get['tour_id']) ? base64_decode(trim($get['tour_id'])) : '';

        if ($tour_id == 'all')
            $tour_id = '';

        $tournament_arr = $this->Admin_model->get_week_tournament($id);
        $value['tournament_arr'] = !empty($tournament_arr) ? $tournament_arr : array();
        $event_arr = $this->Admin_model->get_sports_odds($id, '', '', $tour_id);
        // print_r($event_arr); die;

        if (!empty($event_arr['result_arr'])) {

            foreach ($event_arr['result_arr'] as $sportr) {
                $msportr['schedule_date'] = strtotime($sportr['scheduled']);
                $msportr['schedule_r_date'] = $sportr['scheduled'];
                $detailArr = $this->Admin_model->get_sport_detail(explode(',', $sportr['events']), $sportr['scheduled']);

                if (!empty($detailArr)) {
                    $newDetailArr = array();
                    foreach ($detailArr as $detail) {

                        $bookDetail = $this->Admin_model->get_bookie_odds($detail['id']);
                        //print_r($bookDetail);
                        $book = !empty($bookDetail['book_id']) ? $bookDetail['book_id'] : '';
                        $type = !empty($bookDetail['type']) ? $bookDetail['type'] : '';
                        $odds = !empty($bookDetail['odds']) ? $bookDetail['odds'] : '';

                        $detail['book_id'] = $book;
                        $detail['type'] = $type;
                        $detail['odds'] = $odds;
                        $detail['match_time'] = date('h:i:s', strtotime($detail['match_time']));

                        //print_r($detail);
                        //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                        //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';

                        $newDetailArr[] = $detail;
                    }
                }


                $msportr['detail_arr'] = $newDetailArr;
                $newArr[] = $msportr;
                //print_r($newDetailArr); exit;
            }
        }


        if (!empty($newDetailArr))
            $value['res_arr'] = !empty($newArr) ? $newArr : array();
        else
            $value['res_arr'] = array();
    }
    
    public function delete_feature_tip(){
        
        try {

            $this->_authCheck();

           $post=$this->input->post();
        
         if(!empty($post)){
             
              $delete_id=!empty($post['did'])?base64_decode($post['did']):''; 
             
             $this->Admin_model->delete_data('tbl_free_tips',array('where'=>array('id'=>$delete_id)));
             $this->Admin_model->delete_data('tbl_tips_odds',array('where'=>array('tip_id'=>$delete_id)));
             
             echo "success"; die;
         }
         
        } catch (Exception $ex) {

            $error= $ex->getMessage();
            
            echo $error; die;
        }
        
        
    }

    /**
     * @Name tipsDetails
     * @Param No
     * @Date 29/11/2017
     * @Desc get all free tips with apgination enabled
     * @Return list to view
     * */
    
    public function tipsDetails() {

        $data = [];

        $this->_authCheck();

        try {

            $id = trim($this->input->get('id'))?base64_decode($this->input->get('id')):redirect(base_url('admin/freetips'));
            $existId= is_numeric($id)?$id:redirect(base_url('admin/freetips'));
            $data['details'] = $this->Admin_model->feature_game_list('', '', '', $existId);

            if(count($data['details'])<1){
                redirect(base_url('admin/freetips'));
            }
        } catch (Exception $ex) {

            $data['exception'] = $ex->getMessage();
        }

        $this->html('tips-details', $data);
    }

    /**
     * @Name change_priorities
     * @Param formdata/multipart
     * @Date 29/11/2017
     * @Desc Change free tips priorities
     * @Return action message
     * */
    
    public function change_priorities() {

        try {

            $this->_authCheck();

             $changeId = $this->input->get('id'); 
             $value = $this->input->get('value'); 
             $exp = explode('|+|', $changeId);
             $id = $exp[1];
            
            $priority=$this->Admin_model->fetch_data('tbl_free_tips','priorities',array('where'=>array('id'=>$id)),true);
            if(!empty($priority))
            {
                $updatePri['priorities']=$priority['priorities'];
                $this->Admin_model->update_single('tbl_free_tips',$updatePri,array('where'=>array('priorities'=>$value)));
            }
            

            $succ = $this->Admin_model->updateData('free_tips', array('priorities' => $value), $id);
            if ($succ) {

                echo $this->lang->line('priorities_changed');
            } else {

                throw new Exception($succ);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function deleteRow(){


        try{

            $this->_authCheck();
            
            if (!empty($this->input->get('delete')) && count($this->input->get('delete')) > 0) {

                $delId = base64_decode($this->input->get('delete'));
               
                $maxPriorites = $this->Admin_model->maxId('free_tips');

                $del = $this->Admin_model->deleteAndBlock('free_tips', $delId, 'delete');

                $this->Admin_model->updateData('free_tips', array('priorities'=>$maxPriorites->maxpriorities-1 ), array('priorities'=> $maxPriorites->maxpriorities));
                if($del == TRUE){
                    
                    $this->session->set_flashdata('msg', $this->lang->line('tips_deleted'));
                }else{
                    
                    $this->session->set_flashdata('msg', $del); 
                }
                
            
                redirect(base_url() . 'admin/freetips');

            }else{

                return  FALSE;
            }
            
        }  catch (Exception $ex){
            
            
            echo $ex->getMessage();
        }
        
        
    }
}
