<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function access(){

    $token = $_ENV["token"];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.football-data.org/v4/teams?limit=100&offset=null',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('X-Auth-Token: '.$token.''
        ),
      ));
      
      $response = curl_exec($curl);
      curl_close($curl);
      $value = json_decode($response, true);    
      return $value;
}
function show(){
    $names = [];
    $teams = access();
    for($i = 0; $i < 100; $i++){
        $team["name"] = $teams["teams"][$i]["name"];
        array_push($names, $team);
    }
    return $names;
}

$team1 = show();
var_dump($team1);
?>