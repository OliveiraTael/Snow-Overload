<?php

require('../vendor/autoload.php');

//get user's wishlist total
use snow\WishList;
$wish = new WishList();
$wish_total = $wish -> getWishListTotal();

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use snow\ProductSnowboard;

$products = new ProductSnowboard();
$products_result = $products -> getProductsSnowboard();

use snow\CategorySnowboard;

$cat= new CategorySnowboard();
$categories= $cat -> getCategories();

use snow\ShoppingCart;
$cart = new ShoppingCart();
$cart_total = $cart -> getCartTotal();

//create twig loader
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load twig template
$template = $twig -> load('product_snowboard.twig');

//pass values to twig
echo $template -> render([
    'categories' => $categories,
    'wish' => $wish_total,
    'navigation' => $nav_items,
    'cart_count' => $cart_total,
    'products' => $products_result,
    'title' => 'Snow Overload'
]);

?>