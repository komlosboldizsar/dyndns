<?php

namespace DynDns\Datamodel\Exceptions;

class PropertyNotExistsException extends \Exception {

    function __construct($propertyName, $className)
    {
        parent::__construct("Property '{$propertyName}' doesn't exists in class '{$className}'!");
    }

}

?>