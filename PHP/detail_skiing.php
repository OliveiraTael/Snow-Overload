<?php

require('../vendor/autoload.php');

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use snow\WishList;
$wish = new WishList();
$wish_total = $wish -> getWishListTotal();

//get user's cart total
use snow\ShoppingCart;
$cart = new ShoppingCart();

// get the total cart items for the navigation
$cart_total = $cart -> getCartTotal();

// get the wishlist items for the page
$cart_items = $cart -> getCartItemsSkis();

if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['add'] ) ){
    $product_id = $_GET['product_id'];
    //if 'add' == 'list' means the wishlist button has been clicked
    if( $_GET['add'] == 'list' ){
        $add = $wish -> addItem($product_id);
    }
}
$wish_total = $wish -> getWishListTotal();

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
    'wish' => $wish_total,
    'cart' => $cart_total,
    'detail' => $detail,
    'title' => $detail['product']['name']
]);

?>