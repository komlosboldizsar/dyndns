<?php

namespace DynDns\Datamodel;

class FilterQueryPart {
	
	public $queryString = null;
	public $queryParams = null;
	
	public function __construct($queryString, $queryParams)
    {
		$this->queryString = $queryString;
		$this->queryParams = $queryParams;
	}
	
}

?>