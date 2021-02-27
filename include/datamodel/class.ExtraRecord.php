<?php

namespace DynDns\Datamodel;

class ExtraRecord extends DataObject {

    const TABLE_NAME = 'extra_records';

    const PROPERTY_ID = 'id';
    const PROPERTY_ZONE_ID = 'zone_id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_VALUE = 'value';

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
        self::PROPERTY_TYPE => array(
            'get' => '*auto*',
            'set' => '*auto*',
            'table_field' => '*auto*'
        ),
        self::PROPERTY_VALUE => array(
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