<?php

namespace DynDns\MySQL;

class MySQLresult implements \IteratorAggregate {

    private $rows = array();
    private $numrows = null;
    private $insertid = -1;
    private $errno = 0;
    private $errmsg = 0;

    function __construct($rows, $numrows = 0, $insertid = null, $errno = 0, $errmsg = "")
    {
        $this->rows = $rows;
        $this->numrows = $numrows;
        $this->insertid = $insertid;
        $this->errno = $errno;
        $this->errmsg = $errmsg;
    }

    public function numRows() {
        return $this->numrows;
    }

    public function insertID() {
        return (($this->insertid !== null) && ($this->insertid > 0)) ? $this->insertid : null;
    }

    public function getRow($i) {
        return $this->rows[$i];
    }

    public function getIterator()
    {
        if ($this->rows === null)
            throw new \Exception("Result rows were not fetched!");
        return new \ArrayIterator($this->rows);
    }

    public function isSuccessful() {
        return ($this->errno == 0);
    }

    public function getErrorNumber() {
        return $this->errno;
    }

    public function getErrorMessage() {
        return $this->errmsg;
    }

}

?>