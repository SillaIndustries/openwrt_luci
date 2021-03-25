#!/usr/bin/php
<?php


function postprocess_file($file)
{

  $fd = fopen($file, "r");

  $buffer = array();

  $state = 0;
  $locations = null;

  while (($line = fgets($fd)) !== false) {
    $line = rtrim($line, "\r\n");

    if ($state == 0) {
      if (substr($line, 0, 2) != "#:") {
        $buffer[] = $line;
        continue;
      }

      $locations = array();
      $state = 1;
    }

    if ($state == 1) {
      assert(is_array($locations));

      if (substr($line, 0, 2) != "#:") {
        /* output all the locations we acquired */
        foreach ($locations as $location) {
          $buffer[] = "#: " . $location;
        }
        /* output the current line */
        $buffer[] = $line;
        $state = 0;
        continue;
      }
      else {
        if (!preg_match('{^#: ([a-z0-9/._-]+):(\d+)$}', $line, $regp))
          exit("BOGUS LINE: $line\n");

        $_pure_file = $regp[1];
        if (preg_match('{^vendor/(.+)$}', $_pure_file, $regp))
          $_pure_file = "applications/" . $regp[1];

        if (!in_array($_pure_file, $locations))
          $locations[] = $_pure_file;
      }
    }
  }
  fclose($fd);

  $buffer = implode("\n", $buffer) . "\n";

  file_put_contents($file, $buffer);
}

postprocess_file($argv[1]);
