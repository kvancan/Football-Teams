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
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - vue.js - order and filter list animation</title>
  <link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<div id="app" v-cloak>
	<div class="search">
		<div class="icon">
			<div class="circle"></div>
			<div class="bar"></div>
		</div>
		<div class="search-input">
			<input type="text" placeholder="Search..." v-model="searchQuery">
		</div>
	</div>
	<div class="table-container">
		<table>
			<thead>
				<tr>
					<th class="small center" @click="sort('position','asc')">#</th>
					<th class="medium center"></th>
					<th class="large" @click="sort('teamName','asc')">Club</th>
				</tr>
			</thead>
			<tbody name="fade-list" is="transition-group">
				<tr v-for="team in filteredBySearch" :key="team.position">
					<td class="small center">1</td>
					<td class="medium"><img :src="team.crestURI" alt=""></td>
					<td class="large"><?= $team1[0]["name"] ?></td>

					</td>
				</tr>
			</tbody>
		</table>
		<div class="warning" v-if="filteredBySearch.length<=0">
			<span>No results found.</span>
		</div>
	</div>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17-beta.0/vue.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js'></script>
<script src='https://codepen.io/ClementRoche/pen/OrGaMg.js'></script><script  src="./script.js"></script>

</body>
</html>
