<?php

namespace DynDns\Datamodel;

class Zone extends DataObject {

    const TABLE_NAME = 'zones';

    const PROPERTY_ID = 'id';
    const PROPERTY_SYMNAME = 'symname';
    const PROPERTY_FILE = 'file';
    const PROPERTY_FILE_LAST_UPDATE = 'file_last_update';
    const PROPERTY_ORIGIN = 'origin';
    const PROPERTY_TTL = 'ttl';
    const PROPERTY_SOA_MNAME = 'soa_mname';
    const PROPERTY_SOA_RNAME = 'soa_rname';
    const PROPERTY_SOA_SERIAL_DATE = 'soa_serial_date';
    const PROPERTY_SOA_SERIAL_COUNTER = 'soa_serial_counter';
    const PROPERTY_SOA_REFRESH = 'soa_refresh';
    const PROPERTY_SOA_RETRY = 'soa_retry';
    const PROPERTY_SOA_EXPIRE = 'soa_expire';
    const PROPERTY_SOA_TTL = 'soa_ttl';

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
        self::PROPERTY_ORIGIN => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_TTL => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_MNAME => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_RNAME => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_SERIAL_DATE => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_SERIAL_COUNTER => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_REFRESH => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_RETRY => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_EXPIRE => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_SOA_TTL => array(
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

    public function getExtraRecords()
    {
        return ExtraRecord::byZone($this);
    }

    public function needsUpdate() {
        foreach ($this->getDomains() as $domain) {
            if ($domain->last_update >= $this->data[$this->fieldName(self::PROPERTY_FILE_LAST_UPDATE)])
                return true;
        }
        return false;
    }

}

?>