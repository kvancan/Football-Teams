<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$name = $_GET["name"];
function access(){
    $token = $_ENV["token"];
    $id = $_GET["id"];
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.football-data.org//v4/teams/'.$id.'/matches',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'X-Auth-Token: '.$token.''
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $value = json_decode($response, true);   
    return $value;
}
function showmatches(){
    $maclar = [];
    $matches = access();
    $matches = $matches["matches"];
    foreach($matches as $match){
        $club["status"] = $match["status"];        
        $club["hometeam"] = $match["homeTeam"]["name"];
        $club["awayteam"] = $match["awayTeam"]["name"];
        $club["hometeamimage"] = $match["homeTeam"]["crest"];
        $club["awayteamimage"] = $match["awayTeam"]["crest"];
        $club["hometeamscore"] = $match["score"]["fullTime"]["home"];
        $club["awayteamscore"] = $match["score"]["fullTime"]["away"];
        array_push($maclar,$club);
    }
    return $maclar;
}
$maclar = showmatches();
$increment = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1><?= $name ?></h1>
    </header>
    
    <section id="standings">
        <table id="standingsTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">#</th>
                    <th>Logo</th>
                    <th onclick="sortTable(2)">Home Team</th>
                    <th onclick="sortTable(3)">Score</th>
                    <th onclick="sortTable(4)">Score</th>
                    <th>Logo</th>
                    <th onclick="sortTable(2)">Away Team</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($maclar as $eleman){
                    $increment = $increment + 1;
                    
                    if ($eleman["status"] == 'TIMED') {
                       
                        $eleman["hometeamscore"] = 'TIMED';
                        $eleman["awayteamscore"] = 'TIMED';
                    }

                    if ($eleman["status"] == 'SCHEDULED') {
                       
                        $eleman["hometeamscore"] = 'SCHEDULED';
                        $eleman["awayteamscore"] = 'SCHEDULED';
                    }

                    ?>
                <tr>
                    <td><?= $increment ?></td>
                    <td><img src="<?= $eleman["hometeamimage"] ?>" alt="Team A Logo" class="team-logo"  ></td>
                    <td><?= $eleman["hometeam"] ?></td>
                    <td style = 'font-family: math;' ><?= $eleman["hometeamscore"] ?></td>
                    <td style = 'font-family: math;' ><?= $eleman["awayteamscore"] ?></td>
                    <td><img src="<?= $eleman["awayteamimage"] ?>" alt="Team A Logo" class="team-logo"  ></td>
                    <td><?= $eleman["awayteam"] ?></td>
                </tr>
                <?php } ?>
                <!-- Add more teams here with their logos -->
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; 2024 Football League</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
