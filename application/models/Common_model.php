<?php

class Common_model extends CI_Model {

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
            $where = "password='" . $this->encrypt_decrypt('encrypt',$password) . "' AND email='" . $email . "' AND status='0'";
        else
            $where = "password='" . $this->encrypt_decrypt('encrypt',$password) . "' AND phone_number=" . $phone_no . " AND status='0'";

        $this->db->where($where);
        $query = $this->db->get();
        $data = $query->row_array();


        return $data;
    }
    
    public function check_exists_user($email=NULL,$phone=NULL)
    {
        $this->db->select('*');
        $this->db->from('tbl_users');

        if(!empty($phone) && !empty($email))
                 $where = "((phone_number='" . $phone . "' OR email='" . $email . "') AND status='0')"; //die;
        else if(!empty($phone))
                 $where = "phone_number='" . $phone . "'  AND status='0'";
        else if(!empty($email))
                 $where = "email='" . $email . "' AND status='0'";
        
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
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
        $secret_key = 'bet$comk@ard';
        $secret_iv = 'kh@com@&betd';

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

}
