#!/usr/bin/env php

<?php
$port = 4200;
if (isset($argv[1])) {
  $port = $argv[1];
}
$current_path = getcwd();
$pub_path = realpath($current_path . '/../../public');

echo 'Running in ', $current_path . PHP_EOL;

if ($current_path && $pub_path) {
  $commands = [
    'Delete tmp'         => "rm -rf {$current_path}/tmp",
    'Delete dist'        => "rm -rf {$pub_path}/frontend",
    'Ember version is:'  => "ember version",
    'Start ember server' => "ember serve --port=$port"
  ];

  foreach ($commands as $desc => $command) {
    echo $desc, PHP_EOL;
    system($command);
  }
}

