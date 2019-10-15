<?php

require('../vendor/autoload.php');

use snow\Search;

if( isset($_GET['query']) ){
  $search = new Search();
  $ski_search = new Search();
  $result = $search -> getSearchResult();
  $ski_result = $ski_search -> getResults();
}
else{
  $result = '';
}

use snow\Navigation;
$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use snow\WishList;
$wish_list = new WishList();
$wish_total = $wish_list -> getWishListTotal();

use snow\ShoppingCart;
$cart = new ShoppingCart();
$cart_total = $cart -> getCartTotal();

//create twig loader
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load twig template
$template = $twig -> load('search.twig');

//pass values to twig
echo $template -> render( array(
    'result' => $result,
    'wish_count' => $wish_total,
    'cart_count' => $cart_total,
    'ski_result' => $ski_result,
    'navigation' => $nav_items,
    'title' => "Search Result for " . $result['query']
  ) );

?>