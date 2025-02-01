<?php

namespace DynDns\Datamodel;

use DynDns\Datamodel\FilterCriteriaCombine;

class FilterCriteriaOr extends FilterCriteriaCombine {
	
	protected abstract function combinerWord()
	{
		return "OR";
	}
	
}

?>