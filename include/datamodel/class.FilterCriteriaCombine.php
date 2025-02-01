<?php

namespace DynDns\Datamodel;

use DynDns\Datamodel\FilterCriteria;

abstract class FilterCriteriaCombine extends FilterCriteria {
	
	protected $parts = null;
	
	public function __construct($parts)
    {
		$this->parts = $parts;
	}
	
	protected function toMysqlQueryPart()
	{
		$queryStringParts = array();
		$queryParams = array();
		foreach ($this->parts as $part) {
			$partQueryData = $part->toMysqlQueryPart();
			$queryStringParts[] = "(" . $partQueryData->queryString . ")";
			$queryParams = array_merge($queryParams, $partQueryData->queryParams);
		}
		$combinerWord = " " . combinerWord() . " ";
		$queryString = implode($combinerWord, $queryStringParts);
		return new DataObjectFilterCriteriaPart($queryString, $queryParams);
	}
	
	protected abstract function combinerWord();
	
}

?>