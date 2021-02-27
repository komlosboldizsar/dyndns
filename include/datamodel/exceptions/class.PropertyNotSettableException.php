<?php

namespace DynDns\Datamodel\Exceptions;

use Throwable;

class PropertyNotSettableException extends \Exception {

    function __construct($propertyName, $className)
    {
        parent::__construct("Property '{$propertyName}' is not settable in class '{$className}'!");
    }

}

?>