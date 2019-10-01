<?php

require('../vendor/autoload.php');

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use snow\ProductDetailSki;

//get the product id from url parameter

if(isset($_GET['product_id']) == false){
    echo "No parameter set";
    exit();
}

//create an instance of ProductDetail class
$pd = new ProductDetailSki();
$detail = $pd -> getProductDetail($_GET['product_id']);

//create twig loader
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load twig template
$template = $twig -> load('detail_ski.twig');

//pass values to twig
echo $template -> render([
    'navigation' => $nav_items,
    'detail' => $detail,
    'title' => $detail['product']['name']
]);

?>