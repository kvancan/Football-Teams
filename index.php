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
        CURLOPT_URL => 'https://api.football-data.org/v4/competitions/',
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
function showcompetitions(){
    $ligler = [];
    $competitions = access();
    for($i = 0; $i < 10; $i++){
        $competition["name"] = $competitions["competitions"][$i]["name"];
        $competition["image"] = $competitions["competitions"][$i]["emblem"];
        $competition["id"] = $competitions["competitions"][$i]["id"];
        array_push($ligler,$competition);
    }
    return $ligler;
}
$ligler = showcompetitions();
$increment = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Teams Standings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Football League Standings</h1>
    </header>
    
    <section id="standings">
        <table id="standingsTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">#</th>
                    <th>Logo</th>
                    <th onclick="sortTable(2)">Standing</th>
                    <th onclick="sortTable(2)"></th>
                </tr>
            </thead>
            <tbody>
              <div id ="lig" >
                <?php foreach($ligler as $eleman){
                    $increment = $increment + 1;
                    
                    ?>
                <tr>
                    <td><?= $increment ?></td>
                    <td><img  src="<?= $eleman["image"] ?>" style = 'height:90px;' ></td>
                    <td><a href="teams.php?league=<?= $eleman["id"] ?>&name=<?= $eleman["name"] ?>"> <?= $eleman["name"] ?></a></td>
                    <td><a href="leaguematches.php?id=<?= $eleman["id"] ?>&name=<?= $eleman["name"] ?>"> <?= 'Matches' ?></a></td>
                </tr>
                <?php } ?>
                <!-- Add more teams here with their logos -->
              </div>
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; 2024 Football League</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
