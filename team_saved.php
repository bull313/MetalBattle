<!DOCTYPE html>
<html>
<head>
  <title>MBT - Team Saved</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style/style.css">
  <link type="image/png" rel="icon" href="img/logo.png">
</head>
<body>
  <!-- imported files -->
  <?php
  require_once('banner.php');
  require_once('info.php');
  require_once('escape_string.php');
  ?>
  <div id="team_saved">
    <!-- Page Logic -->
    <?php
    //get team name and players
    $team_name = $_GET['team_name'];
    $team_players = $_GET['team_players'];

    //parse team players into array
    $player_list = preg_split('/\|/', $team_players);

    //validate team (check for repetition)
    $dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);
    $get_teams = mysqli_query($dbc, 'SELECT * FROM teams') or die(Q_FAIL . "<h3><em>Error loading all teams from database</em></h3>");
    $get_contenders = mysqli_query($dbc, 'SELECT * FROM contenders') or die(Q_FAIL . "<h3><em>Error loading all contenders from database</em></h3>");

    //count teams
    $num_teams = 0;
    while (($team = mysqli_fetch_array($get_teams))) { ++$num_teams; }
    if ($num_teams >= 16) {
      echo "\t\t<h2>Sorry! You have enough teams for a metal battle!<br> You cannot create any more!</h2>\n";
    }

    else {
      //check for equality
      $valid_name = true;
      $player_repeat = "";
      while (($contender = mysqli_fetch_array($get_contenders))) {
        if ($contender[1] == $team_name) {
          $valid_name = false;
        }

        foreach ($player_list as $player) {
          if ($player == $contender[0]) {
            $player_repeat .= $contender[0] . "|";
            break;
          }
        }
      }

      //if valid, continue, otherwise link back to player creation
      if ($valid_name && $player_repeat == "") {
        #add team to team list and each player to player list
        //add team to team list
        $team_name = escapeString($team_name);
        $add_team = mysqli_query($dbc, "INSERT INTO teams (team_name, eliminate, round_played) VALUES ('$team_name', '0', '0')") or die(Q_FAIL . "<h3><em>Error adding team to database</em></h3>");

        //add players to list
        foreach ($player_list as $player) {
          $esc_player = escapeString($player);
          $add_player = mysqli_query($dbc, "INSERT INTO contenders (name, team) VALUES ('$esc_player', '$team_name')") or die(Q_FAIL . "<h3><em>Error adding " . $player . " to database</em></h3>");
        }

        if ($num_teams == COMP_SIZE - 1) {
          echo "\t\t<h3>You have enough teams for a metal battle!</h3>\n";
          echo "\t\t<div class='link'><a href='index.php'>Back to Home Page</a></div>\n";
        } else {
          echo "\t\t<h3>This competition is still short " . (COMP_SIZE - $num_teams - 1) . " teams. You must add more teams before
          beginning this competition!</h3>";
          echo "\t\t<div class='link'><a href='create_team.php'>Create More Teams</a></div>\n";
        }
      } else {
        //escort user back to create team page
        echo "\t\t<h2>There was a problem creating your team:</h2>\n";

        if (!$valid_name) {
          echo "\t\t<h3>The team name you have submitted has already been taken.</h3>\n";
        }
        if ($player_repeat !== "") {
          echo "\t\t<h3><em>The following players are already on a team:</em></h3>\n";
          echo "\t\t<ul>";
          $repeat_list = preg_split('/\|/', $player_repeat);
          foreach ($repeat_list as $player) {
            if ($player == "") {
              break;
            }
            echo "\t\t\t<li>" . $player . "</li>";
          }
          echo "\t\t</ul>";
        }

        echo "\t\t<h2><em>Please go back to the team creation page and try again</em></h2>\n";
        echo "\t\t<div class='link'><a href='create_team.php'>Try Again</a></div>\n";
      }
    }
    ?>
  </div>
</body>
</html>
