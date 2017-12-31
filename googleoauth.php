<?php
require_once (__DIR__ . '/google-api-php-client-2.2.1/vendor/autoload.php');

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_id.json');
$client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

function parse_event($event) {

	// Get Destination
    
    $words = explode(" ", $event->getSummary());
    $len = count($words);
    if ($len > 3){
	//When automatically created by Google Calderdar
	//e.g. Fly to Chicago (AC 5104)
	if ($words[$len-4] == "to"){
	    $flight = substr($words[$len-1], 1, -1);
	    $carrier = substr($words[$len-2], 1);
	    $flight = $carrier.$flight;
	}
    }

    //Zh-TW
    if ($words[$len-2] != "to"){
	$pos = mb_strpos($event->getSummary(), "往", 0,'UTF-8' );
	if ($pos) {
		$flight = substr($words[$len-1], 0, -1);
		$carrier = substr($words[$len-2], 1);
		$flight = $carrier.$flight;
	    //$dest = mb_substr($event->getSummary(), $pos+1, NULL,'UTF8' );
	    //$dest = explode(" ", $dest)[0];
	}
	else
	    return;
	}

	$credential = json_decode('key.json');	
	$username = $credential['username'];
	$apiKey = $credential['apiKey'];
	$fxmlUrl = $credential['fxmlUrl'];

	$queryParams = array(
	    'ident' => $flight,
	    'howMany' => 1,
	    'offset' => 0
	);
	$url = $fxmlUrl . 'FlightInfoStatus?' . http_build_query($queryParams);

	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	if ($result = curl_exec($ch)) {
		curl_close($ch);
		$ret = Array();
	    $result = json_decode($result, true);
	    $result =  $result['FlightInfoStatusResult']['flights'][0];
	    $ret['origin'] = $result['origin']['alternate_ident'];
	    $ret['dest'] = $result['destination']['alternate_ident'];
	    $ret['d_time'] = $result['filed_departure_time']['localtime'];
		$ret['e_time'] = $result['filed_arrival_time']['localtime'];
		return $ret
	}
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $calendar = new Google_Service_Calendar($client);

	// Print the next 10 events on the user's calendar.
	$calendarId = 'primary';
	$optParams = array(
	  'maxResults' => 10,
	  'orderBy' => 'startTime',
	  'singleEvents' => TRUE,
	  'timeMin' => date('c'),
	);
	$results = $calendar->events->listEvents($calendarId, $optParams);

	if (count($results->getItems()) == 0) {
		echo '<h1>No upcoming events found.</h1>';
	} else {
		$ret = Array();	
		foreach ($results->getItems() as $event) {
			$entry = parse_event($event);
			array_push($ret, $entry);
	  	}
	}

} else {
	$redirect_uri = 'https://'. $_SERVER['HTTP_HOST'] . '/IoT/oauth2callback.php';
	var_dump($redirect_uri);
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>
