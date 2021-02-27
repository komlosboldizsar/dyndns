<?php

namespace DynDns\Datamodel;

class Domain extends DataObject {

    const TABLE_NAME = 'domains';

    const PROPERTY_ID = 'id';
    const PROPERTY_ZONE_ID = 'zone_id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_IP_ADDRESS = 'ip_address';
    const PROPERTY_UPDATE_KEY_1 = 'update_key_1';
    const PROPERTY_UPDATE_KEY_2 = 'update_key_2';
    const PROPERTY_LAST_UPDATE = 'last_update';

    const PROPERTIES = array(
        self::PROPERTY_ID => array(
            'get' => '*auto*',
            'table_field' => 'id'
        ),
        self::PROPERTY_ZONE_ID => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_NAME => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_IP_ADDRESS => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_UPDATE_KEY_1 => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_UPDATE_KEY_2 => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_LAST_UPDATE => array(
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
        self::PROPERTY_NAME => DataObject::ORDER_ASC
    );

    public static function all($ordering = self::ORDERING_DEFAULT)
    {
        return DataObject::_all(get_class(), $ordering);
    }

    public static function filter($criteria = null, $ordering = self::ORDERING_DEFAULT)
    {
        return DataObject::_filter(get_class(), $criteria, $ordering);
    }

    public static function byZone($zone)
    {
        return static::filter(sprintf('zone_id = %d', $zone->id));
    }

    public function getZone() {
        return new Zone($this->data[$this->fieldName(self::PROPERTY_ZONE_ID)]);
    }

}

?>