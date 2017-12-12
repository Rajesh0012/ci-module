<?php

class Admin_model extends CI_Model {

    protected $prefix='tbl_';

    public function __construct() {
        $this->load->database();
        $this->load->library('session');
    }

    /**
     * Fetch data from any table based on different conditions
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	array
     * @return	bool
     */
    public function fetch_data($table, $fields = '*', $conditions = array(), $returnRow = false) {
        //echo $table; die('here');
        //Preparing query
        $this->db->select($fields);
        $this->db->from($table);

        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        //echo '<pre>'; print_r($conditions);echo '</pre>';//die;
        $query = $this->db->get();
        //echo '<pre>'; print_r($query);die;
        //echo $this->db->last_query(); die;
        //Return
        return $returnRow ? $query->row_array() : $query->result_array();
    }



    /** function aim- fetch login user detail
     * * params- user_id,table,fields
     * *response-Array
     * *update date-
     * * create date-
     * */
    public function fetch_login_data($table, $fields = "*", $email = NULL, $phone_no = NULL, $password) {

        $this->db->select($fields);
        $this->db->from($table);

        if (!empty($email))
            $where = "password='" . md5($password) . "' AND email='" . $email . "' AND status='0'";
        else
            $where = "password='" . md5($password) . "' AND phone_no=" . $phone_no . " AND status='0'";

        $this->db->where($where);
        $query = $this->db->get();
        $data = $query->row_array();


        return $data;
    }

    /**
     * Insert data in DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	string
     * @return	string
     */
    public function insert_single($table, $data = array()) {
        //Check if any data to insert
        if (count($data) < 1) {
            return false;
        }


        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Insert batch data
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	array
     * @param	bool
     * @return	bool
     */
    public function insert_batch($table, $defaultArray, $dynamicArray = array(), $updatedTime = false) {
        //Check if default array has values
        if (count($dynamicArray) < 1) {
            return false;
        }

        //If updatedTime is true
        if ($updatedTime) {
            $defaultArray['UpdatedTime'] = time();
        }

        //Iterate it
        foreach ($dynamicArray as $val) {
            $updates[] = array_merge($defaultArray, $val);
        }
        $this->db->insert_batch($table, $updates);
    }

    /**
     * Delete data from DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	string
     * @return	string
     */
    public function delete_data($table, $conditions = array()) {
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->delete($table);
    }

    /**
     * Handle different conditions of query
     *
     * @access	public
     * @param	array
     * @return	bool
     */
    private function condition_handler($conditions) {
        //Where
        if (array_key_exists('where', $conditions)) {

            //Iterate all where's
            foreach ($conditions['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }

        //Where In
        if (array_key_exists('where_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_in'] as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }

        //Where Not In
        if (array_key_exists('where_not_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_not_in'] as $key => $val) {
                $this->db->where_not_in($key, $val);
            }
        }

        //Having
        if (array_key_exists('having', $conditions)) {
            $this->db->having($conditions['having']);
        }

        //Group By
        if (array_key_exists('group_by', $conditions)) {
            $this->db->group_by($conditions['group_by']);
        }

        //Order By
        if (array_key_exists('order_by', $conditions)) {

            //Iterate all order by's
            foreach ($conditions['order_by'] as $key => $val) {
                $this->db->order_by($key, $val);
            }
        }

        //Like
        if (array_key_exists('like', $conditions)) {

            //Iterate all likes
            $i = 1;
            foreach ($conditions['like'] as $key => $val) {
                if ($i == 1) {
                    $this->db->like('LOWER(' . $key . ')', strtolower($val), 'after');
                } else {
                    $this->db->or_like('LOWER(' . $key . ')', strtolower($val), 'after');
                }
                $i++;
            }
        }

        //Not Like
        if (array_key_exists('not_like', $conditions)) {

            //Iterate all likes
            $i = 1;
            foreach ($conditions['not_like'] as $key => $val) {
                if ($i == 1) {
                    $this->db->not_like('LOWER(' . $key . ')', strtolower($val), 'after');
                } else {
                    $this->db->or_not_like('LOWER(' . $key . ')', strtolower($val), 'after');
                }
                $i++;
            }
        }

        //Limit
        if (array_key_exists('limit', $conditions)) {
            //If offset is there too?
            if (count($conditions['limit']) == 1) {
                $this->db->limit($conditions['limit'][0]);
            } else {
                $this->db->limit($conditions['limit'][0], $conditions['limit'][1]);
            }
        }
    }

    /**
     * Update Batch
     *
     * @access	public
     * @param	string
     * @param	array
     * @return	boolean
     */
    public function update_batch_data($table, $defaultArray, $dynamicArray = array(), $key) {
        //Check if any data
        if (count($dynamicArray) < 1) {
            return false;
        }

        //Prepare data for insertion
        foreach ($dynamicArray as $val) {
            $data[] = array_merge($defaultArray, $val);
        }
        return $this->db->update_batch($table, $data, $key);
    }

    public function update_db($table, $updates, $where) {
        //If there are conditions
        $this->db->set('tagTitle', $updates['tagTitle'])->where('tagId', $where['tagId'])->update($table);
    }

    /**
     * Update details in DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	array
     * @return	string
     */
    public function update_single($table, $updates, $conditions = array()) {
        //If there are conditions

        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->update($table, $updates);
    }

    /** function aim- update user information
     * * params-table,condition,data
     * *response-booleen
     * *update date-
     * * create date-
     * */
    public function update_new($table, $updates, $condition) {

        //If there are conditions
        //~ if (count($conditions) > 0) {
        //~ $this->condition_handler($conditions);
        //~ }

        $this->db->where('id', $condition);

        //echo $this->db->last_query(); die;
        return $this->db->update($table, $updates);
    }



    /**
     * Count all records
     *
     * @access	public
     * @param	string
     * @return	array
     */
    public function fetch_count($table, $conditions = array()) {
        $this->db->from($table);
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->count_all_results();
    }

    /**
     * For sending mail
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	string
     * @param	boolean
     * @return	array
     */
    public function sendmail($email, $subject, $message = false, $single = true, $param = false, $templet = false) {
        if ($single == true) {
            $this->load->library('email');
        }

        $this->config->load('email');
        $this->email->set_newline("\r\n");
        $this->email->from($this->config->item('from'), $this->config->item('from_name'));
        $this->email->reply_to($this->config->item('reply_to'), $this->config->item('reply_to_name'));
        $this->email->to($email);
        $this->email->subject($subject);
        if ($param && $templet) {
            $body = $this->load->view('mail/' . $templet, $param, TRUE);

            $this->email->message($body);
        } else {
            $this->email->message($message);
        }

        return $this->email->send() ? true : false;
    }

    function mcrypt_data($input) {
        /* Return mcrypted data */
        $key1 = "ShareSpark";
        $key2 = "Org";
        $key = $key1 . $key2;
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $input, MCRYPT_MODE_CBC, md5(md5($key))));
        //var_dump($encrypted);
        return $encrypted;
    }

    function demcrypt_data($input) {
        /* Return De-mcrypted data */
        $key1 = "ShareSpark";
        $key2 = "Org";
        $key = $key1 . $key2;
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $decrypted;
    }

    function bcrypt_data($input) {
        $salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
        $hash = crypt($input, '$2a$12$' . $salt);
        return $hash;
    }

    public function simplify_array($array, $key) {
        $returnArray = array();
        foreach ($array as $val) {
            $returnArray[] = $val[$key];
        }
        return $returnArray;
    }

    //Validate date
    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    // for layout
    function load_views($customView, $data = array()) {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/left_menu', $data);
        $this->load->view($customView, $data);
        $this->load->view('templates/footer', $data);
    }

    function encrypt_decrypt($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'lin$hk@rd';
        $secret_iv = 'kh@lin@&d';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }


    Public function encryptResponse($text, $salt = '', $key='') {

        $padding = 16 - (strlen($text) % 16);
        $data = $text.str_repeat(chr($padding), $padding);

        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $salt, $data, MCRYPT_MODE_CBC, $key)));
    }


    function decryptResponse($text, $salt = '', $key='') {

        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $salt, base64_decode($text), MCRYPT_MODE_CBC, $key));
    }



    /**
     * Handle Pagination
     *
     * @access	public
     */
    public function handlePagination($totalRows) {

        //Load Pagination Library
        $this->load->config('pagination');
        $this->load->library('pagination');

        //First validate if there are any rows
        if ($totalRows > 0) {

            //Basic Pagination Config
            $finalSegment = $this->uri->segment(2);
            $config['per_page'] = $this->config->item('per_page_' . $finalSegment);
            $showMore = $this->input->get('show_more');
            $pageNumber = (!empty($showMore) and is_numeric($showMore)) ? $showMore - 1 : 0;
            $start = $config['per_page'] * $pageNumber;
            $config['total_rows'] = $totalRows;

            //Handle get params
            $additionalParams = '';
            $get = count($_GET) > 0 ? $_GET : array();
            $pageNumberKey = $this->config->item('query_string_segment');
            if (array_key_exists($pageNumberKey, $get)) {
                unset($get[$pageNumberKey]);
            }
            if (count($get) > 0) {
                $additionalParams = http_build_query($get);
            }
            $config['base_url'] = base_url() . 'view/' . $finalSegment . '?' . $additionalParams;
            $config['full_tag_open'] = '<div class="row"><div class="col-sm-5"><div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing ' . ($start + 1) . ' to ' . ($start + $config['per_page']) . ' of ' . $totalRows . ' entries</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="example2_paginate"><ul class="pagination">';
            $this->pagination->initialize($config);

            return array(
                'totalRecords' => $config['total_rows'],
                'startCount' => $start
            );
        } else {
            return array(
                'totalRecords' => 0,
                'startCount' => 0
            );
        }
    }


    public  function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function get_upcoming_events()
    {
        $currDate=date('Y-m-d');
        $this->db->select("tour.*,s.*");
        $this->db->from("tbl_tournament tour");
        $this->db->join("tbl_season s","tour.tournament_id=s.tournament_id","left");
        $this->db->where('s.end_date>=',$currDate);

        $this->db->group_by('tour.tournament_id');

        $query=$this->db->get();

        return $query->result_array();

    }

    public function get_Team_Detail($event_id=NULL)
    {
        $this->db->select('tc.*,se.scheduled,v.venue_name');
        $this->db->from('tbl_competitors tc');
        $this->db->join('tbl_sports_events se','tc.event_id=se.event_id','left');
        $this->db->join('tbl_venue v','tc.event_id=v.event_id','left');
        $this->db->where('tc.event_id',$event_id);

        $query=$this->db->get();

        return $query->result_array();

    }

    public function get_sport_events($tournament_id=NULL)
    {

        $this->db->select('se.id,se.tournament_id,se.event_id,se.scheduled,se.event_status,oc.sport_id,oc.market_name,oc.outcome_type,oc.outcome_odds,oc.handicap,oc.deep_link');
        $this->db->from('tbl_sports_events se');
        $this->db->join('tbl_competitors com','com.event_id=se.event_id','left');
        $this->db->join('tbl_outcome oc','oc.event_id=se.event_id','left');

        $this->db->where('se.tournament_id',$tournament_id);
        $this->db->group_by('se.event_id');
        $query=$this->db->get();

        return $query->result_array();

    }


    public function get_sport_outcomes($event_id=NULL)
    {

        $this->db->select('oc.market_name,oc.outcome_type,oc.outcome_odds,oc.handicap,oc.deep_link,com.*,se.scheduled');
        $this->db->from('tbl_outcome oc');
        $this->db->join('tbl_competitors com','com.event_id=oc.event_id','left');
        $this->db->join('tbl_sports_events se','oc.event_id=se.event_id','left');

        $this->db->where('oc.event_id',$event_id);
        $this->db->group_by('com.id');
        $query=$this->db->get();

        return $query->result_array();

    }


    /**
     * @name  fetch_using_join
     * @description fetch data from join
     * @param string $select
     * @param string $from
     * @param string $join
     * @param string $where
     * @param string $asArray
     * @param string $offset
     * @param string $orderBy
     * @param string $limit
     * @return arrray
     */
    public function fetch_using_join($select ,$from ,$join, $where , $asArray = NULL , $offset=NULL , $orderBy = NULL, $limit = NULL , $groupBy = NULL , $numRows = NULL , $like = NULL,$having=NULL){
        define(PAGINATION_LIMIT,10);
        $this->db->select($select,FALSE);
        $this->db->from($from);
        for ($i = 0; $i < count($join); $i++) {
            $this->db->join($join[$i]["table"] , $join[$i]["condition"] , $join[$i]["type"]);
        }
        $this->db->where($where);
        if (!empty($like)) {
            $this->db->like($like);
        }
        if (isset($orderBy['order']) && $orderBy !== NULL) {
            $this->db->order_by($orderBy["order"], $orderBy["sort"]);
        }

        if ($offset!==NULL) {
            $this->db->limit(PAGINATION_LIMIT, $offset);
        }

        if (!empty($limit)) {
            $this->db->limit($limit);
        }

        if ($groupBy!==NULL) {
            $this->db->group_by($groupBy);
        }
        if ($having!==NULL) {
            $this->db->having($having);
        }
        $query = $this->db->get();
        if (!empty($numRows)) {
            return $query->num_rows();
        }else{
            return ($asArray!==NULL) ? $query->row() : $query->result_array();
        }
    }

    /**
     * @Name Userlist
     * @Date 06/11/2017
     * @Param $limit,$offset,$filterdata,$userId
     * @Desc get user list from here using above parameter this also work with filter search and pagination condition
     *
     * */

    public function Userlist($limit,$offset,$filterdata,$userId = ''){


        $this->db->select('id,country_code,phone_number,signup_type,image,full_name,email,image,status,create_date');
        $this->db->from($this->prefix.'users');

        if(isset($filterdata['to_date']) && isset($filterdata['from_date']) && !empty(trim($filterdata['to_date'])) && !empty(trim($filterdata['from_date'])))
        {

            $from_unix=strtotime($filterdata['from_date']);
            $converted_date_from=date('Y-d-m',$from_unix);
            $to_unix=strtotime($filterdata['to_date']);
            $converted_date_to=date('Y-d-m',$to_unix);
            $this->db->where("create_date between '$converted_date_from' and '$converted_date_to' ");
        }

        if(isset($filterdata['device_type']) && !empty(trim($filterdata['device_type'])))
        {

            $this->db->where(array(

                'device_type' => $filterdata['device_type']));

        }
        if(isset($filterdata['registerd_via']) && !empty(trim($filterdata['registerd_via'])))
        {
            $this->db->where(array('signup_type' => $filterdata['registerd_via']));
        }
        if(isset($filterdata['searchname']) &&!empty($filterdata['searchname']) )
        {
            $arr=array('email' => $filterdata['searchname'],
                'full_name'=>$filterdata['searchname'],
                'phone_number'=>$filterdata['searchname']

            );
            $this->db->or_like($arr);
        }

        if(!empty(trim($userId))){ $this->db->where('id',$userId); }
        $this->db->limit($limit,$offset);
        $this->db->order_by('id','DESC');
        $data=$this->db->get();
        $error=$this->db->error();
        if(isset($error) && !empty($error['code'])){
            throw new Exception($this->lang->line('somthing_went_wrong'));

        }else{

            if($data->num_rows()>0){

                if(!empty($userId)){ return $data->row(); }

                return  $data->result();

            }else{

                return false;
            }


        }

    }

    /**
     * @Name getSports
     * @Date 07/11/2017
     * @Param NO
     * @Desc get all sport from here
     *
     * */

    public function getSports(){


        $this->db->select('sport_id,sport_name');
        $this->db->from($this->prefix.'sports');
        $data=$this->db->get();
        $error=$this->db->error();
        if(isset($error) && !empty($error['code'])){
            throw new Exception($this->lang->line('somthing_went_wrong'));

        }else{

            if($data->num_rows()>0){

                return  $data->result();

            }else{

                return false;
            }


        }

    }

    /**
     * @Name getSportCategoryById
     * @Date 07/11/2017
     * @Param $id
     * @Desc name are suggesting there work this will call for ajax work and thier html from here
     *
     * */

    public function getSportCategoryById($id = '')
    {

            if(!empty($id)) {
            $this->db->select('category_id,sport_id,category_name');
            $this->db->from($this->prefix . 'categories');
            $this->db->where('sport_id', $id);
            $data = $this->db->get();
            $error = $this->db->error();
                if (isset($error) && !empty($error['code'])) {
                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {

                    if ($data->num_rows() > 0) :
                        echo '<select  name="categories" required onchange="getTournament(this.value)" class="selectpicker form-control" >';
                        echo '<option value=" ">--select--</option>';
                        foreach ($data->result() as $key => $values) :
                            ?>

                            <option  value="<?php echo $values->category_id; ?>"><?php echo $values->category_name; ?></option>


                        <?php
                     endforeach;  echo '</select>'; ?>
                    <?php else: ?>
                <select required class="selectpicker">
                <option>--No Records--</option>
                </select>
                   <?php endif;


                }

            }
    }

    /**
     * @Name getTournamentById
     * @Date 07/11/2017
     * @Param $id
     * @Desc name are suggesting there work this will call for ajax work and thier html from here
     *
     * */

    public function getTournamentById($id = '')
    {

        if(!empty($id)) {
            $this->db->select('tournament_id,tournament_name');
            $this->db->from($this->prefix.'tournament');
            $this->db->where('sportradar_category_id', $id);
            $data = $this->db->get();
            $error = $this->db->error();
            if (isset($error) && !empty($error['code'])) {
                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {

                if ($data->num_rows() > 0) {
                    echo '<select id="tournament" name="tournament" class="selectpicker form-control">';
                    echo '<option value=" ">--select--</option>';
                    foreach ($data->result() as $key => $values) {
                        ?>

                        <option value="<?php echo $values->tournament_id; ?>"><?php echo $values->tournament_name; ?></option>


                    <?php }
                    echo '</select>';

                } else {
                    ?>
                <select class="selectpicker form-control">
                <option>--No Records--</option>
                </select>
                    <?php
                }


            }

        }
    }

    /**
     * @Name getEventDetails
     * @Date 07/11/2017
     * @Param $id
     * @Desc name are suggesting there work this will call for ajax work and thier html from here
     *
     * */

    public function getEventDetails($id = '')
    {

        if (!empty($id)) {

            $this->db->select('evnt.event_id,ssn.season_name,evnt.scheduled,vn.venue_name,vn.city_name,vn.country_name');
            $this->db->from($this->prefix.'sports_events evnt');
            $this->db->join($this->prefix.'season ssn','evnt.tournament_id=ssn.tournament_id','left');
            $this->db->join($this->prefix .'venue vn', 'evnt.event_id=vn.event_id', 'left');
            $this->db->where('ssn.tournament_id',$id);
            $this->db->group_by('evnt.event_id');
            $data = $this->db->get();

            $error = $this->db->error();
            if (isset($error) && !empty($error['code'])) {
                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {

                if ($data->num_rows() > 0) {

                    foreach ($data->result() as $key=>$values) {
                        ?>
                        <div class="col-sm-4">
                            <div class="inner-event-card">
                                <h2 class="event-title"><?php echo $values->season_name; ?></h2>
                                <div class="row admin-filed-wrap">
                                    <div class="col-xs-6">
                                        <label class="admin-label">Date</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="show-label"><?php  $unix1=strtotime($values->scheduled);echo date('Y-M-d',$unix1); ?></span>
                                    </div>
                                </div>
                                <div class="row admin-filed-wrap">
                                    <div class="col-xs-6">
                                        <label class="admin-label">Time</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="show-label"><?php $unix=strtotime($values->scheduled); echo date('h:i A',$unix); ?></span>
                                    </div>
                                </div>
                                <div class="row admin-filed-wrap">
                                    <div class="col-xs-6">
                                        <label class="admin-label">Location</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="show-label"><?php  if(!empty($values->venue_name)){ echo $values->venue_name;}else{ echo 'N/A';} ?>
                                            <?php echo $values->city_name; ?><br>
                                            <?php echo $values->country_name; ?><br>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }    } else {

                   echo ' <div class="col-sm-3">No Event Found!</div>';
                }


            }

        }else{
            return false;
        }
    }

    /**
     * @Name add_user
     * @Date 08/11/2017
     * @Param $id
     * @Desc all users adding in database form here
     *
     * */

    public function add_user($table,$data = array()){

        try{

            if(!empty($data)){

                $data['create_date']= date('Y-m-d h:i:s');
                $data['signup_type']= 6;
                $data['status']= 0;


                $submitted=$this->db->insert($this->prefix.$table,$data);

               if(!$submitted) {
                   $error = $this->db->error();
                   if (isset($error) && !empty($error['code'])) {

                       throw new Exception($this->lang->line('somthing_went_wrong'));

                   }
               }else{

                   return TRUE;
               }

            }

        }
        catch(Exception $e){

         return  $e->getMessage();
        }



    }

    /**
     * @Name editUserList
     * @Date 08/11/2017
     * @Param $id
     * @Desc edit user and select thier data from here
     *
     * */

    public function editUserList($data = array(),$userId = '')
    {
        try {
            if (!empty($data) && !empty($userId)) {

                $data['update_date']=date('Y-m-d h:i:s');


                $this->db->set($data);
                $this->db->where('id', $userId);
                $submitted = $this->db->update($this->prefix . 'users');

                if (!$submitted) {
                    $error = $this->db->error();
                    if (isset($error) && !empty($error['code'])) {

                        throw new Exception($this->lang->line('somthing_went_wrong'));

                    }
                } else {

                    return TRUE;
                }

            }
        }catch (Exception $ex){

           return $ex->getMessage();


        }
    }

    /**
     * @Name count_user
     * @Date 09/11/2017
     * @Param $filterdata
     * @Desc count all user from here and also available in database total users with different condition
     *
     * */

    function count_user($filterdata = array())
    {

        $table=$this->prefix.'users';

        if(!empty($table))
        {
            $this->db->select("count(id) as id");

            if (count($filterdata) > 0)
            {

                if(isset($filterdata['to_date']) && isset($filterdata['from_date']) && trim(!empty($filterdata['to_date'])) && trim(!empty($filterdata['from_date'])))
                {

                    $from_unix=strtotime($filterdata['from_date']);
                    $converted_date_from=date('Y-d-m',$from_unix);
                    $to_unix=strtotime($filterdata['to_date']);
                    $converted_date_to=date('Y-d-m',$to_unix);
                    $this->db->where("create_date between '$converted_date_from' and '$converted_date_to' ");
                }

                if(isset($filterdata['device_type']) && !empty($filterdata['device_type']))
                {

                    $this->db->where(array(

                        'device_type' => $filterdata['device_type']));

                }

                if(isset($filterdata['registerd_via']) &&!empty($filterdata['registerd_via']) )
                {
                    $this->db->where(array('signup_type' => $filterdata['registerd_via']));
                }

                if(isset($filterdata['searchname']) &&!empty($filterdata['searchname']) )
                {
                    $arr=array('email' => $filterdata['searchname'],
                        'full_name'=>$filterdata['searchname'],
                        'phone_number'=>$filterdata['searchname']

                    );
                    $this->db->or_like($arr);
                }


            }


            $this->db->from($table);
            $count = $this->db->get();
            $err=$this->db->error();
            if(isset($err['code']) && $err['code'] != 0){
                throw new Exception($this->lang->line('somthing_went_wrong'));

            }else{

                return $count->result();
            }

        }
        return false;
    }

    /**
     * @Name count_odds
     * @Date 10/11/2017
     * @Param $filterdata,$fetchcondition
     * @Desc count all odds on the basis of filter,search or without any condition it also work for pagination
     *
     * */

    public function count_odds($filterdata = [])
    {

        try {
            //print_r($fetchcondition['comptitors_id']);

            $table = $this->prefix . 'addcomptitors add';
            if (!empty($table)) {

                    $this->db->select("SQL_CALC_FOUND_ROWS com.id",FALSE);
                    $this->db->from($table);
                   $this->db->join($this->prefix . 'categories ctg', 'add.category_id=ctg.category_id', 'left');
                   $this->db->join($this->prefix . 'season ssn', 'ssn.tournament_id=add.tournament_id', 'left');
                   $this->db->join($this->prefix . 'competitors cmt', 'add.comptitors_id=cmt.competitor_id', 'left');
                   $this->db->join('tbl_comptitor_list com','add.comptitors_id=com.comptitors_id and add.tournament_id=com.tournament_id and add.bookies_id=com.bookies_id','left');

                if (isset($filterdata['gamecategory']) && !empty(trim($filterdata['gamecategory']))) {
                        $this->db->where('add.category_id', trim($filterdata['gamecategory']));
                    }

                    if (isset($filterdata['searchkeywords']) && !empty(trim($filterdata['searchkeywords']))) {
                        $this->db->or_like(array('ssn.season_name' => trim($filterdata['searchkeywords'])));
                    }

                    if (isset($filterdata['searchkeywords']) && !empty(trim($filterdata['searchkeywords']))) {
                        $this->db->or_like(array('com.name' => trim($filterdata['searchkeywords'])));
                    }
                    $this->db->group_by('add.category_id');
                    $this->db->group_by('add.match_id');
                    $this->db->group_by('add.odds');

                    $data = $this->db->get();
                    //echo  $this->db->last_query(); die;
                    $querytotal = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
                    $totalres = $querytotal->row()->Count;

                $err = $this->db->error();
                    if (isset($err['code']) && $err['code'] != 0) {

                        throw new Exception($this->lang->line('somthing_went_wrong'));

                    } else {

                        return $totalres;

                    }
            }

        }catch (Exception $ex){


            return $ex->getMessage();

        }


    }

    /**
     * @Name getOdds
     * @Date 09/11/2017
     * @Param $limit ,$offset,$filterdata,$fetchcondition
     * @Desc get all odds on the basis of filter,search or without any condition it also work for pagination
     *
     * */

    public function getOdds($limit = '',$offset = '',$filterdata = [])
    {
        try {

            $table = $this->prefix . 'addcomptitors add';
            if (!empty($table)) {
                $this->db->select('add.id as id,ctg.category_name,add.odds,ssn.season_name,com.name as competitor_name,ssn.tournament_id');
                $this->db->from($table);
                $this->db->join($this->prefix . 'categories ctg', 'add.category_id=ctg.category_id', 'left');
                $this->db->join($this->prefix . 'season ssn', 'ssn.tournament_id=add.tournament_id', 'left');
                $this->db->join($this->prefix . 'competitors cmt', 'add.comptitors_id=cmt.competitor_id', 'left');
                $this->db->join('tbl_comptitor_list com','add.comptitors_id=com.comptitors_id and add.tournament_id=com.tournament_id and add.bookies_id=com.bookies_id','left');

                if (isset($filterdata['gamecategory']) && !empty(trim($filterdata['gamecategory']))) {
                    $this->db->where('add.category_id', trim($filterdata['gamecategory']));
                }

                if (isset($filterdata['searchkeywords']) && !empty(trim($filterdata['searchkeywords']))) {
                    $this->db->or_like(array('ssn.season_name' => trim($filterdata['searchkeywords'])));
                }
                if (isset($filterdata['searchkeywords']) && !empty(trim($filterdata['searchkeywords']))) {
                    $this->db->or_like(array('com.name' => trim($filterdata['searchkeywords'])));
                }
                $this->db->limit($limit,$offset);
                $this->db->group_by('add.category_id');
                $this->db->group_by('add.match_id');
                $this->db->group_by('add.odds');
                $this->db->order_by('add.id','DESC');
                $data = $this->db->get();
               //echo  $this->db->last_query(); die;
                $err = $this->db->error();
                if (isset($err['code']) && $err['code'] != 0) {
                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {
                    if ($data->num_rows() > 0) {

                        return $data->result();

                    } else {

                        return FALSE;
                    }

                }

            }


        }catch (Exception $ex){


            return $ex->getMessage();

        }

    }

    /**
     * @Name getSearchCategories
     * @Date 09/11/2017
     * @Param No
     * @Desc get all category
     *
     * */
    
    public function getSearchCategories()
    {
        try{

            $this->db->select('category_id,category_name');
            $this->db->from($this->prefix.'categories');
            $data=$this->db->get();
            $err = $this->db->error();
            if (isset($err['code']) && $err['code'] != 0) {
                throw new Exception($this->lang->line('somthing_went_wrong'));

            }else{

               return $data->result();


            }



            }catch (Exception $ex){


            return $ex->getMessage();

        }

    }

    /**
     * @Name sportEventList
     * @Date 09/11/2017
     * @Param $sid
     * @Desc get sports list from here
     *
     * */

     public function sportEventList($sid = NULL){

         try {

                 $this->db->select('trnm.tournament_name,trnm.tournament_id');
                 $this->db->from($this->prefix . 'tournament trnm');
                 $this->db->join($this->prefix . 'sports ts', 'trnm.sport_id=ts.sport_id', 'left');
                 //$this->db->join($this->prefix . 'competitors cmt', 'cmt.tournament_id=s.tournament_id', 'left');
                 $this->db->join($this->prefix . 'season s', 'trnm.tournament_id=s.tournament_id', 'left');
                 $this->db->where('CURRENT_DATE()<= s.end_date');
                 $this->db->where('ts.sport_id', $sid);
                // $this->db->group_by('se.id');
                 $data = $this->db->get();
                 $err = $this->db->error();

                 if (isset($err['code']) && $err['code'] != 0) {
                     throw new Exception($this->lang->line('somthing_went_wrong'));

                 } else {
                     echo '<select id="event_select" class="form-control" onchange="createLinks(this.value) || getMatch(this.value)">';
                     echo '<option value="noev">--select--</option>';
                    if($data->num_rows()>0){


                        foreach ($data->result() as $key=>$values): if(!empty($values->tournament_name)):?>

                            <option value="<?php echo base64_encode($values->tournament_id); ?>"><?php echo  $values->tournament_name;?></option>

                           <?php endif; endforeach;

                    }else{


                       echo "<option value='noev'>No Records!</option>";
                    }

                     echo '</select>';

                 }


         }catch (Exception $ex){


             return $ex->getMessage();

         }
            return FALSE;
        
     }

    // public function getTournamentComptetor


    public function eventMatches($sid = ''){

        try {

            $this->db->select('GROUP_CONCAT(competitor_name SEPARATOR "||") as competitor_name,abbreviation,tournament_id,category_id,event_id');
            $this->db->from($this->prefix . 'competitors');
            $this->db->where('event_id', $sid);
            $this->db->group_by('event_id');
            $query = $this->db->get();

            $err = $this->db->error();

            if (isset($err['code']) && $err['code'] != 0) {
                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {

                echo '<select id="event_select" class="form-control" onchange="createLinks(this.value)">';
                echo '<option value="noev">--select--</option>';

                if($query->num_rows()>0){

                foreach ($query->result() as $key=>$values): $exp=explode('||',$values->competitor_name); ?>

                    <option value="<?php echo  base64_encode($values->category_id).'&tournament_id='.base64_encode($values->tournament_id).'&matchId='.$values->event_id.'&filter=2';?>"><?php echo $exp[0].' Vs '.$exp[1] ?></option>

              <?php  endforeach;

                }else{

                    echo '<option value=" ">No Records</option>';
                }
 
                echo '</select>';

            }


        }catch (Exception $ex){


            return $ex->getMessage();

        }
        return FALSE;

    }



     public function countComptitors($tournament_id = '')
     {
        try {

echo 1; die;
			if (!empty($tournament_id) && !empty($table)) {
            
				
                $this->db->select("count(cml.id) as id");
                $this->db->from($this->prefix . 'comptitor_list cml');
                $this->db->join($this->prefix . 'tournament trnm','cml.tournament_id=trnm.tournament_id','left');
                //$this->db->join($this->prefix . 'season ssn','cml.tournament_id=ssn.tournament_id','left');
                if(isset($matchId) && !empty($matchId)) { $this->db->join($this->prefix . 'competitors cmt','cml.comptitors_id=cmt.competitor_id','left'); }
                //$this->db->where('cml.category_id', $id);
                $this->db->where('cml.tournament_id', $tournament_id);
                if(!empty($matchId)){

                    $this->db->where('cmt.event_id', $matchId);
                }
                $count = $this->db->get();
                echo $this->db->last_query(); die;
                $err = $this->db->error();
                if (isset($err['code']) && $err['code'] != 0) {
                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {

                    return $count->result();
                }

            }else{
				
			return FALSE;	
				
			}

        }catch (Exception $ex){


            return $ex->getMessage();
        }


     }

    public function getComptitorOdds($limit,$offset,$tournament_id = '',$matchId = '')
    {

        try {

            if (!empty($tournament_id)) {

                $this->db->select('cml.name,cml.category_id,cml.comptitors_id,cml.bookies_id,GROUP_CONCAT(cml.odds SEPARATOR "|+|") as odds,GROUP_CONCAT(cml.bookies_id SEPARATOR "|+|") as bookies', FALSE);
                $this->db->from($this->prefix . 'comptitor_list cml');
                $this->db->join($this->prefix . 'tournament trnm','cml.tournament_id=trnm.tournament_id','left');
                //$this->db->join($this->prefix . 'season ssn','cml.tournament_id=ssn.tournament_id','left');
                if(isset($matchId) && !empty($matchId)) { $this->db->join($this->prefix . 'competitors cmt','cml.comptitors_id=cmt.competitor_id','left'); }
                //$this->db->where('cml.category_id', $id);
                $this->db->where('cml.tournament_id', $tournament_id);
                if(!empty($matchId)){

                    $this->db->where('cmt.event_id', $matchId);
                }
                //$this->db->where('ssn.end_date<=',date('Y-m-d'));
                //$this->db->group_by('cml.comptitors_id');
                $this->db->order_by('cml.odds', 'DESC');
                $this->db->limit($limit, $offset);
                $data = $this->db->get();
                //echo $this->db->last_query();

                $err = $this->db->error();

                if (isset($err['code']) && $err['code'] != 0) {
                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {

                    if ($data->num_rows() > 0) {
                        return $data->result();

                    } else {

                        return FALSE;

                    }


                }
            } else {

                return FALSE;

            }

        } catch (Exception $ex) {

            return $ex->getMessage();


        }

        return FALSE;

    }

    public function checkExist($id = '')
    {

        try{


                $this->db->select('*');
                $this->db->from($this->prefix . 'addcomptitors');
                if(!empty($id)){ $this->db->where('comptitors_id', $id); }
                $data = $this->db->get();
                $err = $this->db->error();
                if (isset($err['code']) && $err['code'] != 0) {

                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {

                    if ($data->num_rows() > 0) {
                        if(!empty($id)){

                            return TRUE;

                        }else{

                            return $data->result_array();
                        }


                    } else {

                        return false;

                    }
                }


        } catch (Exception $ex) {

            return $ex->getMessage();


        }

        return FALSE;


    }

    public  function insertQuery($table = '',$data = array())
    {

        try {

            if (!empty($table) && !empty($data)) {

                $insert = $this->db->insert($this->prefix.$table, $data);
                $err = $this->db->error();
                if (isset($err['code']) && $err['code'] != 0) {
                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {

                   if(!$insert){

                       throw new Exception($insert);

                   }else{

                       return TRUE;
                   }
                }

            } else {


                return FALSE;

            }

        }catch (Exception $ex){


            return $ex->getMessage();

        }
        return FALSE;

    }
    public  function updateQuery($id,$table = '',$data = array())
    {

        try {

            if (!empty($table) && !empty($data)) {

                $this->db->set($data);
                $this->db->where('comptitors_id', $id);
                $update = $this->db->update($this->prefix.$table);
                $err = $this->db->error();
                if (isset($err['code']) && $err['code'] != 0) {

                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {

                    if(!$update){

                        throw new Exception($update);

                    }else{

                        return TRUE;
                    }
                }

            } else {


                return FALSE;

            }

        }catch (Exception $ex){


            return $ex->getMessage();

        }
        return FALSE;

    }

    public function getAllEvents(){


        try{


            $this->db->select('season_name');
            $this->db->from($this->prefix . 'season');
            $data = $this->db->get();
            $err = $this->db->error();
            if (isset($err['code']) && $err['code'] != 0) {

                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {

                if ($data->num_rows() > 0) {
                    if(!empty($id)){

                        return TRUE;

                    }else{

                        return $data->result_array();
                    }


                } else {

                    return false;

                }
            }


        } catch (Exception $ex) {

            return $ex->getMessage();


        }

        return FALSE;

    }

    public function deleteAndBlock($table = '',$id = '',$decider = '')
    {

        try {

            if (!empty($id) && !empty($table)) {


                if($decider == 'delete') { $this->db->delete($this->prefix.$table,array('id'=>$id)); }
                if($decider == 'block') { $this->db->update($this->prefix.$table,array('status'=>0),array('id'=>$id)); }
                $error = $this->db->error();
                if (isset($error['code']) && $error['code'] != 0) {

                    throw new Exception($this->lang->line('somthing_went_wrong'));

                } else {

                   return TRUE;
                }

            }else{

                return FALSE;
            }

        }catch (Exception $ex){

            return $ex->getMessage();

        }

        return FALSE;

    }

    public function get_sports_odds($sport_id= '',$limit= '',$start= '',$tournament= '')
    {
        try {

            $this->db->select("SQL_CALC_FOUND_ROWS se.scheduled,se.sportsradar_category,group_concat(se.event_id) as events,group_concat(se.tournament_id) as tournaments", FALSE);
            $this->db->from($this->prefix . 'sports_events se');
            $this->db->join($this->prefix . 'categories c', 'se.sportsradar_category=c.category_id', 'left');
            //$this->db->where("se.scheduled BETWEEN DATE_ADD(NOW(),INTERVAL 1 DAY) AND DATE_ADD(NOW(), INTERVAL 7 DAY)");
            $this->db->order_by('se.scheduled', 'DESC');
            $this->db->group_by('DATE(se.scheduled)');
            if (!empty($limit)) {

                $this->db->limit(RECORDS_PER_PAGE, $start);
            }


            $data = $this->db->get();
            //echo $this->db->last_query(); die;
            $err = $this->db->error();
            if (isset($err['code']) && $err['code'] != 0) {

                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {

            if($data->num_rows()>0){

                $querytotal = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
                $totalres = $querytotal->row()->Count;

                return array('result_arr' => $data->result_array(), 'total_record' => $totalres);

            }else{

                return FALSE;
            }

            }

        }catch (Exception $ex){

           return $ex->getMessage();
        }

        return FALSE;

    }

    public function get_sport_detail($date = '')
    {
        try {
            $this->db->select("mkt.id,se.scheduled as match_time,mkt.market_name,mkt.sport_id,mkt.match_id,IF(group_concat(com.competitor_name)!='',group_concat(com.competitor_name),'') as comptitors,IF(v.venue_name!='',v.venue_name,'') as venue_name,IF(v.country_name!='',v.country_name,'') as country_name,IF(s.season_name!='',s.season_name,'') as season_name");
            $this->db->from($this->prefix . 'markets mkt');
            $this->db->join($this->prefix . 'competitors com', 'mkt.match_id=com.event_id', 'left');
            $this->db->join($this->prefix . 'season s', 'mkt.match_id=s.sportsradar_event_id', 'left');
            $this->db->join($this->prefix . 'venue v', 'mkt.match_id=v.event_id', 'left');
            $this->db->join($this->prefix . 'sports_events se', 'se.event_id=mkt.match_id', 'left');
            //$this->db->where('DATE(se.scheduled)',date('Y-m-d',strtotime($date)));
            $this->db->group_start();
            $this->db->where_in('mkt.market_name', array('3way', '2way'));
            $this->db->where('mkt.group_name', 'regular');
            $this->db->group_by('mkt.match_id');
            $this->db->group_end();
            $data = $this->db->get();
            $err = $this->db->error();
            if (isset($err['code']) && $err['code'] != 0) {

                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {

                if($data->num_rows()>0){

                    return $data->result_array();

                }else{

                    return FALSE;
                }


            }

        }catch (Exception $ex){

            return $ex->getMessage();
        }

        return FALSE;

    }

    public function get_bookie_odds($market = '')
    {
        try {
            $this->db->select('mo.book_id,group_concat(mo.type) as type,group_concat(mo.odds) as odds');
            $this->db->from($this->prefix . 'market_outcome mo');
            //$this->db->where('mo.market_id',$market);
            $this->db->group_by('mo.market_id');
            $this->db->group_by('mo.book_id');
            $data = $this->db->get();
            $err = $this->db->error();
            if (isset($err['code']) && $err['code'] != 0) {

                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {


                if($data->num_rows()>0){

                    return $data->result_array();

                }else{

                    return FALSE;
                }
            }
        }catch (Exception $ex){

            return $ex->getMessage();
        }

        return FALSE;
    }


    public function get_week_tournament($sport = ''){

        try {

            $this->db->select("t.tournament_id,t.tournament_name,s.sport_name");
            $this->db->from($this->prefix . 'sports_events se');
            $this->db->join($this->prefix . 'tournament t', 'se.tournament_id=t.tournament_id', 'left');
            $this->db->join($this->prefix . 'sports s', 's.sport_id=t.sport_id', 'left');
            $this->db->where("se.scheduled BETWEEN DATE_ADD(NOW(),INTERVAL 1 DAY) AND DATE_ADD(NOW(), INTERVAL 7 DAY)");
            //$this->db->where('t.sport_id',$sport);
            $this->db->group_by('t.tournament_id');
            $data = $this->db->get();
            $err = $this->db->error();
            if (isset($err['code']) && $err['code'] != 0) {

                throw new Exception($this->lang->line('somthing_went_wrong'));

            } else {


                if($data->num_rows()>0){

                    return $data->result_array();

                }else{

                    return FALSE;
                }
            }

        }catch (Exception $ex){

            return $ex->getMessage();
        }

        return FALSE;

    }


}
