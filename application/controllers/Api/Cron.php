<?php

require APPPATH . 'libraries/REST_Controller.php';
//require APPPATH . 'libraries/twilioapi/Twilio/autoload.php';



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//use Twilio\Rest\Client;

/**
 * Description of Api-signup
 *
 * @author 
 */
class Cron extends REST_Controller {

    public function __construct() {
        parent::__construct();

        //echo "ghh"; die;
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->helper('cookie');
        $this->load->model('Sport_model');
        $this->load->model('Cron_model');
        $this->load->language('common');
        $this->load->library('session');
    }

    public function index_get() {

        //$this->addSports();
        //$this->addCategories();
        //$this->addTournaments();
        //$this->tournamentSchedule();
        //$this->addEvent();
        //$this->tournamentSchedule();
        //$this->addSeason();
        //$this->addComptitors();
        $this->addBokiesAndComptitor();
    }

    public function index_post() {

        $postDataArr = $this->post();
    }

    private function xml2array($xmlObject, $out = array()) {
        //print_r($xmlObject);exit;
        foreach ((array) $xmlObject as $index => $node)
            $out[$index] = ( is_object($node) ) ? xml2array($node) : $node;
        return $out;
    }

    /**
     * @ Name addSports
     * @desc fetch sports from API and insert into database
     */
    public function addSports() {
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/sports.json?api_key=fesnnrpjz5spbka659z7jzvv";
        try {


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $your_url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            //echo "<br />cURL error number:" . curl_errno($ch);
            //echo "<br />cURL error:" . curl_error($ch);
            //$xml = new SimpleXMLElement(file_get_contents($your_url));
            //$value = $this->xml2array($xml);

            $value = json_decode($response, TRUE);
            //print_r($value); die;
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
            $sportsToInsert = array();
            if (isset($value['sports']) && !empty($value['sports'])) {
                foreach ($value['sports'] as $key => $val) {

                    $sportId = (isset($val['id']) && !empty($val['id'])) ? $val['id'] : '';
                    $sportName = (isset($val['name']) && !empty($val['name'])) ? $val['name'] : '';
                    $isRemoved = (isset($val['removed']) && !empty($val['removed'])) ? $val['removed'] : FALSE;
                    if ((in_array($sportId, $considerSports)) && ($isRemoved == FALSE)) {
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
     * @Name addCategories
     * @desc fetch Categories from API and insert into database
     */
    public function addCategories() {
        $this->Cron_model->truncateTableByName('tbl_categories');
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/categories.json?api_key=fesnnrpjz5spbka659z7jzvv";
        try {


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $your_url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            $value = json_decode($response, TRUE);

            //print_r($value); die;
            //$value = $this->xml2array($xml);
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
            if (isset($value['categories']) && !empty($value['categories'])) {
                foreach ($value['categories'] as $key => $val) {
                    //$catarray = (array) $val;
                    $catId = (isset($val['id']) && !empty($val['id'])) ? $val['id'] : '';
                    $catName = (isset($val['name']) && !empty($val['name'])) ? $val['name'] : '';
                    $sportid = (isset($val['sport_id']) && !empty($val['sport_id'])) ? $val['sport_id'] : '';
                    $countrycode = (isset($val['country_code']) && !empty($val['country_code'])) ? $val['country_code'] : '';
                    $outrights = (isset($val['outrights']) && !empty($val['outrights'])) ? 1 : 0;
                    $isRemoved = (isset($val['removed']) && !empty($val['removed'])) ? $val['removed'] : FALSE;
                    $isexist = FALSE;
                    if ((in_array($sportid, $considerSports)) && ($isRemoved == FALSE)) {
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
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments.json?api_key=fesnnrpjz5spbka659z7jzvv";
        try {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $your_url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            $value = json_decode($response, TRUE);

            //print_r($value); die;
            //$xml = new SimpleXMLElement(file_get_contents($your_url));
            //$value = $this->xml2array($xml);
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
            if (isset($value['tournaments']) && !empty($value['tournaments'])) {


                foreach ($value['tournaments'] as $key => $tournamentarray) {
                    //$tournamentarray = (array) $val;
                    $tournamentId = (isset($tournamentarray['id']) && !empty($tournamentarray['id'])) ? $tournamentarray['id'] : '';
                    $tournamentName = (isset($tournamentarray['name']) && !empty($tournamentarray['name'])) ? $tournamentarray['name'] : '';
                    $isRemoved = (isset($tournamentarray['removed']) && !empty($tournamentarray['removed'])) ? $tournamentarray['removed'] : FALSE;

                    if (isset($tournamentarray['sport'])) {
                        $tournamentSport = $tournamentarray['sport'];
                        $sportid = (isset($tournamentSport['id']) && !empty($tournamentSport['id'])) ? $tournamentSport['id'] : '';
                    }

                    if (isset($tournamentarray['category'])) {
                        $tournamentCategory = $tournamentarray['category'];
                        $categoryid = (isset($tournamentCategory['id']) && !empty($tournamentCategory['id'])) ? $tournamentCategory['id'] : '';
                        $categoryArr = $this->Sport_model->fetch_data('tbl_categories', '', array('where' => array('category_id' => $categoryid)), true);
                    }


                    // code to insert into Season Table Ends
                    if ((in_array($sportid, $considerSports)) && ($isRemoved == FALSE)) {
                        $tournamentsToInsert = array("tournament_id" => !empty($tournamentId) ? $tournamentId : '', "sport_id" => !empty($sportid) ? $sportid : '', "sportradar_category_id" => !empty($categoryid) ? $categoryid : '', "category_id" => !empty($categoryArr['id']) ? $categoryArr['id'] : '', "tournament_name" => !empty($tournamentName) ? $tournamentName : '');
                        $Status = $this->Sport_model->insert_single('tbl_tournament', $tournamentsToInsert);
                        //$seasonToInsert[] = array("season_id" => $seasonid, "tournament_id" => $tournamentId, "sport_id" => $sportid, "season_name" => $season_name, "start_date" => "$season_start_date", "end_date" => $season_end_date, "year" => "$season_year");
                    }
                }
                //$this->Cron_model->truncateTableByName('tbl_tournament');
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
     * @ Name tournamentSchedule
     * @desc fetch Tournament schedule from API on the bases of tournament id and insert all events and related data into database
     */
    public function tournamentSchedule() {

        $this->Cron_model->truncateTableByName('tbl_tournament_schedule');
        //$this->Cron_model->truncateTableByName('tbl_competitors');
        //$this->Cron_model->truncateTableByName('tbl_venue');
        //$this->Cron_model->truncateTableByName('tbl_markets');
        //$this->Cron_model->truncateTableByName('tbl_outcome');
        //$this->Cron_model->truncateTableByName('tbl_sports_events');
        $tournamentArr = $this->Sport_model->fetch_data('tbl_tournament', '', array('where' => array('status' => '1')), false);
        //print_r($tournamentArr); die;
        if (!empty($tournamentArr)) {

            foreach ($tournamentArr as $tournament) {
                $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/" . $tournament['tournament_id'] . "/schedule.json?api_key=fesnnrpjz5spbka659z7jzvv";
                try {

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $your_url);
                    curl_setopt($ch, CURLOPT_FAILONERROR, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);

                    $value = json_decode($response, TRUE);

                    //print_r($value); die;

                    $considerSports = array(
                        "sr:sport:2",
                        "sr:sport:12",
                        "sr:sport:5",
                        "sr:sport:9",
                        "sr:sport:21",
                        "sr:sport:10",
                        "sr:sport:1"
                    );
                    $tournamentScheduleToInsert = array();
                    //$tournamentSeasonToInsert=array();;
                    //$sportEventsToInsert=array();
                    //$competitorsToInsert=array();
                    //$venueToInsert=array();
                    //$outcomeToInsert=array();
                    //$marketsToInsert=array();
                    //print_r(file_get_contents($your_url));exit;
                    //$xml = new SimpleXMLElement(file_get_contents($your_url));
                    //print_r($xml);exit;
                    //$value = ((array) $xml);
                    //print_r($value); die;
                    if (isset($value) && !empty($value)) {
                        $tournamentInfo = (array) $value['tournament'];
                        $tournamentSport = (array) $tournamentInfo['sport'];
                        $tournamentCategory = (array) $tournamentInfo['category'];
                        $tournament_id = $tournamentInfo['id'];
                        $tournamenr_sport_id = (isset($tournamentSport['id']) && !empty($tournamentSport['id'])) ? $tournamentSport['id'] : '';
                        $tournamenr_category_id = (isset($tournamentCategory['id']) && !empty($tournamentCategory['id'])) ? $tournamentCategory['id'] : '';
                        //$tournament_insert_array=array("tournament_id"=>$tournament_id,"sport_id"=>$tournamenr_sport_id);
                        if (in_array($tournamenr_sport_id, $considerSports)) {
                            /* $evntarr = (array) $value ['sport_events']->sport_event;

                              $sport_event_insert_array=array(
                              "event_id"=>$evntarr['@attributes']['id'],
                              "tournament_id"=>$tournament_id,
                              "scheduled"=>$evntarr['@attributes']['scheduled'],
                              "start_time_tbd"=>$evntarr['@attributes']['start_time_tbd'],
                              "event_status"=>$evntarr['@attributes']['status'],
                              "created_date"=>date('Y-m-d H:i:s'),
                              "status"=>'1',
                              "updated_date"=>time()
                              );

                              $sportEventToInsert[]=$sport_event_insert_array; */


                            $Status = FALSE;
                            if (isset($value['sport_events']) && !empty($value['sport_events'])) {

                                foreach ($value['sport_events'] as $key => $eventarray) {
                                    if (!empty($eventarray)) {
                                        //$eventarray = (array) $val;


                                        if (isset($eventarray['tournament_round']) && !empty($eventarray['tournament_round'])) {

                                            $tournament_roundinfo = (array) $eventarray['tournament_round'];
                                            $tournament_round_type = (isset($tournament_roundinfo['type']) && !empty($tournament_roundinfo['type'])) ? $tournament_roundinfo['type'] : '';
                                            $tournament_round_name = (isset($tournament_roundinfo['name']) && !empty($tournament_roundinfo['name'])) ? $tournament_roundinfo['name'] : '';
                                            $tournament_event_id = (isset($eventarray['id']) && !empty($eventarray['id'])) ? $eventarray['id'] : '';

                                            $tournament_insert_array["tournament_round_type"] = $tournament_round_type;
                                            $tournament_insert_array["tournament_round_name"] = $tournament_round_name;
                                            $tournament_insert_array["event_id"] = $tournament_event_id;
                                            $tournament_insert_array["tournament_id"] = $tournament_id;
                                            $tournament_insert_array["sport_id"] = $tournamenr_sport_id;
                                            $tournament_insert_array["sportradar_category_id"] = $tournamenr_category_id;
                                        }
                                        /*  if(isset($eventarray['season'])&&!empty($eventarray['season']))
                                          {
                                          $tournament_seasoninfo=(array)$eventarray['season'];
                                          $tournament_season_id=(isset($tournament_seasoninfo['@attributes']['id'])&& !empty($tournament_seasoninfo['@attributes']['id']))?$tournament_seasoninfo['@attributes']['id']:'';
                                          $tournament_season_name=(isset($tournament_seasoninfo['@attributes']['name'])&& !empty($tournament_seasoninfo['@attributes']['name']))?$tournament_seasoninfo['@attributes']['name']:'';
                                          $tournament_season_start_date=(isset($tournament_seasoninfo['@attributes']['start_date'])&& !empty($tournament_seasoninfo['@attributes']['start_date']))?$tournament_seasoninfo['@attributes']['start_date']:'';
                                          $tournament_season_end_date=(isset($tournament_seasoninfo['@attributes']['end_date'])&& !empty($tournament_seasoninfo['@attributes']['end_date']))?$tournament_seasoninfo['@attributes']['end_date']:'';
                                          $tournament_season_year=(isset($tournament_seasoninfo['@attributes']['year'])&& !empty($tournament_seasoninfo['@attributes']['year']))?$tournament_seasoninfo['@attributes']['year']:'';
                                          $season_insert_array=array(
                                          "season_id"=>$tournament_season_id,
                                          "tournament_id"=>$tournament_id,
                                          "sport_id"=>$tournamenr_sport_id,
                                          "season_name"=>$tournament_season_name,
                                          "start_date"=>$tournament_season_start_date,
                                          "end_date"=>$tournament_season_end_date,
                                          "year"=>$tournament_season_year
                                          );
                                          } */
                                        /*       $eventId = (isset($eventarray['@attributes']['id']) && !empty($eventarray['@attributes']['id'])) ? $eventarray['@attributes']['id'] : '';
                                          $scheduled = (isset($eventarray['@attributes']['scheduled']) && !empty($eventarray['@attributes']['scheduled'])) ? $eventarray['@attributes']['scheduled'] : '';
                                          $eventStartTimeTbd = (isset($eventarray['@attributes']['start_time_tbd']) && !empty($eventarray['@attributes']['start_time_tbd'])) ? 1 : 0;
                                          $eventStatus = (isset($eventarray['@attributes']['status']) && !empty($eventarray['@attributes']['status'])) ? $eventarray['@attributes']['status'] : '';
                                          $sportEventsToInsert[]=array();
                                          if (isset($eventarray['competitors']) && !empty($eventarray['competitors'])) {
                                          // COMPETITORS LOGIC BEGINS HERE
                                          foreach ($eventarray['competitors']as $keyc => $valc) {
                                          $this->Cron_model->deleteData('tbl_outcome', array("event_id" => $eventId));
                                          $compArr = (array) $valc;
                                          $compidId = (isset($compArr['@attributes']['id']) && !empty($compArr['@attributes']['id'])) ? $compArr['@attributes']['id'] : '';
                                          $compName = (isset($compArr['@attributes']['name']) && !empty($compArr['@attributes']['name'])) ? $compArr['@attributes']['name'] : '';
                                          $compCountry = (isset($compArr['@attributes']['country']) && !empty($compArr['@attributes']['country'])) ? $compArr['@attributes']['country'] : '';
                                          $compCountryCode = (isset($compArr['@attributes']['country_code']) && !empty($compArr['@attributes']['country_code'])) ? $compArr['@attributes']['country_code'] : '';
                                          $compAbbr = (isset($compArr['@attributes']['abbreviation']) && !empty($compArr['@attributes']['abbreviation'])) ? $compArr['@attributes']['abbreviation'] : '';
                                          $compqlf = (isset($compArr['@attributes']['qualifier']) && !empty($compArr['@attributes']['qualifier'])) ? $compArr['@attributes']['qualifier'] : '';
                                          $isexist = FALSE;
                                          $insertArray = array(
                                          "competitor_id" => $compidId,
                                          "competitor_name" => $compName,
                                          "country" => $compCountry,
                                          "country_code" => $compCountryCode,
                                          "abbreviation" => $compAbbr,
                                          "qualifier" => $compqlf
                                          );
                                          $competitorsToInsert[]=$insertArray;
                                          }
                                          } *///END OF COMPETITORS LOGIC
                                        // VENUE LOGIC BEGINS HERE
                                        /*   if (isset($eventarray['venue']) && !empty($eventarray['venue'])) {
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
                                          "event_id"=>$eventId,
                                          "capacity" => $venCcapacity,
                                          "city_name" => $venCity,
                                          "country_name" => $vencountry,
                                          "map_coordinates" => $mapcordinates,
                                          "country_code" => $venCountryCode
                                          );
                                          $venueToInsert[]=$insertArray;

                                          } *///VENUE logic ends here
                                        // MARKETS LOGIC BEGINS HERE
                                        /*        if (isset($eventarray['markets']) && !empty($eventarray['markets'])) {
                                          $marketsArray = (array) $eventarray['markets'];
                                          foreach ($marketsArray as $onemarket) {
                                          $marketarray = (array) $onemarket;
                                          if (isset($onemarket) && !empty($onemarket)) {
                                          foreach ($marketarray as $singlemarket) {
                                          $merket = (array) $singlemarket;
                                          $groupName = (isset($merket['@attributes']['group_name']) && !empty($merket['@attributes']['group_name'])) ? $merket['@attributes']['group_name'] : '';
                                          $marketname = (isset($merket['@attributes']['name']) && !empty($merket['@attributes']['name'])) ? $merket['@attributes']['name'] : '';
                                          $isexist = FALSE;
                                          $insertArray = array(
                                          "market_name" => $marketname,
                                          "group_name" => $groupName,
                                          );

                                          $marketsToInsert[]=$insertArray;
                                          if (isset($merket['book']) && !empty($merket['book'])) {
                                          $booksArr = (array) $merket['book'];
                                          foreach ($booksArr as $onebook) {
                                          $bokdetails = (array) $onebook;
                                          if (isset($bokdetails['outcome']) && !empty($bokdetails['outcome'])) {
                                          if (empty($isexistevent)) {
                                          foreach ($bokdetails['outcome'] as $oneoutcome) {
                                          $eid = $eventId;
                                          $bookid = (isset($bokdetails['@attributes']) && !empty($bokdetails['@attributes'])) ? $bokdetails['@attributes']['id'] : 'NA';
                                          $outcomearr = (array) $oneoutcome;
                                          $outcometype = (isset($outcomearr['@attributes']['type']) && !empty($outcomearr['@attributes']['type'])) ? $outcomearr['@attributes']['type'] : 'NA';
                                          $odds = (isset($outcomearr['@attributes']['odds']) && !empty($outcomearr['@attributes']['odds'])) ? $outcomearr['@attributes']['odds'] : '';
                                          $deep_link = (isset($outcomearr['@attributes']['deep_link']) && !empty($outcomearr['@attributes']['deep_link'])) ? $outcomearr['@attributes']['deep_link'] : 'NA';
                                          $handicap = (isset($outcomearr['@attributes']['handicap']) && !empty($outcomearr['@attributes']['handicap'])) ? $outcomearr['@attributes']['handicap'] : 'NA';
                                          $insertArray = array(
                                          "book_id" => $bookid,
                                          "sport_id"=>$tournamenr_sport_id,
                                          "event_id" => $eid,
                                          "market_name" => $marketname,
                                          "outcome_type" => $outcometype,
                                          "outcome_odds" => $odds,
                                          "deep_link" => $deep_link,
                                          "handicap" => $handicap
                                          );
                                          $outcomeToInsert[]=$insertArray;
                                          }
                                          } else {


                                          foreach ($bokdetails['outcome'] as $oneoutcome) {
                                          $eid = $eventId;

                                          $bookid = (isset($bokdetails['@attributes']) && !empty($bokdetails['@attributes'])) ? $bokdetails['@attributes']['id'] : 'NA';
                                          $outcomearr = (array) $oneoutcome;
                                          $outcometype = (isset($outcomearr['@attributes']['type']) && !empty($outcomearr['@attributes']['type'])) ? $outcomearr['@attributes']['type'] : 'NA';
                                          $odds = (isset($outcomearr['@attributes']['odds']) && !empty($outcomearr['@attributes']['odds'])) ? $outcomearr['@attributes']['odds'] : '';
                                          $deep_link = (isset($outcomearr['@attributes']['deep_link']) && !empty($outcomearr['@attributes']['deep_link'])) ? $outcomearr['@attributes']['deep_link'] : 'NA';
                                          $handicap = (isset($outcomearr['@attributes']['handicap']) && !empty($outcomearr['@attributes']['handicap'])) ? $outcomearr['@attributes']['handicap'] : 'NA';
                                          $insertArray = array(
                                          "book_id" => $bookid,
                                          "event_id" => $eid,
                                          "tournament_id" => $tournament['tournament_id'],
                                          "market_name" => $marketname,
                                          "outcome_type" => $outcometype,
                                          "outcome_odds" => $odds,
                                          "deep_link" => $deep_link,
                                          "handicap" => $handicap
                                          );
                                          $outcomeToInsert[]=$insertArray;
                                          //$Status = $this->Api_Model->insertData('tbl_outcome', $insertArray);
                                          }
                                          }
                                          }
                                          }
                                          }
                                          }
                                          }
                                          }
                                          } */
                                        //MARKETS logic ends here
                                    }
                                }
                                //insert all data from here
                                $tournamentScheduleToInsert[] = $tournament_insert_array;
                                //$tournamentSeasonToInsert[]=$season_insert_array;

                                $Status = $this->Cron_model->insertData('tbl_tournament_schedule', $tournamentScheduleToInsert);
                                //$this->Cron_model->truncateTableByName('tbl_season');
                                //$Status =$this->Cron_model->insertData('tbl_season',$tournamentSeasonToInsert);
                                // $Status =$this->Cron_model->insertData('tbl_competitors',$competitorsToInsert);
                                //$Status =$this->Cron_model->insertData('tbl_venue',$venueToInsert);
                                //$Status =$this->Cron_model->insertData('tbl_markets',$marketsToInsert);
                                //$Status =$this->Cron_model->insertData('tbl_outcome',$outcomeToInsert);
                                //$Status =$this->Cron_model->insertData('tbl_sports_events', $sportEventToInsert);

                                if (!empty($Status)) {
                                    echo 'All Sport events added susseccfully in database';
                                } else {
                                    echo 'One or more sport event not added successfully in database';
                                }
                            }
                        } else {
                            echo 'this tournament not contain specified sports';
                        }
                    }
                } catch (Exception $ex) {
                    echo "following error occur while reading data from API <br>" . $ex->getMessage();
                }
            }
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

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.json?api_key=fesnnrpjz5spbka659z7jzvv";
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
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);


                        // $xml = simplexml_load_string($data);
                        // $value = ((array) $xml);

                        if (curl_errno($ch) != '22') {

                            //$xml = simplexml_load_string($html);
                            //$value = ((array) $xml);

                            $value = json_decode($response, TRUE);
                            //print_r($value);
                            //die;


                            if (isset($value) && !empty($value)) {

                                $tournamentInfo = $value['tournament'];
                                $categoryInfo = $tournamentInfo['category'];

                                $tournamentSport = $tournamentInfo['sport'];
                                $tournament_id = $tournamentInfo['id'];
                                $category_id = $categoryInfo['id'];

                                $tournamenr_sport_id = (isset($tournamentSport['id']) && !empty($tournamentSport['id'])) ? $tournamentSport['id'] : '';
                                $tournament_insert_array = array("tournament_id" => $tournament_id, "sport_id" => $tournamenr_sport_id);
                                if (in_array($tournamenr_sport_id, $considerSports)) {
                                    $evntarr = $value ['sport_events'];

                                    if (!empty($evntarr)) {

                                        foreach ($evntarr as $event) {

                                            $sport_event_insert_array = array
                                                (
                                                "event_id" => !empty($event['id']) ? $event['id'] : '',
                                                "sportsradar_category" => $category_id,
                                                "tournament_id" => $tournament_id,
                                                "sport_id" => $tournamenr_sport_id,
                                                "scheduled" => !empty($event['scheduled']) ? $event['scheduled'] : '',
                                                "start_time_tbd" => !empty($event['start_time_tbd']) ? $event['start_time_tbd'] : '',
                                                "event_status" => !empty($event['status']) ? $event['status'] : '',
                                                "created_date" => date('Y-m-d H:i:s'),
                                                "status" => '1',
                                                "updated_date" => time()
                                            );


                                            $this->Sport_model->insert_single('tbl_sports_events', $sport_event_insert_array);
                                        }
                                    }
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

                        //$check = $this->checkExistTournamentId($tvalues->tournament_id);



                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.json?api_key=fesnnrpjz5spbka659z7jzvv";
                        //$xml = simplexml_load_string(file_get_contents($your_url));


                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                        $response = curl_exec($ch);

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

                            //$xml = simplexml_load_string($html);
                            //$value = ((array) $xml);

                            $value = json_decode($response, TRUE);

                            if (isset($value) && !empty($value)) {
                                $tournamentInfo = (array) $value['tournament'];
                                $tournamentSport = (array) $tournamentInfo['sport'];
                                $tournament_id = $tournamentInfo['id'];
                                $tournamenr_sport_id = (isset($tournamentSport['id']) && !empty($tournamentSport['id'])) ? $tournamentSport['id'] : '';
                                $tournament_insert_array = array("tournament_id" => $tournament_id, "sport_id" => $tournamenr_sport_id);
                                if (in_array($tournamenr_sport_id, $considerSports)) {
                                    $evntarr = $value ['sport_events'];

                                    if (!empty($evntarr)) {

                                        foreach ($evntarr as $event) {

                                            if (isset($event['season']) && !empty($event['season'])) {
                                                $tournament_seasoninfo = $event['season'];
                                                $tournament_season_id = (isset($tournament_seasoninfo['id']) && !empty($tournament_seasoninfo['id'])) ? $tournament_seasoninfo['id'] : '';
                                                $tournament_season_name = (isset($tournament_seasoninfo['name']) && !empty($tournament_seasoninfo['name'])) ? $tournament_seasoninfo['name'] : '';
                                                $tournament_season_start_date = (isset($tournament_seasoninfo['start_date']) && !empty($tournament_seasoninfo['start_date'])) ? $tournament_seasoninfo['start_date'] : '';
                                                $tournament_season_end_date = (isset($tournament_seasoninfo['end_date']) && !empty($tournament_seasoninfo['end_date'])) ? $tournament_seasoninfo['end_date'] : '';
                                                $tournament_season_year = (isset($tournament_seasoninfo['year']) && !empty($tournament_seasoninfo['year'])) ? $tournament_seasoninfo['year'] : '';
                                                $season_insert_array = array(
                                                    "season_id" => $tournament_season_id,
                                                    "tournament_id" => $tournament_id,
                                                    "sportsradar_event_id" => $event['id'],
                                                    "sport_id" => $tournamenr_sport_id,
                                                    "season_name" => $tournament_season_name,
                                                    "start_date" => $tournament_season_start_date,
                                                    "end_date" => $tournament_season_end_date,
                                                    "year" => $tournament_season_year
                                                );
                                            }

                                            $this->Sport_model->insert_single('tbl_season', $season_insert_array);
                                        }
                                    }
                                }
                            }
                        }
                    }
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

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.json?api_key=fesnnrpjz5spbka659z7jzvv";
                        //$xml = simplexml_load_string(file_get_contents($your_url));

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                        $response = curl_exec($ch);

                        //echo "<br />cURL error number:" . curl_errno($ch);
                        //echo "<br />cURL error:" . curl_error($ch);

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
                            

                            $value=json_decode($response,TRUE);
                            //print_r($xml);exit;
                            //$xml = simplexml_load_string($html);
                            //$value = ((array) $xml);
                            //print_r($value); //die;
                            if (isset($value) && !empty($value)) {
                                $tournamentInfo = (array) $value['tournament'];
                                $tournamentSport = (array) $tournamentInfo['sport'];
                                $tournamenr_sport_id = (isset($tournamentSport['id']) && !empty($tournamentSport['id'])) ? $tournamentSport['id'] : '';

                                if (in_array($tournamenr_sport_id, $considerSports)) {


                                    if (isset($value['sport_events']) && !empty($value['sport_events'])) {

                                        foreach ($value['sport_events'] as $key => $val) {
                                          //  if (isset($value['sport_events']) && !empty($value['sport_events'])) {
                                                $eventarray = $val;

                                                $eventId = (isset($eventarray['id']) && !empty($eventarray['id'])) ? $eventarray['id'] : '';

                                                //$sportEventsToInsert[] = array();

                                                if (isset($eventarray['venue']) && !empty($eventarray['venue'])) {
                                                    $venueArr = (array) $eventarray['venue']; //(array)$valv;
                                                    $venidId = (isset($venueArr['id']) && !empty($venueArr['id'])) ? $venueArr['id'] : '';
                                                    $venName = (isset($venueArr['name']) && !empty($venueArr['name'])) ? $venueArr['name'] : '';
                                                    $venCcapacity = (isset($venueArr['capacity']) && !empty($venueArr['capacity'])) ? $venueArr['capacity'] : '';
                                                    $venCity = (isset($venueArr['city_name']) && !empty($venueArr['city_name'])) ? $venueArr['city_name'] : '';
                                                    $vencountry = (isset($venueArr['country_name']) && !empty($venueArr['country_name'])) ? $venueArr['country_name'] : '';
                                                    $mapcordinates = (isset($venueArr['map_coordinates']) && !empty($venueArr['map_coordinates'])) ? $venueArr['map_coordinates'] : '';
                                                    $venCountryCode = (isset($venueArr['country_code']) && !empty($venueArr['country_code'])) ? $venueArr['country_code'] : '';
                                                    $isRemoved = (isset($venueArr['removed']) && !empty($venueArr['removed'])) ? $venueArr['removed'] : FALSE;
                                                    $isexist = FALSE;
                                                if($isRemoved==FALSE)
                                                {
                                                    
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

                                                    $this->Sport_model->insert_single('tbl_venue', $insertArray);
                                                    
                                                }
                                                    
                                                }
                                            //}
                                        }
                                    }
                                }
                            }
                        }
                    }

                    
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


            //$this->Cron_model->truncateTableByName('tbl_markets');
            //$this->Cron_model->truncateTableByName('tbl_market_outcome');

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

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.json?api_key=fesnnrpjz5spbka659z7jzvv";

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);

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

                            //$xml = simplexml_load_string($html);
                            //$value = ((array) $xml);
                            
                            $value=  json_decode($response,TRUE);

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

                                $tournament_id = (isset($tournamentInfo['id']) && !empty($tournamentInfo['id'])) ? $tournamentInfo['id'] : '';
                                $tournamenr_sport_id = (isset($tournamentSport['id']) && !empty($tournamentSport['id'])) ? $tournamentSport['id'] : '';
                                $tournamenr_category_id = (isset($tournamentCategory['id']) && !empty($tournamentCategory['id'])) ? $tournamentCategory['id'] : '';

                                if (in_array($tournamenr_sport_id, $considerSports)) {


                                    if (isset($value['sport_events']) && !empty($value['sport_events'])) {

                                        foreach ($value['sport_events'] as $key => $val) {
                                           // if (isset($value['sport_events']) && !empty($value['sport_events'])) {
                                                $eventarray = $val;
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
                                            //}
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

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/$tvalues->tournament_id/schedule.json?api_key=fesnnrpjz5spbka659z7jzvv";
                        // $xml = simplexml_load_string(file_get_contents($your_url));

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);
                        //print_r($html);
                        /*   if (!$html) {
                          echo "<br />cURL error number:" . curl_errno($ch);
                          echo "<br />cURL error:" . curl_error($ch);
                          //exit;
                          continue;
                          } else */
                        if (curl_errno($ch) != '22') {

                           // echo "<br />cURL error number:" . curl_errno($ch);
                            //echo "<br />cURL error:" . curl_error($ch);

                            $considerSports = array(
                                    "sr:sport:2",
                                    "sr:sport:10",
                                    "sr:sport:21",
                                    "sr:sport:1",
                                    "sr:sport:9",
                                    "sr:sport:5",
                                    "sr:sport:12"
                            );

                            //$xml = simplexml_load_string($html);

                            //$value = ((array) $xml);
                            
                               $value=json_decode($response,TRUE);
                               //print_r($value); die;

                            if (isset($value) && !empty($value)) {
                                $tournamentInfo = $value['tournament'];
                                $tournamentSport = $tournamentInfo['sport'];
                                $tournamentCategory = $tournamentInfo['category'];

                                $tournament_id = $tournamentInfo['id'];
                                $category_id = $tournamentCategory['id'];
                                $tournamenr_sport_id = (isset($tournamentSport['id']) && !empty($tournamentSport['id'])) ? $tournamentSport['id'] : '';
                                $tournament_insert_array = array("tournament_id" => $tournament_id, "sport_id" => $tournamenr_sport_id);
                                if (in_array($tournamenr_sport_id, $considerSports)) {
                                    $totalEventArr = $value ['sport_events'];

                                    //print_r($totalEventArr); die;
                                    //$totalEventArr = $evntarr['sport_event'];

                                    if (!empty($totalEventArr)) {

                                        foreach ($totalEventArr as $eventval) {

                                            //$eventval = (array) $tevent;
                                            $event_id = $eventval['id'];

                                            if (!empty($eventval['competitors'])) {

                                                $comptitorArr = $eventval['competitors'];

                                                //$comptitorArr = (array) $comptitorsArr['competitor'];

                                                if (!empty($comptitorArr)) {

                                                    foreach ($comptitorArr as $comval) {

                                                        //$comval = (array) $com;
                                                        //print_r($comval); die;
                                                        $com_id = (isset($comval['id']) && !empty($comval['id'])) ? $comval['id'] : '';
                                                        $com_name = (isset($comval['name']) && !empty($comval['name'])) ? $comval['name'] : '';
                                                        $com_country = (isset($comval['country']) && !empty($comval['country'])) ? $comval['country'] : '';
                                                        $com_code = (isset($comval['country_code']) && !empty($comval['country_code'])) ? $comval['country_code'] : '';
                                                        $com_abbr = (isset($comval['abbreviation']) && !empty($comval['abbreviation'])) ? $comval['abbreviation'] : '';
                                                        $com_qua = (isset($comval['qualifier']) && !empty($comval['qualifier'])) ? $comval['qualifier'] : '';
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

                                                        print_r($insertCompetitorArr); //die;
                                                        $succ = $this->Sport_model->insert_single('tbl_competitors', $insertCompetitorArr);
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


    public function addBokiesAndComptitor() {

//echo 1; die;

        try {


            $this->db->select('category_id');
            $this->db->from('tbl_categories');
            $this->db->order_by('id', 'asc');
            $data = $this->db->get();

            //echo $this->db->last_query(); die;
            if ($data->num_rows() > 0) {

                $resultArr = $data->result_array();
//print_r($resultArr); die;
                foreach ($resultArr as $key1 => $catvalues) {

                    if (!empty($catvalues['category_id'])) {

                        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/categories/" . $catvalues['category_id'] . "/outrights.json?api_key=fesnnrpjz5spbka659z7jzvv";

                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $your_url);
                        curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);

                        $value=json_decode($response,TRUE);


                        //$xml = simplexml_load_string(file_get_contents($your_url));


                        //$value = ((array) $xml);
                        //print_r($your_url) ; exit;

                        $sportId = $value['sport']['id'];
                        $sportId = ((array) $sportId);
                        $sport_Id = $sportId[0];
                        $catId = $value['category']['id']; //die;
                        $catId = ((array) $catId);
                        $cat_Id = $catId[0];

                        $outArr = $value['outrights'];
                        //echo "<pre>";
                        //print_r($outArr); die;
                        if (!empty($outArr)) {

                            foreach ($outArr as $key2 => $valueout) {
                                //$valueout = (array) $valueout;
                                $tournament_id = $valueout['tournament_id'];
                                $comArr = $valueout['competitors'];
                                //$comArr = (array) $comArr;
                                //die;
                                if (!empty($comArr)) {

                                    foreach ($comArr as $key3 => $valuescom) {
                                        //$valuescom = (array) $valuescom;
                                        $comptitorsId = $valuescom['id'];
                                        $comptitorsName = $valuescom['name'];
                                        $comptitorsCountry = $valuescom['country'];
                                        $comptitorsCountry_code = $valuescom['country_code'];
                                        $bookArr = $valuescom['books'];
                                        //$bookArr = (array) $bookArr;

                                        foreach ($bookArr as $key4 => $valuesbook) {

                                            //$valuesbook = (array) $valuesbook;
                                            $bookiesId = $valuesbook['id'];
                                            $bookiesodds = $valuesbook['odds'];

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

}
