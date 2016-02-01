<?php
$error = FALSE;
if(file_exists('config.php')){
    require_once('lib/whitepages.php');
    $message = '';
    if(!isset($API_KEY) || empty($API_KEY)){
        $message .= ' $API_KEY is empty.';
        $error = TRUE;
    }
    
    if($error == TRUE){
        $message .= ' Please edit <strong>config.php</strong>, keys can be obtained from <a href="http://www.pro.whitepages.com/lp/search-by-api-signup/">http://www.pro.whitepages.com/lp/search-by-api-signup/</a>';
    }
    
} else {
    $error = TRUE;
    $message = 'Missing <strong>config.php</strong>, Please copy and rename the file <strong>config.php.sample</strong> to <strong>config.php</strong> and provide valid API Keys. API keys can be obtained from'.
            ' <a href="http://www.pro.whitepages.com/lp/search-by-api-signup/">http://www.pro.whitepages.com/lp/search-by-api-signup/</a>';
    
}
if($error == TRUE){
    $data = array();
    $results['recordsTotal'] = 0;
    $results['recordsFiltered'] = 0;
    $results['dataError'] = array('message'=>$message);
    $results['draw'] = (int) $draw;
    $results['data'] = $data;
    echo json_encode($results);
    die();
}
/* Magic Begins here! */

extract($_POST);

$data = array();
$results['recordsTotal'] = 0;
$results['recordsFiltered'] = 0;
$searchType = '';
if(isset($type)){
    if($type == 'person'){
        // person search
        $searchType = 'person';
    }
}

if(isset($term) && isset($location)){
    
    if($searchType == 'person'){
        $response = json_decode(searchPerson($term,$location,$postal_code,'',''));
    } else {
        $response = json_decode(search($term,$location,'',''));
    }
    if($response->results){
        $results['recordsTotal'] = $results['recordsFiltered'] = count($response->results);
    } else {
        $results['recordsTotal'] = $results['recordsFiltered'] = 0;
    }
    if(isset($length) && isset($start)){
        if($searchType == 'person'){
            $response = json_decode(search($term,$location,$postal_code,$length,$start));
        } else {
            $response = json_decode(search($term,$location,$length,$start));
        }
    }
    
    $whitepageresults = array();
    
    if($response->results){
        $whitepageresults = $response->results;
    }
    
    if(isset($response->error)){
        $results['dataError'] = $response->error;
    }
    
    foreach($whitepageresults as $result){
        
        if(isset($result->name)){
            $name = $result->name;
        } else if(isset($result->best_name)) { // in case of person search
            $name = $result->best_name;
        } else {
            $name = '';
        }
        
        $age_range = '';
        if(isset($result->age_range)){
            if(isset($result->age_range->start)){
                $age_range = $result->age_range->start;
            }
            
            if(isset($result->age_range->end)){
                $age_range .= '-'.$result->age_range->end;
            }
            
        }
        
        $locations = $result->locations;
        
        $address = '';
        $city = '';
        $postal_code = '';
        $zip4 = '';
        $state_code = '';
        
        $display_address = '';
        
        if(isset($locations[0])){
            $location = $locations[0];
            $address = $location->address;
            
            $display_address = $location->standard_address_line1;
            
            if(!empty($location->standard_address_line2)){
                $display_address .= ', ' . $location->standard_address_line2;
            }
            
            $city = $location->city;
            $postal_code = $location->postal_code;
            $zip4 = $location->zip4;
            $state_code = $location->state_code;
        }
        
        $phones = $result->phones;
        
        $phone = '';
        $country_code = '';
        if(isset($phones[0])){
            $phone = $phones[0]->phone_number;
            $country_code = $phones[0]->country_calling_code;
        }
        
        $url = '';
        if(isset($result->urls[0]) && !empty($result->urls[0])){
            $url = $result->urls[0]->url;
        }
        
        $display_url = '';
        $display_link = '';
        
        if($url){
            if(strlen($url) > 25){
                $display_url = substr($url, 0, 25);
                $display_link = '<a href="'.$url.'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="'.$url.'">'.$display_url.'...</a>';
            } else {
                $display_url = $url;
                $display_link = '<a href="'.$url.'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="'.$url.'">'.$display_url.'</a>';
            }
        }
        
        $data[] = array(
            'name' => $name,
            'age_range' => $age_range,
            'phone_display' => $phone,
            'phone' => $phone,
            'address' => $display_address,
            'city' => $city,
            'state' => $state_code,
            'zip'   => $postal_code . '-'. $zip4,
            //'category' => '', // whitepages does not have category
            'url_link'   => $display_link,
            'url' => $url
            );
    }
    
} else {
    $results['dataError'] = array('message'=>'Missing parameters');
}

$results['draw'] = (int) $draw;
$results['data'] = $data;
echo json_encode($results);
die();