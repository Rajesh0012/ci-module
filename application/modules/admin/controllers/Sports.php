<?php

require 'View_Manager.php';

class Sports extends View_Manager{

    /**
     * @Name  _authCheck
     * @Date  09/11/2017
     * @Description check admin is logged in or not
     * @Param array $data
     * @Return ---
     *
     */

    protected function _authCheck() {
        if ($this->session->userdata('admin_id') == "") {
            redirect(SITE_URL . '/admin');
        }
    }

    /**
     * @Name  get_sports_list
     * @Date  09/11/2017
     * @Description get data of all sports from database
     * @Param array $data
     * @Return arrray into view
     *
     */


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
     * @Name  getCategories
     * @Date  14/11/2017
     * @Description get data of all categories from database via ajax request
     * @Param array $id
     * @Return  html with data into view
     *
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

    /**
     * @Name  getTournament
     * @Date  14/11/2017
     * @Description get data of all torunament from database via ajax request
     * @Param array $id
     * @Return  html with data into view
     *
     */

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

    /**
     * @Name  getEvent
     * @Date  15/11/2017
     * @Description get data of all torunament from database via ajax request
     * @Param array $id
     * @Return  html with data into view
     *
     */

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

    /**
     * @Name  getSportEvent
     * @Date  15/11/2017
     * @Description get data of all Event from database via ajax request
     * @Param array $id
     * @Return  html with data into view
     *
     */
    
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
