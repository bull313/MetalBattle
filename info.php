<?php
  define('NET', getenv('DB_HOST'));
  define('USER', getenv('DB_USER'));
  define('PWD', getenv('DB_PWD'));
  define('DB', getenv('DB'));
  define('PORT', getenv('DB_PORT'));
  define('Q_FAIL', "<h2>MYSQL had a problem running a query....</h2>");
  define('FAIL', "<h2>Error connecting to database " . DB . ".</h2>");
  define('COMP_SIZE', 16);
?>
