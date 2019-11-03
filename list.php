<!-- Print out each team with team members -->
<!DOCTYPE html>
<html>
<head>
  <title>!!!METAL BATTLE!!!</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style/style.css">
  <link type="image/png" rel="icon" href="img/logo.png">
</head>
<body>
  <!-- imported files -->
  <?php
  require_once('banner.php');
  require_once('info.php');
  ?>
  <!-- Page Logic -->
  <div id="list">
    <?php
    //load database
    $dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);

    $get_contenders = mysqli_query($dbc, 'SELECT * FROM contenders') or die(Q_FAIL . "<h3>Error loading contenders table</h3>");
    $current_team = "";
    $current_players = array();
    while (($player = mysqli_fetch_array($get_contenders))) {
      if ($current_team == "") {
        $current_team = $player[1];
        array_push($current_players, $player[0]);
        continue;
      }

      if ($current_team == $player[1]) {
        array_push($current_players, $player[0]);
      } else {
        //display team and reset
        echo "\t\t\t<h3>$current_team</h3>\n";
        echo "\t\t\t<ul>\n";
        foreach ($current_players as $the_player) {
          echo "\t\t\t\t<li>$the_player</li>\n";
        }
        echo "\t\t\t</ul>\n";

        $current_team = $player[1];
        $current_players = array();
        array_push($current_players, $player[0]);
      }
    }
    ?>
  </div>
</body>
</html>
