<?php

namespace SmartHomeTechnology\SRecord;

class SRecordFile {

	protected $records;
	
	public function __construct($fname){
		$fdata = file($fname);
		$this->records = array();
		foreach($fdata as $line){
			$this->records[] = SRecord::factoryFromLine($line);
		}
	}
	
	public function getRecords(){ return $this->records; }
	
	public function getHeaderRecord(){
		$header = NULL;
		foreach($this->records as $r){
			if ($r instanceof SRecordHeader){
				$header = $r;
				break;
			}
		}
		return $header;
	}
	
	public function getExecutionStartRecord(){
		$header = NULL;
		foreach($this->records as $r){
			if ($r instanceof SRecordExecutionStartRecord){
				$header = $r;
				break;
			}
		}
		return $header;
	}
	
	/**
	*
	*/
	public function getDataSize(){
		return array_sum(
			array_map(
				function($v){
					return $v->getLength();					
				},
				array_filter(
					$this->records,
					function($v){
						return $v instanceof SRecordData;
					}
				)
			)
		);
	}

}