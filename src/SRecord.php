<?php

namespace SmartHomeTechnology\SRecord;

abstract class SRecord {

	static public $OFFSET_TYPE			= 1;
	static public $OFFSET_BYTECOUNT 	= 2;
	static public $OFFSET_ADDRESS 		= 4;
	static public $LENGTH_ADDRESS 		= array(
											 0 => 2,
											 1 => 2,
											 2 => 3,
											 3 => 4,
											 5 => 2,
											 7 => 8,
											 8 => 3,
											 9 => 2
										);


	protected $type;
	protected $byteCount;
	protected $address;
	protected $data;
	protected $checksum;	
	
	
	public static function factoryFromLine($line){
	
		$line = trim($line);
		
		if ($line[0] != 'S'){
			throw Exception("Wrong record start: {$line[0]}");
		}
		
		$type = intval($line[1]);
		
		$record = NULL;
		switch($type){
			case 0:
				$record = new SRecordHeader();
				break;
			
			case 1:
			case 2:
			case 3:
				$record = new SRecordData();
				break;
			
			case 5:
				$record = new SRecordRecordCount();
				break;
			
			case 7:
			case 8:
			case 9:
				$record = new SRecordExecutionStartAddress();
				break;
			
			default:
				throw new Exception();
		}
		$record->type = $type;
		
		$record->byteCount = hexdec(substr($line,self::$OFFSET_BYTECOUNT,self::$OFFSET_ADDRESS - self::$OFFSET_BYTECOUNT));
		$length_address = 2*self::$LENGTH_ADDRESS[$type];
		$record->address = hexdec(substr($line,self::$OFFSET_ADDRESS, $length_address));
		
		$offset_data = self::$OFFSET_ADDRESS + $length_address;
		$length_data = 2 * $record->byteCount - $length_address - 2;
		if ($length_data < 0){
			$length_data = 0;
		}
		$record->data = substr($line,$offset_data, $length_data);
		
		$offset_checksum = $offset_data + $length_data;
		$record->checksum = hexdec(substr($line, $offset_checksum, 2));
		
		return $record;
	}
	
	public function getType(){ return $this->type; }
	public function getByteCount(){ return $this->byteCount; }
	public function getAddress(){ return $this->address; }
	public function getData(){ return $this->data; }
	public function getChecksum(){ return $this->checksum; }
	
	abstract public function __toString();
}