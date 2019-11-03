<?php
//puts escape characters in names for easier query usage
function escapeString($str) {
  $escapeString = "";
  for ($i = 0; $i < strlen($str); ++$i) {
    $char = substr($str, $i, 1);
    if ($char == "'" || $char == "\"" || $char == "\\") {
      $escapeString .= "\\";
    }
    $escapeString .= $char;
  }
  return $escapeString;
}

//does the same thing but for HTML usage
function escapeHTML($str) {
  $escapeStr = "";
  for ($i = 0; $i < strlen($str); ++$i) {
    $char = substr($str, $i, 1);
    if ($char == '\'') {
      $escapeStr .= "&apos;";
    } else if ($char == '"') {
      $escapeStr .= "&quot;";
    } else if ($char == "&") {
      $escapeStr .= "&amp;";
    }
    else {
      $escapeStr .= $char;
    }
  }

  return $escapeStr;
}
?>
