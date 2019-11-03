<div id="no_comp">
<!-- INITIAL HTML -->
<h2>No Competition in Progress</h2>
<!-- Page Logic -->
<?php
#get current number of teams
//load database
$dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);
$get_teams = mysqli_query($dbc, 'SELECT * FROM teams') or die(Q_FAIL . "<h3><em>Error loading all teams from database</em></h3>");

//count teams
$num_teams = 0;
while (($team = mysqli_fetch_array($get_teams))) { ++$num_teams; }
###
//if number of teams is less than required, tell user and link them to team creation interface
//if we are at the correct number of teams, display the start competition button to set competition value to 1 and
//jump to home page
if ($num_teams < COMP_SIZE) {
  echo "\t<h3>This competition is short " . (COMP_SIZE - $num_teams) . " teams. You must add more teams before
  beginning this competition!</h3>\n";
  echo "\t<div class='link'><a href='create_team.php'>Create Teams</a></div>\n";
} else if ($num_teams == COMP_SIZE) {
  //set the started attribute in competition table to true (1)
  $start_comp = mysqli_query($dbc, 'UPDATE competition SET started=1 WHERE started=0') or die(Q_FAIL . "<h3><em>Error setting competition started to true (1)</em></h3>");

  echo "\t<h3>A METAL BATTLE is ready to begin!</h3>\n";
  echo "\t<div class='link'><a href='index.php'>TURN SOME HEADS!!!</a></div>\n";
} else {
  echo "\t<h1>There is a horrible error going on...\n";
  echo "\t<br>There are MORE teams than required for the battle.<br>Please fix this immediately....</h1>\n";
}
?>
</div>
