<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'View_Manager.php';
require APPPATH . 'libraries/src/TwitterOAuth.php';

//require APPPATH . 'libraries/OAuth.php';
//require APPPATH . 'libraries/twitteroauth.php';

class Home extends View_Manager {

    public function __construct() {
        parent::__construct();

        $this->load->model('Web_model');
        $this->load->model('Sport_model');
        $this->load->helper(array('cookie', 'url', 'language'));
        $this->load->model('Home_model');
        $this->load->library('session');
    }

    protected function _authCheck() {

        if ($this->session->userdata('user_id') == "") {
            redirect(SITE_URL . '/web');
        }
    }

    public function basketball() {



        $get = $this->input->get();
        $value=array();
        $newArr=array();
		$newDetailArr =[];
        $sportslist=$this->Web_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        $value['country_code'] = $this->Web_model->countryCode();

        $id=!empty($get['id'])?base64_decode(trim($get['id'])):'';
        $tour_id=!empty($get['tour_id'])?base64_decode(trim($get['tour_id'])):'';
        if($tour_id=='all')
            $tour_id='';

        $tournament_arr=$this->Web_model->get_week_tournament($id);
        $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
        $event_arr=$this->Web_model->get_sports_odds($id,'','',$tour_id);
        // print_r($event_arr); die;


        if(!empty($event_arr['result_arr']))
        {

            foreach($event_arr['result_arr'] as $sportr)
            {
                $msportr['schedule_date']=strtotime($sportr['scheduled']);
                $msportr['schedule_r_date']=$sportr['scheduled'];
                $detailArr=$this->Web_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);

                if(!empty($detailArr))
                {
                    foreach($detailArr as $detail)
                    {

                        $bookDetail=$this->Web_model->get_bookie_odds($detail['id']);
                        if(!empty($bookDetail)){

                            //print_r($bookDetail);
                            $book=!empty($bookDetail['book_id'])?$bookDetail['book_id']:'';
                            $type=!empty($bookDetail['type'])?$bookDetail['type']:'';
                            $odds=!empty($bookDetail['odds'])?$bookDetail['odds']:'';

                            $detail['book_id']=$book;
                            $detail['type']=$type;
                            $detail['odds']=$odds;
                            $detail['match_time']=  date('h:i:s',strtotime($detail['match_time']));

                            //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                            //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';

                            $newDetailArr[]=$detail;
                        }


                    }
                }



                 if(!empty($newDetailArr) )
                {
                       $msportr['detail_arr']=$newDetailArr;
                       $newArr[]=$msportr;
                 }


            }

        }

       // echo "<pre>";
        //print_r($newArr); die;

        if(!empty($newDetailArr) )
         {
             $value['res_arr']=!empty($newArr)?$newArr:array();
         } else{
            $value['res_arr']=array();
        }


            //echo "<pre>";
           //print_r($value); die;


                            $this->html('basketball', $value);

    }
    public function tennis(){

        $get = $this->input->get();
        $value=array();
        $newArr=array();
        $newDetailArr =[];
        $sportslist=$this->Web_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        $value['country_code'] = $this->Web_model->countryCode();

        $id=!empty($get['id'])?base64_decode(trim($get['id'])):'';
        $tour_id=!empty($get['tour_id'])?base64_decode(trim($get['tour_id'])):'';
        if($tour_id=='all')
            $tour_id='';

        $tournament_arr=$this->Web_model->get_week_tournament($id);
        $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
        $event_arr=$this->Web_model->get_sports_odds($id,'','',$tour_id);
        // print_r($event_arr); die;

        if(!empty($event_arr['result_arr']))
        {

            foreach($event_arr['result_arr'] as $sportr)
            {
                $msportr['schedule_date']=strtotime($sportr['scheduled']);
                $msportr['schedule_r_date']=$sportr['scheduled'];
                $detailArr=$this->Web_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);

                if(!empty($detailArr))
                {
                    foreach($detailArr as $detail)
                    {

                        $bookDetail=$this->Web_model->get_bookie_odds($detail['id']);
                        //print_r($bookDetail);
                        $book=!empty($bookDetail['book_id'])?$bookDetail['book_id']:'';
                        $type=!empty($bookDetail['type'])?$bookDetail['type']:'';
                        $odds=!empty($bookDetail['odds'])?$bookDetail['odds']:'';

                        $detail['book_id']=$book;
                        $detail['type']=$type;
                        $detail['odds']=$odds;
                        $detail['match_time']=  date('h:i:s',strtotime($detail['match_time']));

                        //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                        //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';

                        $newDetailArr[]=$detail;

                    }
                }


                if(!empty($newDetailArr) )
                {
                $msportr['detail_arr']=$newDetailArr;
                $newArr[]=$msportr;
                }


            }

        }

        //echo "<pre>";
        //print_r($newArr); die;

        if(!empty($newDetailArr))
         {
             $value['res_arr']=!empty($newArr)?$newArr:array();
         } else{
            $value['res_arr']=array();
        }


        $this->html('tennis', $value);



    }
    public function rugby() {


      // echo APPPATH . 'application/views/mail/';

        $data = $this->input->post();
        $get = $this->input->get();
        $value=array();
        $newArr=array();
        $newDetailArr =[];
        $sportslist=$this->Web_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }
        $value['sports'] = $sportslist;
        $value['country_code'] = $this->Web_model->countryCode();
        
       $id=!empty($get['id'])?base64_decode($get['id']):'';
       $tour_id=!empty($get['tour_id'])?base64_decode($get['tour_id']):'';
        
                            $tournament_arr=$this->Web_model->get_week_tournament($id);
                            $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
                            $event_arr=$this->Web_model->get_sports_odds($id,'','',$tour_id);
                            //print_r($event_arr); die;
                            
                            if(!empty($event_arr['result_arr']))
                            {
                                
                                foreach($event_arr['result_arr'] as $sportr)
                    {
                                        $msportr['schedule_date']=strtotime($sportr['scheduled']);
                                        $msportr['schedule_r_date']=$sportr['scheduled'];
                                        $detailArr=$this->Web_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);
                        
                      if(!empty($detailArr))
                      {
                            foreach($detailArr as $detail)
                            {
                              
                                $bookDetail=$this->Web_model->get_bookie_odds($detail['id']);
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
                        
                            if(!empty($newDetailArr) )
                {
                            $msportr['detail_arr']=$newDetailArr;  
                            $newArr[]=$msportr;
                }
                        
                        
                    }
                                
                            }
        
                    
                    
            if(!empty($newDetailArr))
			 {
				 $value['res_arr']=!empty($newArr)?$newArr:array();
			 } else{
				$value['res_arr']=array();
			}


                           // echo "<pre>";
                           // print_r($newArr); die;



        $this->html('rugby', $value);

    }
    
    public function football() {

       // $data = $this->input->post();
        $get = $this->input->get();
        $value=array();
        $newArr=array();
        $newDetailArr =[];
        $newDetailArr = [];
        $sportslist=$this->Web_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        $value['country_code'] = $this->Web_model->countryCode();

        $id=!empty($get['id'])?base64_decode(trim($get['id'])):'';
         $tour_id=!empty($get['tour_id'])?base64_decode(trim($get['tour_id'])):'';

        if($tour_id=='all')
            $tour_id='';

        $tournament_arr=$this->Web_model->get_week_tournament($id);
        $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
        $event_arr=$this->Web_model->get_sports_odds($id,'','',$tour_id);
       // print_r($event_arr); die;

        if(!empty($event_arr['result_arr']))
        {

            foreach($event_arr['result_arr'] as $sportr)
            {
                $msportr['schedule_date']=strtotime($sportr['scheduled']);
                $msportr['schedule_r_date']=$sportr['scheduled'];
                $detailArr=$this->Web_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);
//print_r($detailArr); exit;
                if(!empty($detailArr))
                {
                    $newDetailArr=array();
                    foreach($detailArr as $detail)
                    {

                        $bookDetail=$this->Web_model->get_bookie_odds($detail['id']);
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


                  if(!empty($newDetailArr) )
                {
                $msportr['detail_arr']=$newDetailArr;
                $newArr[]=$msportr;
                }
               // print_r($newDetailArr); exit;

            }

        }

        //echo "<pre>";
        //print_r($newArr); die;
         if(!empty($newDetailArr))
         {
             $value['res_arr']=!empty($newArr)?$newArr:array();
         } else{
            $value['res_arr']=array();
        }


        //echo "<pre>";
        //print_r($value['res_arr']); die;

        $this->html('football', $value);

    }
    
    public function cricket() {


      // echo APPPATH . 'application/views/mail/';

        $get = $this->input->get();
        $value=array();
        $newArr=array();
        $newDetailArr =[];
        $sportslist=$this->Web_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        $value['country_code'] = $this->Web_model->countryCode();

        $id=!empty($get['id'])?base64_decode(trim($get['id'])):'';
        $tour_id=!empty($get['tour_id'])?base64_decode(trim($get['tour_id'])):'';
        if($tour_id=='all')
            $tour_id='';

        $tournament_arr=$this->Web_model->get_week_tournament($id);
        $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
        $event_arr=$this->Web_model->get_sports_odds($id,'','',$tour_id);
        // print_r($event_arr); die;

        if(!empty($event_arr['result_arr']))
        {

            foreach($event_arr['result_arr'] as $sportr)
            {
                $msportr['schedule_date']=strtotime($sportr['scheduled']);
                $msportr['schedule_r_date']=$sportr['scheduled'];
                $detailArr=$this->Web_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);

                if(!empty($detailArr))
                {
                    foreach($detailArr as $detail)
                    {

                        $bookDetail=$this->Web_model->get_bookie_odds($detail['id']);
                        //print_r($bookDetail);
                        $book=!empty($bookDetail['book_id'])?$bookDetail['book_id']:'';
                        $type=!empty($bookDetail['type'])?$bookDetail['type']:'';
                        $odds=!empty($bookDetail['odds'])?$bookDetail['odds']:'';

                        $detail['book_id']=$book;
                        $detail['type']=$type;
                        $detail['odds']=$odds;
                        $detail['match_time']=  date('h:i:s',strtotime($detail['match_time']));

                        //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                        //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';

                        $newDetailArr[]=$detail;

                    }
                }


                  if(!empty($newDetailArr) )
                {
                $msportr['detail_arr']=$newDetailArr;
                $newArr[]=$msportr;
                }


            }

        }

        //echo "<pre>";
        //print_r($newArr); die;

        if(!empty($newDetailArr))
         {
             $value['res_arr']=!empty($newArr)?$newArr:array();
         } else{
            $value['res_arr']=array();
        }


        $this->html('cricket', $value);

    }
    
    public function golf() {


      // echo APPPATH . 'application/views/mail/';

        $get = $this->input->get();
        $value=array();
        $newArr=array();
        $newDetailArr =[];
        $sportslist=$this->Web_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        $value['country_code'] = $this->Web_model->countryCode();

        $id=!empty($get['id'])?base64_decode(trim($get['id'])):'';
        $tour_id=!empty($get['tour_id'])?base64_decode(trim($get['tour_id'])):'';
        if($tour_id=='all')
            $tour_id='';

        $tournament_arr=$this->Web_model->get_week_tournament($id);
        $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
        $event_arr=$this->Web_model->get_sports_odds($id,'','',$tour_id);
        // print_r($event_arr); die;

        if(!empty($event_arr['result_arr']))
        {

            foreach($event_arr['result_arr'] as $sportr)
            {
                $msportr['schedule_date']=strtotime($sportr['scheduled']);
                $msportr['schedule_r_date']=$sportr['scheduled'];
                $detailArr=$this->Web_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);

                if(!empty($detailArr))
                {
                    foreach($detailArr as $detail)
                    {

                        $bookDetail=$this->Web_model->get_bookie_odds($detail['id']);
                        //print_r($bookDetail);
                        $book=!empty($bookDetail['book_id'])?$bookDetail['book_id']:'';
                        $type=!empty($bookDetail['type'])?$bookDetail['type']:'';
                        $odds=!empty($bookDetail['odds'])?$bookDetail['odds']:'';

                        $detail['book_id']=$book;
                        $detail['type']=$type;
                        $detail['odds']=$odds;
                        $detail['match_time']=  date('h:i:s',strtotime($detail['match_time']));

                        //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                        //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';

                        $newDetailArr[]=$detail;

                    }
                }


                 if(!empty($newDetailArr) )
                {
                $msportr['detail_arr']=$newDetailArr;
                $newArr[]=$msportr;
                }


            }

        }

        //echo "<pre>";
        //print_r($newArr); die;

        if(!empty($newDetailArr))
         {
             $value['res_arr']=!empty($newArr)?$newArr:array();
         } else{
            $value['res_arr']=array();
        }




        $this->html('golf', $value);

    }
    
    public function boxing() {


      // echo APPPATH . 'application/views/mail/';

        $get = $this->input->get();
        $value=array();
        $newArr=array();
        $newDetailArr =[];
        $sportslist=$this->Web_model->getSports();
        foreach ($sportslist as $key=>$sportvalues){

            $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
        }

        $value['sports'] = $sportslist;
        $value['country_code'] = $this->Web_model->countryCode();

        $id=!empty($get['id'])?base64_decode(trim($get['id'])):'';
        $tour_id=!empty($get['tour_id'])?base64_decode(trim($get['tour_id'])):'';
        if($tour_id=='all')
            $tour_id='';

        $tournament_arr=$this->Web_model->get_week_tournament($id);
        $value['tournament_arr']=!empty($tournament_arr)?$tournament_arr:array();
        $event_arr=$this->Web_model->get_sports_odds($id,'','',$tour_id);
        // print_r($event_arr); die;

        if(!empty($event_arr['result_arr']))
        {

            foreach($event_arr['result_arr'] as $sportr)
            {
                $msportr['schedule_date']=strtotime($sportr['scheduled']);
                $msportr['schedule_r_date']=$sportr['scheduled'];
                $detailArr=$this->Web_model->get_sport_detail(explode(',',$sportr['events']),$sportr['scheduled']);

                if(!empty($detailArr))
                {
                    foreach($detailArr as $detail)
                    {

                        $bookDetail=$this->Web_model->get_bookie_odds($detail['id']);
                        //print_r($bookDetail);
                        $book=!empty($bookDetail['book_id'])?$bookDetail['book_id']:'';
                        $type=!empty($bookDetail['type'])?$bookDetail['type']:'';
                        $odds=!empty($bookDetail['odds'])?$bookDetail['odds']:'';

                        $detail['book_id']=$book;
                        $detail['type']=$type;
                        $detail['odds']=$odds;
                        $detail['match_time']=  date('h:i:s',strtotime($detail['match_time']));

                        //$venueArr=$this->Sport_model->fetch_data('tbl_venue','',array('where'=>array('event_id'=>$detail['match_id'])),true);
                        //$detail['venue_name']=isset($venueArr['venue_name'])?$venueArr['venue_name']:'';

                        $newDetailArr[]=$detail;

                    }
                }


                 if(!empty($newDetailArr) )
                {
                $msportr['detail_arr']=$newDetailArr;
                $newArr[]=$msportr;
                }


            }

        }

        //echo "<pre>";
        //print_r($newArr); die;

        if(!empty($newDetailArr))
         {
             $value['res_arr']=!empty($newArr)?$newArr:array();
         } else{
            $value['res_arr']=array();
        }



        $this->html('boxing', $value);

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

    

    function loadMore(){

        try {

            if ($this->input->is_ajax_request()) {
                $page=$this->input->get('page');
                $sport_id=$this->input->get('sport_id');
                $decodedSportId=base64_decode($sport_id);
                $SportId=isset($decodedSportId) && !empty($decodedSportId)?$decodedSportId:'';
                if (!empty($page)) {


                    $pageno = (($page-1) * (WEB_RECORDS_PER_PAGE));

                } else {

                    $pageno = 1;
                }


                    $data= $this->Web_model->loadMoreData($SportId,WEB_RECORDS_PER_PAGE,$pageno);
                    echo $data;

            }else{

                    return show_404();
            }

        }catch (Exception $ex){

            return $ex->getMessage();
        }
    }

    public function loadMoredata(){

        try {

            $data=$this->input->get();

            if ($this->input->is_ajax_request()) {
                $page=$this->input->get('page');
                $sport_id=$this->input->get('sport_id');
                $decodedSportId=base64_decode($sport_id);
                $SportId=isset($decodedSportId) && !empty($decodedSportId)?$decodedSportId:'';
                if (!empty($page)) {


                    $pageno = (($page-1) * (WEB_RECORDS_PER_PAGE));

                } else {

                    $pageno = 1;
                }


                $data= $this->Web_model->recordsLoadMore($SportId,WEB_RECORDS_PER_PAGE,$pageno);
                echo $data;

            }else{

                return show_404();
            }

        }catch (Exception $ex){

            return $ex->getMessage();
        }

    }

    public function view_odds()
    {
        $data = [];
        $bookie_id = [];
        try{

            $id=$this->input->get('id');
            $decodedId=base64_decode(trim($id));
            $tournamentId=isset($decodedId)?$decodedId:'';
            $sportslist=$this->Web_model->getSports();
            $bookieslist=$this->Web_model->getBokkiesList();
            foreach ($bookieslist as $bkey=>$bvalues){

                $bookie_id[]=$bvalues->book_id;
            }
            $data['bookie_id'] = $bookie_id;

            foreach ($sportslist as $key=>$sportvalues){

                $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
            }


            $data['sports'] = $sportslist;


                $data['oddslist'] =$this->Web_model->getAllComptitorsOdds($tournamentId);



            //echo '<pre>';
            //print_r($data['oddslist']); exit;

        }catch (Exception $ex){

            $data['exception']=$ex->getMessage();
        }


        $this->html('view-odds',$data);

    }

    public function match_odds()
    {
        $data = [];
        $bookie_id = [];
        try{


            $decodedMatchId=trim(base64_decode($this->input->get('matchId')));
            $marketId=trim(base64_decode($this->input->get('id')));
            $marketName=trim(base64_decode(trim($this->input->get('market_name'))));
            $MatchId=isset($decodedMatchId)?$decodedMatchId:'';
            $sportslist=$this->Web_model->getSports();
            $bookieslist=$this->Web_model->getBokkiesList();
            foreach ($bookieslist as $bkey=>$bvalues){

                $bookie_id[]=$bvalues->book_id;
            }
            $data['bookie_id'] = $bookie_id;

            foreach ($sportslist as $key=>$sportvalues){

                $sportvalues->sport_id=base64_encode($sportvalues->sport_id);
            }


            $data['sports'] = $sportslist;
            //$data['oddslist']=$this->Web_model->get_bookie_odds(1);
            /*'sr:match:12841404'*/
           
                $data['market_name'] =$this->Web_model->MarketNamebyMatchId($MatchId);
                
                
                $data['oddslist'] =$this->Web_model->ComptitorsOddsbyMatchId($marketId,$MatchId);
             //echo '<pre>';
            //print_r($data['oddslist']); exit;
        }catch (Exception $ex){

            $data['exception']=$ex->getMessage();
        }


        $this->html('match_odds',$data);

    }

}
