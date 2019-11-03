<!DOCTYPE html>
<html>
<head>
  <title>MTB - Player Eliminated</title>
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

  <!-- Raw HTML -->
  <div id="eliminate">
    <h2>Match Results</h2>

    <!-- Define Functions -->
    <?php
    //Score = 0.6 * Character + 0.3 * Catchiness + 0.1 * Cleanliness
    function calculateScore($char, $catch, $clean) {
      return 0.6 * $char + 0.3 * $catch + 0.1 * $clean;
    }
    ?>

    <!-- Page Logic -->
    <?php
    //get packaged data
    $team_1_name = $_POST["team_1_name"];
    $team_1_character = $_POST["team_1_character"];
    $team_1_cleanliness = $_POST["team_1_cleanliness"];
    $team_1_catchiness = $_POST["team_1_catchiness"];

    $team_2_name = $_POST["team_2_name"];
    $team_2_character = $_POST["team_2_character"];
    $team_2_cleanliness = $_POST["team_2_cleanliness"];
    $team_2_catchiness = $_POST["team_2_catchiness"];

    //calculate score to win:
    $team_1_score = calculateScore($team_1_character, $team_1_catchiness, $team_1_cleanliness);
    $team_2_score = calculateScore($team_2_character, $team_2_catchiness, $team_2_cleanliness);

    //display scores:
    echo "\t\t\t<h2 class='team_score'><em>$team_1_name final score: <span class='score'>$team_1_score</span></em></h2>\n";
    echo "\t\t\t<h2 class='team_score'><em>$team_2_name final score: <span class='score'>$team_2_score</span></em></h2>\n";
    echo "\t\t\t<br>\n";

    //find out the winners
    $eliminate_1 = -1;
    if ($team_1_score == $team_2_score) {
      echo "\t\t\t<h2>$team_1_name and $team_2_name have equal scores. They must play another round.</h2>\n";
    } else if ($team_1_score > $team_2_score) {
      echo "\t\t\t<h2><em>$team_1_name Wins!<br>$team_2_name has been eliminated!</em></h2>\n";
      $eliminate_1 = false;
    } else {
      echo "\t\t\t<h2><em>$team_2_name Wins!<br>$team_1_name has been eliminated!</em></h2>\n";
      $eliminate_1 = true;
    }

    //set losing team's "eliminated" to 1, and set each team's "round played" to 1
    if ($eliminate_1 === true || $eliminate_1 === false) {
      //connect to database
      $dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);

      //update eliminated
      $team_1_elm = mysqli_query($dbc, "UPDATE teams SET eliminate='" . ($eliminate_1 ? 1:0) . "' WHERE team_name='" . escapeString($team_1_name) . "'") or die(Q_FAIL . "<h3>Error updating 'eliminate' for $team_1_name in database</h3>");
      $team_2_elm = mysqli_query($dbc, "UPDATE teams SET eliminate='" . ($eliminate_1 ? 0:1) . "' WHERE team_name='" . escapeString($team_2_name) . "'") or die(Q_FAIL . "<h3>Error updating 'eliminate' for  $team_2_name in database</h3>");

      //update round played
      $team_1_rp = mysqli_query($dbc, "UPDATE teams SET round_played='1' WHERE team_name='" . escapeString($team_1_name) . "'") or die(Q_FAIL . "<h3>Error updating 'round_played' for $team_1_name in database</h3>");
      $team_2_rp = mysqli_query($dbc, "UPDATE teams SET round_played='1' WHERE team_name='" . escapeString($team_2_name) . "'") or die(Q_FAIL . "<h3>Error updating 'round_played' for $team_2_name in database</h3>");

      //check for deletion (all players have played)
      $get_unused_teams = mysqli_query($dbc, "SELECT * FROM teams WHERE round_played='0'") or die(Q_FAIL . "<h3>Error finding teams with round_played='0'</h3>");
      if (count(mysqli_fetch_array($get_unused_teams)) == 0) {
        //delete eliminated teams and set all round_played to 0
        $delete_teams = mysqli_query($dbc, "DELETE FROM teams WHERE eliminate='1'") or die(Q_FAIL . "<h3>Error removing eliminated players from table</h3>");
        $reset_rounds = mysqli_query($dbc, "UPDATE teams SET round_played='0'") or die(Q_FAIL . "<h3>Error resetting all teams' 'round_played' to '0'</h3>");
        echo "\t\t\t<h2>Half the teams have been eliminated!</h2>\n";
      }
      echo "\t\t\t<h2>Round Over.</h2>\n";
    }

    //link back to bracket
    echo "\t\t\t<div class='link'><a href='index.php'>Return to Bracket</a></div>\n";
    ?>
  </div>
</body>
</html>
