<!DOCTYPE html>
<html>
<head>
  <title>MBT - Create Team</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style/style.css">
  <link type="image/png" rel="icon" href="img/logo.png">
  <script>
  function deleteChildren(id) {
    while (document.getElementById(id).firstChild)
      document.getElementById(id).removeChild(document.getElementById(id).firstChild);
  }

  function validateForm() {
    var field1 = document.getElementById("team_name");
    var field2 = document.getElementById("textarea");
    if (field1.value == "" || field2.value == "") {
      deleteChildren("err");
      document.getElementById("err").appendChild(document.createTextNode("You cannot create this team until both fields are filled out!"));
      return false;
    } else if (field2.value.endsWith('|')) {
      deleteChildren("err");
      document.getElementById("err").appendChild(document.createTextNode("Please don't end the player field with \"|\"!"));
      return false;
    }

    return true;
  }

  window.onload = function() {
    //script to modify the number of columns in the form's textarea element
    var textarea = document.getElementById("textarea");
    textarea.cols = textarea.textContent.length;

    //validate form
    document.getElementById("create_team_form").onsubmit = validateForm;
  };
  </script>
</head>
<body>
  <!-- imported files -->
  <?php
  require_once('banner.php');
  require_once('info.php');
  ?>
  <div id="create_team">
    <!-- Page Logic -->
    <?php
    //Get and display number of teams
    $dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);
    $get_teams = mysqli_query($dbc, 'SELECT * FROM teams') or die(Q_FAIL);

    //count teams
    $num_teams = 0;
    while (($team = mysqli_fetch_array($get_teams))) { ++$num_teams; }
    ?>

    <!--Raw HTML -->
    <h2 id='ct_title'>Create Team Page</h2>

    <!-- Create New Team Form -->
    <form id="create_team_form" action="team_saved.php" method="get">
      <div>
        <label>Team Name:</label> <input type="text" name="team_name" value=<?php echo "\"Team #" . ($num_teams + 1) . "\"" ?> id="team_name"> <br>
        <label>Players on Team (Follow Format Below):</label> <br>
        <textarea id="textarea" name="team_players">Player One|Player Two|Player Three|Player Four|Player Five</textarea> <br>
        <input type="submit" value="Create Team">
      </div>
    </form>

    <h3 id="err"></h3>

    <?php
      echo "\t\t\t<h2><em>You have " . (COMP_SIZE - $num_teams) . " empty team spots left to fill!</em></h2>\n";
    ?>
  </div>
</body>
</html>
