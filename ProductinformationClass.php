<?php

class ProductinformationClass extends ObjectModelCore
{
    public $id_product_information;
    public $name;
    public $content;
    public $image;
    public $value;
    public $active;

    public static $definition = array(
      'table' => 'product_information',
      'primary' => 'id_product_information',
      'multilang' => false,
      'fields' => array(
        'id_product_information'=> array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
        'name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
        'content' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
        'image' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
        'value' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
        'active' => array('type' => self::TYPE_INT, 'validate' => 'isBool', 'required' => true, )
      )
    );
}
