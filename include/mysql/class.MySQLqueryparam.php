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
	
	public static function autoType($value)
	{
		if(is_int($value))
			return new MySQLqueryparam(MySQLqueryparam::INTEGER, $value)
		else if (is_float($value) || is_double($value))
			return new MySQLqueryparam(MySQLqueryparam::DOUBLE, $value);
		else if (is_bool($value))
			return new MySQLqueryparam(MySQLqueryparam::INTEGER, ($value) ? 1 : 0);
		else
			return new MySQLqueryparam(MySQLqueryparam::STRING, $value);
	}

}

?>