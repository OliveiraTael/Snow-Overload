<?php

require('../vendor/autoload.php');

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

//get the product id from url parameter
if( isset( $_GET['product_id'] ) == false ){
    echo "no parameter set";
    exit();
}
else{
    $product_id = $_GET['product_id'];
}

use snow\WishList;
$wish_list = new WishList();

use snow\ShoppingCart;
$cart = new ShoppingCart();

if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['add']) ){

    //check if add == list
    if( $_GET['add'] == 'list' ){
        $add_to_wish = $wish_list -> addItem( $_GET['product_id'] );
    }
    if( $_GET['add'] == 'cart' ){
        $add_to_cart = $cart -> addItem( $_GET['product_id'], $_GET['quantity']);
    }
}

$wish_total = $wish_list -> getWishListTotal();
$cart_total = $cart -> getCartTotal();

use snow\ProductDetailSki;

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
    'wish' => $wish_total,
    'cart_count' => $cart_total,
    'navigation' => $nav_items,
    'detail' => $detail,
    'title' => $detail['product']['name']
]);

?>