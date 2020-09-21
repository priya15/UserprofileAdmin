    <?php
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $lat1="22.7265665";
    $long1="75.88097429999999";
    $lat2 = "22.692633";
    $long2="75.86760749999999";
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyDu09OTRUuqUanO3wkqiD6kC9waAd46oK4";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    print_r($response_a);die();
    echo $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    echo $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

   // return array('distance' => $dist, 'time' => $time);
?>