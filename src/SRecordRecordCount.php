<?php

namespace SmartHomeTechnology\SRecord;

class SRecordRecordCount extends SRecord {
	public function getCount(){
		return $this->address;
	}
	public function __toString(){
		return "RECORD-COUNT previous record count = {$this->byteCount}"; 
	}
}