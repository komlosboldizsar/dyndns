<?php

namespace DynDns\MySQL;

class MySQLqueryparam {

    const INTEGER = "i";
    const DOUBLE = "d";
    const STRING = "s";
    const BLOB = "b";

    private $type;
    private $value;

    function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }

}

?>