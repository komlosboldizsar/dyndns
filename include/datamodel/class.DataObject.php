<?php

namespace DynDns\Datamodel;

use Webpage\Datamodel\Exceptions\PropertyNotExistsException;
use Webpage\MySQL\MySQLconnection;
use Webpage\MySQL\MySQLqueryparam;

abstract class DataObject {

    protected $id = null;
    protected $data = array();

    protected $initialized = false;
    protected $new = false;
    protected $notexisting = false;

    public function __construct($id)
    {
        $this->id = $id;
        if ($id === null)
            $this->new = true;
        $properties = $this->properties();
        foreach ($properties as $propertyName => $property) {
            if (isset($property['table_field']) && ($property['table_field'] !== null)) {
                $field = $property['table_field'];
                if ($field == '*auto*')
                    $field = $propertyName;
                $this->data[$field] = null;
            }
        }
        $this->init();
    }

    protected function loadFromDatabase() {

        $columns = array();
        $properties = $this->properties();
        foreach ($properties as $propertyName => $property) {
            if (isset($property['table_field']) && !empty($property['table_field'])) {
                $field = $property['table_field'];
                if ($field == '*auto*')
                    $field = $propertyName;
                $columns[] = '`' . $field . '`';
            }
        }

        $querystring = sprintf("SELECT %s FROM `%s` WHERE %s;",
            implode(', ', $columns),
            $this->tableName(),
            $this->databaseFilter());

        $result = MySQLconnection::defaultConnection()->query($querystring, array(), true, MySQLconnection::FETCH_ARRAY_ASSOC);
        if ($result->numRows() > 0) {
            $this->data = $result->getRow(0);
            foreach ($properties as $propertyName => $property)
                if (isset($property['convert_bool']) && ($property['convert_bool'] === true))
                    $this->data[$this->fieldName($propertyName)] = ($this->data[$this->fieldName($propertyName)] == 1);
            return true;
        } else {
            $this->notexisting = true;
            return false;
        }

    }

    protected function saveToDatabase() {
        if ($this->new)
            return $this->insertToDatabase();
        else
            return $this->updateInDatabase();
    }

    protected function insertToDatabase() {

        $columns = array();
        $values = array();
        $params = array();
        $properties = $this->properties();
        foreach ($properties as $propertyName => $property) {
            if (isset($property['table_field']) && !empty($property['table_field'])) {

                $field = $property['table_field'];
                if ($field == '*auto*')
                    $field = $propertyName;
                $columns[] = "`{$field}`";

                if (isset($this->data[$field]) && ($this->data[$field] !== null) && ($field != $this->idField())) {
                    $value = $this->data[$field];
                    if(is_int($value)) {
                        $type = MySQLqueryparam::INTEGER;
                    } else if (is_float($value) || is_double($value)) {
                        $type = MySQLqueryparam::DOUBLE;
                    } else if (is_bool($value)) {
                        $value = ($value) ? 1 : 0;
                        $type = MySQLqueryparam::INTEGER;
                    } else {
                        $type = MySQLqueryparam::STRING;
                    }
                    $values[] = "?";
                    $params[] = new MySQLqueryparam($type, $value);
                } else{
                    $values[] = "NULL";
                }

            }
        }

        $querystring = sprintf("INSERT INTO `%s`(%s) VALUES(%s);",
            $this->tableName(),
            implode(', ', $columns),
            implode(', ', $values));

        $query = MySQLconnection::defaultConnection()->query($querystring, $params);
        if ($query->isSuccessful()) {
            $iid = $query->insertID();
            $this->id = $iid;
            $this->data[$this->idField()] = $iid;
            $this->new = false;
        }

        return $query->isSuccessful();

    }

    protected function updateInDatabase() {

        $columns = array();
        $params = array();
        $properties = $this->properties();
        foreach ($properties as $propertyName => $property) {
            if (isset($property['table_field']) && !empty($property['table_field'])) {
                $field = $property['table_field'];
                if ($field == '*auto*')
                    $field = $propertyName;
                if ($field == $this->idField())
                    continue;
                if (isset($this->data[$field]) && ($this->data[$field] !== null)) {
                    $columns[] = "`{$field}` = ?";
                    $value = $this->data[$field];
                    if(is_int($value)) {
                        $type = MySQLqueryparam::INTEGER;
                    } else if (is_float($value) || is_double($value)) {
                        $type = MySQLqueryparam::DOUBLE;
                    } else if (is_bool($value)) {
                        $value = ($value) ? 1 : 0;
                        $type = MySQLqueryparam::INTEGER;
                    } else {
                        $type = MySQLqueryparam::STRING;
                    }
                    $params[] = new MySQLqueryparam($type, $value);
                } else{
                    $columns[] = "{$field} = NULL";
                }
            }
        }

        $querystring = sprintf("UPDATE `%s` SET %s WHERE %s;",
            $this->tableName(),
            implode(', ', $columns),
            $this->databaseFilter());

        return MySQLconnection::defaultConnection()->query($querystring, $params)->isSuccessful();

    }

    public function __set($name, $value)
    {

        $properties = $this->properties();

        if (!isset($properties[$name]))
            throw new Exceptions\PropertyNotExistsException($name, get_class($this));
        if (!isset($properties[$name]['set']))
            throw new Exceptions\PropertyNotSettableException($name, get_class($this));

        $setter = $properties[$name]['set'];

        if ($setter == '*auto*')
            $this->data[$name] = $value;
        else
            call_user_func_array(array($this, $setter), array($value));

    }

    public function __get($name)
    {

        $properties = $this->properties();

        if (!isset($properties[$name]))
            throw new Exceptions\PropertyNotExistsException($name, get_class($this));
        if (!isset($properties[$name]['get']))
            throw new Exceptions\PropertyNotGettableException($name, get_class($this));

        $getter = $properties[$name]['get'];

        if ($getter == '*auto*')
            return isset($this->data[$name]) ? $this->data[$name] : null;
        return call_user_func_array(array($this, $getter), array());

    }

    public function exists() {
        return !$this->notexisting;
    }

    protected abstract function properties();
    protected abstract function tableName();

    protected function databaseFilter() {
        return sprintf("`id` = %d", $this->id);
    }

    protected function idField() {
        return "id";
    }

    protected function init() {
        $this->setDefaults();
        if (!$this->new)
            $this->loadFromDatabase();
        $this->initialized = true;
    }

    protected function setDefaults() {
        $properties = $this->properties();
        foreach ($properties as $propertyName => $property) {
            if (isset($property['table_field']) && ($property['table_field'] !== null) && isset($property['default'])) {
                $field = $property['table_field'];
                if ($field == '*auto*')
                    $field = $propertyName;
                $this->data[$field] = $property['default'];
            }
        }
    }

    public function save() {
        return $this->saveToDatabase();
    }

    public static function _all($class, $ordering = array()) {
        return static::_filter($class, null, $ordering);
    }

    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    public static function _filter($class, $criteria = null, $ordering = array()) {

        $dummyObject = new $class(null);
        if (!is_subclass_of($dummyObject, get_class()))
            throw new \Exception();

        $criteriaStr = '';
        if (is_string($criteria))
            $criteriaStr = ' WHERE '.$criteria;
        else if (is_array($criteria))
            throw new \Exception("Arrays as criteria: not implemented.");

        $orderingStr = static::__orderingArrayToString($dummyObject, $ordering);

        $idsQueryString = sprintf('SELECT `%s` FROM %s%s%s;', $dummyObject->idField(), $dummyObject->tableName(), $criteriaStr, $orderingStr);
        $idsQuery = MySQLconnection::defaultConnection()->query($idsQueryString, array(), true, MySQLconnection::FETCH_ARRAY_INDEX);
        if (!$idsQuery->isSuccessful())
            return null;

        $resultObjects = array();
        foreach ($idsQuery as $idRow)
            $resultObjects[] = new $class($idRow[0]);
        return $resultObjects;

    }

    protected function _getRelatedByManyToMany($thisClass, $relatedClass, $relationTable, $thisIdColumn, $relatedIdColumn, $criteria = null, $ordering = array()) {

        $dummyThisObject = new $thisClass(null);
        if (!is_subclass_of($dummyThisObject, get_class()))
            throw new \Exception();

        $dummyRelatedObject = new $relatedClass(null);
        if (!is_subclass_of($dummyRelatedObject, get_class()))
            throw new \Exception();

        $criteriaStr = '';
        if (is_string($criteria))
            $criteriaStr = ' AND ('.$criteria.')';
        else if (is_array($criteria))
            throw new \Exception("Arrays as criteria: not implemented.");

        $orderingStr = static::__orderingArrayToString($dummyRelatedObject, $ordering);

        $idsQueryString = sprintf('SELECT `%s` FROM `%s` INNER JOIN %s ON `%s`.`%s` = `%s`.`%s` WHERE `%s`.`%s` = %d%s%s;',
            $dummyRelatedObject->idField(),   // SELECT %s
            $dummyRelatedObject->tableName(), // FROM %s
            $relationTable,                   // INNER JOIN %s
            $dummyRelatedObject->tableName(), // ON %s.%s
            $dummyRelatedObject->idField(),
            $relationTable,                   // = %s.%s
            $relatedIdColumn,
            $relationTable,                   // WHERE %s.%s = %d
            $thisIdColumn,
            $this->id,
            $criteriaStr,                     // more criteria
            $orderingStr);

        $idsQuery = MySQLconnection::defaultConnection()->query($idsQueryString, array(), true, MySQLconnection::FETCH_ARRAY_INDEX);
        if (!$idsQuery->isSuccessful())
            return null;

        $resultObjects = array();
        foreach ($idsQuery as $idRow)
            $resultObjects[] = new $relatedClass($idRow[0]);
        return $resultObjects;

    }

    protected static function __orderingArrayToString($dummyObject, $ordering = array()) {

        if (!is_array($ordering))
            throw new \InvalidArgumentException();
        $orderingClauses = array();
        foreach ($ordering as $field => $direction) {
            $tableFieldName = '';
            if (substr($field, 0, 1) != '@') {
                if (!array_key_exists($field, $dummyObject->properties()))
                    throw new PropertyNotExistsException();
                $tableFieldName = $dummyObject->properties()[$field]['table_field'];
                if ($tableFieldName == '*auto*')
                    $tableFieldName = $field;
            } else {
                $tableFieldName = substr($field, 1);
            }
            $orderingClauses[] = "`$tableFieldName` $direction";
        }

        if(!empty($orderingClauses))
            return ' ORDER BY ' . implode(', ', $orderingClauses);
        return '';

    }

    protected function fieldName($field) {
        $properties = $this->properties();
        if (!isset($properties[$field]))
            return null;
        if ($properties[$field]['table_field'] != "*auto*")
            return $properties[$field]['table_field'];
        return $field;
    }

    const DTM_OBJECT = 'object';
    const DTM_ARRAY = 'array';

    public function getData($properties = null, $dataTransferMethod = self::DTM_OBJECT)
    {

        if ($properties === null) {
            $properties = array();
            foreach ($this->properties() as $propertyName => $propertyData)
                $properties[] = $propertyName;
        }

        if ($dataTransferMethod == self::DTM_OBJECT) {
            $returnObject = new \stdClass();
            foreach ($properties as $propertyName)
                $returnObject->{$propertyName} = $this->__get($propertyName);
            return $returnObject;
        }

        if ($dataTransferMethod == self::DTM_ARRAY) {
            $returnArray = array();
            foreach ($properties as $propertyName)
                $returnArray[$propertyName] = $this->__get($propertyName);
            return $returnArray;
        }

        return null;

    }

    public function setData($values)
    {

        if (!is_object($values) && !is_array($values))
            throw new \InvalidArgumentException();

        if (is_object($values))
            $values = get_object_vars($values);

        foreach ($values as $key => $value)
            $this->__set($key, $value);

    }

}

?>