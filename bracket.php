<!--Initial HTML-->
<div id="bracket">
  <!--Page Logic-->
  <?php
  //get needed functions
  require_once("escape_string.php");

  //Get and display number of teams
  $dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);
  $get_teams = mysqli_query($dbc, 'SELECT * FROM teams') or die(Q_FAIL);

  //count teams
  $num_teams = 0;
  $team_list = array();
  $team_1 = false;
  $team_2 = false;
  while (($team = mysqli_fetch_array($get_teams))) {
    if ($team[1] == 0) {
      array_push($team_list, $team[0]);
    } else {
      array_push($team_list, "|" . $team[0]);
    }

    if ($team_1 === false && $team[2] == 0) {
      $team_1 = $team[0];
    } else if ($team_1 !== false && $team_2 === false && $team[2] == 0) {
      $team_2 = $team[0];
    }

    ++$num_teams;

    if ($num_teams > 16) {
      $remove_team = mysqli_query($dbc, "DELETE FROM teams WHERE team_name='" . escapeString($team[0]) . "'") or die(Q_FAIL);
      --$num_teams;
    }
  }

  //choose the proper bracket
  $teams_per_side = -1;
  $img = "";
  if ($num_teams > COMP_SIZE / 2 && $num_teams <= COMP_SIZE) {
    $img = "<div class='brkt'><img src='img/brkt_16.png' alt='Bracket Load Error....'></div>\n";
    $teams_per_side = COMP_SIZE / 2;
  } else if ($num_teams > COMP_SIZE / 4 && $num_teams <= COMP_SIZE / 2) {
    $img = "<div class='brkt'><img src='img/brkt_8.png' alt='Bracket Load Error....'></div>\n";
    $teams_per_side = COMP_SIZE / 4;
  } else if ($num_teams > COMP_SIZE / 8 && $num_teams <= COMP_SIZE / 4) {
    $img = "<div class='brkt'><img src='img/brkt_4.png' alt='Bracket Load Error....'></div>\n";
    $teams_per_side = COMP_SIZE / 8;
  } else if ($num_teams == COMP_SIZE / 8) {
    $img = "<div class='brkt'><h2 id='final_2'>V.S.</h2></div>\n";
    $teams_per_side = COMP_SIZE / 16;
  } else {
    require_once("victory.php");
  }

  if ($teams_per_side != -1) {
    echo "<h2>The Bracket</h2>\n";
    for ($i = 0; $i < 2; ++$i) {
      echo "<div class='side" . (($i == 0) ? " right":" left") . "'>\n";
      for ($j = 0; $j < $teams_per_side; ++$j) {
        $team_str = $team_list[$teams_per_side * $i + $j];
        if (substr($team_str, 0, 1) != "|") {
          echo "\t<div class='brkt_team_$teams_per_side'>" . $team_str . "</div><br>\n";
        } else {
          echo "\t<div class='brkt_team_$teams_per_side eliminated'><span class='del'>" . substr($team_str, 1) . "</span></div><br>\n";
        }
      }
      echo "</div>\n";

      if ($i == 0) {
        echo "\t" . $img . "\n";
      }
    }

    echo "\t<!--Hold the invisible inputs-->\n";
    echo "\t<form id=\"bracket_form\" action=\"match.php\" method=\"post\">\n";
    echo "\t\t<div>\n";
    echo "\t\t\t<input type=\"hidden\" value=\"" . escapeHTML($team_1) . "\" name=\"team_1\">\n";
    echo "\t\t\t<input type=\"hidden\" value=\"" . escapeHTML($team_2) . "\" name=\"team_2\">\n";
    echo "\t\t\t<input type=\"submit\" value=\"Upcoming Match: " . escapeHTML($team_1) . " V.S. " . escapeHTML($team_2) . "\">\n";
    echo "\t\t</div>\n";
    echo "\t</form>\n";
  }
  ?>
</div>
