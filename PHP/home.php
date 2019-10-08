<?php

require('../vendor/autoload.php');

//get user's wishlist total
use snow\WishList;
$wish = new WishList();
$wish_total = $wish -> getWishListTotal();

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use snow\MostViewedProducts;

$products = new MostViewedProducts();
$products_result = $products -> getSnowboardOnSpecial();

$ski = new MostViewedProducts();
$ski_result = $ski -> getSkiOnSpecial();

//create twig loader
$loader = new Twig_Loader_Filesystem('../templates');
//create twig environment
$twig = new Twig_Environment($loader);

//load twig template
$template = $twig -> load('home.twig');

//pass values to twig
echo $template -> render([
    'wish' => $wish_total,
    'navigation' => $nav_items,
    'products' => $products_result,
    'ski' => $ski_result,
    'title' => 'Snow Overload'
]);

?>