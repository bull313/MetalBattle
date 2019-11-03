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
  <?php
  //load database
  $dbc = mysqli_connect(NET, USER, PWD, DB) or die(FAIL);

  //query to find competition started boolean
  $get_comp_started = mysqli_query($dbc, 'SELECT * FROM competition') or die(Q_FAIL);
  $started = mysqli_fetch_array($get_comp_started)[0];

  //load different page based on value
  if ($started == 0) {
    require_once('no_comp.php');
  } else {
    require_once("bracket.php");
  }
  ?>
</body>
</html>
