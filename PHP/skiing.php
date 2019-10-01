<?php

require('../vendor/autoload.php');

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use snow\ProductSki;

$products = new ProductSki();
$products_result = $products -> getProductsSki();

use snow\CategorySki;

$cat= new CategorySki();
$categories= $cat -> getCategories();

//create twig loader
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load twig template
$template = $twig -> load('product_ski.twig');

//pass values to twig
echo $template -> render([
    'categories' => $categories,
    'navigation' => $nav_items,
    'products' => $products_result,
    'title' => 'Snow Overload'
]);

?>