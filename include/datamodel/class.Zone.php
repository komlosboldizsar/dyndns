<?php

namespace DynDns\Datamodel;

class Zone extends DataObject {

    const TABLE_NAME = 'zones';

    const PROPERTY_ID = 'id';
    const PROPERTY_SYMNAME = 'symname';
    const PROPERTY_FILE = 'description';
    const PROPERTY_FILE_LAST_UPDATE = 'file_last_update';
    const PROPERTY_MNAME = 'products';
    const PROPERTY_RNAME = 'ordering';
    const PROPERTY_SERIAL_DATE = 'serial_date';
    const PROPERTY_SERIAL_COUNTER = 'serial_counter';
    const PROPERTY_REFRESH = 'refresh';
    const PROPERTY_RETRY = 'retry';
    const PROPERTY_EXPIRE = 'expire';
    const PROPERTY_TTL = 'ttl';

    const PROPERTIES = array(
        self::PROPERTY_ID => array(
            'get' => '*auto*',
            'table_field' => 'id'
        ),
        self::PROPERTY_SYMNAME => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_FILE => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_FILE_LAST_UPDATE => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_MNAME => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_RNAME => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SERIAL_DATE => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SERIAL_COUNTER => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_REFRESH => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_RETRY => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_EXPIRE => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_TTL => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        )
    );

    protected function tableName()
    {
        return self::TABLE_NAME;
    }

    protected function properties()
    {
        return self::PROPERTIES;
    }

    const ORDERING_DEFAULT = array(
        self::PROPERTY_SYMNAME => DataObject::ORDER_ASC
    );

    public static function all($ordering = self::ORDERING_DEFAULT)
    {
        return DataObject::_all(get_class(), $ordering);
    }

    public static function filter($criteria = null, $ordering = self::ORDERING_DEFAULT)
    {
        return DataObject::_filter(get_class(), $criteria, $ordering);
    }

    public function getDomains()
    {
        return Domain::byZone($this);
    }

}

?>