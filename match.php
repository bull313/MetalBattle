<!DOCTYPE html>
<html>
<head>
  <?php
  //get packaged data
  $team_1 = $_POST['team_1'];
  $team_2 = $_POST['team_2'];
  ?>
  <title>!!!MTB - <?php echo "$team_1 VS $team_2" ?>!!!</title>
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

  <!-- Page Logic -->
  <?php
  //print the teams out
  echo "\t<h2 id='versus'><span id='team_1'>" . escapeHTML($team_1) . "</span> <span id='vs'>V.S.</span> <span id='team_2'>" . escapeHTML($team_2) . "</span></h2>\n";

  //evaluation criteria strings
  $eval_criteria = array("Character", "Catchiness", "Cleanliness");

  //generate form with select options
  echo "\t<form action='eliminate.php' method='post'>\n";
  echo "\t\t<div>\n";
  for ($i = 0; $i < 2; ++$i) {
    echo "\t\t\t<div id='select_team_" . ($i + 1) . "'>\n";
    for ($j = 0; $j < 3; ++$j) {
      $team = $team_2;
      if ($i == 0) {
        $team = $team_1;
      }
      echo "\t\t\t\t<label class='select_label'>" . $team . " " . $eval_criteria[$j] . ":</label>\n";
      echo "\t\t\t\t<select name='team_" . ($i + 1) . "_" . strtolower($eval_criteria[$j]) . "'>\n";
      for ($k = 0; $k <= 100; ++$k) {
        echo "\t\t\t\t\t<option value='$k'>$k</option>\n";
      }
      echo "\t\t\t\t</select><br>\n";
    }
    echo "\t\t\t</div>\n";
  }

  echo "\t\t\t<input type='hidden' name='team_1_name' value='" . escapeHTML($team_1) . "'>\n";
  echo "\t\t\t<input type='hidden' name='team_2_name' value='" . escapeHTML($team_2) . "'>\n";
  echo "\t\t\t<div id='match_submit'><input type='submit' value='Submit Scores'></div>\n";
  echo "\t\t</div>\n";
  echo "\t</form>\n";
  ?>
</body>
</html>
