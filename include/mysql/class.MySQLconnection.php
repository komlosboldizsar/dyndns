<?php

namespace DynDns\MySQL;

class MySQLconnection {

    private static $_defaultConnection = null;

    public static function defaultConnection() {
        if (self::$_defaultConnection === null)
            self::$_defaultConnection = new MySQLconnection(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
        return self::$_defaultConnection;
    }

    private $connection = null;

    private $host;
    private $username;
    private $password;
    private $database;

    function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    function __destruct()
    {
        $this->disconnect();
    }

    private function connect() {

        if ($this->connection !== null)
            return;

        $this->connection = new \mysqli($this->host, $this->username, $this->password, $this->database);

        // Error handling
        if ($this->connection->connect_error) {
            $error = $this->connection->connect_error;
            $this->connection = null;
            die("MySQL connect error: " . $error);
        }

    }

    private function disconnect() {
        if ($this->connection === null)
            return;
        $this->connection->close();
    }

    public function connected() {
        return ($this->connection !== null);
    }

    const FETCH_ARRAY_INDEX = "fetch_array_index";
    const FETCH_ARRAY_ASSOC = "fetch_array_assoc";
    const FETCH_ARRAY_BOTH = "fetch_array_both";
    const FETCH_OBJECT = "fetch_object";

    function query($querystring, $params = array(), $fetchresult = true, $fetchmode = self::FETCH_OBJECT) {

        if ($this->connection === null)
            return;

        $stmt = $this->connection->prepare($querystring);

        if (!empty($params)) {

            $paramTypes = '';
            $paramValues = array();
            foreach ($params as $param) {
                $paramTypes .= $param->getType();
                $paramValues[] = $param->getValue();
            }

            $callParams = array(&$paramTypes);
            foreach ($paramValues as &$paramValue)
                $callParams[] = &$paramValue;

            call_user_func_array(array($stmt, 'bind_param'), $callParams);

        }

        $stmt->execute();

        $result = $stmt->get_result();
        $rows = array();

        if ($fetchresult && ($result != false)) {
            switch($fetchmode) {
                case self::FETCH_ARRAY_INDEX:
                {
                    while ($row = $result->fetch_array(MYSQLI_NUM))
                        $rows[] = $row;
                } break;
                case self::FETCH_ARRAY_ASSOC:
                {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC))
                        $rows[] = $row;
                } break;
                case self::FETCH_ARRAY_BOTH:
                {
                    while ($row = $result->fetch_array(MYSQLI_BOTH))
                        $rows[] = $row;
                } break;
                case self::FETCH_OBJECT:
                default: {
                    while ($row = $result->fetch_object())
                        $rows[] = $row;
                } break;
            }
        } else {
            $rows = null;
        }

        $num_rows = $stmt->num_rows;
        if ($stmt->affected_rows > 0)
            $num_rows = $stmt->affected_rows;

        return new MySQLresult($rows, $num_rows, $stmt->insert_id, $stmt->errno, $stmt->error);

    }

    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }

}

?>