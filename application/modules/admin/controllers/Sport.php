<?php

/**
 * @Class: Sports
 * @access public
 * @category Create, list, edit, delete coupons
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sport extends MX_Controller {

    
    function __construct() {
        parent::__construct();
        $this->load->model("Api_Model");
        //$this->load->model(array('Common_model'));
        
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
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/sports.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        try
        {
            $xml = new SimpleXMLElement(file_get_contents($your_url));
            $value = $this->xml2array($xml);
            $Status = FALSE;
            $considerSports=array(
                "sr:sport:2",
                "sr:sport:12",
                "sr:sport:5",
                "sr:sport:9",
                "sr:sport:21",
                "sr:sport:10",
                "sr:sport:16"
                );
            $sportsToInsert=array();
            if(isset($value['sport']) && !empty($value['sport']))
            {
            foreach ($value['sport'] as $key => $val) {
                $sportarray = (array) $val;
                $sportId = (isset($sportarray['@attributes']['id']) && !empty($sportarray['@attributes']['id'])) ? $sportarray['@attributes']['id'] : '';
                $sportName = (isset($sportarray['@attributes']['name']) && !empty($sportarray['@attributes']['name'])) ? $sportarray['@attributes']['name'] : '';
                if(in_array($sportId, $considerSports))
                {
                    $sportsToInsert[]=array("sport_id"=>$sportId,"sport_name"=>$sportName);
                }
            }
            $this->Api_Model->truncateTable('tbl_sports');
            $Status = $this->Api_Model->insertData('tbl_sports', $sportsToInsert);
            }
            if (!empty($Status)) {
                echo 'All Sports added susseccfully in database';
            } else {
                echo 'One or more Sports not added successfully in database';
            }
        }
        catch (Exception $ex) {
            echo 'Following error occur<br>'.$ex->getMessage();
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
        $Status = FALSE;
        foreach ($value['book'] as $key => $val) {
            $bookarray = (array) $val;
            $bookId = (isset($bookarray['@attributes']['id']) && !empty($bookarray['@attributes']['id'])) ? $bookarray['@attributes']['id'] : '';
            $bookName = (isset($bookarray['@attributes']['name']) && !empty($bookarray['@attributes']['name'])) ? $bookarray['@attributes']['name'] : '';
            $isexist = FALSE;
            $isexist = $this->Api_Model->checkexistingData('tbl_books', array("book_id" => $bookId));
            if (empty($isexist)) {
                $insertArray = array("book_id" => $bookId, "book_name" => $bookName);
                $Status = $this->Api_Model->insertData('tbl_books', $insertArray);
            } else {
                $updatetArray = array("book_name" => $bookName);
                $Status = $this->Api_Model->updateData('tbl_books', array("book_id" => $bookId), $updatetArray);
            }
        }
        if (!empty($Status)) {
            echo 'All Books added susseccfully in database';
        } else {
            echo 'One or more Book not added successfully in database';
        }
    }

    /**
     * @ Name addCategories
     * @desc fetch Categories from API and insert into database
     */
    public function addCategories() {
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/categories.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        try
        {
        $xml = new SimpleXMLElement(file_get_contents($your_url));
        $value = $this->xml2array($xml);
        $Status = FALSE;
        $considerSports=array(
                "sr:sport:2",
                "sr:sport:12",
                "sr:sport:5",
                "sr:sport:9",
                "sr:sport:21",
                "sr:sport:10",
                "sr:sport:16"
                );
        $categoriesToInsert=array();
        if(isset($value['category'])&& !empty($value['category']))
        {
        foreach ($value['category'] as $key => $val) 
        {
            $catarray = (array) $val;
            $catId = (isset($catarray['@attributes']['id']) && !empty($catarray['@attributes']['id'])) ? $catarray['@attributes']['id'] : '';
            $catName = (isset($catarray['@attributes']['name']) && !empty($catarray['@attributes']['name'])) ? $catarray['@attributes']['name'] : '';
            $sportid = (isset($catarray['@attributes']['sport_id']) && !empty($catarray['@attributes']['sport_id'])) ? $catarray['@attributes']['sport_id'] : '';
            $countrycode = (isset($catarray['@attributes']['country_code']) && !empty($catarray['@attributes']['country_code'])) ? $catarray['@attributes']['country_code'] : '';
            $outrights = (isset($catarray['@attributes']['outrights']) && !empty($catarray['@attributes']['outrights'])) ? 1 : 0;
            $isexist = FALSE;
            if(in_array($sportid, $considerSports))
                {
                    $categoriesToInsert[]=array("category_id"=>$catId,"sport_id"=>$sportid,"category_name"=>$catName,"country_code"=>$countrycode,"outrights"=>$outrights);
                }
        }
        $this->Api_Model->truncateTable('tbl_categories');
        $Status = $this->Api_Model->insertData('tbl_categories', $categoriesToInsert);
        }
        if (!empty($Status)) {
            echo 'All Categories added susseccfully in database';
        } else {
            echo 'One or more Category not added successfully in database';
        } 
        } 
        catch (Exception $ex) {

        }
        
    }

    /**
     * @ Name addTournaments
     * @desc fetch Tournaments from API and insert into database
     */
    public function addTournaments() {
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        try
        {
        $xml = new SimpleXMLElement(file_get_contents($your_url));
        $value = $this->xml2array($xml);
        $Status = FALSE;
        $considerSports=array(
                 "sr:sport:2",
                "sr:sport:12",
                "sr:sport:5",
                "sr:sport:9",
                "sr:sport:21",
                "sr:sport:10",
                "sr:sport:16"
                );
        $tournamentsToInsert=array();
        $seasonToInsert=array();
        if(isset($value['tournament'])&& !empty($value['tournament']))
        {
            $sportid='';
            
        foreach ($value['tournament'] as $key => $val) {
            $tournamentarray = (array) $val;
            $tournamentId = (isset($tournamentarray['@attributes']['id']) && !empty($tournamentarray['@attributes']['id'])) ? $tournamentarray['@attributes']['id'] : '';
            $tournamentName = (isset($tournamentarray['@attributes']['name']) && !empty($tournamentarray['@attributes']['name'])) ? $tournamentarray['@attributes']['name'] : '';
            if(isset($tournamentarray['sport']))
            {
                $tournamentSport=(array)$tournamentarray['sport'];
                $sportid=  (isset($tournamentSport['@attributes']['id'])&&!empty($tournamentSport['@attributes']['id']))?$tournamentSport['@attributes']['id']:'';
            }
            // code to insert into Season Table begins
             if(isset($tournamentarray['current_season']))
            {
                $tournamentseason=(array)$tournamentarray['current_season'];
                $seasonid=(isset($tournamentseason['@attributes']['id'])&&!empty($tournamentseason['@attributes']['id']))?$tournamentseason['@attributes']['id']:'';
                $season_name=(isset($tournamentseason['@attributes']['name'])&&!empty($tournamentseason['@attributes']['name']))?$tournamentseason['@attributes']['name']:'';
                $season_start_date=(isset($tournamentseason['@attributes']['start_date'])&&!empty($tournamentseason['@attributes']['start_date']))?$tournamentseason['@attributes']['start_date']:'';
                $season_end_date=(isset($tournamentseason['@attributes']['end_date'])&&!empty($tournamentseason['@attributes']['end_date']))?$tournamentseason['@attributes']['end_date']:'';
                $season_year=(isset($tournamentseason['@attributes']['year'])&&!empty($tournamentseason['@attributes']['year']))?$tournamentseason['@attributes']['year']:'';
            }
            
             // code to insert into Season Table Ends
            if(in_array($sportid, $considerSports))
                {
                    $tournamentsToInsert[]=array("tournament_id"=>$tournamentId,"sport_id"=>$sportid,"tournament_name"=>$tournamentName);
                    $seasonToInsert[]=array("season_id"=>$seasonid,"tournament_id"=>$tournamentId,"sport_id"=>$sportid,"season_name"=>$season_name,"start_date"=>"$season_start_date","end_date"=>$season_end_date,"year"=>"$season_year");
                }
        }
        $this->Api_Model->truncateTable('tbl_tournament');
        $Status =$this->Api_Model->insertData('tbl_tournament', $tournamentsToInsert);
        $this->Api_Model->truncateTable('tbl_season');
        $this->Api_Model->insertData('tbl_season', $seasonToInsert);
        }
        if (!empty($Status)) {
            echo 'All Tournament added susseccfully in database';
        } else {
            echo 'One or more Tournament not added successfully in database';
        }  
        } 
        catch (Exception $ex) {

        }
        
    }

    /**
     * @ Name tournamentSchedule
     * @desc fetch Tournament schedule from API on the bases of tournament id and insert all events and related data into database
     */
    public function tournamentSchedule() {
        
        
        $your_url = "https://api.sportradar.us/oddscomparison-rowt1/en/eu/tournaments/sr:tournament:132/schedule.xml?api_key=fesnnrpjz5spbka659z7jzvv";
        try
        {
            $considerSports=array(
                "sr:sport:2",
                "sr:sport:12",
                "sr:sport:5",
                "sr:sport:9",
                "sr:sport:21",
                "sr:sport:10",
                "sr:sport:16"
                );
        $tournamentScheduleToInsert=array();
        $tournamentSeasonToInsert=array();;
        $sportEventsToInsert=array();
        $competitorsToInsert=array();
        $venueToInsert=array();
        $outcomeToInsert=array();
        $marketsToInsert=array();
        //print_r(file_get_contents($your_url));exit;
        $xml = new SimpleXMLElement(file_get_contents($your_url));
        //print_r($xml);exit;
        $value = ((array) $xml);
        //print_r($value); die;
        if(isset($value)&&!empty($value))
        {
            $tournamentInfo=(array)$value['tournament'];
            $tournamentSport=(array)$tournamentInfo['sport'];
            $tournament_id=$tournamentInfo['@attributes']['id'];
            $tournamenr_sport_id=(isset($tournamentSport['@attributes']['id'])&& !empty($tournamentSport['@attributes']['id']))?$tournamentSport['@attributes']['id']:'';
            $tournament_insert_array=array("tournament_id"=>$tournament_id,"sport_id"=>$tournamenr_sport_id);
            if(in_array($tournamenr_sport_id, $considerSports))
            {
                $evntarr = (array) $value ['sport_events']->sport_event;
                
                /*$sport_event_insert_array=array(
                    "event_id"=>$evntarr['@attributes']['id'],
                    "tournament_id"=>$tournament_id,
                    "scheduled"=>$evntarr['@attributes']['scheduled'],
                    "start_time_tbd"=>$evntarr['@attributes']['start_time_tbd'],
                    "event_status"=>$evntarr['@attributes']['status'],
                    "created_date"=>date('Y-m-d H:i:s'),
                    "status"=>'1',
                    "updated_date"=>time()
                );
                
                //print_r($sport_event_insert_array); die;
                //$this->Api_Model->truncateTable('tbl_sports_events');
                $this->Api_Model->insertData('tbl_sports_events', $sport_event_insert_array);*/
                
        $Status = FALSE;
        if(isset($value['sport_events'])&& !empty($value['sport_events']))
        {
            
        foreach ($value['sport_events'] as $key => $val) {
            if (isset($value['sport_events']) && !empty($value['sport_events'])) {
                $eventarray = (array) $val;
                if(isset($eventarray['tournament_round'])&&!empty($eventarray['tournament_round']))
                {
                $tournament_roundinfo=(array)$eventarray['tournament_round'];
                $tournament_round_type=(isset($tournament_roundinfo['@attributes']['type'])&& !empty($tournament_roundinfo['@attributes']['type']))?$tournament_roundinfo['@attributes']['type']:'';
                $tournament_round_name=(isset($tournament_roundinfo['@attributes']['name'])&& !empty($tournament_roundinfo['@attributes']['name']))?$tournament_roundinfo['@attributes']['name']:'';
                $tournament_insert_array["tournament_round_type"]=$tournament_round_type;
                $tournament_insert_array["tournament_round_name"]=$tournament_round_name;
                }
                if(isset($eventarray['season'])&&!empty($eventarray['season']))
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
                }
                $eventId = (isset($eventarray['@attributes']['id']) && !empty($eventarray['@attributes']['id'])) ? $eventarray['@attributes']['id'] : '';
                $scheduled = (isset($eventarray['@attributes']['scheduled']) && !empty($eventarray['@attributes']['scheduled'])) ? $eventarray['@attributes']['scheduled'] : '';
                $eventStartTimeTbd = (isset($eventarray['@attributes']['start_time_tbd']) && !empty($eventarray['@attributes']['start_time_tbd'])) ? 1 : 0;
                $eventStatus = (isset($eventarray['@attributes']['status']) && !empty($eventarray['@attributes']['status'])) ? $eventarray['@attributes']['status'] : '';
                $sportEventsToInsert[]=array();
               if (isset($eventarray['competitors']) && !empty($eventarray['competitors'])) {
                    // COMPETITORS LOGIC BEGINS HERE  
                    foreach ($eventarray['competitors']as $keyc => $valc) {
                        $this->Api_Model->deleteData('tbl_outcome', array("event_id" => $eventId));
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
                }//END OF COMPETITORS LOGIC
                // VENUE LOGIC BEGINS HERE
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
                            "event_id"=>$eventId,
                            "capacity" => $venCcapacity,
                            "city_name" => $venCity,
                            "country_name" => $vencountry,
                            "map_coordinates" => $mapcordinates,
                            "country_code" => $venCountryCode
                        );
                    $venueToInsert[]=$insertArray;
                    
                }//VENUE logic ends here
                
           // MARKETS LOGIC BEGINS HERE
                if (isset($eventarray['markets']) && !empty($eventarray['markets'])) {
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
                }//MARKETS logic ends here
            }
        }
        //insert all data from here
        $tournamentScheduleToInsert[]=$tournament_insert_array;
        $tournamentSeasonToInsert[]=$season_insert_array;
        $this->Api_Model->truncateTable('tbl_tournament_schedule');
        $Status =$this->Api_Model->insertData('tbl_tournament_schedule', $tournamentScheduleToInsert);
        $this->Api_Model->truncateTable('tbl_season');
        $Status =$this->Api_Model->insertData('tbl_season',$tournamentSeasonToInsert);
        $this->Api_Model->truncateTable('tbl_competitors');
        $Status =$this->Api_Model->insertData('tbl_competitors',$competitorsToInsert);
        $this->Api_Model->truncateTable('tbl_venue');
        $Status =$this->Api_Model->insertData('tbl_venue',$venueToInsert);
        $this->Api_Model->truncateTable('tbl_markets');
        $Status =$this->Api_Model->insertData('tbl_markets',$marketsToInsert);
        $this->Api_Model->truncateTable('tbl_outcome');
        $Status =$this->Api_Model->insertData('tbl_outcome',$outcomeToInsert);
         if (!empty($Status)) {
            echo 'All Sport events added susseccfully in database';
        } else {
            echo 'One or more sport event not added successfully in database';
        } 
        }
          }
            else
            {
                echo 'this tournament not contain specified sports';
            }
        
        }
        
        
        
        } 
        catch (Exception $ex) {
            echo "following error occur while reading data from API <br>".$ex->getMessage();
        }
        
    }

}
