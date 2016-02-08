<?php
/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT MIT
 * @copyright 2015 Smart Home Technology GmbH, smart-home-technology.ch
 * @author An innocent space cat
 */
namespace SmartHomeTechnology\SRecord;

/**
 * SRecordFile class
 * A container for SRecords, only current use case is to read a given srecord file
 * and to return basic information about it.
 * No further uses.
 */
class SRecordFile {

	const STYLE_UNKNOWN = 0;
	const STYLE_S19 = 1;
	const STYLE_S28 = 2;
	const STYLE_S37 = 3;

	var $records;
	
	const I_HEADER 		= 0;
	const I_COUNT		= 1;
	const I_START_ADDR 	= 2;
	
	var $uniqueRecords;
	
	public function __construct($fname){
		$fdata = file($fname);
		$this->records = array();
		$this->uniqueRecords = array(-1,-1,-1);
		foreach($fdata as $line){
			$srec = SRecord::createFromString($line);
			if ($srec){
				$this->records[] = $srec;
				
				$i = -1;
				switch($srec->type){
					case SRecord::TYPE_HEADER:
						$i = self::I_HEADER;
						break;
					
					case SRecord::TYPE_COUNT_16BIT:
					case SRecord::TYPE_COUNT_24BIT:
						$i = self::I_COUNT;
						break;
						
					case SRecord::TYPE_START_ADDR_32BIT:
					case SRecord::TYPE_START_ADDR_24BIT:
					case SRecord::TYPE_START_ADDR_16BIT:
						$i = self::I_START_ADDR;
						break;
				}
				if ($i > -1){
					$this->uniqueRecords[$i] = count($this->records) - 1;
				}
			}
		}
	}
	
	public function getAllRecords(){
		return $this->records;
	}
	
	public function getHeaderRecord(){
		$i = $this->uniqueRecords[self::I_HEADER];
		if (-1 < $i){
			return $this->records[$i];
		}
		return NULL;
	}
	
	public function getDataRecords(){
		return array_filter($records,function($srec){
			return $srec->isData();
		});
	}
	
	public function getDataSize(){
		return array_sum(array_map('count',$this->records));
	}

	public function getCountRecord(){
		$i = $this->uniqueRecords[self::I_COUNT];
		if (-1 < $i){
			return $this->records[$i];
		}
		return NULL;
	}
	
	public function getStartAddressRecord(){
		$i = $this->uniqueRecords[self::I_START_ADDR];
		if (-1 < $i){
			return $this->records[$i];
		}
		return NULL;
	}
}