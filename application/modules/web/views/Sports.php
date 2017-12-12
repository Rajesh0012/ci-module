<?php

require 'View_Manager.php';

class Sports extends View_Manager{

    /**
     * @name  get_sports_list
     * @description get data of sports from database
     * @param array $data
     * @return arrray into view
     */

    protected function _authCheck() {
        if ($this->session->userdata('admin_id') == "") {
            redirect(SITE_URL . '/admin');
        }
    }

    public function get_sports_list()
    {
        $this->_authCheck();
        $data = [];

        try {
            $data['list'] = $this->Admin_model->getSports();

            if(empty($data['list'])){

                throw new Exception($this->lang->line('no_records'));
            }

        }catch (Exception $ex){

            $data['exception'] = $ex->getMessage();

        }


        View_Manager::html('sports',$data);

    }

    /**
     * @name  getCategories
     * @description get data of sports category from database via jquery ajax
     * @param string $id
     * @return arrray into view
     */

    public function getCategories()
    {
        $this->_authCheck();

        if ($this->input->is_ajax_request()) {


             $id = $this->input->get('id', TRUE);

            echo $this->Admin_model->getSportCategoryById($id);

        }else{

            return show_404();
        }

    }

    public function getTournament()
    {

        $this->_authCheck();
        try{

            if ($this->input->is_ajax_request()) {


                $id = $this->input->get('id', TRUE);

                 echo $this->Admin_model->getTournamentById($id);

            }else{

                return show_404();
            }

        } catch (Exception $ex){

            echo $ex->getMessage();
        }

    }

    public function getEvent()
    {

        $this->_authCheck();
        try {

            if ($this->input->is_ajax_request()) {


                 $id = $this->input->get('id', TRUE);

                echo $this->Admin_model->getEventDetails($id);

            }else{

                return show_404();
            }
        }catch (Exception $ex){


            echo $ex->getMessage();
        }

    }
    
    public function getSportEvent()
    {
        $this->_authCheck();
        try {

            if ($this->input->is_ajax_request()) {

                $sid = $this->input->get('id', TRUE);

                echo $this->Admin_model->sportEventList($sid);

            } else {

                return show_404();
            }
        }catch (Exception $ex){


            echo $ex->getMessage();
        }
    }


}

?>
