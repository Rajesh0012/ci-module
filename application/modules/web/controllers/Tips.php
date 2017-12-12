<?php

if (!defined('BASEPATH'))
{
  exit('No direct script access allowed');  
}
    

require_once 'View_Manager.php';

class Tips extends View_Manager {

    public function __construct() {
        parent::__construct();

        $this->load->model('Web_model');
        $this->load->model('Sport_model');
        $this->load->helper(array('cookie', 'language'));
        $this->load->helper('url');
        $this->load->model('Home_model');
        $this->load->library('session');
    }

    protected function _authCheck() {

        if ($this->session->userdata('user_id') == "") {
            redirect(SITE_URL . '/web');
        }
    }

    public function index() {

        try {

            $value = array();
            $tipDetail = array();
            $newDetailArr = array();
            $sportslist = [];
            $sportslist = $this->Web_model->getSports();
            $tipsArr = $this->Home_model->get_grouped_tips();

            if (!empty($tipsArr)) {

                foreach ($tipsArr as $tip) {
                    
                    $tipDetail['sport_name'] = $tip['sport_name'];
                    $tipDetail['sport_id'] = $tip['sports'];
                    $tipDetail['tips_list'] = $this->Web_model->fetch_data('tbl_free_tips', 'id,title,image', array('where' => array('sports' => $tip['sports']), 'limit' => array('5')), false);

                    $newDetailArr[] = $tipDetail;
                    
                }
            }

            if (!empty($sportslist)) {

                foreach ($sportslist as $key => $sportvalues) {

                    $sportvalues->sport_id = base64_encode($sportvalues->sport_id);
                }
            }


            $value['sports'] = $sportslist;
            $value['all_tips_arr'] = $newDetailArr;

            //echo "<pre>";
            //print_r($value); die;
            $this->html('free_tips', $value);
        } catch (Exception $ex) {

            return $ex->getMessage();
        }
    }

    public function loadMoredata() {

        try {
            $data = $this->input->get();

            if ($this->input->is_ajax_request()) {
                $page = $this->input->get('page');
                $sport_id = $this->input->get('sport_id');
                $decodedSportId = base64_decode($sport_id);
                $SportId = isset($decodedSportId) && !empty($decodedSportId) ? $decodedSportId : '';
                if (!empty($page)) {


                    $pageno = (($page - 1) * (WEB_RECORDS_PER_PAGE));
                } else {

                    $pageno = 1;
                }

                $data = $this->Web_model->recordsLoadMore($SportId, WEB_RECORDS_PER_PAGE, $pageno);
                echo $data;
            } else {

                return show_404();
            }
        } catch (Exception $ex) {

            return $ex->getMessage();
        }
    }
    
    public function sportsTips(){
        
        $sportslist = $this->Web_model->getSports();
        $sportId =  base64_decode($this->input->get('sport_id'));
        if (!empty($sportslist)) {

                foreach ($sportslist as $key => $sportvalues) {

                    $sportvalues->sport_id = base64_encode($sportvalues->sport_id);
                }
        }
            $records = $this->Web_model->getRelatedTipsViaSportId('','',$sportId);
             
            $paged =$records['num_of_records']; 
            $config = array();
            $data['available_users'] = $paged;
            $config['cur_tag_open'] = '<a class="active">';
            $config['cur_tag_close'] = '</a>';
            $config['base_url'] = site_url().'/web/tips/sportsTips?sport_id='.$this->input->get('sport_id');
            $config['total_rows'] = $paged;
            $config['use_page_numbers'] = TRUE;
            //$config['num_links'] = NUM_LINKS;
            $config['enable_query_strings'] = true;
            $config['page_query_string']=true;
            $this->pagination->initialize($config);
            //print_r($config); exit; 
            if(isset($_GET['per_page']) && is_numeric($this->input->get('per_page'))){
                $pageno=$this->input->get('per_page');
            }

            if (!empty($this->input->get('per_page'))) {


                $data['page'] = (($pageno-1) * (TIPS_RECORDS_PER_PAGE));

            } else {

                $data['page'] = 0;
            }
            
            $data['tips']= $this->Web_model->getTipsViaSportId($data['page'],$sportId);
            $data['relatedTips'] = $this->Web_model->getRelatedTipsViaSportId(TIPS_RECORDS_PER_PAGE, $data['page']+2,$sportId);
             
            $data['sports']=$sportslist;
            $data['pagination']  = $this->pagination->create_links();
           View_Manager::html('sports-tips',$data);
    }   
    
    public function tips_details(){
        
       
        $id =  base64_decode($this->input->get('id'));
        
         $sportslist = $this->Web_model->getSports();
        
        if (!empty($sportslist)) {

                foreach ($sportslist as $key => $sportvalues) {

                    $sportvalues->sport_id = base64_encode($sportvalues->sport_id);
                }
        }
           $data['sports'] = $sportslist;
           $tipDetailArr= $this->Web_model->tips_details($id);
        
        
                       $oddsRes=array();
                        if($tipDetailArr->type=='2')
                            {
                                
                                $oddsRes=$this->Sport_model->fetch_data('tbl_tips_odds','odd,odd_desc,position',array('where'=>array('tip_id'=>$tipDetailArr->id)),false);
                                //print_r($oddsRes);
                                $newOresArr=array();
                                if(!empty($oddsRes)){
                                    foreach($oddsRes as $ores){
                                        
                                        $mTypeArr=$this->Sport_model->fetch_data('tbl_market_outcome','type,deep_link',array('where'=>array('market_id'=>$tipDetailArr->market_id,'odds'=>$ores['odd'])),true);
                                        
                                        if(!empty($mTypeArr))
                                        {
                                            
                                            if($mTypeArr['type']=='home' || $mTypeArr['type']=='away')
                                        {
                                            $comptetorArr = $this->Sport_model->fetch_data('tbl_competitors', 'competitor_name,qualifier', array('where' => array('event_id' => $tipDetailArr->event_id,'qualifier'=>trim($mTypeArr['type']))), true);
                                            $ores['competitor_name']=!empty($comptetorArr['competitor_name'])?$comptetorArr['competitor_name']:'';
                                        }
                                        if($mTypeArr['type']=='draw')
                                        {
                                            $comptetorArr = $this->Sport_model->fetch_data('tbl_competitors', 'competitor_name,qualifier', array('where' => array('event_id' => $tipDetailArr->event_id)), false);
                                            $nameArr = array_column($comptetorArr, 'competitor_name');
                                            $implname=implode(' Vs ',$nameArr);
                                            $ores['competitor_name']=!empty($implname)?$implname:'';
                                        }
                                            $ores['type']=!empty($mTypeArr['type'])?$mTypeArr['type']:'';
                                            $ores['deep_link']=!empty($mTypeArr['deep_link'])?$mTypeArr['deep_link']:'';
                                            
                                        }
                                        else{
                                                 $ores['competitor_name']='';
                                                 $ores['type']='';
                                                 $ores['deep_link']='';
                                        }
                                        
                                        
                                            $newOresArr[]=$ores;
                                    }
                                }
                                
                                $tipDetailArr->bet_odds=!empty($newOresArr)?$newOresArr:array();
                                 
                            }
                            
                            //$newResArr[]=$tipDetailArr;
                        
                        $data['tips_details']=$tipDetailArr;
        //echo "<pre>";
        //print_r($data); die;
           
        View_Manager::html('view-tips',$data);
    }

}
