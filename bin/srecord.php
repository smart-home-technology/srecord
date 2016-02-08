#!/usr/bin/php
<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

if ($argc == 1){
	echo "Usage: {$argv[0]} filename.s19\n";
	return;
}

$fname = $argv[1];

if (!file_exists($fname)){
	echo "File does not exist: {$fname}";
	return 1;
}


$recordFile = new SmartHomeTechnology\SRecord\SRecordFile($fname);

// $options = getopt();

foreach($recordFile->getRecords() as $r){
	echo "{$r}\n";
}

echo "Total data size: ".$recordFile->getDataSize()."\n";

// foreach($fdata as $line){
// 	$srec = new SRecord($line);
// 	echo "{$srec}\n";
// }

// var_dump($b);
