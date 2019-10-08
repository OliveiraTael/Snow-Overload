<?php

require('../vendor/autoload.php');

//get user's wishlist total
use snow\WishList;
$wish = new WishList();

if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['action'] ) ){
  $product_id = $_GET['product_id'];
  if( $_GET['action'] == 'delete' ){
    $delete = $wish -> removeItem( $product_id );
  }
}

// get the total wishlist items for the navigation
$wish_total = $wish -> getWishListTotal();

// get the wishlist items for the page
$wish_items = $wish -> getWishListItemsSnowboards();

//create twig loader for templates
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment and pass the loader
$twig = new Twig_Environment($loader);

//call a twig template
$template = $twig -> load('wishlist_snowboard.twig');

//output the template and pass the data
echo $template -> render( array(
  'wish' => $wish_total,
  'wish_items' => $wish_items,
) );

?>