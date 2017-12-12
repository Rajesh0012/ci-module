<?php

require 'View_Manager.php';

class Home extends View_Manager {

    public function __construct() {

        parent::__construct();
    }

    /**
     * @Name _authCheck
     * @Date 09/11/2017
     * @Param No
     * @Desc check here admin login or not
     *
     * */
    protected function _authCheck() {

        if ($this->session->userdata('admin_id') == "") {
            redirect(SITE_URL . '/admin');
        }
    }

    /**
     * @Name homePage
     * @Date 13/11/2017
     * @Param No
     * @Desc home page related action are performing here like get sport listing added from odd listing get odds
     * details chosing tournament and particular match
     * @Return array to views
     * */
    function homePage() {

        $data = [];
        $this->_authCheck();
        $catId = [];
        $searchdata=array();
        $gameCategory='';
        $sdisplay='';
        try {

            
            $searchdata = $this->input->get();
            
            $config = array();
            $newDataArr=array();

            
            
             if (!empty($searchdata['delete'])) {
                foreach ($searchdata['delete'] as $delId) {

                    $del = $this->Admin_model->deleteAndBlock('addcomptitors', $delId, 'delete');
                }

                if ($del) {

                    $data['msg'] = $this->session->set_flashdata('msg', $this->lang->line('odd_deleted'));

                    redirect(SITE_URL . '/admin/Home/homePage');
                } else {

                    $data['msg'] = $del;
                }
            }
            
            $gameCategory = isset($searchdata['gamecategory']) ? '?gamecategory=' . trim($searchdata['gamecategory']) : '';
            $sdisplay = isset($searchdata['display']) ? '&display=' . trim($searchdata['display']) : '';
            $searchkeywords = isset($searchdata['searchkeywords']) ? '&searchkeywords=' . trim($searchdata['searchkeywords']) : '';
            $display = isset($searchdata['display']) ? trim($searchdata['display']) : '';
            $config['base_url'] = site_url() . '/admin/Home/homePage' . $gameCategory . $sdisplay.$searchkeywords;
            
            if (isset($display) && !empty(trim($display))) {

                $config['per_page'] = $display;
            } else {

                $config['per_page'] = RECORDS_PER_PAGE;
            }


             if (isset($_GET['per_page']) && is_numeric($this->input->get('per_page'))) {
                $pageno = $this->input->get('per_page');
            }

            if (!empty($this->input->get('per_page'))) {


                $data['page'] = (($pageno - 1) * (!empty($display) ? $display : RECORDS_PER_PAGE));
            } else {

                $data['page'] = 0;
            }
            
            //$total_row = $this->Admin_model->count_odds($searchdata);
            $dataArr= $this->Admin_model->getOdds($config['per_page'], $data['page'], $searchdata);
            
            if(!empty($dataArr['result_arr'])){
                
                foreach($dataArr['result_arr'] as $mdata)
                {
                             if(!empty($mdata->match_id))
                            {
                              if(!empty($mdata->comptitors_id)){
                                        $comArr=$this->Admin_model->fetch_data('tbl_competitors','',array('where'=>array('competitor_id'=>$mdata->comptitors_id)),true);
                                        $mdata->competitor_name=!empty($comArr['competitor_name'])?$comArr['competitor_name']:'';
                              }
                              else{
                                       $comptitorDetail=$this->Admin_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$mdata->match_id)),false);
                                       $comNameArr=array_column($comptitorDetail,'competitor_name');
                                       $compitetorName=!empty($comNameArr)?implode(' Vs ',$comNameArr):'';
                                       $mdata->competitor_name='Draw ('.$compitetorName.')';   
                              }
                            }
                            
                      $newDataArr[]=$mdata;      
                }
            }
            
            $data['odds'] = !empty($newDataArr)?$newDataArr:'';
            $total_row = !empty($dataArr['total_res'])?$dataArr['total_res']:'0';
            $paged = $total_row;
            $data['available_odds'] = $paged;
            
            $config['total_rows'] = $paged;
            $config['use_page_numbers'] = TRUE;
            $config['num_links'] = NUM_LINKS;
            $config['enable_query_strings'] = true;
            $config['page_query_string'] = true;
            $this->pagination->initialize($config);
            
            $data['pagination'] = $this->pagination->create_links();
            $data['search'] = $searchdata;
            $data['sports'] = $this->Admin_model->fetch_data('tbl_sports', '', array('where' => array('status' => '1')), false);

            //echo "<pre>";
            ///print_r($data); die;
            $this->html('home', $data);
        } catch (Exception $ex) {

            $data['msg'] = $ex->getMessage();
        }
    }

    /**
     * @Name getEvents
     * @Date 16/11/2017
     * @Param No
     * @Desc  get tournament odds details by match or tournament
     * @Return array to Views
     * */
    public function getEvents() {

        $this->_authCheck();
        $data = [];
        $paged = '';
        $total_row = '';
        $id = '';
        $tournament_id = '';
        $matchId = '';
        $competitor_id = '';
        try {


            $tid = base64_decode($this->input->get('tid', TRUE));
            $tournament_id = base64_decode($this->input->get('tournament_id', TRUE));
            $filter = $this->input->get('filter');
            if (isset($_GET['matchId']) && !empty($this->input->get('matchId')) && $filter == 2) {

                $matchId = base64_decode($this->input->get('matchId'));
                //$match_id = base64_decode($this->input->get('matchId', TRUE));
                $markets = base64_decode($this->input->get('markets', TRUE)); //die;
            }


            if (isset($tid) && !empty($tid)) {


                $total_row = $this->Admin_model->countComptitors($tid);
                $bookArr = $this->Admin_model->fetch_data('tbl_books', '', array('where' => array('status' => '1')), false);

                if (!empty($total_row)) {

                    foreach ($total_row as $totalpage) {

                        $paged = $totalpage->id;
                    }
                }


                $config = array();
                $newComArr=array();
                $data['available_users'] = 19;

                $config['base_url'] = site_url() . '/admin/Home/getEvents?tid=' . base64_encode($tid);
                $config['total_rows'] = 19;


                $config['per_page'] = COMPTITORS_PER_PAGE;

                $config['use_page_numbers'] = TRUE;
                $config['num_links'] = 5;
                $config['enable_query_strings'] = true;
                $config['page_query_string'] = true;
                $this->pagination->initialize($config);

                if (isset($_GET['per_page']) && is_numeric($this->input->get('per_page'))) {
                    $pageno = $this->input->get('per_page');
                }

                if (!empty($this->input->get('per_page'))) {

                    $data['page'] = (($pageno - 1) * (COMPTITORS_PER_PAGE));
                } else {

                    $data['page'] = 0;
                }

                $data['tournament_id'] = !empty($tournament_id)?$tournament_id:$tid;
                $data['match_id'] = $matchId;
                $data['book_arr'] = $bookArr;
                $data['pagination'] = $this->pagination->create_links();
                if (!empty($matchId) && !empty($markets)) {

                     $comResultArr = $this->Admin_model->getMatcheMarketOdds(explode(',', $markets));
                     //$comptitorArr= !empty($comResultArr) ? $comResultArr : array();
                     
                     if(!empty($comResultArr)){
                         
                         foreach($comResultArr as $comres){
                             
                             $bookexp = explode('|+|', $comres->bookies);
                             $bookodds = explode('|+|', $comres->odds);
                             if($comres->name=='home' || $comres->name=='away')
                                $comptitorDetail=$this->Admin_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$comres->match_id,'qualifier'=>$comres->name)),true);
                            else 
                                $comptitorDetail=$this->Admin_model->fetch_data('tbl_competitors','competitor_id,competitor_name',array('where'=>array('event_id'=>$comres->match_id)),false);
                            
                            
                            
                            //print_r($comptitorDetail); ;
                             unset($comres->bookies);
                             unset($comres->odds);
                            $comres->odds=$bookodds;
                            $comres->bookies=$bookexp;
                            if($comres->name=='home' || $comres->name=='away')
                            {
                               $comres->compitetor_name=!empty($comptitorDetail['competitor_name'])?$comptitorDetail['competitor_name']:'';
                               $comres->comptitors_id=!empty($comptitorDetail['competitor_id'])?$comptitorDetail['competitor_id']:'';
                            }
                            
                            if($comres->name=='draw'){
                                $comNameArr=array_column($comptitorDetail,'competitor_name');
                                //$comres->compitetor_name=!empty($comNameArr)?implode(' Vs ',$comNameArr):'';
                                $comres->compitetor_name='Draw';
                            }
                            $newComArr[]=$comres;
                         }
                       }
                     ///die;
                      $data['comptitor']=$newComArr;   
                    //echo "<pre>";
                    //print_r($comResultArr); die;
                } else {

                     $comptitorArr= $this->Admin_model->getComptitorOdds($config['per_page'], $data['page'], $tid, $matchId);
                     
                     if(!empty($comptitorArr))
                     {
                        foreach($comptitorArr as $com){
                            
                            $bookexp = explode('|+|', $com->bookies);
                            $oddsArr=$this->Admin_model->fetch_data('tbl_comptitor_list','odds',array('where'=>array('comptitors_id'=>$com->comptitors_id,'tournament_id'=>$com->tournament_id),'where_in'=>array('bookies_id'=>$bookexp)),false);
                            $nodds = array_column($oddsArr, 'odds');
                            
                            

                            unset($com->bookies);
                            $com->odds=$nodds;
                            $com->bookies=$bookexp;
                            $newComArr[]=$com;
                            
                        } 
                        //die;
                     }
                     
                     $data['comptitor']=$newComArr;
                }

                //echo "<pre>";
            //print_r($data); die;

               /* if (!empty($data['comptitor'])) {

                    foreach ($data['comptitor'] as $key => $values) {

                        //$exp = explode('|+|', $values->odds);
                        $exp2 = explode('|+|', $values->bookies);
                        //$values->odds = $exp;
                        unset($values->bookies);
                        $values->bookies = $exp2;
                    }
                }*/
            } else {

                $data['mag'] = $this->lang->line('catid_blank');
            }
            
            
        } catch (Exception $ex) {

            echo $ex->getMessage();
        }

        //echo "<pre>";
        //print_r($data); die;


        View_Manager::html('event-list', $data);
    }

    /**
     * @Name addComptitors
     * @Date 16/11/2017
     * @Param No
     * @Desc  add comptitr to admin and web and show there odds
     * @Return array to Views
     * */
    function addComptitors() {

        $thisValues = '';
        $exist = '';

        $this->_authCheck();

        if ($this->input->is_ajax_request()) {


            try {

                $adddata = $this->input->get();
                $imp = implode('|=|', $adddata);
                $exp = explode('|=|', $imp);
               //print_r($exp); 

                if (count($exp)>6) {
                    
                    $addDataArr['category_id'] = !empty($exp[0]) ? $exp[0] : '';
                    $addDataArr['comptitors_id'] = !empty($exp[1]) ? $exp[1] : '';
                    $addDataArr['bookies_id'] = !empty($exp[2]) ? $exp[2] : '';
                    $addDataArr['tournament_id'] = !empty($exp[3]) ? $exp[3] : '';
                    $addDataArr['match_id'] = !empty($exp[4]) ? $exp[4] : '';
                    $addDataArr['odds'] = !empty($exp[5]) ? $exp[5] : '';
                    $addDataArr['sport_id'] = !empty($exp[6]) ? $exp[6] : '';
                } else {

                    $addDataArr['category_id'] = !empty($exp[0]) ? $exp[0] : '';
                    $addDataArr['bookies_id'] = !empty($exp[1]) ? $exp[1] : '';
                    $addDataArr['tournament_id'] = !empty($exp[2]) ? $exp[2] : '';
                    $addDataArr['match_id'] = !empty($exp[3]) ? $exp[3] : '';
                    $addDataArr['odds'] = !empty($exp[4]) ? $exp[4] : '';
                    $addDataArr['sport_id'] = !empty($exp[5]) ? $exp[5] : '';
                }

                //print_r($addDataArr); die;
                if (empty($addDataArr['match_id'])) {

                    $exist = $this->Admin_model->checkExist($addDataArr['comptitors_id']);

                    if ($exist) {


                        $updated = $this->Admin_model->update_single('tbl_addcomptitors',$addDataArr,array('where'=>array('comptitors_id'=>$addDataArr['comptitors_id'],'match_id'=>'')));

                        if ($updated) {

                            echo $this->lang->line('odds_update');
                        } else {


                            echo $this->lang->line('odds_Not_update');
                        }
                    } else {

                        $data = $this->Admin_model->insertQuery('addcomptitors', $addDataArr);

                        if ($data) {


                            echo $this->lang->line('odds_added');
                        } else {

                            echo $this->lang->line('odds_not_added') . $data;
                        }
                    }
                } else {

                    $addDataArr['comptitors_id']=isset($addDataArr['comptitors_id'])?$addDataArr['comptitors_id']:'';
                    $isexist = $this->Admin_model->checkMatchExist($addDataArr['match_id'], $addDataArr['tournament_id'],$addDataArr['comptitors_id']);
                    //print_r($isexist); die;
                    if (!empty($isexist)) {

                        $updated = $this->Admin_model->update_single('tbl_addcomptitors', $addDataArr, array('where' => array('id' => $isexist['id'])));
//echo $this->db->last_query(); die;
                        if ($updated) {

                            echo $this->lang->line('odds_update');
                        } else {
                            echo $this->lang->line('odds_Not_update');
                        }
                    } else {

                        $data = $this->Admin_model->insert_single('tbl_addcomptitors', $addDataArr);

                        if ($data) {


                            echo $this->lang->line('odds_added');
                        } else {

                            echo $this->lang->line('odds_not_added') . $data;
                        }
                    }
                }
            } catch (Exception $ex) {


                echo $ex->getMessage();
            }
        } else {

            return show_404();
        }
    }

    /**
     * @Name eventMatches
     * @Date 24/11/2017
     * @Param No
     * @Desc  get all matches of turnaments
     * @Return array to Views
     * */
    public function eventMatches() {

        $this->_authCheck();

        try {

            if ($this->input->is_ajax_request()) {

                $id = $this->input->get('id');
                $type = $this->input->get('type');



                //$exp=explode('&',$id);
                //$matchId=$id != 'noev'?$exp[2]:'';
                //$imp=explode('=',$matchId);
                //$match=$imp != ' '?$imp[1]:'';

                $data='';
                $tourId = base64_decode($id);
                if ($type == '2') {
                    $data = $this->Admin_model->eventMatches($tourId);
                }



               echo isset($data)?$data:'';
            } else {

                return show_404();
            }
        } catch (Exception $ex) {


            echo $ex->getMessage();
        }
    }

}
