<?php
/**
 * @copyright   Copyright (c) 2023 TheMarketer.com
 * @project     TheMarketer.com
 * @website     https://themarketer.com/
 * @author      Alexandru Buzica (EAX LEX S.R.L.) <b.alex@eax.ro>
 * @license     http://opensource.org/licenses/osl-3.0.php - Open Software License (OSL 3.0)
 * @docs        https://themarketer.com/resources/api
 */

namespace Mktr\Tracker\Routes;

class Category
{
    private static $init = null;

    private static $map = array(
        "fileName" => "categories",
        "secondName" => "category"
    );

    private static $cat = array();

    public static function init()
    {
        if (self::$init == null) {
            self::$init = new self();
        }
        return self::$init;
    }

    public static function get($f = 'fileName'){
        if (isset(self::$map[$f]))
        {
            return self::$map[$f];
        }
        return null;
    }

    public static function execute()
    {
        $categoryTaxonomyName = apply_filters('marketer_override_product_category', 'product_cat');

        $args = array(
            'taxonomy' => $categoryTaxonomyName,
            'order' => 'DESC',
            'return' => 'ids'
        );

        $get = array();
        $cat = get_categories($args);

        foreach ($cat as $k=>$val)
        {
            $get[] = array(
                "name" => $val->name,
                "url" => get_term_link($val->slug, 'product_cat'),
                'id'=> $val->term_id,
                "hierarchy" => self::buildCategory($val),
                // "image_url" => $category->getImageUrl()
            );
        }



        return $get;
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public static function buildCategory($categoryRegistry = null)
    {
        $categoryTaxonomyName = apply_filters('marketer_override_product_category', 'product_cat');

        $build = array($categoryRegistry->name);
        if (property_exists($categoryRegistry, 'category_parent')) {
            while ($categoryRegistry->category_parent > 0) {
                $categoryRegistry = get_term_by('id', $categoryRegistry->category_parent, $categoryTaxonomyName);
                $build[] = $categoryRegistry->name;
            }
        }
        return implode("|", array_reverse($build));
    }
    public static function build($category){

        $newList = array(
            "name" => $category->getName(),
            // "url" => self::$url. $category->getUrlPath().'.html',
            'id'=> $category->getId(),
            // "hierarchy" => self::hierarchy($category),
            "image_url" => $category->getImageUrl()
        );

        if (empty($newList["image_url"])) {
            unset($newList["image_url"]);
        }

        // self::$data[] = $newList;
    }
}