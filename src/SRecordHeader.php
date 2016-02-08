<?php

namespace SmartHomeTechnology\SRecord;

class SRecordHeader extends SRecord {
	
	public function getModuleName(){
		return hex2bin(substr($this->data,0,40));
	}
	
	public function getVersion(){
		return (ord($this->data[40]) << 4) && ord($this->data[41]);
	}
	
	public function getRevision(){
		return (ord($this->data[42]) << 4) && ord($this->data[43]);
	}
	
	public function getComment(){
		if (count($this->data) < 44){
			return NULL;
		}
		return hex2bin(substr($this->data,44));
	}
	
	public function __toString(){
		$comment = $this->getComment();
		return "HEADER ".$this->getModuleName()." ".$this->getVersion()." / ".$this->getRevision() . (empty($comment) ? "" : " ({$comment})");
	}
}