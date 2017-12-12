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
class Sport_Odd extends REST_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->helper('bet');
        $this->load->model('Common_model');
        $this->load->model('Sport_model');
        $this->load->language('common');
        $this->load->library('session');

        ob_start('ob_gzhandler');
        
        //echo 1; die;
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
                    case 'odds_by_sport' :
                        $response = $this->odds_by_sport($getDataArr);
                        break;
                    case 'get_market_odds' :
                        $response = $this->get_market_odds($getDataArr);
                        break;
                    case 'get_market_list' :
                        $response = $this->get_market_list($getDataArr);
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
     * Function: odds_by_sport
     * @access	public
     * Description: get user all adds of sport in a week.
     * @return success and array of sport list 
     * Date: 22/11/2017
     */
    public function odds_by_sport($getDataArr) {

        $user_access_token = isset($getDataArr['user_access_token']) ? $getDataArr['user_access_token'] : '';
        $sport_id = isset($getDataArr['sport_id']) ? $getDataArr['sport_id'] : '';
        $count = isset($getDataArr['count']) ? $getDataArr['count'] : '0';
        $tournament_id = isset($getDataArr['tournament_id']) ? $getDataArr['tournament_id'] : '';

        try {

            if (!empty($user_access_token) && !empty($sport_id)) {
                
                $user = $this->check_login_user($user_access_token);
                $user_id = $user['id'];
                $limit = 10;
                
        
                $page = isset($count) ? $count : 0;
                $next = $page + $limit;

                if(empty($tournament_id))
                $tournamentArr = $this->Sport_model->get_week_tournament($sport_id);
                $newArr=array();
                $newDetailArr=array();
                $sportResArr=$this->Sport_model->get_sports_odds($sport_id,$limit,$page,$tournament_id);
                //print_r($sportResArr); die;

                if (!empty($sportResArr)) {
                    
                    if ($sportResArr['total_record'] <= $next) {
                                   $next = -1;
                        }

                    foreach($sportResArr['result_arr'] as $sportr)
                    {
                        
                        $msportr['schedule_date']=strtotime($sportr['scheduled']);
                        $msportr['schedule_r_date']=$sportr['scheduled'];  
                        $detailArr=$this->Sport_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);
                        //print_r($detailArr); die;
                        
                      if(!empty($detailArr))
                      {
                                  
                            foreach($detailArr as $detail)
                            {
                              
                                $bookDetail=$this->Sport_model->get_bookie_odds($detail['id']);
                                //print_r($bookDetail);
                                $book=!empty($bookDetail['book_id'])?$bookDetail['book_id']:'';
                                $type=!empty($bookDetail['type'])?$bookDetail['type']:'';
                                $odds=!empty($bookDetail['odds'])?$bookDetail['odds']:'';
                                
                                $detail['book_id']=$book;
                                $detail['type']=$type;
                                $detail['odds']=$odds;
                                $detail['match_time']=  strtotime($detail['match_time']);
                                
                                //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                                //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';
                                
                                $newDetailArr[]=$detail;
                                
                            }
                        }
                        
                    
                            if(!empty($newDetailArr))
                            {
                               $msportr['detail_arr']=$newDetailArr;  
                               $newArr[]=$msportr;
                            }
                        
                        
                    }

                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('record_found');
                    $errorMsgArr['VALUE'] = $newArr;
                    $errorMsgArr['NEXT']=$next;
                    if(empty($tournament_id))
                    $errorMsgArr['tournament_arr']=!empty($tournamentArr)?$tournamentArr:array();

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
     * Function: get_market_odds
     * @access	public
     * Description: list all market odds for a match.
     * @return success and array of sport list 
     * Date: 22/11/2017
     */
    
    
    public function get_market_odds($getDataArr){
        
        
        $user_access_token = isset($getDataArr['user_access_token']) ? $getDataArr['user_access_token'] : '';
        $match_id = isset($getDataArr['match_id']) ? $getDataArr['match_id'] : '';
        $tournament_id = isset($getDataArr['tournament_id']) ? $getDataArr['tournament_id'] : '';
        $count = isset($getDataArr['count']) ? $getDataArr['count'] : '0';
        $market_id = isset($getDataArr['market_id']) ? $getDataArr['market_id'] : '';

        try {

            if (!empty($user_access_token)) {
                
                $user = $this->check_login_user($user_access_token);
                $user_id = $user['id'];
                $limit = 10;
                
        
                $page = isset($count) ? $count : 0;
                $next = $page + $limit;
                
                if(!empty($market_id) && empty($tournament_id))
                {
                      $newArr=array();
                      $newDetailArr=array();
                      $marketInfo=$this->Sport_model->fetch_data('tbl_markets','market_name',array('where'=>array('id'=>$market_id)),true);
                   if($marketInfo['market_name']=='3way')
                   {
                           $mtype='2';
                           $marketResArr=$this->Sport_model->get_market_odds($match_id,$limit,$page,$market_id);
                   }
                   else if($marketInfo['market_name']=='2way')
                   {
                           $mtype='3';
                           $marketResArr=$this->Sport_model->get_market_odds($match_id,$limit,$page,$market_id);
                   }
                   else if($marketInfo['market_name']=='total')
                   {
                           $mtype='4';
                           $marketResArr=$this->Sport_model->get_market_odds_total($match_id,$limit,$page,$market_id);
                           
                           //print_r($marketResArr); die;
                   }
                   else if($marketInfo['market_name']=='spread')
                   {
                           $mtype='5';
                           $marketResArr=$this->Sport_model->get_market_odds_spread($match_id,$limit,$page,$market_id);
                           
                           //print_r($marketResArr); die;
                   }
                   else if($marketInfo['market_name']=='asian_handicap')
                   {
                           $mtype='6';
                           $marketResArr=$this->Sport_model->get_market_odds_ahandicap($match_id,$limit,$page,$market_id);
                           
                           //print_r($marketResArr); die;
                   }
                   else if($marketInfo['market_name']=='handicap')
                   { 
                           $mtype='7';
                           $marketResArr=$this->Sport_model->get_market_odds_handicap($match_id,$limit,$page,$market_id);
                           
                           //print_r($marketResArr); die;
                           
                   }
                   
                       
                }
                else{
                    
                            $newArr=array();
                            $newDetailArr=array();
                           if(empty($tournament_id))
                                    $marketResArr=$this->Sport_model->get_market_odds($match_id,$limit,$page,$market_id);
                           if(!empty($tournament_id))
                                     $marketResArr=$this->Sport_model->get_tournament_odds($tournament_id,$limit,$page,$market_id);
                                     $mtype='8';
                                     
                                     //print_r($marketResArr); die;     
                    
                }

             
                if (!empty($marketResArr['result_arr'])) {
                    
                    if ($marketResArr['total_record'] <= $next) {
                                   $next = -1;
                        }
                        
                        if(empty($tournament_id) && ($mtype=='2' || $mtype=='3' || $mtype=='8'))
                        {
                            $comptitorInfo=$this->Sport_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$match_id)),false);
                            $comNameArr=array_column($comptitorInfo,'competitor_name');
                            $compitetorName=!empty($comNameArr)?implode(' Vs ',$comNameArr):'';
                            $tournamentDetail=$this->Sport_model->getTournamentDetail($match_id);
                            
                            $newArr=$marketResArr['result_arr'];
                            
                        }
                        
                        if(empty($tournament_id) && ($mtype=='4'))
                        {
                            foreach($marketResArr['result_arr'] as $mresult){
                                
                                 $key=$mresult['type'].' '.$mresult['total'];
                                 $newVal['type']=$key;
                                 $newVal['odds']=$mresult['odds'];
                                 $newVal['book_id']=$mresult['book_id'];
                                //$neres[$key]=$this->Sport_model->getOutcomeDetail($market_id,$mresult['type'],$mresult['total']);
                                 $keyArr=$this->Sport_model->getOutcomeDetail($market_id,$mresult['type'],$mresult['total']);
                                 $newVal['book_arr']=$keyArr;
                                 $newArr[]=$newVal;
                            }
                            //$comptitorInfo=$this->Sport_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$match_id)),false);
                            //$comNameArr=array_column($comptitorInfo,'competitor_name');
                            //$compitetorName=!empty($comNameArr)?implode(' Vs ',$comNameArr):'';
                            //$tournamentDetail=$this->Sport_model->getTournamentDetail($match_id);
                            
                            //$newArr=$marketResArr['result_arr'];
                            
                            //print_r($newArr); die;
                            
                        }
                        
                        if(empty($tournament_id) && ($mtype=='5'))
                        {
                            foreach($marketResArr['result_arr'] as $mresult){
                                
                                $comptitorInfo=$this->Sport_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$match_id,'qualifier'=>$mresult['type'])),true);
                                $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$mresult['type'];
                                $key=$comName.' '.$mresult['spread'];
                                $newVal['type']=$key;
                                $newVal['odds']=$mresult['odds'];
                                $newVal['book_id']=$mresult['book_id'];
                                //$neres[$key]=$this->Sport_model->getOutcomeDetail($market_id,$mresult['type'],$mresult['total']);
                                $keyArr=$this->Sport_model->getOutcomeDetail($market_id,$mresult['type'],'',$mresult['spread']);
                                $newVal['book_arr']=$keyArr;
                                $newArr[]=$newVal;
                            }
                            
                        }
                        
                        if(empty($tournament_id) && ($mtype=='6'))
                        {
                            foreach($marketResArr['result_arr'] as $mresult)
                             {
                                
                                $comptitorInfo=$this->Sport_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$match_id,'qualifier'=>$mresult['type'])),true);
                                $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$mresult['type'];
                                $key=$comName.' '.$mresult['handicap'];
                                $newVal['type']=$key;
                                $newVal['odds']=$mresult['odds'];
                                $newVal['book_id']=$mresult['book_id'];
                                //$neres[$key]=$this->Sport_model->getOutcomeDetail($market_id,$mresult['type'],$mresult['total']);
                                $keyArr=$this->Sport_model->getOutcomeDetail($market_id,$mresult['type'],'','',$mresult['handicap']);
                                $newVal['book_arr']=$keyArr;
                                $newArr[]=$newVal;
                            }
                            
                        }
                        
                        
                        if(empty($tournament_id) && ($mtype=='7'))
                        {
                            foreach($marketResArr['result_arr'] as $mresult)
                             {
                                $handicapArr=explode(':',$mresult['handicap']);
                                if(!empty($handicapArr) && $handicapArr[0]<$handicapArr[1])
                                {
                                    $newVal['odds']=$mresult['odds'];
                                    $newVal['book_id']=$mresult['book_id'];
                                    $typeArr=explode(',',$mresult['type']);
                                    
                                    if(!empty($typeArr)){
                                        foreach($typeArr as $type)
                                        {
                                            if(!empty($type))
                                            {
                                                if($type=='draw')
                                                     $typen='home';
                                                else 
                                                     $typen=$type;
                                               
                                                    $comptitorInfo=$this->Sport_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$match_id,'qualifier'=>$typen)),true);
                                                  
                                                    if($type=='home')
                                                    {
                                                      $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$type;
                                                      $key=$comName.' +1 ';
                                                      $newVal['type']=$key;
                                                    }
                                                    if($type=='away')
                                                    {
                                                      $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$type;
                                                      $key=$comName.' -1 ';
                                                      $newVal['type']=$key;
                                                    }
                                                    if($type=='draw')
                                                    {
                                                      $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$type;
                                                       $key="Draw"."($comName +1)";
                                                      $newVal['type']=$key;
                                                    }
                                                      
                                                  
                                
                                                  $keyArr=$this->Sport_model->getOutcomeDetail($market_id,$type,'','','');
                                                  $newVal['book_arr']=$keyArr;
                                                  $newArr[]=$newVal;
                                            }
                                           
                                        }
                                    }
                                    
                                    
                                }
                                
                                if(!empty($handicapArr) && $handicapArr[0]>$handicapArr[1])
                                {
                                    
                                    $newVal['odds']=$mresult['odds'];
                                    $newVal['book_id']=$mresult['book_id'];
                                    $typeArr=explode(',',$mresult['type']);
                                    
                                    if(!empty($typeArr)){
                                        foreach($typeArr as $type)
                                        {
                                            if(!empty($type))
                                            {
                                                if($type=='draw')
                                                     $typen='home';
                                                else 
                                                     $typen=$type;
                                               
                                                    $comptitorInfo=$this->Sport_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$match_id,'qualifier'=>$typen)),true);
                                                  
                                                    if($type=='home')
                                                    {
                                                      $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$type;
                                                      $key=$comName.' -1 ';
                                                      $newVal['type']=$key;
                                                    }
                                                    if($type=='away')
                                                    {
                                                      $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$type;
                                                      $key=$comName.' +1 ';
                                                      $newVal['type']=$key;
                                                    }
                                                    if($type=='draw')
                                                    {
                                                      $comName=!empty($comptitorInfo['competitor_name'])?$comptitorInfo['competitor_name']:$type;
                                                       $key="Draw"."($comName -1)";
                                                      $newVal['type']=$key;
                                                    }
                                                      
                                                  
                                
                                                  $keyArr=$this->Sport_model->getOutcomeDetail($market_id,$type,'','','');
                                                  $newVal['book_arr']=$keyArr;
                                                  $newArr[]=$newVal;
                                            }
                                           
                                        }
                                    }
                                    
                                    
                                }
                                
                                
                            }
                            
                        }
                        
                        if(!empty($tournament_id) && empty($match_id))
                        {
                            foreach($marketResArr['result_arr'] as $mresArr)
                            {
                                 $oddDetail['comptitors_id']=$mresArr['comptitors_id'];
                                 $oddDetail['bookies_id']=$mresArr['bookies_id'];
                                 $oddDetail['name']=$mresArr['name'];
                                 $oddDetail['odds']=$mresArr['odds'];
                                 $oddDetail['book_name']=$mresArr['book_name'];
                                 $oddDetail['tournament_name']=$mresArr['tournament_name'];
                                 $getCompOdds=$this->Sport_model->fetch_data('tbl_comptitor_list','bookies_id,odds',array('where'=>array('comptitors_id'=>$mresArr['comptitors_id'],'tournament_id'=>$tournament_id)),false);
                                 $oddDetail['bookie_detail']=$getCompOdds;
                                 $newArr[]=$oddDetail;
                                
                            }
                            
                        }

                    $errorMsgArr = array();
                    $errorMsgArr['CODE'] = SUCCESS_CODE;
                    $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                    $errorMsgArr['MESSAGE'] = $this->lang->line('record_found');
                    $errorMsgArr['VALUE'] = $newArr;
                    $errorMsgArr['NEXT']=$next;
                    $errorMsgArr['COMPETITOR']=!empty($compitetorName)?$compitetorName:'';
                    $errorMsgArr['TOURNAMENT']=!empty($tournamentDetail['tournament_name'])?$tournamentDetail['tournament_name']:'';
                    if(!empty($tournament_id))
                        $errorMsgArr['TYPE']='1';
                    if(empty($tournament_id))
                        $errorMsgArr['TYPE']=!empty($mtype)?$mtype:'0';

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
     * Function: get_market_list
     * @access	public
     * Description: list all markets for a match.
     * @return success and array of sport list 
     * Date: 5/12/2017
     */
    
    
    public function get_market_list($getDataArr)
    {
       
        $user_access_token = isset($getDataArr['user_access_token']) ? $getDataArr['user_access_token'] : '';
        $match_id = isset($getDataArr['match_id']) ? $getDataArr['match_id'] : '';
        $tournament_id = isset($getDataArr['tournament_id']) ? $getDataArr['tournament_id'] : '';
        $count = isset($getDataArr['count']) ? $getDataArr['count'] : '0';
        

        try {

            if (!empty($user_access_token)) {
                
                $user = $this->check_login_user($user_access_token);
                $user_id = $user['id'];
                $limit = 10;
                
        
                $page = isset($count) ? $count : 0;
                $next = $page + $limit;

                           $marketArr=array();
                           $marketArr = $this->Sport_model->get_match_markets($match_id,$limit,$page,$tournament_id);
                 if(!empty($marketArr['result_arr']))
                 {
                     
                     if ($marketArr['total_record'] <= $next) {
                                   $next = -1;
                        }
                     
                     $errorMsgArr = array();
                     $errorMsgArr['CODE'] = SUCCESS_CODE;
                     $errorMsgArr['APICODERESULT'] = $this->lang->line('api_success');
                     $errorMsgArr['MESSAGE'] = $this->lang->line('record_found');
                     $errorMsgArr['VALUE'] = !empty($marketArr['result_arr'])?$marketArr['result_arr']:array();
                     $errorMsgArr['NEXT']=$next;
                     
                     $this->response($errorMsgArr);
                             
                 }
                 else {

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
