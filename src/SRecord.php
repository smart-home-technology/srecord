<?php
/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT MIT
 * @copyright 2015 Smart Home Technology GmbH, smart-home-technology.ch
 * @author A bewildered space cat
 */
namespace SmartHomeTechnology\SRecord;

/**
 * SRecord class
 * An abstraction of individual srecords within a given file.
 */
class SRecord implements \Countable {

	const TYPE_HEADER 		= 0;
	const TYPE_DATA_16BIT 	= 1;
	const TYPE_DATA_24BIT	= 2;
	const TYPE_DATA_32BIT	= 3;
	const TYPE_RESERVED		= 4;
	const TYPE_COUNT_16BIT	= 5;
	const TYPE_COUNT_24BIT	= 6;
	const TYPE_START_ADDR_32BIT = 7;
	const TYPE_START_ADDR_24BIT = 8;
	const TYPE_START_ADDR_16BIT = 9;

	const OFFSET_TYPE			= 1;
	const OFFSET_BYTECOUNT 		= 2;
	const OFFSET_ADDRESS 		= 4;
	
	static public $LENGTH_ADDRESS 		= array(
											 0 => 4,
											 1 => 4,
											 2 => 6,
											 3 => 8,
											 5 => 4,
											 7 => 8,
											 8 => 6,
											 9 => 4
										);

// 	var $records = array();

	var $type;
	var $byteCount;
	var $address;
	var $data;
	var $checksum;
	
	// public function __construct(){
	
	// }
	
	public static function createFromString($line){
		
		$srec = new self();
		
		$line = trim($line);
		
		if ($line[0] != 'S'){
			throw Exception("Wrong record start: {$line[0]}");
		}
		
		$srec->type = intval($line[1]);
		$srec->byteCount = hexdec(substr($line,self::OFFSET_BYTECOUNT,self::OFFSET_ADDRESS - self::OFFSET_BYTECOUNT));
		$srec->address = hexdec(substr($line,self::OFFSET_ADDRESS, self::$LENGTH_ADDRESS[$srec->type]));
		
		$offset_data = self::OFFSET_ADDRESS + self::$LENGTH_ADDRESS[$srec->type];
		$length_data = 2 * $srec->byteCount - self::$LENGTH_ADDRESS[$srec->type] - 2;
		$srec->data = hex2bin(substr($line,$offset_data, $length_data));
		
		$offset_checksum = $offset_data + $length_data;
		$srec->checksum = hexdec(substr($line, $offset_checksum, 2));
		
		return $srec;
	}
	
	public function __toString(){
		switch($this->type){
			case self::TYPE_HEADER:
				return "HEADER {$this->data}";
				
			case self::TYPE_DATA_16BIT:
			case self::TYPE_DATA_24BIT:
			case self::TYPE_DATA_32BIT:
				return "DATA length = " . count($this);
				
			case self::TYPE_COUNT_16BIT:
			case self::TYPE_COUNT_24BIT:
				return "RECORD-COUNT previous record count = {$this->byteCount}"; 
			
			case self::TYPE_START_ADDR_32BIT:
			case self::TYPE_START_ADDR_24BIT:
			case self::TYPE_START_ADDR_16BIT:
				return "EXECUTION-START-ADDRESS 0x" . sprintf('%0'.(self::$LENGTH_ADDRESS[$this->type]).'X',$this->address);
		}
	}
	
	public function count(){
		switch($this->type){
				
			case self::TYPE_DATA_16BIT:
			case self::TYPE_DATA_24BIT:
			case self::TYPE_DATA_32BIT:
				return $this->byteCount;
				
			default:
				return 0;
		}
	}
	
	public function isHeader(){
		return $this->type == self::TYPE_HEADER;
	}
	
	public function isData(){
		return ($this->type == self::TYPE_DATA_16BIT or $this->type == self::TYPE_DATA_24BIT or $this->type == self::TYPE_DATA_32BIT);
	}
	
	public function isCount(){
		return ($this->type == self::TYPE_COUNT_16BIT or $this->type == self::TYPE_COUNT_24BIT);
	}
	
	public function isStartAddress(){
		return ($this->type == self::TYPE_START_ADDR_16BIT or $this->type == self::TYPE_START_ADDR_24BIT or $this->type == self::TYPE_START_ADDR_32BIT);
	}
}