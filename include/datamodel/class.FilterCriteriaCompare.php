<?php

namespace DynDns\Datamodel;

use DynDns\Datamodel\FilterCriteria;

class FilterCriteriaCompare extends FilterCriteria {
	
	protected $property = null;
	protected $compareMode = null;
	protected $value = null;
	
	public function __construct($property, $compareMode, $value)
    {
		$this->property = $property;
		$this->compareMode = $compareMode;
		$this->value = $value;
	}
	
	protected function toMysqlQueryPart()
	{
		return new DataObjectFilterQueryPart($this->property . " " . $this->compareMode . " ?", array(MYSQLqueryparam::autoType($this->value)));
	}
	
}

?>