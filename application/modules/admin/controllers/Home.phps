<?php

require 'View_Manager.php';

class Home extends View_Manager
{


    public function __construct()
    {

        parent::__construct();

    }

    protected function _authCheck()
    {

        if ($this->session->userdata('admin_id') == "") {
            redirect(SITE_URL . '/admin');
        }
    }

    function homePage()
    {
        $data=[];
        $this->_authCheck();
        $catId=[];
        try {

            $searchdata=$this->input->get();

            $total_row = $this->Admin_model->count_odds($searchdata);
            if(!empty($searchdata['delete']))
            {
                foreach ($searchdata['delete'] as $delId)
                {

                    $del=$this->Admin_model->deleteAndBlock('addcomptitors',$delId,'delete');


                }

                if($del){

                   $data['msg'] = $this->session->set_flashdata('msg',$this->lang->line('odd_deleted'));

                   redirect(SITE_URL.'/admin/Home/homePage');

                } else {

                   $data['msg']=$del;
                }


            }
            foreach ($total_row as $totalpage)
            {

                $paged = $totalpage->id;
            }

            $gameCategory=isset($searchdata['gamecategory'])?'?gamecategory='.trim($searchdata['gamecategory']):'';
            $sdisplay=isset($searchdata['display'])?'&display='.trim($searchdata['display']):'';
            $display=isset($searchdata['display'])?trim($searchdata['display']):'';


            $config = array();

            $data['available_odds'] = $paged;

            $config['base_url'] = site_url().'/admin/Home/homePage'.$gameCategory.$sdisplay;
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

            $data['odds'] = $this->Admin_model->getOdds($config['per_page'], $data['page'],$searchdata);
            //print_r($data['odds']);

            $data['pagination'] = $this->pagination->create_links();
            $data['search']=$searchdata;
            $data['sports'] =$this->Admin_model->fetch_data('tbl_sports','',array('where'=>array('status'=>'1')),false);
            $data['categories'] = $this->Admin_model->getSearchCategories();
            $data['allEvents'] = $this->Admin_model->getAllEvents();

            $this->html('home',$data);

        }catch (Exception $ex){

            $data['msg']=$ex->getMessage();

        }

    }

    public function getEvents()
    {

        $this->_authCheck();
        $data= [];
        $paged = '';
        $total_row = '';
        $id = '';
        $tournament_id = '';
        $matchId= '';
        try {


            $id = base64_decode($this->input->get('catId', TRUE));
            $tournament_id = base64_decode($this->input->get('tournament_id', TRUE));
            $matchId = base64_decode($this->input->get('matchId', TRUE));

            if (isset($id) && !empty($id)) {


                $total_row = $this->Admin_model->countComptitors($id, $tournament_id);

                foreach ($total_row as $totalpage) {

                    $paged = $totalpage->id;
                }

                $config = array();
                $data['available_users'] = $paged;

                $config['base_url'] = site_url() . '/admin/Home/getEvents?catId=' . base64_encode($id);
                $config['total_rows'] = $paged;


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

                $data['tournament_id'] = $tournament_id;
                $data['match_id'] = $matchId;
                $data['pagination'] = $this->pagination->create_links();
                $data['comptitor'] = $this->Admin_model->getComptitorOdds($config['per_page'], $data['page'], $id, $tournament_id);

                if (!empty($data['comptitor'])) {

                    foreach ($data['comptitor'] as $key => $values) {

                        $exp = explode('|+|', $values->odds);
                        $exp2 = explode('|+|', $values->bookies);
                        $values->odds = $exp;
                        unset($values->bookies);
                        $values->bookies = $exp2;
                    }

                }


            } else {

                echo $this->lang->line('catid_blank');

            }
        }catch (Exception $ex){

            echo $ex->getMessage();

        }


        View_Manager::html('event-list',$data);
    }


    function addComptitors(){

        $thisValues = '';
        $exist ='';

        $this->_authCheck();

        if ($this->input->is_ajax_request()) {


            try {

                $adddata = $this->input->get();
                $imp = implode('|=|', $adddata);
                $exp = explode('|=|', $imp);

                $addData['category_id'] = $exp[0];
                $addData['comptitors_id'] = $exp[1];
                $addData['bookies_id'] = $exp[2];
                $addData['tournament_id'] = $exp[3];
                $addData['match_id'] = $exp[4];
                $addData['odds'] = $exp[5];

                $exist = $this->Admin_model->checkExist($addData['comptitors_id']);

                if ($exist) {


                    $updated = $this->Admin_model->updateQuery($addData['comptitors_id'], 'addcomptitors', $addData);

                    if ($updated) {

                        echo $this->lang->line('odds_update');


                    } else {


                        echo $this->lang->line('odds_Not_update');
                    }


                } else {

                    $data = $this->Admin_model->insertQuery('addcomptitors', $addData);

                    if ($data) {


                        echo $this->lang->line('odds_added');

                    } else {

                        echo $this->lang->line('odds_not_added') . $data;

                    }
                }

            } catch (Exception $ex) {


                echo $ex->getMessage();

            }

        }else{

            return show_404();
        }


    }

}
