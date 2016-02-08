#!/usr/bin/php
<?php
/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT MIT
 * @copyright 2015 Smart Home Technology GmbH, smart-home-technology.ch
 * @author An adorable space cat
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

if ($argc != 2){
	echo "Usage: {$argv[0]} filename.s19\r\n";
	echo "Prints header record, if available, and total data size of srecord file.\r\n";
	echo "S19, S28, S37 file format is supported (honestly, it's the same)\r\n";
	return;
}

$fname = $argv[1];

if (!file_exists($fname)){
	echo "File does not exist: {$fname}";
	return 1;
}

echo "\r\nSRECORD {$fname}\r\n";

$recordFile = new SmartHomeTechnology\SRecord\SRecordFile($fname);

$header = $recordFile->getHeaderRecord();
if ($header !== NULL){
	echo "Header : \"{$header->data}\"\r\n";
} else {
	echo "Header : none\r\n";
}

echo "Total data size: " . $recordFile->getDataSize() . "\r\n";

echo "\r\nHave a spacevangerous day! purr\r\n";