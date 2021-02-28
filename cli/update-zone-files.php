<?php

require_once '_includes.php';
use \DynDns\Datamodel\Zone;

function help() {
    global $argv;
    echo "Usage: php {$argv[0]}\n";
    echo "\n";
}

if ($argc < 1) {
    echo "Not enough arguments passed.\n";
    help();
    return;
}

if (($argc >= 2) && (($argv[1] == '-h') || ($argv[1] == '--help'))) {
    help();
    return;
}

foreach (Zone::all() as $zone) {
    if ($zone->needsUpdate()) {
        $zone->generateFile();
        printf("File '%s' (zone '%s') updated.\n", $zone->symname, $zone->file);
    }
}

?>