<?php

namespace DynDns\Datamodel;

use DynDns\Datamodel\FilterCriteriaCompare;

class FilterCriteriaEq extends FilterCriteriaCompare {
	
	public function __construct($property, $value)
    {
		parent::_construct($property, '=', $value);
	}
	
}

?>