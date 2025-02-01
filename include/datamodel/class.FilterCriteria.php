<?php

namespace DynDns\Datamodel;

abstract class FilterCriteria {
	protected abstract function toMysqlQueryPart();
}

?>