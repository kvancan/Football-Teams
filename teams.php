<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$name = $_GET["name"];
function access(){
    $token = $_ENV["token"];
    $curl = curl_init();
    $league = $_GET["league"];
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.football-data.org/v4/competitions/'.$league.'/standings',
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
function showteams(){
    $takimlar = [];
    $teams = access();
    $teams = $teams['standings'][0]["table"];
    foreach($teams as $team){
        $club["name"] = $team["team"]["name"];
        $club["image"] = $team["team"]["crest"];
        $club["id"] = $team["team"]["id"];
        $club["won"] = $team["won"];
        $club["draw"] = $team["draw"];
        $club["lost"] = $team["lost"];
        $club["points"] = $team["points"];
        $club["played"] = $team["playedGames"];
        array_push($takimlar,$club);
    }
    return $takimlar;
}

$takimlar = showteams();
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
                    <th onclick="sortTable(2)">Team</th>
                    <th onclick="sortTable(2)"></th>
                    <th onclick="sortTable(3)">Played</th>
                    <th onclick="sortTable(4)">Won</th>
                    <th onclick="sortTable(5)">Draw</th>
                    <th onclick="sortTable(6)">Lost</th>
                    <th onclick="sortTable(7)">Points</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($takimlar as $eleman){
                    $increment = $increment + 1; ?>
                <tr>
                    <td><?= $increment ?></td>
                    <td><img src="<?= $eleman["image"] ?>" alt="Team A Logo" class="team-logo"  ></td>
                    <td><a href="players.php?id=<?= $eleman["id"] ?>&name=<?= $eleman["name"] ?>"> <?= $eleman["name"] ?></a></td>
                    <td><a href="matches.php?id=<?= $eleman["id"] ?>&name=<?= $eleman["name"] ?>"> <?= 'Matches' ?></a></td>
                    <td><?= $eleman["played"] ?></td>
                    <td><?= $eleman["won"] ?></td>
                    <td><?= $eleman["draw"] ?></td>
                    <td><?= $eleman["lost"] ?></td>
                    <td><?= $eleman["points"] ?></td>
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

