<!--Initial HTML-->
<div id="victory">
  <h2>METAL BATTLE CHAMPION:</h2>

  <!-- Funcitions -->
  <?php
  function stringURL($str) {
    $escapeStr = "";
    for ($i = 0; $i < strlen($str); ++$i) {
      $char = substr($str, $i, 1);
      if ($char == ' ') {
        $escapeStr .= "+";
      } else if ($char == '/') {
        $escapeStr .= "%2F";
      } else if ($char == "?") {
        $escapeStr .= "%3F";
      } else if ($char == "&") {
        $escapeStr .= "%26";
      } else if ($char == ",") {
        $escapeStr .= "%2C";
      } else if ($char == ":") {
        $escapeStr .= "%3A";
      } else if ($char == "@") {
        $escapeStr .= "%40";
      } else if ($char == "#") {
        $escapeStr .= "%23";
      } else if ($char == "$") {
        $escapeStr .= "%24";
      } else if ($char == "%") {
        $escapeStr .= "%25";
      }
      else {
        $escapeStr .= $char;
      }
    }

    return $escapeStr;
  }
  ?>

  <!--Page Logic-->
  <?php
  require_once('escape_string.php');

  //Get and display number of teams
  $dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);
  $get_teams = mysqli_query($dbc, 'SELECT * FROM teams') or die(Q_FAIL . "<h3>Error loading teams table</h3>");
  $champion_team = mysqli_fetch_array($get_teams)[0];
  $champion_players = array();

  //get contenders
  $get_winning_contenders = mysqli_query($dbc, "SELECT * FROM contenders WHERE team='" . escapeString($champion_team) . "'") or die(Q_FAIL . "<h3>Error loading contenders</h3>");
  while (($contender = mysqli_fetch_array($get_winning_contenders))) {
    array_push($champion_players, $contender[0]);
  }

  //display champions
  echo "<h2 id='champion'>$champion_team</h2>\n";
  echo "<h3>Winning Band Mate(s):</h3>\n";
  echo "<ul>\n";
  foreach ($champion_players as $champ) {
    echo "\t<li class='win_list'>$champ</li>\n";
  }
  echo "</ul>\n";

  //cleanup
  echo "<h3 class='url'>URLs to Recreate Teams:</h3>\n";
  echo "<ul class='url'>\n";
  //display links to recreate each team
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
      //create player string
      $player_str = "";
      foreach ($current_players as $p) {
        $player_str .= $p . "|";
      }
      $player_str = substr($player_str, 0, -1);
      $player_str = stringURL($player_str);

      //display URL and reset
      echo "\t<li class='url'>http://localhost/my_sites/metal_battle/team_saved.php?team_name=$current_team&amp;team_players=$player_str</li>\n";

      $current_team = $player[1];
      $current_players = array();
      array_push($current_players, $player[0]);
    }
  }
  echo "</ul>\n";

  //delete every team and every contender; and reset competition->started to 0
  $delete_teams = mysqli_query($dbc, 'DELETE FROM teams') or die(Q_FAIL . "<h3>Error deleting all teams</h3>");
  $delete_players = mysqli_query($dbc, 'DELETE FROM contenders') or die(Q_FAIL . "<h3>Error deleting all contenders</h3>");
  $reset_comp = mysqli_query($dbc, 'UPDATE competition SET started=0 WHERE started=1') or die(Q_FAIL . "<h3>Error resetting competition started to 0</h3>");

  //reset button
  echo "\t<div class='link'><a href='index.php'>End Tournament</a></div>\n";
  ?>
</div>
