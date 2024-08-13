<?php
/**
 * @copyright   Copyright (c) 2023 TheMarketer.com
 * @project     TheMarketer.com
 * @website     https://themarketer.com/
 * @author      Alexandru Buzica (EAX LEX S.R.L.) <b.alex@eax.ro>
 * @license     http://opensource.org/licenses/osl-3.0.php - Open Software License (OSL 3.0)
 * @docs        https://themarketer.com/resources/api
 */

namespace Mktr\Tracker\Model;

/**
 * @method static getId()
 * @method static getName()
 * @method static getParentId()
 */

class Category
{
    private static $init = null;
    private static $asset = null;

    private static $valueNames = array(
        'getId' => 'term_id',
        'getName' => 'name',
        'getParentId' => 'parent'
    );

    public static function init()
    {
        if (self::$init == null) {
            self::$init = new self();
        }
        return self::$init;
    }

    public static function __callStatic($name, $arguments)
    {
        return self::getValue($name);
    }

    public function __call($name, $arguments)
    {
        return self::getValue($name);
    }

    public static function getValue($name)
    {
        if (self::$asset == null){
            self::getById();
        }

        if (isset(self::$valueNames[$name]))
        {
            $v = self::$valueNames[$name];
            return self::$asset->{$v};
        }
        return null;

    }
    public static function getById($id = null)
    {
        if ($id == null)
        {
            $id = get_queried_object()->term_id;
        }
        
        $taxonomyName = apply_filters('marketer_override_product_category', 'product_cat');
        
        self::$asset = get_term_by('id', $id, $taxonomyName);
        return self::init();
    }

}