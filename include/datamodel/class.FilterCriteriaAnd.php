<?php

namespace DynDns\Datamodel;

use DynDns\Datamodel\FilterCriteriaCombine;

class FilterCriteriaAnd extends FilterCriteriaCombine {
	
	protected abstract function combinerWord()
	{
		return "AND";
	}
	
}

?>