<?php
/**
 * 
 */
require_once(__DIR__.'/../config.php');
$API_HOST = 'proapi.whitepages.com';
$SEARCH_LIMIT = 1;
$SEARCH_PATH = '/2.1/business.json'; //business search API path
$SEARCH_PATH = '/2.1/person.json'; //person search API path

/** 
 * Makes a request to the Whitepages API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request($host, $path) {
    $unsigned_url = "https://" . $host . $path;

    // Send Whitepage API Call
    $ch = curl_init($unsigned_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    //Get the status codes
    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE); // for error handling
    curl_close($ch);
    
    return $data;
}

/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term = '', $location = '',$limit = 1,$offset = 0,$sort = 0) {
    $url_params = array();
    $url_params['api_key'] = $GLOBALS['API_KEY'];
    $url_params['name'] = $term; // business name or any keyword
    $url_params['city'] = $location; // not documented but this is required field
    if($limit != ''){
        $url_params['page_len'] = $limit;
    }
    if($offset != ''){
        $url_params['page_first'] = $offset;
    }
    //$url_params['sort'] = $sort; // invalid for whitepages
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    
    return request($GLOBALS['API_HOST'], $search_path);
}

function searchPerson($term = '', $location = '',$postal_code = '',$limit = 1,$offset = 0,$sort = 0) {
    $url_params = array();
    $url_params['api_key'] = $GLOBALS['API_KEY'];
    $url_params['name'] = $term; // name 
    if($location != ''){
        $url_params['city'] = $location; // for person search this is not required
    }
    if($postal_code != ''){
        $url_params['postal_code'] = $postal_code;
    }
    if($limit != ''){
        $url_params['page_len'] = $limit;
    }
    if($offset != ''){
        $url_params['page_first'] = $offset;
    }
    //$url_params['sort'] = $sort; // invalid for whitepages
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    if($term != ''){
        return request($GLOBALS['API_HOST'], $search_path);
    } else {
        return ''; // dont search is $term is empty
    }
    
}