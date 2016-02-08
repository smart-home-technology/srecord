<?php

namespace SmartHomeTechnology\SRecord;

class SRecordExecutionStartAddress extends SRecord {
	public function __toString(){
		return "EXECUTION-START-ADDRESS 0x" . sprintf('%0'.(self::$LENGTH_ADDRESS[$this->type]).'X',$this->address);
	}
}