<?php

require('../vendor/autoload.php');

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use snow\WishList;
$wish = new WishList();
$wish_total = $wish -> getWishListTotal();

if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['add'] ) ){
    $product_id = $_GET['product_id'];
    //if 'add' == 'list' means the wishlist button has been clicked
    if( $_GET['add'] == 'list' ){
        $add = $wish -> addItem($product_id);
    }
}
$wish_total = $wish -> getWishListTotal();

use snow\ProductDetailSnowboard;

//get the product id from url parameter

if(isset($_GET['product_id']) == false){
    echo "No parameter set";
    exit();
}

//create an instance of ProductDetail class
$pd = new ProductDetailSnowboard();
$detail = $pd -> getProductDetail($_GET['product_id']);

use snow\ShoppingCart;
$cart = new ShoppingCart();
$cart_total = $cart -> getCartTotal();

//create twig loader
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load twig template
$template = $twig -> load('detail_snowboard.twig');

//pass values to twig
echo $template -> render([
    'navigation' => $nav_items,
    'cart_count' => $cart_total,
    'wish' => $wish_total,
    'detail' => $detail,
    'title' => $detail['product']['name']
]);

?>