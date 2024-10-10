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
      CURLOPT_URL => 'https://api.football-data.org/v4/teams/'.$id.'',
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
function showplayers(){
    $oyuncular = [];
    $players = access();
    $players = $players["squad"];
    foreach($players as $player){
        $club["name"] = $player["name"];
        $club["position"] = $player["position"];
        $club["nationality"] = $player["nationality"];
        $club["id"] = $player["id"];
        $club["dateofbirth"] = $player["dateOfBirth"];
        array_push($oyuncular,$club);
    }
    return $oyuncular;
}
$oyuncular = showplayers();
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
                    <th onclick="sortTable(2)">Name</th>
                    <th onclick="sortTable(3)">Position</th>
                    <th onclick="sortTable(4)">Nationality</th>
                    <th onclick="sortTable(5)">Date of Birth</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($oyuncular as $eleman){
                    $increment = $increment + 1; ?>
                <tr>
                    <td><?= $increment ?></td>
                    <td><?= $eleman["name"] ?></td>
                    <td><?= $eleman["position"] ?></td>
                    <td><?= $eleman["nationality"] ?></td>
                    <td><?= $eleman["dateofbirth"] ?></td>
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

