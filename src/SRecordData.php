<?php

namespace SmartHomeTechnology\SRecord;

class SRecordData extends SRecord {

	public function getLength(){
		return strlen($this->data) / 2;
	}
	
	public function __toString(){
		return "DATA length = " . $this->getLength() . " {$this->data}";
	}
}