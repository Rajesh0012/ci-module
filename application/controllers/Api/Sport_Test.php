<?php

/**
 * @Class: Sports
 * @access public
 * @category Create, list, edit, delete coupons
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sport_Test extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Cron_model");
        $this->load->model("Common_model");
        $this->load->model("Sport_model");
    }

    public function getSports() {

        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/sports.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        $xml = new SimpleXMLElement(file_get_contents($your_url));
        $value = $this->xml2array($xml);
        $players = array();
        $i = 0;
//        echo '<pre>';
//        print_r($value['sport']);exit;
        foreach ($value['sport'] as $player) {
            $attr = $player->attributes();

            // cast the SimpleXMLElement to a string
            $players[$i]['sport_id'] = (string) $attr['id'];
            $players[$i]['sport_name'] = (string) $attr['name'];
            $i++;
        }
        /* foreach ($value['sport'] as $key=>$val)
          {
          echo '<pre>';
          //print_r($val{'name'}[0]);
          echo $val{'name'}[0];
          }
          exit; */
        echo '<pre>';
        $json = json_encode($players);
        $array = json_decode($json, true);
        print_r($array);
    }

    public function changLog() {
        $timestamp = time() - 600;

        $your_url = 'https://api.sportradar.us/oddscomparison-rowt1/en/eu/sport_events/' . $timestamp . '/changelog.xml?api_key=fesnnrpjz5spbka659z7jzvv';
        $xml = new SimpleXMLElement(file_get_contents($your_url));
        $value = $this->xml2array($xml);
        echo '<pre>';
        echo $json = json_encode($value);
        print_r($value);
        die;
        $players = array();
        $i = 0;
        foreach ($value['sport_event'] as $player) {
            $attr = $player->attributes();
            print_r($attr);
            // cast the SimpleXMLElement to a string
            // $players[$i]['type'] = (string) $attr['type'];
            // $players[$i]['number'] = (string) $attr['number'];
            // $i++;
        }
        // echo '<pre>';
        // $json = json_encode($players);
        // $array = json_decode($json, true);
        // print_r($array);
    }

    public function xml2array($xmlObject, $out = array()) {
        //print_r($xmlObject);exit;
        foreach ((array) $xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? xml2array($node) : $node;
        return $out;
    }

    /**
     * @ Name addSports
     * @desc fetch sports from API and insert into database
     */
    public function addSports() {
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/sports.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        try {
            $xml = new SimpleXMLElement(file_get_contents($your_url));
            $value = $this->xml2array($xml);
            $Status = FALSE;
            $considerSports = array(
                "sr:sport:2",
                "sr:sport:10",
                "sr:sport:21",
                "sr:sport:16",
                "sr:sport:9",
                "sr:sport:5",
                "sr:sport:12"
            );
            $sportsToInsert = array();
            if (isset($value['sport']) && !empty($value['sport'])) {
                foreach ($value['sport'] as $key => $val) {
                    $sportarray = (array) $val;
                    $sportId = (isset($sportarray['@attributes']['id']) && !empty($sportarray['@attributes']['id'])) ? $sportarray['@attributes']['id'] : '';
                    $sportName = (isset($sportarray['@attributes']['name']) && !empty($sportarray['@attributes']['name'])) ? $sportarray['@attributes']['name'] : '';
                    if (in_array($sportId, $considerSports)) {
                        $sportsToInsert[] = array("sport_id" => $sportId, "sport_name" => $sportName);
                    }
                }
                $this->Cron_model->truncateTableByName('tbl_sports');
                $Status = $this->Cron_model->insertData('tbl_sports', $sportsToInsert);
            }
            if (!empty($Status)) {
                echo 'All Sports added susseccfully in database';
            } else {
                echo 'One or more Sports not added successfully in database';
            }
        } catch (Exception $ex) {
            echo 'Following error occur<br>' . $ex->getMessage();
        }
    }

    /**
     * @ Name addBooks
     * @desc fetch Books from API and insert into database
     */
    public function addBooks() {
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/books.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        $xml = new SimpleXMLElement(file_get_contents($your_url));
        $value = $this->xml2array($xml);
        //print_r($value); die;
        $Status = FALSE;
        foreach ($value['book'] as $key => $val) {
            $bookarray = (array) $val;
            $bookId = (isset($bookarray['@attributes']['id']) && !empty($bookarray['@attributes']['id'])) ? $bookarray['@attributes']['id'] : '';
            $bookName = (isset($bookarray['@attributes']['name']) && !empty($bookarray['@attributes']['name'])) ? $bookarray['@attributes']['name'] : '';
            $isexist = FALSE;
            $isexist = $this->Cron_model->checkexistingData('tbl_books', array("book_id" => $bookId));
            if (empty($isexist)) {
                $insertArray = array("book_id" => $bookId, "book_name" => $bookName, "created_date" => date('Y-m-d H:i:s'));
                $Status = $this->Common_model->insert_single('tbl_books', $insertArray);
            } else {
                $updatetArray = array("book_name" => $bookName);
                $Status = $this->Common_model->update_single('tbl_books', $updatetArray, array("where" => array("book_id" => $bookId)));
            }
        }

        if (!empty($Status)) {
            echo 'All Books added susseccfully in database';
        } else {
            echo 'One or more Book not added successfully in database';
        }
    }

    /**
     * @Name addCategories
     * @desc fetch Categories from API and insert into database
     */
    public function addCategories() {
        $this->Cron_model->truncateTableByName('tbl_categories');
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/categories.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        try {
            $xml = new SimpleXMLElement(file_get_contents($your_url));
            $value = $this->xml2array($xml);
            $Status = FALSE;
            $considerSports = array(
                "sr:sport:2",
                "sr:sport:10",
                "sr:sport:21",
                "sr:sport:1",
                "sr:sport:9",
                "sr:sport:5",
                "sr:sport:12"
            );
            $categoriesToInsert = array();
            if (isset($value['category']) && !empty($value['category'])) {
                foreach ($value['category'] as $key => $val) {
                    $catarray = (array) $val;
                    $catId = (isset($catarray['@attributes']['id']) && !empty($catarray['@attributes']['id'])) ? $catarray['@attributes']['id'] : '';
                    $catName = (isset($catarray['@attributes']['name']) && !empty($catarray['@attributes']['name'])) ? $catarray['@attributes']['name'] : '';
                    $sportid = (isset($catarray['@attributes']['sport_id']) && !empty($catarray['@attributes']['sport_id'])) ? $catarray['@attributes']['sport_id'] : '';
                    $countrycode = (isset($catarray['@attributes']['country_code']) && !empty($catarray['@attributes']['country_code'])) ? $catarray['@attributes']['country_code'] : '';
                    $outrights = (isset($catarray['@attributes']['outrights']) && !empty($catarray['@attributes']['outrights'])) ? 1 : 0;
                    $isexist = FALSE;
                    if (in_array($sportid, $considerSports)) {
                        $categoriesToInsert[] = array("category_id" => $catId, "sport_id" => $sportid, "category_name" => $catName, "country_code" => $countrycode, "outrights" => $outrights);
                    }
                }
                //print_r($categoriesToInsert); die;

                $Status = $this->Cron_model->insertData('tbl_categories', $categoriesToInsert);
            }
            if (!empty($Status)) {
                echo 'All Categories added susseccfully in database';
            } else {
                echo 'One or more Category not added successfully in database';
            }
        } catch (Exception $ex) {
            
        }
    }

    /**
     * @Name addTournaments
     * @Desc fetch Tournaments from API and insert into database
     */
    public function addTournaments() {
        $this->Cron_model->truncateTableByName('tbl_tournament');
        //$this->Cron_model->truncateTableByName('tbl_season');
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        try {
            $xml = new SimpleXMLElement(file_get_contents($your_url));
            $value = $this->xml2array($xml);
            $Status = FALSE;
            $considerSports = array(
                "sr:sport:2",
                "sr:sport:10",
                "sr:sport:21",
                "sr:sport:1",
                "sr:sport:9",
                "sr:sport:5",
                "sr:sport:12"
            );
            $tournamentsToInsert = array();
            $seasonToInsert = array();
            if (isset($value['tournament']) && !empty($value['tournament'])) {
                $sportid = '';

                foreach ($value['tournament'] as $key => $val) {
                    $tournamentarray = (array) $val;
                    $tournamentId = (isset($tournamentarray['@attributes']['id']) && !empty($tournamentarray['@attributes']['id'])) ? $tournamentarray['@attributes']['id'] : '';
                    $tournamentName = (isset($tournamentarray['@attributes']['name']) && !empty($tournamentarray['@attributes']['name'])) ? $tournamentarray['@attributes']['name'] : '';
                    if (isset($tournamentarray['sport'])) {
                        $tournamentSport = (array) $tournamentarray['sport'];
                        $sportid = (isset($tournamentSport['@attributes']['id']) && !empty($tournamentSport['@attributes']['id'])) ? $tournamentSport['@attributes']['id'] : '';
                    }

                    if (isset($tournamentarray['category'])) {
                        $tournamentCategory = (array) $tournamentarray['category'];
                        $categoryid = (isset($tournamentCategory['@attributes']['id']) && !empty($tournamentCategory['@attributes']['id'])) ? $tournamentCategory['@attributes']['id'] : '';
                        $categoryArr = $this->Common_model->fetch_data('tbl_categories', '', array('where' => array('category_id' => $categoryid)), true);
                    }
                    // code to insert into Season Table begins
                    if (isset($tournamentarray['current_season'])) {
                        $tournamentseason = (array) $tournamentarray['current_season'];
                        $seasonid = (isset($tournamentseason['@attributes']['id']) && !empty($tournamentseason['@attributes']['id'])) ? $tournamentseason['@attributes']['id'] : '';
                        $season_name = (isset($tournamentseason['@attributes']['name']) && !empty($tournamentseason['@attributes']['name'])) ? $tournamentseason['@attributes']['name'] : '';
                        $season_start_date = (isset($tournamentseason['@attributes']['start_date']) && !empty($tournamentseason['@attributes']['start_date'])) ? $tournamentseason['@attributes']['start_date'] : '';
                        $season_end_date = (isset($tournamentseason['@attributes']['end_date']) && !empty($tournamentseason['@attributes']['end_date'])) ? $tournamentseason['@attributes']['end_date'] : '';
                        $season_year = (isset($tournamentseason['@attributes']['year']) && !empty($tournamentseason['@attributes']['year'])) ? $tournamentseason['@attributes']['year'] : '';
                    }

                    // code to insert into Season Table Ends
                    if (in_array($sportid, $considerSports)) {
                        $tournamentsToInsert[] = array("tournament_id" => $tournamentId, "sport_id" => $sportid, "sportradar_category_id" => $categoryid, "category_id" => !empty($categoryArr['id']) ? $categoryArr['id'] : '', "tournament_name" => $tournamentName);
                        //$seasonToInsert[] = array("season_id" => $seasonid, "tournament_id" => $tournamentId, "sport_id" => $sportid, "season_name" => $season_name, "start_date" => "$season_start_date", "end_date" => $season_end_date, "year" => "$season_year");
                    }
                }
                //$this->Cron_model->truncateTableByName('tbl_tournament');
                $Status = $this->Cron_model->insertData('tbl_tournament', $tournamentsToInsert);

                //$this->Cron_model->insertData('tbl_season', $seasonToInsert);
            }
            if (!empty($Status)) {
                echo 'All Tournament added susseccfully in database';
            } else {
                echo 'One or more Tournament not added successfully in database';
            }
        } catch (Exception $ex) {
            
        }
    }

    /**
     *
     * @Name addEvent
     * @Date 09/11/2017
     * @Param n/a
     * @Desc get Tournament EVENT from API on the bases of tournament id and insert all events and related data into database
     */
    public function addEvent() {
        try {

            $this->Cron_model->truncateTableByName('tbl_sports_events');
            $this->db->select('tournament_id');
            $this->db->from('tbl_tournament');
            $data = $this->db->get();


            if ($data->num_rows() > 0) {

                foreach ($data->result() as $key => $tvalues) {

                    if (!empty($tvalues->tournament_id)) {

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.xml?api_key=fesnnrpjz5spbka659z7jzvv";
                        //$xml = new SimpleXMLElement(file_get_contents($your_url));
                        //$xml = simplexml_load_string(file_get_contents($your_url));
                        //print_r($data); exit;
                        $considerSports = array(
                            "sr:sport:2",
                            "sr:sport:10",
                            "sr:sport:21",
                            "sr:sport:1",
                            "sr:sport:9",
                            "sr:sport:5",
                            "sr:sport:12"
                        );

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        $html = curl_exec($ch);

                        // $xml = simplexml_load_string($data);
                        // $value = ((array) $xml);

                        if (curl_errno($ch) != '22') {

                            $xml = simplexml_load_string($html);
                            $value = ((array) $xml);

                            if (isset($value) && !empty($value)) {

                                $tournamentInfo = (array) $value['tournament'];
                                $categoryInfo = (array) $tournamentInfo['category'];

                                $tournamentSport = (array) $tournamentInfo['sport'];
                                $tournament_id = $tournamentInfo['@attributes']['id'];
                                $category_id = $categoryInfo['@attributes']['id'];

                                $tournamenr_sport_id = (isset($tournamentSport['@attributes']['id']) && !empty($tournamentSport['@attributes']['id'])) ? $tournamentSport['@attributes']['id'] : '';
                                $tournament_insert_array = array("tournament_id" => $tournament_id, "sport_id" => $tournamenr_sport_id);
                                if (in_array($tournamenr_sport_id, $considerSports)) {
                                    $evntarr = (array) $value ['sport_events']->sport_event;

                                    $sport_event_insert_array = array(
                                        "event_id" => $evntarr['@attributes']['id'],
                                        "sportsradar_category" => $category_id,
                                        "tournament_id" => $tournament_id,
                                        "scheduled" => $evntarr['@attributes']['scheduled'],
                                        "start_time_tbd" => $evntarr['@attributes']['start_time_tbd'],
                                        "event_status" => $evntarr['@attributes']['status'],
                                        "created_date" => date('Y-m-d H:i:s'),
                                        "status" => '1',
                                        "updated_date" => time()
                                    );


                                    $this->Sport_model->insert_single('tbl_sports_events', $sport_event_insert_array);

                                    //print_r($sport_event_insert_array); die;
                                    //$this->Cron_model->truncateTableByName('tbl_sports_events');
                                    // print_r($sport_event_insert_array); exit();
                                }
                            }
                        }
                    }
                }


                echo 'successfully uploaded';
            } else {

                echo 'No result found';
            }
        } catch (Exception $ex) {


            return $ex->getMessage();
        }
    }

    /**
     * @Name checkExistTournamentId
     * @Date 09/11/2017
     * @Param  $tournament_id
     * @Desc this function used for synced data with api to local database to check tournament is exist or not in table season
     *
     * */
    public function checkExistTournamentId($tournament_id) {
        try {

            if (!empty($tournament_id)) {

                $this->db->select('tournament_id');
                $this->db->from('tbl_season');
                $this->db->where('tournament_id', $tournament_id);
                $data = $this->db->get();
                $error = $this->db->error();
                if (isset($error) && !empty($error['code'])) {

                    throw new Exception($error['message']);
                }
                return $data->result();
            } else {

                return FALSE;
            }
        } catch (Exception $ex) {


            return $ex->getMessage();
        }
    }

    /**
     * @Name addSeason
     * @Date 09/11/2017
     * @Param  n/a
     * @Desc this function used for synced data with api to local database to get all Tournament Seasons
     *
     */
    public function addSeason() {

        try {

            $this->Cron_model->truncateTableByName('tbl_season');
            $this->db->select('tournament_id');
            $this->db->from('tbl_sports_events');
            $data = $this->db->get();
            $error = $this->db->error();
            if (isset($error) && !empty($error['code'])) {

                throw new Exception($error['message']);
            }

            if ($data->num_rows() > 0) {

                foreach ($data->result() as $key => $tvalues) {

                    if (!empty($tvalues->tournament_id)) {

                        $check = $this->checkExistTournamentId($tvalues->tournament_id);

                        if (count($check) > 0) {

                            continue;
                        } else {

                            $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.xml?api_key=fesnnrpjz5spbka659z7jzvv";
                            //$xml = simplexml_load_string(file_get_contents($your_url));


                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL, $your_url);
                            curl_setopt($ch, CURLOPT_FAILONERROR, true);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

                            $html = curl_exec($ch);

                            if (curl_errno($ch) != '22') {

                                $considerSports = array(
                                    "sr:sport:2",
                                    "sr:sport:10",
                                    "sr:sport:21",
                                    "sr:sport:1",
                                    "sr:sport:9",
                                    "sr:sport:5",
                                    "sr:sport:12"
                                );

                                $xml = simplexml_load_string($html);
                                $value = ((array) $xml);

                                if (isset($value) && !empty($value)) {
                                    $tournamentInfo = (array) $value['tournament'];
                                    $tournamentSport = (array) $tournamentInfo['sport'];
                                    $tournament_id = $tournamentInfo['@attributes']['id'];
                                    $tournamenr_sport_id = (isset($tournamentSport['@attributes']['id']) && !empty($tournamentSport['@attributes']['id'])) ? $tournamentSport['@attributes']['id'] : '';
                                    $tournament_insert_array = array("tournament_id" => $tournament_id, "sport_id" => $tournamenr_sport_id);
                                    if (in_array($tournamenr_sport_id, $considerSports)) {
                                        $evntarr = (array) $value ['sport_events']->sport_event;

                                        $sport_event_insert_array = array(
                                            "event_id" => $evntarr['@attributes']['id'],
                                            "tournament_id" => $tournament_id,
                                            "scheduled" => $evntarr['@attributes']['scheduled'],
                                            "start_time_tbd" => $evntarr['@attributes']['start_time_tbd'],
                                            "event_status" => $evntarr['@attributes']['status'],
                                            "created_date" => date('Y-m-d H:i:s'),
                                            "status" => '1',
                                            "updated_date" => time()
                                        );

                                        if (isset($value['sport_events']) && !empty($value['sport_events'])) {

                                            foreach ($value['sport_events'] as $key => $val) {
                                                if (isset($value['sport_events']) && !empty($value['sport_events'])) {
                                                    $eventarray = (array) $val;

                                                    if (isset($eventarray['tournament_round']) && !empty($eventarray['tournament_round'])) {
                                                        $tournament_roundinfo = (array) $eventarray['tournament_round'];
                                                        $tournament_round_type = (isset($tournament_roundinfo['@attributes']['type']) && !empty($tournament_roundinfo['@attributes']['type'])) ? $tournament_roundinfo['@attributes']['type'] : '';
                                                        $tournament_round_name = (isset($tournament_roundinfo['@attributes']['name']) && !empty($tournament_roundinfo['@attributes']['name'])) ? $tournament_roundinfo['@attributes']['name'] : '';
                                                        $tournament_insert_array["tournament_round_type"] = $tournament_round_type;
                                                        $tournament_insert_array["tournament_round_name"] = $tournament_round_name;
                                                    }
                                                    if (isset($eventarray['season']) && !empty($eventarray['season'])) {
                                                        $tournament_seasoninfo = (array) $eventarray['season'];
                                                        $tournament_season_id = (isset($tournament_seasoninfo['@attributes']['id']) && !empty($tournament_seasoninfo['@attributes']['id'])) ? $tournament_seasoninfo['@attributes']['id'] : '';
                                                        $tournament_season_name = (isset($tournament_seasoninfo['@attributes']['name']) && !empty($tournament_seasoninfo['@attributes']['name'])) ? $tournament_seasoninfo['@attributes']['name'] : '';
                                                        $tournament_season_start_date = (isset($tournament_seasoninfo['@attributes']['start_date']) && !empty($tournament_seasoninfo['@attributes']['start_date'])) ? $tournament_seasoninfo['@attributes']['start_date'] : '';
                                                        $tournament_season_end_date = (isset($tournament_seasoninfo['@attributes']['end_date']) && !empty($tournament_seasoninfo['@attributes']['end_date'])) ? $tournament_seasoninfo['@attributes']['end_date'] : '';
                                                        $tournament_season_year = (isset($tournament_seasoninfo['@attributes']['year']) && !empty($tournament_seasoninfo['@attributes']['year'])) ? $tournament_seasoninfo['@attributes']['year'] : '';
                                                        $season_insert_array = array(
                                                            "season_id" => $tournament_season_id,
                                                            "tournament_id" => $tournament_id,
                                                            "sportsradar_event_id" => $evntarr['@attributes']['id'],
                                                            "sport_id" => $tournamenr_sport_id,
                                                            "season_name" => $tournament_season_name,
                                                            "start_date" => $tournament_season_start_date,
                                                            "end_date" => $tournament_season_end_date,
                                                            "year" => $tournament_season_year
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->Sport_model->addEvents('tbl_season', $season_insert_array);
                }

                echo 'Season data Synced Successfully!';
                // echo 'successfully uploaded';
            } else {

                echo 'No result found';
            }
        } catch (Exception $ex) {


            return $ex->getMessage();
        }
    }

    /**
     * @Name tournamentSchedule
     * @Date 09/11/2017
     * @Param n/a
     * @Desc this function used for synced data with api to local database to get all Tournament Schedule
     *
     */
    public function tournamentSchedule() {
        try {


            $this->db->select('tournament_id');
            $this->db->from('tbl_season');
            $data = $this->db->get();
            $error = $this->db->error();
            if (isset($error) && !empty($error['code'])) {

                throw new Exception($error['message']);
            }
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key => $tvalues) {

                    if (!empty($tvalues->tournament_id)) {

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.xml?api_key=fesnnrpjz5spbka659z7jzvv";

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_URL, $your_url);    // get the url contents

                        $data = curl_exec($ch); // execute curl request
                        curl_close($ch);

                        //$xml = simplexml_load_string($data);
                        //print_r($xml);
                        //exit;

                        $xml = simplexml_load_string(file_get_contents($your_url));

                        //print_r($xml);

                        $considerSports = array(
                            "sr:sport:2",
                            "sr:sport:12",
                            "sr:sport:5",
                            "sr:sport:9",
                            "sr:sport:21",
                            "sr:sport:10",
                            "sr:sport:16"
                        );


                        $value = ((array) $xml);
                        //print_r($value); die;
                        if (isset($value) && !empty($value)) {
                            $tournamentInfo = (array) $value['tournament'];
                            $tournamentSport = (array) $tournamentInfo['sport'];
                            $tournament_id = $tournamentInfo['@attributes']['id'];
                            $tournamenr_sport_id = (isset($tournamentSport['@attributes']['id']) && !empty($tournamentSport['@attributes']['id'])) ? $tournamentSport['@attributes']['id'] : '';
                            $tournament_insert_array = array("tournament_id" => $tournament_id, "sport_id" => $tournamenr_sport_id);

                            if (in_array($tournamenr_sport_id, $considerSports)) {
                                $evntarr = (array) $value ['sport_events']->sport_event;

                                $sport_event_insert_array = array(
                                    "event_id" => $evntarr['@attributes']['id'],
                                    "tournament_id" => $tournament_id,
                                    "scheduled" => $evntarr['@attributes']['scheduled'],
                                    "start_time_tbd" => $evntarr['@attributes']['start_time_tbd'],
                                    "event_status" => $evntarr['@attributes']['status'],
                                    "created_date" => date('Y-m-d H:i:s'),
                                    "status" => '1',
                                    "updated_date" => time()
                                );

                                $Status = FALSE;
                                if (isset($value['sport_events']) && !empty($value['sport_events'])) {

                                    foreach ($value['sport_events'] as $key => $val) {
                                        if (isset($value['sport_events']) && !empty($value['sport_events'])) {
                                            $eventarray = (array) $val;

                                            if (isset($eventarray['tournament_round']) && !empty($eventarray['tournament_round'])) {
                                                $tournament_roundinfo = (array) $eventarray['tournament_round'];
                                                $tournament_round_type = (isset($tournament_roundinfo['@attributes']['type']) && !empty($tournament_roundinfo['@attributes']['type'])) ? $tournament_roundinfo['@attributes']['type'] : '';
                                                $tournament_round_name = (isset($tournament_roundinfo['@attributes']['name']) && !empty($tournament_roundinfo['@attributes']['name'])) ? $tournament_roundinfo['@attributes']['name'] : '';
                                                $tournament_insert_array["tournament_round_type"] = $tournament_round_type;
                                                $tournament_insert_array["tournament_round_name"] = $tournament_round_name;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }


                    $this->Sport_model->addEvents('tbl_tournament_schedule', $tournament_insert_array);
                }
                echo 'data Synced Successfully!';
                exit();
            }
        } catch (Exception $ex) {
            echo 'error occur' . $ex->getMessage();
        }
    }

    /**
     * @Name sheduledVenue
     * @Param n/a
     * @Date 11/09/2017
     * @Desc this function used for synced data with api to local database
     *
     */
    public function sheduledVenue() {

        try {

            $this->db->select('tournament_id');
            $this->db->from('tbl_tournament');
            $data = $this->db->get();
            $error = $this->db->error();
            if (isset($error) && !empty($error['code'])) {

                throw new Exception($error['message']);
            }
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key => $tvalues) {

                    if (!empty($tvalues->tournament_id)) {

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.xml?api_key=fesnnrpjz5spbka659z7jzvv";
                        //$xml = simplexml_load_string(file_get_contents($your_url));

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

                        $html = curl_exec($ch);

                        if (curl_errno($ch) != '22') {

                            //print_r($xml);

                            $considerSports = array(
                                "sr:sport:2",
                                "sr:sport:10",
                                "sr:sport:21",
                                "sr:sport:1",
                                "sr:sport:9",
                                "sr:sport:5",
                                "sr:sport:12"
                            );
                            $sportEventsToInsert = array();

                            //print_r($xml);exit;
                            $xml = simplexml_load_string($html);
                            $value = ((array) $xml);
                            //print_r($value); die;
                            if (isset($value) && !empty($value)) {
                                $tournamentInfo = (array) $value['tournament'];
                                $tournamentSport = (array) $tournamentInfo['sport'];

                                $tournamenr_sport_id = (isset($tournamentSport['@attributes']['id']) && !empty($tournamentSport['@attributes']['id'])) ? $tournamentSport['@attributes']['id'] : '';

                                if (in_array($tournamenr_sport_id, $considerSports)) {


                                    if (isset($value['sport_events']) && !empty($value['sport_events'])) {

                                        foreach ($value['sport_events'] as $key => $val) {
                                            if (isset($value['sport_events']) && !empty($value['sport_events'])) {
                                                $eventarray = (array) $val;

                                                $eventId = (isset($eventarray['@attributes']['id']) && !empty($eventarray['@attributes']['id'])) ? $eventarray['@attributes']['id'] : '';

                                                $sportEventsToInsert[] = array();

                                                if (isset($eventarray['venue']) && !empty($eventarray['venue'])) {
                                                    $venueArr = (array) $eventarray['venue']; //(array)$valv;
                                                    $venidId = (isset($venueArr['@attributes']['id']) && !empty($venueArr['@attributes']['id'])) ? $venueArr['@attributes']['id'] : '';
                                                    $venName = (isset($venueArr['@attributes']['name']) && !empty($venueArr['@attributes']['name'])) ? $venueArr['@attributes']['name'] : '';
                                                    $venCcapacity = (isset($venueArr['@attributes']['capacity']) && !empty($venueArr['@attributes']['capacity'])) ? $venueArr['@attributes']['capacity'] : '';
                                                    $venCity = (isset($venueArr['@attributes']['city_name']) && !empty($venueArr['@attributes']['city_name'])) ? $venueArr['@attributes']['city_name'] : '';
                                                    $vencountry = (isset($venueArr['@attributes']['country_name']) && !empty($venueArr['@attributes']['country_name'])) ? $venueArr['@attributes']['country_name'] : '';
                                                    $mapcordinates = (isset($venueArr['@attributes']['map_coordinates']) && !empty($venueArr['@attributes']['map_coordinates'])) ? $venueArr['@attributes']['map_coordinates'] : '';
                                                    $venCountryCode = (isset($venueArr['@attributes']['country_code']) && !empty($venueArr['@attributes']['country_code'])) ? $venueArr['@attributes']['country_code'] : '';
                                                    $isexist = FALSE;
                                                    $insertArray = array(
                                                        "venue_id" => $venidId,
                                                        "venue_name" => $venName,
                                                        "event_id" => $eventId,
                                                        "capacity" => $venCcapacity,
                                                        "city_name" => $venCity,
                                                        "country_name" => $vencountry,
                                                        "map_coordinates" => $mapcordinates,
                                                        "country_code" => $venCountryCode
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $this->Sport_model->addEvents('tbl_venue', $insertArray);
                }

                echo 'data Synced Successfully!';
            } else {

                echo 'No Data found to Synced';
            }
        } catch (Exception $ex) {


            return $ex->getMessage();
        }
        return FALSE;
    }

    /**
     * @Name addMarkets
     * @Param n/a
     * @Date 11/20/2017
     * @Desc this function used for synced data with api to local database
     *
     */
    public function addMarkets() {

        try {


            $this->Cron_model->truncateTableByName('tbl_markets');
            $this->Cron_model->truncateTableByName('tbl_market_outcome');

            $this->db->select('tournament_id');
            $this->db->from('tbl_tournament');
            $data = $this->db->get();
            $error = $this->db->error();

            if (isset($error) && !empty($error['code'])) {

                throw new Exception($error['message']);
            }
            if ($data->num_rows() > 0) {
                //$i= 1; 
                foreach ($data->result() as $key => $tvalues) {
//echo $i;
                    if (!empty($tvalues->tournament_id)) {

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.xml?api_key=fesnnrpjz5spbka659z7jzvv";

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        $html = curl_exec($ch);

                        //print_r($html);
                        /* if (!$html) {
                          echo "<br />cURL error number:" . curl_errno($ch);
                          echo "<br />cURL error:" . curl_error($ch);
                          continue;
                          } else {


                          //echo "<pre>";
                          //print_r($value); die;
                          } */
                        //$xml = new SimpleXMLElement(file_get_contents($your_url));

                        if (curl_errno($ch) != '22') {

                            $xml = simplexml_load_string($html);
                            $value = ((array) $xml);

                            $considerSports = array(
                                "sr:sport:2",
                                "sr:sport:12",
                                "sr:sport:5",
                                "sr:sport:9",
                                "sr:sport:21",
                                "sr:sport:10",
                                "sr:sport:1"
                            );

                            if (isset($value) && !empty($value)) {
                                $tournamentInfo = (array) $value['tournament'];
                                $tournamentSport = (array) $tournamentInfo['sport'];
                                $tournamentCategory = (array) $tournamentInfo['category'];

                                $tournament_id = (isset($tournamentInfo['@attributes']['id']) && !empty($tournamentInfo['@attributes']['id'])) ? $tournamentInfo['@attributes']['id'] : '';
                                $tournamenr_sport_id = (isset($tournamentSport['@attributes']['id']) && !empty($tournamentSport['@attributes']['id'])) ? $tournamentSport['@attributes']['id'] : '';
                                $tournamenr_category_id = (isset($tournamentCategory['@attributes']['id']) && !empty($tournamentCategory['@attributes']['id'])) ? $tournamentCategory['@attributes']['id'] : '';

                                if (in_array($tournamenr_sport_id, $considerSports)) {


                                    if (isset($value['sport_events']) && !empty($value['sport_events'])) {

                                        foreach ($value['sport_events'] as $key => $val) {
                                            if (isset($value['sport_events']) && !empty($value['sport_events'])) {
                                                $eventarray = (array) $val;
                                                //print_r(json_encode()); die;
                                                $eventId = (isset($eventarray['@attributes']['id']) && !empty($eventarray['@attributes']['id'])) ? $eventarray['@attributes']['id'] : '';

                                                //$sportMarketToInsert[] = array();

                                                if (isset($eventarray['markets']) && !empty($eventarray['markets'])) {
                                                    $marketsArr = (array) $eventarray['markets'];
                                                    //print_r($marketsArr);
                                                    $newMarketArr = $marketsArr['market'];
                                                    //print_r($newMarketArr); die;
                                                    if (!empty($newMarketArr)) {
                                                        foreach ($newMarketArr as $mark) {
                                                            $markval = (array) $mark;

                                                            $marketName = (isset($markval['@attributes']['name']) && !empty($markval['@attributes']['name'])) ? $markval['@attributes']['name'] : '';
                                                            $tournament_id = (isset($tournament_id) && !empty($tournament_id)) ? $tournament_id : '';
                                                            $groupName = (isset($markval['@attributes']['group_name']) && !empty($markval['@attributes']['group_name'])) ? $markval['@attributes']['group_name'] : '';
                                                            $tournamenr_sport_id = (isset($tournamenr_sport_id) && !empty($tournamenr_sport_id)) ? $tournamenr_sport_id : '';
                                                            $tournamenr_category_id = (isset($tournamenr_category_id) && !empty($tournamenr_category_id)) ? $tournamenr_category_id : '';
                                                            $eventId = (isset($eventId) && !empty($eventId)) ? $eventId : '';
                                                            $isexist = FALSE;

                                                            if ((!empty($marketName)) && !empty($groupName)) {// && ($marketName == '2way' || $marketName == '3way' || $marketName == 'handicap')
                                                                $sportMarketToInsert = array(
                                                                    "market_name" => $marketName,
                                                                    "group_name" => $groupName,
                                                                    "match_id" => $eventId,
                                                                    "tournament_id" => $tournament_id,
                                                                    "sport_id" => $tournamenr_sport_id,
                                                                    "category_id" => $tournamenr_category_id,
                                                                    "created_date" => date('Y-m-d H:i:s'),
                                                                    "updated_date" => date('Y-m-d H:i:s'),
                                                                    "status" => '1'
                                                                );

                                                                $market_id = $this->Sport_model->insert_single('tbl_markets', $sportMarketToInsert);
                                                                if ($market_id) {

                                                                    $bookval = (array) $markval['book'];
                                                                    //print_r($bookval); die;
                                                                    if (!empty($bookval)) {
                                                                        foreach ($bookval as $book) {


                                                                            $nbookval = (array) $book;
                                                                            $book_id = (isset($nbookval['@attributes']['id']) && !empty($nbookval['@attributes']['id'])) ? $nbookval['@attributes']['id'] : '';
                                                                            $is_removed = (isset($nbookval['@attributes']['removed']) && !empty($nbookval['@attributes']['removed'])) ? $nbookval['@attributes']['removed'] : FALSE;
                                                                            $outcomeArr = isset($nbookval['outcome']) ? $nbookval['outcome'] : '';

                                                                            if ($is_removed === FALSE) {
                                                                                if (!empty($outcomeArr)) {

                                                                                    foreach ($outcomeArr as $out) {

                                                                                        $outval = (array) $out;
                                                                                        //print_r($outval); die;
                                                                                        $sportOutComeToInsert = array(
                                                                                            "type" => isset($outval['@attributes']['type']) ? $outval['@attributes']['type'] : '',
                                                                                            "odds" => isset($outval['@attributes']['odds']) ? $outval['@attributes']['odds'] : '',
                                                                                            "total" => isset($outval['@attributes']['total']) ? $outval['@attributes']['total'] : '',
                                                                                            "deep_link" => isset($outval['@attributes']['deep_link']) ? $outval['@attributes']['deep_link'] : '',
                                                                                            "book_id" => isset($book_id) ? $book_id : '',
                                                                                            "market_id" => isset($market_id) ? $market_id : '',
                                                                                            "created_date" => date('Y-m-d H:i:s'),
                                                                                            "updated_date" => date('Y-m-d H:i:s'),
                                                                                            "status" => '1'
                                                                                        );

                                                                                        $this->Sport_model->insert_single('tbl_market_outcome', $sportOutComeToInsert);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }




                        //$sportMarketToInsert = array();
                        //print_r($xml);exit;
                        //$value = ((array)$xml);
                        //print_r($value); die;
                    }
                    //$i++;
                }

                echo 'data Synced Successfully!';
            } else {

                echo 'No Data found to Synced';
            }
        } catch (Exception $ex) {


            return $ex->getMessage();
        }
        return FALSE;
    }

    public function addBookiesOdds() {
        try {
            $this->Cron_model->truncateTableByName('tbl_bookies_odds');
            $this->db->select('category_id');
            $this->db->from('tbl_categories');
            $this->db->order_by('id', 'asc');
            $data = $this->db->get();

            //echo $this->db->last_query(); die;
            if ($data->num_rows() > 0) {

                $resultArr = $data->result_array();

                foreach ($resultArr as $key1 => $catvalues) {

                    // print_r($catvalues); die;
                    if (!empty($catvalues['category_id'])) {

                         $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/categories/" . $catvalues['category_id'] . "/outrights.xml?api_key=fesnnrpjz5spbka659z7jzvv";// die;

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        $html = curl_exec($ch);
                        //print_r($html);
                        /*if (!$html) {
                            echo "<br />cURL error number:" . curl_errno($ch);
                            echo "<br />cURL error:" . curl_error($ch);
                            exit;
                        } else */
                        if(curl_errno($ch)!='22' && curl_errno($ch)!='0') {
                            
                            echo "<br />cURL error number:" . curl_errno($ch);
                            echo "<br />cURL error:" . curl_error($ch);

                            // $xml = new SimpleXMLElement(file_get_contents($your_url));

                            $xml = simplexml_load_string($html);
                            //die;

                            $value = ((array) $xml);
                            //echo "<pre>";
                            //print_r($value);
                            // $outArr=array();
                            $sportId = $value['sport']['id'];
                            $sportId = ((array) $sportId);
                            $sport_Id = $sportId[0];
                            $catId = $value['category']['id']; //die;
                            $catId = ((array) $catId);
                            $cat_Id = $catId[0];

                            $outArr = $value['outrights'];
                            echo "<pre>";
                            print_r($outArr); die;
                            if (!empty($outArr)) {

                                foreach ($outArr as $key2 => $valueout) {
                                    $valueout = (array) $valueout;
                                    $comArr = $valueout['competitors'];
                                    $comArr = (array) $comArr;
                                    //die;
                                    foreach ($comArr['competitor'] as $key3 => $valuescom) {
                                        $valuescom = (array) $valuescom;
                                        $comptitorsId = $valuescom['@attributes']['id'];
                                        $bookArr = $valuescom['books'];
                                        $bookArr = (array) $bookArr;

                                        foreach ($bookArr['book'] as $key4 => $valuesbook) {

                                            $valuesbook = (array) $valuesbook;
                                            $bookiesId = isset($valuesbook['@attributes']['id'])?$valuesbook['@attributes']['id']:'';
                                            $bookiesodds = isset($valuesbook['@attributes']['odds'])?$valuesbook['@attributes']['odds']:'';
                                            $bookiesremove = isset($valuesbook['@attributes']['removed'])?$valuesbook['@attributes']['removed']:FALSE;

                                            if ($bookiesremove==FALSE) {

                                                $bookies_details_odds = array(
                                                    'sports_id' => $sport_Id,
                                                    'category_id' => $cat_Id,
                                                    'comptitor_id' => $comptitorsId,
                                                    'bookie_id' => $bookiesId,
                                                    'odds' => $bookiesodds,
                                                    'create_date' => date('Y-m-d H:i:s')
                                                );
                                                //echo "<pre>";
                                                print_r($bookies_details_odds);
                                                $this->Sport_model->addEvents('tbl_bookies_odds', $bookies_details_odds);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                echo 'odds data Synced Successfully!';
            } else {

                echo 'No result found';
            }
        } catch (Exception $ex) {


            return $ex->getMessage();
        }
    }

    public function addBokiesAndComptitor() {

        $valuesbook['@attributes']['odds'] = '';
        $valuescom['@attributes']['id'] = '';
        $valuescom['@attributes']['name'] = '';
        $valuescom['@attributes']['country'] = '';
        $valuescom['@attributes']['country_code'] = '';

        try {


            $this->db->select('category_id');
            $this->db->from('tbl_categories');
            $this->db->order_by('id', 'asc');
            $data = $this->db->get();

            //echo $this->db->last_query(); die;
            if ($data->num_rows() > 0) {

                $resultArr = $data->result_array();

                foreach ($resultArr as $key1 => $catvalues) {

                    if (!empty($catvalues['category_id'])) {

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/categories/" . $catvalues['category_id'] . "/outrights.json?api_key=fesnnrpjz5spbka659z7jzvv";

                        $data=json_decode($your_url);


                        $xml = simplexml_load_string(file_get_contents($your_url));


                        $value = ((array) $xml);
                        print_r($your_url) ; exit;

                        $sportId = $value['sport']['id'];
                        $sportId = ((array) $sportId);
                        $sport_Id = $sportId[0];
                        $catId = $value['category']['id']; //die;
                        $catId = ((array) $catId);
                        $cat_Id = $catId[0];

                        $outArr = $value['outrights'];
                        //echo "<pre>";
                        //print_r($outArr);
                        if (!empty($outArr)) {

                            foreach ($outArr as $key2 => $valueout) {
                                $valueout = (array) $valueout;
                                $tournament_id = $valueout['@attributes']['tournament_id'];
                                $comArr = $valueout['competitors'];
                                $comArr = (array) $comArr;
                                //die;
                                if (!empty($comArr)) {

                                    foreach ($comArr['competitor'] as $key3 => $valuescom) {
                                        $valuescom = (array) $valuescom;
                                        $comptitorsId = $valuescom['@attributes']['id'];
                                        $comptitorsName = $valuescom['@attributes']['name'];
                                        $comptitorsCountry = $valuescom['@attributes']['country'];
                                        $comptitorsCountry_code = $valuescom['@attributes']['country_code'];
                                        $bookArr = $valuescom['books'];
                                        $bookArr = (array) $bookArr;

                                        foreach ($bookArr['book'] as $key4 => $valuesbook) {

                                            $valuesbook = (array) $valuesbook;
                                            $bookiesId = $valuesbook['@attributes']['id'];
                                            $bookiesodds = $valuesbook['@attributes']['odds'];

                                            if (!empty($bookiesodds) && !empty($comptitorsName)) {

                                                $bookies_details_odds = array(
                                                    'sports_id' => $sport_Id,
                                                    'category_id' => $cat_Id,
                                                    'tournament_id' => $tournament_id,
                                                    'comptitors_id' => $comptitorsId,
                                                    'bookies_id' => $bookiesId,
                                                    'name' => $comptitorsName,
                                                    'country' => $comptitorsCountry,
                                                    'country_code' => $comptitorsCountry_code,
                                                    'odds' => $bookiesodds,
                                                    'date_created' => date('Y-m-d H:i:s')
                                                );
                                                //echo "<pre>";
//print_r($bookies_details_odds); die;
                                                $this->Sport_model->addEvents('tbl_comptitor_list', $bookies_details_odds);
                                            }
                                        }
                                    }
                                }
                            }

                            echo 'data synced successfully!';
                        }
                    }
                }
            }
        } catch (Exception $ex) {

            return $ex->getMessage();
        }
    }

    public function addComptitors() {


        try {

            $this->Cron_model->truncateTableByName('tbl_competitors');
            $this->db->select('tournament_id');
            $this->db->from('tbl_tournament');
            $this->db->order_by('id', 'asc');
            $data = $this->db->get();
            $error = $this->db->error();
            if (isset($error) && !empty($error['code'])) {

                throw new Exception($error['message']);
            }


            if ($data->num_rows() > 0) {

                foreach ($data->result() as $key => $tvalues) {

                    if (!empty($tvalues->tournament_id)) {

                        $check = $this->checkExistTournamentId($tvalues->tournament_id);


                        //if (count($check) > 0) {
                        // continue;
                        // } else {

                         $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.xml?api_key=fesnnrpjz5spbka659z7jzvv";
                        // $xml = simplexml_load_string(file_get_contents($your_url));

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        $html = curl_exec($ch);

                        /*   if (!$html) {
                          echo "<br />cURL error number:" . curl_errno($ch);
                          echo "<br />cURL error:" . curl_error($ch);
                          //exit;
                          continue;
                          } else */
                        if (curl_errno($ch) != '22') {

                          //  echo "<br />cURL error number:" . curl_errno($ch);
                           // echo "<br />cURL error:" . curl_error($ch);
                          
                            $considerSports = array(
                                "sr:sport:2",
                                "sr:sport:10",
                                "sr:sport:21",
                                "sr:sport:1",
                                "sr:sport:9",
                                "sr:sport:5",
                                "sr:sport:12"
                            );

                            $xml = simplexml_load_string($html);

                            $value = ((array) $xml);


                            if (isset($value) && !empty($value)) {
                                $tournamentInfo = (array) $value['tournament'];
                                $tournamentSport = (array) $tournamentInfo['sport'];
                                $tournamentCategory = (array) $tournamentInfo['category'];

                                $tournament_id = $tournamentInfo['@attributes']['id'];
                                $category_id = $tournamentCategory['@attributes']['id'];
                                $tournamenr_sport_id = (isset($tournamentSport['@attributes']['id']) && !empty($tournamentSport['@attributes']['id'])) ? $tournamentSport['@attributes']['id'] : '';
                                $tournament_insert_array = array("tournament_id" => $tournament_id, "sport_id" => $tournamenr_sport_id);
                                if (in_array($tournamenr_sport_id, $considerSports)) {
                                    $evntarr = (array) $value ['sport_events'];


                                    $totalEventArr = $evntarr['sport_event'];

                                    if (!empty($totalEventArr)) {

                                        foreach ($totalEventArr as $tevent) {

                                            $eventval = (array) $tevent;
                                            $event_id = $eventval['@attributes']['id'];

                                            if (!empty($eventval['competitors'])) {
                                                print_r($eventval); exit;
                                                $comptitorsArr = (array) $eventval['competitors'];

                                                $comptitorArr = (array) $comptitorsArr['competitor'];

                                                if (!empty($comptitorArr)) {

                                                    foreach ($comptitorArr as $com) {

                                                        $comval = (array) $com;

                                                        $com_id = (isset($comval['@attributes']['id']) && !empty($comval['@attributes']['id'])) ? $comval['@attributes']['id'] : '';
                                                        $com_name = (isset($comval['@attributes']['name']) && !empty($comval['@attributes']['name'])) ? $comval['@attributes']['name'] : '';
                                                        $com_country = (isset($comval['@attributes']['country']) && !empty($comval['@attributes']['country'])) ? $comval['@attributes']['country'] : '';
                                                        $com_code = (isset($comval['@attributes']['country_code']) && !empty($comval['@attributes']['country_code'])) ? $comval['@attributes']['country_code'] : '';
                                                        $com_abbr = (isset($comval['@attributes']['abbreviation']) && !empty($comval['@attributes']['abbreviation'])) ? $comval['@attributes']['abbreviation'] : '';
                                                        $com_qua = (isset($comval['@attributes']['qualifier']) && !empty($comval['@attributes']['qualifier'])) ? $comval['@attributes']['qualifier'] : '';
                                                        $tournament_id = isset($tournament_id) ? $tournament_id : '';
                                                        $category_id = isset($category_id) ? $category_id : '';
                                                        $event_id = isset($event_id) ? $event_id : '';

                                                        $insertCompetitorArr['competitor_id'] = $com_id;
                                                        $insertCompetitorArr['category_id'] = $category_id;
                                                        $insertCompetitorArr['tournament_id'] = $tournament_id;
                                                        $insertCompetitorArr['competitor_name'] = !empty($com_name) ? $com_name : '';
                                                        $insertCompetitorArr['event_id'] = $event_id;
                                                        $insertCompetitorArr['country'] = !empty($com_country) ? $com_country : '';
                                                        $insertCompetitorArr['country_code'] = !empty($com_code) ? $com_code : '';
                                                        $insertCompetitorArr['abbreviation'] = !empty($com_abbr) ? $com_abbr : '';
                                                        $insertCompetitorArr['qualifier'] = !empty($com_qua) ? $com_qua : '';
                                                        $insertCompetitorArr['created_date'] = date('Y-m-d H:i:s');
                                                        //$insertCompetitorArr['competitor_id']=$com_id;
                                                        $insertCompetitorArr['status'] = '1';

                                                        //print_r($insertCompetitorArr); //die;
                                                        $succ = $this->Sport_model->addEvents('tbl_competitors', $insertCompetitorArr);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }




                        //}
                    }
                    //$this->Sport_model->addEvents('tbl_season', $season_insert_array);
                }

                if (!empty($succ)) {

                    echo 'Comptitors data Synced Successfully!';
                }
                // echo 'successfully uploaded';
            } else {

                echo 'No result found';
            }
        } catch (Exception $ex) {


            return $ex->getMessage();
        }
    }

}
