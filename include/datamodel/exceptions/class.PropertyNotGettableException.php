<?php

namespace DynDns\Datamodel\Exceptions;

class PropertyNotGettableException extends \Exception {

    function __construct($propertyName, $className)
    {
        parent::__construct("Property '{$propertyName}' is not gettable in class '{$className}'!");
    }

}

?>