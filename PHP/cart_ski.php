<?php

require('../vendor/autoload.php');

//get user's wishlist total
use snow\WishList;
$wish = new WishList();
$wish_total = $wish -> getWishListTotal();

//get user's cart total
use snow\ShoppingCart;
$cart = new ShoppingCart();

if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['action'] ) ){
  $product_id = $_GET['product_id'];
  if( $_GET['action'] == 'delete' ){
    $delete = $cart -> removeItem( $product_id );
  }
}

// get the total cart items for the navigation
$cart_total = $cart -> getCartTotal();

// get the cart items for the page
$cart_items = $cart -> getCartItemsSkis();

// create navigation
use snow\Navigation;
$nav = new Navigation();
$navigation = $nav -> getNavigation();

//create twig loader for templates
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment and pass the loader
$twig = new Twig_Environment($loader);

//call a twig template
$template = $twig -> load('cart_ski.twig');

//output the template and pass the data
echo $template -> render( array(
  'wish' => $wish_total,
  'navigation' => $navigation,
  'cart' => $cart_total,
  'cart_items' => $cart_items,
  'title' => "Cart List"
) );

?>